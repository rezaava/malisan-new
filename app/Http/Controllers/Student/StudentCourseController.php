<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Discussion;
use App\Models\Exercise;
use App\Models\Question;
use App\Models\Role;
use App\Models\session;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

class StudentCourseController extends Controller
{
    public function view($id)
    {
        $course = Course::with(['sessions' => function ($query) {
            $query->orderBy('number', 'desc');
        }, 'settings'])->findOrFail($id);

        $user = Auth::user();
        $isStudent = $user->hasRole('student');
        $setting = $course->settings;

        $courseUser = CourseUser::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->first();
        
        $member = ($courseUser) ? 1 : 0;
        $paid = ($course->price == 0 || ($courseUser && $courseUser->paid == 1)) ? 1 : 0;

        $sessionIdsForJudgment = Session::where('course_id', $id)->pluck('id');
        $pendingQuestionsCount = Question::whereNull('status')
            ->whereIn('session_id', $sessionIdsForJudgment)
            ->count();
        $pendingDiscussionsCount = Discussion::whereNull('status')
            ->whereIn('session_id', $sessionIdsForJudgment)
            ->count();
        $isJudment = ($pendingQuestionsCount > 0 || $pendingDiscussionsCount > 0);

        if ($isStudent) {
            $sessions = $course->sessions->where('active', 1);
            
            if ($sessions->isEmpty()) {
                $sessions = collect();
            } else {
                $totalSessionsCount = $sessions->count();

                if ($member == 1 && $course->private == 1) {
                    $now = Carbon::now();
                    $time = Carbon::parse($courseUser->created_at);
                    $diffInDays = $time->diffInDays($now);
                    $availableCount = $totalSessionsCount - floor($diffInDays / $course->period) - 1;

                    if ($availableCount > 0) {
                        $sessions = $sessions->filter(function ($session, $index) use ($availableCount) {
                            return $index < $availableCount;
                        });
                    } else {
                        $sessions = collect();
                    }
                }

                if ($member == 0) {
                    $sessions = $sessions->filter(function ($session) {
                        return $session->number == 1;
                    });
                } elseif ($paid == 0) {
                    $sessions = $sessions->filter(function ($session) {
                        return $session->number <= 4;
                    });
                }
            }
        } else {
            $sessions = $course->sessions;
            
            if ($sessions->isEmpty()) {
                $sessions = collect();
            }
        }

        $khodazmaii = 0;
        
        if (!$sessions->isEmpty() && $setting) {
            $sessionIds = $sessions->pluck('id');
            
            $statusFilter = match ($setting->sath_khod) {
                1 => [1],
                2 => [1, 2],
                3 => [2],
                default => null,
            };

            if ($statusFilter) {
                $questionCount = Question::whereIn('session_id', $sessionIds)
                    ->whereIn('status', $statusFilter)
                    ->count();
                
                $khodazmaii = ($questionCount >= $setting->q_num) ? 1 : 0;
            }
        }

        if (!$sessions->isEmpty()) {
            foreach ($sessions as $index => $session) {
                $session['ex_count'] = Exercise::where('session_id', $session->id)->count();
                $session['taklif_last'] = 1;
                $session['gozaresh_last'] = 1;
                $session['soal_last'] = 1;

                if ($index != 0) {
                    if ($setting && $setting->taklif_last == 1) {
                        $session['taklif_last'] = 0;
                    }
                    if ($setting && $setting->gozaresh_last == 1) {
                        $session['gozaresh_last'] = 0;
                    }
                    if ($isStudent && $setting && $setting->soal_last == 1) {
                        $session['soal_last'] = 0;
                    }
                }
            }
        }

        $studentRole = Role::where('name', 'student')->first();
        $teacherRole = Role::where('name', 'teacher')->first();

        $course['sessions'] = $course->sessions()->count();
        $course['count'] = ($studentRole) 
            ? $course->users()->where('role_id', $studentRole->id)->count() 
            : 0;

        if ($teacherRole) {
            $teacher = $course->users()->where('role_id', $teacherRole->id)->pluck('user_id')->first();
            $course['user'] = $teacher ? User::findOrFail($teacher) : null;
        }

        $course['students'] = ($studentRole)
            ? $course->users()->where('role_id', $studentRole->id)->take(5)->get()
            : collect();

        return view('student.course', compact(
            'setting',
            'khodazmaii',
            'sessions',
            'course',
            'isJudment',
            'member',
            'paid'
        ))->with([
            'pageTitle' => 'صفحه مدیریت درس',
            'pageName' => 'درس',
            'pageDescription' => $isStudent 
                ? "دوست من ! اینجا صفحه مدیریت کلاس درسته" 
                : "مدرس گرامی ! داشبورد مدیریت درس در اختیار شماست",
            'student' => (int) $isStudent,
        ]);
    }
    public function join(Request $request)
    {
        // اعتبارسنجی
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10',
        ], [
            'code.required' => 'لطفاً کد درس را وارد کنید',
            'code.max' => 'کد درس نباید بیشتر از ۱۰ کاراکتر باشد',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $studentRole = Role::where('name', 'student')->first();

            // پیدا کردن دوره با کد
            $course = Course::where('code', $request->code)->first();

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'کد درس وارد شده نامعتبر است'
                ], 404);
            }

            // بررسی تکراری بودن عضویت
            $exists = $user->courses()->where('course_id', $course->id)->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'شما قبلاً در این کلاس عضو هستید'
                ], 409);
            }

            // عضویت دانشجو در دوره
            $course->users()->attach($user, ['role_id' => $studentRole->id]);

            // تراکنش مالی (در صورت نیاز)
            // $this->anetoTrans($user, 2000, 5, 'ورود درس ' . $course->name);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'عضویت با موفقیت انجام شد',
                'course_name' => $course->name,
                'redirect' => route('view.coure', $course->id)
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::error('Join course failed: ' . $exception->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'خطایی در سرور رخ داده است: ' . $exception->getMessage()
            ], 500);
        }
    }
}
