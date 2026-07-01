@extends('layout.master')

@section('title')
ملیسان | مدیریت درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-course.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .session-action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #f0f4f9;
        color: #4a5a6e;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 16px;
        position: relative;
    }

    .action-icon-btn:hover {
        background: #1e6f9f;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 111, 159, 0.3);
    }

    .action-icon-btn[data-tooltip]:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: -32px;
        left: 50%;
        transform: translateX(-50%);
        background: #1a2332;
        color: #fff;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
        z-index: 10;
    }

    @media (max-width: 768px) {
        .session-action-buttons {
            gap: 6px;
        }
        .action-icon-btn {
            width: 34px;
            height: 34px;
            font-size: 14px;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="course-detail-container">
    <div class="course-header">
        <h4 class="course-title-main">{{ $course->name ?? 'عنوان درس' }}</h4>
    </div>
    
    <div class="course-chips">
        <a href="{{ route('student.selfTest.start', $course->id) }}" class="chip-item">
            <i class="fas fa-star"></i>
            خودآزمایی
        </a>
        <a href="{{ route('student.my.activities',$course->id) }}" class="chip-item">
            <i class="fas fa-database"></i>
            فعالیت های من 
        </a>
        <a href="{{ route('student.progress', $course->id) }}" class="chip-item">
            <i class="fas fa-chart-line"></i>
            پیشرفت درسی
        </a>
    </div>

    <div class="sessions-section">
        <div class="sessions-sidebar">
            <div class="sessions-header">
                <h5>جلسه های ارائه شده</h5>
            </div>
            <div class="sessions-list">
                @forelse($sessions as $session)
                    <a href="#" class="session-item {{ $loop->first ? 'active' : '' }}" 
                       data-session="{{ $session->id }}" 
                       onclick="changeSession(this, '{{ $session->id }}', '{{ $session->file ?? '' }}', '{{ addslashes($session->name) }}', 'جلسه {{ $session->number }}')">
                        <span class="session-check"><i class="fas fa-check-circle"></i></span>
                        <span class="session-title">{{ $session->name }}</span>
                        <small class="session-number">(جلسه {{ $session->number }})</small>
                    </a>
                @empty
                    <div class="alert alert-warning m-3">
                        <i class="fas fa-info-circle"></i>
                        این دوره هنوز جلسه‌ای ندارد
                    </div>
                @endforelse
            </div>
        </div>

        <div class="session-content">
            <div class="session-content-header">
                <div class="session-title-display">
                    <h5 id="sessionTitleDisplay">
                        @if($sessions->isNotEmpty())
                            جلسه {{ $sessions->first()->number }} : {{ $sessions->first()->name }}
                        @else
                            هیچ جلسه‌ای انتخاب نشده است
                        @endif
                    </h5>
                </div>
                <div class="session-action-buttons">
                    <a href="{{ route('student.question.create',$course->id) }}" class="action-icon-btn" data-tooltip="طرح سوال">
                        <i class="fas fa-question-circle"></i>
                    </a>
                    
                    <a href="{{ route('student.exercise.show',$course->id) }}" class="action-icon-btn" data-tooltip="مدیریت تکالیف">
                        <i class="fas fa-file-alt"></i>
                    </a>
                    
                    {{-- دکمه گزارش --}}
                    <a href="{{ route('student.discussion.create',$course->id) }}" id="reportBtn" class="action-icon-btn" data-tooltip="ارسال گزارش">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>

            <div class="session-pdf-container">
                <div class="pdf-toolbar">
                    <a href="#" id="pdfOpenBtn" class="pdf-open-btn" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                        باز کردن PDF در صفحه جدید
                    </a>
                </div>
                <div class="pdf-viewer">
                    @if($sessions->isNotEmpty() && $sessions->first()->file)
                        <object id="pdfViewer" data="/files/session{{ $sessions->first()->file }}" type="application/pdf" width="100%" height="550px">
                            <object width="100%" height="550" data="https://docs.google.com/gview?embedded=true&url={{ $sessions->first()->file }}"></object>
                        </object>
                    @else
                        <div class="text-center p-5">
                            <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                            <p class="text-muted">هیچ فایلی برای این جلسه آپلود نشده است</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="session-description">
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <i class="fas fa-bell"></i>
                        طرح درس یا محتوای درس
                        <i class="fas fa-chevron-down expand-icon"></i>
                    </div>
                    <div class="collapsible-body" id="sessionDescription">
                        @if($sessions->isNotEmpty() && $sessions->first()->text)
                            <p>{{ $sessions->first()->text }}</p>
                        @else
                            <p class="text-muted">هیچ توضیحی برای این جلسه ثبت نشده است</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentSessionId = '{{ $sessions->first()->id ?? "" }}';
    let currentPdfUrl = '{{ $sessions->first()->file ?? "" }}';
    let currentSessionTitle = '{{ $sessions->first()->name ?? "" }}';
    let currentSessionNumber = 'جلسه {{ $sessions->first()->number ?? "" }}';

    function changeSession(element, sessionId, pdfUrl, title, number) {
        document.querySelectorAll('.session-item').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');

        currentSessionId = sessionId;
        currentPdfUrl = pdfUrl;
        currentSessionTitle = title;
        currentSessionNumber = number;

        document.getElementById('sessionTitleDisplay').innerHTML = `<h5>${number} : ${title}</h5>`;

        const pdfViewer = document.getElementById('pdfViewer');
        if (pdfUrl) {
            pdfViewer.setAttribute('data', pdfUrl);
            pdfViewer.innerHTML = `<object width="100%" height="550" data="https://docs.google.com/gview?embedded=true&url=${pdfUrl}"></object>`;
        } else {
            pdfViewer.innerHTML = `
                <div class="text-center p-5">
                    <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                    <p class="text-muted">هیچ فایلی برای این جلسه آپلود نشده است</p>
                </div>
            `;
        }

        const pdfOpenBtn = document.getElementById('pdfOpenBtn');
        if (pdfUrl) {
            pdfOpenBtn.setAttribute('href', pdfUrl);
            pdfOpenBtn.style.display = 'inline-flex';
        } else {
            pdfOpenBtn.style.display = 'none';
        }

        // ==========================================
        // تنظیم لینک ۳ دکمه
        // ==========================================
        
        // 1. دکمه ویرایش جلسه (استاد)
        const editBtn = document.getElementById('editBtn');
        if (editBtn) {
            editBtn.setAttribute('href', `/dashboard/courses/sessions/edit/${sessionId}`);
        }

        // 2. دکمه طرح سوال (دانشجو)
        const questionBtn = document.getElementById('questionBtn');
        if (questionBtn) {
            questionBtn.setAttribute('href', `/student/questions/create/${sessionId}`);
        }

        // 3. دکمه مدیریت تکالیف (استاد)
        const homeworkTeacherBtn = document.getElementById('homeworkTeacherBtn');
        if (homeworkTeacherBtn) {
            homeworkTeacherBtn.setAttribute('href', `/dashboard/exercise/show/${sessionId}`);
        }
        const reportBtn = document.getElementById('reportBtn');
        if (reportBtn) {
            reportBtn.setAttribute('href', `/student/discussion/create/${sessionId}`);
        }

    }

    // ===== Collapsible =====
    document.querySelector('.collapsible-header')?.addEventListener('click', function() {
        const body = this.nextElementSibling;
        const icon = this.querySelector('.expand-icon');
        if (body.style.display === 'none' || body.style.display === '') {
            body.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        } else {
            body.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        }
    });

    // ===== تنظیم لینک‌های اولیه برای جلسه اول =====
    @if($sessions->isNotEmpty())
        document.addEventListener('DOMContentLoaded', function() {
            const firstSession = document.querySelector('.session-item.active');
            if (firstSession) {
                const sessionId = firstSession.dataset.session;
                
                // 1. دکمه ویرایش جلسه
                document.getElementById('editBtn').setAttribute('href', `/dashboard/courses/sessions/edit/${sessionId}`);
                
                // 2. دکمه طرح سوال
                document.getElementById('questionBtn').setAttribute('href', `/student/questions/create/${sessionId}`);
                
                // 3. دکمه مدیریت تکالیف
                document.getElementById('homeworkTeacherBtn').setAttribute('href', `/student/exercise/show/${sessionId}`);

                document.getElementById('reportBtn').setAttribute('href', `/student/discussion/create/${sessionId}`);
            }
        });
    @endif
    // ===== Report Button =====
    const reportBtn = document.getElementById('reportBtn');
    if (reportBtn) {
        reportBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '/student/discussion/create/' + currentSessionId;
        });
    }
</script>

<style>
    .text-center { text-align: center; }
    .p-5 { padding: 3rem; }
    .m-3 { margin: 1rem; }
    .text-muted { color: #6c757d; }
    .fa-3x { font-size: 3em; }
    .mb-3 { margin-bottom: 1rem; }
    .alert-warning {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 0.75rem 1.25rem;
        border-radius: 0.25rem;
        width: 100%;
    }
    .alert-warning i {
        margin-left: 0.5rem;
    }
</style>
@endsection