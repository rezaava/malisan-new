<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Discussion;
use App\Models\Exercise;
use App\Models\Question;
use App\Models\Role;
use App\Models\Scoring;
use App\Models\session;
use App\Models\Setting;
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
            // ===== بررسی active بودن کل دوره =====
            if ($course->active == 1) {
                // فقط جلسات فعال رو بگیر
                $sessions = $course->sessions->where('active', 1);
                
                if ($sessions->isEmpty()) {
                    $sessions = collect();
                } else {
                    $totalSessionsCount = $sessions->count();

                    // فیلتر بر اساس عضویت
                    if ($member == 1 && $course->private == 1) {
                        $now = Carbon::now();
                        $time = Carbon::parse($courseUser->created_at);
                        $diffInDays = $time->diffInDays($now);
                        $availableCount = $totalSessionsCount - floor($diffInDays / $course->period) - 1;

                        if ($availableCount > 0) {
                            $filteredSessions = collect();
                            $index = 0;
                            foreach ($sessions as $session) {
                                if ($index < $availableCount) {
                                    $filteredSessions->push($session);
                                }
                                $index++;
                            }
                            $sessions = $filteredSessions;
                        } else {
                            $sessions = collect();
                        }
                    }

                    // فیلتر برای کاربر غیرعضو
                    if ($member == 0) {
                        $sessions = $sessions->filter(function ($session) {
                            return $session->number == 1;
                        });
                    } 
                    // فیلتر برای کاربری که پرداخت نکرده
                    elseif ($paid == 0) {
                        $sessions = $sessions->filter(function ($session) {
                            return $session->number <= 4;
                        });
                    }
                }
            } else {
                // اگر دوره غیرفعال بود، هیچ جلسه‌ای نشون نده
                $sessions = collect();
            }
        } else {
            // برای استاد همه جلسات رو نشون بده (حتی غیرفعال)
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

        // ===== اضافه کردن وضعیت دسترسی به هر جلسه =====
        if (!$sessions->isEmpty()) {
            // پیدا کردن آخرین جلسه
            $lastSession = Session::where('course_id', $course->id)
                ->orderBy('id', 'desc')
                ->first();
            
            foreach ($sessions as $index => $session) {
                $session['ex_count'] = Exercise::where('session_id', $session->id)->count();
                
                // تنظیم وضعیت‌های پیش‌فرض
                $session['taklif_last'] = 1;
                $session['gozaresh_last'] = 1;
                $session['soal_last'] = 1;
                
                // تنظیم وضعیت‌های دسترسی برای دکمه‌ها
                $session['can_question'] = true;
                $session['can_homework'] = true;
                $session['can_report'] = true;

                // اگر تنظیمات وجود دارد و محدودیت فعال است
                if ($setting) {
                    // محدودیت طرح سوال به آخرین جلسه
                    if ($setting->soal_last == 1 && $isStudent) {
                        if (!$lastSession || $session->id != $lastSession->id) {
                            $session['can_question'] = false;
                            $session['soal_last'] = 0;
                        }
                    }
                    
                    // محدودیت ارسال تکلیف به آخرین جلسه
                    if ($setting->taklif_last == 1) {
                        if (!$lastSession || $session->id != $lastSession->id) {
                            $session['can_homework'] = false;
                            $session['taklif_last'] = 0;
                        }
                    }
                    
                    // محدودیت ارسال گزارش به آخرین جلسه
                    if ($setting->gozaresh_last == 1) {
                        if (!$lastSession || $session->id != $lastSession->id) {
                            $session['can_report'] = false;
                            $session['gozaresh_last'] = 0;
                        }
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

            // بررسی خصوصی بودن دوره
            if ($course->private == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'این دوره خصوصی است و امکان عضویت با کد وجود ندارد'
                ], 403);
            }

            // بررسی آرشیو بودن دوره
            if ($course->archive == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'این دوره آرشیو شده و امکان عضویت در آن وجود ندارد'
                ], 403);
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

            // ==========================================
            // ایجاد Scoring برای دانشجو با مقادیر پیش‌فرض ۰
            // ==========================================
            $scoring = Scoring::create([
                'course_id' => $course->id,
                'user_id' => $user->id,
                'q_1' => 0,
                'q_2' => 0,
                'q_3' => 0,
                'q_4' => 0,
                'd_1' => 0,
                'd_2' => 0,
                'd_3' => 0,
                'd_4' => 0,
                'e_1' => 0,
                'e_2' => 0,
                'e_3' => 0,
                'e_4' => 0,
                's_1' => 0,
                's_2' => 0,
                's_3' => 0,
                's_4' => 0,
            ]);

            // همچنین اطمینان از وجود Setting برای دوره (اگر وجود نداشت)
            Setting::firstOrCreate(
                ['course_id' => $course->id],
                ['course_id' => $course->id]
            );

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'عضویت با موفقیت انجام شد',
                'course_name' => $course->name,
                'redirect' => route('view.coure.St', $course->id)
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
