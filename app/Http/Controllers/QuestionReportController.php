<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\QuestionReport;
use App\Models\Question;
use App\Models\ScoreQuestion;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuestionReportController extends Controller
{
    /**
     * لیست گزارش‌های ایراد سوال (برای استاد)
     */
    public function index()
    {
        $reports = QuestionReport::with(['user', 'question.user', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($reports as $report) {
            $report->scores = ScoreQuestion::where('question_id', $report->question_id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $stats = [
            'total' => $reports->count(),
            'pending' => $reports->where('status', 'pending')->count(),
            'reviewed' => $reports->where('status', 'reviewed')->count(),
            'resolved' => $reports->where('status', 'resolved')->count(),
            'rejected' => $reports->where('status', 'rejected')->count(),
        ];

        return view('teacher.question-reports', compact('reports', 'stats'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'description' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        $existingReport = QuestionReport::where('user_id', $user->id)
            ->where('question_id', $request->question_id)
            ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'شما قبلاً برای این سوال گزارش ثبت کرده‌اید.'
            ], 422);
        }

        $report = new QuestionReport();
        $report->user_id = $user->id;
        $report->question_id = $request->question_id;
        $report->description = $request->description;
        $report->status = 'pending';
        $report->save();

        return response()->json([
            'success' => true,
            'message' => 'گزارش شما با موفقیت ثبت شد.',
            'data' => $report
        ]);
    }

    /**
     * لیست گزارش‌های یک درس (برای استاد)
     */
    public function courseReports($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        $sessionIds = Session::where('course_id', $courseId)->pluck('id');
        $questionIds = Question::whereIn('session_id', $sessionIds)->pluck('id');
        
        // همه گزارش‌ها را بگیر
        $allReports = QuestionReport::whereIn('question_id', $questionIds)
            ->with(['user', 'question.user', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->get();

        // فقط گزارش‌های pending را فیلتر کن
        $reports = $allReports->where('status', 'pending');

        foreach ($reports as $report) {
            $report->scores = ScoreQuestion::where('question_id', $report->question_id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $approvedScores = ScoreQuestion::where('question_id', $report->question_id)
                ->where('status', 'approved')
                ->pluck('score')
                ->toArray();
            
            $report->average_score = count($approvedScores) > 0 
                ? round(array_sum($approvedScores) / count($approvedScores), 2) 
                : null;
        }

        // آمار کامل از همه گزارش‌ها
        $stats = [
            'total' => $allReports->count(),
            'pending' => $allReports->where('status', 'pending')->count(),
            'reviewed' => $allReports->where('status', 'reviewed')->count(),
            'resolved' => $allReports->where('status', 'resolved')->count(),
            'rejected' => $allReports->where('status', 'rejected')->count(),
        ];

        return view('teacher.question-reports', compact('course', 'reports', 'stats'));
    }
    /**
     * بروزرسانی وضعیت گزارش (برای استاد)
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,reviewed,resolved,rejected',
            'admin_response' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = QuestionReport::findOrFail($id);
        $report->status = $request->status;
        $report->admin_response = $request->admin_response;
        $report->reviewed_by = Auth::id();
        $report->reviewed_at = now();
        $report->save();

        return response()->json([
            'success' => true,
            'message' => 'وضعیت گزارش با موفقیت بروزرسانی شد.',
            'data' => $report
        ]);
    }

    /**
     * بروزرسانی سوال (استاد)
     */
    public function updateQuestion(Request $request, $id)
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
}