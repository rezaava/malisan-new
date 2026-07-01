<?php

namespace App\Http\Controllers;

use App\Models\CourseUser;
use App\Models\Discussion;
use App\Models\Exercise;
use App\Models\ExerciseAnswer;
use App\Models\Question;
use App\Models\ScoreDiscussion;
use App\Models\ScoreExercise;
use App\Models\ScoreQuestion;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class JudgmentController extends Controller
{
    /**
     * صفحه اصلی داوری (برای دانشجویان)
     */
    public function index()
    {
        $user = Auth::user();
        
        // فقط دانشجویان می‌توانند داوری کنند
        if (!$user->hasRole('student')) {
            return redirect()->back()->with('error', 'فقط دانشجویان می‌توانند داوری کنند.');
        }

        // دریافت آیتم‌های نیازمند داوری (که خودش نفرستاده)
        $items = $this->getPendingItems($user->id);

        $stats = [
            'total' => count($items),
            'pending' => count(array_filter($items, function($item) {
                return $item['score_count'] < 3;
            })),
            'done' => count(array_filter($items, function($item) {
                return $item['score_count'] >= 3;
            })),
            'my_judgments' => $this->getMyJudgmentsCount($user->id),
        ];

        return view('student.judgment.index', compact('items', 'stats'));
    }
    private function getPendingItems($userId)
    {
        $items = [];

        // دریافت آی‌دی درس‌هایی که کاربر در آنها ثبت نام کرده
        $courseIds = CourseUser::where('user_id', $userId)
            ->pluck('course_id')
            ->toArray();

        if (empty($courseIds)) {
            return $items; // اگر کاربر در هیچ درسی ثبت نام نکرده
        }

        // دریافت آی‌دی جلسات این درس‌ها
        $sessionIds = Session::whereIn('course_id', $courseIds)
            ->pluck('id')
            ->toArray();

        if (empty($sessionIds)) {
            return $items; // اگر هیچ جلسه‌ای وجود ندارد
        }

        // ==========================================
        // 1. سوالات نیازمند داوری (به جز سوالات خودش)
        // فقط از جلسات درس‌هایی که کاربر ثبت نام کرده
        // ==========================================
        $questions = Question::whereIn('session_id', $sessionIds)
            ->whereNull('status')
            ->where('user_id', '!=', $userId)
            ->with(['user', 'session.course'])
            ->get();

        foreach ($questions as $question) {
            $scoreCount = ScoreQuestion::where('question_id', $question->id)
                ->where('status', ScoreQuestion::STATUS_APPROVED)
                ->count();

            $hasJudged = ScoreQuestion::where('question_id', $question->id)
                ->where('user_id', $userId)
                ->exists();

            if (!$hasJudged && $scoreCount < 3) {
                $items[] = [
                    'id' => $question->id,
                    'type' => 'question',
                    'type_label' => 'سوال',
                    'title' => $question->question,
                    'user' => $question->user,
                    'course_name' => $question->session->course->name ?? 'نامشخص',
                    'session_name' => $question->session->name ?? 'نامشخص',
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

        // ==========================================
        // 2. گزارش‌ها (بحث‌ها) نیازمند داوری
        // فقط از جلسات درس‌هایی که کاربر ثبت نام کرده
        // ==========================================
        $discussions = Discussion::whereIn('session_id', $sessionIds)
            ->whereNull('status')
            ->where('user_id', '!=', $userId)
            ->with(['user', 'session.course'])
            ->get();

        foreach ($discussions as $discussion) {
            $scoreCount = ScoreDiscussion::where('discussion_id', $discussion->id)
                ->where('status', 'approved')
                ->count();

            $hasJudged = ScoreDiscussion::where('discussion_id', $discussion->id)
                ->where('user_id', $userId)
                ->exists();

            if (!$hasJudged && $scoreCount < 3) {
                $items[] = [
                    'id' => $discussion->id,
                    'type' => 'discussion',
                    'type_label' => 'گزارش',
                    'title' => $discussion->session->name,
                    'text' => $discussion->text,
                    'user' => $discussion->user,
                    'course_name' => $discussion->session->course->name ?? 'نامشخص',
                    'session_name' => $discussion->session->name ?? 'نامشخص',
                    'created_at' => $discussion->created_at,
                    'data' => $discussion,
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
     * تعداد داوری‌های من
     */
    private function getMyJudgmentsCount($userId)
    {
        $count = ScoreQuestion::where('user_id', $userId)->count();
        $count += ScoreDiscussion::where('user_id', $userId)->count();
        $count += ScoreExercise::where('user_id', $userId)->count();
        return $count;
    }

    /**
     * ثبت داوری دانشجو
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'type' => 'required|in:question,discussion,exercise',
            'score' => 'nullable|integer|min:1|max:3',
            'negaresh' => 'nullable|boolean',
            'gozine' => 'nullable|boolean',
            'dark' => 'nullable|boolean',
            'comment' => 'nullable|string|max:500',
            'action' => 'required|in:approve,reject',
        ]);

        $user = Auth::user();

        // بررسی اینکه کاربر نمی‌تواند سوال خودش را داوری کند
        if ($this->isOwnItem($request->item_id, $request->type, $user->id)) {
            return redirect()->back()->with('error', 'شما نمی‌توانید سوال/گزارش/تکلیف خودتان را داوری کنید.');
        }

        // بررسی اینکه کاربر قبلاً این آیتم را داوری نکرده باشد
        if ($this->hasAlreadyJudged($request->item_id, $request->type, $user->id)) {
            return redirect()->back()->with('error', 'شما قبلاً این آیتم را داوری کرده‌اید.');
        }

        // ذخیره داوری
        $result = $this->saveJudgment($request, $user);

        if (!$result) {
            return redirect()->back()->with('error', 'خطا در ثبت داوری');
        }

        // بروزرسانی وضعیت آیتم بعد از ۳ داوری تایید شده
        $this->updateItemStatus($request->item_id, $request->type);

        // اگر کاربر رد کرده باشد، آیتم را به کاربر برگشت بده
        if ($request->action === 'reject') {
            $this->returnItemToUser($request->item_id, $request->type, $request->comment);
        }

        return redirect()->back()->with('success', 'داوری با موفقیت ثبت شد.');
    }

    /**
     * بررسی اینکه آیا آیتم متعلق به خود کاربر است
     */
    private function isOwnItem($itemId, $type, $userId)
    {
        switch ($type) {
            case 'question':
                $item = Question::find($itemId);
                return $item && $item->user_id == $userId;
            case 'discussion':
                $item = Discussion::find($itemId);
                return $item && $item->user_id == $userId;
            case 'exercise':
                $item = ExerciseAnswer::find($itemId);
                return $item && $item->user_id == $userId;
            default:
                return false;
        }
    }

    /**
     * بررسی اینکه کاربر قبلاً این آیتم را داوری کرده است
     */
    private function hasAlreadyJudged($itemId, $type, $userId)
    {
        switch ($type) {
            case 'question':
                return ScoreQuestion::where('question_id', $itemId)
                    ->where('user_id', $userId)
                    ->exists();
            case 'discussion':
                return ScoreDiscussion::where('discussion_id', $itemId)
                    ->where('user_id', $userId)
                    ->exists();
            case 'exercise':
                return ScoreExercise::where('exercise_answer_id', $itemId)
                    ->where('user_id', $userId)
                    ->exists();
            default:
                return false;
        }
    }

    /**
     * ذخیره داوری در جدول مناسب
     */
    private function saveJudgment($request, $user)
    {
        // تبدیل action به status معتبر
        $status = match ($request->action) {
            'approve' => 'approved',
            'reject' => 'rejected',
            'return' => 'returned',
            default => 'pending',
        };

        $data = [
            'user_id' => $user->id,
            'comment' => $request->comment,
            'negaresh' => $request->negaresh ?? 0,
            'gozine' => $request->gozine ?? 0,
            'dark' => $request->dark ?? 0,
            'status' => $status,  // استفاده از مقدار معتبر
        ];

        if ($request->action === 'approve') {
            $data['score'] = $request->score;
        }

        switch ($request->type) {
            case 'question':
                $data['question_id'] = $request->item_id;
                return ScoreQuestion::create($data);
                
            case 'discussion':
                $data['discussion_id'] = $request->item_id;
                return ScoreDiscussion::create($data);
                
            case 'exercise':
                $data['exercise_answer_id'] = $request->item_id;
                return ScoreExercise::create($data);
                
            default:
                return false;
        }
    }

    /**
     * برگشت آیتم به کاربر (برای اصلاح)
     */

    private function returnItemToUser($itemId, $type, $comment)
    {
        switch ($type) {
            case 'question':
                Question::where('id', $itemId)->update([
                    'status' => 0,  // 0 به معنی برگشت خورده
                    'comment' => $comment,
                ]);
                break;
            case 'discussion':
                Discussion::where('id', $itemId)->update([
                    'status' => 0,  // 0 به معنی برگشت خورده
                    'comment' => $comment,
                ]);
                break;
            case 'exercise':
                ExerciseAnswer::where('id', $itemId)->update([
                    'status' => 'returned',  // در exercise_answers از string استفاده می‌شود
                    'comment' => $comment,
                ]);
                break;
        }
    }

    /**
     * بروزرسانی وضعیت آیتم بعد از ۳ داوری تایید شده
     */
    private function updateItemStatus($itemId, $type)
    {
        $scores = [];

        switch ($type) {
            case 'question':
                $scores = ScoreQuestion::where('question_id', $itemId)
                    ->where('status', ScoreQuestion::STATUS_APPROVED)  // استفاده از ثابت
                    ->pluck('score')
                    ->toArray();
                break;
                
            case 'discussion':
                $scores = ScoreDiscussion::where('discussion_id', $itemId)
                    ->where('status', ScoreDiscussion::STATUS_APPROVED)
                    ->pluck('score')
                    ->toArray();
                break;
                
            case 'exercise':
                $scores = ScoreExercise::where('exercise_answer_id', $itemId)
                    ->where('status', ScoreExercise::STATUS_APPROVED)
                    ->pluck('score')
                    ->toArray();
                break;
        }



        // اگر ۳ داور تایید کرده باشند
        if (count($scores) >= 3) {
            $average = array_sum($scores) / count($scores);
            
            $status = match (true) {
                $average >= 2.5 => 3,
                $average >= 1.6 => 2,
                $average >= 1 => 1, 
                default => 4,
            };

            switch ($type) {
                case 'question':
                    Question::where('id', $itemId)->update([
                        'status' => $status,
                        'score' => round($average, 2)
                    ]);
                    break;
                    
                case 'discussion':
                    Discussion::where('id', $itemId)->update([
                        'status' => $status,
                        'score' => round($average, 2)
                    ]);
                    break;
                    
                case 'exercise':
                    ExerciseAnswer::where('id', $itemId)->update([
                        'status' => 'scored',
                        'rate' => $this->getRateLabel($status)
                    ]);
                    break;
            }
        }
    }

    /**
     * دریافت برچسب نمره بر اساس وضعیت
     */
    private function getRateLabel($status)
    {
        return match ($status) {
            1 => 'excellent',
            2 => 'good',
            3 => 'medium',
            4 => 'weak',
            default => 'pending',
        };
    }

    /**
     * نمایش آمار داوری (برای دانشجو)
     */
    public function stats()
    {
        $user = Auth::user();
        
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
        $exerciseStats = [
            'total' => ExerciseAnswer::count(),
            'pending' => ExerciseAnswer::whereNull('status')->count(),
            'scored' => ExerciseAnswer::where('status', 'scored')->count(),
        ];

        // آمار داوری‌های من
        $myStats = [
            'questions' => ScoreQuestion::where('user_id', $user->id)->count(),
            'discussions' => ScoreDiscussion::where('user_id', $user->id)->count(),
            'exercises' => ScoreExercise::where('user_id', $user->id)->count(),
        ];

        return view('student.judgment.stats', compact('questionStats', 'discussionStats', 'exerciseStats', 'myStats'));
    }

    /**
     * حذف داوری (برای ادمین)
     */
    public function destroy($id)
    {
        $score = ScoreQuestion::find($id) 
            ?? ScoreDiscussion::find($id) 
            ?? ScoreExercise::find($id);
            
        if ($score) {
            $score->delete();
            return redirect()->back()->with('success', 'داوری با موفقیت حذف شد.');
        }

        return redirect()->back()->with('error', 'داوری یافت نشد.');
    }

    /**
     * مشاهده آیتم‌های برگشت خورده (برای دانشجو)
     */
    public function returnedItems()
    {
        $user = Auth::user();
        
        $returnedQuestions = Question::where('user_id', $user->id)
            ->where('status', 0)  // 0 = برگشت خورده
            ->get();
            
        $returnedDiscussions = Discussion::where('user_id', $user->id)
            ->where('status', 0)  // 0 = برگشت خورده
            ->get();
            
        $returnedExercises = ExerciseAnswer::where('user_id', $user->id)
            ->where('status', 'returned')  // در exercise_answers
            ->get();

        return view('student.judgment.returned', compact(
            'returnedQuestions',
            'returnedDiscussions',
            'returnedExercises'
        ));
    }

    /**
     * اصلاح و ارسال مجدد آیتم برگشت خورده
     */
    public function resubmit(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'type' => 'required|in:question,discussion,exercise',
            'text' => 'required|string|min:5',
        ]);

        $user = Auth::user();

        switch ($request->type) {
            case 'question':
                $item = Question::where('id', $request->item_id)
                    ->where('user_id', $user->id)
                    ->where('status', 0)  // 0 = برگشت خورده
                    ->firstOrFail();
                $item->question = $request->text;
                $item->status = null;  // برگشت به حالت انتظار داوری
                $item->comment = null; // پاک کردن کامنت برگشت
                $item->save();
                break;
                
            case 'discussion':
                $item = Discussion::where('id', $request->item_id)
                    ->where('user_id', $user->id)
                    ->where('status', 0)  // 0 = برگشت خورده
                    ->firstOrFail();
                $item->text = $request->text;
                $item->status = null;
                $item->comment = null;
                $item->save();
                break;
                
            case 'exercise':
                $item = ExerciseAnswer::where('id', $request->item_id)
                    ->where('user_id', $user->id)
                    ->where('status', 'returned')
                    ->firstOrFail();
                $item->answer = $request->text;
                $item->status = null;
                $item->comment = null;
                $item->save();
                break;
        }

        return redirect()->back()->with('success', 'آیتم با موفقیت اصلاح و ارسال شد.');
    }
}