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

    .action-icon-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .action-icon-btn.hidden-btn {
        display: none !important;
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

@section('mohtava')
<div class="course-detail-container">
    <div class="course-header">
        <h4 class="course-title-main">{{ $course->name ?? 'عنوان درس' }}</h4>
    </div>
    
    <div class="course-chips">
        @if($course->quiz == 1)
            <a href="{{ route('student.selfTest.start', $course->id) }}" class="chip-item">
                <i class="fas fa-star"></i>
                خودآزمایی
            </a>
        @endif
        
        @if($course->faaliat == 1)
            <a href="{{ route('student.my.activities',$course->id) }}" class="chip-item">
                <i class="fas fa-database"></i>
                فعالیت های من 
            </a>
        @endif
        
        @if($course->pishraft == 1)
            <a href="{{ route('student.progress', $course->id) }}" class="chip-item">
                <i class="fas fa-chart-line"></i>
                پیشرفت درسی
            </a>
        @endif
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
                       data-can-question="{{ isset($session['can_question']) && $session['can_question'] ? 'true' : 'false' }}"
                       data-can-homework="{{ isset($session['can_homework']) && $session['can_homework'] ? 'true' : 'false' }}"
                       data-can-report="{{ isset($session['can_report']) && $session['can_report'] ? 'true' : 'false' }}"
                       data-pdf="{{ $session->file ?? '' }}"
                       data-title="{{ addslashes($session->name) }}"
                       data-number="جلسه {{ $session->number }}"
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
                    {{-- دکمه طرح سوال --}}
                    <a href="#" id="questionBtn" class="action-icon-btn" data-tooltip="{{ $setting->tarahi_soal_desc ?? 'طرح سوال' }}">
                        <i class="fas fa-question-circle"></i>
                    </a>

                    {{-- دکمه مدیریت تکالیف --}}
                    <a href="#" id="homeworkBtn" class="action-icon-btn" data-tooltip="ارسال تکالیف">
                        <i class="fas fa-file-alt"></i>
                    </a>

                    {{-- دکمه ارسال گزارش --}}
                    <a href="#" id="reportBtn" class="action-icon-btn" data-tooltip="{{ $setting->ersal_gozaresh_desc ?? 'ارسال گزارش' }}">
                        <i class="fas fa-edit"></i>
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
                            <p>{!! $sessions->first()->text !!}</p>
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
                        <div id="pdfViewer" class="text-center p-5">
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
    let currentSessionId = '{{ $sessions->first()->id ?? "" }}';
    let currentPdfUrl = '{{ $sessions->first()->file ?? "" }}';
    let currentSessionTitle = '{{ $sessions->first()->name ?? "" }}';
    let currentSessionNumber = 'جلسه {{ $sessions->first()->number ?? "" }}';

    function changeSession(element, sessionId, pdfUrl, title, number) {
        // اگر عنصر وجود نداشت، از تابع خارج شو
        if (!element) return;
        
        document.querySelectorAll('.session-item').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');

        currentSessionId = sessionId;
        currentPdfUrl = pdfUrl;
        currentSessionTitle = title;
        currentSessionNumber = number;

        // به‌روزرسانی عنوان جلسه
        const titleDisplay = document.getElementById('sessionTitleDisplay');
        if (titleDisplay) {
            titleDisplay.innerHTML = `<h5>${number} : ${title}</h5>`;
        }

        // ===== به‌روزرسانی PDF Viewer =====
        const pdfViewer = document.getElementById('pdfViewer');
        if (pdfViewer) {
            if (pdfUrl) {
                pdfViewer.outerHTML = `<object id="pdfViewer" data="${pdfUrl}" type="application/pdf" width="100%" height="550px">
                    <object width="100%" height="550" data="https://docs.google.com/gview?embedded=true&url=${pdfUrl}"></object>
                </object>`;
            } else {
                pdfViewer.innerHTML = `
                    <div class="text-center p-5">
                        <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                        <p class="text-muted">هیچ فایلی برای این جلسه آپلود نشده است</p>
                    </div>
                `;
            }
        }

        // ===== به‌روزرسانی دکمه باز کردن PDF =====
        const pdfOpenBtn = document.getElementById('pdfOpenBtn');
        if (pdfOpenBtn) {
            if (pdfUrl) {
                pdfOpenBtn.setAttribute('href', pdfUrl);
                pdfOpenBtn.style.display = 'inline-flex';
            } else {
                pdfOpenBtn.style.display = 'none';
            }
        }

        // ===== به‌روزرسانی توضیحات جلسه =====
        // (در صورت نیاز می‌توانید توضیحات را نیز از طریق AJAX دریافت کنید)

        // ==========================================
        // تنظیم دکمه‌ها بر اساس وضعیت دسترسی جلسه
        // ==========================================
        
        // دریافت وضعیت‌های دسترسی از data attributes
        const canQuestion = element.dataset.canQuestion === 'true';
        const canHomework = element.dataset.canHomework === 'true';
        const canReport = element.dataset.canReport === 'true';
        
        // 1. دکمه طرح سوال
        const questionBtn = document.getElementById('questionBtn');
        if (questionBtn) {
            if (canQuestion) {
                questionBtn.setAttribute('href', `/student/questions/create/${sessionId}`);
                questionBtn.style.display = 'inline-flex';
                questionBtn.style.opacity = '1';
                questionBtn.style.pointerEvents = 'auto';
                questionBtn.classList.remove('disabled');
                questionBtn.classList.remove('hidden-btn');
            } else {
                questionBtn.style.display = 'none';
                questionBtn.classList.add('disabled');
                questionBtn.classList.add('hidden-btn');
            }
        }

        // 2. دکمه مدیریت تکالیف
        const homeworkBtn = document.getElementById('homeworkBtn');
        if (homeworkBtn) {
            if (canHomework) {
                homeworkBtn.setAttribute('href', `/student/exercise/show/${sessionId}`);
                homeworkBtn.style.display = 'inline-flex';
                homeworkBtn.style.opacity = '1';
                homeworkBtn.style.pointerEvents = 'auto';
                homeworkBtn.classList.remove('disabled');
                homeworkBtn.classList.remove('hidden-btn');
            } else {
                homeworkBtn.style.display = 'none';
                homeworkBtn.classList.add('disabled');
                homeworkBtn.classList.add('hidden-btn');
            }
        }

        // 3. دکمه ارسال گزارش
        const reportBtn = document.getElementById('reportBtn');
        if (reportBtn) {
            if (canReport) {
                reportBtn.setAttribute('href', `/student/discussion/create/${sessionId}`);
                reportBtn.style.display = 'inline-flex';
                reportBtn.style.opacity = '1';
                reportBtn.style.pointerEvents = 'auto';
                reportBtn.classList.remove('disabled');
                reportBtn.classList.remove('hidden-btn');
            } else {
                reportBtn.style.display = 'none';
                reportBtn.classList.add('disabled');
                reportBtn.classList.add('hidden-btn');
            }
        }
    }

    // ===== Collapsible =====
    document.addEventListener('DOMContentLoaded', function() {
        const collapsibleHeader = document.querySelector('.collapsible-header');
        if (collapsibleHeader) {
            collapsibleHeader.addEventListener('click', function() {
                const body = this.nextElementSibling;
                const icon = this.querySelector('.expand-icon');
                if (body) {
                    if (body.style.display === 'none' || body.style.display === '') {
                        body.style.display = 'block';
                        if (icon) icon.style.transform = 'rotate(180deg)';
                    } else {
                        body.style.display = 'none';
                        if (icon) icon.style.transform = 'rotate(0deg)';
                    }
                }
            });
        }

        // ===== تنظیم دکمه‌ها برای جلسه اول =====
        const firstSession = document.querySelector('.session-item.active');
        if (firstSession) {
            const sessionId = firstSession.dataset.session;
            const canQuestion = firstSession.dataset.canQuestion === 'true';
            const canHomework = firstSession.dataset.canHomework === 'true';
            const canReport = firstSession.dataset.canReport === 'true';
            
            // 1. دکمه طرح سوال
            const questionBtn = document.getElementById('questionBtn');
            if (questionBtn) {
                if (canQuestion) {
                    questionBtn.setAttribute('href', `/student/questions/create/${sessionId}`);
                    questionBtn.style.display = 'inline-flex';
                    questionBtn.classList.remove('disabled');
                    questionBtn.classList.remove('hidden-btn');
                } else {
                    questionBtn.style.display = 'none';
                    questionBtn.classList.add('disabled');
                    questionBtn.classList.add('hidden-btn');
                }
            }
            
            // 2. دکمه مدیریت تکالیف
            const homeworkBtn = document.getElementById('homeworkBtn');
            if (homeworkBtn) {
                if (canHomework) {
                    homeworkBtn.setAttribute('href', `/student/exercise/show/${sessionId}`);
                    homeworkBtn.style.display = 'inline-flex';
                    homeworkBtn.classList.remove('disabled');
                    homeworkBtn.classList.remove('hidden-btn');
                } else {
                    homeworkBtn.style.display = 'none';
                    homeworkBtn.classList.add('disabled');
                    homeworkBtn.classList.add('hidden-btn');
                }
            }

            // 3. دکمه ارسال گزارش
            const reportBtn = document.getElementById('reportBtn');
            if (reportBtn) {
                if (canReport) {
                    reportBtn.setAttribute('href', `/student/discussion/create/${sessionId}`);
                    reportBtn.style.display = 'inline-flex';
                    reportBtn.classList.remove('disabled');
                    reportBtn.classList.remove('hidden-btn');
                } else {
                    reportBtn.style.display = 'none';
                    reportBtn.classList.add('disabled');
                    reportBtn.classList.add('hidden-btn');
                }
            }
        }
    });
</script>
@endsection