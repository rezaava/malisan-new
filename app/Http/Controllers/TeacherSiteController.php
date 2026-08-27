<?php

namespace App\Http\Controllers;

use App\Models\Angizesh;
use App\Models\Course;
use App\Models\Azmon;
use App\Models\CourseUser;
use App\Models\Konkor;
use App\Models\OptionUser;
use App\Models\Role;
use App\Models\Scoring;
use App\Models\Setting;
use App\Models\SiteSetting;
use App\Models\Survey;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class TeacherSiteController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        return view('teacher.profile', compact('user'));
    }
    public function coin()
    {
        return view('teacher.coin',);
    }
    public function index()
    {
        $user = Auth::user();
        // چک کردن اینکه استاد باید به صفحه onboarding برود یا خیر
        if (SiteSetting::isTeacherSurveyEnabled() && !session()->has('teacher_onboarding_done')) {
            $answeredSurveyIds = OptionUser::where('user_id', $user->id)
                ->pluck('survey_id')
                ->toArray();

            $hasUnansweredSurveys = Survey::where('active', 1)
                ->where('type', 1)
                ->whereNotIn('id', $answeredSurveyIds)
                ->exists();

            if ($hasUnansweredSurveys) {
                return redirect()->route('teacher.onboarding')
                    ->with('info', 'لطفاً به سوالات پاسخ دهید.');
            } else {
                session()->put('teacher_onboarding_done', true);
            }
        }
        // دریافت پیام انگیزشی
        $message = Angizesh::whereIn('level', [7, 8])
            ->inRandomOrder()
            ->first();
        
        // دریافت اطلاعات کیف پول
        $aneto = null;
        if ($user->national != 'admin') {
            // منطق دریافت کیف پول را اینجا بنویسید
            // $aneto = $user->wallet->balance ?? 0;
            $aneto = null;
        }
        
        // تعداد کل دوره‌های کاربر
        $coursesCount = $user->courses()->where('archieve','0')->where('private','0')->count();
        
        // ==========================================
        // آمار مخصوص معلم
        // ==========================================
        $teacherRoleId = Role::where('name', 'teacher')->value('id');
        $studentRoleId = Role::where('name', 'student')->value('id');

        // 1. تعداد دوره‌های فعال و خصوصی معلم
        $course_count = $user->courses()
            ->where('active', '1')
            ->where('private', '1')
            ->count();

        // 2. تعداد کل دوره‌های فعال در سیستم
        $total_course_count = Course::where('active', '1')
            ->where('private', '1')
            ->count();

        // 3. تعداد دانشجویان (دانشجویانی که در دوره‌های این معلم ثبت‌نام کرده‌اند)
        $student_count = DB::table('course_user')
            ->join('courses', 'courses.id', '=', 'course_user.course_id')
            ->where('course_user.role_id', $studentRoleId)
            ->where('courses.active', 1)
            ->where('courses.archieve', 0)
            ->whereIn('course_user.course_id', function ($query) use ($teacherRoleId) {
                $query->select('course_id')
                    ->from('course_user')
                    ->where('user_id', Auth::id())
                    ->where('role_id', $teacherRoleId);
            })
            ->distinct('course_user.user_id')
            ->count('course_user.user_id');

        // 4. تعداد درس‌های معلم
        $lesson_count = DB::table('course_user')
            ->join('courses', 'courses.id', '=', 'course_user.course_id')
            ->where('course_user.user_id', Auth::id())
            ->where('course_user.role_id', $teacherRoleId)
            ->where('courses.archieve', 0)
            ->where('courses.active', 1)
            ->count();

        // 5. تعداد کنکورهای فعال
        $konkor_count = Konkor::where('active', 1)->count();
        $massage = null;
        if ($user->hasRole('admin')) {
            $massage = Angizesh::whereNotIn('level', [8,7])->count();
        }

        return view('teacher.index', compact(
            'user',
            'aneto',
            'message',
            'coursesCount',
            'course_count',
            'total_course_count',
            'student_count',
            'lesson_count',
            'massage',
            'konkor_count'
        ));
    }
    public function courses() {
        $user = Auth::user();
        $teacherRole = Role::where('name', 'teacher')->first();
        
        $courses = $user->courses()
            ->where('archieve', 0)
            ->whereIn('type', ['0','2'])
            ->wherePivot('role_id', $teacherRole->id)
            ->withCount(['users as pending_requests_count' => function($query) {
                $query->where('course_user.status', 2);
            }])
            ->get();

        return view('teacher.courses', compact('courses'));
    }
    public function pendingRequests($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            
            // Check if user is teacher of this course
            $user = Auth::user();
            $teacherRole = Role::where('name', 'teacher')->first();
            
            $isTeacher = $user->courses()
                ->where('course_id', $courseId)
                ->wherePivot('role_id', $teacherRole->id)
                ->exists();
                
            if (!$isTeacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'شما دسترسی به این دوره ندارید'
                ], 403);
            }
            
            // Get pending requests with student info
            $studentRole = Role::where('name', 'student')->first();
            $pendingUsers = $course->users()
                ->wherePivot('status', 2)
                ->wherePivot('role_id', $studentRole->id)
                ->get(['users.id', 'users.name', 'users.email', 'users.mobile', 'course_user.created_at']);
            
            return response()->json([
                'success' => true,
                'data' => $pendingUsers,
                'course_name' => $course->name
            ]);
        } catch (\Exception $e) {
            \Log::error('Pending requests error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا در دریافت اطلاعات'
            ], 500);
        }
    }

    public function approveRequest($courseId, $userId)
    {
        try {
            DB::beginTransaction();
            
            $course = Course::findOrFail($courseId);
            $user = User::findOrFail($userId);
            
            // Check if user is teacher
            $teacherRole = Role::where('name', 'teacher')->first();
            $isTeacher = Auth::user()->courses()
                ->where('course_id', $courseId)
                ->wherePivot('role_id', $teacherRole->id)
                ->exists();
                
            if (!$isTeacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'شما دسترسی به این دوره ندارید'
                ], 403);
            }
            
            // Update status to approved (1)
            $course->users()->updateExistingPivot($userId, [
                'status' => 1
            ]);
            
            // Create scoring for the student
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
            
            // Ensure setting exists
            Setting::firstOrCreate(
                ['course_id' => $course->id],
                ['course_id' => $course->id]
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'درخواست عضویت تأیید شد'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Approve request error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا در تأیید درخواست: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectRequest($courseId, $userId)
    {
        try {
            $course = Course::findOrFail($courseId);
            
            // Check if user is teacher
            $teacherRole = Role::where('name', 'teacher')->first();
            $isTeacher = Auth::user()->courses()
                ->where('course_id', $courseId)
                ->wherePivot('role_id', $teacherRole->id)
                ->exists();
                
            if (!$isTeacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'شما دسترسی به این دوره ندارید'
                ], 403);
            }
            
            // Remove the user from course
            $course->users()->detach($userId);
            
            return response()->json([
                'success' => true,
                'message' => 'درخواست عضویت رد شد'
            ]);
        } catch (\Exception $e) {
            \Log::error('Reject request error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا در رد درخواست'
            ], 500);
        }
    }
    public function azmoon(){
        $user = Auth::user();
        // Get courses where user is a teacher
        $teacherRole = Role::where('name', 'teacher')->first();
        
        $courses = $user->courses()
            ->wherePivot('role_id', $teacherRole->id)
            ->get();
        $exams = collect();

    if ($courses->isNotEmpty()) {
        $courses->load('Azmons');                    // رابطه رو لود کن

        $exams = $courses->flatMap(function ($course) {
            return $course->Azmons;                  // رابطه به صورت property
        })->values();
    }
        // $exams = $courses->Azmons();

        return view('teacher.azmoon', compact('exams'));
    }
}
