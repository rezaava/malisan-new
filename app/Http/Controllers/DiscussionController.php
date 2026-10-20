<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Session;
use App\Models\Setting;
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

        $setting = Setting::where('course_id', $course->id)->first();

        if ($setting && $setting->gozaresh_last == 1) {
            $lastSession = Session::where('course_id', $course->id)
                ->orderBy('id', 'desc')
                ->first();

            if (!$lastSession || $session->id != $lastSession->id) {
                return redirect()->back()
                    ->with('error', 'شما فقط می‌توانید برای آخرین جلسه این درس گزارش ارسال کنید.');
            }
        }

        $gozareshs = Discussion::where('user_id', Auth::id())
            ->where('session_id', $sessionId)
            ->count();

        if ($setting && $setting->max_gozaresh <= $gozareshs) {
            return redirect()->back()
                ->with('error', 'شما حداکثر گزارش برای این جلسه را ارسال کردید.');
        }

        $discussion = null;

        return view('student.discussion-create', compact(
            'session',
            'course',
            'discussion'
        ));
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $discussion = new Discussion;
        $discussion->user_id = Auth::id();
        $discussion->session_id = $request->session_id;
        $discussion->text = $request->text;
        $discussion->save();

        return redirect()
            ->route('view.coure.St', $discussion->session->course_id)
            ->with('success', 'گزارش شما با موفقیت ثبت شد.');
    }

    /**
     * نمایش فرم ویرایش گزارش
     */
    public function edit($id)
    {
        $discussion = Discussion::where('id', $id)
            ->where('user_id', Auth::id())
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 0);
            })
            ->firstOrFail();

        $discussion->load('session.course');

        $session = $discussion->session;
        $course = $session->course;

        return view('student.discussion-create', compact(
            'discussion',
            'session',
            'course'
        ));
    }

    /**
     * ذخیره تغییرات گزارش
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $discussion = Discussion::where('id', $id)
            ->where('user_id', Auth::id())
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 0);
            })
            ->firstOrFail();

        $discussion->text = $request->text;
        $discussion->status = null;
        $discussion->save();

        return redirect()
            ->route('view.coure.St', $discussion->session->course_id)
            ->with('success', 'گزارش شما با موفقیت اصلاح و برای داوری مجدد ارسال شد.');
    }
}