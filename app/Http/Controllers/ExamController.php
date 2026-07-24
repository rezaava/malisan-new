<?php

namespace App\Http\Controllers;

use App\Models\Angizesh;
use App\Models\Answer;
use App\Models\Azmon;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\ScoreQuestion;
use App\Models\Session;
use App\Models\Setting;
use App\Models\User;
use App\Models\Score;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;

class ExamController extends Controller
{

    /**
     * نمایش فرم ایجاد سوال (دانشجو)
     */
    public function studentCreate($session_id)
    {
        $session = Session::with('course')->findOrFail($session_id);
        $course = $session->course;

        $setting = Setting::where('course_id', $course->id)->first();
        
        if ($setting && $setting->soal_last == 1) {
            $lastSession = Session::where('course_id', $course->id)
                ->orderBy('id', 'desc') // یا orderBy('session_number', 'desc') اگر چنین فیلدی دارید
                ->first();
                
            if (!$lastSession || $session->id != $lastSession->id) {
                return redirect()->back()->with('error', 'شما فقط می‌توانید برای آخرین جلسه این درس سوال طراحی کنید.');
            }
        }

        $settingDescription = $setting->tarahi_soal_desc ?? 'یک سؤال خلاقانه طراحی کنید که به یادگیری دوستانتان کمک کند و به نام خودتان منتشر شود. قبل از ارسال، حتماً سؤالاتی که دیگران طرح کرده اند را مرور کنید تا از تکراری نبودن سوال خود مطمئن شوید.';
        $settingScore = $setting->tarahi_soal_nomre ?? 10;

        return view('student.create-question', compact('session', 'course', 'settingDescription', 'settingScore'));
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

        $session = Session::findOrFail($request->session_id);
        $setting = Setting::where('course_id', $session->course_id)->first();
        
        // بررسی مجدد شرط soal_last در زمان ذخیره‌سازی
        if ($setting && $setting->soal_last == 1) {
            $lastSession = Session::where('course_id', $session->course_id)
                ->orderBy('id', 'desc')
                ->first();
                
            if (!$lastSession || $session->id != $lastSession->id) {
                return redirect()->back()->with('error', 'شما فقط می‌توانید برای آخرین جلسه این درس سوال طراحی کنید.');
            }
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

            // بررسی محدودیت تعداد سوالات (فقط برای دانشجویان)
            if ($setting && $setting->max_soal) {
                // اگر کاربر معلم نیست، محدودیت تعداد سوال را اعمال کن
                if (!$user->hasRole('teacher')) {
                    $questionCount = Question::where('session_id', $session_id)
                        ->where('user_id', $user->id)
                        ->count();

                    if ($questionCount >= $setting->max_soal) {
                        return redirect()->back()->with('error', 'شما به حداکثر تعداد مجاز سوال برای این جلسه رسیده‌اید.');
                    }
                }
            }

            $isTeacher = $user->hasRole('teacher||admin');
            $status = $isTeacher ? 5 : null; 

            $question = Question::create([
                'question' => $request->question,
                'answer1' => $options[0] ?? '',
                'answer2' => $options[1] ?? '',
                'answer3' => $options[2] ?? '',
                'answer4' => $options[3] ?? '',
                'answer' => $correctIndex,
                'user_id' => $user->id,
                'session_id' => $session_id,
                'status' => $status,
                'star' => 0,
                'counter' => 0,
                'is_edit' => 0,
                'score' => 0,
                'comment' => null,
            ]);

            // پیام مناسب بر اساس نقش کاربر
            $message = $isTeacher 
                ? 'سوال شما با موفقیت ثبت و تایید شد.' 
                : 'سوال شما با موفقیت ثبت شد و در انتظار تایید است.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در ثبت سوال: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * نمایش یک سوال
     */
    public function show($id)
    {
        $question = Question::with(['user', 'session.course'])
            ->findOrFail($id);

        // دریافت نظرات (داوری‌ها)
        $scores = ScoreQuestion::where('question_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // محاسبه میانگین نمرات
        $approvedScores = ScoreQuestion::where('question_id', $id)
            ->where('status', 'approved')
            ->pluck('score')
            ->toArray();

        $averageScore = count($approvedScores) > 0
            ? round(array_sum($approvedScores) / count($approvedScores), 2)
            : null;

        // وضعیت‌های مجاز برای تغییر
        $statusOptions = [
            null => 'در انتظار داوری',
            0 => 'برگشت خورده',
            1 => 'عالی',
            2 => 'خوب',
            3 => 'متوسط',
            4 => 'بد',
        ];

        // اطلاعات طراح
        $designer = $question->user;
        $designerName = $designer ? $designer->name . ' ' . $designer->family : 'نامشخص';

        return view('teacher.question-show', compact(
            'question',
            'scores',
            'averageScore',
            'statusOptions',
            'designerName'
        ))->with([
                    'pageTitle' => 'نمایش سوال',
                    'pageName' => 'سوال',
                    'pageDescription' => 'مشاهده جزئیات سوال',
                ]);
    }

    /**
     * به‌روزرسانی وضعیت سوال
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:0,1,2,3,4',
        ]);

        $question = Question::findOrFail($id);
        $question->status = $request->status;
        $question->save();

        $statusLabels = [
            null => 'در انتظار داوری',
            0 => 'برگشت خورده',
            1 => 'عالی',
            2 => 'خوب',
            3 => 'متوسط',
            4 => 'بد',
        ];

        return response()->json([
            'success' => true,
            'message' => 'وضعیت سوال با موفقیت تغییر یافت.',
            'status' => $request->status,
            'status_label' => $statusLabels[$request->status] ?? 'نامشخص'
        ]);
    }

    /**
     * حذف سوال
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);

        // حذف داوری‌های مرتبط
        ScoreQuestion::where('question_id', $id)->delete();

        $question->delete();

        return redirect()->back()->with('success', 'سوال با موفقیت حذف شد.');
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
                5 => 'سوال استاد', // اضافه شد
                null => 'در انتظار تایید',
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

        $totalQuestions = Question::whereIn('session_id', $sessions)
            ->whereIn('status', [1, 2])
            ->count();

        if ($totalQuestions == 0) {
            return redirect()->back()->with('error', 'هیچ سوالی برای این درس وجود ندارد.');
        }

        $user = Auth::user();

        $quiz = Quiz::create([
            'course_id' => $course->id,
            'user_id'   => $user->id,
            'start'     => Carbon::now(),
            'azmon_id'  => null,
        ]);

        $question = $this->getQuestionForSelfTest($sessions, $setting);
        $q_num = $setting->q_num ?? 10;

        if (!$question) {
            return redirect()->back()->with('error', 'هنوز سوالی برای خودآزمایی طرح نشده است.');
        }

        // شافل کردن گزینه‌ها
        $options = [
            ['label' => 'الف', 'value' => $question->answer1, 'index' => 0],
            ['label' => 'ب', 'value' => $question->answer2, 'index' => 1],
            ['label' => 'ج', 'value' => $question->answer3, 'index' => 2],
            ['label' => 'د', 'value' => $question->answer4, 'index' => 3],
        ];
        shuffle($options);

        $correctIndex = null;
        foreach ($options as $key => $opt) {
            if ($opt['index'] == ($question->answer - 1)) {
                $correctIndex = $key;
                break;
            }
        }

        \Session::put('shuffled_question_' . $question->id, [
            'options'       => $options,
            'correct_index' => $correctIndex,
        ]);

        $shuffledOptions = $options;

        $answer = Answer::create([
            'quiz_id'       => $quiz->id,
            'question_id'   => $question->id,
        ]);

        $num = 1;
        $showQuiz = $setting->show_quiz ?? 0;

        // ارسال یک متغیر برای مشخص کردن اینکه در صفحه اول هستیم (برای جاوااسکریپت)
        $isFirstQuestion = true;

        return view('student.self-test', compact(
            'question',
            'answer',
            'q_num',
            'num',
            'course',
            'showQuiz',
            'shuffledOptions',
            'isFirstQuestion'
        ))->with([
            'pageTitle'        => 'خودآزمایی',
            'pageName'         => 'خودآزمایی',
            'pageDescription'  => 'به سوالات با دقت پاسخ دهید',
        ]);
    }

    /**
     * دریافت سوال بعدی خودآزمایی (اصلاح شده)
     */
    public function nextQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'answer_id' => 'required|exists:answers,id',
            'answer'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        $currentAnswer = Answer::findOrFail($request->answer_id);
        $quiz = Quiz::findOrFail($currentAnswer->quiz_id);
        $course = Course::findOrFail($quiz->course_id);
        $setting = Setting::where('course_id', $course->id)->first();

        if (!$setting) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'تنظیمات دوره کامل نیست.']);
            }
            return redirect()->back()->with('error', 'تنظیمات این دوره کامل نیست.');
        }

        $q_num = $setting->q_num ?? 10;
        $showQuiz = $setting->show_quiz ?? 0;

        // ─── ذخیره پاسخ کاربر ───
        $previousQuestion = null;
        $isCorrect = false;
        $userAnswerIndex = null;
        $shuffledOptionsPrev = null;
        $correctIndexPrev = null;

        if ($request->has('answer')) {
            $userAnswerIndex = (int) $request->answer; // ۰ تا ۳
            // ذخیره با +۱ برای هماهنگی با دیتابیس
            $currentAnswer->answer = $userAnswerIndex + 1;
            $currentAnswer->save();

            if ($showQuiz == 1) {
                $previousQuestion = Question::find($currentAnswer->question_id);
                $shuffledData = \Session::get('shuffled_question_' . $previousQuestion->id);
                if ($shuffledData) {
                    $shuffledOptionsPrev = $shuffledData['options'];
                    $correctIndexPrev    = $shuffledData['correct_index']; // ۰ تا ۳
                    // برای مقایسه باید از مقدار ذخیره‌شده (۱ تا ۴) استفاده کنیم
                    // پس $currentAnswer->answer را با ($correctIndexPrev + 1) مقایسه می‌کنیم
                    $isCorrect = ($currentAnswer->answer == ($correctIndexPrev + 1));
                }
            }
        }

        // ─── دریافت سوالات قبلی ───
        $oldQuestions = Answer::where('quiz_id', $currentAnswer->quiz_id)
            ->whereNotNull('answer')
            ->pluck('question_id');

        $sessions = $course->sessions()->pluck('id');

        // ─── بررسی پایان آزمون ───
        if ($oldQuestions->count() >= $q_num) {
            // پایان آزمون
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'finished' => true,
                    'quiz_id' => $quiz->id,
                ]);
            }
            return $this->finishSelfTest($quiz, $course, $setting);
        }

        // ─── دریافت سوال بعدی ───
        $nextQuestion = $this->getNextQuestionForSelfTest($sessions, $setting, $oldQuestions);

        if (!$nextQuestion) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'finished' => true,
                    'quiz_id' => $quiz->id,
                ]);
            }
            return $this->finishSelfTest($quiz, $course, $setting);
        }

        // --- شافل کردن گزینه‌های سوال جدید ---
        $options = [
            ['label' => 'الف', 'value' => $nextQuestion->answer1, 'index' => 0],
            ['label' => 'ب', 'value' => $nextQuestion->answer2, 'index' => 1],
            ['label' => 'ج', 'value' => $nextQuestion->answer3, 'index' => 2],
            ['label' => 'د', 'value' => $nextQuestion->answer4, 'index' => 3],
        ];
        shuffle($options);

        $correctIndex = null;
        foreach ($options as $key => $opt) {
            if ($opt['index'] == ($nextQuestion->answer - 1)) {
                $correctIndex = $key;
                break;
            }
        }

        \Session::put('shuffled_question_' . $nextQuestion->id, [
            'options'       => $options,
            'correct_index' => $correctIndex,
        ]);

        $shuffledOptions = $options;

        // --- ایجاد پاسخ جدید برای سوال بعدی ---
        $newAnswer = Answer::create([
            'quiz_id'     => $quiz->id,
            'question_id' => $nextQuestion->id,
        ]);

        $num = $oldQuestions->count() + 1;

        // ─── پاسخ AJAX ───
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'finished' => false,
                'show_quiz' => $showQuiz,
                'previous' => [
                    'question' => $previousQuestion ? $previousQuestion->question : null,
                    'options'  => $shuffledOptionsPrev,
                    'correct_index' => $correctIndexPrev, // ۰ تا ۳
                    'user_answer_index' => $userAnswerIndex, // ۰ تا ۳ (همان مقدار اولیه)
                    'is_correct' => $isCorrect, // قبلاً با +۱ محاسبه شده
                ],
                'next' => [
                    'question_id'   => $nextQuestion->id,
                    'question_text' => $nextQuestion->question,
                    'options'       => $shuffledOptions,
                    'answer_id'     => $newAnswer->id,
                    'num'           => $num,
                    'total'         => $q_num,
                ]
            ]);
        }

        // ─── پاسخ معمولی (رفرش صفحه) ───
        return view('student.self-test', compact(
            'nextQuestion',
            'newAnswer',
            'q_num',
            'num',
            'course',
            'showQuiz',
            'previousQuestion',
            'isCorrect',
            'userAnswerIndex',
            'shuffledOptionsPrev',
            'correctIndexPrev',
            'shuffledOptions'
        ))->with([
            'pageTitle'        => 'خودآزمایی',
            'pageName'         => 'خودآزمایی',
            'pageDescription'  => 'به سوالات با دقت پاسخ دهید',
        ]);
    }

    /**
     * پایان خودآزمایی و نمایش نتیجه (غیر AJAX)
     */
    private function finishSelfTest($quiz, $course, $setting)
    {
        $answers = Answer::where('quiz_id', $quiz->id)->get();
        $totalQuestions = $answers->count();
        $correctAnswers = 0;

        foreach ($answers as $ans) {
            $shuffledData = \Session::get('shuffled_question_' . $ans->question_id);
            if ($shuffledData) {
                $correctIndex = $shuffledData['correct_index']; // ۰ تا ۳
                // پاسخ ذخیره‌شده ۱ تا ۴ است، پس باید با ($correctIndex + 1) مقایسه شود
                if ($ans->answer == ($correctIndex + 1)) {
                    $correctAnswers++;
                }
            } else {
                $question = Question::find($ans->question_id);
                if ($question && $ans->answer == $question->answer) {
                    $correctAnswers++;
                }
            }
        }
        $score = $totalQuestions > 0 ? ($correctAnswers * 20) / $totalQuestions : 0;
        $quiz->score = $score;
        $quiz->save();

        if ($setting->natije == '1') {
            return redirect()->route('student.selfTest.results', ['quiz_id' => $quiz->id])
                ->with('success', "از {$totalQuestions} سوال، به {$correctAnswers} سوال پاسخ صحیح دادید.");
        }

        return redirect()->route('view.coure.St', $course->id);
    }

    /**
     * نمایش نتایج خودآزمایی
     */
    public function selfTestResults($quizId)
    {
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
    
            // دریافت اطلاعات شافل شده از session
            $shuffledData = \Session::get('shuffled_question_' . $question->id);
            if ($shuffledData) {
                $question['shuffled_options'] = $shuffledData['options']; // آرایه‌ی شافل شده
                $question['shuffled_correct_index'] = $shuffledData['correct_index']; // ۰ تا ۳
            } else {
                // اگر در session نبود، از ترتیب اصلی استفاده کن
                $question['shuffled_options'] = [
                    ['value' => $question->answer1, 'index' => 0],
                    ['value' => $question->answer2, 'index' => 1],
                    ['value' => $question->answer3, 'index' => 2],
                    ['value' => $question->answer4, 'index' => 3],
                ];
                $question['shuffled_correct_index'] = $question->answer - 1; // تبدیل ۱ تا ۴ به ۰ تا ۳
            }
    
            // اندیس پاسخ کاربر (۰ تا ۳)
            if ($answer) {
                $question['user_answer_index'] = $answer->answer - 1; // چون answer در دیتابیس ۱ تا ۴ است
                // بررسی صحت پاسخ با استفاده از اندیس شافل شده
                if ($answer->answer == ($question['shuffled_correct_index'] + 1)) {
                    $correctAnswers++;
                }
            } else {
                $question['user_answer_index'] = null;
            }
        }
    
        $score = $totalQuestions > 0 ? ($correctAnswers * 20) / $totalQuestions : 0;
        $motivational = $this->getMotivationalMessage($score);
    
        $course = Course::find($quiz->course_id);
        if ($course->settings->natije != 1) {
            return redirect()->route('view.coure.St', $course->id);
        }
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
        ));
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
    /**
     * دریافت داده‌های سوال برای ویرایش (AJAX)
     */
    public function getQuestionData($id)
    {
        $question = Question::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $question
        ]);
    }
    /**
     * به‌روزرسانی وضعیت سوال (استاد)
     */
    public function updateStatusTe(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|min:5',
            'answer1' => 'required|string',
            'answer2' => 'required|string',
            'answer3' => 'required|string',
            'answer4' => 'required|string',
            'correct_answer' => 'required|integer|min:1|max:4',
            'status' => 'nullable|integer|min:0|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $question = Question::findOrFail($id);
        $question->question = $request->question;
        $question->answer1 = $request->answer1;
        $question->answer2 = $request->answer2;
        $question->answer3 = $request->answer3;
        $question->answer4 = $request->answer4;
        $question->answer = $request->correct_answer;
        $question->status = $request->status;
        $question->save();

        return response()->json([
            'success' => true,
            'message' => 'سوال با موفقیت بروزرسانی شد.',
            'data' => $question
        ]);
    }
    /**
     * دریافت لیست سوالات یک جلسه برای نمایش در مودال (AJAX)
     */
    public function getSessionQuestions($sessionId)
    {
        try {
            $questions = Question::where('session_id', $sessionId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $result = [];
            foreach ($questions as $q) {
                // استفاده از ورتا برای تبدیل تاریخ
                $date = '';
                if ($q->created_at) {
                    $verta = Verta::instance($q->created_at);
                    $date = $verta->format('Y/m/d');
                }
                
                $result[] = [
                    'id' => $q->id,
                    'question' => $q->question,
                    'answer1' => $q->answer1,
                    'answer2' => $q->answer2,
                    'answer3' => $q->answer3,
                    'answer4' => $q->answer4,
                    'answer' => $q->answer,
                    'status' => $q->status,
                    'user_name' => $q->user ? $q->user->name . ' ' . $q->user->family : 'نامشخص',
                    'date' => $date,
                ];
            }
            
            return response()->json([
                'success' => true,
                'questions' => $result,
                'count' => count($result)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در دریافت سوالات: ' . $e->getMessage()
            ], 500);
        }
    }

}