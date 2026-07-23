@extends('layout.master')

@section('title')
ملیسان | گفتگو
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/chat.css')}}">
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

        fetch(' route("chat.send")', {
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