@extends('layout.master')

@section('title')
    ملیسان | نمرات دانشجویان
@endsection

@section('head')
    <link rel="stylesheet" href="{{asset('css/style-grades-list.css')}}">
    <link rel="stylesheet" href="{{asset('css/badge.css')}}">
    <link rel="stylesheet" href="{{asset('css/style-course.css')}}">
    <style>
        .name-cell {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
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

        .message-btn {
            background: none;
            border: none;
            color: #4a90d9;
            font-size: 16px;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 6px;
            transition: all 0.2s ease;
            position: relative;
        }

        .message-btn:hover {
            background: rgba(74, 144, 217, 0.1);
            color: #2a6fb0;
            transform: scale(1.1);
        }

        .message-btn:active {
            transform: scale(0.9);
        }

        .message-btn .unread-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #e74c3c;
            color: white;
            font-size: 9px;
            font-weight: bold;
            min-width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            box-shadow: 0 2px 6px rgba(231, 76, 60, 0.4);
        }

        /* ===== مودال ارسال پیام ===== */
        .message-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .message-modal.active {
            display: flex;
        }

        .message-modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.3s ease;
        }

        .message-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border-bottom: 1px solid #eee;
            background: #f8f9fa;
        }

        .message-modal-header h3 {
            margin: 0;
            font-size: 18px;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .message-modal-header h3 i {
            color: #4a90d9;
        }

        .message-modal-header h3 span {
            color: #4a90d9;
        }

        .message-modal-close {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #999;
            transition: color 0.2s;
            line-height: 1;
            padding: 0 4px;
        }

        .message-modal-close:hover {
            color: #e74c3c;
        }

        .message-modal-body {
            padding: 24px;
        }

        .message-modal-body textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e8edf3;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            transition: border-color 0.2s;
            direction: rtl;
            min-height: 120px;
            max-height: 300px;
            background: #fafbfc;
        }

        .message-modal-body textarea:focus {
            outline: none;
            border-color: #4a90d9;
            box-shadow: 0 0 0 4px rgba(74, 144, 217, 0.1);
            background: white;
        }

        .message-modal-body textarea::placeholder {
            color: #a0b3c9;
        }

        .message-modal-actions {
            margin-top: 16px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .message-modal-actions .btn {
            padding: 10px 28px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .message-modal-actions .btn-cancel {
            background: #f1f3f5;
            color: #6b7a8f;
        }

        .message-modal-actions .btn-cancel:hover {
            background: #e8edf3;
        }

        .message-modal-actions .btn-primary {
            background: #4a90d9;
            color: white;
        }

        .message-modal-actions .btn-primary:hover {
            background: #3a7bc8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 144, 217, 0.3);
        }

        .message-modal-actions .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .char-counter {
            text-align: left;
            font-size: 12px;
            color: #a0b3c9;
            margin-top: 6px;
        }

        .char-counter.limit {
            color: #e74c3c;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 500px) {
            .message-modal-content {
                width: 95%;
                border-radius: 12px;
            }
            .message-modal-header h3 {
                font-size: 16px;
            }
            .message-modal-body {
                padding: 16px;
            }
            .message-modal-actions .btn {
                padding: 8px 20px;
                font-size: 13px;
            }
        }
    </style>
@endsection

@section('mohtava')
    <div class="grades-container">
        <div class="grades-header">
            <div class="info-badge course-badge">
                <span class="badge-icon">
                    <i class="fas fa-book-open"></i>
                </span>
                <span class="badge-label">نمرات دانشجویان در درس:</span>
                <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
            </div>
            <div>
                @include('layout.backbtn')
                <a href="{{ route('courses.setting', $course->id) }}?open_section=barmbandi" class="action-btn settings-btn">
                    <i class="fas fa-cog"></i>
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <form action="{{ route('grades.save', $course->id) }}" method="POST">
                @csrf
                <table class="grades-table" id="gradesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام و نام خانوادگی</th>
                            <th>نمره ارزشیابی (از {{ $setting->mostamar_nomre ?? 20 }})</th>
                            @foreach($scoreSections as $key => $section)
                                <th>{{ $section['title'] }} (از {{ $setting->$key ?? 20 }})</th>
                            @endforeach
                            <th>تشویقی/ارفاق (خارج از ۲۰)</th>
                            <th>نمره نهایی (از ۲۰)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users ?? [] as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="name-cell">
                                        <span class="online-dot {{ $user->isOnline() ? 'green' : 'gray' }}"
                                            title="{{ $user->isOnline() ? 'آنلاین' : 'آفلاین' }}"></span>
                                        {{ $user->name ?? '' }} {{ $user->family ?? '' }}
                                        <button type="button" class="message-btn" 
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
                                {{-- ستون ارزشیابی (ورودی با max = بارم واقعی) --}}
                                <td>
                                    <input type="number"
                                           name="mostamar[{{ $user->id }}]"
                                           class="grade-input section-grade"
                                           data-user-id="{{ $user->id }}"
                                           step="0.01"
                                           min="0"
                                           max="{{ $setting->mostamar_nomre ?? 20 }}"
                                           placeholder="—"
                                           value="{{ $user->amali_scores[8] ?? $user->mostamar_nomre ?? '' }}">
                                </td>
                                @foreach($scoreSections as $key => $section)
                                    <td>
                                        @if($key == 'payan_term_nomre')
                                            <input type="number" name="final[{{ $user->id }}]" class="grade-input section-grade"
                                                data-user-id="{{ $user->id }}" step="0.01" min="0" max="{{ $setting->$key ?? 20 }}"
                                                placeholder="—" value="{{ $user->final ?? '' }}">
                                        @else
                                            <input type="number" name="scores[{{ $user->id }}][{{ $section['type'] }}]"
                                                class="grade-input section-grade" data-user-id="{{ $user->id }}" step="0.01" min="0"
                                                max="{{ $setting->$key ?? 20 }}" placeholder="—"
                                                value="{{ $user->amali_scores[$section['type']] ?? '' }}">
                                        @endif
                                    </td>
                                @endforeach
                                {{-- ستون تشویقی/ارفاق --}}
                                <td>
                                    <input type="number" name="extra[{{ $user->id }}]" class="grade-input section-grade"
                                        data-user-id="{{ $user->id }}" step="0.01" min="0" placeholder="—"
                                        value="{{ $user->amali_scores[7] ?? '' }}">
                                </td>
                                {{-- ستون نمره نهایی --}}
                                <td>
                                    <span class="final-grade" data-user-id="{{ $user->id }}">0.00</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 5 + count($scoreSections) }}"
                                    style="text-align:center;padding:40px;color:#6b7a8f;">
                                    <i class="fas fa-users"
                                        style="font-size:32px;display:block;margin-bottom:12px;color:#d0d7e2;"></i>
                                    هیچ دانشجویی در این درس ثبت‌نام نکرده است
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if(count($scoreSections) > 0 && $users->count() > 0)
                    <div class="action-bar">
                        <button type="submit" class="save-grades-btn">
                            <i class="fas fa-save"></i>
                            ثبت نمرات
                        </button>
                    </div>
                @endif
            </form>
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
                    <button type="button" class="btn btn-cancel" id="cancelMessageBtn">
                        <i class="fas fa-times"></i>
                        انصراف
                    </button>
                    <button type="button" class="btn btn-primary" id="sendMessageBtn">
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
        document.addEventListener('DOMContentLoaded', function () {
            // ============================================
            // DataTable
            // ============================================
            const table = document.getElementById('gradesTable');
            if (table && typeof $ !== 'undefined' && $.fn.DataTable) {
                $('#gradesTable').DataTable({
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "همه"]],
                    language: {
                        search: "جستجو:",
                        lengthMenu: "نمایش _MENU_ رکورد در هر صفحه",
                        info: "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
                        infoEmpty: "هیچ رکوردی یافت نشد",
                        zeroRecords: "موردی یافت نشد",
                        paginate: {
                            first: "ابتدا",
                            last: "انتها",
                            next: "بعدی",
                            previous: "قبلی"
                        }
                    },
                    order: [[0, "asc"]]
                });
            }

            // ============================================
            // محاسبه نمره نهایی (جمع تمام ورودی‌های section-grade)
            // ============================================
            function updateFinalGrade(userId) {
                const inputs = document.querySelectorAll(`.section-grade[data-user-id="${userId}"]`);
                let total = 0;
                inputs.forEach(input => {
                    let val = parseFloat(input.value);
                    if (!isNaN(val) && val >= 0) {
                        total += val;
                    }
                });
                const finalSpan = document.querySelector(`.final-grade[data-user-id="${userId}"]`);
                if (finalSpan) {
                    finalSpan.textContent = total.toFixed(2);
                }
            }

            // اتصال رویداد به همه ورودی‌ها و محاسبه اولیه
            document.querySelectorAll('.section-grade').forEach(input => {
                input.addEventListener('input', function () {
                    const userId = this.dataset.userId;
                    updateFinalGrade(userId);
                });
                const userId = input.dataset.userId;
                updateFinalGrade(userId);
            });

            // ============================================
            // اعتبارسنجی ورودی‌ها (حداقل 0 و حداکثر max)
            // ============================================
            document.querySelectorAll('.grade-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    var val = parseFloat(this.value);
                    var maxVal = parseFloat(this.getAttribute('max'));
                    if (maxVal !== null && !isNaN(maxVal)) {
                        if (val < 0) {
                            this.value = 0;
                            showToast('نمره نمی‌تواند منفی باشد', 'warning');
                        } else if (val > maxVal) {
                            this.value = maxVal;
                            showToast('نمره نمی‌تواند بیشتر از ' + maxVal + ' باشد', 'warning');
                        }
                    } else {
                        if (val < 0) {
                            this.value = 0;
                            showToast('نمره نمی‌تواند منفی باشد', 'warning');
                        }
                    }
                    const userId = this.dataset.userId;
                    if (userId) updateFinalGrade(userId);
                });

                input.addEventListener('blur', function () {
                    if (this.value === '' || this.value === null || this.value === '-') {
                        return;
                    }
                    var val = parseFloat(this.value);
                    if (isNaN(val)) {
                        this.value = '';
                    }
                });
            });

            // ============================================
            // ارسال پیام به دانشجو
            // ============================================
            var courseId = '{{ $course->id ?? 0 }}';
            var csrfToken = '{{ csrf_token() }}';
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
                    e.preventDefault();   // جلوگیری از سابمیت فرم
                    e.stopPropagation();  // جلوگیری از bubbling
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
        });

        // ============================================
        // Toast Notifications
        // ============================================
        function showToast(message, type = 'info') {
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

            setTimeout(function () {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.4s';
                setTimeout(function () {
                    toast.remove();
                }, 400);
            }, 3500);
        }
    </script>
@endsection