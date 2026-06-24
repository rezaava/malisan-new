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
        <a href="#" class="chip-item {{ $isJudment ? '' : 'inactive' }}">
            <i class="fas {{ $isJudment ? 'fa-gavel' : 'fa-ban' }}"></i>
            اعتراضات (غیر فعال) 
        </a>
        <a href="/dashboard/evaluation?course_id={{ $course->id }}" class="chip-item">
            <i class="fas fa-chart-line"></i>
            پایش و ارزیابی
        </a>
        <a href="/dashboard/courses/grades/{{ $course->id }}" class="chip-item">
            <i class="fas fa-star"></i>
            نمرات دانشجویان
        </a>
        <a href="/dashboard/courses/bank?course_id={{ $course->id }}" class="chip-item">
            <i class="fas fa-database"></i>
            بانک سوالات
        </a>
        <a href="/dashboard/azmon?id={{ $course->id }}" class="chip-item">
            <i class="fas fa-pencil-alt"></i>
            تعریف آزمون
        </a>
        <a href="/dashboard/allprogress?course_id={{ $course->id }}" class="chip-item">
            <i class="fas fa-eye"></i>
            پایش دانشجویان
        </a>
        <a href="/dashboard/survey?course_id={{ $course->id }}" class="chip-item">
            <i class="fas fa-poll"></i>
            نظرسنجی
        </a>
        <a href="/dashboard/kholaseha?course_id={{ $course->id }}" class="chip-item">
            <i class="fas fa-file-alt"></i>
            لیست گزارش دانشجویان
        </a>
        <a href="/dashboard/courses/{{ $course->id }}/nomreha" class="chip-item">
            <i class="fas fa-tasks"></i>
            فعالیت های دانشجویان
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
                    <a href="{{ route('createQuestion') }}" id="questionBtn" class="action-icon-btn" data-position="bottom" data-tooltip="طرح سوال">
                        <i class="fas fa-question-circle"></i>
                    </a>
                    <a href="#" id="homeworkTeacherBtn" class="action-icon-btn" data-position="bottom" data-tooltip="دادن تکلیف">
                        <i class="fas fa-file-alt"></i>
                    </a>
                    <a href="#" id="toggleActiveBtn" class="action-icon-btn" data-position="bottom" data-tooltip="غیرفعال کردن">
                        <i class="fas fa-check-circle"></i>
                    </a>
                    <a href="#" id="homeworkBtn" class="action-icon-btn" data-position="bottom" data-tooltip="تکلیف">
                        <i class="fas fa-tasks"></i>
                    </a>
                    <a href="#" id="editBtn" class="action-icon-btn" data-position="bottom" data-tooltip="ویرایش">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="#" id="profExBtn" class="action-icon-btn" data-position="bottom" data-tooltip="تکلیفها">
                        <i class="fas fa-list-ul"></i>
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

        const editBtn = document.getElementById('editBtn');
        editBtn.setAttribute('href', `/dashboard/courses/sessions/edit/${sessionId}`);

        const questionBtn = document.getElementById('questionBtn');
        questionBtn.setAttribute('href', `/dashboard/question/show?session_id=${sessionId}`);

        const homeworkTeacherBtn = document.getElementById('homeworkTeacherBtn');
        homeworkTeacherBtn.setAttribute('href', `/dashboard/exercise/show/${sessionId}`);

        const homeworkBtn = document.getElementById('homeworkBtn');
        homeworkBtn.setAttribute('href', `/dashboard/exercise/show/${sessionId}`);

        const toggleActiveBtn = document.getElementById('toggleActiveBtn');
        toggleActiveBtn.setAttribute('href', `/dashboard/courses/sessions/active/${sessionId}`);

        const profExBtn = document.getElementById('profExBtn');
        profExBtn.setAttribute('href', `/dashboard/courses/sessions/prof-ex/${sessionId}`);
    }

    // Event listener for collapsible
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

    // اگر اولین جلسه وجود داشته باشد، لینک‌های اولیه را تنظیم کن
    @if($sessions->isNotEmpty())
        document.addEventListener('DOMContentLoaded', function() {
            const firstSession = document.querySelector('.session-item.active');
            if (firstSession) {
                const sessionId = firstSession.dataset.session;
                const pdfUrl = '{{ $sessions->first()->file ?? "" }}';
                const title = '{{ addslashes($sessions->first()->name) }}';
                const number = 'جلسه {{ $sessions->first()->number }}';
                
                // تنظیم لینک‌ها برای جلسه اول
                document.getElementById('editBtn').setAttribute('href', `/dashboard/courses/sessions/edit/${sessionId}`);
                document.getElementById('questionBtn').setAttribute('href', `/dashboard/question/show?session_id=${sessionId}`);
                document.getElementById('homeworkTeacherBtn').setAttribute('href', `/dashboard/exercise/show/${sessionId}`);
                document.getElementById('homeworkBtn').setAttribute('href', `/dashboard/exercise/show/${sessionId}`);
                document.getElementById('toggleActiveBtn').setAttribute('href', `/dashboard/courses/sessions/active/${sessionId}`);
                document.getElementById('profExBtn').setAttribute('href', `/dashboard/courses/sessions/prof-ex/${sessionId}`);
            }
        });
    @endif
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