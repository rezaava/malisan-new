@extends('layout.master')

@section('title')
ملیسان | مدیریت درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-course.css')}}">
@endsection

@section('mohtava')
<div class="course-detail-container">
    <div class="course-header">
        <h4 class="course-title-main">{{ $course->name ?? 'عنوان درس' }}</h4>
    </div>

    <div class="course-actions-bar">
        <a href="{{ route('courses.setting',$course->id) }}" class="action-btn settings-btn">
            <i class="fas fa-cog"></i>
        </a>
        <a href="#" class="action-btn back-btn" onclick="history.back()">
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="course-chips">
        <a href="{{ route('studentsList',$course->id) }}" class="chip-item">
            <i class="fas fa-user-graduate"></i>
            مشخصات دانشجویان ({{ $course->students->count() ?? 0 }})
        </a>
        <a href="{{ route('teacher.question.reports.list', $course->id) }}" class="chip-item">
            <i class="fas fa-flag" style="color:#f44336;"></i>
            ایراد سوال‌ها ({{ $reportCount ?? 0 }})
        </a>
        <a href="{{ route('gradesList',$course->id) }}" class="chip-item">
            <i class="fas fa-star"></i>
            نمرات دانشجویان
        </a>
        <a href="{{ route('question.bank',$course->id) }}" class="chip-item">
            <i class="fas fa-database"></i>
            بانک سوالات
        </a>
        <a href="{{ route('azmon.list',$course->id) }}" class="chip-item">
            <i class="fas fa-pencil-alt"></i>
            تعریف آزمون
        </a>
        <a href="{{ route('activities',$course->id) }}" class="chip-item">
            <i class="fas fa-eye"></i>
            پایش دانشجویان
        </a>
        <a href="{{ route('surveys.index', $course->id) }}" class="chip-item">
            <i class="fas fa-poll"></i>
            نظرسنجی
        </a>
        <a href="{{ route('teacher.reports.list', $course->id) }}" class="chip-item">
            <i class="fas fa-file-alt"></i>
            لیست گزارش دانشجویان
        </a>
        <a href="{{ route('studentActivities',$course) }}" class="chip-item">
            <i class="fas fa-tasks"></i>
            فعالیت های دانشجویان
        </a>
        <a href="{{ route('exercises.correction', $course->id) }}" class="chip-item">
            <i class="fas fa-check-double"></i>
            تصحیح تکالیف
        </a>
    </div>

    <div class="sessions-section">
        <div class="sessions-sidebar">
            <div class="sessions-header">
                <h5>جلسه های ارائه شده</h5>
                <a href="{{ route('sessions.create',$course->id) }}" class="add-session-btn">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
            <div class="sessions-list">
                @forelse($sessions as $session)
                    <a href="#" class="session-item {{ $loop->first ? 'active' : '' }}" 
                       data-session="{{ $session->id }}"
                       data-pdf="{{ $session->file ?? '' }}"
                       data-title="{{ $session->name }}"
                       data-number="جلسه {{ $session->number }}"
                       data-description="{{ htmlspecialchars($session->text ?? '', ENT_QUOTES, 'UTF-8') }}"
                       onclick="changeSessionFromData(this)">
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
                    <a href="#" id="homeworkTeacherBtn" class="action-icon-btn" data-position="bottom" data-tooltip="مدیریت تکالیف">
                        <i class="fas fa-file-alt"></i>
                    </a>
                    <a href="#" id="homeworkBtn" class="action-icon-btn" data-position="bottom" data-tooltip="تکالیف من">
                        <i class="fas fa-tasks"></i>
                    </a>
                    <a href="#" id="profExBtn" class="action-icon-btn" data-position="bottom" data-tooltip="لیست تکالیف">
                        <i class="fas fa-list-ul"></i>
                    </a>
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
                            {!! $sessions->first()->text !!}
                        @else
                            <p class="text-muted">هیچ توضیحی برای این جلسه ثبت نشده است</p>
                        @endif
                    </div>
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
        </div>
    </div>
</div>

<script>
    // متغیرهای اولیه با استفاده از json_encode
    let currentSessionId = {!! json_encode($sessions->first()->id ?? '') !!};
    let currentPdfUrl = {!! json_encode($sessions->first()->file ?? '') !!};
    let currentSessionTitle = {!! json_encode($sessions->first()->name ?? '') !!};
    let currentSessionNumber = {!! json_encode('جلسه ' . ($sessions->first()->number ?? '')) !!};
    let currentDescription = {!! json_encode($sessions->first()->text ?? '') !!};

    // تابع اصلی برای تغییر جلسه
    function changeSession(element, sessionId, pdfUrl, title, number, description) {
        // غیرفعال کردن همه جلسات
        document.querySelectorAll('.session-item').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');

        // به‌روزرسانی متغیرها
        currentSessionId = sessionId;
        currentPdfUrl = pdfUrl;
        currentSessionTitle = title;
        currentSessionNumber = number;

        // به‌روزرسانی عنوان جلسه
        document.getElementById('sessionTitleDisplay').innerHTML = `<h5>${number} : ${title}</h5>`;

        // به‌روزرسانی توضیحات جلسه
        const sessionDescription = document.getElementById('sessionDescription');
        if (description && description.trim() !== '') {
            sessionDescription.innerHTML = description;
        } else {
            sessionDescription.innerHTML = '<p class="text-muted">هیچ توضیحی برای این جلسه ثبت نشده است</p>';
        }

        // به‌روزرسانی PDF
        const pdfViewer = document.getElementById('pdfViewer');
        if (pdfUrl && pdfUrl !== '') {
            const filePath = '/files/session' + pdfUrl;
            pdfViewer.setAttribute('data', filePath);
            pdfViewer.innerHTML = `<object width="100%" height="550" data="${filePath}" type="application/pdf">
                                        <object width="100%" height="550" data="https://docs.google.com/gview?embedded=true&url=${encodeURIComponent(pdfUrl)}"></object>
                                    </object>`;
        } else {
            pdfViewer.innerHTML = `
                <div class="text-center p-5">
                    <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                    <p class="text-muted">هیچ فایلی برای این جلسه آپلود نشده است</p>
                </div>
            `;
        }

        // به‌روزرسانی دکمه PDF
        const pdfOpenBtn = document.getElementById('pdfOpenBtn');
        if (pdfUrl && pdfUrl !== '') {
            pdfOpenBtn.setAttribute('href', '/files/session' + pdfUrl);
            pdfOpenBtn.style.display = 'inline-flex';
        } else {
            pdfOpenBtn.style.display = 'none';
        }

        // به‌روزرسانی دکمه‌ها
        const homeworkTeacherBtn = document.getElementById('homeworkTeacherBtn');
        if (homeworkTeacherBtn) {
            homeworkTeacherBtn.setAttribute('href', `/teacher/courses/exercises/show/${sessionId}`);
        }

        const homeworkBtn = document.getElementById('homeworkBtn');
        if (homeworkBtn) {
            homeworkBtn.setAttribute('href', `/teacher/courses/exercises/show/${sessionId}`);
        }

        const profExBtn = document.getElementById('profExBtn');
        if (profExBtn) {
            profExBtn.setAttribute('href', `/teacher/courses/sessions/prof-ex/${sessionId}`);
        }
    }

    // تابع واسط برای خواندن از data attributes
    function changeSessionFromData(element) {
        const sessionId = element.dataset.session;
        const pdfUrl = element.dataset.pdf;
        const title = element.dataset.title;
        const number = element.dataset.number;
        const description = element.dataset.description;
        
        changeSession(element, sessionId, pdfUrl, title, number, description);
    }

    // Event listener برای collapsible
    document.addEventListener('DOMContentLoaded', function() {
        const collapsibleHeader = document.querySelector('.collapsible-header');
        if (collapsibleHeader) {
            collapsibleHeader.addEventListener('click', function() {
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
        }

        // تنظیم دکمه‌ها برای جلسه اول
        const firstSession = document.querySelector('.session-item.active');
        if (firstSession) {
            const sessionId = firstSession.dataset.session;
            
            const homeworkTeacherBtn = document.getElementById('homeworkTeacherBtn');
            if (homeworkTeacherBtn) {
                homeworkTeacherBtn.setAttribute('href', `/teacher/courses/exercises/show/${sessionId}`);
            }
            
            const homeworkBtn = document.getElementById('homeworkBtn');
            if (homeworkBtn) {
                homeworkBtn.setAttribute('href', `/teacher/courses/exercises/show/${sessionId}`);
            }
            
            const profExBtn = document.getElementById('profExBtn');
            if (profExBtn) {
                profExBtn.setAttribute('href', `/teacher/courses/sessions/prof-ex/${sessionId}`);
            }
        }
    });
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