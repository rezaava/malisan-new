<?php

namespace App\Http\Controllers;

use App\Models\Angizesh;
use App\Models\Answer;
use App\Models\Azmon;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Session;
use App\Models\Setting;
use App\Models\User;
use App\Models\Score;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;

class ExamController extends Controller
{
    /**
     * نمایش فرم ایجاد سوال (معلم)
     */
    public function create()
    {
        return view('teacher.create-question')->with([
            'pageTitle' => 'طرح سوال',
            'pageName' => 'سوال',
            'pageDescription' => 'سوال خود را با دقت وارد کنید',
        ]);
    }

    /**
     * ذخیره سوال جدید (معلم)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|min:5',
            'options' => 'required|array|min:4|max:4',
            'options.*' => 'required|string|min:1',
            'correct_answer' => 'required|integer|min:0|max:3',
            'session_id' => 'nullable|exists:sessions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $options = $request->options;
            $correctIndex = (int) $request->correct_answer;

            if (!isset($options[$correctIndex])) {
                return redirect()->back()->with('error', 'گزینه صحیح نامعتبر است')->withInput();
            }
        $question = Question::create([
            'question' => $request->question,
            'answer1' => $options[0] ?? '',
            'answer2' => $options[1] ?? '',
            'answer3' => $options[2] ?? '',
            'answer4' => $options[3] ?? '',
            'answer' => $correctIndex + 1, // ذخیره شماره گزینه (۱، ۲، ۳ یا ۴)
            'user_id' => Auth::id(),
            'session_id' => $request->session_id ?? 1,
            'status' => null,
            'star' => 0,
            'counter' => 0,
            'is_edit' => 0,
            'score' => 0,
            'comment' => null,
        ]);

            return redirect()->route('createQuestion')
                ->with('success', 'سوال با موفقیت ثبت شد!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در ثبت سوال: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * نمایش فرم ایجاد سوال (دانشجو)
     */
    public function studentCreate($session_id)
    {
        $session = Session::with('course')->findOrFail($session_id);
        $course = $session->course;
        
        return view('student.create-question', compact('session', 'course'))->with([
            'pageTitle' => 'طرح سوال',
            'pageName' => 'سوال',
            'pageDescription' => 'سوال خود را با دقت وارد کنید',
        ]);
    }

    /**
     * ذخیره سوال جدید (دانشجو)
     */
    public function studentStore(Request $request, $session_id)
    {

        $validator = Validator::make($request->all(), [
            'question' => 'required|string|min:5',
            'options' => 'required|array|min:4|max:4',
            'options.*' => 'required|string|min:1',
            'correct_answer' => 'required|integer|min:0|max:3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $options = $request->options;
            $correctIndex = (int) $request->correct_answer + 1;
            
            
            if (!isset($options[$correctIndex])) {
                return redirect()->back()->with('error', 'گزینه صحیح نامعتبر است')->withInput();
            }
            
            $user = Auth::user();
            $session = Session::findOrFail($session_id);
            $setting = Setting::where('course_id', $session->course_id)->first();
            
            // بررسی محدودیت تعداد سوالات دانشجو
            if ($setting && $setting->max_soal) {
                $questionCount = Question::where('session_id', $session_id)
                ->where('user_id', $user->id)
                ->count();
                
                if ($questionCount >= $setting->max_soal) {
                    return redirect()->back()->with('error', 'شما به حداکثر تعداد مجاز سوال برای این جلسه رسیده‌اید.');
                }
            }

                    
            $question = Question::create([
                'question' => $request->question,
                'answer1' => $options[0] ?? '',
                'answer2' => $options[1] ?? '',
                'answer3' => $options[2] ?? '',
                'answer4' => $options[3] ?? '',
                'answer' => $correctIndex,
                'user_id' => $user->id,
                'session_id' => $session_id,
                'status' => null,
                'star' => 0,
                'counter' => 0,
                'is_edit' => 0,
                'score' => 0,
                'comment' => null,
            ]);

            return redirect()->route('view.coure.St', $session->course_id)
                ->with('success', 'سوال شما با موفقیت ثبت شد و در انتظار تایید است.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در ثبت سوال: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * نمایش یک سوال
     */
    public function show($id)
    {
        $question = Question::with('user')->findOrFail($id);

        return view('teacher.question-show', compact('question'))->with([
            'pageTitle' => 'نمایش سوال',
            'pageName' => 'سوال',
            'pageDescription' => 'مشاهده جزئیات سوال',
        ]);
    }

    /**
     * لیست سوالات
     */
    public function list(Request $request)
    {
        $course = Course::findOrFail($request->course_id);
        $sessions = $course->sessions()->pluck('id');
        
        $questions = Question::whereIn('session_id', $sessions)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($questions as $question) {
            if ($question->user) {
                $question->designer_name = $question->user->hasRole('teacher') 
                    ? 'استاد' 
                    : $question->user->name . ' ' . $question->user->family;
            } else {
                $question->designer_name = 'نامشخص';
            }

            $question->level_text = match ($question->status) {
                1 => 'عالی',
                2 => 'خوب',
                3 => 'متوسط',
                4 => 'بد',
                default => 'نامشخص',
            };
        }

        return view('teacher.question-list', compact('questions', 'course'))->with([
            'pageTitle' => 'لیست سوالات',
            'pageName' => 'سوالات',
            'pageDescription' => 'لیست سوالات درس',
        ]);
    }

    /**
     * دریافت سوالات تصادفی (API)
     */
    public function getRandomQuestions($count = 10)
    {
        $questions = Question::with('user')
            ->where('status', '!=', 4)
            ->inRandomOrder()
            ->limit($count)
            ->get();

        $formattedQuestions = [];
        foreach ($questions as $q) {
            $options = [$q->answer1, $q->answer2, $q->answer3, $q->answer4];
            shuffle($options);
            $newCorrectIndex = array_search($q->answer, $options);

            $formattedQuestions[] = [
                'id' => $q->id,
                'question' => $q->question,
                'options' => $options,
                'correct_answer' => $newCorrectIndex,
                'level' => $q->status,
                'user_name' => $q->user->name ?? 'ناشناس',
            ];
        }

        return response()->json($formattedQuestions);
    }

    /**
     * دریافت سوالات برای آزمون
     */
    public function getQuestionsForExam($sessionId, $count = 20)
    {
        $questions = Question::where('session_id', $sessionId)
            ->where('status', '!=', 4)
            ->inRandomOrder()
            ->limit($count)
            ->get();

        $result = [];
        foreach ($questions as $q) {
            $options = [$q->answer1, $q->answer2, $q->answer3, $q->answer4];
            shuffle($options);
            $correctIndex = array_search($q->answer, $options);

            $result[] = [
                'id' => $q->id,
                'question' => $q->question,
                'options' => $options,
                'correct_answer' => $correctIndex,
                'level' => $q->status,
            ];
        }

        return $result;
    }

    // ==========================================
    // خودآزمایی - دانشجو
    // ==========================================

    /**
     * شروع خودآزمایی دانشجو
     */
    public function startSelfTest($courseId)
    {
        $course = Course::findOrFail($courseId);
        $setting = Setting::where('course_id', $course->id)->first();

        if (!$setting) {
            return redirect()->back()->with('error', 'تنظیمات این دوره کامل نیست.');
        }

        $sessions = $course->sessions()->pluck('id');

        // بررسی وجود سوال
        $totalQuestions = Question::whereIn('session_id', $sessions)
            ->whereIn('status', [1, 2])
            ->count();

        if ($totalQuestions == 0) {
            return redirect()->back()->with('error', 'هیچ سوالی برای این درس وجود ندارد.');
        }

        $user = Auth::user();

        // ایجاد کوئیز جدید
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'start' => Carbon::now(),
            'azmon_id' => null,
        ]);

        // دریافت سوال اول
        $question = $this->getQuestionForSelfTest($sessions, $setting);
        $q_num = $setting->q_num ?? 10;

        if (!$question) {
            return redirect()->back()->with('error', 'هنوز سوالی برای خودآزمایی طرح نشده است.');
        }

        // **ایجاد پاسخ برای سوال اول**
        $answer = Answer::create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            // 'answer' => null, // هنوز پاسخ داده نشده
        ]);

        $num = 1;
        $showQuiz = $setting->show_quiz ?? 0;

        // شافل کردن گزینه‌ها
        $options = [
            ['label' => 'الف', 'value' => $question->answer1, 'index' => 0],
            ['label' => 'ب', 'value' => $question->answer2, 'index' => 1],
            ['label' => 'ج', 'value' => $question->answer3, 'index' => 2],
            ['label' => 'د', 'value' => $question->answer4, 'index' => 3],
        ];
        shuffle($options);
        $question->shuffled_options = $options;
        $question->correct_answer_value = $question->answer;

        return view('student.self-test', compact(
            'question',
            'answer',
            'q_num',
            'num',
            'course',
            'showQuiz'
        ))->with([
            'pageTitle' => 'خودآزمایی',
            'pageName' => 'خودآزمایی',
            'pageDescription' => 'به سوالات با دقت پاسخ دهید',
        ]);
    }

    /**
     * دریافت سوال بعدی خودآزمایی (اصلاح شده)
     */
    public function nextQuestion(Request $request)
    {
        Log::info($request->all());
        $validator = Validator::make($request->all(), [
            'answer_id' => 'required|exists:answers,id',
            'answer' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // ─── دریافت پاسخ فعلی ───
        $currentAnswer = Answer::findOrFail($request->answer_id);
        $quiz = Quiz::findOrFail($currentAnswer->quiz_id);
        $course = Course::findOrFail($quiz->course_id);
        $setting = Setting::where('course_id', $course->id)->first();

        if (!$setting) {
            return redirect()->back()->with('error', 'تنظیمات این دوره کامل نیست.');
        }

        $q_num = $setting->q_num ?? 10;
        $showQuiz = $setting->show_quiz ?? 0;
        $feedback = null;

        // ─── ذخیره پاسخ کاربر برای سوال فعلی ───
        $previousQuestion = null;
        $isCorrect = null;

        if ($request->has('answer')) {
            // تبدیل به عدد صحیح و +1 برای ذخیره 1 تا 4
            $answerValue = (int) $request->answer;
            
            // اگر بین 0 تا 3 بود، +1 کن تا بشود 1 تا 4
            if ($answerValue >= 0 && $answerValue <= 3) {
                $currentAnswer->answer = $answerValue + 1;
            } else {
                $currentAnswer->answer = $answerValue;
            }
            $currentAnswer->save();

            // بررسی صحیح بودن پاسخ برای نمایش feedback
            if ($showQuiz == 1) {
                $previousQuestion = Question::find($currentAnswer->question_id);
                $correctIndex = $this->getCorrectOptionIndex($previousQuestion);
                $userAnswer = (int) $request->answer; // 0 تا 3
                
                if ($userAnswer == $correctIndex) {
                    $feedback = '✅ پاسخ شما صحیح بود!';
                    $isCorrect = true;
                } else {
                    $feedback = '❌ پاسخ شما صحیح نبود.';
                    $isCorrect = false;
                }
            }
        }

        // ─── دریافت سوالات قبلی ───
        $oldQuestions = Answer::where('quiz_id', $currentAnswer->quiz_id)
            ->whereNotNull('answer') // فقط سوالاتی که پاسخ داده شده
            ->pluck('question_id');

        $sessions = $course->sessions()->pluck('id');

        // ─── بررسی پایان آزمون ───
        if ($oldQuestions->count() >= $q_num) {
            return $this->finishSelfTest($quiz, $course, $setting);
        }

        // ─── دریافت سوال بعدی ───
        $question = $this->getNextQuestionForSelfTest($sessions, $setting, $oldQuestions);

        if (!$question) {
            return $this->finishSelfTest($quiz, $course, $setting);
        }

        // **ایجاد رکورد جدید برای سوال بعدی**
        $newAnswer = Answer::create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            // 'answer' => null, // هنوز پاسخ داده نشده
        ]);

        $num = $oldQuestions->count() + 1;
        $designer = User::find($question->user_id);
        $question['designer'] = $designer;

        // شافل کردن گزینه‌ها
        $options = [
            ['label' => 'الف', 'value' => $question->answer1, 'index' => 0],
            ['label' => 'ب', 'value' => $question->answer2, 'index' => 1],
            ['label' => 'ج', 'value' => $question->answer3, 'index' => 2],
            ['label' => 'د', 'value' => $question->answer4, 'index' => 3],
        ];
        shuffle($options);
        $question->shuffled_options = $options;

        // ارسال رکورد جدید به view
        return view('student.self-test', compact(
            'question',
            'newAnswer', // تغییر نام به newAnswer برای وضوح
            'q_num',
            'num',
            'course',
            'showQuiz'
        ))->with([
            'pageTitle' => 'خودآزمایی',
            'pageName' => 'خودآزمایی',
            'pageDescription' => 'به سوالات با دقت پاسخ دهید',
            'feedback' => $feedback,
            'previousQuestion' => $previousQuestion,
            'isCorrect' => $isCorrect,
        ]);
    }

    /**
     * پایان خودآزمایی و نمایش نتیجه (غیر AJAX)
     */
    private function finishSelfTest($quiz, $course, $setting)
    {
        $oldAnswers = Answer::where('quiz_id', $quiz->id)->get();
        $totalQuestions = $oldAnswers->count();
        $correctAnswers = 0;

        foreach ($oldAnswers as $oldAnswer) {
            $q = Question::find($oldAnswer->question_id);
            if ($q) {
                // مقایسه با مقدار ذخیره شده (1 تا 4)
                if ($q->answer == $oldAnswer->answer) {
                    $correctAnswers++;
                }
            }
        }

        $score = $totalQuestions > 0 ? ($correctAnswers * 20) / $totalQuestions : 0;
        $quiz->score = $score;
        $quiz->save();

        $motivational = $this->getMotivationalMessage($score);

        if ($setting->natije == '1') {
            return redirect()->route('student.selfTest.results', ['quiz_id' => $quiz->id])
                ->with('success', "از {$totalQuestions} سوال، به {$correctAnswers} سوال پاسخ صحیح دادید.");
        }

        return redirect()->route('view.coure.St', $course->id)
            ->with('success', "از {$totalQuestions} سوال، به {$correctAnswers} سوال پاسخ صحیح دادید.");
    }
    /**
     * نمایش نتایج خودآزمایی
     */
    public function selfTestResults(Request $request)
    {
        $quizId = $request->quiz_id;
        $quiz = Quiz::findOrFail($quizId);

        $answers = Answer::where('quiz_id', $quizId)->get();
        $questions = Question::whereIn('id', $answers->pluck('question_id'))->get();

        $totalQuestions = $questions->count();
        $correctAnswers = 0;

        foreach ($questions as $question) {
            $answer = Answer::where('quiz_id', $quizId)
                ->where('question_id', $question->id)
                ->first();
            $question['user_answer'] = $answer;

            if ($answer) {
                // مقایسه مستقیم مقادیر ذخیره شده (هر دو 1 تا 4 هستند)
                if ($question->answer == $answer->answer) {
                    $correctAnswers++;
                }
            }
        }

        // امتیاز از ۲۰
        $score = $totalQuestions > 0 ? ($correctAnswers * 20) / $totalQuestions : 0;
        $motivational = $this->getMotivationalMessage($score);

        $course = Course::find($quiz->course_id);
        $user = User::find($quiz->user_id);
            
        return view('student.self-test-result', compact(
            'questions',
            'course',
            'user',
            'quiz',
            'score',
            'correctAnswers',
            'totalQuestions',
            'motivational'
        ))->with([
            'pageTitle' => 'نتیجه خودآزمایی',
            'pageName' => 'نتیجه',
            'pageDescription' => 'نتیجه خودآزمایی شما',
        ]);
    }

    /**
     * تاریخچه خودآزمایی‌های دانشجو
     */
    public function selfTestHistory()
    {
        $quizzes = Quiz::where('user_id', Auth::id())
            ->whereNull('azmon_id')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($quizzes as $quiz) {
            $questions = Answer::where('quiz_id', $quiz->id)->count();
            $quiz['count'] = $questions;

            $correct = 0;
            $answers = Answer::where('quiz_id', $quiz->id)->get();
            foreach ($answers as $answer) {
                $q = Question::find($answer->question_id);
                if ($q && $q->answer == $answer->answer) {
                    $correct++;
                }
            }
            $quiz['correct'] = $correct;
        }

        return view('student.self-test-history', compact('quizzes'))->with([
            'pageTitle' => 'تاریخچه خودآزمایی',
            'pageName' => 'تاریخچه',
            'pageDescription' => 'مشاهده تاریخچه خودآزمایی‌ها',
        ]);
    }

    // ==========================================
    // متدهای کمکی (Helper Methods)
    // ==========================================

    /**
     * دریافت سوال برای خودآزمایی از همان درس (رندم)
     */
    private function getQuestionForSelfTest($sessions, $setting)
    {
        $statusFilter = match ($setting->sath_khod ?? 2) {
            1 => [1],
            2 => [1, 2],
            3 => [2],
            default => [1, 2],
        };

        return Question::whereIn('session_id', $sessions)
            ->whereIn('status', $statusFilter)
            ->inRandomOrder()
            ->first();
    }

    /**
     * دریافت سوال بعدی خودآزمایی از همان درس (به جز سوالات قبلی)
     */
    private function getNextQuestionForSelfTest($sessions, $setting, $oldQuestions)
    {
        $statusFilter = match ($setting->sath_khod ?? 2) {
            1 => [1],
            2 => [1, 2],
            3 => [2],
            default => [1, 2],
        };

        return Question::whereIn('session_id', $sessions)
            ->whereNotIn('id', $oldQuestions)
            ->inRandomOrder()
            ->first();
            // ->whereIn('status', $statusFilter)
    }

    /**
     * دریافت پیام انگیزشی بر اساس امتیاز
     */
    private function getMotivationalMessage($score)
    {
        $level = match (true) {
            $score == 20 => 1,
            $score >= 18 => 2,
            $score >= 15 => 3,
            $score >= 12 => 4,
            $score >= 10 => 5,
            default => 6,
        };

        return Angizesh::where('level', $level)->inRandomOrder()->first();
    }

    /**
     * دریافت وضعیت خودآزمایی برای دانشجو
     */
    public function getSelfTestStatus($courseId)
    {
        $course = Course::findOrFail($courseId);
        $setting = Setting::where('course_id', $course->id)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'تنظیمات این دوره کامل نیست.'
            ]);
        }

        $sessions = $course->sessions()->pluck('id');
        $questionCount = $this->getQuestionCountForSelfTest($sessions, $setting);

        return response()->json([
            'success' => true,
            'total_questions' => min($questionCount, $setting->q_num ?? 10),
            'show_quiz' => $setting->show_quiz ?? 0,
            'show_results' => $setting->natije ?? 0,
        ]);
    }
    private function getCorrectOptionIndex($question)
    {
        $options = [
            $question->answer1,
            $question->answer2,
            $question->answer3,
            $question->answer4,
        ];
        
        return array_search($question->answer, $options); // 0,1,2,3
    }
}