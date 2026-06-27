<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Course;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * نمایش لیست چت‌ها
     */
    public function index()
    {
        $user = Auth::user();
        $chats = collect();

        if ($user->hasRole('student')) {
            $courses = $user->courses()->get();
            
            foreach ($courses as $course) {
                $chat = Chat::where('course_id', $course->id)
                    ->where('student_id', $user->id)
                    ->orderBy('updated_at', 'desc')
                    ->first();

                if ($chat) {
                    $messages = Message::where('chat_id', $chat->id)->get();
                    $last = Message::where('chat_id', $chat->id)->orderBy('id', 'desc')->first();

                    // وضعیت دیده شدن
                    $chat->seen_status = $this->getSeenStatusForStudent($chat);
                    
                    $chatData = (object)[
                        'id' => $chat->id,
                        'course_name' => $course->name,
                        'course_id' => $course->id,
                        'messages' => $messages,
                        'last_message' => $last,
                        'seen_status' => $chat->seen_status,
                        'updated_at' => $last ? $last->created_at : $chat->updated_at,
                        'is_teacher' => false,
                        'student_name' => null,
                    ];
                    $chats->push($chatData);
                } else {
                    // ایجاد چت جدید اگر وجود نداشت
                    $chat = new Chat();
                    $chat->course_id = $course->id;
                    $chat->student_id = $user->id;
                    $chat->save();

                    $chatData = (object)[
                        'id' => $chat->id,
                        'course_name' => $course->name,
                        'course_id' => $course->id,
                        'messages' => collect(),
                        'last_message' => null,
                        'seen_status' => 'new',
                        'updated_at' => $chat->updated_at,
                        'is_teacher' => false,
                        'student_name' => null,
                    ];
                    $chats->push($chatData);
                }
            }

            $chats = $chats->sortByDesc('updated_at');

        } else {
            // استاد
            $courses = $user->courses()->pluck('courses.id');
            $chats = Chat::whereIn('course_id', $courses)
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($chats as $chat) {
                $messages = Message::where('chat_id', $chat->id)->get();
                $last = Message::where('chat_id', $chat->id)->orderBy('id', 'desc')->first();

                $chat->seen_status = $this->getSeenStatusForTeacher($chat);
                $chat->course = Course::find($chat->course_id);
                $chat->student = User::find($chat->student_id);
                $chat->last_message = $last;
                
                // حذف چت‌های بدون پیام
                if ($messages->isEmpty() && $chat->seen_status === 'new') {
                    continue;
                }
            }
        }

        return view('chat.index', compact('chats'));
    }

    /**
     * ارسال پیام
     */
    public function send(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'text' => 'required|string|min:1|max:1000',
        ]);

        $user = Auth::user();
        $chat = Chat::findOrFail($request->chat_id);

        // به‌روزرسانی زمان
        $chat->updated_at = Carbon::now();
        $chat->save();

        // ایجاد پیام جدید
        $message = new Message();
        $message->sender = $user->hasRole('student') ? 1 : 2;
        $message->text = $request->text;
        $message->chat_id = $chat->id;
        $message->save();

        // به‌روزرسانی وضعیت دیده شدن
        if ($user->hasRole('student')) {
            $chat->seen = 1;
        } else {
            $chat->seen = 2;
        }
        $chat->save();

        return response()->json([
            'success' => true,
            'message' => 'پیام با موفقیت ارسال شد',
            'data' => [
                'id' => $message->id,
                'text' => $message->text,
                'sender' => $message->sender,
                'created_at' => Verta::instance($message->created_at)->format('H:i'),
            ]
        ]);
    }

    /**
     * دریافت پیام‌های جدید (AJAX)
     */
    public function getMessages($chatId)
    {
        $chat = Chat::findOrFail($chatId);
        $user = Auth::user();

        // به‌روزرسانی وضعیت دیده شدن
        if ($user->hasRole('student')) {
            $chat->seen = 1;
        } else {
            $chat->seen = 2;
        }
        $chat->save();

        $messages = Message::where('chat_id', $chatId)->get();

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'text' => $msg->text,
                    'sender' => $msg->sender,
                    'created_at' => Verta::instance($msg->created_at)->format('H:i'),
                ];
            })
        ]);
    }

    /**
     * دریافت وضعیت دیده شدن برای دانشجو
     */
    private function getSeenStatusForStudent($chat)
    {
        if ($chat->seen == 0 || $chat->seen == 2) {
            $chat->seen = 1;
            $chat->save();
            return 'unread';
        }
        return 'read';
    }

    /**
     * دریافت وضعیت دیده شدن برای استاد
     */
    private function getSeenStatusForTeacher($chat)
    {
        if ($chat->seen == 0 || $chat->seen == 2) {
            return 'unread';
        }
        if ($chat->seen == 1) {
            $chat->seen = 3;
            $chat->save();
            return 'read';
        }
        return 'read';
    }
}