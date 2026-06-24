<?php

namespace App\Http\Controllers;

use App\Models\Angizesh;
use App\Models\Course;
use App\Models\Azmon;
use App\Models\CourseUser;
use App\Models\Konkor;
use App\Models\Role;
use Auth;
use DB;
use Illuminate\Http\Request;

class TeacherSiteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
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
        $coursesCount = $user->courses()->count();
        
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

        return view('teacher.index', compact(
            'user',
            'aneto',
            'message',
            'coursesCount',
            'course_count',
            'total_course_count',
            'student_count',
            'lesson_count',
            'konkor_count'
        ));
    }
    function courses() {
        $user = Auth::user();
        // Get courses where user is a teacher
        $teacherRole = Role::where('name', 'teacher')->first();
        
        $courses = $user->courses()->where('archieve',0)
            ->wherePivot('role_id', $teacherRole->id)
            ->get();
        
        return view('teacher.courses', compact('courses'));
    }
    function azmoon(){
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
