@extends('layout.master')

@section('title')
    ملیسان | مدیریت درس
@endsection

@section('head')
    <link rel="stylesheet" href="{{asset('css/style-course.css')}}">
    <link rel="stylesheet" href="{{asset('css/badge.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">

    <style>
        .collapsible-body {
            display: none;
        }

        .collapsible-body.open {
            display: block;
        }

        .expand-icon.rotated {
            transform: rotate(180deg);
        }

        .content-section {
            background: #f0f7ff;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 16px;
            border-right: 4px solid #1e6f9f;
        }

        .content-section .content-header {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #1e6f9f;
            margin-bottom: 8px;
        }

        .content-section .content-header i {
            font-size: 18px;
        }

        .content-section .content-body {
            font-size: 14px;
            line-height: 2;
            color: #1a2332;
            padding-right: 10px;
        }

        .content-section .content-body ul {
            padding-right: 20px;
            margin: 8px 0;
        }

        .content-section .content-body ul li {
            margin-bottom: 4px;
        }

        .content-frame-body .form-divider {
            border-top: 1px dashed #dce3ec;
            margin: 16px 0;
            position: relative;
        }

        .content-frame-body .form-divider span {
            background: #f8fafc;
            padding: 0 12px;
            position: absolute;
            right: 12px;
            top: -10px;
            font-size: 12px;
            color: #6b7a8f;
        }

        .session-content-header .info-badges {
            flex-wrap: wrap;
            gap: 8px;
        }

        .info-badge.plan-badge {
            background: #e8f5e9;
            border-color: #4caf50;
        }

        .info-badge.plan-badge .badge-icon i {
            color: #4caf50;
        }

        /* استایل باکس فعالیت‌های دانشجو */
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
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.6);
            text-decoration: none;
        }

        .student-activities-section .activity-icons a:hover {
            background: #ffffff;
            color: #1e6f9f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 111, 159, 0.15);
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

        .session-action-buttons {
            display: none;
        }
    </style>
@endsection

@section('mohtava')

    <div class="course-detail-container">

        {{-- ========================================== --}}
        {{-- نوار بالای درس --}}
        {{-- ========================================== --}}
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

            <div>

                <a href="{{ route('courses.setting', $course->id) }}"
                   class="action-btn settings-btn">
                    <i class="fas fa-cog"></i>
                </a>

                <a href="{{ route('courses') }}"
                   class="back-action-btn back-btn">
                    <i class="fas fa-arrow-right"></i>
                </a>

            </div>

        </div>


        {{-- ========================================== --}}
        {{-- چیپ‌های مدیریت درس --}}
        {{-- ========================================== --}}
        <div class="course-chips">

            <a href="{{ route('activities', $course->id) }}"
               class="chip-item">
                <i class="fas fa-eye"></i>
                پایش دانشجویان
            </a>

            <a href="{{ route('studentActivities', $course) }}"
               class="chip-item">
                <i class="fas fa-tasks"></i>
                فعالیت های دانشجویان
            </a>

            <a href="{{ route('teacher.reports.list', $course->id) }}"
               class="chip-item">
                <i class="fas fa-file-alt"></i>
                لیست گزارش دانشجویان
            </a>

            <a href="{{ route('gradesList', $course->id) }}"
               class="chip-item">
                <i class="fas fa-star"></i>
                نمرات دانشجویان
            </a>

            <a href="{{ route('studentsList', $course->id) }}"
               class="chip-item">
                <i class="fas fa-user-graduate"></i>
                مشخصات دانشجویان ({{ $course->students->count() ?? 0 }})
            </a>

            <a href="{{ route('teacher.question.reports.list', $course->id) }}"
               class="chip-item">
                <i class="fas fa-flag" style="color:#f44336;"></i>
                ایراد سوال‌ها ({{ $reportCount ?? 0 }})
            </a>

            <a href="{{ route('azmon.list', $course->id) }}"
               class="chip-item">
                <i class="fas fa-pencil-alt"></i>
                تعریف آزمون
            </a>

            <a href="{{ route('surveys.index', $course->id) }}"
               class="chip-item">
                <i class="fas fa-poll"></i>
                نظرسنجی
            </a>

            <a href="{{ route('exercises.correction', $course->id) }}"
               class="chip-item">
                <i class="fas fa-check-double"></i>
                تصحیح تکالیف
            </a>

            <a href="{{ route('question.bank', $course->id) }}"
               class="chip-item">
                <i class="fas fa-database"></i>
                بانک سوالات
            </a>

        </div>


        {{-- ========================================== --}}
        {{-- جلسات --}}
        {{-- ========================================== --}}
        <div class="sessions-section">

            {{-- ========================================== --}}
            {{-- Sidebar جلسات --}}
            {{-- ========================================== --}}
            <div class="sessions-sidebar">

                <div class="sessions-header">

                    <h5>
                        جلسه های ارائه شده
                    </h5>

                    <button
                        class="add-session-btn"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="ایجاد جلسه جدید"
                        onclick="openModal('create')">

                        <i class="fas fa-plus"></i>

                    </button>

                </div>


                <div class="sessions-list">

                    @forelse($sessions as $session)

                        <a
                            href="#"
                            class="session-item {{ $loop->first ? 'active' : '' }}"
                            data-session="{{ $session->id }}"
                            data-pdf="{{ $session->file ?? '' }}"
                            data-title="{{ addslashes($session->name) }}"
                            data-number="جلسه {{ $session->number }}"
                            data-description="{{ addslashes($session->text ?? '') }}"
                            data-lessonplan="{{ addslashes($session->lesson_plan ?? '') }}"
                            data-majazi="{{ e($session->majazi ?? '') }}"
                            onclick="changeSessionFromData(this)"
                        >

                            <span class="session-check">
                                <i class="fas fa-check-circle"></i>
                            </span>

                            <span class="session-title">
                                {{ $session->name }}
                            </span>

                            <small class="session-number">
                                (جلسه {{ $session->number }})
                            </small>

                            <span class="session-actions">

                                <button
                                    class="action-btn-mini"
                                    onclick="event.stopPropagation(); openModal('edit', {{ $session->id }})"
                                    title="ویرایش">

                                    <i class="fas fa-edit"></i>

                                </button>

                                <button
                                    class="action-btn-mini danger"
                                    onclick="event.stopPropagation(); deleteSession({{ $session->id }})"
                                    title="حذف">

                                    <i class="fas fa-trash-alt"></i>

                                </button>

                            </span>

                        </a>

                    @empty

                        <div class="alert alert-warning m-3">

                            <i class="fas fa-info-circle"></i>

                            این دوره هنوز جلسه‌ای ندارد

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- ========================================== --}}
            {{-- محتوای جلسه --}}
            {{-- ========================================== --}}
            <div class="session-content">

                {{-- ========================================== --}}
                {{-- Header جلسه --}}
                {{-- ========================================== --}}
                <div class="session-content-header">

                    <div class="session-title-display">

                        <div class="info-badges">

                            <div class="info-badge session-badge">

                                <span class="badge-icon">
                                    <i class="fas fa-hashtag"></i>
                                </span>

                                <span class="badge-label">
                                    جلسه:
                                </span>

                                <span
                                    class="badge-value"
                                    id="sessionNumberDisplay">

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

                                <span class="badge-label">
                                    موضوع:
                                </span>

                                <span
                                    class="badge-value"
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

                        </div>

                    </div>

                    <div class="session-action-buttons"></div>

                </div>


                {{-- ========================================== --}}
                {{-- فعالیت‌های دانشجو --}}
                {{-- ========================================== --}}
                <div class="student-activities-section">

                    <div class="activity-header">

                        <div class="header-left">

                            <i class="fas fa-users"></i>

                            <span>
                                فعالیت‌های دانشجو
                            </span>

                        </div>

                        <div class="activity-icons">

                            <a
                                href="#"
                                id="questionTeacherBtn"
                                data-tooltip="ثبت سوال">

                                <i class="fas fa-question-circle"></i>

                            </a>

                            <a
                                href="#"
                                id="homeworkTeacherBtn"
                                data-tooltip="مدیریت تکالیف">

                                <i class="fas fa-file-alt"></i>

                            </a>

                            <a
                                href="#"
                                onclick="openProfExModal()"
                                data-tooltip="مدیریت گزارش">

                                <i class="fas fa-list-ul"></i>

                            </a>

                        </div>

                    </div>

                </div>


                {{-- ========================================== --}}
                {{-- طرح درس --}}
                {{-- lesson_plan --}}
                {{-- ========================================== --}}
                <div class="session-description">

                    <div class="collapsible-section">

                        <div class="collapsible-header">

                            <i class="fas fa-tasks"></i>

                            طرح درس

                            <i class="fas fa-chevron-down expand-icon"></i>

                        </div>


                        <div
                            class="collapsible-body"
                            id="sessionLessonPlan">

                            @if(
                                $sessions->isNotEmpty() &&
                                $sessions->first()->lesson_plan
                            )

                                {!! $sessions->first()->lesson_plan !!}

                            @else

                                <p class="text-muted">
                                    هیچ طرح درسی برای این جلسه ثبت نشده است
                                </p>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- ========================================== --}}
                {{-- محتوای درس --}}
                {{-- text --}}
                {{-- قبل از PDF --}}
                {{-- ========================================== --}}
                <div class="session-text-section">

                    <div class="collapsible-section">

<div class="collapsible-header">

    <i class="fas fa-book"></i>

    محتوای درس

    <div style="margin-right: auto;">

        <a
            href="javascript:void(0);"
            id="majaziSessionBtn"
            class="info-badge majazi-badge text-start"
            target="_blank"
            rel="noopener noreferrer"
            style="display: {{ $sessions->isNotEmpty() && !empty($sessions->first()->majazi) ? 'inline-flex' : 'none' }}; text-decoration:none; cursor:pointer;"
        >

            <span class="badge-icon">
                <i class="fas fa-video"></i>
            </span>

            <span class="badge-value">
                کلاس مجازی
            </span>

        </a>

        <i class="fas fa-chevron-down expand-icon">
        </i>

    </div>

</div>
                        <div
                            class="collapsible-body"
                            id="sessionText">

                            @if(
                                $sessions->isNotEmpty() &&
                                $sessions->first()->text
                            )

                                {!! $sessions->first()->text !!}

                            @else

                                <p class="text-muted">
                                    هیچ محتوایی برای این جلسه ثبت نشده است
                                </p>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- ========================================== --}}
                {{-- PDF --}}
                {{-- ========================================== --}}
                <div class="session-pdf-container">

                    <div class="collapsible-section">

                        <div class="collapsible-header p-0 px-2">


                            <div
                                class="pdf-toolbar d-flex justify-content-between align-items-center w-100">

                                <div class="w-100">

                                    <span class="px-2">
                                        پیوست (جزوه، کتاب، پاورپوینت و...)
                                    </span>
<a
    href="#"
    id="pdfOpenBtn"
    class="pdf-open-btn"
    title="باز کردن سند پیوست شده در صفحه جدید"
    target="_blank"
    rel="noopener noreferrer"
    style="float: left; {{ $sessions->isNotEmpty() && $sessions->first()->file ? 'display:inline-flex;' : 'display:none;' }}"
>
    <i class="fas fa-file-pdf" style="color: black"></i>
</a>

                                </div>


                            </div>

                            <i class="fas fa-chevron-down expand-icon"></i>

                        </div>


                        <div class="collapsible-body">

                            <div class="pdf-viewer">

                                @if(
                                    $sessions->isNotEmpty() &&
                                    $sessions->first()->file
                                )

                                    <object
                                        id="pdfViewer"
                                        data="/files/session{{ $sessions->first()->file }}"
                                        type="application/pdf"
                                        width="100%"
                                        height="550px"
                                    >

                                        <object
                                            width="100%"
                                            height="550"
                                            data="/files/session{{ $sessions->first()->file }}">
                                        </object>

                                    </object>

                                @else

                                    <div class="text-center p-5">

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

        </div>

    </div>


    {{-- ========================================== --}}
    {{-- MODAL راهنمای ارسال گزارش --}}
    {{-- ========================================== --}}
    <div class="modal-overlay" id="profExModal">

        <div class="modal-box" style="max-width: 700px;">

            <div class="modal-header">

                <h4>

                    <i
                        class="fas fa-file-alt"
                        style="color:#1e6f9f;">
                    </i>

                    راهنمای ارسال گزارش

                </h4>

                <button
                    class="modal-close-btn"
                    onclick="closeProfExModal()">

                    <i class="fas fa-times"></i>

                </button>

            </div>


            <div class="modal-body">

                <div style="padding:10px 0;">

                    <div
                        style="background:#f8fafc;border-radius:12px;padding:20px;border-right:4px solid #1e6f9f;margin-bottom:16px;">

                        <p
                            style="font-size:14px;line-height:2;color:#1e6f9f;margin:0;font-weight:600;">

                            <i
                                class="fas fa-quote-right"
                                style="margin-left:6px;">
                            </i>

                            متن راهنما:

                        </p>

                        <div
                            style="font-size:14px;line-height:2;color:#1a2332;margin-top:8px;padding:12px 16px;background:#fff;border-radius:8px;">

                            <span style="color:#6b7a8f;">

                                از دانشجو بخواهید گزارشی برای این جلسه تهیه کند.
                                این گزارش می تواند شامل موارد زیر باشد:

                                - تهیه یک طرح درسی برای مبحث ارائه شده

                                - نوشتن خلاصه ای از مهمترین موضوعات تدریس شده در این جلسه

                                - هر گونه نکته یا پیشنهاد تکمیلی که به بهبود یادگیری کمک کند.

                                توجه: در صورت عدم تکمیل این بخش، توضیحات پیش فرض ثبت شده در تنظیمات به دانشجو نمایش داده خواهد شد.

                            </span>

                        </div>

                    </div>


                    <div
                        style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;padding-bottom:16px;border-bottom:2px solid #f0f4f9;margin-bottom:16px;">

                        <button
                            class="btn-settings"
                            onclick="openEditReportModal()"
                            style="background:linear-gradient(135deg,#ff9800,#e65100);">

                            <i class="fas fa-edit"></i>

                            هدایت و راهنمایی دانشجو

                        </button>

                        <button
                            class="btn-close-modal"
                            onclick="closeProfExModal()">

                            <i class="fas fa-times"></i>

                            بستن

                        </button>

                    </div>


                    <div
                        style="background:#e3f2fd;border-radius:12px;padding:16px 20px;font-size:13px;color:#1a2332;">

                        <i
                            class="fas fa-info-circle"
                            style="color:#1e6f9f;">
                        </i>

                        <strong>توجه:</strong>

                        در صورت عدم تکمیل این بخش، توضیحات پیش فرض ثبت شده در تنظیمات به دانشجو نمایش داده خواهد شد.

                    </div>


                    <div
                        style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;padding-top:16px;">

                        <a
                            href="{{ route('courses.setting', $course->id) }}#activity-settings"
                            class="btn-settings">

                            <i class="fas fa-cog"></i>

                            رفتن به تنظیمات درس

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================== --}}
    {{-- مودال ویرایش توضیحات ارسال گزارش --}}
    {{-- ========================================== --}}
    <div class="modal-overlay" id="editReportModal">

        <div class="modal-box" style="max-width: 700px;">

            <div class="modal-header">

                <h4>

                    <i
                        class="fas fa-edit"
                        style="color:#ff9800;">
                    </i>

                    ویرایش متن راهنمای ارسال گزارش

                </h4>

                <button
                    class="modal-close-btn"
                    onclick="closeEditReportModal()">

                    <i class="fas fa-times"></i>

                </button>

            </div>


            <div class="modal-body">

                <form id="reportDescForm">

                    @csrf

                    <input
                        type="hidden"
                        name="course_id"
                        value="{{ $course->id }}">


                    <div class="form-group">

                        <label for="reportDescEditor">

                            متن راهنما

                            <span class="required">
                                *
                            </span>

                        </label>

                        <textarea
                            class="jodit-editor"
                            id="reportDescEditor"
                            name="description"
                            placeholder="متن راهنمای ارسال گزارش را وارد کنید..."
                        ></textarea>

                    </div>


                    <div
                        style="display:flex;gap:12px;flex-wrap:wrap;padding-top:16px;border-top:2px solid #f0f4f9;margin-top:10px;">

                        <button
                            type="submit"
                            class="btn-settings"
                            id="saveReportDescBtn"
                            style="background:linear-gradient(135deg,#4caf50,#388e3c);">

                            <i class="fas fa-save"></i>

                            ذخیره تغییرات

                        </button>


                        <button
                            type="button"
                            class="btn-close-modal"
                            onclick="closeEditReportModal()">

                            <i class="fas fa-times"></i>

                            انصراف

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- ========================================== --}}
    {{-- MODAL ایجاد / ویرایش جلسه --}}
    {{-- ========================================== --}}
    <div class="modal-overlay" id="sessionModal">

        <div class="modal-box">

            <div class="modal-header">

                <h4>

                    <i class="fas" id="modalIcon"></i>

                    <span id="modalTitle">
                        ایجاد جلسه جدید
                    </span>

                </h4>

                <button
                    class="modal-close-btn"
                    onclick="closeModal()">

                    <i class="fas fa-times"></i>

                </button>

            </div>


            <form
                class="modal-form pt-0"
                id="sessionForm"
                method="POST"
                enctype="multipart/form-data"
                action="{{ route('sessions.store', $course->id) }}"
            >

                @csrf

                <input
                    type="hidden"
                    name="session_id"
                    id="sessionId"
                    value="">

                <input
                    type="hidden"
                    name="course_id"
                    value="{{ $course->id }}">


                <div class="form-group" hidden>

                    <label for="modalNumber">
                        شماره جلسه
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        name="number"
                        id="modalNumber"
                        value="{{ old('number', $sessions->count() + 1) }}"
                        required>

                    @error('number')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                <div class="form-group">

                    <label for="modalName">

                        عنوان جلسه

                        <span class="required">
                            *
                        </span>

                    </label>

                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        name="name"
                        id="modalName"
                        value="{{ old('name') }}"
                        placeholder="عنوان جلسه را وارد کنید"
                        required>

                    @error('name')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                <div class="form-group">

                    <label>

                        <i
                            class="fas fa-tasks"
                            style="color:#4caf50;">
                        </i>

                        طرح درس (اختیاری)

                    </label>

                    <textarea
                        class="jodit-editor @error('lesson_plan') is-invalid @enderror"
                        name="lesson_plan"
                        id="modalLessonPlan"
                        placeholder="مختصری از اهداف آموزشی این جلسه و مطالبی که دانشجو خواهد آموخت یا پس از مطالعهٔ درس قادر به توضیح آن‌ها خواهد بود، به‌صورت فهرست‌وار بیان کنید."
                    >{{ old('lesson_plan') }}</textarea>

                    <small
                        style="color:#6b7a8f;font-size:12px;display:block;margin-top:5px;">

                        <i class="fas fa-info-circle"></i>

                        طرح درس شامل اهداف، سرفصل‌ها، فعالیت‌ها و تکالیف جلسه است.

                    </small>

                    @error('lesson_plan')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                {{-- ========================================== --}}
                {{-- فریم محتوای آموزشی --}}
                {{-- ========================================== --}}
                <div class="content-frame">

                    <div class="content-frame-title">

                        <i class="fas fa-graduation-cap"></i>

                        محتوای آموزشی جلسه
                        (انتخاب حداقل یک گزینه الزامی است.)

                    </div>


                    <div class="content-frame-body">

                        {{-- متن درس --}}

                        <div class="form-group">

                            <label>
                                محتوای درس (اختیاری)
                            </label>

                            <textarea
                                class="jodit-editor @error('text') is-invalid @enderror"
                                name="text"
                                id="modalEditor"
                                placeholder="محتوای آموزشی خود را می‌توانید به‌صورت متنی در این بخش بنویسید یا کپی کنید. در صورت وجود عکس یا نمودار، می‌توانید آن‌ها را نیز در همین بخش و درون متن قرار دهید."
                            >{{ old('text') }}</textarea>

                            @error('text')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        {{-- لینک درس --}}

                        <div class="form-row">

                            <div class="form-group">

                                <label for="modalLink">

                                    لینک محتوای درس (اختیاری)

                                    <i
                                        class="fas fa-question-circle text-muted"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="اگر فایل محتوای جلسه درس را در سایت خود یا فضاهای ابری نظیر گوگل‌درایو یا دراپ‌باکس قرار داده‌اید و دسترسی عمومی به آن‌ها فراهم است، می‌توانید لینک آن‌ را در این قسمت درج کنید تا دانشجو بتواند مستقیماً به آن‌ دسترسی داشته باشد."
                                        style="margin-right:6px;cursor:help;">
                                    </i>

                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('link') is-invalid @enderror"
                                    name="link"
                                    id="modalLink"
                                    value="{{ old('link') }}"
                                    placeholder="https://example.com">

                                @error('link')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>


                            <div class="form-group">

                                <label for="modalMajazi">
                                    لینک فیلم ضبط شده کلاس مجازی (اختیاری)
                                </label>

                                <input
                                    type="text"
                                    class="form-control @error('majazi') is-invalid @enderror"
                                    name="majazi"
                                    id="modalMajazi"
                                    value="{{ old('majazi') }}"
                                    placeholder="https://example.com">

                                @error('majazi')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- لینک آپارات --}}

                        <div class="form-group">

                            <label for="modalAparat">
                                لینک آپارات (اختیاری)
                            </label>

                            <input
                                type="text"
                                class="form-control @error('aparat') is-invalid @enderror"
                                name="aparat"
                                id="modalAparat"
                                value="{{ old('aparat') }}"
                                placeholder="کد اسکریپت آپارات را به همراه iframe یا embed کپی کنید">

                            @error('aparat')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        {{-- آپلود فایل --}}

                        <div class="form-group">

                            <label>
                                بارگذاری محتوای درس (اختیاری)
                            </label>

                            <div class="file-upload-wrapper">

                                <input
                                    type="file"
                                    id="modalFileUpload"
                                    name="file"
                                    class="file-upload-input @error('file') is-invalid @enderror"
                                    accept=".pdf,.doc,.docx,.ppt,.pptx">

                                <label
                                    for="modalFileUpload"
                                    class="file-upload-label">

                                    <i class="fas fa-cloud-upload-alt"></i>

                                    <span>
                                        انتخاب فایل
                                    </span>

                                </label>

                                <span
                                    class="file-name"
                                    id="modalFileName">

                                    هیچ فایلی انتخاب نشده است

                                </span>

                            </div>


                            <small
                                style="color:#6b7a8f;font-size:12px;display:block;margin-top:5px;">

                                فرمت‌های مجاز:
                                PDF، Word، PowerPoint
                                | حداکثر حجم: 20 مگابایت

                            </small>


                            @error('file')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror


                            <div
                                id="modalExistingFile"
                                style="display:none;"
                                class="existing-file">

                                <div class="file-info">

                                    <i class="fas fa-file-pdf"></i>

                                    <span id="modalExistingFileName">
                                        فایل موجود
                                    </span>

                                </div>


                                <button
                                    type="button"
                                    class="btn-sm btn-sm-danger"
                                    onclick="deleteExistingFile()">

                                    <i class="fas fa-trash-alt"></i>

                                    حذف

                                </button>


                                <a
                                    href="#"
                                    id="modalExistingFileLink"
                                    class="btn-sm btn-sm-primary"
                                    target="_blank">

                                    <i class="fas fa-eye"></i>

                                    مشاهده

                                </a>

                            </div>

                        </div>


                        @if($errors->has('content'))

                            <div class="alert alert-danger mt-2">

                                <i class="fas fa-exclamation-circle"></i>

                                {{ $errors->first('content') }}

                            </div>

                        @endif

                    </div>

                </div>


                <div class="form-group">

                    <label class="checkbox-label">

                        <input
                            type="checkbox"
                            name="active"
                            id="modalActive"
                            {{ old('active', true) ? 'checked' : '' }}>

                        <span
                            style="color:#1e6f9f;font-weight:600;">

                            درس به دانشجو نشان داده شود؟

                        </span>

                    </label>

                </div>


                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn-submit btn-primary"
                        id="modalSubmitBtn">

                        <i class="fas fa-save"></i>

                        <span id="modalSubmitText">
                            ایجاد جلسه
                        </span>

                    </button>


                    <button
                        type="button"
                        class="btn-submit btn-outline"
                        onclick="closeModal()">

                        <i class="fas fa-times"></i>

                        انصراف

                    </button>


                    <button
                        type="button"
                        class="btn-submit btn-danger"
                        id="modalDeleteBtn"
                        style="display:none;"
                        onclick="deleteSessionFromModal()">

                        <i class="fas fa-trash-alt"></i>

                        حذف جلسه

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection


@section('js')

<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>

    // ==========================================
    // متغیرهای سراسری
    // ==========================================

    let currentSessionId = '{{ $sessions->first()->id ?? "" }}';

    let currentPdfUrl = '{{ $sessions->first()->file ?? "" }}';

    let currentSessionTitle =
        @json($sessions->first()->name ?? '');

    let currentSessionNumber = 'جلسه {{ $sessions->first()->number ?? "" }}';

    let currentMajaziUrl =
        @json($sessions->first()->majazi ?? '');
        
    let currentLessonPlan = '';

    let joditEditor = null;

    let joditLessonPlan = null;

    let modalMode = 'create';

    let currentEditId = null;

    let reportJoditEditor = null;

    let currentReportDesc = '';

    const courseId = '{{ $course->id }}';


    // ==========================================
    // Collapsible ها
    // ==========================================

    document.addEventListener('DOMContentLoaded', function () {

        const headers =
            document.querySelectorAll('.collapsible-header');

        headers.forEach(function (header) {

            header.addEventListener('click', function () {

                const body =
                    this.nextElementSibling;

                const icon =
                    this.querySelector('.expand-icon');

                if (!body) {
                    return;
                }

                body.classList.toggle('open');

                if (icon) {
                    icon.classList.toggle('rotated');
                }

            });

        });

    });


    // ==========================================
    // باز کردن مودال جلسه
    // ==========================================

    function openModal(mode, sessionId = null) {

        modalMode = mode;

        const modal =
            document.getElementById('sessionModal');

        const form =
            document.getElementById('sessionForm');

        const title =
            document.getElementById('modalTitle');

        const icon =
            document.getElementById('modalIcon');

        const submitText =
            document.getElementById('modalSubmitText');

        const deleteBtn =
            document.getElementById('modalDeleteBtn');


        if (mode === 'create') {

            title.textContent =
                'ایجاد جلسه جدید';

            icon.className =
                'fas fa-plus-circle';

            submitText.textContent =
                'ایجاد جلسه';

            deleteBtn.style.display =
                'none';


            form.action =
                '{{ route("sessions.store", $course->id) }}';

            form.method =
                'POST';


            let methodInput =
                form.querySelector('input[name="_method"]');

            if (methodInput) {
                methodInput.remove();
            }


            document.getElementById('sessionId').value =
                '';

            document.getElementById('modalNumber').value =
                {{ $sessions->count() + 1 }};

            document.getElementById('modalName').value =
                '';

            document.getElementById('modalLink').value =
                '';

            document.getElementById('modalMajazi').value =
                '';

            document.getElementById('modalAparat').value =
                '';

            document.getElementById('modalActive').checked =
                true;


            if (joditEditor) {
                joditEditor.value = '';
            }

            if (joditLessonPlan) {
                joditLessonPlan.value = '';
            }


            document.getElementById('modalFileUpload').value =
                '';

            document.getElementById('modalFileName').textContent =
                'هیچ فایلی انتخاب نشده است';

            document.getElementById('modalExistingFile').style.display =
                'none';


        } else {

            title.textContent =
                'ویرایش جلسه';

            icon.className =
                'fas fa-edit';

            submitText.textContent =
                'بروزرسانی جلسه';

            deleteBtn.style.display =
                'inline-flex';

            currentEditId =
                sessionId;


            form.action =
                '{{ route("sessions.update", "") }}/' +
                sessionId;

            form.method =
                'POST';


            let methodInput =
                form.querySelector('input[name="_method"]');

            if (!methodInput) {

                methodInput =
                    document.createElement('input');

                methodInput.type =
                    'hidden';

                methodInput.name =
                    '_method';

                form.appendChild(methodInput);

            }

            methodInput.value =
                'PUT';


            fetchSessionData(sessionId);

        }


        modal.classList.add('active');

        document.body.style.overflow =
            'hidden';

    }


    // ==========================================
    // بستن مودال جلسه
    // ==========================================

    function closeModal() {

        document
            .getElementById('sessionModal')
            .classList
            .remove('active');

        document.body.style.overflow =
            '';

    }


    document
        .getElementById('sessionModal')
        .addEventListener('click', function (e) {

            if (e.target === this) {
                closeModal();
            }

        });


    // ==========================================
    // دریافت اطلاعات جلسه
    // ==========================================

    function fetchSessionData(sessionId) {

        fetch(
            '/teacher/courses/sessions/edit/' +
            sessionId
        )

        .then(response => response.json())

        .then(data => {

            if (data.success) {

                const session =
                    data.data;


                document.getElementById('sessionId').value =
                    session.id;

                document.getElementById('modalNumber').value =
                    session.number;

                document.getElementById('modalName').value =
                    session.name;

                document.getElementById('modalLink').value =
                    session.link || '';

                document.getElementById('modalMajazi').value =
                    session.majazi || '';

                document.getElementById('modalAparat').value =
                    session.aparat || '';

                document.getElementById('modalActive').checked =
                    session.active == 1;


                if (joditEditor) {
                    joditEditor.value =
                        session.text || '';
                }

                if (joditLessonPlan) {
                    joditLessonPlan.value =
                        session.lesson_plan || '';
                }


                if (session.file) {

                    document.getElementById(
                        'modalExistingFile'
                    ).style.display =
                        'flex';


                    document.getElementById(
                        'modalExistingFileName'
                    ).textContent =
                        session.file.split('/').pop();


                    document.getElementById(
                        'modalExistingFileLink'
                    ).href =
                        '/files/session' +
                        session.file;


                    document.getElementById(
                        'modalFileUpload'
                    ).value =
                        '';


                    document.getElementById(
                        'modalFileName'
                    ).textContent =
                        'فایل جدید جایگزین خواهد شد';

                } else {

                    document.getElementById(
                        'modalExistingFile'
                    ).style.display =
                        'none';

                }

            } else {

                alert(
                    'خطا در دریافت اطلاعات جلسه: ' +
                    data.message
                );

                closeModal();

            }

        })

        .catch(error => {

            console.error(
                'Error:',
                error
            );

            alert(
                'خطا در ارتباط با سرور'
            );

            closeModal();

        });

    }


    // ==========================================
    // حذف فایل
    // ==========================================

    function deleteExistingFile() {

        if (
            !confirm(
                'آیا مطمئن هستید که می‌خواهید فایل این جلسه را حذف کنید؟'
            )
        ) {
            return;
        }


        const sessionId =
            document.getElementById(
                'sessionId'
            ).value;


        fetch(
            '/teacher/courses/sessions/delete-file/' +
            sessionId,
            {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN':
                        '{{ csrf_token() }}',

                    'Content-Type':
                        'application/json'
                }
            }
        )

        .then(response => response.json())

        .then(data => {

            if (data.success) {

                document.getElementById(
                    'modalExistingFile'
                ).style.display =
                    'none';


                document.getElementById(
                    'modalFileName'
                ).textContent =
                    'فایل حذف شد';


                alert(
                    'فایل با موفقیت حذف شد'
                );

            } else {

                alert(
                    'خطا در حذف فایل: ' +
                    data.message
                );

            }

        })

        .catch(error => {

            alert(
                'خطا در ارتباط با سرور'
            );

        });

    }


    // ==========================================
    // حذف جلسه از مودال
    // ==========================================

    function deleteSessionFromModal() {

        if (
            !confirm(
                'آیا مطمئن هستید که می‌خواهید این جلسه را حذف کنید؟'
            )
        ) {
            return;
        }


        const sessionId =
            document.getElementById(
                'sessionId'
            ).value;


        deleteSession(
            sessionId
        );

    }


    // ==========================================
    // حذف جلسه
    // ==========================================

    function deleteSession(sessionId) {

        if (
            !confirm(
                'آیا مطمئن هستید که می‌خواهید این جلسه را حذف کنید؟ این عمل غیرقابل بازگشت است.'
            )
        ) {
            return;
        }


        fetch(
            '/teacher/courses/sessions/delete/' +
            sessionId,
            {
                method: 'DELETE',

                headers: {
                    'X-CSRF-TOKEN':
                        '{{ csrf_token() }}',

                    'Content-Type':
                        'application/json'
                }
            }
        )

        .then(response => response.json())

        .then(data => {

            if (data.success) {

                alert(
                    'جلسه با موفقیت حذف شد'
                );

                location.reload();

            } else {

                alert(
                    'خطا در حذف جلسه: ' +
                    data.message
                );

            }

        })

        .catch(error => {

            alert(
                'خطا در ارتباط با سرور'
            );

        });

    }


    // ==========================================
    // تغییر جلسه
    // ==========================================

    function changeSession(
        element,
        sessionId,
        pdfUrl,
        title,
        number,
        description,
        lessonPlan,
        majaziUrl
    ) {

        if (!element) {
            return;
        }


        // حذف active از جلسات

        document
            .querySelectorAll('.session-item')
            .forEach(function (item) {

                item.classList.remove(
                    'active'
                );

            });


        // فعال کردن جلسه

        element.classList.add(
            'active'
        );


        // ذخیره اطلاعات

        currentSessionId =
            sessionId;

        currentPdfUrl =
            pdfUrl;

        currentSessionTitle =
            title;

        currentSessionNumber =
            number;

        currentLessonPlan =
            lessonPlan || '';

        currentMajaziUrl =
            majaziUrl || '';


        // ==========================================
        // کلاس مجازی
        // ==========================================

        const majaziSessionBtn =
            document.getElementById('majaziSessionBtn');

        if (majaziSessionBtn) {

            const rawMajaziUrl =
                typeof majaziUrl === 'string'
                    ? majaziUrl.trim()
                    : '';

            if (rawMajaziUrl !== '') {

                let finalMajaziUrl = rawMajaziUrl;

                try {

                    // اگر URL پروتکل نداشت، HTTPS اضافه می‌کنیم
                    if (
                        !finalMajaziUrl.startsWith('http://') &&
                        !finalMajaziUrl.startsWith('https://')
                    ) {
                        finalMajaziUrl =
                            'https://' + finalMajaziUrl;
                    }

                    // URL را به صورت Absolute URL می‌سازیم
                    const absoluteUrl =
                        new URL(finalMajaziUrl);

                    majaziSessionBtn.href =
                        absoluteUrl.href;

                    majaziSessionBtn.style.display =
                        'inline-flex';

                } catch (error) {

                    console.error(
                        'Invalid virtual class URL:',
                        rawMajaziUrl,
                        error
                    );

                    majaziSessionBtn.removeAttribute('href');

                    majaziSessionBtn.style.display =
                        'none';
                }

            } else {

                majaziSessionBtn.removeAttribute('href');

                majaziSessionBtn.style.display =
                    'none';
            }
        }


        // ==========================================
        // شماره جلسه
        // ==========================================

        const sessionNumberDisplay =
            document.getElementById(
                'sessionNumberDisplay'
            );


        if (sessionNumberDisplay) {

            const numberMatch =
                number.match(/\d+/);


            sessionNumberDisplay.textContent =
                numberMatch
                    ? numberMatch[0]
                    : '-';

        }


        // ==========================================
        // عنوان جلسه
        // ==========================================

        const sessionNameDisplay =
            document.getElementById(
                'sessionNameDisplay'
            );


        if (sessionNameDisplay) {

            sessionNameDisplay.textContent =
                title ||
                'هیچ جلسه‌ای انتخاب نشده است';

        }


        // ==========================================
        // طرح درس
        // ==========================================

        const sessionLessonPlan =
            document.getElementById(
                'sessionLessonPlan'
            );


        const hasLessonPlan =
            lessonPlan &&
            lessonPlan.trim() !== '' &&
            lessonPlan.trim() !== 'null';


        if (sessionLessonPlan) {

            if (hasLessonPlan) {

                sessionLessonPlan.innerHTML =
                    lessonPlan;

            } else {

                sessionLessonPlan.innerHTML =
                    '<p class="text-muted">هیچ طرح درسی برای این جلسه ثبت نشده است</p>';

            }

        }


        // ==========================================
        // محتوای درس
        // text
        // ==========================================

        const sessionText =
            document.getElementById(
                'sessionText'
            );


        const hasText =
            description &&
            description.trim() !== '' &&
            description.trim() !== 'null';


        if (sessionText) {

            if (hasText) {

                sessionText.innerHTML =
                    description;

            } else {

                sessionText.innerHTML =
                    '<p class="text-muted">هیچ محتوایی برای این جلسه ثبت نشده است</p>';

            }

        }


        // ==========================================
        // ساخت URL فایل
        // ==========================================

        let fullPdfUrl =
            '';


        if (pdfUrl) {

            if (
                pdfUrl.startsWith('http://') ||
                pdfUrl.startsWith('https://')
            ) {

                fullPdfUrl =
                    pdfUrl;

            } else {

                fullPdfUrl =
                    '/files/session' +
                    pdfUrl;

            }

        }


        // ==========================================
        // PDF Viewer
        // ==========================================

        const pdfViewer =
            document.getElementById(
                'pdfViewer'
            );


        if (pdfViewer) {

            if (fullPdfUrl) {

                const parent =
                    pdfViewer.parentNode;


                const newObject =
                    document.createElement(
                        'object'
                    );


                newObject.id =
                    'pdfViewer';


                newObject.setAttribute(
                    'data',
                    fullPdfUrl
                );


                newObject.setAttribute(
                    'type',
                    'application/pdf'
                );


                newObject.setAttribute(
                    'width',
                    '100%'
                );


                newObject.setAttribute(
                    'height',
                    '550px'
                );


                newObject.innerHTML = `
                    <object
                        width="100%"
                        height="550"
                        data="${fullPdfUrl}">
                    </object>
                `;


                parent.replaceChild(
                    newObject,
                    pdfViewer
                );

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
            document.getElementById(
                'pdfOpenBtn'
            );


        if (pdfOpenBtn) {

            if (fullPdfUrl) {

                pdfOpenBtn.setAttribute(
                    'href',
                    fullPdfUrl
                );

                pdfOpenBtn.style.display =
                    'inline-flex';

            } else {

                pdfOpenBtn.removeAttribute(
                    'href'
                );

                pdfOpenBtn.style.display =
                    'none';

            }

        }


        // ==========================================
        // دکمه ثبت سوال
        // ==========================================

        const questionTeacherBtn =
            document.getElementById(
                'questionTeacherBtn'
            );


        if (questionTeacherBtn) {

            questionTeacherBtn.setAttribute(
                'href',
                `/teacher/questions/create/${sessionId}`
            );

        }


        // ==========================================
        // دکمه مدیریت تکالیف
        // ==========================================

        const homeworkTeacherBtn =
            document.getElementById(
                'homeworkTeacherBtn'
            );


        if (homeworkTeacherBtn) {

            homeworkTeacherBtn.setAttribute(
                'href',
                `/teacher/courses/exercises/show/${sessionId}`
            );

        }

    }


    // ==========================================
    // خواندن Data Attributes
    // ==========================================

    function changeSessionFromData(element) {

        const sessionId =
            element.dataset.session;

        const pdfUrl =
            element.dataset.pdf || '';

        const title =
            element.dataset.title || '';

        const number =
            element.dataset.number || '';

        const description =
            element.dataset.description || '';

        const lessonPlan =
            element.dataset.lessonplan || '';

        const majaziUrl =
            element.dataset.majazi || '';


        changeSession(
            element,
            sessionId,
            pdfUrl,
            title,
            number,
            description,
            lessonPlan,
            majaziUrl
        );

    }


    // ==========================================
    // مودال گزارش
    // ==========================================

    function openProfExModal() {

        document
            .getElementById('profExModal')
            .classList
            .add('active');


        document.body.style.overflow =
            'hidden';


        loadReportDescription();

    }


    function closeProfExModal() {

        document
            .getElementById('profExModal')
            .classList
            .remove('active');


        document.body.style.overflow =
            '';

    }


    document
        .getElementById('profExModal')
        .addEventListener(
            'click',
            function (e) {

                if (e.target === this) {
                    closeProfExModal();
                }

            }
        );


    // ==========================================
    // مودال ویرایش گزارش
    // ==========================================

    function openEditReportModal() {

        const modal =
            document.getElementById(
                'editReportModal'
            );


        modal.classList.add(
            'active'
        );


        document.body.style.overflow =
            'hidden';


        fetch(
            '/teacher/courses/settings/get-report-desc?course_id=' +
            courseId
        )

        .then(response => response.json())

        .then(data => {

            if (data.success) {

                currentReportDesc =
                    data.data.description || '';


                if (reportJoditEditor) {

                    reportJoditEditor.value =
                        currentReportDesc;

                }

            } else {

                alert(
                    'خطا در دریافت توضیحات: ' +
                    data.message
                );

            }

        })

        .catch(error => {

            console.error(
                'Error:',
                error
            );

            alert(
                'خطا در ارتباط با سرور'
            );

        });

    }


    function closeEditReportModal() {

        document
            .getElementById(
                'editReportModal'
            )
            .classList
            .remove('active');


        document.body.style.overflow =
            '';

    }


    document
        .getElementById(
            'editReportModal'
        )
        .addEventListener(
            'click',
            function (e) {

                if (e.target === this) {
                    closeEditReportModal();
                }

            }
        );


    function loadReportDescription() {
        // در صورت نیاز می‌توان توضیحات را از سرور دریافت کرد.
    }


    // ==========================================
    // Escape
    // ==========================================

    document.addEventListener(
        'keydown',
        function (e) {

            if (e.key === 'Escape') {

                closeModal();

                closeProfExModal();

                closeEditReportModal();

            }

        }
    );


    // ==========================================
    // مقداردهی Jodit
    // ==========================================

    document.addEventListener(
        'DOMContentLoaded',
        function () {


            // ==========================================
            // Editor محتوای درس
            // ==========================================

            const editorElement =
                document.getElementById(
                    'modalEditor'
                );


            if (editorElement) {

                joditEditor =
                    new Jodit(
                        '#modalEditor',
                        {

                            width: '100%',

                            height: 200,

                            allowResize: true,

                            allowResizeImages: true,

                            direction: 'rtl',

                            language: 'fa',

                            buttons: [
                                'source',
                                '|',
                                'undo',
                                'redo',
                                '|',
                                'bold',
                                'italic',
                                'underline',
                                'strikethrough',
                                '|',
                                'font',
                                'fontsize',
                                'brush',
                                'paragraph',
                                '|',
                                'ul',
                                'ol',
                                'outdent',
                                'indent',
                                '|',
                                'align',
                                'hr',
                                'table',
                                '|',
                                'link',
                                'unlink',
                                '|',
                                'symbols',
                                'emoticons',
                                '|',
                                'print',
                                'fullsize',
                                'preview'
                            ],

                            colors: {
                                text: [
                                    '#000000',
                                    '#ff0000',
                                    '#00ff00',
                                    '#0000ff',
                                    '#ff00ff',
                                    '#00ffff'
                                ],

                                background: [
                                    '#ffffff',
                                    '#ffff00',
                                    '#00ffff',
                                    '#ffcc99'
                                ]
                            },

                            defaultFont:
                                'Vazir, Tahoma, Arial, sans-serif',

                            defaultFontSize:
                                '14px',

                            fonts: [
                                'Vazir',
                                'Tahoma',
                                'Arial',
                                'Courier New'
                            ]

                        }
                    );


                    @if(old('text'))

                        joditEditor.value =
                            '{{ addslashes(old('text')) }}';

                    @endif

            }


            // ==========================================
            // Editor طرح درس
            // ==========================================

            const lessonPlanElement =
                document.getElementById(
                    'modalLessonPlan'
                );


            if (lessonPlanElement) {

                joditLessonPlan =
                    new Jodit(
                        '#modalLessonPlan',
                        {

                            width: '100%',

                            height: 180,

                            allowResize: true,

                            allowResizeImages: true,

                            direction: 'rtl',

                            language: 'fa',

                            buttons: [
                                'source',
                                '|',
                                'undo',
                                'redo',
                                '|',
                                'bold',
                                'italic',
                                'underline',
                                'strikethrough',
                                '|',
                                'font',
                                'fontsize',
                                'brush',
                                'paragraph',
                                '|',
                                'ul',
                                'ol',
                                'outdent',
                                'indent',
                                '|',
                                'align',
                                'hr',
                                'table',
                                '|',
                                'link',
                                'unlink',
                                '|',
                                'symbols',
                                'emoticons',
                                '|',
                                'print',
                                'fullsize',
                                'preview'
                            ],

                            colors: {
                                text: [
                                    '#000000',
                                    '#ff0000',
                                    '#00ff00',
                                    '#0000ff',
                                    '#ff00ff',
                                    '#00ffff'
                                ],

                                background: [
                                    '#ffffff',
                                    '#ffff00',
                                    '#00ffff',
                                    '#ffcc99'
                                ]
                            },

                            defaultFont:
                                'Vazir, Tahoma, Arial, sans-serif',

                            defaultFontSize:
                                '14px',

                            fonts: [
                                'Vazir',
                                'Tahoma',
                                'Arial',
                                'Courier New'
                            ]

                        }
                    );


                    @if(old('lesson_plan'))

                        joditLessonPlan.value =
                            '{{ addslashes(old('lesson_plan')) }}';

                    @endif

            }


            // ==========================================
            // Editor گزارش
            // ==========================================

            const reportEditorElement =
                document.getElementById(
                    'reportDescEditor'
                );


            if (reportEditorElement) {

                reportJoditEditor =
                    new Jodit(
                        '#reportDescEditor',
                        {

                            width: '100%',

                            height: 250,

                            allowResize: true,

                            allowResizeImages: true,

                            direction: 'rtl',

                            language: 'fa',

                            buttons: [
                                'source',
                                '|',
                                'undo',
                                'redo',
                                '|',
                                'bold',
                                'italic',
                                'underline',
                                'strikethrough',
                                '|',
                                'font',
                                'fontsize',
                                'brush',
                                'paragraph',
                                '|',
                                'ul',
                                'ol',
                                'outdent',
                                'indent',
                                '|',
                                'align',
                                'hr',
                                'table',
                                '|',
                                'link',
                                'unlink',
                                '|',
                                'symbols',
                                'emoticons',
                                '|',
                                'print',
                                'fullsize',
                                'preview'
                            ],

                            colors: {
                                text: [
                                    '#000000',
                                    '#ff0000',
                                    '#00ff00',
                                    '#0000ff',
                                    '#ff00ff',
                                    '#00ffff'
                                ],

                                background: [
                                    '#ffffff',
                                    '#ffff00',
                                    '#00ffff',
                                    '#ffcc99'
                                ]
                            },

                            defaultFont:
                                'Vazir, Tahoma, Arial, sans-serif',

                            defaultFontSize:
                                '14px',

                            fonts: [
                                'Vazir',
                                'Tahoma',
                                'Arial',
                                'Courier New'
                            ]

                        }
                    );

            }


            // ==========================================
            // نمایش نام فایل
            // ==========================================

            const fileInput =
                document.getElementById(
                    'modalFileUpload'
                );


            if (fileInput) {

                fileInput.addEventListener(
                    'change',
                    function (e) {

                        const fileName =
                            e.target.files[0]
                                ? e.target.files[0].name
                                : 'هیچ فایلی انتخاب نشده است';


                        document.getElementById(
                            'modalFileName'
                        ).textContent =
                            fileName;


                        if (e.target.files[0]) {

                            document.getElementById(
                                'modalExistingFile'
                            ).style.display =
                                'none';

                        }

                    }
                );

            }


            // ==========================================
            // جلسه اول
            // ==========================================

            const firstSession =
                document.querySelector(
                    '.session-item.active'
                );


            if (firstSession) {

                changeSessionFromData(
                    firstSession
                );

            }


            loadReportDescription();

        }

    );


    // ==========================================
    // ذخیره توضیحات گزارش
    // ==========================================

    document
        .getElementById('reportDescForm')
        .addEventListener(
            'submit',
            function (e) {

                e.preventDefault();


                const submitBtn =
                    document.getElementById(
                        'saveReportDescBtn'
                    );


                submitBtn.disabled =
                    true;


                submitBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> در حال ذخیره...';


                const description =
                    reportJoditEditor
                        ? reportJoditEditor.value
                        : document.getElementById(
                            'reportDescEditor'
                        ).value;


                fetch(
                    '/teacher/courses/settings/update-report-desc',
                    {

                        method: 'POST',

                        headers: {

                            'X-CSRF-TOKEN':
                                '{{ csrf_token() }}',

                            'Content-Type':
                                'application/json'

                        },

                        body: JSON.stringify({

                            description:
                                description,

                            course_id:
                                courseId

                        })

                    }
                )

                .then(response =>
                    response.json()
                )

                .then(data => {

                    if (data.success) {

                        alert(
                            'توضیحات با موفقیت به‌روزرسانی شد'
                        );


                        closeEditReportModal();

                    } else {

                        let errorMsg =
                            'خطا در ذخیره: ';


                        if (data.errors) {

                            const errors =
                                Object.values(
                                    data.errors
                                ).flat();


                            errorMsg +=
                                errors.join(', ');

                        } else {

                            errorMsg +=
                                data.message ||
                                'مشخص نیست';

                        }


                        alert(
                            errorMsg
                        );

                    }

                })

                .catch(error => {

                    console.error(
                        error
                    );

                    alert(
                        'خطا در ارتباط با سرور'
                    );

                })

                .finally(() => {

                    submitBtn.disabled =
                        false;


                    submitBtn.innerHTML =
                        '<i class="fas fa-save"></i> ذخیره تغییرات';

                });

            }
        );

</script>

@endsection