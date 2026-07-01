<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Amali;
use App\Models\Answer;
use App\Models\Azmon;
use App\Models\Course;
use App\Models\ExerciseAnswer;
use App\Models\QuestionReport;
use App\Models\Quiz;
use App\Models\Role;
use App\Models\Score;
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
use Log;

class CourseController extends Controller
{
    public function reportsList($courseId)
    {
        $course = Course::findOrFail($courseId);
        $sessionIds = $course->sessions()->pluck('id');
        
        // دریافت همه گزارش‌های این درس به همراه اطلاعات کاربر و جلسه
        $reports = Discussion::whereIn('session_id', $sessionIds)
            ->with(['user', 'session'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // آمار گزارش‌ها
        $stats = [
            'total' => $reports->count(),
            'pending' => $reports->whereNull('status')->count(),
            'approved' => $reports->whereIn('status', [1, 2])->count(),
            'rejected' => $reports->whereIn('status', [3, 4])->count(),
        ];

        return view('teacher.reports-list', compact('course', 'reports', 'stats'));
    }
    public function getReportDetail($reportId)
    {
        $report = Discussion::with(['user', 'session.course'])->findOrFail($reportId);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $report->id,
                'title' => $report->title,
                'text' => $report->text,
                'status' => $report->status,
                'created_at' => \Hekmatinasser\Verta\Verta::instance($report->created_at)->format('Y/m/d H:i'),
                'user' => [
                    'name' => $report->user->name ?? 'نامشخص',
                    'family' => $report->user->family ?? '',
                ],
                'session' => [
                    'name' => $report->session->name ?? 'نامشخص',
                    'course_id' => $report->session->course_id ?? null,
                ],
            ]
        ]);
    }
    public function studentActivities(Course $course)
    {
        $role = Role::where('name', 'student')->first();
        $users = $course->users()
            ->where('role_id', $role->id)
            ->orderBy('family', 'asc')
            ->get();

        // گرفتن آی‌دی همه جلسات این درس
        $sessionIds = $course->sessions()->pluck('id');

        foreach ($users as $user) {
            // تعداد سوالات دانشجو در این درس
            $user->questions_count = Question::where('user_id', $user->id)
                ->whereIn('session_id', $sessionIds)
                ->count();

            // تعداد گزارش‌ها (بحث‌ها) در این درس
            $user->reports_count = Discussion::where('user_id', $user->id)
                ->whereIn('session_id', $sessionIds)
                ->count();

            // تعداد تکالیف انجام شده در این درس
            $user->homeworks_count = ExerciseAnswer::where('user_id', $user->id)
                ->whereIn('exercise_id', Exercise::whereIn('session_id', $sessionIds)->pluck('id'))
                ->count();

            // تعداد خودآزمایی‌ها در این درس
            $user->self_tests_count = Quiz::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->whereNull('azmon_id')
                ->count();

            // تعداد آزمون‌های رسمی در این درس
            $user->official_exams_count = Quiz::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->whereNotNull('azmon_id')
                ->count();
        }

        return view('teacher.student-activities', compact('course', 'users'));
    }

    /**
     * سوالات یک دانشجو
     */
    public function studentQuestions($userId)
    {
        $user = User::findOrFail($userId);
        $questions = Question::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.student-questions', compact('user', 'questions'));
    }

    /**
     * گزارشات یک دانشجو
     */
    public function studentReports($userId)
    {
        $user = User::findOrFail($userId);
        $reports = Discussion::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.student-reports', compact('user', 'reports'));
    }

    /**
     * تکالیف یک دانشجو
     */
    public function studentHomeworks($userId)
    {
        $user = User::findOrFail($userId);
        $homeworks = ExerciseAnswer::where('user_id', $userId)
            ->with('exercise')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.student-homeworks', compact('user', 'homeworks'));
    }

    /**
     * خودآزمایی‌های یک دانشجو
     */
    public function studentSelfTests($userId)
    {
        $user = User::findOrFail($userId);
        $selfTests = Quiz::where('user_id', $userId)
            ->whereNull('azmon_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.student-self-tests', compact('user', 'selfTests'));
    }

    /**
     * آزمون‌های رسمی یک دانشجو
     */
    public function studentOfficialExams($userId)
    {
        $user = User::findOrFail($userId);
        $officialExams = Quiz::where('user_id', $userId)
            ->whereNotNull('azmon_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.student-official-exams', compact('user', 'officialExams'));
    }

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

        $sessionIds = Session::where('course_id', $id)->pluck('id');
        $questionIds = Question::whereIn('session_id', $sessionIds)->pluck('id');
        $reportCount = QuestionReport::whereIn('question_id', $questionIds)
            ->where('status', 'pending')
            ->count();

        return view('teacher.course', compact(
            'setting',
            'khodazmaii',
            'sessions',
            'course',
            'isJudment',
            'member',
            'reportCount',
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

        return view('teacher.create-session', compact('course', 'nextSessionNumber'))->with([
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

    public function studentsList($id)
    {
        $course = Course::findOrFail($id);
        $role = Role::where("name", "student")->first();
        
        $users = $course->users()
            ->wherePivotNull('deleted_at')
            ->where('role_id', $role->id)
            ->orderBy('family', 'asc')
            ->withCount(['studentEvents', 'studentAdjectives'])
            ->get();

        $setting = Setting::where('course_id', $course->id)->first();

        foreach ($users as $user) {
            // نمره عملی
            $nomre = Amali::where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->where('type', 2)
                ->first();
            $user->nomre = $nomre ? $nomre->nomre : 0;

            // نمره نهایی
            $final = Amali::where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->where('type', 1)
                ->first();
            $user->final = $final ? $final->nomre : 0;

            // وضعیت آنلاین
            $user->online = $user->isOnline() ? 1 : 0;
        }
        $removedCount = CourseUser::onlyTrashed()
            ->where('course_id', $course->id)
            ->where('role_id', $role->id)
            ->count();

        return view('teacher.students-list', compact('users', 'course', 'setting','removedCount'))->with([
            'pageTitle' => 'صفحه دانشجویان دروس',
            'pageName' => 'دانشجویان درس',
            'pageDescription' => 'مدرس گرامی ! لیست دانشجو های شما به شرح زیر می باشد',
        ]);
    }
    public function studentProfile($id)
    {
        $user = User::findOrFail($id);
        
        // بررسی اینکه کاربر دانشجو باشد
        if (!$user->hasRole('student')) {
            return redirect()->back()->with('error', 'این کاربر دانشجو نیست');
        }
        
        return view('teacher.student-profile', compact('user'))->with([
            'pageTitle' => 'پروفایل دانشجو',
            'pageName' => 'پروفایل دانشجو',
            'pageDescription' => 'مشخصات دانشجو',
        ]);
    }
    public function updateStudentProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'family' => 'nullable|string|max:255',
            'national' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|min:8',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            $data = $request->except(['_token', '_method', 'password', 'image']);
            
            // تغییر رمز عبور
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }
            
            // آپلود عکس
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('files/users');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $file->move($destinationPath, $fileName);
                $data['image'] = '/files/users/' . $fileName;
            }
            
            $user->update($data);
            
            return redirect()->back()->with('success', 'پروفایل با موفقیت به‌روزرسانی شد');
            
        } catch (\Exception $e) {
            \Log::error('Update profile failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'خطا در به‌روزرسانی پروفایل');
        }
    }

    public function destroyUser($userId, $courseId)
    {
        try {
            // پیدا کردن رکورد عضویت دانشجو در دوره
            $courseUser = CourseUser::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->first();

            if (!$courseUser) {
                return redirect()->back()->with('error', 'این دانشجو در این دوره عضویت ندارد');
            }

            // دریافت اطلاعات برای لاگ
            $user = User::find($userId);
            $course = Course::find($courseId);

            // انجام Soft Delete
            $courseUser->delete();

            // لاگ کردن (اختیاری)
            \Log::info("Student removed from course", [
                'student_id' => $userId,
                'student_name' => $user->name . ' ' . $user->family,
                'course_id' => $courseId,
                'course_name' => $course->name,
                'teacher_id' => Auth::id(),
                'teacher_name' => Auth::user()->name . ' ' . Auth::user()->family,
                'removed_at' => now()
            ]);

            return redirect()->back()->with('success', 'دانشجو با موفقیت از دوره اخراج شد');

        } catch (\Exception $e) {
            \Log::error('Remove student failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'خطا در اخراج دانشجو: ' . $e->getMessage());
        }
    }

    /**
     * تغییر وضعیت فعال/غیرفعال (private)
     */
    public function toggleStatus($id)
    {
        try {
            $course = Course::findOrFail($id);
            
            // تغییر وضعیت private (1 -> 0 یا 0 -> 1)
            $course->private = $course->private == 1 ? 0 : 1;
            $course->save();
            
            $status = $course->private == 1 ? 'فعال' : 'غیرفعال';
            
            return response()->json([
                'success' => true,
                'message' => "وضعیت دوره با موفقیت به {$status} تغییر یافت",
                'private' => $course->private,
                'status_text' => $status
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Toggle course status failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا در تغییر وضعیت دوره: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تغییر وضعیت آرشیو
     */
    public function toggleArchive($id)
    {
        try {
            $course = Course::findOrFail($id);
            
            // تغییر وضعیت archieve (1 -> 0 یا 0 -> 1)
            $course->archieve = $course->archieve == 1 ? 0 : 1;
            $course->save();
            
            $status = $course->archieve == 1 ? 'آرشیو شده' : 'فعال';
            
            return response()->json([
                'success' => true,
                'message' => "دوره با موفقیت {$status} شد",
                'archieve' => $course->archieve,
                'status_text' => $status
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Toggle archive failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا در آرشیو دوره: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * دریافت دوره‌های آرشیو شده
     */
    public function archivedCourses()
    {
        try {
            $user = Auth::user();
            $teacherRole = Role::where('name', 'teacher')->first();
            
            $archivedCourses = $user->courses()
                ->wherePivot('role_id', $teacherRole->id)
                ->where('archieve', 1)
                ->orderBy('updated_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $archivedCourses
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get archived courses failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا در دریافت دوره‌های آرشیو شده'
            ], 500);
        }
    }

    /**
     * نمایش دوره‌های فعال (غیر آرشیو)
     */
    public function courses()
    {
        $user = Auth::user();
        $teacherRole = Role::where('name', 'teacher')->first();
        
        // فقط دوره‌هایی که آرشیو نشده‌اند (archieve = 0)
        $courses = $user->courses()
            ->wherePivot('role_id', $teacherRole->id)
            ->where('archieve', 0)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('teacher.courses', compact('courses'));
    }
    public function restoreUser($userId, $courseId)
    {
        try {
            // پیدا کردن رکورد حذف شده
            $courseUser = CourseUser::withTrashed()
                ->where('user_id', $userId)
                ->where('course_id', $courseId)
                ->first();

            if (!$courseUser) {
                return redirect()->back()->with('error', 'این دانشجو در این دوره وجود ندارد');
            }

            if (!$courseUser->trashed()) {
                return redirect()->back()->with('error', 'این دانشجو اخراج نشده است');
            }

            // بازگرداندن
            $courseUser->restore();

            // لاگ کردن
            \Log::info("Student restored to course", [
                'student_id' => $userId,
                'course_id' => $courseId,
                'teacher_id' => Auth::id(),
                'restored_at' => now()
            ]);

            return redirect()->back()->with('success', 'دانشجو با موفقیت به دوره بازگردانده شد');

        } catch (\Exception $e) {
            \Log::error('Restore student failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'خطا در بازگرداندن دانشجو: ' . $e->getMessage());
        }
    }

    /**
     * دریافت لیست دانشجویان اخراج شده (JSON)
     */
    public function removedStudents($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            
            $removedStudents = CourseUser::onlyTrashed()
                ->where('course_id', $courseId)
                ->with('user')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $removedStudents
            ]);

        } catch (\Exception $e) {
            \Log::error('Get removed students failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا در دریافت لیست دانشجویان اخراج شده'
            ], 500);
        }
    }


    public function setting($id)
    {
        $course = Course::findOrFail($id);
        
        // دریافت تنظیمات (Setting)
        $setting = Setting::firstOrCreate(
            ['course_id' => $course->id],
            ['course_id' => $course->id]
        );
        
        // دریافت بارم‌بندی (Scoring)
        $scoring = Scoring::firstOrCreate(
            ['course_id' => $course->id],
            ['course_id' => $course->id]
        );

        return view('teacher.course-setting', compact('course', 'setting', 'scoring'))->with([
            'pageTitle' => 'صفحه تنظیمات',
            'pageName' => 'تنظیمات',
            'pageDescription' => 'مدرس گرامی ! صفحه تنظیمات در اختیار شماست',
        ]);
    }

    /**
     * ذخیره تنظیمات درس
     */
    public function editSetting(Request $request)
    {
        Log::info($request->all());
        // اعتبارسنجی
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'jalasat' => 'nullable|numeric|min:0',
            'mostamar_nomre' => 'nullable|numeric|min:0|max:100',
            'taklif_seminar_nomre' => 'nullable|numeric|min:0|max:100',
            'azmon_nomre' => 'nullable|numeric|min:0|max:100',
            'max_soal' => 'nullable|numeric|min:0',
            'max_taklif' => 'nullable|numeric|min:0',
            'max_seminar' => 'nullable|numeric|min:0',
            'min_w_khod' => 'nullable|numeric|min:0',
            'q_num' => 'nullable|numeric|min:0',
            'sath_khod' => 'nullable|in:1,2,3',
            'quiz_num' => 'nullable|numeric|min:0',
            'sath_quiz' => 'nullable|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            // اگر course_id == 99999 باشد، همه دوره‌ها را به‌روز می‌کند
            if ($request->course_id == 99999) {
                return $this->updateAllSettings($request);
            }

            $course = Course::findOrFail($request->course_id);
            
            // ==========================================
            // 1. به‌روزرسانی بارم‌بندی (Scoring)
            // ==========================================
            $scoring = Scoring::firstOrCreate(['course_id' => $course->id]);
            $scoring->update([
                'q_1' => $request->q_1 ?? $scoring->q_1,
                'q_2' => $request->q_2 ?? $scoring->q_2,
                'q_3' => $request->q_3 ?? $scoring->q_3,
                'q_4' => $request->q_4 ?? $scoring->q_4,
                'd_1' => $request->d_1 ?? $scoring->d_1,
                'd_2' => $request->d_2 ?? $scoring->d_2,
                'd_3' => $request->d_3 ?? $scoring->d_3,
                'd_4' => $request->d_4 ?? $scoring->d_4,
                'e_1' => $request->e_1 ?? $scoring->e_1,
                'e_2' => $request->e_2 ?? $scoring->e_2,
                'e_3' => $request->e_3 ?? $scoring->e_3,
                'e_4' => $request->e_4 ?? $scoring->e_4,
                's_1' => $request->s_1 ?? $scoring->s_1,
                's_2' => $request->s_2 ?? $scoring->s_2,
                's_3' => $request->s_3 ?? $scoring->s_3,
                's_4' => $request->s_4 ?? $scoring->s_4,
            ]);

            // ==========================================
            // 2. به‌روزرسانی تنظیمات (Setting)
            // ==========================================
            $setting = Setting::firstOrCreate(['course_id' => $course->id]);
            
            // فیلدهای عددی
            $setting->fill([
                'tarahi_soal_nomre' => $request->tarahi_soal_nomre ?? 0,
                'mostamar_nomre' => $request->mostamar_nomre ?? 0,
                'jalasat' => $request->jalasat ?? 0,
                'ersal_gozaresh_nomre' => $request->ersal_gozaresh_nomre ?? 0,
                'taklif_seminar_nomre' => $request->taklif_seminar_nomre ?? 0,
                'azmon_nomre' => $request->azmon_nomre ?? 0,
                'max_soal' => $request->max_soal ?? 3,
                'max_taklif' => $request->max_taklif ?? 8,
                'max_seminar' => $request->max_seminar ?? 5,
                'min_w_khod' => $request->min_w_khod ?? 14,
                'q_num' => $request->q_num ?? 10,
                'sath_khod' => $request->sath_khod ?? 2,
                'quiz_num' => $request->quiz_num ?? 10,
                'sath_quiz' => $request->sath_quiz ?? 2,
            ]);

            // فیلدهای توضیحی
            if ($request->has('tarahi_soal_desc')) {
                $setting->tarahi_soal_desc = $request->tarahi_soal_desc;
            }
            if ($request->has('ersal_gozaresh_desc')) {
                $setting->ersal_gozaresh_desc = $request->ersal_gozaresh_desc;
            }

            // ==========================================
            // فیلدهای چک‌باکس (boolean)
            // ==========================================
            $setting->soal_last = in_array($request->soal_last, ['on', '1', true]) ? 1 : 0;
            $setting->gozaresh_last = in_array($request->gozaresh_last, ['on', '1', true]) ? 1 : 0;
            $setting->taklif_last = in_array($request->taklif_last, ['on', '1', true]) ? 1 : 0;
            $setting->show_khod = in_array($request->show_khod, ['on', '1', true]) ? 1 : 0;
            $setting->natije = in_array($request->natije, ['on', '1', true]) ? 1 : 0;
            $setting->show_quiz = in_array($request->show_quiz, ['on', '1', true]) ? 1 : 0;

            $setting->save();

            DB::commit();

            return redirect()->back()->with('success', 'تنظیمات با موفقیت ذخیره شد');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Edit setting failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'خطا در ذخیره تنظیمات: ' . $e->getMessage());
        }
    }
    /**
     * نمایش ارزشیابی دانشجو
     */
    public function studentEvaluation($courseId, $userId)
    {
        $course = Course::findOrFail($courseId);
        $setting = Setting::where('course_id', $course->id)->first();
        $scorring = Scoring::where('course_id', $course->id)->first();

        $user = User::findOrFail($userId);
        
        // بررسی دسترسی
        if (Auth::user()->hasRole('student') && $user->id != Auth::id()) {
            return redirect()->back()->with('error', 'شما مجاز به مشاهده این صفحه نیستید.');
        }

        $sessions = Session::where('course_id', $course->id)->pluck('id');
        $max_session = $course->sessions()->count();

        // ==========================================
        // 1. آمار سوالات
        // ==========================================
        $questions_all = Question::where('user_id', $userId)
            ->whereIn('session_id', $sessions)
            ->count();
            
        $questions_1 = Question::where('user_id', $userId)
            ->whereIn('session_id', $sessions)
            ->where('status', '1')->count();
        $questions_2 = Question::where('user_id', $userId)
            ->whereIn('session_id', $sessions)
            ->where('status', '2')->count();
        $questions_3 = Question::where('user_id', $userId)
            ->whereIn('session_id', $sessions)
            ->where('status', '3')->count();
        $questions_4 = Question::where('user_id', $userId)
            ->whereIn('session_id', $sessions)
            ->where('status', '4')->count();

        $questions = [
            '1' => $questions_1,
            '2' => $questions_2,
            '3' => $questions_3,
            '4' => $questions_4,
            'all' => $questions_all,
        ];

        // نمره سوالات
        $q_scores = 0;
        foreach (Question::where('user_id', $userId)->whereIn('session_id', $sessions)->get() as $qq) {
            $q_scores += $qq->score;
        }
        $q_scores = ($questions_all != 0) ? $q_scores / ($questions_all * (20/25)) : 0;
        if ($q_scores > 20) $q_scores = 20;

        // نمره نهایی سوالات
        $q_nomre = ($scorring->q_1 * $questions_1) + ($scorring->q_2 * $questions_2) + 
                ($scorring->q_3 * $questions_3) + ($scorring->q_4 * $questions_4);
        $nomre_har_soal = $setting->tarahi_soal_nomre / ($setting->jalasat * ($setting->max_soal - 1));
        $q_nomre *= $nomre_har_soal;
        if ($q_nomre > $setting->tarahi_soal_nomre) {
            $q_nomre = $setting->tarahi_soal_nomre;
        }
        $nomre['q'] = round($q_nomre, 2);

        // ==========================================
        // 2. آمار گزارش‌ها (Discussions)
        // ==========================================
        $disc_all = Discussion::where('user_id', $userId)
            ->whereIn('session_id', $sessions)
            ->count();
            
        $disc_1 = Discussion::where('user_id', $userId)
            ->whereIn('session_id', $sessions)
            ->where('status', '1')->count();
        $disc_2 = Discussion::where('user_id', $userId)
            ->whereIn('session_id', $sessions)
            ->where('status', '2')->count();
        $disc_3 = Discussion::where('user_id', $userId)
            ->whereIn('session_id', $sessions)
            ->where('status', '3')->count();
        $disc_4 = Discussion::where('user_id', $userId)
            ->whereIn('session_id', $sessions)
            ->where('status', '4')->count();

        $discs = [
            '1' => $disc_1,
            '2' => $disc_2,
            '3' => $disc_3,
            '4' => $disc_4,
            'all' => $disc_all,
        ];

        // نمره گزارش‌ها
        $d_scores = 0;
        foreach (Discussion::where('user_id', $userId)->whereIn('session_id', $sessions)->get() as $dd) {
            $d_scores += $dd->score;
        }
        $d_scores = ($disc_all != 0) ? $d_scores / ($disc_all * (20/25)) : 0;
        if ($d_scores > 20) $d_scores = 20;

        // نمره نهایی گزارش‌ها
        $d_nomre = ($scorring->d_1 * $disc_1) + ($scorring->d_2 * $disc_2) + 
                ($scorring->d_3 * $disc_3) + ($scorring->d_4 * $disc_4);
        if ($d_nomre > $setting->ersal_gozaresh_nomre) {
            $d_nomre = $setting->ersal_gozaresh_nomre;
        }
        $nomre_har_gozaresh = $setting->ersal_gozaresh_nomre / $setting->jalasat;
        $d_nomre *= $nomre_har_gozaresh;
        $nomre['d'] = round($d_nomre, 2);

        // ==========================================
        // 3. آمار تکالیف
        // ==========================================
        $exercises = Exercise::whereIn('session_id', $sessions)->pluck('id');
        
        $exer_1 = ExerciseAnswer::where('user_id', $userId)
            ->whereIn('exercise_id', $exercises)
            ->where('status', '1')->count();
        $exer_2 = ExerciseAnswer::where('user_id', $userId)
            ->whereIn('exercise_id', $exercises)
            ->where('status', '2')->count();
        $exer_3 = ExerciseAnswer::where('user_id', $userId)
            ->whereIn('exercise_id', $exercises)
            ->where('status', '3')->count();
        $exer_4 = ExerciseAnswer::where('user_id', $userId)
            ->whereIn('exercise_id', $exercises)
            ->where('status', '4')->count();
        $exer_all = ExerciseAnswer::where('user_id', $userId)
            ->whereIn('exercise_id', $exercises)
            ->count();

        $exers = [
            '1' => $exer_1,
            '2' => $exer_2,
            '3' => $exer_3,
            '4' => $exer_4,
            'all' => $exer_all,
        ];

        // نمره نهایی تکالیف
        $e_nomre = ($scorring->e_1 * $exer_1) + ($scorring->e_2 * $exer_2) + 
                ($scorring->e_3 * $exer_3) + ($scorring->e_4 * $exer_4);
        if ($e_nomre > $setting->taklif_seminar_nomre) {
            $e_nomre = $setting->taklif_seminar_nomre;
        }
        $nomre['e'] = round($e_nomre, 2);

        // ==========================================
        // 4. آمار خودآزمایی
        // ==========================================
        $num_azmoon = Quiz::where('course_id', $course->id)
            ->where('user_id', $userId)
            ->pluck('id');
        $count_azmoon = count($num_azmoon);

        $qu_scores = 0;
        foreach (Quiz::where('course_id', $course->id)->where('user_id', $userId)->get() as $qq) {
            $qu_scores += $qq->score;
        }
        $qu_scores = ($questions_all != 0) ? $qu_scores / $questions_all : 0;
        if ($qu_scores > 20) $qu_scores = 20;

        // ==========================================
        // 5. آمار داوری
        // ==========================================
        $all_q = Question::whereIn('session_id', $sessions)->pluck('id');
        $all_d = Discussion::whereIn('session_id', $sessions)->pluck('id');

        $davari_q = Score::withTrashed()
            ->whereIn('sub_id', $all_q)
            ->where('type', 1)
            ->where('user_id', $userId)
            ->count();

        $davari_gozaresh = Score::withTrashed()
            ->whereIn('sub_id', $all_d)
            ->where('type', 2)
            ->where('user_id', $userId)
            ->count();

        $davarii = [
            'q' => $davari_q,
            'gozaresh' => $davari_gozaresh,
        ];

        // ==========================================
        // 6. محاسبه امتیازات
        // ==========================================
        
        // 6.1. فعالیت کلاسی
        $score_soal = min(8, ($questions_all * 8) / ($max_session * $setting->max_soal * 5 / 6));
        $score_gozaresh = min(5, $disc_all * 5 / $max_session);
        $score_davari = min(8, (($davarii['q'] + $davarii['gozaresh']) * 8) / ($max_session * (1 + $setting->max_soal) * 3));
        $score_azmoon = min(9, $count_azmoon * 9 / ($setting->min_w_khod * $max_session));
        
        $kelasi = min(30, $score_soal + $score_gozaresh + $score_azmoon + $score_davari);

        // 6.2. پیشرفت درسی
        $score_pish_soal = ($score_soal > 0) ? min(12, $q_scores * 12 / $score_soal) : 0;
        $score_pish_gozaresh = ($score_gozaresh > 0) ? min(10, $d_scores * 10 / $score_gozaresh) : 0;
        $score_pish_azmoon = ($score_azmoon > 0) ? min(24, ($qu_scores / 24) * $score_azmoon) : 0;
        $score_keifiat = min(14, ((($q_scores + $d_scores + $qu_scores + 5) / 4) * 14));
        
        $pishraft = min(70, $score_pish_soal + $score_pish_gozaresh + $score_pish_azmoon + 5 + $score_keifiat);

        // 6.3. ارزشیابی مستمر
        $mostamer = ($pishraft + $kelasi) * 12 / 100;
        if ($mostamer > 12) $mostamer = 12;
        $mostamer_score = ($setting->mostamar_nomre > 0) ? ($mostamer * 20 / $setting->mostamar_nomre) : 0;

        // 6.4. نمرات سایر بخش‌ها
        $nomre_har_talash = 5;
        $talash_soal = min(5, $nomre_har_talash * $questions_all / ($setting->jalasat * ($setting->max_soal - 1)));
        $talash_gozaresh = min(5, $nomre_har_talash * $disc_all / $setting->jalasat);
        $talash_davari_soal = min(5, $nomre_har_talash * $davarii['q'] / ($setting->jalasat * 2 * 6));
        $talash_davari_gozaresh = min(5, $nomre_har_talash * $davarii['gozaresh'] / ($setting->jalasat * 6));
        $talash_khod = min(5, $nomre_har_talash * $count_azmoon / ($setting->jalasat * (7 * 5)));
        
        $nomre['talash'] = round($talash_davari_soal + $talash_davari_gozaresh + $talash_soal + $talash_gozaresh + $talash_khod, 2);

        // ==========================================
        // 7. نمره نهایی
        // ==========================================
        $nomre['pish'] = round($pishraft, 2);
        $nomre['total'] = $pishraft + $d_nomre + $q_nomre + $e_nomre + $nomre['talash'];

        // نمره پایان ترم
        $final = Amali::where('course_id', $course->id)
            ->where('user_id', $userId)
            ->where('type', 1)
            ->first();
        $nomre['final'] = $final ? $final->nomre : null;

        return view('teacher.student-evaluation', compact(
            'course',
            'user',
            'questions',
            'discs',
            'exers',
            'setting',
            'nomre',
            'count_azmoon',
            'davarii',
            'max_session',
            'q_scores',
            'qu_scores',
            'd_scores',
            'kelasi',
            'pishraft',
            'mostamer',
            'mostamer_score',
            'score_soal',
            'score_gozaresh',
            'score_davari',
            'score_azmoon',
            'score_pish_soal',
            'score_pish_gozaresh',
            'score_pish_azmoon',
            'score_keifiat'
        ))->with([
            'pageTitle' => 'ارزشیابی دانشجو',
            'pageName' => 'ارزشیابی',
            'pageDescription' => 'مشاهده وضعیت پیشرفت درسی دانشجو',
        ]);
    }

    /**
     * نمایش نمرات دانشجویان - نسخه Query Builder
     */
    public function gradesList($courseId)
    {
        $course = Course::with(['settings'])->findOrFail($courseId);
        $setting = $course->settings;

        $studentRole = Role::where('name', 'student')->first();

        // دریافت دانشجویان
        $users = $course->users()
            ->where('role_id', $studentRole->id)
            ->orderBy('family', 'asc')
            ->select('users.*')
            ->get();

        $sessions = Session::where('course_id', $course->id)->pluck('id');
        
        if ($course->status == 1) {
            $max_session = $course->max_session;
        } else {
            $max_session = $sessions->count();
        }

        // دریافت آزمون‌ها
        $azmons = Azmon::where('course_id', $course->id)->get();

        // ==========================================
        // دریافت تمام داده‌های مورد نیاز با یک کوئری
        // ==========================================
        
        // دریافت تمام سوالات کاربران
        $questions = Question::whereIn('session_id', $sessions)
            ->whereIn('user_id', $users->pluck('id'))
            ->get()
            ->groupBy('user_id');

        // دریافت تمام گزارش‌های کاربران
        $discussions = Discussion::whereIn('session_id', $sessions)
            ->whereIn('user_id', $users->pluck('id'))
            ->get()
            ->groupBy('user_id');

        // دریافت تمام کوئیزهای کاربران
        $quizzes = Quiz::where('course_id', $course->id)
            ->whereIn('user_id', $users->pluck('id'))
            ->get()
            ->groupBy('user_id');

        // دریافت تمام پاسخ‌های کوئیزها
        $quizIds = Quiz::where('course_id', $course->id)->pluck('id');
        $answers = Answer::whereIn('quiz_id', $quizIds)
            ->with('question')
            ->get()
            ->groupBy('quiz_id');

        // دریافت نمرات پایان ترم
        $finals = Amali::where('course_id', $course->id)
            ->where('type', 1)
            ->whereIn('user_id', $users->pluck('id'))
            ->get()
            ->keyBy('user_id');

        // دریافت تعداد داوری‌ها
        $all_q = Question::whereIn('session_id', $sessions)->pluck('id');
        $all_d = Discussion::whereIn('session_id', $sessions)->pluck('id');

        $davari_counts = Score::withTrashed()
            ->where(function($query) use ($all_q, $all_d) {
                $query->whereIn('sub_id', $all_q)
                    ->orWhereIn('sub_id', $all_d);
            })
            ->whereIn('type', [1, 2])
            ->whereIn('user_id', $users->pluck('id'))
            ->get()
            ->groupBy('user_id');

        foreach ($users as $user) {
            $userId = $user->id;

            // وضعیت آنلاین
            $user->online = $user->isOnline() ? 1 : 0;

            // نمره پایان ترم
            $final = $finals->get($userId);
            $user->final = $final ? $final->nomre : null;

            // ==========================================
            // سوالات این کاربر
            // ==========================================
            $userQuestions = $questions->get($userId, collect());
            $questions_all = $userQuestions->count();
            $q_scores = $userQuestions->sum('score');
            $q_scores = $questions_all != 0 ? $q_scores / ($questions_all * (20 / 25)) : 0;
            if ($q_scores > 20) $q_scores = 20;

            // ==========================================
            // گزارش‌های این کاربر
            // ==========================================
            $userDiscussions = $discussions->get($userId, collect());
            $disc_all = $userDiscussions->count();
            $d_scores = $userDiscussions->sum('score');
            $d_scores = $disc_all != 0 ? $d_scores / ($disc_all * (20 / 25)) : 0;
            if ($d_scores > 20) $d_scores = 20;

            // ==========================================
            // خودآزمایی‌های این کاربر
            // ==========================================
            $userQuizzes = $quizzes->get($userId, collect())->where('azmon_id', null);
            $count_azmoon = $userQuizzes->count();
            $qu_scores = $userQuizzes->sum('score');
            $qu_scores = ($count_azmoon != 0 && $questions_all != 0) ? $qu_scores / $questions_all : 0;
            if ($qu_scores > 20) $qu_scores = 20;

            // ==========================================
            // داوری‌های این کاربر
            // ==========================================
            $userDavaris = $davari_counts->get($userId, collect());
            $davari_q = $userDavaris->where('type', 1)->count();
            $davari_gozaresh = $userDavaris->where('type', 2)->count();

            // ==========================================
            // محاسبه امتیازات
            // ==========================================
            $denom_soal = ($max_session * $setting->max_soal * 5 / 6);
            $score_soal = $denom_soal > 0 ? min(($questions_all * 8) / $denom_soal, 8) : 0;

            $denom_goz = $max_session;
            $score_gozaresh = $denom_goz > 0 ? min($disc_all * 5 / $denom_goz, 5) : 0;

            $denom_dav = $max_session * (1 + $setting->max_soal) * 3;
            $score_davari = $denom_dav > 0 ? min((($davari_q + $davari_gozaresh) * 8) / $denom_dav, 8) : 0;

            $denom_azm = $setting->min_w_khod * $max_session;
            $score_azmoon = $denom_azm > 0 ? min($count_azmoon * 9 / $denom_azm, 9) : 0;

            // پیشرفت درسی
            $score_pish_soal = $score_soal > 0 ? min($q_scores * 12 / $score_soal, 12) : 0;
            $score_pish_gozaresh = $score_gozaresh > 0 ? min($d_scores * 10 / $score_gozaresh, 10) : 0;
            $score_pish_azmoon = $score_azmoon > 0 ? min(($qu_scores / 24) * $score_azmoon, 24) : 0;

            $score_keifiat_raw = ((($q_scores + $d_scores + $qu_scores + 5) / 4) * 14)
                * (max($score_soal, $score_gozaresh, $score_davari, $score_azmoon) + 1);
            $score_keifiat = min($score_keifiat_raw, 14);

            $pishraft = min($score_pish_soal + $score_pish_gozaresh + $score_pish_azmoon + 5 + $score_keifiat, 70);
            $kelasi = min($score_soal + $score_gozaresh + $score_azmoon + $score_davari, 30);

            $mostamer = ($pishraft + $kelasi) * 12 / 100;
            if ($mostamer > 12) $mostamer = 12;

            $user->mostamar_nomre = $setting->mostamar_nomre > 0 
                ? round($mostamer * 20 / $setting->mostamar_nomre, 2) 
                : 0;

            // ==========================================
            // میانگین نمره آزمون‌ها
            // ==========================================
            $weightedScores = [];

            foreach ($azmons as $azmon) {
                $zarib = is_null($azmon->zarib) ? 1 : (float)$azmon->zarib;

                $quiz = $userQuizzes->where('azmon_id', $azmon->id)->first();

                if (!$quiz) continue;

                $answersForQuiz = $answers->get($quiz->id, collect());
                $total_answers = $answersForQuiz->count();
                $correct = $answersForQuiz->filter(function($answer) {
                    $q = $answer->question;
                    return $q && $q->answer == $answer->answer;
                })->count();

                if ($total_answers > 0) {
                    $weightedScores[] = round(($correct / $total_answers) * 20 * $zarib, 2);
                }
            }

            $user->exam_avg = count($weightedScores) > 0
                ? round(array_sum($weightedScores) / count($weightedScores), 2)
                : null;
        }

        return view('teacher.grades-list', compact('users', 'course', 'setting'))->with([
            'pageTitle' => 'نمرات دانشجویان',
            'pageName' => 'نمرات دانشجویان',
            'pageDescription' => 'مدرس گرامی! نمرات دانشجویان به شرح زیر می‌باشد',
        ]);
    }

    /**
     * ذخیره نمرات پایان ترم
     */
    public function saveGrades(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $setting = Setting::where('course_id', $course->id)->first();

        try {
            foreach ($request->ind as $userId) {
                // نمره پایان ترم
                if ($setting->final_nomre > 0 && isset($request->final[$userId])) {
                    $final = Amali::where('course_id', $courseId)
                        ->where('user_id', $userId)
                        ->where('type', 1)
                        ->first();

                    if (!$final) {
                        $final = new Amali();
                    }

                    $final->course_id = $courseId;
                    $final->user_id = $userId;
                    $final->type = 1;
                    $final->nomre = $request->final[$userId];
                    $final->save();
                }

                // نمره عملی (اگر نیاز باشد)
                if ($setting->amali_nomre > 0 && isset($request->amali[$userId])) {
                    $amali = Amali::where('course_id', $courseId)
                        ->where('user_id', $userId)
                        ->where('type', 2)
                        ->first();

                    if (!$amali) {
                        $amali = new Amali();
                    }

                    $amali->course_id = $courseId;
                    $amali->user_id = $userId;
                    $amali->type = 2;
                    $amali->nomre = $request->amali[$userId];
                    $amali->save();
                }
            }

            return redirect()->back()->with('success', 'نمرات با موفقیت ذخیره شد');

        } catch (\Exception $e) {
            \Log::error('Save grades failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'خطا در ذخیره نمرات: ' . $e->getMessage());
        }
    }

    /**
     * نمایش فعالیت‌های دانشجویان (پایش دانشجویان)
     */
    public function allProgress($id)
    {
        $course = Course::findOrFail($id);
        $sessions = Session::where('course_id', $course->id)->pluck('id');
        $studentRole = Role::where('name', 'student')->first();
        
        $users = $course->users()
            ->where('role_id', $studentRole->id)
            ->orderBy('family', 'asc')
            ->get();

        $exers = Exercise::whereIn('session_id', $sessions)->pluck('id');

        foreach ($users as $user) {
            // فعالیت‌های کل
            $user['disc'] = Discussion::where('user_id', $user->id)
                ->whereIn('session_id', $sessions)
                ->count();

            $user['questions'] = Question::where('user_id', $user->id)
                ->whereIn('session_id', $sessions)
                ->count();

            $user['exer'] = ExerciseAnswer::where('user_id', $user->id)
                ->whereIn('exercise_id', $exers)
                ->count();

            $all_d = Discussion::whereIn('session_id', $sessions)->pluck('id');
            $davari_gozaresh = Score::withTrashed()
                ->whereIn('sub_id', $all_d)
                ->where('type', 2)
                ->where('user_id', $user->id)
                ->get();

            $all_q = Question::whereIn('session_id', $sessions)->pluck('id');
            $davari = Score::withTrashed()
                ->whereIn('sub_id', $all_q)
                ->where('type', 1)
                ->where('user_id', $user->id)
                ->get();

            $user['davari'] = $davari_gozaresh->count() + $davari->count();
            $user['khod_total'] = Quiz::where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->count();

            // فعالیت‌های جدید (اخیر)
            $user['disc_new'] = Discussion::where('created_at', '>=', Carbon::now()->subDays(3))
                ->where('user_id', $user->id)
                ->whereIn('session_id', $sessions)
                ->count();

            $user['questions_new'] = Question::where('created_at', '>=', Carbon::now()->subDays(3))
                ->where('user_id', $user->id)
                ->whereIn('session_id', $sessions)
                ->count();

            $user['exer_new'] = ExerciseAnswer::where('created_at', '>=', Carbon::now()->subDays(3))
                ->where('user_id', $user->id)
                ->whereIn('exercise_id', $exers)
                ->count();

            $davari_gozaresh_new = Score::where('created_at', '>=', Carbon::now()->subDays(3))
                ->withTrashed()
                ->whereIn('sub_id', $all_d)
                ->where('type', 2)
                ->where('user_id', $user->id)
                ->get();

            $davari_new = Score::where('created_at', '>=', Carbon::now()->subDays(3))
                ->withTrashed()
                ->whereIn('sub_id', $all_q)
                ->where('type', 1)
                ->where('user_id', $user->id)
                ->get();

            $user['davari_new'] = $davari_gozaresh_new->count() + $davari_new->count();
            $user['khod_new'] = Quiz::where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subDays(3))
                ->count();

            // وضعیت آنلاین
            $user->online = $user->isOnline() ? 1 : 0;
        }

        return view('teacher.activities', compact('users', 'course'))->with([
            'pageTitle' => 'فعالیت‌های دانشجویان',
            'pageName' => 'فعالیت‌های دانشجویان',
            'pageDescription' => 'مشاهده فعالیت‌های دانشجویان در درس',
        ]);
    }
    /**
     * دریافت فعالیت‌های دانشجویان بر اساس بازه زمانی (AJAX)
     */
    public function getStudentActivitiesRange($id)
    {
        $days = $request->days ?? 3;
        $courseId = $id;
        
        $course = Course::findOrFail($courseId);
        $sessions = Session::where('course_id', $course->id)->pluck('id');
        $studentRole = Role::where('name', 'student')->first();
        
        $users = $course->users()
            ->where('role_id', $studentRole->id)
            ->orderBy('family', 'asc')
            ->get();

        $exers = Exercise::whereIn('session_id', $sessions)->pluck('id');

        $result = [];
        foreach ($users as $user) {
            $all_d = Discussion::whereIn('session_id', $sessions)->pluck('id');
            $all_q = Question::whereIn('session_id', $sessions)->pluck('id');

            $davari_gozaresh_new = Score::where('created_at', '>=', Carbon::now()->subDays($days))
                ->withTrashed()
                ->whereIn('sub_id', $all_d)
                ->where('type', 2)
                ->where('user_id', $user->id)
                ->get();

            $davari_new = Score::where('created_at', '>=', Carbon::now()->subDays($days))
                ->withTrashed()
                ->whereIn('sub_id', $all_q)
                ->where('type', 1)
                ->where('user_id', $user->id)
                ->get();

            $result[] = [
                'id' => $user->id,
                'disc_new' => Discussion::where('created_at', '>=', Carbon::now()->subDays($days))
                    ->where('user_id', $user->id)
                    ->whereIn('session_id', $sessions)
                    ->count(),
                'questions_new' => Question::where('created_at', '>=', Carbon::now()->subDays($days))
                    ->where('user_id', $user->id)
                    ->whereIn('session_id', $sessions)
                    ->count(),
                'exer_new' => ExerciseAnswer::where('created_at', '>=', Carbon::now()->subDays($days))
                    ->where('user_id', $user->id)
                    ->whereIn('exercise_id', $exers)
                    ->count(),
                'davari_new' => $davari_gozaresh_new->count() + $davari_new->count(),
                'khod_new' => Quiz::where('course_id', $course->id)
                    ->where('user_id', $user->id)
                    ->where('created_at', '>=', Carbon::now()->subDays($days))
                    ->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'users' => $result
        ]);
    }

    /**
     * نمایش بانک سوالات
     */
    public function questionBank($courseId)
    {
        $course = Course::with('sessions')->findOrFail($courseId);
        $sessions = $course->sessions()->pluck('id');
        
        // دریافت تمام سوالات
        $questions = Question::whereIn('session_id', $sessions)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // اضافه کردن اطلاعات طراح و سطح
        foreach ($questions as $question) {
            // اطلاعات طراح
            if ($question->user) {
                $question->designer_name = $question->user->hasRole('teacher') 
                    ? 'استاد' 
                    : $question->user->name . ' ' . $question->user->family;
            } else {
                $question->designer_name = 'نامشخص';
            }

            // سطح سوال
            $question->level_text = match ($question->status) {
                1 => 'عالی',
                2 => 'خوب',
                3 => 'متوسط',
                4 => 'بد',
                null => 'در انتظار تایید',
                default => 'نامشخص',
            };

            // نظرات (داوری‌ها)
            $nazars = Score::where('sub_id', $question->id)
                ->where('type', 1)
                ->orderBy('id', 'desc')
                ->with('user')
                ->get();
            
            foreach ($nazars as $nazar) {
                if ($nazar->user) {
                    $nazar->user_name = $nazar->user->name . ' ' . $nazar->user->family;
                }
            }
            $question->nazars = $nazars;
        }

        // آمار سوالات
        $stats = [
            'total' => $questions->count(),
            'excellent' => $questions->where('status', 1)->count(),
            'good' => $questions->where('status', 2)->count(),
            'medium' => $questions->where('status', 3)->count(),
            'bad' => $questions->where('status', 4)->count(),
            'pending' => $questions->whereNull('status')->count(),
            'starred' => $questions->where('star', 1)->count(),
        ];
        
        // return $question;
        return view('teacher.question-bank', compact('course', 'questions', 'stats'))->with([
            'pageTitle' => 'بانک سوالات',
            'pageName' => 'بانک سوالات',
            'pageDescription' => 'مدیریت سوالات درس',
        ]);
    }

    /**
     * تغییر وضعیت ستاره سوال
     */
    public function toggleStar($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->star = $question->star == 1 ? 0 : 1;
            $question->save();

            return response()->json([
                'success' => true,
                'star' => $question->star
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در تغییر وضعیت ستاره'
            ], 500);
        }
    }

    /**
     * ثبت نظر و امتیاز برای سوال
     */
    public function scoreQuestion(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'score' => 'required|in:1,2,3,4',
            'comment' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $question = Question::findOrFail($id);

            // ثبت نظر
            $score = new Score();
            $score->user_id = Auth::id();
            $score->type = 1;
            $score->score = $request->score;
            $score->sub_id = $question->id;
            $score->comment = $request->comment;
            $score->save();

            return redirect()->back()->with('success', 'نظر شما با موفقیت ثبت شد');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در ثبت نظر: ' . $e->getMessage());
        }
    }

    /**
     * حذف سوال
     */
    public function deleteQuestion($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->delete();

            return redirect()->back()->with('success', 'سوال با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در حذف سوال: ' . $e->getMessage());
        }
    }

    /**
     * به‌روزرسانی تنظیمات همه دوره‌ها (برای course_id = 99999)
     */
    private function updateAllSettings($request)
    {
        DB::beginTransaction();

        try {
            // به‌روزرسانی همه بارم‌بندی‌ها (Scoring)
            $scores = Scoring::all();
            foreach ($scores as $score) {
                $score->update([
                    'q_1' => $request->q_1 ?? $score->q_1,
                    'q_2' => $request->q_2 ?? $score->q_2,
                    'q_3' => $request->q_3 ?? $score->q_3,
                    'q_4' => $request->q_4 ?? $score->q_4,
                    'd_1' => $request->d_1 ?? $score->d_1,
                    'd_2' => $request->d_2 ?? $score->d_2,
                    'd_3' => $request->d_3 ?? $score->d_3,
                    'd_4' => $request->d_4 ?? $score->d_4,
                    'e_1' => $request->e_1 ?? $score->e_1,
                    'e_2' => $request->e_2 ?? $score->e_2,
                    'e_3' => $request->e_3 ?? $score->e_3,
                    'e_4' => $request->e_4 ?? $score->e_4,
                    's_1' => $request->s_1 ?? $score->s_1,
                    's_2' => $request->s_2 ?? $score->s_2,
                    's_3' => $request->s_3 ?? $score->s_3,
                    's_4' => $request->s_4 ?? $score->s_4,
                ]);
            }

            // به‌روزرسانی همه تنظیمات (Setting)
            $settings = Setting::all();
            foreach ($settings as $setting) {
                $setting->update([
                    'taklif_seminar_desc' => $request->taklif_seminar_desc ?? $setting->taklif_seminar_desc,
                    'taklif_seminar_type' => $request->taklif_seminar_type ?? $setting->taklif_seminar_type,
                    'quiz_mid_nomre' => $request->quiz_mid_nomre ?? $setting->quiz_mid_nomre,
                    'quiz_mid_desc' => $request->quiz_mid_desc ?? $setting->quiz_mid_desc,
                    'quiz_mid_type' => $request->quiz_mid_type ?? $setting->quiz_mid_type,
                    'pishraft_nomre' => $request->pishraft_nomre ?? $setting->pishraft_nomre,
                    'pishraft_desc' => $request->pishraft_desc ?? $setting->pishraft_desc,
                    'talash_nomre' => $request->talash_nomre ?? $setting->talash_nomre,
                    'talash_desc' => $request->talash_desc ?? $setting->talash_desc,
                    'hozor_nomre' => $request->hozor_nomre ?? $setting->hozor_nomre,
                    'hozor_desc' => $request->hozor_desc ?? $setting->hozor_desc,
                    'amali_nomre' => $request->amali_nomre ?? $setting->amali_nomre,
                    'amali_desc' => $request->amali_desc ?? $setting->amali_desc,
                    'final_nomre' => $request->final_nomre ?? $setting->final_nomre,
                    'final_desc' => $request->final_desc ?? $setting->final_desc,
                    'erfagh_nomre' => $request->erfagh_nomre ?? $setting->erfagh_nomre,
                    'erfagh_desc' => $request->erfagh_desc ?? $setting->erfagh_desc,
                    'min_davari' => $request->min_davari ?? $setting->min_davari,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'تنظیمات همه دوره‌ها با موفقیت به‌روزرسانی شد');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update all settings failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'خطا در به‌روزرسانی تنظیمات: ' . $e->getMessage());
        }
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