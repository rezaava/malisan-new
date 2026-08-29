@extends('layout.master')

@section('title')
    پیام‌های من | ملیسان
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/style-messages.css') }}">
@endsection

@section('mohtava')
<div class="messages-container">
    <div class="messages-header">
        <h2>
            <i class="fas fa-inbox"></i>
            پیام‌های من
        </h2>
        <div class="messages-status">
            <span class="unread-badge" id="unreadBadge">
                <i class="fas fa-circle"></i>
                <span id="unreadCount">0</span> پیام خوانده نشده
            </span>
        </div>
    </div>

    <div class="search-wrapper">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="جستجو در پیام‌ها...">
            <button class="search-clear" id="clearSearch">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div class="messages-list" id="messagesList">
        <div class="loading-messages">
            <i class="fas fa-spinner fa-spin"></i>
            در حال بارگذاری پیام‌ها...
        </div>
    </div>

    <div class="pagination-wrapper" id="paginationWrapper">
        <!-- صفحه‌بندی -->
    </div>
</div>
@endsection

@section('js')
<script>
    var csrfToken = '{{ csrf_token() }}';
    var currentPage = 1;
    var searchQuery = '';

    // ============================================
    // بارگذاری پیام‌ها
    // ============================================
    function loadMessages(page = 1, search = '') {
        var messagesList = document.getElementById('messagesList');
        var paginationWrapper = document.getElementById('paginationWrapper');
        
        messagesList.innerHTML = `
            <div class="loading-messages">
                <i class="fas fa-spinner fa-spin"></i>
                در حال بارگذاری پیام‌ها...
            </div>
        `;

        var url = '{{ route("student.messages.get") }}?page=' + page;
        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }

        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderMessages(data.data.data);
                renderPagination(data.data);
                updateUnreadCount();
            } else {
                messagesList.innerHTML = `
                    <div class="empty-messages">
                        <i class="fas fa-inbox"></i>
                        <p>خطا در بارگذاری پیام‌ها</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messagesList.innerHTML = `
                <div class="empty-messages">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>خطا در ارتباط با سرور</p>
                </div>
            `;
        });
    }

    // ============================================
    // رندر پیام‌ها
    // ============================================
    function renderMessages(messages) {
        var messagesList = document.getElementById('messagesList');
        
        if (!messages || messages.length === 0) {
            messagesList.innerHTML = `
                <div class="empty-messages">
                    <i class="fas fa-inbox"></i>
                    <p>هیچ پیامی وجود ندارد</p>
                </div>
            `;
            return;
        }

        var html = '';
        messages.forEach(function(message) {
            var isFromMe = message.sender_id == {{ Auth::id() }};
            var senderName = isFromMe ? 'من' : (message.sender.name + ' ' + (message.sender.family || ''));
            var isRead = message.is_read;
            var time = new Date(message.created_at).toLocaleString('fa-IR');
            
            html += `
                <div class="message-item ${isFromMe ? 'sent' : 'received'} ${!isRead && !isFromMe ? 'unread' : ''}" 
                     data-message-id="${message.id}">
                    <div class="message-avatar">
                        <i class="fas ${isFromMe ? 'fa-user-check' : 'fa-user-tie'}"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-header">
                            <span class="message-sender">${senderName}</span>
                            <span class="message-time">${time}</span>
                            ${!isFromMe && !isRead ? '<span class="unread-dot">جدید</span>' : ''}
                        </div>
                        <div class="message-body">${message.text}</div>
                        ${message.course ? `<div class="message-course"><i class="fas fa-book"></i> ${message.course.name}</div>` : ''}
                    </div>
                </div>
            `;
        });

        messagesList.innerHTML = html;
    }

    // ============================================
    // رندر صفحه‌بندی
    // ============================================
    function renderPagination(data) {
        var wrapper = document.getElementById('paginationWrapper');
        
        if (data.last_page <= 1) {
            wrapper.innerHTML = '';
            return;
        }

        var html = '<div class="pagination">';
        html += `<button class="page-btn" onclick="loadMessages(${data.current_page - 1}, '${searchQuery}')" 
                         ${data.current_page <= 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-right"></i>
                 </button>`;
        
        for (var i = 1; i <= data.last_page; i++) {
            html += `<button class="page-btn ${i === data.current_page ? 'active' : ''}" 
                             onclick="loadMessages(${i}, '${searchQuery}')">
                        ${i}
                     </button>`;
        }
        
        html += `<button class="page-btn" onclick="loadMessages(${data.current_page + 1}, '${searchQuery}')" 
                         ${data.current_page >= data.last_page ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </button>`;
        html += '</div>';

        wrapper.innerHTML = html;
    }

    // ============================================
    // بروزرسانی تعداد پیام‌های خوانده نشده
    // ============================================
    function updateUnreadCount() {
        fetch('{{ route("api.unread.messages.count") }}', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var countSpan = document.getElementById('unreadCount');
                var badge = document.getElementById('unreadBadge');
                if (countSpan) {
                    countSpan.textContent = data.unread_count;
                    if (data.unread_count > 0) {
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // ============================================
    // جستجو
    // ============================================
    var searchInput = document.getElementById('searchInput');
    var clearSearch = document.getElementById('clearSearch');
    var searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                searchQuery = searchInput.value.trim();
                loadMessages(1, searchQuery);
            }, 500);
        });
    }

    if (clearSearch) {
        clearSearch.addEventListener('click', function() {
            searchInput.value = '';
            searchQuery = '';
            loadMessages(1, '');
            searchInput.focus();
        });
    }

    // ============================================
    // بارگذاری اولیه
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        loadMessages(1);
        // بروزرسانی هر 30 ثانیه
        setInterval(function() {
            loadMessages(currentPage, searchQuery);
        }, 30000);
    });
</script>
@endsection