<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentEvent;

class StudentEventController extends Controller
{
    public function index($studentId)
    {
        $events = StudentEvent::where('student_id', $studentId)
            ->select('event', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'event'      => $item->event,
                    'created_at' => $item->created_at->format('Y/m/d H:i'),
                ];
            });
    
        return response()->json($events);
    }
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'event'      => 'required|string|max:255',
        ]);

        $ev = new StudentEvent();
        $ev->student_id = $request->student_id;
        $ev->teacher_id = Auth::id();
        $ev->event      = $request->event;
        $ev->save();

        $newCount = StudentEvent::where('student_id', $request->student_id)->count();

        return response()->json([
            'success'   => true,
            'new_count' => $newCount,
        ]);
    }
}