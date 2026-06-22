<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Role;
use App\Models\Scoring;
use App\Models\Session;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\CourseUser;
use App\Models\Discussion;
use App\Models\Exercise;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;

class CourseController extends Controller
{
    /**
     * نمایش صفحه مدیریت درس
     */
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

        return view('teacher.course', compact(
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

    /**
     * نمایش فرم ایجاد جلسه جدید (GET)
     */
    public function create($id)
    {
        $course = Course::findOrFail($id);
        $sessionsCount = Session::where('course_id', $course->id)->count();
        $nextSessionNumber = $sessionsCount + 1;

        return view('create-session', compact('course', 'nextSessionNumber'))->with([
            'pageTitle' => 'صفحه افزودن جلسه',
            'pageName' => 'افزودن جلسه',
            'pageDescription' => 'مدرس گرامی! برای ایجاد جلسه جدید لطفا فرم زیر را تکمیل نمایید.',
        ]);
    }

    /**
     * ذخیره جلسه جدید (POST)
     */
    public function store(Request $request, $id)
    {
        // اعتبارسنجی
        $validator = Validator::make($request->all(), [
            'number' => 'required|numeric|min:1',
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            'link' => 'nullable|url|max:500',
            'majazi' => 'nullable|url|max:500',
            'aparat' => 'nullable|string|max:500',
            'text' => 'nullable|string',
            'active' => 'nullable|in:on,1',
        ], [
            'file.mimes' => 'فرمت فایل باید PDF، Word یا PowerPoint باشد',
            'file.max' => 'حجم فایل نباید بیشتر از 20 مگابایت باشد',
            'link.url' => 'لینک وارد شده معتبر نیست',
            'majazi.url' => 'لینک وارد شده معتبر نیست',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $course = Course::findOrFail($id);
            
            $session = new Session();
            $session->name = $request->name;
            $session->text = $request->text;
            $session->number = $request->number;
            $session->course_id = $course->id;
            $session->active = $request->has('active') ? 1 : 0;
            
            $session->majazi = $this->cleanUrl($request->majazi);
            $session->link = $this->cleanUrl($request->link);
            $session->aparat = $request->aparat;

            // آپلود فایل
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $id . "_" . time() . "." . $file->getClientOriginalExtension();
                $destinationPath = public_path('files/session');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $file->move($destinationPath, $fileName);
                $session->file = '/' . $fileName; // اصلاح مسیر
            }

            $session->save();

            DB::commit();

            return redirect('/teacher/courses/view/' . $course->id)
                ->with('success', 'جلسه جدید با موفقیت ایجاد شد');

        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::error('Session creation failed: ' . $exception->getMessage());
            return back()->with('error', 'خطایی در سرور رخ داده است: ' . $exception->getMessage());
        }
    }

    /**
     * کپی کردن جلسات از دوره دیگر
     */
    public function getCopyData($id)
    {
        try {
            $course = Course::findOrFail($id);
            
            $user = Auth::user();
            $teacherRole = Role::where('name', 'teacher')->first();
            
            $isOwner = DB::table('course_user')
                ->where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('role_id', $teacherRole->id)
                ->exists();
                
            if (!$isOwner) {
                return response()->json([
                    'error' => 'شما دسترسی به این درس ندارید'
                ], 403);
            }
            
            return response()->json([
                'id' => $course->id,
                'name' => $course->name,
                'majazi' => $course->majazi,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطا در دریافت اطلاعات درس'
            ], 500);
        }
    }

    /**
     * ایجاد دوره جدید
     */
    public function storeCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'majazi' => 'nullable|url|max:255',
            'copy' => 'nullable|exists:courses,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $teacherRole = Role::where('name', 'teacher')->first();
            
            $code = $this->generateUniqueCode();
            
            $course = new Course();
            $course->name = $request->name;
            $course->majazi = $this->cleanUrl($request->majazi);
            $course->max_session = 16;
            $course->code = $code;
            $course->save();
            
            $this->createCourseAssociations($course);
            $course->users()->attach($user, ['role_id' => $teacherRole->id]);
            
            if ($request->filled('copy')) {
                $this->copyCourseSessions($request->copy, $course->id);
            }
            
            DB::commit();
            
            $message = "دانشجوی عزیز، برای دسترسی به درس " . $course->name . 
                      " ابتدا از طریق سایت WWW.MALISAN.IR در سامانه آموزشی ملیسان با هویت واقعی ثبت نام کنید، سپس با استفاده از شناسه " . 
                      $course->code . " در درس ذکر شده عضو شوید.";
            
            return redirect()->route('courses')
                ->with('success', $message)
                ->with('crete', 'ok');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Course creation failed: ' . $e->getMessage());
            return back()->with('error', 'خطایی در سرور رخ داده است: ' . $e->getMessage());
        }
    }

    /**
     * نمایش فرم ایجاد جلسه (مسیر قدیمی)
     */
    public function createSession()
    {
        return view('create-session');
    }

    // ==========================================
    // متدهای کمکی (Helper Methods)
    // ==========================================

    private function generateUniqueCode(): string
    {
        do {
            $code = Str::random(5);
        } while (Course::where('code', $code)->exists());
        
        return $code;
    }

    private function cleanUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }
        
        $url = trim($url);
        $url = str_replace(['http://', 'https://'], '', $url);
        
        return $url ?: null;
    }

    private function createCourseAssociations(Course $course): void
    {
        Setting::create(['course_id' => $course->id]);
        Scoring::create(['course_id' => $course->id]);
    }

    private function copyCourseSessions(int $sourceCourseId, int $targetCourseId): void
    {
        $sessions = Session::where('course_id', $sourceCourseId)
            ->orderBy('id', 'asc')
            ->get();
        
        if ($sessions->isEmpty()) {
            return;
        }
        
        foreach ($sessions as $index => $session) {
            Session::create([
                'course_id' => $targetCourseId,
                'text' => $session->text,
                'file' => $session->file,
                'link' => $session->link,
                'majazi' => $session->majazi,
                'active' => $index === 0 ? 1 : 0,
                'number' => $session->number,
                'name' => $session->name,
            ]);
        }
    }
}