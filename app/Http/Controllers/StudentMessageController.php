<?php

namespace App\Http\Controllers;

use App\Models\StudentMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentMessageController extends Controller
{
    /**
     * ارسال پیام از طرف استاد به دانشجو
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'text' => 'required|string|max:5000',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $message = StudentMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'text' => $request->text,
            'course_id' => $request->course_id,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'پیام با موفقیت ارسال شد',
            'data' => $message->load(['sender', 'receiver'])
        ]);
    }

    /**
     * دریافت لیست پیام‌های یک دانشجو (برای استاد)
     */
    public function getMessages($studentId)
    {
        $messages = StudentMessage::where(function($q) use ($studentId) {
                $q->where('sender_id', Auth::id())
                  ->where('receiver_id', $studentId);
            })
            ->orWhere(function($q) use ($studentId) {
                $q->where('sender_id', $studentId)
                  ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->with(['sender', 'receiver'])
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * علامت‌گذاری پیام به عنوان خوانده شده
     */
    public function markAsRead($messageId)
    {
        $message = StudentMessage::where('receiver_id', Auth::id())
                                  ->where('id', $messageId)
                                  ->firstOrFail();
        $message->markAsRead();
        return response()->json(['success' => true]);
    }

    /**
     * صفحه پیام‌های دانشجو
     */
    public function studentIndex()
    {
        // همه پیام‌های خوانده نشده این کاربر رو یکجا به عنوان خوانده شده علامت‌گذاری کن
        StudentMessage::where('receiver_id', Auth::id())
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);
        return view('student.messages.index');
    }

    /**
     * دریافت پیام‌های دانشجو به صورت JSON
     */
    public function studentGetMessages(Request $request)
    {
        $query = StudentMessage::where('receiver_id', Auth::id())
                               ->orWhere('sender_id', Auth::id())
                               ->orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('text', 'LIKE', "%{$search}%")
                  ->orWhereHas('sender', function($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('family', 'LIKE', "%{$search}%");
                  });
            });
        }

        $messages = $query->with(['sender', 'receiver'])->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }
    /**
     * دریافت تعداد پیام‌های خوانده نشده
     */
    public function getUnreadCount()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'unread_count' => 0
            ]);
        }
        
        $count = StudentMessage::unreadForUser(Auth::id())->count();
        return response()->json([
            'success' => true,
            'unread_count' => $count
        ]);
    }
}