<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    public function create()
    {
        return view('teacher.create-question');
    }

    public function store(Request $request)
    {
        // اعتبارسنجی
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|min:5',
            'options' => 'required|array|min:4|max:4',
            'options.*' => 'required|string|min:1',
            'correct_answer' => 'required|integer|min:0|max:3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // گرفتن گزینه‌ها
            $options = $request->options;
            $correctIndex = (int) $request->correct_answer;

            // اطمینان از اینکه اندیس صحیح معتبره
            if (!isset($options[$correctIndex])) {
                return redirect()->back()
                    ->with('error', 'گزینه صحیح نامعتبر است')
                    ->withInput();
            }

            // ذخیره سوال در دیتابیس
            $question = Question::create([
                'question' => $request->question,
                'answer1' => $options[0] ?? '',
                'answer2' => $options[1] ?? '',
                'answer3' => $options[2] ?? '',
                'answer4' => $options[3] ?? '',
                'answer' => $options[$correctIndex],
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
            return redirect()->back()
                ->with('error', 'خطا در ثبت سوال: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function getRandomQuestions($count = 10)
    {
        $questions = Question::with('user')
            ->where('status', '!=', 4)
            ->inRandomOrder()
            ->limit($count)
            ->get();

        $formattedQuestions = [];
        foreach ($questions as $q) {
            $options = [
                $q->answer1,
                $q->answer2,
                $q->answer3,
                $q->answer4,
            ];

            $shuffledOptions = $options;
            shuffle($shuffledOptions);
            $newCorrectIndex = array_search($q->answer, $shuffledOptions);

            $formattedQuestions[] = [
                'id' => $q->id,
                'question' => $q->question,
                'options' => $shuffledOptions,
                'correct_answer' => $newCorrectIndex,
                'level' => $q->level,
                'user_name' => $q->user->name ?? 'ناشناس',
            ];
        }

        return response()->json($formattedQuestions);
    }

    public function getQuestionsForExam($sessionId, $count = 20)
    {
        $questions = Question::where('session_id', $sessionId)
            ->where('status', '!=', 4)
            ->inRandomOrder()
            ->limit($count)
            ->get();

        $result = [];
        foreach ($questions as $q) {
            $options = [
                $q->answer1,
                $q->answer2,
                $q->answer3,
                $q->answer4,
            ];

            $shuffled = $options;
            shuffle($shuffled);
            $correctIndex = array_search($q->answer, $shuffled);

            $result[] = [
                'id' => $q->id,
                'question' => $q->question,
                'options' => $shuffled,
                'correct_answer' => $correctIndex,
                'level' => $q->level,
            ];
        }

        return $result;
    }
}