<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Session;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DiscussionController extends Controller
{
    /**
     * نمایش فرم ارسال گزارش
     */
    public function create($sessionId)
    {
        $session = Session::with('course')->findOrFail($sessionId);
        $course = $session->course;
        
        return view('student.discussion-create', compact('session', 'course'));
    }

    /**
     * ذخیره گزارش جدید
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:sessions,id',
            'text' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $discussion = new Discussion();
        $discussion->user_id = Auth::id();
        $discussion->session_id = $request->session_id;
        $discussion->text = $request->text;
        $discussion->save();

        return redirect()->route('view.coure.St', $discussion->session->course_id)
            ->with('success', 'گزارش شما با موفقیت ثبت شد.');
    }
}