@extends('layout.master')

@section('title')
ملیسان | تصحیح تکالیف
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-course.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection

@section('mohtava')
<div class="course-detail-container">
    <div class="course-header">
        <h4 class="course-title-main">{{ $course->name ?? 'عنوان درس' }}</h4>
    </div>

    <div class="course-actions-bar">
        <a href="{{ route('view.coure', $course->id) }}" class="action-btn back-btn">
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="correction-container">
        <div class="correction-header">
            <h3>
                <i class="fas fa-check-double"></i>
                تصحیح تکالیف
            </h3>
            <span class="badge-count">
                <i class="fas fa-file-alt"></i>
                {{ $sessionsWithExercises->count() }} جلسه با تکلیف
            </span>
        </div>

        @if($sessionsWithExercises->isNotEmpty())
            <div class="correction-table-wrapper">
                <table class="correction-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>عنوان جلسه</th>
                            <th style="min-width: 200px;">تکالیف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessionsWithExercises as $index => $session)
                            <tr>
                                <td>
                                    <span class="row-number">{{ $index + 1 }}</span>
                                </td>
                                <td class="session-title-cell">
                                    {{ $session->name }}
                                    <span class="session-sub">جلسه {{ $session->number }}</span>
                                </td>
                                <td>
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        @foreach($session->exercises as $exercise)
                                            @php
                                                $hasAnswers = ($exercise->exercise_answers_count ?? 0) > 0;
                                            @endphp
                                            <button class="btn-answers {{ $hasAnswers ? 'has-answers' : 'no-answers' }}" 
                                                    onclick="showExerciseAnswers({{ $exercise->id }})">
                                                <i class="fas fa-file-alt"></i>
                                                تکلیف {{ $loop->iteration }}
                                                <span class="badge-count">{{ $exercise->exercise_answers_count ?? 0 }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-tasks"></i>
                <p>هیچ تکلیفی برای این درس تعریف نشده است</p>
                <a href="{{ route('sessions.create', $course->id) }}" class="btn-primary-custom">
                    <i class="fas fa-plus"></i>
                    ایجاد جلسه و تکلیف
                </a>
            </div>
        @endif
    </div>
</div>

<!-- ==========================================
     مودال نمایش پاسخ‌ها
     ========================================== -->
<div class="modal-overlay" id="answersModal">
    <div class="modal-container">
        <div class="modal-header">
            <h4 id="modalAnswersTitle">
                <i class="fas fa-list"></i>
                پاسخ‌های دانشجویان
                <span class="sub-title" id="modalAnswersSubtitle"></span>
            </h4>
            <button class="modal-close" onclick="closeAnswersModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="answersModalBody">
            <div style="text-align: center; padding: 30px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 28px; color: #1e6f9f;"></i>
                <p style="margin-top: 12px; color: #6c757d;">در حال بارگذاری پاسخ‌ها...</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel-modal" onclick="closeAnswersModal()">
                <i class="fas fa-times"></i>
                بستن
            </button>
        </div>
    </div>
</div>

<script>
    // ============================================
    // نمایش پاسخ‌های تکلیف
    // ============================================
    
    function showExerciseAnswers(exerciseId) {
        const modal = document.getElementById('answersModal');
        const body = document.getElementById('answersModalBody');
        const title = document.getElementById('modalAnswersTitle');
        const subtitle = document.getElementById('modalAnswersSubtitle');
        
        // نمایش مودال
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // تنظیم عنوان
        title.innerHTML = `
            <i class="fas fa-list"></i>
            پاسخ‌های دانشجویان
            <span class="sub-title" id="modalAnswersSubtitle"></span>
        `;
        subtitle.textContent = 'در حال بارگذاری...';
        
        // نمایش لودینگ
        body.innerHTML = `
            <div style="text-align: center; padding: 30px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 28px; color: #1e6f9f;"></i>
                <p style="margin-top: 12px; color: #6c757d;">در حال بارگذاری پاسخ‌ها...</p>
            </div>
        `;
        
        // ارسال درخواست
        fetch(`/teacher/courses/exercises/answers/${exerciseId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    body.innerHTML = `
                        <div class="modal-empty">
                            <i class="fas fa-exclamation-triangle" style="color: #ff9800;"></i>
                            <p>${data.message || 'خطا در بارگذاری پاسخ‌ها'}</p>
                        </div>
                    `;
                    subtitle.textContent = 'خطا';
                    return;
                }
                
                // به‌روزرسانی عنوان
                subtitle.textContent = `(${data.total} پاسخ)`;
                
                if (data.answers.length === 0) {
                    body.innerHTML = `
                        <div class="modal-empty">
                            <i class="fas fa-inbox"></i>
                            <p>هنوز هیچ دانشجویی این تکلیف را ارسال نکرده است</p>
                        </div>
                    `;
                    return;
                }
                
                let html = '';
                data.answers.forEach((answer) => {
                    const statusClass = answer.status ? `status-${answer.status}` : 'status-null';
                    const statusText = answer.status ? 
                        ['', 'عالی', 'خوب', 'متوسط', 'بد'][answer.status] || 'نامشخص' : 
                        'در انتظار بررسی';
                    
                    const studentName = answer.user ? 
                        (answer.user.name || '') + ' ' + (answer.user.family || '') : 
                        'کاربر ناشناس';
                    
                    html += `
                        <div class="answer-item">
                            <div class="student-name">
                                <i class="fas fa-user-graduate"></i>
                                ${studentName}
                            </div>
                            <div class="answer-text">
                                ${answer.answer ? nl2br(answer.answer) : '<span class="empty-answer">(پاسخ متنی ارسال نشده)</span>'}
                            </div>
                            ${answer.file ? `
                                <div class="answer-file">
                                    <a href="/files/exercises/${answer.file}" target="_blank">
                                        <i class="fas fa-paperclip"></i>
                                        مشاهده فایل پیوست
                                    </a>
                                </div>
                            ` : ''}
                            <div class="answer-meta">
                                <span><i class="far fa-calendar-alt"></i> ${new Date(answer.created_at).toLocaleDateString('fa-IR')}</span>
                                <span><i class="far fa-clock"></i> ${new Date(answer.created_at).toLocaleTimeString('fa-IR')}</span>
                            </div>
                            <div>
                                <span class="answer-status ${statusClass}">
                                    <i class="fas ${answer.status ? 'fa-check-circle' : 'fa-clock'}"></i>
                                    ${statusText}
                                </span>
                            </div>
                        </div>
                    `;
                });
                
                body.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                body.innerHTML = `
                    <div class="modal-empty">
                        <i class="fas fa-exclamation-triangle" style="color: #f44336;"></i>
                        <p>خطا در ارتباط با سرور</p>
                    </div>
                `;
                subtitle.textContent = 'خطا';
            });
    }
    
    function closeAnswersModal() {
        const modal = document.getElementById('answersModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
    
    // بستن مودال با کلیک روی پس‌زمینه
    document.getElementById('answersModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAnswersModal();
        }
    });
    
    // بستن مودال با دکمه Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('answersModal');
            if (modal && modal.classList.contains('active')) {
                closeAnswersModal();
            }
        }
    });
    
    // تابع کمکی برای تبدیل خط جدید به <br>
    function nl2br(str) {
        return str.replace(/\n/g, '<br>');
    }
</script>

<style>
    .mb-1 { margin-bottom: 4px; }
</style>
@endsection