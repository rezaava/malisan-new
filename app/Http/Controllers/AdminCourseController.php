<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
   public function courses()
    {
        $courses = Course::where('courses.archieve', 0)->get();
        
        return view('admin.courses', compact('courses'));
    }
}
