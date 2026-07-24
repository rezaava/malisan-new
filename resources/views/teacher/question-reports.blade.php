@extends('layout.master')

@section('title')
ملیسان | گزارش‌های ایراد سوال
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/question-reports.css') }}">
<link rel="stylesheet" href="{{ asset('css/badge.css') }}">
@endsection

@section('mohtava')
<div class="reports-container">
    {{-- HEADER --}}
    <div class="reports-header">
        <div class="info-badge course-badge">
            <span class="badge-icon">
                <i class="fas fa-book-open"></i>
            </span>
            <span class="badge-label">گزارش‌های ایراد سوال در درس:</span>
            <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
        </div>
        @include('layout.backbtn')
    </div>

    {{-- STATS --}}
    <div class="stats-row">
        <div class="stat-box">
            <div class="number">{{ $stats['total'] ?? 0 }}</div>
            <div class="label">کل گزارش‌ها</div>
        </div>
        <div class="stat-box pending">
            <div class="number">{{ $stats['pending'] ?? 0 }}</div>
            <div class="label">در انتظار بررسی</div>
        </div>
        <div class="stat-box reviewed">
            <div class="number">{{ $stats['reviewed'] ?? 0 }}</div>
            <div class="label">بررسی شده</div>
        </div>
        <div class="stat-box resolved">
            <div class="number">{{ $stats['resolved'] ?? 0 }}</div>
            <div class="label">رفع شده</div>
        </div>
        <div class="stat-box rejected">
            <div class="number">{{ $stats['rejected'] ?? 0 }}</div>
            <div class="label">رد شده</div>
        </div>
    </div>

    {{-- REPORTS --}}
    @if($reports->count() > 0)
        @foreach($reports as $report)
            @php
                $statusLabels = [
                    'pending' => 'در انتظار بررسی',
                    'reviewed' => 'بررسی شده',
                    'resolved' => 'رفع شده',
                    'rejected' => 'رد شده',
                ];
                $statusClasses = [
                    'pending' => 'pending',
                    'reviewed' => 'reviewed',
                    'resolved' => 'resolved',
                    'rejected' => 'rejected',
                ];
                $designerName = $report->question->user->name ?? 'نامشخص';
                $designerFamily = $report->question->user->family ?? '';
                
                $scoreLabels = [
                    1 => 'عالی',
                    2 => 'خوب',
                    3 => 'متوسط',
                ];
                $scoreClasses = [
                    1 => 'excellent',
                    2 => 'good',
                    3 => 'medium',
                ];
            @endphp
            <div class="report-card {{ $report->status }}">
                <div class="card-header">
                    <div class="user-info">
                        <div class="avatar">{{ substr($report->user->name ?? '?', 0, 1) }}</div>
                        <div>
                            <div class="name">
                                {{ $report->user->name ?? 'نامشخص' }} {{ $report->user->family ?? '' }}
                                <span class="reporter-label">
                                    (گزارش‌دهنده)
                                </span>
                            </div>
                            <div class="date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ \Hekmatinasser\Verta\Verta::instance($report->created_at)->format('Y/m/d H:i') }}
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        @if($report->average_score)
                            <span class="average-score-badge">
                                <i class="fas fa-star"></i>
                                میانگین: {{ $report->average_score }}
                            </span>
                        @endif
                        <span class="status-badge {{ $report->status }}">
                            <i class="fas {{ $report->status == 'pending' ? 'fa-clock' : ($report->status == 'reviewed' ? 'fa-eye' : ($report->status == 'resolved' ? 'fa-check-circle' : 'fa-times-circle')) }}"></i>
                            {{ $statusLabels[$report->status] ?? 'نامشخص' }}
                        </span>
                        <button class="btn-edit-question" onclick="openEditModal({{ $report->question->id }})">
                            <i class="fas fa-edit"></i> ویرایش سوال
                        </button>
                    </div>
                </div>

                {{-- سوال با گزینه‌ها --}}
                <div class="question-box">
                    <div class="q-label">
                        <i class="fas fa-question-circle" style="color:#1e6f9f;"></i>
                        سوال (طراح: {{ $designerName }} {{ $designerFamily }})
                    </div>
                    <div class="q-text has-margin">{{ $report->question->question ?? 'سوال حذف شده است' }}</div>
                    
                    {{-- گزینه‌ها --}}
                    <div class="options-grid-inline">
                        @php
                            $options = [
                                1 => ['label' => 'الف', 'value' => $report->question->answer1],
                                2 => ['label' => 'ب', 'value' => $report->question->answer2],
                                3 => ['label' => 'ج', 'value' => $report->question->answer3],
                                4 => ['label' => 'د', 'value' => $report->question->answer4],
                            ];
                        @endphp
                        @foreach($options as $key => $option)
                            <div class="option-item-inline {{ $key == $report->question->answer ? 'correct' : '' }}">
                                <span class="opt-label">{{ $option['label'] }}</span>
                                <span class="opt-text">{{ $option['value'] }}</span>
                                @if($key == $report->question->answer)
                                    <span class="opt-icon"><i class="fas fa-check-circle"></i></span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- توضیح ایراد --}}
                <div class="description-box">
                    <div class="d-label">
                        <i class="fas fa-exclamation-triangle"></i>
                        توضیح ایراد:
                    </div>
                    <div class="d-text">{{ $report->description }}</div>
                </div>

                {{-- پاسخ مدیر (اگر وجود داشته باشد) --}}
                @if($report->admin_response)
                    <div class="response-box">
                        <div class="r-label">
                            <i class="fas fa-reply"></i>
                            پاسخ مدیر:
                        </div>
                        <div class="r-text">{{ $report->admin_response }}</div>
                    </div>
                @endif

                {{-- وضعیت داوری سوال --}}
                <div class="question-status">
                    <i class="fas fa-star" style="color:#ffd700;"></i>
                    وضعیت سوال: 
                    <span class="status-value">
                        @if($report->question->status === null)
                            در انتظار داوری
                        @elseif($report->question->status == 1)
                            عالی
                        @elseif($report->question->status == 2)
                            خوب
                        @elseif($report->question->status == 3)
                            متوسط
                        @elseif($report->question->status == 4)
                            بد
                        @else
                            نامشخص
                        @endif
                    </span>
                </div>

                {{-- داوران و نمرات --}}
                <div class="scores-section">
                    <div class="scores-title">
                        <i class="fas fa-users" style="color:#9c27b0;"></i>
                        داوران و نمرات
                        @if($report->scores->count() > 0)
                            ({{ $report->scores->count() }} داوری)
                        @endif
                    </div>

                    @if($report->scores->count() > 0)
                        @foreach($report->scores as $score)
                            <div class="score-item">
                                <span class="judge-name">
                                    <i class="fas fa-user" style="color:#1e6f9f;"></i>
                                    {{ $score->user->name ?? 'نامشخص' }} {{ $score->user->family ?? '' }}
                                </span>

                                @if($score->status === 'approved')
                                    <span class="score-value {{ $scoreClasses[$score->score] ?? '' }}">
                                        {{ $scoreLabels[$score->score] ?? 'نامشخص' }}
                                    </span>
                                @endif

                                @if($score->negaresh == 1 || $score->gozine == 1 || $score->dark == 1)
                                    <span class="score-issues">
                                        @if($score->negaresh == 1) ❌ نگارشی @endif
                                        @if($score->gozine == 1) ❌ گزینه‌ها @endif
                                        @if($score->dark == 1) ❌ گویایی @endif
                                    </span>
                                @endif

                                <span class="score-status {{ $score->status }}">
                                    @if($score->status === 'approved')
                                        <i class="fas fa-check-circle"></i> تایید
                                    @elseif($score->status === 'rejected')
                                        <i class="fas fa-times-circle"></i> رد
                                    @elseif($score->status === 'returned')
                                        <i class="fas fa-undo"></i> برگشت
                                    @else
                                        <i class="fas fa-clock"></i> در انتظار
                                    @endif
                                </span>

                                @if($score->comment)
                                    <span class="score-comment">
                                        <i class="fas fa-comment" style="color:#6b7a8f;"></i>
                                        {{ $score->comment }}
                                    </span>
                                @endif

                                <span class="score-date">
                                    {{ \Hekmatinasser\Verta\Verta::instance($score->created_at)->format('Y/m/d H:i') }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="no-scores">
                            <i class="fas fa-info-circle"></i>
                            هنوز داوری‌ای برای این سوال ثبت نشده است.
                        </div>
                    @endif
                </div>

                {{-- فرم تغییر وضعیت گزارش --}}
                <form class="action-form" data-report-id="{{ $report->id }}" onsubmit="updateReportStatus(event, {{ $report->id }})">
                    @csrf
                    @method('PUT')
                    <select name="status">
                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>در انتظار بررسی</option>
                        <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>بررسی شده</option>
                        <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>رفع شده</option>
                        <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>رد شده</option>
                    </select>
                    <input type="text" name="admin_response" placeholder="پاسخ مدیر (اختیاری)" value="{{ $report->admin_response ?? '' }}">
                    <button type="submit" class="btn-action btn-action-success">
                        <i class="fas fa-save"></i>
                        بروزرسانی
                    </button>
                </form>

                @if($report->reviewed_by)
                    <div class="card-footer">
                        <div class="reviewer-info">
                            <i class="fas fa-user-check" style="color:#1e6f9f;"></i>
                            بررسی شده توسط: 
                            <span class="reviewer-name">
                                {{ $report->reviewer->name ?? 'نامشخص' }} {{ $report->reviewer->family ?? '' }}
                            </span>
                            @if($report->reviewed_at)
                                <span class="reviewer-date">
                                    ({{ \Hekmatinasser\Verta\Verta::instance($report->reviewed_at)->format('Y/m/d H:i') }})
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <span class="empty-icon"><i class="fas fa-inbox"></i></span>
            <h4>هیچ گزارشی ثبت نشده است</h4>
            <p>هنوز دانشجویی برای سوالات این درس گزارشی ثبت نکرده است.</p>
        </div>
    @endif
</div>

{{-- ===== MODAL EDIT QUESTION ===== --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-container">
        <div class="modal-header">
            <h4><i class="fas fa-edit" style="color:#1e6f9f;"></i> ویرایش سوال</h4>
            <button class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="editModalBody">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">در حال بارگذاری...</p>
            </div>
        </div>
    </div>
</div>

<script>
    // ===== UPDATE REPORT STATUS =====
    function updateReportStatus(event, reportId) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال بروزرسانی...';
        
        fetch(`/teacher/question-report/${reportId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
                showToast('✅ وضعیت گزارش با موفقیت بروزرسانی شد.', 'success');
            } else {
                showToast('❌ خطا در بروزرسانی وضعیت.', 'error');
            }
        })
        .catch(error => {
            showToast('❌ خطا در ارتباط با سرور.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> بروزرسانی';
        });
    }

    // ===== EDIT QUESTION MODAL =====
    let currentQuestionId = null;

    function openEditModal(questionId) {
        currentQuestionId = questionId;
        const modal = document.getElementById('editModal');
        const body = document.getElementById('editModalBody');
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        body.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">در حال بارگذاری...</p>
            </div>
        `;
        
        fetch(`/teacher/questions/show/${questionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const q = data.data;
                    
                    body.innerHTML = `
                        <form id="editQuestionForm" onsubmit="updateQuestion(event, ${q.id})">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label>متن سوال <span class="required">*</span></label>
                                <input type="text" class="form-control" name="question" value="${q.question}" required>
                            </div>
                            
                            <div class="options-grid">
                                <div class="form-group">
                                    <label>گزینه الف <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="answer1" value="${q.answer1}" required>
                                </div>
                                <div class="form-group">
                                    <label>گزینه ب <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="answer2" value="${q.answer2}" required>
                                </div>
                                <div class="form-group">
                                    <label>گزینه ج <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="answer3" value="${q.answer3}" required>
                                </div>
                                <div class="form-group">
                                    <label>گزینه د <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="answer4" value="${q.answer4}" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>گزینه صحیح <span class="required">*</span></label>
                                <select class="form-control" name="correct_answer" required>
                                    <option value="1" ${q.answer == 1 ? 'selected' : ''}>الف</option>
                                    <option value="2" ${q.answer == 2 ? 'selected' : ''}>ب</option>
                                    <option value="3" ${q.answer == 3 ? 'selected' : ''}>ج</option>
                                    <option value="4" ${q.answer == 4 ? 'selected' : ''}>د</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>وضعیت سوال</label>
                                <select class="form-control" name="status">
                                    <option value="">در انتظار داوری</option>
                                    <option value="0" ${q.status === 0 ? 'selected' : ''}>برگشت خورده</option>
                                    <option value="1" ${q.status == 1 ? 'selected' : ''}>عالی</option>
                                    <option value="2" ${q.status == 2 ? 'selected' : ''}>خوب</option>
                                    <option value="3" ${q.status == 3 ? 'selected' : ''}>متوسط</option>
                                    <option value="4" ${q.status == 4 ? 'selected' : ''}>بد</option>
                                </select>
                            </div>
                            
                            <div class="modal-actions">
                                <button type="submit" class="btn-modal btn-modal-primary">
                                    <i class="fas fa-save"></i> ذخیره تغییرات
                                </button>
                                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeEditModal()">
                                    <i class="fas fa-times"></i> انصراف
                                </button>
                            </div>
                        </form>
                    `;
                } else {
                    body.innerHTML = `
                        <div class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                            <p class="mt-2">خطا در دریافت اطلاعات سوال</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                body.innerHTML = `
                    <div class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                        <p class="mt-2">خطا در ارتباط با سرور</p>
                    </div>
                `;
            });
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // ===== UPDATE QUESTION =====
    function updateQuestion(event, questionId) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ذخیره...';
        
        fetch(`/teacher/questions/status/${questionId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeEditModal();
                location.reload();
                showToast('✅ سوال با موفقیت بروزرسانی شد.', 'success');
            } else {
                let errorMsg = 'خطا در بروزرسانی سوال';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('\n');
                }
                showToast('❌ ' + errorMsg, 'error');
            }
        })
        .catch(error => {
            showToast('❌ خطا در ارتباط با سرور.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> ذخیره تغییرات';
        });
    }

    // ===== TOAST =====
    function showToast(message, type = 'success') {
        const oldToast = document.querySelector('.toast-message');
        if (oldToast) oldToast.remove();
        
        const toast = document.createElement('div');
        toast.className = 'toast-message ' + type;
        toast.innerHTML = `
            <span>${message}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                toast.style.transition = 'all 0.4s ease';
                setTimeout(() => toast.remove(), 400);
            }
        }, 5000);
    }

    // ===== CLOSE MODAL ON ESCAPE =====
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
        }
    });

    // ===== CLOSE MODAL ON OVERLAY CLICK =====
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endsection