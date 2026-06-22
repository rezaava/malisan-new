<?php

namespace App\Http\Controllers;

use App\Models\Angizesh;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Konkor;
use App\Models\Role;
use Auth;
use DB;
use Illuminate\Http\Request;

class TeacherSiteController extends Controller
{
    function index() {
        $user = Auth::user();        
        $coursesCount = $user->courses()->count();

        $message = Angizesh::whereIn('level',[7,8])->inRandomOrder()->first();
        
        $course_count = Auth::user()
            ->courses()
            ->where('active', '1')
            ->where('private', '1')
            ->count();

        $konkor_count = Konkor::where('active', 1)->count();
        $student_role_id = Role::where('name', 'student')->value('id');
        
        $student_count = DB::table('course_user')
            ->join('courses', 'courses.id', '=', 'course_user.course_id')
            ->where('course_user.role_id', $student_role_id)
            ->where('courses.active', 1)
            ->where('courses.archieve', 0)
            // فقط درس‌هایی که این استاد داره
            ->whereIn('course_user.course_id', function($query) {
                $query->select('course_id')
                    ->from('course_user')
                    ->where('user_id', Auth::id())
                    ->where('role_id', DB::table('roles')->where('name','teacher')->value('id'));
            })
            ->distinct('course_user.user_id')
            ->count('course_user.user_id');
            
        $teacher_role_id = Role::where('name', 'teacher')->value('id');
        
        $lesson_count = DB::table('course_user')
            ->join('courses', 'courses.id', '=', 'course_user.course_id')
            ->where('course_user.user_id', Auth::id())
            ->where('course_user.role_id', $teacher_role_id)
            ->where('courses.archieve', 0)
            ->where('courses.active', 1)
            ->count();

        return view('teacher.index', compact('coursesCount','message'));
    }
    function courses() {
        $user = Auth::user();
        // Get courses where user is a teacher
        $teacherRole = Role::where('name', 'teacher')->first();
        
        $courses = $user->courses()
            ->wherePivot('role_id', $teacherRole->id)
            ->get();
        
        return view('teacher.courses', compact('courses'));
    }
}
