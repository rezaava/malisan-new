<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Discussion;
use App\Models\Exercise;
use App\Models\ExerciseAnswer;
use App\Models\Question;
use App\Models\Score;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JudgmentController extends Controller
{
    /**
     * صفحه اصلی داوری
     */
    public function index()
    {
        $user = Auth::user();

        // دریافت آیتم‌های نیازمند داوری
        $items = $this->getPendingItems($user->id);

        // آمار سریع (تغییر از ۵ به ۳)
        $stats = [
            'total' => count($items),
            'pending' => count(array_filter($items, function($item) {
                return $item['score_count'] < 3;  // تغییر از 5 به 3
            })),
            'done' => count(array_filter($items, function($item) {
                return $item['score_count'] >= 3; // تغییر از 5 به 3
            })),
        ];

        return view('teacher.judgment.index', compact('items', 'stats'));
    }

    /**
     * دریافت آیتم‌های نیازمند داوری
     */
    private function getPendingItems($userId)
    {
        $items = [];

        // 1. سوالات نیازمند داوری
        $questions = Question::whereNull('status')
            ->where('user_id', '!=', $userId)
            ->with('user')
            ->get();

        foreach ($questions as $question) {
            $scoreCount = Score::where('sub_id', $question->id)
                ->where('type', Score::TYPE_QUESTION)
                ->count();

            $hasJudged = Score::where('sub_id', $question->id)
                ->where('type', Score::TYPE_QUESTION)
                ->where('user_id', $userId)
                ->exists();

            if (!$hasJudged) {
                $items[] = [
                    'id' => $question->id,
                    'type' => 'question',
                    'type_code' => Score::TYPE_QUESTION,
                    'type_label' => 'سوال',
                    'title' => $question->question,
                    'user' => $question->user,
                    'created_at' => $question->created_at,
                    'data' => $question,
                    'answers' => [
                        ['label' => 'الف', 'value' => $question->answer1],
                        ['label' => 'ب', 'value' => $question->answer2],
                        ['label' => 'ج', 'value' => $question->answer3],
                        ['label' => 'د', 'value' => $question->answer4],
                    ],
                    'correct_answer' => $question->answer,
                    'score_count' => $scoreCount,
                    'has_judged' => $hasJudged,
                ];
            }
        }

        // 2. گزارش‌ها (بحث‌ها) نیازمند داوری
        $discussions = Discussion::whereNull('status')
            ->where('user_id', '!=', $userId)
            ->with('user')
            ->get();

        foreach ($discussions as $discussion) {
            $scoreCount = Score::where('sub_id', $discussion->id)
                ->where('type', Score::TYPE_DISCUSSION)
                ->count();

            $hasJudged = Score::where('sub_id', $discussion->id)
                ->where('type', Score::TYPE_DISCUSSION)
                ->where('user_id', $userId)
                ->exists();

            if (!$hasJudged) {
                $items[] = [
                    'id' => $discussion->id,
                    'type' => 'discussion',
                    'type_code' => Score::TYPE_DISCUSSION,
                    'type_label' => 'گزارش',
                    'title' => $discussion->title ?? 'بدون عنوان',
                    'text' => $discussion->text,
                    'user' => $discussion->user,
                    'created_at' => $discussion->created_at,
                    'data' => $discussion,
                    'score_count' => $scoreCount,
                    'has_judged' => $hasJudged,
                ];
            }
        }

        // 3. تکالیف نیازمند داوری
        $exerciseAnswers = ExerciseAnswer::whereNull('status')
            ->where('user_id', '!=', $userId)
            ->with(['user', 'exercise'])
            ->get();

        foreach ($exerciseAnswers as $answer) {
            $scoreCount = Score::where('sub_id', $answer->id)
                ->where('type', Score::TYPE_EXERCISE)
                ->count();

            $hasJudged = Score::where('sub_id', $answer->id)
                ->where('type', Score::TYPE_EXERCISE)
                ->where('user_id', $userId)
                ->exists();

            if (!$hasJudged) {
                $items[] = [
                    'id' => $answer->id,
                    'type' => 'exercise',
                    'type_code' => Score::TYPE_EXERCISE,
                    'type_label' => 'تکلیف',
                    'title' => $answer->exercise->text ?? 'بدون عنوان',
                    'answer_text' => $answer->answer,
                    'user' => $answer->user,
                    'created_at' => $answer->created_at,
                    'data' => $answer,
                    'score_count' => $scoreCount,
                    'has_judged' => $hasJudged,
                ];
            }
        }

        // مرتب‌سازی بر اساس تاریخ (جدیدترین اول)
        usort($items, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return $items;
    }

    /**
     * ثبت داوری
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'type' => 'required|in:question,discussion,exercise',
            'score' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        // دریافت کد نوع
        $typeCode = $this->getTypeCode($request->type);

        // ذخیره داوری
        $score = new Score();
        $score->user_id = $user->id;
        $score->sub_id = $request->item_id;
        $score->type = $typeCode;
        $score->score1 = $request->score;
        $score->score2 = 0;
        $score->score3 = 0;
        $score->comment1 = $request->comment;
        $score->comment2 = null;
        $score->comment3 = null;
        $score->description = null;
        $score->save();

        // بروزرسانی وضعیت آیتم بعد از ۵ داوری
        $this->updateItemStatus($request->item_id, $request->type);

        return redirect()->back()->with('success', 'داوری با موفقیت ثبت شد.');
    }

    /**
     * دریافت کد نوع
     */
    private function getTypeCode($type)
    {
        return match ($type) {
            'question' => Score::TYPE_QUESTION,
            'discussion' => Score::TYPE_DISCUSSION,
            'exercise' => Score::TYPE_EXERCISE,
            default => Score::TYPE_QUESTION,
        };
    }

    /**
     * بروزرسانی وضعیت آیتم بعد از ۳ داوری
     */
    private function updateItemStatus($itemId, $type)
    {
        $typeCode = $this->getTypeCode($type);

        $scoreCount = Score::where('sub_id', $itemId)
            ->where('type', $typeCode)
            ->count();

        // بعد از ۳ داوری، وضعیت مشخص می‌شود
        if ($scoreCount >= 3) {
            $scores = Score::where('sub_id', $itemId)
                ->where('type', $typeCode)
                ->pluck('score1');

            $average = $scores->avg();

            $status = match (true) {
                $average >= 4.5 => 1,
                $average >= 3.5 => 2,
                $average >= 2.5 => 3,
                default => 4,
            };

            if ($type == 'question') {
                Question::where('id', $itemId)->update([
                    'status' => $status,
                    'score' => round($average, 2)
                ]);
            } elseif ($type == 'discussion') {
                Discussion::where('id', $itemId)->update([
                    'status' => $status,
                    'score' => round($average, 2)
                ]);
            } elseif ($type == 'exercise') {
                ExerciseAnswer::where('id', $itemId)->update([
                    'status' => 'scored',
                    'rate' => $this->getRateLabel($status)
                ]);
            }
        }
    }
    /**
     * دریافت برچسب نمره بر اساس وضعیت
     */
    private function getRateLabel($status)
    {
        return match ($status) {
            1 => 'excellent', // عالی
            2 => 'good',      // خوب
            3 => 'medium',    // متوسط
            4 => 'weak',      // بد
            default => 'pending',
        };
    }
    /**
     * نمایش آمار داوری
     */
    public function stats()
    {
        // آمار سوالات
        $questionStats = [
            'total' => Question::count(),
            'pending' => Question::whereNull('status')->count(),
            'approved' => Question::whereIn('status', [1, 2])->count(),
            'rejected' => Question::whereIn('status', [3, 4])->count(),
        ];

        // آمار گزارش‌ها
        $discussionStats = [
            'total' => Discussion::count(),
            'pending' => Discussion::whereNull('status')->count(),
            'approved' => Discussion::whereIn('status', [1, 2])->count(),
            'rejected' => Discussion::whereIn('status', [3, 4])->count(),
        ];

        // آمار تکالیف
        $exerciseIds = Exercise::pluck('id');
        $exerciseStats = [
            'total' => ExerciseAnswer::whereIn('exercise_id', $exerciseIds)->count(),
            'pending' => ExerciseAnswer::whereIn('exercise_id', $exerciseIds)->whereNull('status')->count(),
            'scored' => ExerciseAnswer::whereIn('exercise_id', $exerciseIds)->where('status', 'scored')->count(),
        ];

        return view('teacher.judgment.stats', compact('questionStats', 'discussionStats', 'exerciseStats'));
    }

    /**
     * حذف داوری (برای ادمین)
     */
    public function destroy($id)
    {
        $score = Score::findOrFail($id);
        $score->delete();

        return redirect()->back()->with('success', 'داوری با موفقیت حذف شد.');
    }
}