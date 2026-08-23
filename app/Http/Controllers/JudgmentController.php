<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Discussion;
use App\Models\Exercise;
use App\Models\ExerciseAnswer;
use App\Models\Question;
use App\Models\ScoreDiscussion;
use App\Models\ScoreExercise;
use App\Models\ScoreQuestion;
use App\Models\Session;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class JudgmentController extends Controller
{
    /**
     * صفحه اصلی داوری (برای دانشجویان)
     */
    public function index($course_id = null)
    {
        return 'a';
        $user = Auth::user();

        // ===== بررسی محدودیت روزانه =====
        $dailyLimitReached = false;
        $warningMessage = null;

        if ($course_id) {
            // فقط محدودیت درس مشخص شده را بررسی کن
            $setting = Setting::where('course_id', $course_id)->first();
            $dailyLimit = $setting->daily_judgment_limit ?? 5;

            if ($dailyLimit > 0) {
                $todayCount = $this->getTodayJudgmentsCount($user->id, $course_id);
                if ($todayCount >= $dailyLimit) {
                    $dailyLimitReached = true;
                    $course = Course::find($course_id);
                    $warningMessage = "سقف داوری‌های مجاز امروز شما برای درس {$course->name} به پایان رسید. برای ادامهٔ داوری فعالیت‌های دوستان‌تان، فردا نیز سری بزنید.";
                }
            }
        } else {
            // محدودیت همه دروس را بررسی کن (همانند قبل)
            $courseIds = CourseUser::where('user_id', $user->id)
                ->pluck('course_id')
                ->toArray();

            if (!empty($courseIds)) {
                $courseIds = Course::whereIn('id', $courseIds)
                    ->where('davari', 1)
                    ->pluck('id')
                    ->toArray();

                foreach ($courseIds as $cId) {
                    $setting = Setting::where('course_id', $cId)->first();
                    $dailyLimit = $setting->daily_judgment_limit ?? 5;

                    if ($dailyLimit > 0) {
                        $todayCount = $this->getTodayJudgmentsCount($user->id, $cId);
                        if ($todayCount >= $dailyLimit) {
                            $dailyLimitReached = true;
                            $course = Course::find($cId);
                            $warningMessage = "سقف داوری‌های مجاز امروز شما برای درس {$course->name} به پایان رسید. برای ادامهٔ داوری فعالیت‌های دوستان‌تان، فردا نیز سری بزنید.";
                            break;
                        }
                    }
                }
            }
        }

        // دریافت آیتم‌ها (با فیلتر course_id در صورت وجود)
        $result = $this->getPendingItems($user->id, $course_id);
        $items = $result['items'];
        $totalPending = $result['total'];

        // اگر کاربر دانشجو است، فقط یک آیتم تصادفی نمایش داده شود
        if ($user->hasRole('student') && !empty($items)) {
            $randomIndex = array_rand($items);
            $items = [$items[$randomIndex]];
        }

        $stats = [
            'total' => $totalPending,
            'pending' => $totalPending,
            'done' => 0,
            'my_judgments' => $this->getMyJudgmentsCount($user->id),
        ];

        if ($dailyLimitReached) {
            session()->flash('warning', $warningMessage);
        }

        return view('student.judgment.index', compact('items', 'stats'));
    }

    /**
     * دریافت آیتم‌های نیازمند داوری (با محدودیت ۴۰ تایی)
     */
    private function getPendingItems($userId, $courseId = null)
    {
        $items = [];

        if ($courseId) {
            // بررسی اینکه کاربر در این درس ثبت‌نام کرده و davari=1 است
            $course = Course::where('id', $courseId)
                ->where('davari', 1)
                ->first();

            if (!$course) {
                return ['total' => 0, 'items' => []];
            }

            // بررسی عضویت کاربر در این درس
            $isEnrolled = CourseUser::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->exists();

            if (!$isEnrolled) {
                return ['total' => 0, 'items' => []];
            }

            // دریافت جلسات این درس
            $sessionIds = Session::where('course_id', $courseId)
                ->pluck('id')
                ->toArray();

            if (empty($sessionIds)) {
                return ['total' => 0, 'items' => []];
            }

        } else {
            // دریافت آی‌دی درس‌هایی که کاربر در آنها ثبت نام کرده و davari=1 دارند
            $courseIds = CourseUser::where('user_id', $userId)
                ->pluck('course_id')
                ->toArray();

            if (empty($courseIds)) {
                return ['total' => 0, 'items' => []];
            }

            $courseIds = Course::whereIn('id', $courseIds)
                ->where('davari', 1)
                ->pluck('id')
                ->toArray();

            if (empty($courseIds)) {
                return ['total' => 0, 'items' => []];
            }

            // دریافت آی‌دی جلسات این درس‌ها
            $sessionIds = Session::whereIn('course_id', $courseIds)
                ->pluck('id')
                ->toArray();

            if (empty($sessionIds)) {
                return ['total' => 0, 'items' => []];
            }
        }

        // ==========================================
        // 1. سوالات نیازمند داوری (به جز سوالات خودش)
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

        // ذخیره تعداد کل و اعمال محدودیت ۴۰ تایی
        $total = count($items);
        $maxItems = 40;
        if (count($items) > $maxItems) {
            $items = array_slice($items, 0, $maxItems);
        }

        return [
            'total' => $total,
            'items' => $items
        ];
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

    // ==========================================
    // متدهای کمکی برای محدودیت روزانه
    // ==========================================

    /**
     * دریافت course_id از آیتم بر اساس نوع
     */
    private function getCourseIdFromItem($itemId, $type)
    {
        switch ($type) {
            case 'question':
                $question = Question::with('session.course')->find($itemId);
                return $question?->session?->course_id;
            case 'discussion':
                $discussion = Discussion::with('session.course')->find($itemId);
                return $discussion?->session?->course_id;
            case 'exercise':
                $exerciseAnswer = ExerciseAnswer::with('exercise.course')->find($itemId);
                return $exerciseAnswer?->exercise?->course_id;
            default:
                return null;
        }
    }

    /**
     * تعداد داوری‌های امروز کاربر برای یک درس خاص
     */
    private function getTodayJudgmentsCount($userId, $courseId)
    {
        $today = now()->toDateString();

        // داوری‌های سوالات
        $questionCount = ScoreQuestion::where('score_questions.user_id', $userId)
            ->whereDate('score_questions.created_at', $today)
            ->join('questions', 'score_questions.question_id', '=', 'questions.id')
            ->join('sessions', 'questions.session_id', '=', 'sessions.id')
            ->where('sessions.course_id', $courseId)
            ->count();

        // داوری‌های گزارش‌ها
        $discussionCount = ScoreDiscussion::where('score_discussions.user_id', $userId)
            ->whereDate('score_discussions.created_at', $today)
            ->join('discussions', 'score_discussions.discussion_id', '=', 'discussions.id')
            ->join('sessions', 'discussions.session_id', '=', 'sessions.id')
            ->where('sessions.course_id', $courseId)
            ->count();


        return $questionCount + $discussionCount;
    }

    // ==========================================
    // متدهای اصلی داوری
    // ==========================================

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

        // ===== بررسی محدودیت روزانه =====
        $courseId = $this->getCourseIdFromItem($request->item_id, $request->type);
        if ($courseId) {
            $setting = Setting::where('course_id', $courseId)->first();
            $dailyLimit = $setting->daily_judgment_limit ?? 5; // پیش‌فرض ۵

            if ($dailyLimit > 0) {
                $todayCount = $this->getTodayJudgmentsCount($user->id, $courseId);
                if ($todayCount >= $dailyLimit) {
                    return redirect()->back()->with('error', "سقف داوری‌های مجاز امروز شما به پایان رسید. برای ادامهٔ داوری فعالیت‌های دوستان‌تان، فردا نیز سری بزنید.");
                }
            }
        }

        // بررسی اینکه کاربر نمی‌تواند سوال خودش را داوری کند
        if ($this->isOwnItem($request->item_id, $request->type, $user->id)) {
            return redirect()->back()->with('error', 'شما نمی‌توانید سوال/گزارش/تکلیف خودتان را داوری کنید.');
        }

        // بررسی اینکه کاربر قبلاً این آیتم را داوری نکرده باشد
        if ($this->hasAlreadyJudged($request->item_id, $request->type, $user->id)) {
            return redirect()->back()->with('error', 'شما قبلاً این آیتم را داوری کرده‌اید.');
        }

        // ذخیره داوری و دریافت شماره داور
        $judgeNumber = $this->saveJudgment($request, $user);

        if ($judgeNumber === false) {
            return redirect()->back()->with('error', 'خطا در ثبت داوری');
        }

        // بروزرسانی وضعیت آیتم بعد از ۳ داوری تایید شده
        $this->updateItemStatus($request->item_id, $request->type);

        // اگر کاربر رد کرده باشد، آیتم را به کاربر برگشت بده (با شماره داور)
        if ($request->action === 'reject') {
            $this->returnItemToUser($request->item_id, $request->type, $request->comment, $judgeNumber);
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
     * ذخیره داوری در جدول مناسب و برگرداندن شماره داور
     *
     * @return int|false شماره داور (۱، ۲ یا ۳) یا false در صورت خطا
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
            'status' => $status,
        ];

        if ($request->action === 'approve') {
            $data['score'] = $request->score;
        }

        $judgeNumber = 0;

        switch ($request->type) {
            case 'question':
                $data['question_id'] = $request->item_id;
                $existing = ScoreQuestion::where('question_id', $request->item_id)->count();
                $judgeNumber = $existing + 1;
                ScoreQuestion::create($data);
                break;

            case 'discussion':
                $data['discussion_id'] = $request->item_id;
                $existing = ScoreDiscussion::where('discussion_id', $request->item_id)->count();
                $judgeNumber = $existing + 1;
                ScoreDiscussion::create($data);
                break;

            case 'exercise':
                $data['exercise_answer_id'] = $request->item_id;
                $existing = ScoreExercise::where('exercise_answer_id', $request->item_id)->count();
                $judgeNumber = $existing + 1;
                ScoreExercise::create($data);
                break;

            default:
                return false;
        }

        return $judgeNumber; // شماره داور (۱، ۲ یا ۳)
    }

    /**
     * برگشت آیتم به کاربر (برای اصلاح) - فقط در صورت رد شدن
     * کامنت در فیلد comment1, comment2 یا comment3 بر اساس شماره داور ذخیره می‌شود
     */
    private function returnItemToUser($itemId, $type, $comment, $judgeNumber)
    {
        // تعیین نام فیلد کامنت بر اساس شماره داور
        $field = 'comment' . $judgeNumber; // comment1, comment2, comment3

        switch ($type) {
            case 'question':
                Question::where('id', $itemId)->update([
                    'status' => 0,
                    $field => $comment,
                ]);
                break;
            case 'discussion':
                Discussion::where('id', $itemId)->update([
                    'status' => 0,
                    $field => $comment,
                ]);
                break;
            case 'exercise':
                ExerciseAnswer::where('id', $itemId)->update([
                    'status' => 'returned',
                    $field => $comment,
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
                    ->where('status', ScoreQuestion::STATUS_APPROVED)
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
            ->where('status', 0)
            ->get();

        $returnedDiscussions = Discussion::where('user_id', $user->id)
            ->where('status', 0)
            ->get();

        $returnedExercises = ExerciseAnswer::where('user_id', $user->id)
            ->where('status', 'returned')
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
                    ->where('status', 0)
                    ->firstOrFail();
                $item->question = $request->text;
                $item->status = null;
                $item->comment = null;
                $item->save();
                break;

            case 'discussion':
                $item = Discussion::where('id', $request->item_id)
                    ->where('user_id', $user->id)
                    ->where('status', 0)
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