@extends('layout.master')

@section('title')
    ملیسان | مدیریت درس
@endsection

@section('head')
    <link rel="stylesheet" href="{{asset('css/style-course.css')}}">
    <link rel="stylesheet" href="{{asset('css/badge.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .course-actions-bar {
            justify-content: flex-start !important;
        }
    </style>
@endsection

@section('mohtava')
    <div class="course-detail-container">
        <div class="course-actions-bar">
            <div class="info-badge course-badge">
                <span class="badge-icon">
                    <i class="fas fa-book-open"></i>
                </span>
                <span class="badge-label">دوره آموزشی:</span>
                <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
            </div>
            {{-- ===== نمایش تایمر باز شدن جلسه بعدی ===== --}}
            @if(isset($nextUnlockTime) && $nextUnlockTime && $member == 1)
                <div class="info-badge topic-badge">
                    <i class="fas fa-clock"></i>
                    <span id="timerDisplay" data-unlock="{{ $nextUnlockTime->toIso8601String() }}">
                        --:--:--
                    </span>
                </div>
            @endif
        </div>

        <div class="course-chips">
            @if($course->quiz == 1)
                <a href="{{ route('student.selfTest.start', $course->id) }}" class="chip-item">
                    <i class="fas fa-star"></i>
                    خودآزمایی
                </a>
            @endif

            @if($course->faaliat == 1)
                <a href="{{ route('student.my.activities', $course->id) }}" class="chip-item">
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

            @if(isset($course->davari) && $course->davari == 1)
                <a href="{{ route('student.judgment.index', ['course_id' => $course->id]) }}" class="chip-item davari">
                    <i class="fas fa-users-cog"></i>
                    داوری دوستان
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
                        <a href="#" class="session-item {{ $loop->first ? 'active' : '' }}" data-session="{{ $session->id }}"
                            data-can-question="{{ isset($session['can_question']) && $session['can_question'] ? 'true' : 'false' }}"
                            data-can-homework="{{ isset($session['can_homework']) && $session['can_homework'] ? 'true' : 'false' }}"
                            data-can-report="{{ isset($session['can_report']) && $session['can_report'] ? 'true' : 'false' }}"
                            data-pdf="{{ $session->file ?? '' }}" data-title="{{ addslashes($session->name) }}"
                            data-number="جلسه {{ $session->number }}" data-description="{{ addslashes($session->text ?? '') }}"
                            onclick="changeSession(this, '{{ $session->id }}', '{{ $session->file ?? '' }}', '{{ addslashes($session->name) }}', 'جلسه {{ $session->number }}', '{{ addslashes($session->text ?? '') }}')">
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
                        <div class="info-badges">
                            <div class="info-badge session-badge">
                                <span class="badge-icon">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <span class="badge-label">جلسه:</span>
                                <span class="badge-value" id="sessionNumberDisplay">
                                    @if($sessions->isNotEmpty())
                                        {{ $sessions->first()->number }}
                                    @else
                                        <span class="empty">-</span>
                                    @endif
                                </span>
                            </div>

                            <div class="info-badge topic-badge">
                                <span class="badge-icon">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <span class="badge-label">موضوع:</span>
                                <span class="badge-value" id="sessionNameDisplay">
                                    @if($sessions->isNotEmpty())
                                        {{ $sessions->first()->name }}
                                    @else
                                        <span class="empty">هیچ جلسه‌ای انتخاب نشده است</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="session-action-buttons">
                        <a href="#" id="questionBtn" class="action-icon-btn" data-tooltip="ارسال سوال">
                            <i class="fas fa-question-circle"></i>
                        </a>

                        <a href="#" id="homeworkBtn" class="action-icon-btn" data-tooltip="ارسال تکلیف">
                            <i class="fas fa-file-alt"></i>
                        </a>

                        <a href="#" id="reportBtn" class="action-icon-btn" data-tooltip="ارسال گزارش">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
                <div class="session-description">
                    @php
                        $hasDescription = false;
                        if ($sessions->isNotEmpty()) {
                            $firstSession = $sessions->first();
                            $hasDescription = !empty($firstSession->text) && trim(strip_tags($firstSession->text)) !== '';
                        }
                    @endphp

                    @if($hasDescription)
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <i class="fas fa-bell"></i>
                                طرح درس یا محتوای درس
                                <i class="fas fa-chevron-down expand-icon"></i>
                            </div>
                            <div class="collapsible-body" id="sessionDescription">
                                <p>{!! $sessions->first()->text !!}</p>
                            </div>
                        </div>
                    @else
                        <div class="collapsible-section" style="display:none;">
                            <div class="collapsible-header">
                                <i class="fas fa-bell"></i>
                                طرح درس یا محتوای درس
                                <i class="fas fa-chevron-down expand-icon"></i>
                            </div>
                            <div class="collapsible-body" id="sessionDescription">
                                <p class="text-muted">هیچ توضیحی برای این جلسه ثبت نشده است</p>
                            </div>
                        </div>
                    @endif
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
                            <object id="pdfViewer" data="/files/session{{ $sessions->first()->file }}" type="application/pdf"
                                width="100%" height="550px">
                                <object width="100%" height="550"
                                    data="https://docs.google.com/gview?embedded=true&url={{ $sessions->first()->file }}"></object>
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
        let currentDescription = '{{ addslashes($sessions->first()->text ?? "") }}';

        function changeSession(element, sessionId, pdfUrl, title, number, description) {
            if (!element) return;

            document.querySelectorAll('.session-item').forEach(item => {
                item.classList.remove('active');
            });
            element.classList.add('active');

            currentSessionId = sessionId;
            currentPdfUrl = pdfUrl;
            currentSessionTitle = title;
            currentSessionNumber = number;
            currentDescription = description;

            const sessionNumberDisplay = document.getElementById('sessionNumberDisplay');
            if (sessionNumberDisplay) {
                const numberMatch = number.match(/\d+/);
                sessionNumberDisplay.textContent = numberMatch ? numberMatch[0] : '-';
            }

            const sessionNameDisplay = document.getElementById('sessionNameDisplay');
            if (sessionNameDisplay) {
                sessionNameDisplay.textContent = title || 'هیچ جلسه‌ای انتخاب نشده است';
            }

            const pdfViewer = document.getElementById('pdfViewer');
            if (pdfViewer) {
                if (pdfUrl) {
                    if (pdfUrl.startsWith('http')) {
                        pdfViewer.outerHTML = `<object id="pdfViewer" data="${pdfUrl}" type="application/pdf" width="100%" height="550px">
                            <object width="100%" height="550" data="https://docs.google.com/gview?embedded=true&url=${pdfUrl}"></object>
                        </object>`;
                    } else {
                        pdfViewer.outerHTML = `<object id="pdfViewer" data="/files/session${pdfUrl}" type="application/pdf" width="100%" height="550px">
                            <object width="100%" height="550" data="https://docs.google.com/gview?embedded=true&url=/files/session${pdfUrl}"></object>
                        </object>`;
                    }
                } else {
                    pdfViewer.innerHTML = `
                        <div class="text-center p-5">
                            <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                            <p class="text-muted">هیچ فایلی برای این جلسه آپلود نشده است</p>
                        </div>
                    `;
                }
            }

            const pdfOpenBtn = document.getElementById('pdfOpenBtn');
            if (pdfOpenBtn) {
                if (pdfUrl) {
                    const fullPdfUrl = pdfUrl.startsWith('http') ? pdfUrl : '/files/session' + pdfUrl;
                    pdfOpenBtn.setAttribute('href', fullPdfUrl);
                    pdfOpenBtn.style.display = 'inline-flex';
                } else {
                    pdfOpenBtn.style.display = 'none';
                }
            }

            const sessionDescription = document.getElementById('sessionDescription');
            const collapsibleSection = document.querySelector('.collapsible-section');

            if (sessionDescription && collapsibleSection) {
                const hasValidDescription = description && description.trim() !== '' && description.trim() !== 'null';

                if (hasValidDescription) {
                    sessionDescription.innerHTML = `<p>${description}</p>`;
                    collapsibleSection.style.display = 'block';
                } else {
                    sessionDescription.innerHTML = '<p class="text-muted">هیچ توضیحی برای این جلسه ثبت نشده است</p>';
                    collapsibleSection.style.display = 'none';
                }
            }

            const canQuestion = element.dataset.canQuestion === 'true';
            const canHomework = element.dataset.canHomework === 'true';
            const canReport = element.dataset.canReport === 'true';

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
        document.addEventListener('DOMContentLoaded', function () {
            const collapsibleHeader = document.querySelector('.collapsible-header');
            if (collapsibleHeader) {
                collapsibleHeader.addEventListener('click', function () {
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

            // ===== تایمر شمارش معکوس =====
            const timerDisplay = document.getElementById('timerDisplay');
            if (timerDisplay) {
                const unlockTime = new Date(timerDisplay.dataset.unlock).getTime();

                function updateTimer() {
                    const now = new Date().getTime();
                    const diff = unlockTime - now;

                    if (diff <= 0) {
                        timerDisplay.textContent = 'هم‌اکنون باز شد!';
                        // در صورت نیاز می‌توان صفحه را رفرش کرد یا پیغام داد
                        // location.reload(); // اختیاری
                        return;
                    }

                    const hours = Math.floor(diff / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    timerDisplay.textContent =
                        String(hours).padStart(2, '0') + ':' +
                        String(minutes).padStart(2, '0') + ':' +
                        String(seconds).padStart(2, '0');
                }

                updateTimer();
                setInterval(updateTimer, 1000);
            }
        });
    </script>

    {{-- استایل اختیاری برای تایمر --}}
    <style>
        .next-session-timer {
            background: #f0f4ff;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 15px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            border: 1px solid #d0d9ff;
        }

        .timer-box i {
            color: #2a5cff;
            font-size: 20px;
        }

        #timerDisplay {
            font-weight: bold;
            color: #2a5cff;
            font-size: 20px;
            direction: ltr;
            display: inline-block;
            min-width: 80px;
        }
    </style>
@endsection