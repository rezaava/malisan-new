@extends('layout.master')

@section('title')
ملیسان | فعالیت‌های دانشجویان
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-activities.css')}}">
<link rel="stylesheet" href="{{asset('css/badge.css')}}">
    <style>

        .online-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .online-dot.green {
            background: #4CAF50;
            box-shadow: 0 0 6px rgba(76, 175, 80, 0.5);
        }
        .online-dot.gray {
            background: #b0b0b0;
        }
    </style>
@endsection

@section('mohtava')
<div class="activities-container">
    <div class="activities-header">
        <div class="info-badge course-badge">
            <span class="badge-icon">
                <i class="fas fa-book-open"></i>
            </span>
            <span class="badge-label">پایش دانشجویان در درس:</span>
            <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
        </div>
        <div class="activity-controls">
            <div class="filter-group">
                <label for="dayFilter">بازه زمانی:</label>
                <select class="filter-select" id="dayFilter">
                    <option value="1">یک روز</option>
                    <option value="2">دو روز</option>
                    <option value="3" selected>سه روز</option>
                    <option value="5">پنج روز</option>
                    <option value="7">یک هفته</option>
                    <option value="14">دو هفته</option>
                    <option value="30">یک ماه</option>
                </select>
            </div>
            <button class="show-activity-btn" id="showActivityBtn">
                <i class="fas fa-eye"></i>
                نمایش فعالیت
            </button>
            @include('layout.backbtn')
        </div>
    </div>

    <div class="search-wrapper">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="جستجوی نام دانشجو...">
            <button class="search-clear" id="clearSearch">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="search-result" id="searchResult"></div>
    </div>

    <div class="table-wrapper">
        <table class="activities-table" id="activitiesTable">
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align: middle; min-width: 120px;">نام</th>
                    <th colspan="5" class="blue-header">فعالیت‌های انجام شده</th>
                    <th colspan="5" class="red-header">فعالیت‌های بازه انتخابی</th>
                </tr>
                <tr>
                    <th>گزارش</th>
                    <th>سوال</th>
                    <th>تکلیف</th>
                    <th>داوری</th>
                    <th>خودآزمایی</th>
                    <th>گزارش</th>
                    <th>سوال</th>
                    <th>تکلیف</th>
                    <th>داوری</th>
                    <th>خودآزمایی</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($users ?? [] as $user)
                    <tr data-user-id="{{ $user->id }}">
                        <td>
                            <div class="student-name">
                                <a href="{{ route('studentEvaluation', ['courseId' => $course->id, 'userId' => $user->id]) }}">
                                    {{ $user->name ?? '' }} {{ $user->family ?? '' }}
                                </a>
                                        <span class="online-dot {{ $user->isOnline() ? 'green' : 'gray' }}"
                                            title="{{ $user->isOnline() ? 'آنلاین' : 'آفلاین' }}"></span>
                                <button class="message-btn" 
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name ?? '' }} {{ $user->family ?? '' }}"
                                        title="ارسال پیام به دانشجو">
                                    <i class="far fa-envelope"></i>
                                    @if(isset($user->unread_messages_count) && $user->unread_messages_count > 0)
                                        <span class="unread-badge">{{ $user->unread_messages_count }}</span>
                                    @endif
                                </button>
                            </div>
                        </td>
                        <td class="old">{{ $user['disc'] ?? 0 }}</td>
                        <td class="old">{{ $user['questions'] ?? 0 }}</td>
                        <td class="old">{{ $user['exer'] ?? 0 }}</td>
                        <td class="old">{{ $user['davari'] ?? 0 }}</td>
                        <td class="old">{{ $user['khod_total'] ?? 0 }}</td>
                        <td class="new disc-new">{{ $user['disc_new'] ?? 0 }}</td>
                        <td class="new question-new">{{ $user['questions_new'] ?? 0 }}</td>
                        <td class="new exer-new">{{ $user['exer_new'] ?? 0 }}</td>
                        <td class="new davari-new">{{ $user['davari_new'] ?? 0 }}</td>
                        <td class="new khod-new">{{ $user['khod_new'] ?? 0 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="20" style="text-align:center;padding:40px;color:#6b7a8f;">
                            <i class="fas fa-users" style="font-size:32px;display:block;margin-bottom:12px;color:#d0d7e2;"></i>
                            هیچ دانشجویی در این درس ثبت‌نام نکرده است
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ===== مودال ارسال پیام ===== -->
<div class="message-modal" id="messageModal">
    <div class="message-modal-content">
        <div class="message-modal-header">
            <h3>
                <i class="fas fa-paper-plane"></i>
                ارسال پیام به <span id="receiverName"></span>
            </h3>
            <button class="message-modal-close" id="closeMessageModal">&times;</button>
        </div>
        <div class="message-modal-body">
            <textarea id="messageText" placeholder="متن پیام خود را وارد کنید..." maxlength="5000"></textarea>
            <div class="char-counter">
                <span id="charCount">0</span> / 5000
            </div>
            <div class="message-modal-actions">
                <button class="btn btn-cancel" id="cancelMessageBtn">
                    <i class="fas fa-times"></i>
                    انصراف
                </button>
                <button class="btn btn-primary" id="sendMessageBtn">
                    <i class="fas fa-paper-plane"></i>
                    ارسال پیام
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // ============================================
    // متغیرهای عمومی
    // ============================================
    var courseId = '{{ $course->id ?? 0 }}';
    var csrfToken = '{{ csrf_token() }}';
    var ajaxUrl = '{{ route("get.student.activities.range", $course->id) }}';

    // ============================================
    // جستجو
    // ============================================
    var searchInput = document.getElementById('searchInput');
    var clearSearch = document.getElementById('clearSearch');
    var searchResult = document.getElementById('searchResult');
    var tableBody = document.getElementById('tableBody');
    var rows = tableBody ? tableBody.querySelectorAll('tr') : [];

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var searchValue = this.value.toLowerCase().trim();
            var foundCount = 0;

            rows.forEach(function(row) {
                var nameCell = row.querySelector('td:first-child');
                if (nameCell) {
                    var nameText = nameCell.textContent.toLowerCase();
                    if (nameText.includes(searchValue)) {
                        row.style.display = '';
                        foundCount++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            if (searchValue.length > 0) {
                clearSearch.style.display = 'flex';
                searchResult.textContent = foundCount + ' نتیجه یافت شد';
                searchResult.style.display = 'block';
            } else {
                clearSearch.style.display = 'none';
                searchResult.style.display = 'none';
            }
        });
    }

    if (clearSearch) {
        clearSearch.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        });
    }

    // ============================================
    // بارگذاری داده‌های جدید با AJAX
    // ============================================
    function updateActivities(days) {
        var loadingText = document.getElementById('loadingText');
        if (loadingText) loadingText.style.display = 'inline';

        fetch(ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                course_id: courseId,
                days: days
            })
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(function(data) {
            if (loadingText) loadingText.style.display = 'none';

            if (data.success && data.users) {
                data.users.forEach(function(user) {
                    var row = document.querySelector('tr[data-user-id="' + user.id + '"]');
                    if (row) {
                        var discNew = row.querySelector('.disc-new');
                        var questionNew = row.querySelector('.question-new');
                        var exerNew = row.querySelector('.exer-new');
                        var davariNew = row.querySelector('.davari-new');
                        var khodNew = row.querySelector('.khod-new');

                        if (discNew) discNew.textContent = user.disc_new || 0;
                        if (questionNew) questionNew.textContent = user.questions_new || 0;
                        if (exerNew) exerNew.textContent = user.exer_new || 0;
                        if (davariNew) davariNew.textContent = user.davari_new || 0;
                        if (khodNew) khodNew.textContent = user.khod_new || 0;
                    }
                });
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            if (loadingText) loadingText.style.display = 'none';
            showToast('خطا در دریافت اطلاعات', 'error');
        });
    }

    // ============================================
    // دکمه نمایش فعالیت
    // ============================================
    var showBtn = document.getElementById('showActivityBtn');
    if (showBtn) {
        showBtn.addEventListener('click', function() {
            var days = document.getElementById('dayFilter').value;
            updateActivities(days);
            showToast('فعالیت‌ها بروزرسانی شدند', 'success');
        });
    }

    // ============================================
    // بارگذاری خودکار هنگام لود صفحه
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        var defaultDays = document.getElementById('dayFilter')?.value || 3;
        updateActivities(defaultDays);
    });

    // ============================================
    // TOAST NOTIFICATION
    // ============================================
    function showToast(message, type) {
        var existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }

        var toast = document.createElement('div');
        toast.className = 'toast-notification';

        var colors = {
            success: '#4CAF50',
            error: '#f44336',
            info: '#2196F3',
            warning: '#FF9800'
        };

        toast.style.cssText = `
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: ${colors[type] || colors.info};
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            z-index: 100000;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            animation: slideUp 0.4s ease;
            direction: rtl;
            max-width: 90%;
            text-align: center;
        `;

        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.4s';
            setTimeout(function() {
                toast.remove();
            }, 400);
        }, 3500);
    }

    // ============================================
    // ارسال پیام به دانشجو
    // ============================================
    var messageModal = document.getElementById('messageModal');
    var receiverNameSpan = document.getElementById('receiverName');
    var messageText = document.getElementById('messageText');
    var charCount = document.getElementById('charCount');
    var sendMessageBtn = document.getElementById('sendMessageBtn');
    var closeMessageModal = document.getElementById('closeMessageModal');
    var cancelMessageBtn = document.getElementById('cancelMessageBtn');
    var currentReceiverId = null;

    // شمارش کاراکترها
    if (messageText) {
        messageText.addEventListener('input', function() {
            var length = this.value.length;
            charCount.textContent = length;
            var counter = document.querySelector('.char-counter');
            if (length > 4800) {
                counter.classList.add('limit');
            } else {
                counter.classList.remove('limit');
            }
        });
    }

    // کلیک روی آیکون پاکت نامه
    document.querySelectorAll('.message-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            currentReceiverId = this.dataset.userId;
            var userName = this.dataset.userName;
            receiverNameSpan.textContent = userName;
            messageText.value = '';
            charCount.textContent = '0';
            document.querySelector('.char-counter').classList.remove('limit');
            messageModal.classList.add('active');
            setTimeout(function() {
                messageText.focus();
            }, 300);
        });
    });

    // بستن مودال
    function closeModal() {
        messageModal.classList.remove('active');
        currentReceiverId = null;
    }

    closeMessageModal.addEventListener('click', closeModal);
    cancelMessageBtn.addEventListener('click', closeModal);

    // کلیک خارج از مودال
    messageModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // دکمه Esc برای بستن
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && messageModal.classList.contains('active')) {
            closeModal();
        }
    });

    // ارسال پیام
    sendMessageBtn.addEventListener('click', function() {
        var text = messageText.value.trim();
        
        if (!text) {
            showToast('لطفاً متن پیام را وارد کنید', 'warning');
            messageText.focus();
            return;
        }
        
        if (!currentReceiverId) {
            showToast('خطا در شناسایی دانشجو', 'error');
            return;
        }
        
        // غیرفعال کردن دکمه
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ارسال...';
        
        fetch('{{ route("teacher.student-messages.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                receiver_id: currentReceiverId,
                text: text,
                course_id: courseId
            })
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                showToast('پیام با موفقیت ارسال شد', 'success');
                closeModal();
            } else {
                showToast(data.message || 'خطا در ارسال پیام', 'error');
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            showToast('خطا در ارتباط با سرور', 'error');
        })
        .finally(function() {
            sendMessageBtn.disabled = false;
            sendMessageBtn.innerHTML = '<i class="fas fa-paper-plane"></i> ارسال پیام';
        });
    });
</script>
@endsection