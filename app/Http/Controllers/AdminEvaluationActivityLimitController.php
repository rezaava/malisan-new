<?php

namespace App\Http\Controllers;

use App\Models\EvaluationActivityLimit;
use Illuminate\Http\Request;

class AdminEvaluationActivityLimitController extends Controller
{
    public function index()
    {
        $activities = EvaluationActivityLimit::orderBy('id', 'desc')->get();

        return view('admin.evaluation_activity_limits.index', compact('activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity' => 'required|string|max:255',
            'max_score' => 'required|integer|min:0',
        ], [
            'activity.required' => 'وارد کردن فعالیت الزامی است.',
            'max_score.required' => 'وارد کردن سقف امتیاز الزامی است.',
            'max_score.integer' => 'سقف امتیاز باید عدد باشد.',
            'max_score.min' => 'سقف امتیاز نمی‌تواند منفی باشد.',
        ]);

        $activity = new EvaluationActivityLimit();
        $activity->activity = $request->activity;
        $activity->max_score = $request->max_score;
        $activity->save();

        return redirect()->route('admin.evaluation-activity-limits')->with('success', 'فعالیت با موفقیت اضافه شد.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'activity' => 'required|string|max:255',
            'max_score' => 'required|integer|min:0',
        ], [
            'activity.required' => 'وارد کردن فعالیت الزامی است.',
            'max_score.required' => 'وارد کردن سقف امتیاز الزامی است.',
            'max_score.integer' => 'سقف امتیاز باید عدد باشد.',
            'max_score.min' => 'سقف امتیاز نمی‌تواند منفی باشد.',
        ]);

        $activity = EvaluationActivityLimit::findOrFail($id);

        $activity->activity = $request->activity;
        $activity->max_score = $request->max_score;
        $activity->save();

        return redirect()->route('admin.evaluation-activity-limits')->with('success', 'فعالیت با موفقیت ویرایش شد.');
    }
}