<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentAdjective;
use App\Models\User;

class StudentAdjectiveController extends Controller
{
    // GET /dashboard/adjectives/{studentId}
    public function index($studentId)
    {
        $adjectives = StudentAdjective::where('student_id', $studentId)
            ->where('teacher_id', Auth::id())   // only this teacher
            ->select('adjective', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'adjective'  => $item->adjective,
                    'created_at' => $item->created_at->format('Y/m/d H:i'),
                ];
            });
    
        return response()->json($adjectives);
    }

    // POST /dashboard/adjectives
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'adjective'  => 'required|string|max:100',
        ]);

        StudentAdjective::create([
            'student_id' => $request->student_id,
            'teacher_id' => Auth::id(),
            'adjective'  => $request->adjective,
        ]);

        $newCount = StudentAdjective::where('student_id', $request->student_id)->count();

        return response()->json([
            'success'   => true,
            'new_count' => $newCount,
        ]);
    }
}