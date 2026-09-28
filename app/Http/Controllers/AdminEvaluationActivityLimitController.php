<?php

namespace App\Http\Controllers;

use App\Models\EvaluationActivityLimit;
use Illuminate\Http\Request;

class AdminEvaluationActivityLimitController extends Controller
{
    private function getActivity()
    {
        return EvaluationActivityLimit::firstOrCreate(
            ['id' => 1],
            [
                'judging_quality' => 15,
                'self_test_quality' => 25,
                'report_quality' => 15,
                'question_quality' => 15,
                'self_test_participation' => 9,
                'judging_completion' => 8,
                'report_submission' => 5,
                'question_creation' => 8,
            ]
        );
    }

    public function index()
    {
        $activity = $this->getActivity();

        $activities = [
            [
                'key' => 'judging_quality',
                'name' => 'کیفیت داوری',
                'score' => $activity->judging_quality,
            ],
            [
                'key' => 'self_test_quality',
                'name' => 'کیفیت خودآزمایی',
                'score' => $activity->self_test_quality,
            ],
            [
                'key' => 'report_quality',
                'name' => 'کیفیت گزارش',
                'score' => $activity->report_quality,
            ],
            [
                'key' => 'question_quality',
                'name' => 'کیفیت سوال',
                'score' => $activity->question_quality,
            ],
            [
                'key' => 'self_test_participation',
                'name' => 'شرکت در خودآزمایی',
                'score' => $activity->self_test_participation,
            ],
            [
                'key' => 'judging_completion',
                'name' => 'انجام داوری',
                'score' => $activity->judging_completion,
            ],
            [
                'key' => 'report_submission',
                'name' => 'ارسال گزارش',
                'score' => $activity->report_submission,
            ],
            [
                'key' => 'question_creation',
                'name' => 'طرح سوال',
                'score' => $activity->question_creation,
            ],
        ];

        $totalScore = collect($activities)->sum('score');

        return view(
            'admin.evaluation_activity_limits.index',
            compact('activity', 'activities', 'totalScore')
        );
    }

    public function update(Request $request)
    {
        $validated = $request->validate(
            [
                'judging_quality' => 'required|integer|min:0',
                'self_test_quality' => 'required|integer|min:0',
                'report_quality' => 'required|integer|min:0',
                'question_quality' => 'required|integer|min:0',
                'self_test_participation' => 'required|integer|min:0',
                'judging_completion' => 'required|integer|min:0',
                'report_submission' => 'required|integer|min:0',
                'question_creation' => 'required|integer|min:0',
            ],
            [
                'judging_quality.required' => 'مقدار کیفیت داوری الزامی است.',
                'self_test_quality.required' => 'مقدار کیفیت خودآزمایی الزامی است.',
                'report_quality.required' => 'مقدار کیفیت گزارش الزامی است.',
                'question_quality.required' => 'مقدار کیفیت سوال الزامی است.',
                'self_test_participation.required' => 'مقدار شرکت در خودآزمایی الزامی است.',
                'judging_completion.required' => 'مقدار انجام داوری الزامی است.',
                'report_submission.required' => 'مقدار ارسال گزارش الزامی است.',
                'question_creation.required' => 'مقدار طرح سوال الزامی است.',

                '*.integer' => 'تمام مقادیر باید عدد باشند.',
                '*.min' => 'مقادیر نمی‌توانند منفی باشند.',
            ]
        );

        $totalScore = collect($validated)->sum();

        if ($totalScore !== 100) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'total_score' => "مجموع امتیازها باید دقیقاً ۱۰۰ باشد. مجموع فعلی: {$totalScore}"
                ]);
        }

        $activity = $this->getActivity();

        $activity->update($validated);

        return redirect()
            ->route('admin.evaluation-activity-limits')
            ->with('success', 'مقادیر فعالیت‌ها با موفقیت ذخیره شد.');
    }
}