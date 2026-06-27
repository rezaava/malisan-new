@extends('layout.master')

@section('title')
ملیسان | گفتگو
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .chat-container {
        max-width: 1100px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .chat-wrapper {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 30px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        display: flex;
        height: 600px;
    }

    /* ===== SIDEBAR ===== */
    .chat-sidebar {
        width: 320px;
        min-width: 320px;
        border-left: 1px solid #f0f4f9;
        display: flex;
        flex-direction: column;
        background: #fafbfc;
    }

    .chat-sidebar-header {
        padding: 18px 20px;
        border-bottom: 1px solid #f0f4f9;
        background: #fff;
    }

    .chat-sidebar-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1a2332;
    }

    .chat-sidebar-header h5 i {
        color: #1e6f9f;
        margin-left: 8px;
    }

    .chat-search {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f4f9;
    }

    .chat-search input {
        width: 100%;
        padding: 8px 14px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 13px;
        background: #fff;
        transition: all 0.3s ease;
    }

    .chat-search input:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
    }

    .chat-list {
        flex: 1;
        overflow-y: auto;
        padding: 8px 0;
    }

    .chat-list::-webkit-scrollbar {
        width: 4px;
    }

    .chat-list::-webkit-scrollbar-thumb {
        background: #d0d7e2;
        border-radius: 10px;
    }

    .chat-item {
        display: flex;
        align-items: center;
        padding: 12px 18px;
        cursor: pointer;
        transition: all 0.2s ease;
        border-right: 3px solid transparent;
        gap: 12px;
    }

    .chat-item:hover {
        background: #f0f7fe;
    }

    .chat-item.active {
        background: #e3f2fd;
        border-right-color: #1e6f9f;
    }

    .chat-item .avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1e6f9f;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }

    .chat-item .info {
        flex: 1;
        min-width: 0;
    }

    .chat-item .info .name {
        font-weight: 600;
        color: #1a2332;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .chat-item .info .name .badge-unread {
        background: #f44336;
        color: #fff;
        font-size: 10px;
        padding: 1px 8px;
        border-radius: 10px;
        font-weight: 700;
    }

    .chat-item .info .preview {
        font-size: 13px;
        color: #6b7a8f;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-item .time {
        font-size: 11px;
        color: #6b7a8f;
        flex-shrink: 0;
    }

    .chat-empty-sidebar {
        text-align: center;
        padding: 40px 20px;
        color: #6b7a8f;
    }

    .chat-empty-sidebar i {
        font-size: 40px;
        color: #d0d7e2;
        display: block;
        margin-bottom: 12px;
    }

    /* ===== CHAT BOX ===== */
    .chat-box {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .chat-box-header {
        padding: 16px 24px;
        border-bottom: 1px solid #f0f4f9;
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fff;
    }

    .chat-box-header .avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1e6f9f;
        font-weight: 700;
        font-size: 14px;
    }

    .chat-box-header .chat-title {
        font-weight: 600;
        color: #1a2332;
        font-size: 15px;
    }

    .chat-box-header .chat-subtitle {
        font-size: 12px;
        color: #6b7a8f;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px 24px;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .chat-messages::-webkit-scrollbar {
        width: 4px;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #d0d7e2;
        border-radius: 10px;
    }

    .message {
        max-width: 70%;
        padding: 10px 16px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.6;
        word-wrap: break-word;
        position: relative;
    }

    .message.sent {
        align-self: flex-end;
        background: #1e6f9f;
        color: #fff;
        border-bottom-left-radius: 4px;
    }

    .message.received {
        align-self: flex-start;
        background: #fff;
        color: #1a2332;
        border-bottom-right-radius: 4px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    }

    .message .msg-time {
        font-size: 10px;
        opacity: 0.7;
        display: block;
        margin-top: 4px;
        text-align: left;
    }

    .message.sent .msg-time {
        text-align: right;
    }

    .chat-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
        color: #6b7a8f;
        padding: 40px;
        text-align: center;
    }

    .chat-empty i {
        font-size: 48px;
        color: #d0d7e2;
        margin-bottom: 16px;
    }

    .chat-empty h5 {
        color: #1a2332;
        font-size: 18px;
        margin-bottom: 4px;
    }

    /* ===== CHAT INPUT ===== */
    .chat-input-area {
        padding: 14px 20px;
        border-top: 1px solid #f0f4f9;
        background: #fff;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .chat-input-area input {
        flex: 1;
        padding: 10px 16px;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
    }

    .chat-input-area input:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
        background: #fff;
    }

    .chat-input-area input:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-send {
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .btn-send:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
    }

    .btn-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .chat-wrapper {
            flex-direction: column;
            height: auto;
            min-height: 500px;
        }

        .chat-sidebar {
            width: 100%;
            min-width: unset;
            max-height: 250px;
            border-left: none;
            border-bottom: 1px solid #f0f4f9;
        }

        .chat-box {
            min-height: 400px;
        }

        .message {
            max-width: 85%;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="chat-container">
    <div class="chat-wrapper">
        {{-- SIDEBAR --}}
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <h5><i class="fas fa-comments"></i> گفتگوها</h5>
            </div>
            <div class="chat-search">
                <input type="text" id="chatSearch" placeholder="جستجو در گفتگوها...">
            </div>
            <div class="chat-list" id="chatList">
                @forelse($chats as $chat)
                    @php
                        $isActive = $loop->first;
                        $lastMsg = $chat->last_message ?? null;
                        $unread = $chat->seen_status === 'unread';
                    @endphp
                    <div class="chat-item {{ $isActive ? 'active' : '' }}" 
                         data-chat-id="{{ $chat->id }}"
                         onclick="selectChat(this, {{ $chat->id }})">
                        <div class="avatar">
                            @if(!Auth::user()->hasRole('student'))
                                {{ substr($chat->student->name ?? '?', 0, 1) }}
                            @else
                                {{ substr($chat->course_name ?? '?', 0, 1) }}
                            @endif
                        </div>
                        <div class="info">
                            <div class="name">
                                @if(Auth::user()->hasRole('student'))
                                    {{ $chat->course_name }}
                                @else
                                    {{ $chat->student->name ?? 'نامشخص' }} {{ $chat->student->family ?? '' }}
                                @endif
                                @if($unread)
                                    <span class="badge-unread">جدید</span>
                                @endif
                            </div>
                            <div class="preview">
                                {{ $lastMsg ? Str::limit($lastMsg->text, 30) : 'بدون پیام' }}
                            </div>
                        </div>
                        <div class="time">
                            @if($lastMsg)
                                {{ \Hekmatinasser\Verta\Verta::instance($lastMsg->created_at)->format('H:i') }}
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="chat-empty-sidebar">
                        <i class="fas fa-inbox"></i>
                        <p>هیچ گفتگویی وجود ندارد</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- CHAT BOX --}}
        <div class="chat-box">
            <div class="chat-box-header">
                <div class="avatar-sm" id="chatAvatar">?</div>
                <div>
                    <div class="chat-title" id="chatTitle">انتخاب مکالمه</div>
                    <div class="chat-subtitle" id="chatSubtitle">یک مکالمه را از سمت راست انتخاب کنید</div>
                </div>
            </div>

            <div class="chat-messages" id="chatMessages">
                <div class="chat-empty">
                    <i class="fas fa-comment-dots"></i>
                    <h5>هیچ پیامی وجود ندارد</h5>
                    <p>یک مکالمه را انتخاب کنید تا پیام‌ها نمایش داده شوند</p>
                </div>
            </div>

            <div class="chat-input-area">
                <input type="text" id="messageInput" placeholder="پیام خود را بنویسید..." disabled>
                <button class="btn-send" id="sendBtn" disabled>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let currentChatId = null;
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');

    // ==========================================
    // انتخاب چت
    // ==========================================
    function selectChat(element, chatId) {
        // حذف active قبلی
        document.querySelectorAll('.chat-item').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');

        currentChatId = chatId;
        messageInput.disabled = false;
        sendBtn.disabled = false;

        // نمایش لودینگ
        chatMessages.innerHTML = `
            <div style="text-align:center;padding:40px;color:#6b7a8f;">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p style="margin-top:12px;">در حال بارگذاری...</p>
            </div>
        `;

        // به‌روزرسانی هدر
        const nameEl = element.querySelector('.name');
        const name = nameEl ? nameEl.textContent.replace('جدید', '').trim() : 'مکالمه';
        document.getElementById('chatTitle').textContent = name;
        document.getElementById('chatSubtitle').textContent = 'گفتگو با ' + name;
        
        const avatar = element.querySelector('.avatar');
        document.getElementById('chatAvatar').textContent = avatar ? avatar.textContent : '?';

        // دریافت پیام‌ها
        fetch(`/chat/messages/${chatId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderMessages(data.messages);
                } else {
                    chatMessages.innerHTML = `
                        <div class="chat-empty">
                            <i class="fas fa-exclamation-circle"></i>
                            <h5>خطا در دریافت پیام‌ها</h5>
                            <p>لطفاً دوباره تلاش کنید</p>
                        </div>
                    `;
                }
            })
            .catch(() => {
                chatMessages.innerHTML = `
                    <div class="chat-empty">
                        <i class="fas fa-exclamation-circle"></i>
                        <h5>خطا در ارتباط با سرور</h5>
                        <p>لطفاً دوباره تلاش کنید</p>
                    </div>
                `;
            });
    }

    // ==========================================
    // نمایش پیام‌ها
    // ==========================================
    function renderMessages(messages) {
        if (!messages || messages.length === 0) {
            chatMessages.innerHTML = `
                <div class="chat-empty">
                    <i class="fas fa-comment-dots"></i>
                    <h5>هیچ پیامی وجود ندارد</h5>
                    <p>اولین پیام را ارسال کنید</p>
                </div>
            `;
            return;
        }

        let html = '';
        messages.forEach(msg => {
            const isSent = msg.sender === 1; // 1 = دانشجو, 2 = استاد
            html += `
                <div class="message ${isSent ? 'sent' : 'received'}">
                    ${msg.text}
                    <span class="msg-time">${msg.created_at || ''}</span>
                </div>
            `;
        });
        chatMessages.innerHTML = html;
        scrollToBottom();
    }

    // ==========================================
    // اسکرول به پایین
    // ==========================================
    function scrollToBottom() {
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 100);
    }

    // ==========================================
    // ارسال پیام (AJAX)
    // ==========================================
    function sendMessage() {
        const text = messageInput.value.trim();
        if (!text || !currentChatId) return;

        const btn = sendBtn;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch('{{ route("chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                chat_id: currentChatId,
                text: text
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                // اضافه کردن پیام جدید به صفحه
                const msgDiv = document.createElement('div');
                msgDiv.className = 'message sent';
                msgDiv.innerHTML = `
                    ${text}
                    <span class="msg-time">اکنون</span>
                `;
                // حذف empty state اگر وجود داشت
                const empty = chatMessages.querySelector('.chat-empty');
                if (empty) empty.remove();
                chatMessages.appendChild(msgDiv);
                scrollToBottom();
            } else {
                alert('خطا در ارسال پیام');
            }
        })
        .catch(() => {
            alert('خطا در ارتباط با سرور');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        });
    }

    // ==========================================
    // رویدادها
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        // انتخاب اولین چت به صورت خودکار
        const firstChat = document.querySelector('.chat-item');
        if (firstChat) {
            firstChat.click();
        }

        // ارسال با Enter
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });

        // دکمه ارسال
        sendBtn.addEventListener('click', sendMessage);

        // جستجو
        document.getElementById('chatSearch').addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.chat-item').forEach(item => {
                const name = item.querySelector('.name')?.textContent?.toLowerCase() || '';
                item.style.display = name.includes(query) ? 'flex' : 'none';
            });
        });
    });
</script>
@endsection