<?php
namespace App\Http\Controllers;
use App\Models\Angizesh;
use App\Models\Azmon;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class AzmonController extends Controller
{
    public function toggleZarib($id)
    {
        $azmon = Azmon::findOrFail($id);
        if (is_null($azmon->zarib) || $azmon->zarib == 1) {
            $azmon->zarib = 0;
        } else {
            $azmon->zarib = 1;
        }
        $azmon->save();
        return redirect()->back()->with('success', 'وضعیت ضریب آزمون با موفقیت تغییر کرد.');
    }
    public function azmonStats($id)
    {
        $azmon = Azmon::findOrFail($id);
        $quizzes = Quiz::where('azmon_id', $azmon->id)->get();
        if ($quizzes->isEmpty()) {
            return response()->json([
                'count' => 0,
                'min' => null,
                'max' => null,
                'average' => null,
            ]);
        }
        $zarib = is_null($azmon->zarib) ? 1 : (float) $azmon->zarib;
        $scores = [];
        foreach ($quizzes as $quiz) {
            $answers = Answer::where('quiz_id', $quiz->id)->get();
            $total_answers = $answers->count();
            $correct = 0;
            foreach ($answers as $item) {
                $question = Question::find($item->question_id);
                if ($question && $question->answer == $item->answer) {
                    $correct++;
                }
            }
            if ($total_answers > 0) {
                $scores[] = round(($correct / $total_answers) * 20 * $zarib, 2);
            }
        }
        if (empty($scores)) {
            return response()->json([
                'count' => 0,
                'min' => null,
                'max' => null,
                'average' => null,
            ]);
        }
        return response()->json([
            'title' => $azmon->title,
            'zarib' => $zarib,
            'count' => count($scores),
            'min' => min($scores),
            'max' => max($scores),
            'average' => round(array_sum($scores) / count($scores), 2),
        ]);
    }
    public function list($id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();
        $azmons = Azmon::where('course_id', $course->id)->get();
        foreach ($azmons as $azmon) {
            $hasParticipant = Quiz::where('azmon_id', $azmon->id)->exists();
            $azmon['expire'] = $hasParticipant ? '1' : '0';
            $azmon['participant_count'] = Quiz::where('azmon_id', $azmon->id)->count();
        }
        return view('teacher.azmon-list', compact('course', 'azmons'));
    }
    public function create(Request $request)
    {
        $course = Course::findOrFail($request->id);
        $sessions = $course->sessions()->get();
        $code = Str::random(5);
        $uniq = Azmon::where('code', $code)->first();
        while ($uniq) {
            $code = Str::random(5);
            $uniq = Azmon::where('code', $code)->first();
        }
        return view('teacher.azmon-create', compact('course', 'code', 'sessions'));
    }
    public function createPost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sessions' => 'required|array|min:1',
            'num' => 'required|integer|min:1|max:100',
            'time' => 'required|integer|min:1|max:300',
            'time_limit_khod' => 'nullable|boolean',
            'time_type' => 'required_if:time_limit_khod,1|nullable|in:per_question,total',
            'time_per_question' => 'required_if:time_type,per_question|nullable|integer|min:1|max:300',
            'total_time_limit' => 'required_if:time_type,total|nullable|integer|min:1|max:3000',
            'start_date' => 'required',
            'start_h' => 'required|integer|min:0|max:23',
            'start_m' => 'required|integer|min:0|max:59',
            'end_date' => 'required',
            'end_h' => 'required|integer|min:0|max:23',
            'end_m' => 'required|integer|min:0|max:59',
            'type' => 'required|in:periodic,mid-term,final',
            'access_type' => 'required|in:code,free',
        ]);
        $startDateTime = $request->start_date . ' ' . $request->start_h . ':' . $request->start_m . ':00';
        $endDateTime = $request->end_date . ' ' . $request->end_h . ':' . $request->end_m . ':00';
        $startCarbon = $this->convertPersianToCarbon($startDateTime);
        $endCarbon = $this->convertPersianToCarbon($endDateTime);
        $azmon = new Azmon();
        $azmon->course_id = $request->id;
        $azmon->title = $request->title;
        $azmon->description = $request->description;
        $azmon->sath = $request->sath ?? 3;
        if ($request->access_type === 'code') {
            $azmon->code = $request->code;
        } else {
            $azmon->code = null;
        }
        $azmon->start = $startCarbon;
        $azmon->end = $endCarbon;
        $azmon->time = $request->time;
        $azmon->type = $request->type;
        $azmon->sessions = implode(',', $request->sessions);
        $azmon->num = $request->num;
        $azmon->zarib = 1;
        $azmon->time_limit_khod = $request->has('time_limit_khod') ? 1 : 0;
        if ($azmon->time_limit_khod) {
            $azmon->time_type = $request->time_type;
            if ($request->time_type === 'per_question') {
                $azmon->time_per_question = $request->time_per_question ?? 0;
                $azmon->total_time_limit = 0;
            } elseif ($request->time_type === 'total') {
                $azmon->time_per_question = 0;
                $azmon->total_time_limit = $request->total_time_limit ?? 0;
            }
        } else {
            $azmon->time_type = null;
            $azmon->time_per_question = 0;
            $azmon->total_time_limit = 0;
        }
        $azmon->show_nomre = $request->has('show_nomre') ? 1 : 0;
        $azmon->show_ans = $request->has('show_ans') ? 1 : 0;
        $azmon->show_state = $request->has('show_state') ? 1 : 0;
        $azmon->show_remain = $request->has('show_remain') ? 1 : 0;
        $azmon->changeable = $request->has('changeable') ? 1 : 0;
        $azmon->save();
        return redirect()->route('azmon.list', ['id' => $request->id])
            ->with('success', 'آزمون با موفقیت ایجاد شد.');
    }
    public function edit(Request $request)
    {
        $azmon = Azmon::findOrFail($request->id);
        $course = Course::findOrFail($azmon->course_id);
        $sessions = $course->sessions()->get();
        $selectedSessions = $azmon->sessions ? explode(",", $azmon->sessions) : [];
        return view('teacher.azmon-create', compact('course', 'azmon', 'sessions', 'selectedSessions'));
    }
    public function editPost(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sessions' => 'required|array|min:1',
            'num' => 'required|integer|min:1|max:100',
            'time' => 'required|integer|min:1|max:300',
            'time_limit_khod' => 'nullable|boolean',
            'time_type' => 'required_if:time_limit_khod,1|nullable|in:per_question,total',
            'time_per_question' => 'required_if:time_type,per_question|nullable|integer|min:1|max:300',
            'total_time_limit' => 'required_if:time_type,total|nullable|integer|min:1|max:3000',
            'start_date' => 'required',
            'start_h' => 'required|integer|min:0|max:23',
            'start_m' => 'required|integer|min:0|max:59',
            'end_date' => 'required',
            'end_h' => 'required|integer|min:0|max:23',
            'end_m' => 'required|integer|min:0|max:59',
            'type' => 'required|in:periodic,mid-term,final',
            'access_type' => 'required|in:code,free',
        ]);
        $startDateTime = $request->start_date . ' ' . $request->start_h . ':' . $request->start_m . ':00';
        $endDateTime = $request->end_date . ' ' . $request->end_h . ':' . $request->end_m . ':00';
        $startCarbon = $this->convertPersianToCarbon($startDateTime);
        $endCarbon = $this->convertPersianToCarbon($endDateTime);
        $azmon = Azmon::findOrFail($id);
        $azmon->title = $request->title;
        $azmon->description = $request->description;
        $azmon->sath = $request->sath ?? 3;
        if ($request->access_type === 'code') {
            $azmon->code = $request->code;
        } else {
            $azmon->code = null;
        }
        $azmon->start = $startCarbon;
        $azmon->end = $endCarbon;
        $azmon->time = $request->time;
        $azmon->type = $request->type;
        $azmon->sessions = implode(',', $request->sessions);
        $azmon->num = $request->num;
        $azmon->time_limit_khod = $request->has('time_limit_khod') ? 1 : 0;
        if ($azmon->time_limit_khod) {
            $azmon->time_type = $request->time_type;
            if ($request->time_type === 'per_question') {
                $azmon->time_per_question = $request->time_per_question ?? 0;
                $azmon->total_time_limit = 0;
            } elseif ($request->time_type === 'total') {
                $azmon->time_per_question = 0;
                $azmon->total_time_limit = $request->total_time_limit ?? 0;
            }
        } else {
            $azmon->time_type = null;
            $azmon->time_per_question = 0;
            $azmon->total_time_limit = 0;
        }
        $azmon->show_nomre = $request->has('show_nomre') ? 1 : 0;
        $azmon->show_ans = $request->has('show_ans') ? 1 : 0;
        $azmon->show_state = $request->has('show_state') ? 1 : 0;
        $azmon->show_remain = $request->has('show_remain') ? 1 : 0;
        $azmon->changeable = $request->has('changeable') ? 1 : 0;
        $azmon->save();
        return redirect()->route('azmon.list', ['id' => $azmon->course_id])
            ->with('success', 'آزمون با موفقیت بروزرسانی شد.');
    }
    public function delete($id)
    {
        $azmon = Azmon::findOrFail($id);
        $courseId = $azmon->course_id;
        $azmon->delete();
        return redirect()->route('azmon.list', ['id' => $courseId])
            ->with('success', 'آزمون با موفقیت حذف شد.');
    }
    public function enterAzmon(Request $request)
    {
        $azmoon = Azmon::where('code', $request->code)
            ->where('course_id', $request->course_id)
            ->first();
        if (!$azmoon) {
            return back()->with('error', 'کد صحیح نیست یا متعلق به این درس نمی‌باشد!');
        }
        if (Carbon::now() < $azmoon->start) {
            return back()->with('error', 'آزمون هنوز شروع نشده است!');
        }
        if (Carbon::now() > $azmoon->end) {
            return back()->with('error', 'آزمون تمام شده است!');
        }
        $user = Auth::user();
        $quiz = Quiz::where('user_id', $user->id)
            ->where('azmon_id', $azmoon->id)
            ->first();
        if ($quiz) {
            return back()->with('error', 'شما قبلاً این آزمون را داده‌اید!');
        }
        return redirect()->route('quiz.start', [
            'az' => $azmoon->id,
            'course_id' => $azmoon->course_id
        ]);
    }
    public function nextExamQuestion(Request $request)
    {
        $request->validate([
            'answer_id' => 'required|exists:answers,id',
            'answer' => 'nullable|string',
        ]);
        $currentAnswer = Answer::findOrFail($request->answer_id);
        $quiz = Quiz::findOrFail($currentAnswer->quiz_id);
        $azmon = Azmon::findOrFail($quiz->azmon_id);
        $course = Course::findOrFail($azmon->course_id);
        $now = Carbon::now();
        $endTime = $this->getExamEndTime($quiz, $azmon, $currentAnswer);
        if ($now->greaterThanOrEqualTo($endTime)) {
            return $this->finishExam($quiz, $azmon, $course);
        }
        if ($request->has('answer')) {
            $answerValue = (int) $request->answer;
            if ($answerValue >= 0 && $answerValue <= 3) {
                $currentAnswer->answer = $answerValue + 1;
            } else {
                $currentAnswer->answer = $answerValue;
            }
            $currentAnswer->save();
        }
        $oldQuestions = Answer::where('quiz_id', $quiz->id)
            ->whereNotNull('answer')
            ->pluck('question_id');
        if ($oldQuestions->count() >= $azmon->num) {
            return $this->finishExam($quiz, $azmon, $course);
        }
        $sessionIds = explode(",", $azmon->sessions);
        $nextQuestion = $this->getNextQuestionForExam($azmon, $sessionIds, $oldQuestions);
        if (!$nextQuestion) {
            return $this->finishExam($quiz, $azmon, $course);
        }
        $newAnswer = new Answer();
        $newAnswer->quiz_id = $quiz->id;
        $newAnswer->question_id = $nextQuestion->id;
        $newAnswer->save();
        $settings = [
            'show_nomre' => $azmon->show_nomre ?? 0,
            'show_ans' => $azmon->show_ans ?? 0,
            'changeable' => $azmon->changeable ?? 0,
            'show_remain' => $azmon->show_remain ?? 0,
            'show_state' => $azmon->show_state ?? 0,
        ];
        $endTime = $this->getExamEndTime($quiz, $azmon, $newAnswer);
        $totalQuestions = $azmon->num;
        $currentNumber = $oldQuestions->count() + 1;
        $options = $this->shuffleOptions($nextQuestion);
        return view('student.exam', [
            'azmon' => $azmon,
            'course' => $course,
            'quiz' => $quiz,
            'question' => $nextQuestion,
            'answer' => $newAnswer,
            'settings' => $settings,
            'endTime' => $endTime,
            'totalQuestions' => $totalQuestions,
            'currentNumber' => $currentNumber,
            'options' => $options,
        ]);
    }
    private function getExamEndTime($quiz, $azmon, $answer = null)
    {
        if ((int) $azmon->time_limit_khod === 1) {
            if ($azmon->time_type === 'per_question') {
                $start = $answer && $answer->created_at
                    ? Carbon::parse($answer->created_at)
                    : Carbon::parse($quiz->start);
                return $start->addMinutes((int) $azmon->time_per_question);
            }
            if ($azmon->time_type === 'total') {
                return Carbon::parse($quiz->start)->addMinutes((int) $azmon->total_time_limit);
            }
        }
        return Carbon::parse($quiz->start)->addMinutes((int) $azmon->time);
    }
    private function finishExam($quiz, $azmon, $course)
    {
        $answers = Answer::where('quiz_id', $quiz->id)->get();
        $totalQuestions = $answers->count();
        $correctAnswers = 0;
        $wrongAnswers = 0;
        foreach ($answers as $answer) {
            $question = Question::find($answer->question_id);
            if ($question) {
                if ($question->answer == $answer->answer) {
                    $correctAnswers++;
                } else {
                    $wrongAnswers++;
                }
            }
        }
        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 20, 2) : 0;
        $quiz->score = $score;
        $quiz->save();
        Log::info('آزمون به پایان رسید', [
            'quiz_id' => $quiz->id,
            'user_id' => $quiz->user_id,
            'azmon_id' => $azmon->id,
            'score' => $score,
            'correct' => $correctAnswers,
            'wrong' => $wrongAnswers,
            'total' => $totalQuestions
        ]);
        if ($azmon->show_nomre == 0) {
            return redirect()->route('courses.st', $course->id)
                ->with('success', 'آزمون با موفقیت به پایان رسید.');
        }
        return redirect()->route('exam.results', $quiz->id);
    }
    public function examResults($id)
    {
        $quiz = Quiz::with('course')->findOrFail($id);
        $azmon = Azmon::findOrFail($quiz->azmon_id);
        $user = User::findOrFail($quiz->user_id);
        $answers = Answer::where('quiz_id', $quiz->id)->get();
        $questions = Question::whereIn('id', $answers->pluck('question_id'))->get();
        $totalQuestions = $questions->count();
        $correctAnswers = 0;
        $wrongAnswers = 0;
        foreach ($questions as $question) {
            $answer = Answer::where('quiz_id', $quiz->id)
                ->where('question_id', $question->id)
                ->first();
            $question['user_answer'] = $answer;
            if ($answer) {
                if ($answer->answer == $question->answer) {
                    $correctAnswers++;
                } else {
                    $wrongAnswers++;
                }
            }
        }
        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 20, 2) : 0;
        $percentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        $motivational = $this->getMotivationalMessage($score);
        $course = Course::find($quiz->course_id);
        return view('student.exam-results', [
            'quiz' => $quiz,
            'azmon' => $azmon,
            'questions' => $questions,
            'course' => $course,
            'user' => $user,
            'score' => $score,
            'percentage' => $percentage,
            'correctAnswers' => $correctAnswers,
            'wrongAnswers' => $wrongAnswers,
            'totalQuestions' => $totalQuestions,
            'motivational' => $motivational,
        ]);
    }
    public function examHistory()
    {
        $user = Auth::user();
        $quizzes = Quiz::where('user_id', $user->id)
            ->whereNotNull('azmon_id')
            ->whereNotNull('score')
            ->orderBy('created_at', 'desc')
            ->with('azmon', 'course')
            ->get();
        foreach ($quizzes as $quiz) {
            $answers = Answer::where('quiz_id', $quiz->id)->get();
            $total = $answers->count();
            $correct = 0;
            foreach ($answers as $answer) {
                $question = Question::find($answer->question_id);
                if ($question && $question->answer == $answer->answer) {
                    $correct++;
                }
            }
            $quiz->total_questions = $total;
            $quiz->correct_answers = $correct;
            $quiz->wrong_answers = $total - $correct;
        }
        return view('student.exam-history', compact('quizzes'));
    }
    public function getExamInfo($id)
    {
        try {
            $exam = Azmon::with('course')->findOrFail($id);
            $duration = $exam->time ?? 0;
            if ((int) $exam->time_limit_khod === 1) {
                if ($exam->time_type === 'per_question') {
                    $duration = $exam->time_per_question ?? 0;
                } elseif ($exam->time_type === 'total') {
                    $duration = $exam->total_time_limit ?? 0;
                }
            }
            return response()->json([
                'title' => $exam->title,
                'description' => $exam->description ?? 'توضیحاتی برای این آزمون وجود ندارد.',
                'has_code' => !empty($exam->code) && $exam->code !== null,
                'start_time' => $exam->start ? Carbon::parse($exam->start)->format('Y/m/d H:i') : null,
                'end_time' => $exam->end ? Carbon::parse($exam->end)->format('Y/m/d H:i') : null,
                'duration' => $duration,
                'time_limit_khod' => (int) ($exam->time_limit_khod ?? 0),
                'time_type' => $exam->time_type,
                'time_per_question' => (int) ($exam->time_per_question ?? 0),
                'total_time_limit' => (int) ($exam->total_time_limit ?? 0),
                'question_count' => $exam->num ?? 0,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting exam info: ' . $e->getMessage());
            return response()->json(['error' => 'آزمون یافت نشد'], 404);
        }
    }
    public function verifyExamCode(Request $request, $id)
    {
        try {
            $exam = Azmon::findOrFail($id);
            if (empty($exam->code) || $exam->code === null) {
                return response()->json(['valid' => true]);
            }
            $enteredCode = trim($request->code);
            $valid = $enteredCode === $exam->code;
            return response()->json(['valid' => $valid]);
        } catch (\Exception $e) {
            Log::error('Error verifying exam code: ' . $e->getMessage());
            return response()->json(['error' => 'خطا در بررسی کد'], 500);
        }
    }
    public function startExam(Request $request)
    {
        try {
            $exam = Azmon::findOrFail($request->azmon_id);
            $course = Course::findOrFail($exam->course_id);
            $user = Auth::user();
            if (!empty($exam->code) && $exam->code !== null) {
                if (!$request->has('exam_code') || empty($request->exam_code)) {
                    return redirect()->back()->with('error', 'لطفاً کد آزمون را وارد کنید');
                }
                if ($request->exam_code !== $exam->code) {
                    return redirect()->back()->with('error', 'کد آزمون صحیح نیست');
                }
            }
            $now = Carbon::now();
            if ($now < Carbon::parse($exam->start)) {
                return redirect()->back()->with('error', 'زمان شروع آزمون فرا نرسیده است.');
            }
            if ($now > Carbon::parse($exam->end)) {
                return redirect()->back()->with('error', 'زمان آزمون به پایان رسیده است.');
            }
            $existingQuiz = Quiz::where('user_id', $user->id)
                ->where('azmon_id', $exam->id)
                ->first();
            if ($existingQuiz) {
                return redirect()->back()->with('error', 'شما قبلاً در این آزمون شرکت کرده‌اید!');
            }
            $sessionIds = explode(",", $exam->sessions);
            $question = $this->getQuestionForExam($exam, $sessionIds);
            if (!$question) {
                return redirect()->back()->with('error', 'هنوز سوالی برای این آزمون طرح نشده است.');
            }
            $quiz = new Quiz();
            $quiz->course_id = $course->id;
            $quiz->user_id = $user->id;
            $quiz->azmon_id = $exam->id;
            $quiz->start = Carbon::now();
            $quiz->score = 0;
            $quiz->save();
            $answer = new Answer();
            $answer->quiz_id = $quiz->id;
            $answer->question_id = $question->id;
            $answer->save();
            $settings = [
                'show_nomre' => $exam->show_nomre ?? 0,
                'show_ans' => $exam->show_ans ?? 0,
                'changeable' => $exam->changeable ?? 0,
                'show_remain' => $exam->show_remain ?? 0,
                'show_state' => $exam->show_state ?? 0,
            ];
            $endTime = $this->getExamEndTime($quiz, $exam, $answer);
            $totalQuestions = $exam->num;
            $currentNumber = 1;
            $options = $this->shuffleOptions($question);
            return view('student.exam', [
                'azmon' => $exam,
                'course' => $course,
                'quiz' => $quiz,
                'question' => $question,
                'answer' => $answer,
                'settings' => $settings,
                'endTime' => $endTime,
                'totalQuestions' => $totalQuestions,
                'currentNumber' => $currentNumber,
                'options' => $options,
            ]);
        } catch (\Exception $e) {
            Log::error('Error starting exam: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'خطا در شروع آزمون: ' . $e->getMessage());
        }
    }
    public function checkExamAccess($examId)
    {
        try {
            $exam = Azmon::findOrFail($examId);
            $user = Auth::user();
            $now = Carbon::now();
            if ($now < Carbon::parse($exam->start)) {
                return response()->json([
                    'accessible' => false,
                    'message' => 'آزمون هنوز شروع نشده است.'
                ]);
            }
            if ($now > Carbon::parse($exam->end)) {
                return response()->json([
                    'accessible' => false,
                    'message' => 'زمان آزمون به پایان رسیده است.'
                ]);
            }
            $existingQuiz = Quiz::where('user_id', $user->id)
                ->where('azmon_id', $exam->id)
                ->first();
            if ($existingQuiz) {
                return response()->json([
                    'accessible' => false,
                    'message' => 'شما قبلاً در این آزمون شرکت کرده‌اید.'
                ]);
            }
            return response()->json([
                'accessible' => true,
                'message' => 'می‌توانید آزمون را شروع کنید.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'accessible' => false,
                'message' => 'خطا در بررسی دسترسی'
            ], 500);
        }
    }
    private function getQuestionForExam($azmon, $sessionIds)
    {
        $query = Question::whereIn('session_id', $sessionIds);
        switch ($azmon->sath) {
            case 1:
                $query->where('status', 1);
                break;
            case 2:
                $query->where('status', 2);
                break;
            case 3:
                $query->whereIn('status', [1, 2]);
                break;
            case 4:
                $query->where('star', 1);
                break;
            case 5:
                $teacher = Course::find($azmon->course_id)->users()->where('role_id', 2)->first();
                if ($teacher) {
                    $query->where('user_id', $teacher->id);
                }
                break;
        }
        return $query->inRandomOrder()->first();
    }
    private function getNextQuestionForExam($azmon, $sessionIds, $oldQuestions)
    {
        $query = Question::whereIn('session_id', $sessionIds)
            ->whereNotIn('id', $oldQuestions);
        switch ($azmon->sath) {
            case 1:
                $query->where('status', 1);
                break;
            case 2:
                $query->where('status', 2);
                break;
            case 3:
                $query->whereIn('status', [1, 2]);
                break;
            case 4:
                $query->where('star', 1);
                break;
            case 5:
                $teacher = Course::find($azmon->course_id)->users()->where('role_id', 2)->first();
                if ($teacher) {
                    $query->where('user_id', $teacher->id);
                }
                break;
        }
        return $query->inRandomOrder()->first();
    }
    private function shuffleOptions($question)
    {
        $options = [
            ['label' => 'الف', 'value' => $question->answer1, 'index' => 0],
            ['label' => 'ب', 'value' => $question->answer2, 'index' => 1],
            ['label' => 'ج', 'value' => $question->answer3, 'index' => 2],
            ['label' => 'د', 'value' => $question->answer4, 'index' => 3],
        ];
        shuffle($options);
        return $options;
    }
    private function getMotivationalMessage($score)
    {
        if ($score == 20) {
            $level = 1;
        } elseif ($score >= 18) {
            $level = 2;
        } elseif ($score >= 15) {
            $level = 3;
        } elseif ($score >= 12) {
            $level = 4;
        } elseif ($score >= 10) {
            $level = 5;
        } else {
            $level = 6;
        }
        return Angizesh::where('level', $level)->inRandomOrder()->first();
    }
    private function convertPersianToCarbon($dateString)
    {
        $dateString = trim($dateString);
        $parts = explode(" ", $dateString);
        $date = $parts[0] ?? '';
        $time = $parts[1] ?? '00:00:00';
        $dateParts = explode("/", $date);
        $year = (int) $this->convertNumbers($dateParts[0] ?? 0);
        $month = (int) $this->convertNumbers($dateParts[1] ?? 1);
        $day = (int) $this->convertNumbers($dateParts[2] ?? 1);
        $timeParts = explode(":", $time);
        $hour = (int) $this->convertNumbers($timeParts[0] ?? 0);
        $minute = (int) $this->convertNumbers($timeParts[1] ?? 0);
        $second = (int) $this->convertNumbers($timeParts[2] ?? 0);
        try {
            $persianDate = $year . '/' . str_pad($month, 2, '0', STR_PAD_LEFT) . '/' . str_pad($day, 2, '0', STR_PAD_LEFT);
            $persianDateTime = $persianDate . ' ' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minute, 2, '0', STR_PAD_LEFT) . ':' . str_pad($second, 2, '0', STR_PAD_LEFT);
            $verta = Verta::parse($persianDateTime);
            return $verta->toCarbon();
        } catch (\Exception $e) {
            try {
                $verta = Verta::create($year, $month, $day, $hour, $minute, $second);
                return $verta->toCarbon();
            } catch (\Exception $e2) {
                return Carbon::now();
            }
        }
    }
    private function convertNumbers($string)
    {
        if (!$string) return $string;
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $num = range(0, 9);
        $converted = str_replace($persian, $num, (string) $string);
        return str_replace($arabic, $num, $converted);
    }
}