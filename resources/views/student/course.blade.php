@extends('layout.master')

@section('title')
    ملیسان | مدیریت درس
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/badge.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-course.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- =========================================
         استایل‌های بخش فعالیت‌های دانشجو
         (فقط استایل‌های جدید)
    ========================================== --}}
    <style>
        .student-activities-section {
            background: #f0f7ff;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 16px;
            border-right: 4px solid #1e6f9f;
        }

        .student-activities-section .activity-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            color: #1e6f9f;
            margin-bottom: 0;
        }

        .student-activities-section .activity-header .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .student-activities-section .activity-header .header-left i {
            font-size: 18px;
        }

        .student-activities-section .activity-icons {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .student-activities-section .activity-icons a {
            color: #1a2332;
            font-size: 18px;
            transition: all .2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(255,255,255,.6);
            text-decoration: none;
        }

        .student-activities-section .activity-icons a:hover {
            background: #fff;
            color: #1e6f9f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30,111,159,.15);
        }

        .student-activities-section .activity-icons a i {
            font-size: 18px;
        }

        .student-activities-section .activity-icons a .fa-question-circle {
            color: #ff9800;
        }

        .student-activities-section .activity-icons a .fa-file-alt {
            color: #4caf50;
        }

        .student-activities-section .activity-icons a .fa-list-ul {
            color: #2196f3;
        }

        .student-activities-section .activity-icons a:hover .fa-question-circle {
            color: #e65100;
        }

        .student-activities-section .activity-icons a:hover .fa-file-alt {
            color: #2e7d32;
        }

        .student-activities-section .activity-icons a:hover .fa-list-ul {
            color: #0d47a1;
        }

        .student-activities-section .activity-icons a.disabled,
        .student-activities-section .activity-icons a.hidden-btn {
            display: none !important;
        }

        /* استایل ریسپانسیو برای موبایل */
        @media (max-width: 768px) {
            .student-activities-section .activity-header {
                flex-direction: column;
                gap: 12px;
            }

            .student-activities-section .activity-icons {
                justify-content: center;
            }
        }
    </style>
@endsection

@section('mohtava')

<div class="course-detail-container">

    {{-- =========================
         اطلاعات دوره
    ========================== --}}
    <div class="course-actions-bar">
        <div class="info-badge course-badge">
            <span class="badge-icon">
                <i class="fas fa-book-open"></i>
            </span>

            <span class="badge-label">درس:</span>

            <span class="badge-value">
                {{ $course->name ?? 'عنوان درس' }}
            </span>
        </div>
    </div>


    {{-- =========================
         امکانات دوره
    ========================== --}}
    <div class="course-chips">

        @if($course->quiz == 1)
            <a href="{{ route('student.selfTest.start', $course->id) }}"
               class="chip-item">
                <i class="fas fa-star"></i>
                خودآزمایی
            </a>
        @endif

        @if($course->faaliat == 1)
            <a href="{{ route('student.my.activities', $course->id) }}"
               class="chip-item">
                <i class="fas fa-database"></i>
                فعالیت های من
            </a>
        @endif

        @if($course->pishraft == 1)
            <a href="{{ route('student.progress', $course->id) }}"
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


    {{-- =========================
         جلسات
    ========================== --}}
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

                    <a href="javascript:void(0);"
                       class="session-item {{ $loop->first ? 'active' : '' }}"

                       data-session="{{ $session->id }}"

                       data-can-question="{{ isset($session['can_question']) && $session['can_question'] ? 'true' : 'false' }}"

                       data-can-homework="{{ isset($session['can_homework']) && $session['can_homework'] ? 'true' : 'false' }}"

                       data-can-report="{{ isset($session['can_report']) && $session['can_report'] ? 'true' : 'false' }}"

                       data-pdf="{{ $session->file ?? '' }}"

                       data-title="{{ $session->name ?? '' }}"

                       data-number="جلسه {{ $session->number ?? '' }}"

                       data-description="{{ $session->text ?? '' }}"

                       data-majazi="{{ $session->majazi ?? '' }}"

                       onclick="changeSession(this)">

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


                        {{-- کلاس مجازی --}}
                        <a href="#"
                           id="majaziSessionBtn"
                           class="info-badge majazi-badge"
                           target="_blank"
                           rel="noopener noreferrer"
                           style="display: {{ $sessions->isNotEmpty() && !empty($sessions->first()->majazi) ? 'inline-flex' : 'none' }}; text-decoration:none; cursor:pointer;">

                            <span class="badge-icon">
                                <i class="fas fa-video"></i>
                            </span>

                            <span class="badge-value">
                                کلاس مجازی
                            </span>

                        </a>

                    </div>
                </div>


                {{-- دکمه‌های عملیات --}}
                <div class="session-action-buttons">
                </div>

            </div>


            {{-- =========================
                 ⭐ بخش فعالیت‌های دانشجو (اضافه شده)
            ========================== --}}
            <div class="student-activities-section">

                <div class="activity-header">

                    <div class="header-left">

                        <i class="fas fa-users"></i>

                        <span>
                            فعالیت‌های دانشجو در
                            <span id="activitySessionLabel">
                                @if($sessions->isNotEmpty())
                                    جلسه {{ $sessions->first()->number }}
                                @else
                                    جلسه جاری
                                @endif
                            </span>
                        </span>

                    </div>


                    <div class="activity-icons">

                        <a
                            href="#"
                            id="questionStudentBtn"
                            data-tooltip="ثبت سوال">

                            <i class="fas fa-question-circle"></i>

                        </a>

                        <a
                            href="#"
                            id="homeworkStudentBtn"
                            data-tooltip="ارسال تکلیف">

                            <i class="fas fa-file-alt"></i>

                        </a>

                        <a
                            href="#"
                            id="reportStudentBtn"
                            data-tooltip="ارسال گزارش">

                            <i class="fas fa-list-ul"></i>

                        </a>

                    </div>

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

                            محتوای درس

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

                            محتوای درس

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
                       target="_blank">

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


<script>

    // =========================================================
    // اطلاعات جلسه فعلی
    // =========================================================

    let currentSessionId = '';
    let currentPdfUrl = '';
    let currentSessionTitle = '';
    let currentSessionNumber = '';
    let currentDescription = '';
    let currentMajaziUrl = '';


    // =========================================================
    // تغییر جلسه
    // =========================================================

    function changeSession(element) {

        if (!element) {
            return;
        }


        // =====================================================
        // گرفتن اطلاعات از data attributes
        // =====================================================

        const sessionId =
            element.dataset.session || '';

        const pdfUrl =
            element.dataset.pdf || '';

        const title =
            element.dataset.title || '';

        const number =
            element.dataset.number || '';

        const description =
            element.dataset.description || '';

        const majaziUrl =
            element.dataset.majazi || '';


        // =====================================================
        // فعال کردن جلسه انتخاب‌شده
        // =====================================================

        document
            .querySelectorAll('.session-item')
            .forEach(item => {

                item.classList.remove('active');

            });

        element.classList.add('active');


        // =====================================================
        // ذخیره اطلاعات جلسه
        // =====================================================

        currentSessionId = sessionId;
        currentPdfUrl = pdfUrl;
        currentSessionTitle = title;
        currentSessionNumber = number;
        currentDescription = description;
        currentMajaziUrl = majaziUrl;


        // =====================================================
        // شماره جلسه
        // =====================================================

        const sessionNumberDisplay =
            document.getElementById('sessionNumberDisplay');

        if (sessionNumberDisplay) {

            const numberMatch =
                number.match(/\d+/);

            sessionNumberDisplay.textContent =
                numberMatch
                    ? numberMatch[0]
                    : '-';
        }


        // =====================================================
        // ⭐ به‌روزرسانی برچسب فعالیت‌های دانشجو
        // =====================================================

        const activitySessionLabel =
            document.getElementById('activitySessionLabel');

        if (activitySessionLabel) {

            const numberMatch =
                number.match(/\d+/);

            if (numberMatch) {

                activitySessionLabel.textContent =
                    'جلسه ' + numberMatch[0];

            } else {

                activitySessionLabel.textContent =
                    'جلسه جاری';
            }
        }


        // =====================================================
        // موضوع جلسه
        // =====================================================

        const sessionNameDisplay =
            document.getElementById('sessionNameDisplay');

        if (sessionNameDisplay) {

            sessionNameDisplay.textContent =
                title || 'هیچ جلسه‌ای انتخاب نشده است';
        }


        // =====================================================
        // کلاس مجازی
        // =====================================================

        const majaziSessionBtn =
            document.getElementById('majaziSessionBtn');

        if (majaziSessionBtn) {

            if (
                majaziUrl &&
                majaziUrl.trim() !== '' &&
                majaziUrl.trim() !== 'null'
            ) {

                majaziSessionBtn.setAttribute(
                    'href',
                    majaziUrl
                );

                majaziSessionBtn.style.display =
                    'inline-flex';

            } else {

                majaziSessionBtn.setAttribute(
                    'href',
                    '#'
                );

                majaziSessionBtn.style.display =
                    'none';
            }
        }


        // =====================================================
        // PDF Viewer
        // =====================================================

        const pdfViewer =
            document.getElementById('pdfViewer');

        if (pdfViewer) {

            if (pdfUrl) {

                let fullPdfUrl;

                if (pdfUrl.startsWith('http')) {

                    fullPdfUrl = pdfUrl;

                } else {

                    fullPdfUrl =
                        '/files/session' + pdfUrl;
                }


                pdfViewer.outerHTML = `
                    <object
                        id="pdfViewer"
                        data="${fullPdfUrl}"
                        type="application/pdf"
                        width="100%"
                        height="550px">

                        <object
                            width="100%"
                            height="550"
                            data="https://docs.google.com/gview?embedded=true&url=${encodeURIComponent(fullPdfUrl)}">
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


        // =====================================================
        // دکمه باز کردن PDF
        // =====================================================

        const pdfOpenBtn =
            document.getElementById('pdfOpenBtn');

        if (pdfOpenBtn) {

            if (pdfUrl) {

                const fullPdfUrl =
                    pdfUrl.startsWith('http')
                        ? pdfUrl
                        : '/files/session' + pdfUrl;

                pdfOpenBtn.setAttribute(
                    'href',
                    fullPdfUrl
                );

                pdfOpenBtn.style.display =
                    'inline-flex';

            } else {

                pdfOpenBtn.removeAttribute('href');

                pdfOpenBtn.style.display =
                    'none';
            }
        }


        // =====================================================
        // توضیحات جلسه
        // =====================================================

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
                description.trim() !== '' &&
                description.trim() !== 'null';


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


        // =====================================================
        // وضعیت دسترسی دکمه‌ها
        // =====================================================

        const canQuestion =
            element.dataset.canQuestion === 'true';

        const canHomework =
            element.dataset.canHomework === 'true';

        const canReport =
            element.dataset.canReport === 'true';


        // =====================================================
        // دکمه سوال (در بخش فعالیت‌های دانشجو)
        // =====================================================

        const questionStudentBtn =
            document.getElementById('questionStudentBtn');

        if (questionStudentBtn) {

            if (canQuestion) {

                questionStudentBtn.setAttribute(
                    'href',
                    `/student/questions/create/${sessionId}`
                );

                questionStudentBtn.style.display =
                    'inline-flex';

                questionStudentBtn.style.opacity =
                    '1';

                questionStudentBtn.style.pointerEvents =
                    'auto';

                questionStudentBtn.classList.remove('disabled');
                questionStudentBtn.classList.remove('hidden-btn');

            } else {

                questionStudentBtn.removeAttribute('href');

                questionStudentBtn.style.display =
                    'none';

                questionStudentBtn.classList.add('disabled');
                questionStudentBtn.classList.add('hidden-btn');
            }
        }


        // =====================================================
        // دکمه تکلیف (در بخش فعالیت‌های دانشجو)
        // =====================================================

        const homeworkStudentBtn =
            document.getElementById('homeworkStudentBtn');

        if (homeworkStudentBtn) {

            if (canHomework) {

                homeworkStudentBtn.setAttribute(
                    'href',
                    `/student/exercise/show/${sessionId}`
                );

                homeworkStudentBtn.style.display =
                    'inline-flex';

                homeworkStudentBtn.style.opacity =
                    '1';

                homeworkStudentBtn.style.pointerEvents =
                    'auto';

                homeworkStudentBtn.classList.remove('disabled');
                homeworkStudentBtn.classList.remove('hidden-btn');

            } else {

                homeworkStudentBtn.removeAttribute('href');

                homeworkStudentBtn.style.display =
                    'none';

                homeworkStudentBtn.classList.add('disabled');
                homeworkStudentBtn.classList.add('hidden-btn');
            }
        }


        // =====================================================
        // دکمه گزارش (در بخش فعالیت‌های دانشجو)
        // =====================================================

        const reportStudentBtn =
            document.getElementById('reportStudentBtn');

        if (reportStudentBtn) {

            if (canReport) {

                reportStudentBtn.setAttribute(
                    'href',
                    `/student/discussion/create/${sessionId}`
                );

                reportStudentBtn.style.display =
                    'inline-flex';

                reportStudentBtn.style.opacity =
                    '1';

                reportStudentBtn.style.pointerEvents =
                    'auto';

                reportStudentBtn.classList.remove('disabled');
                reportStudentBtn.classList.remove('hidden-btn');

            } else {

                reportStudentBtn.removeAttribute('href');

                reportStudentBtn.style.display =
                    'none';

                reportStudentBtn.classList.add('disabled');
                reportStudentBtn.classList.add('hidden-btn');
            }
        }


        // =====================================================
        // دکمه‌های بالا (همان session-action-buttons)
        // =====================================================

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

                questionBtn.style.opacity =
                    '1';

                questionBtn.style.pointerEvents =
                    'auto';

                questionBtn.classList.remove('disabled');
                questionBtn.classList.remove('hidden-btn');

            } else {

                questionBtn.removeAttribute('href');

                questionBtn.style.display =
                    'none';

                questionBtn.classList.add('disabled');
                questionBtn.classList.add('hidden-btn');
            }
        }


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

                homeworkBtn.style.opacity =
                    '1';

                homeworkBtn.style.pointerEvents =
                    'auto';

                homeworkBtn.classList.remove('disabled');
                homeworkBtn.classList.remove('hidden-btn');

            } else {

                homeworkBtn.removeAttribute('href');

                homeworkBtn.style.display =
                    'none';

                homeworkBtn.classList.add('disabled');
                homeworkBtn.classList.add('hidden-btn');
            }
        }


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

                reportBtn.style.opacity =
                    '1';

                reportBtn.style.pointerEvents =
                    'auto';

                reportBtn.classList.remove('disabled');
                reportBtn.classList.remove('hidden-btn');

            } else {

                reportBtn.removeAttribute('href');

                reportBtn.style.display =
                    'none';

                reportBtn.classList.add('disabled');
                reportBtn.classList.add('hidden-btn');
            }
        }

    }


    // =========================================================
    // بعد از لود صفحه
    // =========================================================

    document.addEventListener(
        'DOMContentLoaded',
        function () {


            // =====================================================
            // Collapsible
            // =====================================================

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


            // =====================================================
            // انتخاب جلسه اول
            // =====================================================

            const firstSession =
                document.querySelector('.session-item');

            if (firstSession) {

                changeSession(firstSession);

            }

        }
    );

</script>

@endsection