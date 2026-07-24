@extends('layout.master')

@section('title')
ملیسان | تصحیح تکالیف
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-course.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{asset('css/badge.css')}}">
@endsection

@section('mohtava')
<div class="course-detail-container">

    <div class="correction-container">
        <div class="correction-header">
            <div class="info-badge course-badge">
                <span class="badge-icon">
                    <i class="fas fa-book-open"></i>
                </span>
                <span class="badge-label">تصحیح تکالیف:</span>
                <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
            </div>
            <div>
                <span class="badge-count">
                    <i class="fas fa-file-alt"></i>
                    {{ $sessionsWithExercises->count() }} جلسه با تکلیف
                </span>
                @include('layout.backbtn')
            </div>
        </div>

        @if($sessionsWithExercises->isNotEmpty())
            <div class="correction-table-wrapper">
                <table class="correction-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>عنوان جلسه</th>
                            <th style="min-width: 180px;">تکالیف</th>
                            <th style="min-width: 180px;">سوالات</th>
                            <th style="min-width: 180px;">گزارش‌ها</th>
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
                                <td>
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        @php
                                            $questionsCount = $session->questions_count ?? 0;
                                        @endphp
                                        @if($questionsCount > 0)
                                            <button class="btn-answers has-answers" 
                                                    onclick="showSessionQuestions({{ $session->id }}, '{{ addslashes($session->name) }}')">
                                                <i class="fas fa-question-circle"></i>
                                                سوالات
                                                <span class="badge-count">{{ $questionsCount }}</span>
                                            </button>
                                        @else
                                            <span class="no-item">بدون سوال</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        @php
                                            $discussionsCount = $session->discussions_count ?? 0;
                                        @endphp
                                        @if($discussionsCount > 0)
                                            <button class="btn-answers has-answers" 
                                                    onclick="showSessionDiscussions({{ $session->id }}, '{{ addslashes($session->name) }}')">
                                                <i class="fas fa-comment"></i>
                                                گزارش‌ها
                                                <span class="badge-count">{{ $discussionsCount }}</span>
                                            </button>
                                        @else
                                            <span class="no-item">بدون گزارش</span>
                                        @endif
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
     مودال نمایش پاسخ‌های تکالیف
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

<!-- ==========================================
     مودال نمایش سوالات جلسه
     ========================================== -->
<div class="modal-overlay" id="questionsModal">
    <div class="modal-container" style="max-width: 800px;">
        <div class="modal-header">
            <h4 id="modalQuestionsTitle">
                <i class="fas fa-question-circle"></i>
                سوالات جلسه
                <span class="sub-title" id="modalQuestionsSubtitle"></span>
            </h4>
            <button class="modal-close" onclick="closeQuestionsModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="questionsModalBody">
            <div style="text-align: center; padding: 30px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 28px; color: #1e6f9f;"></i>
                <p style="margin-top: 12px; color: #6c757d;">در حال بارگذاری سوالات...</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel-modal" onclick="closeQuestionsModal()">
                <i class="fas fa-times"></i>
                بستن
            </button>
        </div>
    </div>
</div>

<!-- ==========================================
     مودال نمایش گزارش‌های جلسه
     ========================================== -->
<div class="modal-overlay" id="discussionsModal">
    <div class="modal-container" style="max-width: 800px;">
        <div class="modal-header">
            <h4 id="modalDiscussionsTitle">
                <i class="fas fa-comment"></i>
                گزارش‌های جلسه
                <span class="sub-title" id="modalDiscussionsSubtitle"></span>
            </h4>
            <button class="modal-close" onclick="closeDiscussionsModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="discussionsModalBody">
            <div style="text-align: center; padding: 30px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 28px; color: #1e6f9f;"></i>
                <p style="margin-top: 12px; color: #6c757d;">در حال بارگذاری گزارش‌ها...</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel-modal" onclick="closeDiscussionsModal()">
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
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        title.innerHTML = `
            <i class="fas fa-list"></i>
            پاسخ‌های دانشجویان
            <span class="sub-title" id="modalAnswersSubtitle"></span>
        `;
        subtitle.textContent = 'در حال بارگذاری...';
        
        body.innerHTML = `
            <div style="text-align: center; padding: 30px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 28px; color: #1e6f9f;"></i>
                <p style="margin-top: 12px; color: #6c757d;">در حال بارگذاری پاسخ‌ها...</p>
            </div>
        `;
        
        fetch(`/teacher/courses/exercises/answers2/${exerciseId}`)
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
    
    // ============================================
    // نمایش سوالات جلسه
    // ============================================
    
    function showSessionQuestions(sessionId, sessionName) {
        const modal = document.getElementById('questionsModal');
        const body = document.getElementById('questionsModalBody');
        const title = document.getElementById('modalQuestionsTitle');
        const subtitle = document.getElementById('modalQuestionsSubtitle');
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        title.innerHTML = `
            <i class="fas fa-question-circle"></i>
            سوالات جلسه: ${sessionName}
            <span class="sub-title" id="modalQuestionsSubtitle"></span>
        `;
        subtitle.textContent = 'در حال بارگذاری...';
        
        body.innerHTML = `
            <div style="text-align: center; padding: 30px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 28px; color: #1e6f9f;"></i>
                <p style="margin-top: 12px; color: #6c757d;">در حال بارگذاری سوالات...</p>
            </div>
        `;
        
        fetch(`/teacher/courses/exercises/questions/${sessionId}`)
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
                            <p>${data.message || 'خطا در بارگذاری سوالات'}</p>
                        </div>
                    `;
                    subtitle.textContent = 'خطا';
                    return;
                }
                
                subtitle.textContent = `(${data.total} سوال)`;
                
                if (data.questions.length === 0) {
                    body.innerHTML = `
                        <div class="modal-empty">
                            <i class="fas fa-inbox"></i>
                            <p>هیچ سوالی برای این جلسه ثبت نشده است</p>
                        </div>
                    `;
                    return;
                }
                
                let html = '';
                data.questions.forEach((q, idx) => {
                    const statusClass = q.status ? `status-${q.status}` : 'status-null';
                    const statusText = q.status ? 
                        ['', 'عالی', 'خوب', 'متوسط', 'بد'][q.status] || 'نامشخص' : 
                        'در انتظار بررسی';
                    
                    const studentName = q.user ? 
                        (q.user.name || '') + ' ' + (q.user.family || '') : 
                        'کاربر ناشناس';
                    
                    html += `
                        <div class="question-item">
                            <div class="question-header">
                                <span class="question-number">سوال ${idx + 1}</span>
                                <span class="student-name">
                                    <i class="fas fa-user-graduate"></i>
                                    ${studentName}
                                </span>
                                <span class="answer-status ${statusClass}">
                                    <i class="fas ${q.status ? 'fa-check-circle' : 'fa-clock'}"></i>
                                    ${statusText}
                                </span>
                            </div>
                            <div class="question-text">
                                <strong>متن سوال:</strong>
                                <p>${nl2br(q.question)}</p>
                            </div>
                            <div class="question-options">
                                <div><strong>گزینه 1:</strong> ${q.answer1 || '-'}</div>
                                <div><strong>گزینه 2:</strong> ${q.answer2 || '-'}</div>
                                <div><strong>گزینه 3:</strong> ${q.answer3 || '-'}</div>
                                <div><strong>گزینه 4:</strong> ${q.answer4 || '-'}</div>
                            </div>
                            <div class="question-correct-answer">
                                <strong>پاسخ صحیح:</strong> ${q.answer || 'نامشخص'}
                            </div>
                            ${q.comment ? `
                                <div class="question-comment">
                                    <strong>نظر استاد:</strong> ${nl2br(q.comment)}
                                </div>
                            ` : ''}
                            <div class="question-meta">
                                <span><i class="far fa-calendar-alt"></i> ${new Date(q.created_at).toLocaleDateString('fa-IR')}</span>
                                <span><i class="far fa-clock"></i> ${new Date(q.created_at).toLocaleTimeString('fa-IR')}</span>
                                <span><i class="fas fa-star"></i> ${q.star || 0}</span>
                                <span><i class="fas fa-flag"></i> سطح ${q.level || '-'}</span>
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
    
    function closeQuestionsModal() {
        const modal = document.getElementById('questionsModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
    
    // ============================================
    // نمایش گزارش‌های جلسه
    // ============================================
    
    function showSessionDiscussions(sessionId, sessionName) {
        const modal = document.getElementById('discussionsModal');
        const body = document.getElementById('discussionsModalBody');
        const title = document.getElementById('modalDiscussionsTitle');
        const subtitle = document.getElementById('modalDiscussionsSubtitle');
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        title.innerHTML = `
            <i class="fas fa-comment"></i>
            گزارش‌های جلسه: ${sessionName}
            <span class="sub-title" id="modalDiscussionsSubtitle"></span>
        `;
        subtitle.textContent = 'در حال بارگذاری...';
        
        body.innerHTML = `
            <div style="text-align: center; padding: 30px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 28px; color: #1e6f9f;"></i>
                <p style="margin-top: 12px; color: #6c757d;">در حال بارگذاری گزارش‌ها...</p>
            </div>
        `;
        
        fetch(`/teacher/courses/exercises/discussions/${sessionId}`)
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
                            <p>${data.message || 'خطا در بارگذاری گزارش‌ها'}</p>
                        </div>
                    `;
                    subtitle.textContent = 'خطا';
                    return;
                }
                
                subtitle.textContent = `(${data.total} گزارش)`;
                
                if (data.discussions.length === 0) {
                    body.innerHTML = `
                        <div class="modal-empty">
                            <i class="fas fa-inbox"></i>
                            <p>هیچ گزارشی برای این جلسه ثبت نشده است</p>
                        </div>
                    `;
                    return;
                }
                
                let html = '';
                data.discussions.forEach((d, idx) => {
                    const statusClass = d.status ? `status-${d.status}` : 'status-null';
                    const statusText = d.status ? 
                        ['', 'عالی', 'خوب', 'متوسط', 'بد'][d.status] || 'نامشخص' : 
                        'در انتظار بررسی';
                    
                    const studentName = d.user ? 
                        (d.user.name || '') + ' ' + (d.user.family || '') : 
                        'کاربر ناشناس';
                    
                    html += `
                        <div class="discussion-item">
                            <div class="discussion-header">
                                <span class="discussion-number">گزارش ${idx + 1}</span>
                                <span class="student-name">
                                    <i class="fas fa-user-graduate"></i>
                                    ${studentName}
                                </span>
                                <span class="answer-status ${statusClass}">
                                    <i class="fas ${d.status ? 'fa-check-circle' : 'fa-clock'}"></i>
                                    ${statusText}
                                </span>
                            </div>
                            <div class="discussion-text">
                                <strong>متن گزارش:</strong>
                                <p>${nl2br(d.text)}</p>
                            </div>
                            ${d.comment1 ? `
                                <div class="discussion-comment">
                                    <strong>نظر استاد:</strong> ${nl2br(d.comment1)}
                                </div>
                            ` : ''}
                            ${d.comment2 ? `
                                <div class="discussion-comment">
                                    <strong>نظر دوم:</strong> ${nl2br(d.comment2)}
                                </div>
                            ` : ''}
                            ${d.comment3 ? `
                                <div class="discussion-comment">
                                    <strong>نظر سوم:</strong> ${nl2br(d.comment3)}
                                </div>
                            ` : ''}
                            <div class="discussion-meta">
                                <span><i class="far fa-calendar-alt"></i> ${new Date(d.created_at).toLocaleDateString('fa-IR')}</span>
                                <span><i class="far fa-clock"></i> ${new Date(d.created_at).toLocaleTimeString('fa-IR')}</span>
                                <span><i class="fas fa-star"></i> امتیاز: ${d.score || 0}</span>
                                <span><i class="fas fa-flag"></i> سطح ${d.level || '-'}</span>
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
    
    function closeDiscussionsModal() {
        const modal = document.getElementById('discussionsModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
    
    // ============================================
    // توابع کمکی
    // ============================================
    
    function nl2br(str) {
        if (!str) return '';
        return str.replace(/\n/g, '<br>');
    }
    
    // بستن مودال‌ها با کلیک روی پس‌زمینه
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            if (e.target.id === 'answersModal') closeAnswersModal();
            if (e.target.id === 'questionsModal') closeQuestionsModal();
            if (e.target.id === 'discussionsModal') closeDiscussionsModal();
        }
    });
    
    // بستن مودال‌ها با دکمه Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('answersModal').classList.contains('active')) closeAnswersModal();
            if (document.getElementById('questionsModal').classList.contains('active')) closeQuestionsModal();
            if (document.getElementById('discussionsModal').classList.contains('active')) closeDiscussionsModal();
        }
    });
</script>

<style>
    .no-item {
        color: #adb5bd;
        font-size: 0.85rem;
        padding: 4px 8px;
        background: #f8f9fa;
        border-radius: 4px;
    }
    
    .question-item, .discussion-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
        background: #fff;
    }
    
    .question-header, .discussion-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f3f5;
    }
    
    .question-number, .discussion-number {
        font-weight: bold;
        color: #1e6f9f;
        background: #e7f3ff;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.9rem;
    }
    
    .question-text p, .discussion-text p {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        margin: 6px 0 12px 0;
        white-space: pre-wrap;
        word-break: break-word;
    }
    
    .question-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px 16px;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        margin: 8px 0;
    }
    
    .question-options div {
        font-size: 0.95rem;
        padding: 4px 0;
    }
    
    .question-correct-answer {
        margin: 8px 0;
        padding: 6px 12px;
        background: #d4edda;
        border-radius: 6px;
        color: #155724;
    }
    
    .question-comment, .discussion-comment {
        margin: 8px 0;
        padding: 8px 12px;
        background: #fff3cd;
        border-radius: 6px;
        color: #856404;
    }
    
    .question-meta, .discussion-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 12px;
        padding-top: 8px;
        border-top: 1px solid #e9ecef;
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .question-meta span, .discussion-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .question-meta i, .discussion-meta i {
        width: 16px;
        text-align: center;
    }
    
    .status-1 { background: #d4edda; color: #155724; }
    .status-2 { background: #cce5ff; color: #004085; }
    .status-3 { background: #fff3cd; color: #856404; }
    .status-4 { background: #f8d7da; color: #721c24; }
    .status-null { background: #e2e3e5; color: #383d41; }
    
    .answer-status {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .modal-empty {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    .modal-empty i {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 16px;
        display: block;
    }
    .modal-empty p {
        font-size: 1rem;
    }
    
    .btn-answers {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        background: #e7f3ff;
        color: #1e6f9f;
    }
    .btn-answers:hover {
        background: #d0e4f5;
    }
    .btn-answers.has-answers {
        background: #d4edda;
        color: #155724;
    }
    .btn-answers.has-answers:hover {
        background: #c3e6cb;
    }
    .btn-answers.no-answers {
        background: #f8f9fa;
        color: #6c757d;
        opacity: 0.7;
        cursor: default;
    }
    .btn-answers .badge-count {
        display: inline-block;
        background: rgba(0,0,0,0.1);
        padding: 0 6px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        line-height: 1.4;
    }
    .btn-answers.has-answers .badge-count {
        background: rgba(21, 87, 36, 0.15);
    }
</style>
@endsection