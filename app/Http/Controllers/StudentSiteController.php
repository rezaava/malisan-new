<?php

namespace App\Http\Controllers;

use App\Models\Angizesh;
use App\Models\Azmon;
use App\Models\Course;
use App\Models\Konkor;
use App\Models\Role;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class StudentSiteController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('student') && !session()->has('onboarding_done')) {
            return redirect()->route('student.onboarding');
        }
        
        // دریافت پیام انگیزشی
        $message = Angizesh::whereIn('level', [7, 8])
            ->inRandomOrder()
            ->first();
        
        // دریافت دوره‌های کاربر
        $userCourseIds = DB::table('course_user')
            ->where('user_id', $user->id)
            ->pluck('course_id');
        
        // دریافت آزمون‌های شرکت کرده
        $participatedAzmonIds = DB::table('quizzes')
            ->where('user_id', $user->id)
            ->whereNotNull('azmon_id')
            ->pluck('azmon_id');
        
        // دریافت آزمون‌های فعال
        $activeExams = Azmon::whereIn('course_id', $userCourseIds)
        ->where('start', '<=', Carbon::now())
        ->where('end', '>=', Carbon::now())
        ->whereNotIn('id', $participatedAzmonIds)
        ->with('course')
        ->get();
        

        // آمار کلی
        $course_count = Course::where('active', '1')
            ->where('private', '1')
            ->count();
        
        $konkor_count = Konkor::where('active', 1)
            ->count();
        
        // تعداد آزمون‌های فعال برای نمایش در کارت
        $active_exam_count = $activeExams->count();
        
        return view('student.index', compact(
            'user',
            'message',
            'activeExams',
            'active_exam_count',
            'course_count',
            'konkor_count'
        ))->with([
            'pageTitle' => 'صفحه دانشجو',
            'pageName' => 'دانشجو',
            'pageDescription' => 'خوش آمدید',
        ]);
    }

    public function courses()
    {
        $user = Auth::user();
        
        $courses = $user->courses()->get();
        
        return view('student.courses', compact('courses'));
    }
}
