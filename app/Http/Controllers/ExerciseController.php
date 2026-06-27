<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\ExerciseAnswer;
use App\Models\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExerciseController extends Controller
{
        public function studentShow($sessionId)
    {
        $user = Auth::user();
        $session = Session::with('course')->findOrFail($sessionId);
        $course = $session->course;
        
        $exercises = Exercise::where('session_id', $session->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // دریافت پاسخ‌های کاربر برای هر تمرین
        foreach ($exercises as $exercise) {
            $answer = ExerciseAnswer::where('exercise_id', $exercise->id)
                ->where('user_id', $user->id)
                ->first();
            $exercise['user_answer'] = $answer;
        }
        
        return view('student.exercises', compact('session', 'course', 'exercises'));
    }

    /**
     * نمایش لیست تمرین‌های یک جلسه (برای استاد)
     */
    public function show($sessionId)
    {
        $user = Auth::user();
        $session = Session::with('course')->findOrFail($sessionId);
        $course = $session->course;
        
        $exercises = Exercise::where('session_id', $session->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        foreach ($exercises as $exercise) {
            $answer = ExerciseAnswer::where('exercise_id', $exercise->id)
                ->where('user_id', $user->id)
                ->first();
            $exercise['user_answer'] = $answer;
            
            // تعداد پاسخ‌های دانشجویان (برای استاد)
            $exercise['answers_count'] = ExerciseAnswer::where('exercise_id', $exercise->id)->count();
        }
        
        return view('teacher.exercises', compact('session', 'course', 'exercises'));
    }

    /**
     * ارسال پاسخ تمرین (دانشجو)
     */
    public function answer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exercise_id' => 'required|exists:exercises,id',
            'text' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        
        $answer = ExerciseAnswer::where('exercise_id', $request->exercise_id)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$answer) {
            $answer = new ExerciseAnswer();
        }

        $answer->user_id = $user->id;
        $answer->exercise_id = $request->exercise_id;
        $answer->answer = $request->text;

        if ($request->hasFile('file')) {
            if ($answer->file && file_exists(public_path($answer->file))) {
                unlink(public_path($answer->file));
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('files/answers');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $file->move($destinationPath, $fileName);
            $answer->file = 'files/answers/' . $fileName;
        }

        $answer->save();

        return redirect()->back()->with('success', 'پاسخ شما با موفقیت ثبت شد.');
    }

    /**
     * ویرایش پاسخ (دانشجو)
     */
    public function updateAnswer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $answer = ExerciseAnswer::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $answer->text = $request->text;

        if ($request->hasFile('file')) {
            if ($answer->file && file_exists(public_path($answer->file))) {
                unlink(public_path($answer->file));
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('files/answers');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $file->move($destinationPath, $fileName);
            $answer->file = 'files/answers/' . $fileName;
        }

        $answer->save();

        return redirect()->back()->with('success', 'پاسخ شما با موفقیت بروزرسانی شد.');
    }

    /**
     * حذف پاسخ (دانشجو)
     */
    public function deleteAnswer($id)
    {
        $user = Auth::user();
        $answer = ExerciseAnswer::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        if ($answer->file && file_exists(public_path($answer->file))) {
            unlink(public_path($answer->file));
        }
        
        $answer->delete();

        return redirect()->back()->with('success', 'پاسخ شما با موفقیت حذف شد.');
    }

    /**
     * ایجاد تمرین جدید (استاد)
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|min:5',
            'session_id' => 'required|exists:sessions,id',
            'file' => 'nullable|file|max:10240', // حداکثر 10MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        $exercise = new Exercise();
        $exercise->text = $request->text;
        $exercise->user_id = $user->id;
        $exercise->session_id = $request->session_id;

        // آپلود فایل
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('files/exercises');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $file->move($destinationPath, $fileName);
            $exercise->file = 'files/exercises/' . $fileName;
        }

        $exercise->save();

        return redirect()->back()->with('success', 'تمرین با موفقیت ایجاد شد.');
    }

    /**
     * نمایش فرم ویرایش تمرین (استاد)
     */
    public function edit(Request $request)
    {
        $exercise = Exercise::with('session.course')->findOrFail($request->exercise_id);
        $session = $exercise->session;
        $course = $session->course;
        
        return view('teacher.exercise-edit', compact('exercise', 'session', 'course'));
    }

    /**
     * بروزرسانی تمرین (استاد)
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|min:5',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $exercise = Exercise::findOrFail($id);
        $exercise->text = $request->text;

        // آپلود فایل جدید
        if ($request->hasFile('file')) {
            // حذف فایل قبلی
            if ($exercise->file && file_exists(public_path($exercise->file))) {
                unlink(public_path($exercise->file));
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('files/exercises');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $file->move($destinationPath, $fileName);
            $exercise->file = 'files/exercises/' . $fileName;
        }

        $exercise->save();

        return redirect()->route('exercise.show', $exercise->session_id)
            ->with('success', 'تمرین با موفقیت بروزرسانی شد.');
    }

    /**
     * حذف تمرین (استاد)
     */
    public function delete($id)
    {
        $exercise = Exercise::findOrFail($id);
        $sessionId = $exercise->session_id;
        
        // حذف فایل
        if ($exercise->file && file_exists(public_path($exercise->file))) {
            unlink(public_path($exercise->file));
        }
        
        // حذف پاسخ‌های مرتبط
        ExerciseAnswer::where('exercise_id', $id)->delete();
        
        $exercise->delete();

        return redirect()->route('exercise.show', $sessionId)
            ->with('success', 'تمرین با موفقیت حذف شد.');
    }

    /**
     * ثبت نمره (استاد)
     */
    public function score(Request $request)
    {
        // return $request->all();
        $request->validate([
            'answer_id' => 'required|exists:exercise_answers,id',
            'rate' => 'required',
            'comment' => 'nullable|string',
        ]);

        $answer = ExerciseAnswer::findOrFail($request->answer_id);
        $answer->comment = $request->comment;
        $answer->status = $request->rate;
        $answer->save();

        return redirect()->back()->with('success', 'نمره با موفقیت ثبت شد.');
    }

    /**
     * نمایش همه پاسخ‌های یک تمرین (استاد)
     */
    public function answersList($exerciseId)
    {
        $exercise = Exercise::with('session.course')->findOrFail($exerciseId);
        $session = $exercise->session;
        $course = $session->course;
        
        $answers = ExerciseAnswer::where('exercise_id', $exerciseId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('teacher.exercise-answers', compact('exercise', 'session', 'course', 'answers'));
    }
}