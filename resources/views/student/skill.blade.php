@extends('layout.master')

@section('title')
ملیسان | مدیریت درس
@endsection

@section('head') <link rel="stylesheet" href="{{asset('css/style-course.css')}}"> <link rel="stylesheet" href="{{asset('css/badge.css')}}"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('mohtava') <div class="course-detail-container">


    <div class="course-actions-bar">
        <div class="info-badge course-badge">
            <span class="badge-icon">
                <i class="fas fa-book-open"></i>
            </span>

            <span class="badge-label">دوره آموزشی:</span>

            <span class="badge-value">
                {{ $course->name ?? 'عنوان درس' }}
            </span>
        </div>

        @if(isset($nextUnlockTime) && $nextUnlockTime && $member == 1)
            <div class="info-badge topic-badge">
                <i class="fas fa-clock"></i>

                <span id="timerDisplay"
                    data-unlock="{{ $nextUnlockTime->utc()->toIso8601String() }}">
                    --:--:--
                </span>
            </div>
        @endif
    </div>

    <div class="course-chips">

        @if($course->quiz == 1)
            <a href="{{ route('student.selfTest.start', $course->id) }}"
                class="chip-item">
                <i class="fas fa-star"></i>
                خودآزمایی
            </a>
        @endif

        @if($course->faaliat == 1)
            <a href="{{ route('student.my.activities.skill', $course->id) }}"
                class="chip-item">
                <i class="fas fa-database"></i>
                فعالیت های من
            </a>
        @endif

        @if($course->pishraft == 1)
            <a href="{{ route('student.progress.skill', $course->id) }}"
                class="chip-item">
                <i class="fas fa-chart-line"></i>
                پیشرفت درسی
            </a>
        @endif

        @if(isset($course->davari) && $course->davari == 1)
            <a href="{{ route('student.judgment.index', ['course_id' => $course->id]) }}"
                class="chip-item davari">
                <i class="fas fa-users-cog"></i>
                داوری دوستان
            </a>
        @endif

    </div>

    <div class="sessions-section">

        {{-- =========================
             لیست جلسات
        ========================== --}}
        <div class="sessions-sidebar">

            <div class="sessions-header">
                <h5>جلسه های ارائه شده</h5>
            </div>

            <div class="sessions-list">

                @forelse($sessions as $session)

                    @php
                        $sessionData = [
                            'id' => $session->id,
                            'name' => $session->name,
                            'number' => $session->number,
                            'text' => $session->text ?? '',
                            'file' => $session->file ?? '',
                            'majazi' => $session->majazi ?? '',
                            'can_question' => isset($session['can_question']) && $session['can_question'],
                            'can_homework' => isset($session['can_homework']) && $session['can_homework'],
                            'can_report' => isset($session['can_report']) && $session['can_report'],
                        ];
                    @endphp

                    <a href="#"
                        class="session-item {{ $loop->first ? 'active' : '' }}"
                        data-session="{{ $session->id }}"
                        data-can-question="{{ $sessionData['can_question'] ? 'true' : 'false' }}"
                        data-can-homework="{{ $sessionData['can_homework'] ? 'true' : 'false' }}"
                        data-can-report="{{ $sessionData['can_report'] ? 'true' : 'false' }}"
                        onclick="changeSession(this, {{ json_encode($sessionData) }})">

                        <span class="session-check">
                            <i class="fas fa-check-circle"></i>
                        </span>

                        <span class="session-title">
                            {{ $session->name }}
                        </span>

                        <small class="session-number">
                            (جلسه {{ $session->number }})
                        </small>

                    </a>

                @empty

                    <div class="alert alert-warning m-3">
                        <i class="fas fa-info-circle"></i>
                        این دوره هنوز جلسه‌ای ندارد
                    </div>

                @endforelse

            </div>
        </div>

        {{-- =========================
             محتوای جلسه
        ========================== --}}
        <div class="session-content">

            <div class="session-content-header">

                <div class="session-title-display">

                    <div class="info-badges">

                        {{-- شماره جلسه --}}
                        <div class="info-badge session-badge">

                            <span class="badge-icon">
                                <i class="fas fa-hashtag"></i>
                            </span>

                            <span class="badge-label">
                                جلسه:
                            </span>

                            <span class="badge-value"
                                id="sessionNumberDisplay">

                                @if($sessions->isNotEmpty())
                                    {{ $sessions->first()->number }}
                                @else
                                    <span class="empty">-</span>
                                @endif

                            </span>

                        </div>

                        {{-- موضوع --}}
                        <div class="info-badge topic-badge">

                            <span class="badge-icon">
                                <i class="fas fa-tag"></i>
                            </span>

                            <span class="badge-label">
                                موضوع:
                            </span>

                            <span class="badge-value"
                                id="sessionNameDisplay">

                                @if($sessions->isNotEmpty())
                                    {{ $sessions->first()->name }}
                                @else
                                    <span class="empty">
                                        هیچ جلسه‌ای انتخاب نشده است
                                    </span>
                                @endif

                            </span>

                        </div>

                        {{-- =========================
                             کلاس مجازی
                        ========================== --}}
                        <a href="#"
                            id="majaziSessionBtn"
                            class="info-badge majazi-badge"
                            target="_blank"
                            rel="noopener noreferrer"
                            style="display: {{ $sessions->isNotEmpty() && !empty($sessions->first()->majazi) ? 'inline-flex' : 'none' }};">

                            <span class="badge-icon">
                                <i class="fas fa-video"></i>
                            </span>

                            <span class="badge-value">
                                کلاس مجازی
                            </span>
                        </a>

                    </div>

                </div>

                <div class="session-action-buttons">

                    <a href="#"
                        id="questionBtn"
                        class="action-icon-btn"
                        data-tooltip="ارسال سوال">

                        <i class="fas fa-question-circle"></i>

                    </a>

                    <a href="#"
                        id="homeworkBtn"
                        class="action-icon-btn"
                        data-tooltip="ارسال تکلیف">

                        <i class="fas fa-file-alt"></i>

                    </a>

                    <a href="#"
                        id="reportBtn"
                        class="action-icon-btn"
                        data-tooltip="ارسال گزارش">

                        <i class="fas fa-edit"></i>

                    </a>

                </div>

            </div>

            {{-- =========================
                 توضیحات جلسه
            ========================== --}}
            <div class="session-description">

                @php
                    $hasDescription = false;

                    if ($sessions->isNotEmpty()) {
                        $firstSession = $sessions->first();

                        $hasDescription =
                            !empty($firstSession->text) &&
                            trim(strip_tags($firstSession->text)) !== '';
                    }
                @endphp

                @if($hasDescription)

                    <div class="collapsible-section">

                        <div class="collapsible-header">

                            <i class="fas fa-bell"></i>

                            طرح درس یا محتوای درس

                            <i class="fas fa-chevron-down expand-icon"></i>

                        </div>

                        <div class="collapsible-body"
                            id="sessionDescription">

                            <p>
                                {!! $sessions->first()->text !!}
                            </p>

                        </div>

                    </div>

                @else

                    <div class="collapsible-section"
                        style="display:none;">

                        <div class="collapsible-header">

                            <i class="fas fa-bell"></i>

                            طرح درس یا محتوای درس

                            <i class="fas fa-chevron-down expand-icon"></i>

                        </div>

                        <div class="collapsible-body"
                            id="sessionDescription">

                            <p class="text-muted">
                                هیچ توضیحی برای این جلسه ثبت نشده است
                            </p>

                        </div>

                    </div>

                @endif

            </div>

            {{-- =========================
                 PDF
            ========================== --}}
            <div class="session-pdf-container">

                <div class="pdf-toolbar">

                    <a href="#"
                        id="pdfOpenBtn"
                        class="pdf-open-btn"
                        target="_blank"
                        rel="noopener noreferrer">

                        <i class="fas fa-file-pdf"></i>

                        باز کردن PDF در صفحه جدید

                    </a>

                </div>

                <div class="pdf-viewer">

                    @if($sessions->isNotEmpty() && $sessions->first()->file)

                        <object
                            id="pdfViewer"
                            data="/files/session{{ $sessions->first()->file }}"
                            type="application/pdf"
                            width="100%"
                            height="550px">

                            <object
                                width="100%"
                                height="550"
                                data="https://docs.google.com/gview?embedded=true&url={{ $sessions->first()->file }}">
                            </object>

                        </object>

                    @else

                        <div id="pdfViewer"
                            class="text-center p-5">

                            <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>

                            <p class="text-muted">
                                هیچ فایلی برای این جلسه آپلود نشده است
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


@endsection

@section('js')

<script>

    // ==========================================
    // متغیرهای جلسه فعلی
    // ==========================================

    let currentSessionId =
        '{{ $sessions->first()->id ?? "" }}';

    let currentPdfUrl =
        '{{ $sessions->first()->file ?? "" }}';

    let currentSessionTitle =
        '{{ $sessions->first()->name ?? "" }}';

    let currentSessionNumber =
        'جلسه {{ $sessions->first()->number ?? "" }}';

    let currentDescription =
        {!! json_encode($sessions->first()->text ?? '') !!};

    let currentMajaziUrl =
        {!! json_encode($sessions->first()->majazi ?? '') !!};


    // ==========================================
    // تغییر جلسه
    // ==========================================

    function changeSession(element, sessionData) {

        if (!element) return;

        // حذف active از تمام جلسات
        document
            .querySelectorAll('.session-item')
            .forEach(item => item.classList.remove('active'));

        // فعال کردن جلسه انتخاب شده
        element.classList.add('active');


        // ==========================================
        // دریافت اطلاعات جلسه
        // ==========================================

        const sessionId =
            sessionData.id;

        const pdfUrl =
            sessionData.file || '';

        const title =
            sessionData.name || '';

        const number =
            sessionData.number || '';

        const description =
            sessionData.text || '';

        const majaziUrl =
            sessionData.majazi || '';

        const canQuestion =
            sessionData.can_question;

        const canHomework =
            sessionData.can_homework;

        const canReport =
            sessionData.can_report;


        // ==========================================
        // ذخیره اطلاعات فعلی
        // ==========================================

        currentSessionId =
            sessionId;

        currentPdfUrl =
            pdfUrl;

        currentSessionTitle =
            title;

        currentSessionNumber =
            'جلسه ' + number;

        currentDescription =
            description;

        currentMajaziUrl =
            majaziUrl;


        // ==========================================
        // شماره جلسه
        // ==========================================

        const sessionNumberDisplay =
            document.getElementById('sessionNumberDisplay');

        if (sessionNumberDisplay) {

            sessionNumberDisplay.textContent =
                number || '-';

        }


        // ==========================================
        // عنوان جلسه
        // ==========================================

        const sessionNameDisplay =
            document.getElementById('sessionNameDisplay');

        if (sessionNameDisplay) {

            sessionNameDisplay.textContent =
                title || 'هیچ جلسه‌ای انتخاب نشده است';

        }


        // ==========================================
        // کلاس مجازی
        // ==========================================

        const majaziSessionBtn =
            document.getElementById('majaziSessionBtn');

        if (majaziSessionBtn) {

            if (
                majaziUrl &&
                majaziUrl.trim() !== ''
            ) {

                majaziSessionBtn.href =
                    majaziUrl;

                majaziSessionBtn.style.display =
                    'inline-flex';

            } else {

                majaziSessionBtn.href =
                    '#';

                majaziSessionBtn.style.display =
                    'none';

            }

        }


        // ==========================================
        // نمایش PDF
        // ==========================================

        const pdfViewer =
            document.getElementById('pdfViewer');

        if (pdfViewer) {

            if (pdfUrl) {

                const fullUrl =
                    pdfUrl.startsWith('http')
                        ? pdfUrl
                        : '/files/session' + pdfUrl;


                pdfViewer.outerHTML = `

                    <object
                        id="pdfViewer"
                        data="${fullUrl}"
                        type="application/pdf"
                        width="100%"
                        height="550px">

                        <object
                            width="100%"
                            height="550"
                            data="https://docs.google.com/gview?embedded=true&url=${fullUrl}">
                        </object>

                    </object>

                `;

            } else {

                pdfViewer.outerHTML = `

                    <div
                        id="pdfViewer"
                        class="text-center p-5">

                        <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>

                        <p class="text-muted">
                            هیچ فایلی برای این جلسه آپلود نشده است
                        </p>

                    </div>

                `;

            }

        }


        // ==========================================
        // دکمه باز کردن PDF
        // ==========================================

        const pdfOpenBtn =
            document.getElementById('pdfOpenBtn');

        if (pdfOpenBtn) {

            if (pdfUrl) {

                const fullUrl =
                    pdfUrl.startsWith('http')
                        ? pdfUrl
                        : '/files/session' + pdfUrl;

                pdfOpenBtn.setAttribute(
                    'href',
                    fullUrl
                );

                pdfOpenBtn.style.display =
                    'inline-flex';

            } else {

                pdfOpenBtn.style.display =
                    'none';

            }

        }


        // ==========================================
        // توضیحات جلسه
        // ==========================================

        const sessionDescription =
            document.getElementById('sessionDescription');

        const collapsibleSection =
            document.querySelector('.collapsible-section');


        if (
            sessionDescription &&
            collapsibleSection
        ) {

            const hasValidDescription =
                description &&
                description.trim() !== '';


            if (hasValidDescription) {

                sessionDescription.innerHTML =
                    `<p>${description}</p>`;

                collapsibleSection.style.display =
                    'block';

            } else {

                sessionDescription.innerHTML =
                    '<p class="text-muted">هیچ توضیحی برای این جلسه ثبت نشده است</p>';

                collapsibleSection.style.display =
                    'none';

            }

        }


        // ==========================================
        // دکمه سوال
        // ==========================================

        const questionBtn =
            document.getElementById('questionBtn');

        if (questionBtn) {

            if (canQuestion) {

                questionBtn.setAttribute(
                    'href',
                    `/student/questions/create/${sessionId}`
                );

                questionBtn.style.display =
                    'inline-flex';

            } else {

                questionBtn.style.display =
                    'none';

            }

        }


        // ==========================================
        // دکمه تکلیف
        // ==========================================

        const homeworkBtn =
            document.getElementById('homeworkBtn');

        if (homeworkBtn) {

            if (canHomework) {

                homeworkBtn.setAttribute(
                    'href',
                    `/student/exercise/show/${sessionId}`
                );

                homeworkBtn.style.display =
                    'inline-flex';

            } else {

                homeworkBtn.style.display =
                    'none';

            }

        }


        // ==========================================
        // دکمه گزارش
        // ==========================================

        const reportBtn =
            document.getElementById('reportBtn');

        if (reportBtn) {

            if (canReport) {

                reportBtn.setAttribute(
                    'href',
                    `/student/discussion/create/${sessionId}`
                );

                reportBtn.style.display =
                    'inline-flex';

            } else {

                reportBtn.style.display =
                    'none';

            }

        }

    }


    // ==========================================
    // بعد از بارگذاری صفحه
    // ==========================================

    document.addEventListener(
        'DOMContentLoaded',
        function () {


            // ==========================================
            // تنظیم جلسه اول
            // ==========================================

            const firstSession =
                document.querySelector(
                    '.session-item.active'
                );


            if (firstSession) {

                const sessionId =
                    firstSession.dataset.session;

                const canQuestion =
                    firstSession.dataset.canQuestion === 'true';

                const canHomework =
                    firstSession.dataset.canHomework === 'true';

                const canReport =
                    firstSession.dataset.canReport === 'true';


                // ------------------------------
                // سوال
                // ------------------------------

                const questionBtn =
                    document.getElementById('questionBtn');

                if (questionBtn) {

                    if (canQuestion) {

                        questionBtn.setAttribute(
                            'href',
                            `/student/questions/create/${sessionId}`
                        );

                        questionBtn.style.display =
                            'inline-flex';

                    } else {

                        questionBtn.style.display =
                            'none';

                    }

                }


                // ------------------------------
                // تکلیف
                // ------------------------------

                const homeworkBtn =
                    document.getElementById('homeworkBtn');

                if (homeworkBtn) {

                    if (canHomework) {

                        homeworkBtn.setAttribute(
                            'href',
                            `/student/exercise/show/${sessionId}`
                        );

                        homeworkBtn.style.display =
                            'inline-flex';

                    } else {

                        homeworkBtn.style.display =
                            'none';

                    }

                }


                // ------------------------------
                // گزارش
                // ------------------------------

                const reportBtn =
                    document.getElementById('reportBtn');

                if (reportBtn) {

                    if (canReport) {

                        reportBtn.setAttribute(
                            'href',
                            `/student/discussion/create/${sessionId}`
                        );

                        reportBtn.style.display =
                            'inline-flex';

                    } else {

                        reportBtn.style.display =
                            'none';

                    }

                }

            }


            // ==========================================
            // Collapsible
            // ==========================================

            const collapsibleHeader =
                document.querySelector(
                    '.collapsible-header'
                );


            if (collapsibleHeader) {

                collapsibleHeader.addEventListener(
                    'click',
                    function () {

                        const body =
                            this.nextElementSibling;

                        const icon =
                            this.querySelector(
                                '.expand-icon'
                            );


                        if (body) {

                            if (
                                body.style.display === 'none' ||
                                body.style.display === ''
                            ) {

                                body.style.display =
                                    'block';

                                if (icon) {

                                    icon.style.transform =
                                        'rotate(180deg)';

                                }

                            } else {

                                body.style.display =
                                    'none';

                                if (icon) {

                                    icon.style.transform =
                                        'rotate(0deg)';

                                }

                            }

                        }

                    }
                );

            }


            // ==========================================
            // تایمر شمارش معکوس
            // ==========================================

            const timerDisplay =
                document.getElementById(
                    'timerDisplay'
                );


            if (timerDisplay) {

                const unlockStr =
                    timerDisplay.dataset.unlock;


                if (
                    !unlockStr ||
                    unlockStr.trim() === ''
                ) {

                    timerDisplay.textContent =
                        'زمان نامشخص';

                    return;

                }


                const unlockTime =
                    new Date(unlockStr).getTime();


                if (isNaN(unlockTime)) {

                    timerDisplay.textContent =
                        'خطا در تاریخ';

                    return;

                }


                function updateTimer() {

                    const now =
                        Date.now();

                    const diff =
                        unlockTime - now;


                    if (diff <= 0) {

                        timerDisplay.textContent =
                            'هم‌اکنون باز شد!';

                        return;

                    }


                    const hours =
                        Math.floor(
                            diff /
                            (1000 * 60 * 60)
                        );


                    const minutes =
                        Math.floor(
                            (
                                diff %
                                (1000 * 60 * 60)
                            ) /
                            (1000 * 60)
                        );


                    const seconds =
                        Math.floor(
                            (
                                diff %
                                (1000 * 60)
                            ) /
                            1000
                        );


                    timerDisplay.textContent =
                        String(hours).padStart(2, '0') +
                        ':' +
                        String(minutes).padStart(2, '0') +
                        ':' +
                        String(seconds).padStart(2, '0');

                }


                updateTimer();

                setInterval(
                    updateTimer,
                    1000
                );

            }

        }
    );

</script>

@endsection
