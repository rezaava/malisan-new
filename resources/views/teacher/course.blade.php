@extends('layout.master')

@section('title')
ملیسان | مدیریت درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-course.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">

<style>
    /* ===== استایل‌های مودال ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-box {
        background: #fff;
        border-radius: 20px;
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 30px;
        position: relative;
        animation: slideUp 0.3s ease;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-box::-webkit-scrollbar {
        width: 6px;
    }

    .modal-box::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .modal-box::-webkit-scrollbar-thumb {
        background: #1e6f9f;
        border-radius: 10px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 25px;
    }

    .modal-header h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
    }

    .modal-header h4 i {
        color: #1e6f9f;
        margin-left: 10px;
    }

    .modal-close-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: #f0f4f9;
        border-radius: 50%;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4a5a6e;
    }

    .modal-close-btn:hover {
        background: #e74c3c;
        color: #fff;
        transform: rotate(90deg);
    }

    /* ===== فرم داخل مودال ===== */
    .modal-form .form-group {
        margin-bottom: 20px;
    }

    .modal-form .form-group label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #1a2332;
        margin-bottom: 6px;
    }

    .modal-form .form-group label .required {
        color: #e74c3c;
        margin-right: 3px;
    }

    .modal-form .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        font-family: inherit;
    }

    .modal-form .form-control:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.08);
        background: #fff;
    }

    .modal-form .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .modal-form .file-upload-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        padding: 16px 20px;
        border: 2px dashed #e8edf3;
        border-radius: 12px;
        background: #fafbfc;
        transition: all 0.3s ease;
    }

    .modal-form .file-upload-wrapper:hover {
        border-color: #1e6f9f;
        background: #f0f7fe;
    }

    .modal-form .file-upload-input {
        display: none;
    }

    .modal-form .file-upload-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        background: #1e6f9f;
        color: #fff;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .modal-form .file-upload-label:hover {
        background: #155a82;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 111, 159, 0.3);
    }

    .modal-form .file-name {
        color: #6b7a8f;
        font-size: 13px;
    }

    .modal-form .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 20px;
        border-top: 2px solid #f0f4f9;
        margin-top: 10px;
    }

    .modal-form .btn-submit {
        padding: 12px 32px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: inherit;
        text-decoration: none;
    }

    .modal-form .btn-primary {
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
    }

    .modal-form .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 111, 159, 0.3);
        color: #fff;
    }

    .modal-form .btn-danger {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: #fff;
    }

    .modal-form .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
        color: #fff;
    }

    .modal-form .btn-outline {
        background: transparent;
        color: #1e6f9f;
        border: 2px solid #1e6f9f;
    }

    .modal-form .btn-outline:hover {
        background: #1e6f9f;
        color: #fff;
        transform: translateY(-2px);
    }

    .modal-form .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        color: #1a2332;
    }

    .modal-form .checkbox-label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #1e6f9f;
        cursor: pointer;
    }

    .modal-form .existing-file {
        padding: 10px 16px;
        background: #f0f4f9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .modal-form .existing-file .file-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #1a2332;
        font-size: 14px;
    }

    .modal-form .existing-file .file-info i {
        color: #e74c3c;
    }

    .modal-form .existing-file .btn-sm {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }

    .modal-form .existing-file .btn-sm-danger {
        background: #ffebee;
        color: #c62828;
    }

    .modal-form .existing-file .btn-sm-danger:hover {
        background: #f44336;
        color: #fff;
    }

    .modal-form .existing-file .btn-sm-primary {
        background: #e3f2fd;
        color: #1e6f9f;
    }

    .modal-form .existing-file .btn-sm-primary:hover {
        background: #1e6f9f;
        color: #fff;
    }

    /* ===== JODIT ===== */
    .jodit-container {
        border-radius: 12px !important;
        overflow: hidden;
        border: 2px solid #e8edf3 !important;
        transition: all 0.3s ease;
    }

    .jodit-container:focus-within {
        border-color: #1e6f9f !important;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.08);
    }

    .jodit-container .jodit-toolbar {
        background: #f8fafc !important;
        border-bottom: 1px solid #e8edf3 !important;
    }

    .jodit-container .jodit-wysiwyg {
        padding: 12px 16px !important;
        font-family: 'Vazir', Tahoma, Arial, sans-serif !important;
        font-size: 14px !important;
        direction: rtl !important;
        min-height: 200px !important;
    }

    /* ===== انیمیشن‌ها ===== */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* ===== دکمه اکشن در جلسات ===== */
    .session-item .session-actions {
        display: none;
        gap: 6px;
        margin-right: auto;
    }

    .session-item:hover .session-actions {
        display: flex;
    }

    .session-item .session-actions .action-btn-mini {
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #6b7a8f;
        text-decoration: none;
    }

    .session-item .session-actions .action-btn-mini:hover {
        background: #e3f2fd;
        color: #1e6f9f;
    }

    .session-item .session-actions .action-btn-mini.danger:hover {
        background: #ffebee;
        color: #e74c3c;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .modal-box {
            padding: 20px;
            width: 95%;
        }

        .modal-form .form-row {
            grid-template-columns: 1fr;
        }

        .modal-form .form-actions {
            flex-direction: column;
        }

        .modal-form .btn-submit {
            justify-content: center;
        }

        .session-item .session-actions {
            display: flex;
        }
    }

    /* ===== اضافه کردن دکمه‌های جدید به چپ بار ===== */
    .session-item {
        position: relative;
        padding: 10px 14px;
    }

    .session-item .session-title {
        flex: 1;
        font-size: 14px;
    }

    .session-item .session-number {
        font-size: 11px;
        color: #6b7a8f;
    }

    /* ===== بقیه استایل‌های موجود ===== */
    .text-center { text-align: center; }
    .p-5 { padding: 3rem; }
    .m-3 { margin: 1rem; }
    .text-muted { color: #6c757d; }
    .fa-3x { font-size: 3em; }
    .mb-3 { margin-bottom: 1rem; }
    .mt-2 { margin-top: 0.5rem; }

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

    /* ===== استایل مودال لیست تکالیف ===== */
    .btn-settings {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 28px;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-family: inherit;
    }

    .btn-settings:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
        color: #fff;
    }

    .btn-close-modal {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 28px;
        background: #f0f4f9;
        color: #4a5a6e;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .btn-close-modal:hover {
        background: #e3e8ef;
        transform: translateY(-2px);
    }

    .modal-body ul {
        list-style: none;
        padding-right: 0;
    }

    .modal-body ul li {
        position: relative;
        padding-right: 24px;
        margin-bottom: 6px;
    }

    .modal-body ul li::before {
        content: "•";
        position: absolute;
        right: 0;
        color: #1e6f9f;
        font-weight: 700;
        font-size: 18px;
    }

    /* ===== استایل دکمه‌های اکشن ===== */
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
        border: none;
        cursor: pointer;
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

    .course-actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding: 0 5px;
    }

    .course-actions-bar .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #f0f4f9;
        color: #4a5a6e;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 18px;
    }

    .course-actions-bar .action-btn:hover {
        background: #1e6f9f;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 111, 159, 0.3);
    }
</style>
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
        @include('layout.backbtn')
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
                <button class="add-session-btn" onclick="openModal('create')">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="sessions-list">
                @forelse($sessions as $session)
                    <a href="#" class="session-item {{ $loop->first ? 'active' : '' }}" 
                       data-session="{{ $session->id }}"
                       data-pdf="{{ $session->file ?? '' }}"
                       data-title="{{ addslashes($session->name) }}"
                       data-number="جلسه {{ $session->number }}"
                       data-description="{{ addslashes($session->text ?? '') }}"
                       onclick="changeSessionFromData(this)">
                        <span class="session-check"><i class="fas fa-check-circle"></i></span>
                        <span class="session-title">{{ $session->name }}</span>
                        <small class="session-number">(جلسه {{ $session->number }})</small>
                        <span class="session-actions">
                            <button class="action-btn-mini" onclick="event.stopPropagation(); openModal('edit', {{ $session->id }})" title="ویرایش">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn-mini danger" onclick="event.stopPropagation(); deleteSession({{ $session->id }})" title="حذف">
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
                    {{-- فقط یک دکمه برای مدیریت تکالیف --}}
                    <a href="#" id="homeworkTeacherBtn" class="action-icon-btn" data-tooltip="مدیریت تکالیف">
                        <i class="fas fa-file-alt"></i>
                    </a>
                    <button class="action-icon-btn" onclick="openProfExModal()" data-tooltip="لیست تکالیف">
                        <i class="fas fa-list-ul"></i>
                    </button>
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

{{-- ========================================== --}}
{{-- MODAL لیست تکالیف (ارسال گزارش) --}}
{{-- ========================================== --}}
<div class="modal-overlay" id="profExModal">
    <div class="modal-box" style="max-width: 600px;">
        <div class="modal-header">
            <h4>
                <i class="fas fa-file-alt" style="color:#1e6f9f;"></i>
                راهنمای ارسال گزارش
            </h4>
            <button class="modal-close-btn" onclick="closeProfExModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div style="padding:10px 0;">
                <div style="background:#f8fafc;border-radius:12px;padding:20px;border-right:4px solid #1e6f9f;margin-bottom:20px;">
                    <p style="font-size:14px;line-height:2;color:#1a2332;margin:0;">
                        از دانشجو بخواهید گزارشی برای این جلسه تهیه کند. این گزارش می تواند شامل موارد زیر باشد:
                    </p>
                    <ul style="font-size:14px;line-height:2;color:#1a2332;padding-right:20px;margin:10px 0 0;">
                        <li>تهیه یک طرح درسی برای مبحث ارائه شده</li>
                        <li>نوشتن خلاصه ای از مهمترین موضوعات تدریس شده در این جلسه</li>
                        <li>هر گونه نکته یا پیشنهاد تکمیلی که به بهبود یادگیری کمک کند</li>
                    </ul>
                </div>
                
                <div style="background:#e3f2fd;border-radius:12px;padding:16px 20px;margin-bottom:16px;font-size:13px;color:#1a2332;">
                    <i class="fas fa-info-circle" style="color:#1e6f9f;"></i>
                    <strong>توجه:</strong> در صورت عدم تکمیل این بخش، توضیحات پیش فرض ثبت شده در تنظیمات به دانشجو نمایش داده خواهد شد.
                </div>
                
                <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;padding-top:10px;">
                    <a href="{{ route('courses.setting', $course->id) }}#activity-settings" class="btn-settings">
                        <i class="fas fa-cog"></i>
                        رفتن به تنظیمات
                    </a>
                    <button class="btn-close-modal" onclick="closeProfExModal()">
                        <i class="fas fa-times"></i>
                        بستن
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL برای ایجاد/ویرایش جلسه --}}
{{-- ========================================== --}}
<div class="modal-overlay" id="sessionModal">
    <div class="modal-box">
        <div class="modal-header">
            <h4>
                <i class="fas" id="modalIcon"></i>
                <span id="modalTitle">ایجاد جلسه جدید</span>
            </h4>
            <button class="modal-close-btn" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form class="modal-form" id="sessionForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="session_id" id="sessionId" value="">
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div class="form-group" hidden>
                <label for="modalNumber">شماره جلسه</label>
                <input type="number" class="form-control" name="number" id="modalNumber" required>
            </div>

            <div class="form-group">
                <label for="modalName">عنوان جلسه <span class="required">*</span></label>
                <input type="text" class="form-control" name="name" id="modalName" 
                       placeholder="عنوان جلسه را وارد کنید" required>
            </div>

            <div class="form-group">
                <label>طرح درس یا محتوای درس (اختیاری)</label>
                <textarea class="jodit-editor" name="text" id="modalEditor" 
                          placeholder="متن جلسه را وارد کنید..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="modalLink">لینک درس (اختیاری)</label>
                    <input type="text" class="form-control" name="link" id="modalLink" 
                           placeholder="https://example.com">
                </div>
                <div class="form-group">
                    <label for="modalMajazi">لینک فیلم ضبط شده (اختیاری)</label>
                    <input type="text" class="form-control" name="majazi" id="modalMajazi" 
                           placeholder="https://example.com">
                </div>
            </div>

            <div class="form-group">
                <label for="modalAparat">لینک آپارات (اختیاری)</label>
                <input type="text" class="form-control" name="aparat" id="modalAparat" 
                       placeholder="کد اسکریپت آپارات را وارد کنید">
                <small style="color: #6b7a8f; font-size: 12px;">کد اسکریپت آپارات را به همراه iframe یا embed کپی کنید</small>
            </div>

            <div class="form-group">
                <label>بارگذاری محتوای درس (اختیاری)</label>
                <div class="file-upload-wrapper">
                    <input type="file" id="modalFileUpload" name="file" class="file-upload-input" 
                           accept=".pdf,.doc,.docx,.ppt,.pptx">
                    <label for="modalFileUpload" class="file-upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>انتخاب فایل</span>
                    </label>
                    <span class="file-name" id="modalFileName">هیچ فایلی انتخاب نشده است</span>
                </div>
                <small style="color: #6b7a8f; font-size: 12px; display: block; margin-top: 5px;">
                    فرمت‌های مجاز: PDF، Word، PowerPoint | حداکثر حجم: 20 مگابایت
                </small>
                <div id="modalExistingFile" style="display:none;" class="existing-file">
                    <div class="file-info">
                        <i class="fas fa-file-pdf"></i>
                        <span id="modalExistingFileName">فایل موجود</span>
                    </div>
                    <button type="button" class="btn-sm btn-sm-danger" onclick="deleteExistingFile()">
                        <i class="fas fa-trash-alt"></i> حذف
                    </button>
                    <a href="#" id="modalExistingFileLink" class="btn-sm btn-sm-primary" target="_blank">
                        <i class="fas fa-eye"></i> مشاهده
                    </a>
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="active" id="modalActive" checked>
                    <span style="color: #1e6f9f; font-weight: 600;">درس به دانشجو نشان داده شود؟</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit btn-primary" id="modalSubmitBtn">
                    <i class="fas fa-save"></i>
                    <span id="modalSubmitText">ایجاد جلسه</span>
                </button>
                <button type="button" class="btn-submit btn-outline" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                    انصراف
                </button>
                <button type="button" class="btn-submit btn-danger" id="modalDeleteBtn" style="display:none;" 
                        onclick="deleteSessionFromModal()">
                    <i class="fas fa-trash-alt"></i>
                    حذف جلسه
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>
    // ==========================================
    // متغیرهای سراسری
    // ==========================================
    let currentSessionId = '{{ $sessions->first()->id ?? "" }}';
    let currentPdfUrl = '{{ $sessions->first()->file ?? "" }}';
    let currentSessionTitle = '{{ addslashes($sessions->first()->name ?? "") }}';
    let currentSessionNumber = 'جلسه {{ $sessions->first()->number ?? "" }}';
    let joditEditor = null;
    let modalMode = 'create';
    let currentEditId = null;

    // ==========================================
    // توابع مودال جلسات
    // ==========================================
    function openModal(mode, sessionId = null) {
        modalMode = mode;
        const modal = document.getElementById('sessionModal');
        const form = document.getElementById('sessionForm');
        const title = document.getElementById('modalTitle');
        const icon = document.getElementById('modalIcon');
        const submitBtn = document.getElementById('modalSubmitBtn');
        const submitText = document.getElementById('modalSubmitText');
        const deleteBtn = document.getElementById('modalDeleteBtn');

        if (mode === 'create') {
            title.textContent = 'ایجاد جلسه جدید';
            icon.className = 'fas fa-plus-circle';
            submitText.textContent = 'ایجاد جلسه';
            deleteBtn.style.display = 'none';
            
            form.action = '{{ route("sessions.store", $course->id) }}';
            form.method = 'POST';
            
            document.getElementById('sessionId').value = '';
            document.getElementById('modalNumber').value = {{ $sessions->count() + 1 }};
            document.getElementById('modalName').value = '';
            document.getElementById('modalLink').value = '';
            document.getElementById('modalMajazi').value = '';
            document.getElementById('modalAparat').value = '';
            document.getElementById('modalActive').checked = true;
            
            if (joditEditor) {
                joditEditor.value = '';
            }
            
            document.getElementById('modalFileUpload').value = '';
            document.getElementById('modalFileName').textContent = 'هیچ فایلی انتخاب نشده است';
            document.getElementById('modalExistingFile').style.display = 'none';
            
        } else {
            title.textContent = 'ویرایش جلسه';
            icon.className = 'fas fa-edit';
            submitText.textContent = 'بروزرسانی جلسه';
            deleteBtn.style.display = 'inline-flex';
            currentEditId = sessionId;
            
            form.action = '{{ route("sessions.update", "") }}/' + sessionId;
            form.method = 'POST';
            
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
            
            fetchSessionData(sessionId);
        }

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('sessionModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.getElementById('sessionModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeProfExModal();
        }
    });

    // ==========================================
    // دریافت اطلاعات جلسه برای ویرایش
    // ==========================================
    function fetchSessionData(sessionId) {
        fetch('/teacher/courses/sessions/edit/' + sessionId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const session = data.data;
                    
                    document.getElementById('sessionId').value = session.id;
                    document.getElementById('modalNumber').value = session.number;
                    document.getElementById('modalName').value = session.name;
                    document.getElementById('modalLink').value = session.link || '';
                    document.getElementById('modalMajazi').value = session.majazi || '';
                    document.getElementById('modalAparat').value = session.aparat || '';
                    document.getElementById('modalActive').checked = session.active == 1;
                    
                    if (joditEditor) {
                        joditEditor.value = session.text || '';
                    }
                    
                    if (session.file) {
                        document.getElementById('modalExistingFile').style.display = 'flex';
                        document.getElementById('modalExistingFileName').textContent = session.file.split('/').pop();
                        document.getElementById('modalExistingFileLink').href = '/files/session' + session.file;
                        document.getElementById('modalFileUpload').value = '';
                        document.getElementById('modalFileName').textContent = 'فایل جدید جایگزین خواهد شد';
                    } else {
                        document.getElementById('modalExistingFile').style.display = 'none';
                    }
                } else {
                    alert('خطا در دریافت اطلاعات جلسه: ' + data.message);
                    closeModal();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('خطا در ارتباط با سرور');
                closeModal();
            });
    }

    // ==========================================
    // حذف فایل موجود
    // ==========================================
    function deleteExistingFile() {
        if (!confirm('آیا مطمئن هستید که می‌خواهید فایل این جلسه را حذف کنید؟')) {
            return;
        }

        const sessionId = document.getElementById('sessionId').value;
        
        fetch('/teacher/courses/sessions/delete-file/' + sessionId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalExistingFile').style.display = 'none';
                document.getElementById('modalFileName').textContent = 'فایل حذف شد';
                alert('فایل با موفقیت حذف شد');
            } else {
                alert('خطا در حذف فایل: ' + data.message);
            }
        })
        .catch(error => {
            alert('خطا در ارتباط با سرور');
        });
    }

    // ==========================================
    // حذف جلسه از مودال
    // ==========================================
    function deleteSessionFromModal() {
        if (!confirm('آیا مطمئن هستید که می‌خواهید این جلسه را حذف کنید؟')) {
            return;
        }
        
        const sessionId = document.getElementById('sessionId').value;
        deleteSession(sessionId);
    }

    // ==========================================
    // حذف جلسه
    // ==========================================
    function deleteSession(sessionId) {
        if (!confirm('آیا مطمئن هستید که می‌خواهید این جلسه را حذف کنید؟ این عمل غیرقابل بازگشت است.')) {
            return;
        }

        fetch('/teacher/courses/sessions/delete/' + sessionId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('جلسه با موفقیت حذف شد');
                location.reload();
            } else {
                alert('خطا در حذف جلسه: ' + data.message);
            }
        })
        .catch(error => {
            alert('خطا در ارتباط با سرور');
        });
    }

    // ==========================================
    // تابع اصلی برای تغییر جلسه (مشابه صفحه دانشجو)
    // ==========================================
    function changeSession(element, sessionId, pdfUrl, title, number, description) {
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

        // ===== به‌روزرسانی توضیحات جلسه =====
        const sessionDescription = document.getElementById('sessionDescription');
        if (sessionDescription) {
            if (description && description.trim() !== '') {
                sessionDescription.innerHTML = description;
            } else {
                sessionDescription.innerHTML = '<p class="text-muted">هیچ توضیحی برای این جلسه ثبت نشده است</p>';
            }
        }

        // ===== به‌روزرسانی PDF Viewer =====
        const pdfViewer = document.getElementById('pdfViewer');
        if (pdfViewer) {
            if (pdfUrl) {
                pdfViewer.outerHTML = `<object id="pdfViewer" data="https://docs.google.com/gview?embedded=true&url=${pdfUrl}" type="application/pdf" width="100%" height="550px">
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

        // ==========================================
        // تنظیم لینک دکمه مدیریت تکالیف با session_id جدید
        // ==========================================
        const homeworkTeacherBtn = document.getElementById('homeworkTeacherBtn');
        if (homeworkTeacherBtn) {
            homeworkTeacherBtn.setAttribute('href', `/teacher/courses/exercises/show/${sessionId}`);
        }
    }

    // ==========================================
    // تابع واسط برای خواندن از data attributes
    // ==========================================
    function changeSessionFromData(element) {
        const sessionId = element.dataset.session;
        const pdfUrl = element.dataset.pdf;
        const title = element.dataset.title;
        const number = element.dataset.number;
        const description = element.dataset.description;
        
        changeSession(element, sessionId, pdfUrl, title, number, description);
    }

    // ==========================================
    // توابع مودال لیست تکالیف (ارسال گزارش)
    // ==========================================
    function openProfExModal() {
        document.getElementById('profExModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeProfExModal() {
        document.getElementById('profExModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.getElementById('profExModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeProfExModal();
        }
    });

    // ==========================================
    // Event listener برای collapsible
    // ==========================================
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

        // ==========================================
        // مقداردهی Jodit Editor
        // ==========================================
        const editorElement = document.getElementById('modalEditor');
        if (editorElement) {
            joditEditor = new Jodit('#modalEditor', {
                width: '100%',
                height: 250,
                allowResize: true,
                allowResizeImages: true,
                direction: 'rtl',
                language: 'fa',
                buttons: [
                    'source', '|',
                    'undo', 'redo', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'font', 'fontsize', 'brush', 'paragraph', '|',
                    'ul', 'ol', 'outdent', 'indent', '|',
                    'align', 'hr', 'table', '|',
                    'link', 'unlink',
                    {
                        name: 'uploadImage',
                        iconURL: 'https://cdn-icons-png.flaticon.com/512/1829/1829586.png',
                        tooltip: 'آپلود تصویر',
                        exec: (editor) => {
                            let input = document.createElement('input');
                            input.type = 'file';
                            input.accept = 'image/*';
                            input.onchange = () => {
                                let file = input.files[0];
                                if (!file) return;

                                let formData = new FormData();
                                formData.append('file', file);

                                fetch('{{ route("upload.image") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.files && data.files[0].url) {
                                        let img = document.createElement('img');
                                        img.src = data.files[0].url;
                                        img.style.maxWidth = '100%';
                                        editor.s.insertNode(img);
                                    } else {
                                        alert('خطا در آپلود تصویر');
                                    }
                                })
                                .catch(err => alert('Upload error: ' + err));
                            };
                            input.click();
                        }
                    },
                    {
                        name: 'uploadVideo',
                        iconURL: 'https://cdn-icons-png.flaticon.com/512/727/727245.png',
                        tooltip: 'آپلود ویدیو',
                        exec: (editor) => {
                            let input = document.createElement('input');
                            input.type = 'file';
                            input.accept = 'video/*';
                            input.onchange = () => {
                                let file = input.files[0];
                                if (!file) return;

                                let formData = new FormData();
                                formData.append('file', file);

                                fetch('{{ route("upload.video") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.files && data.files[0].url) {
                                        let wrapper = document.createElement('div');
                                        wrapper.classList.add('video-wrapper');

                                        let video = document.createElement('video');
                                        video.setAttribute('controls', '');
                                        video.src = data.files[0].url;
                                        video.style.maxWidth = '100%';

                                        wrapper.appendChild(video);
                                        editor.s.insertNode(wrapper);
                                    } else {
                                        alert('خطا در آپلود ویدیو');
                                    }
                                })
                                .catch(err => alert('Upload error: ' + err));
                            };
                            input.click();
                        }
                    },
                    '|', 'symbols', 'emoticons', '|',
                    'print', 'fullsize', 'preview'
                ],
                colors: {
                    text: ['#000000', '#ff0000', '#00ff00', '#0000ff', '#ff00ff', '#00ffff'],
                    background: ['#ffffff', '#ffff00', '#00ffff', '#ffcc99']
                },
                defaultFont: 'Vazir, Tahoma, Arial, sans-serif',
                defaultFontSize: '14px',
                fonts: ['Vazir', 'Tahoma', 'Arial', 'Courier New']
            });
        }

        // ==========================================
        // نمایش نام فایل انتخاب شده
        // ==========================================
        document.getElementById('modalFileUpload').addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : 'هیچ فایلی انتخاب نشده است';
            document.getElementById('modalFileName').textContent = fileName;
            
            if (e.target.files[0]) {
                document.getElementById('modalExistingFile').style.display = 'none';
            }
        });

        // ==========================================
        // تنظیم دکمه تکالیف برای جلسه اول
        // ==========================================
        const firstSession = document.querySelector('.session-item.active');
        if (firstSession) {
            const sessionId = firstSession.dataset.session;
            
            const homeworkTeacherBtn = document.getElementById('homeworkTeacherBtn');
            if (homeworkTeacherBtn) {
                homeworkTeacherBtn.setAttribute('href', `/teacher/courses/exercises/show/${sessionId}`);
            }
        }
    });
</script>

@endsection