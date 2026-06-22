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

class CourseController extends Controller
{
    // Add this method for copy functionality
    public function getCopyData($id)
    {
        try {
            $course = Course::findOrFail($id);
            
            // Check if user owns this course
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

    public function store(Request $request)
    {
        // Validation
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
            
            // Generate unique code
            $code = $this->generateUniqueCode();
            
            // Create course
            $course = new Course();
            $course->name = $request->name;
            $course->majazi = $this->cleanUrl($request->majazi);
            $course->max_session = 16;
            $course->code = $code;
            $course->save();
            
            // Create associated records
            $this->createCourseAssociations($course);
            
            // Attach user as teacher
            $course->users()->attach($user, ['role_id' => $teacherRole->id]);
            
            // Handle copying sessions if needed
            if ($request->filled('copy')) {
                $this->copyCourseSessions($request->copy, $course->id);
            }
            
            DB::commit();
            
            $message = "دانشجوی عزیز، برای دسترسی به درس " . $course->name . 
                      " ابتدا از طریق سایت WWW.MALISAN.IR در سامانه آموزشی ملیسان با هویت واقعی ثبت نام کنید، سپس با استفاده از شناسه " . 
                      $course->code . " در درس ذکر شده عضو شوید.";
            
            return redirect()->route('courses.list')
                ->with('success', $message)
                ->with('crete', 'ok');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Course creation failed: ' . $e->getMessage());
            return back()->with('error', 'خطایی در سرور رخ داده است: ' . $e->getMessage());
        }
    }

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