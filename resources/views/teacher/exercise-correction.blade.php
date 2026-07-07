@extends('layout.master')

@section('title')
ملیسان | تصحیح تکالیف
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-course.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .correction-container {
        padding: 24px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        margin-top: 20px;
    }
    
    .correction-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f4f9;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .correction-header h3 {
        margin: 0;
        color: #1a2332;
        font-weight: 700;
        font-size: 20px;
    }
    
    .correction-header h3 i {
        color: #1e6f9f;
        margin-left: 8px;
    }
    
    .correction-header .badge-count {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .correction-header .badge-count i {
        font-size: 14px;
    }
    
    .correction-table-wrapper {
        overflow-x: auto;
    }
    
    .correction-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 500px;
    }
    
    .correction-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 14px 18px;
        text-align: right;
        border-bottom: 2px solid #e9ecef;
        font-size: 14px;
        white-space: nowrap;
    }
    
    .correction-table tbody td {
        padding: 14px 18px;
        border-bottom: 1px solid #f0f4f9;
        vertical-align: middle;
        font-size: 14px;
    }
    
    .correction-table tbody tr:hover td {
        background: #f8f9fa;
    }
    
    .correction-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .row-number {
        font-weight: 700;
        color: #6c757d;
        font-size: 14px;
        display: inline-block;
        width: 28px;
        height: 28px;
        line-height: 28px;
        text-align: center;
        background: #f0f4f9;
        border-radius: 50%;
    }
    
    .session-title-cell {
        font-weight: 500;
        color: #1a2332;
    }
    
    .session-title-cell .session-sub {
        display: block;
        color: #6c757d;
        font-size: 12px;
        font-weight: 400;
        margin-top: 2px;
    }
    
    .btn-answers {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #1e6f9f;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        margin-bottom: 4px;
    }
    
    .btn-answers:hover {
        background: #155a82;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 111, 159, 0.3);
        color: #fff;
    }
    
    .btn-answers .badge-count {
        background: rgba(255,255,255,0.2);
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .btn-answers.has-answers {
        background: #2e7d32;
    }
    
    .btn-answers.has-answers:hover {
        background: #1b5e20;
    }
    
    .btn-answers.no-answers {
        background: #6c757d;
        opacity: 0.7;
    }
    
    .btn-answers.no-answers:hover {
        background: #495057;
        opacity: 1;
    }
    
    .btn-answers i {
        font-size: 14px;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 56px;
        color: #dee2e6;
        margin-bottom: 16px;
    }
    
    .empty-state p {
        font-size: 16px;
        margin: 0 0 16px 0;
    }
    
    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        background: #1e6f9f;
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover {
        background: #155a82;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 111, 159, 0.3);
    }
    
    /* ==========================================
       مودال پاسخ‌ها - طراحی جدید
       ========================================== */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .modal-container {
        background: #fff;
        border-radius: 16px;
        width: 90%;
        max-width: 720px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease;
    }
    
    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .modal-header {
        padding: 18px 24px;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        border-radius: 16px 16px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }
    
    .modal-header h4 {
        color: #fff;
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .modal-header h4 .sub-title {
        font-size: 14px;
        font-weight: 400;
        opacity: 0.8;
    }
    
    .modal-close {
        background: rgba(255,255,255,0.15);
        border: none;
        color: #fff;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .modal-close:hover {
        background: rgba(255,255,255,0.25);
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
        max-height: 500px;
    }
    
    .modal-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .modal-body::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 3px;
    }
    
    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #a8b0b8;
    }
    
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #f0f4f9;
        display: flex;
        justify-content: flex-end;
        flex-shrink: 0;
    }
    
    .btn-cancel-modal {
        padding: 8px 24px;
        background: #f0f4f9;
        color: #495057;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-cancel-modal:hover {
        background: #e2e8f0;
    }
    
    /* ==========================================
       آیتم پاسخ
       ========================================== */
    .answer-item {
        padding: 16px 18px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 12px;
        border-right: 4px solid #1e6f9f;
        transition: all 0.3s ease;
    }
    
    .answer-item:hover {
        background: #f0f4f9;
        transform: translateX(-4px);
    }
    
    .answer-item .student-name {
        font-weight: 600;
        color: #1a2332;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .answer-item .student-name i {
        color: #1e6f9f;
    }
    
    .answer-item .answer-text {
        margin-top: 8px;
        color: #495057;
        font-size: 14px;
        line-height: 1.7;
        background: #fff;
        padding: 10px 14px;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }
    
    .answer-item .answer-file {
        margin-top: 8px;
    }
    
    .answer-item .answer-file a {
        color: #1e6f9f;
        text-decoration: none;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #e3f2fd;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .answer-item .answer-file a:hover {
        background: #bbdefb;
        text-decoration: none;
    }
    
    .answer-item .answer-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 8px;
    }
    
    .status-1 { background: #e8f5e9; color: #2e7d32; }
    .status-2 { background: #e3f2fd; color: #0d47a1; }
    .status-3 { background: #fff3e0; color: #e65100; }
    .status-4 { background: #fbe9e7; color: #c62828; }
    .status-null { background: #f5f5f5; color: #757575; }
    
    .answer-meta {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 6px;
        font-size: 12px;
        color: #6c757d;
    }
    
    .answer-meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .answer-item .empty-answer {
        color: #6c757d;
        font-style: italic;
        font-size: 13px;
    }
    
    /* ==========================================
       حالت خالی در مودال
       ========================================== */
    .modal-empty {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .modal-empty i {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 16px;
    }
    
    .modal-empty p {
        font-size: 16px;
        margin: 0;
    }
    
    /* ==========================================
       Responsive
       ========================================== */
    @media (max-width: 768px) {
        .correction-container {
            padding: 16px;
        }
        
        .correction-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .correction-table thead th,
        .correction-table tbody td {
            padding: 10px 12px;
            font-size: 13px;
        }
        
        .btn-answers {
            font-size: 12px;
            padding: 6px 12px;
        }
        
        .modal-container {
            width: 95%;
            max-height: 95vh;
        }
        
        .modal-header {
            padding: 14px 18px;
        }
        
        .modal-header h4 {
            font-size: 16px;
        }
        
        .modal-body {
            padding: 16px;
            max-height: 400px;
        }
        
        .answer-item {
            padding: 12px 14px;
        }
    }
</style>
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