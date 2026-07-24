@extends('layout.master')

@section('title')
ملیسان | مدیریت درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-course.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
<style>
    /* ===== استایل فریم محتوای آموزشی ===== */
    .content-frame {
        border: 2px solid #e8edf4;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
        background: #fafcff;
        transition: all 0.3s ease;
    }
    .content-frame:hover {
        border-color: #1e6f9f;
        box-shadow: 0 4px 20px rgba(30, 111, 159, 0.08);
    }
    .content-frame-title {
        background: linear-gradient(135deg, #1e6f9f, #0d4b6e);
        color: #fff;
        padding: 14px 20px;
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.3px;
    }
    .content-frame-title i {
        font-size: 18px;
        color: #ffd700;
    }
    .content-frame-body {
        padding: 20px 20px 10px 20px;
        background: #fff;
    }
    .content-frame-body .form-group:last-child {
        margin-bottom: 0;
    }
    .content-frame-body .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    @media (max-width: 576px) {
        .content-frame-body .form-row {
            grid-template-columns: 1fr;
        }
    }

    /* ===== سایر استایل‌های موجود ===== */
    .required {
        color: #f44336;
        margin-right: 3px;
    }
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-box {
        background: #fff;
        border-radius: 20px;
        max-width: 800px;
        width: 95%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease;
    }
    @keyframes modalSlideIn {
        from { transform: translateY(-30px) scale(0.96); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        border-bottom: 2px solid #f0f4f9;
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 10;
        border-radius: 20px 20px 0 0;
    }
    .modal-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1a2332;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .modal-header h4 i {
        color: #1e6f9f;
        font-size: 20px;
    }
    .modal-close-btn {
        background: none;
        border: none;
        font-size: 22px;
        color: #6b7a8f;
        cursor: pointer;
        padding: 5px 8px;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .modal-close-btn:hover {
        background: #fee8e8;
        color: #f44336;
    }
    .modal-form {
        padding: 24px;
    }
    .form-group {
        margin-bottom: 18px;
    }
    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #1a2332;
        margin-bottom: 6px;
    }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e8edf4;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
        background: #fafcff;
    }
    .form-control:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.15);
    }
    .file-upload-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .file-upload-input {
        display: none;
    }
    .file-upload-label {
        background: #f0f4f9;
        border: 2px dashed #c0d0e0;
        border-radius: 10px;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        color: #1e6f9f;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .file-upload-label:hover {
        background: #e3edf7;
        border-color: #1e6f9f;
    }
    .file-name {
        font-size: 13px;
        color: #6b7a8f;
        padding: 5px 10px;
        background: #f8fafc;
        border-radius: 8px;
        flex: 1;
        min-width: 120px;
    }
    .existing-file {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 10px;
        padding: 10px 16px;
        background: #f0f7ff;
        border-radius: 10px;
        border-right: 4px solid #1e6f9f;
        flex-wrap: wrap;
    }
    .existing-file .file-info {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #1a2332;
        flex: 1;
    }
    .existing-file .file-info i {
        color: #f44336;
        font-size: 20px;
    }
    .btn-sm {
        padding: 5px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
    }
    .btn-sm-danger {
        background: #fee8e8;
        color: #f44336;
    }
    .btn-sm-danger:hover {
        background: #fdd0d0;
    }
    .btn-sm-primary {
        background: #e3edf7;
        color: #1e6f9f;
    }
    .btn-sm-primary:hover {
        background: #c5d9ed;
    }
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    .checkbox-label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #1e6f9f;
        cursor: pointer;
    }
    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 16px;
        border-top: 2px solid #f0f4f9;
        margin-top: 10px;
    }
    .btn-submit {
        padding: 10px 24px;
        border-radius: 10px;
        border: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-primary {
        background: linear-gradient(135deg, #1e6f9f, #0d4b6e);
        color: #fff;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(30, 111, 159, 0.3);
    }
    .btn-outline {
        background: #f0f4f9;
        color: #1a2332;
    }
    .btn-outline:hover {
        background: #e0e8f0;
    }
    .btn-danger {
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: #fff;
    }
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(244, 67, 54, 0.3);
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    @media (max-width: 576px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .modal-box {
            width: 98%;
            max-height: 95vh;
        }
        .modal-form {
            padding: 16px;
        }
        .content-frame-body {
            padding: 14px;
        }
    }

    /* ===== استایل مودال‌های دیگر ===== */
    .modal-box[style*="max-width: 700px;"] .modal-body {
        padding: 20px 24px 24px;
    }
    .btn-settings {
        padding: 10px 22px;
        border-radius: 10px;
        border: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #fff;
        background: linear-gradient(135deg, #1e6f9f, #0d4b6e);
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-settings:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(30, 111, 159, 0.25);
        color: #fff;
    }
    .btn-close-modal {
        padding: 10px 22px;
        border-radius: 10px;
        border: 2px solid #e8edf4;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        color: #1a2332;
        transition: all 0.2s;
    }
    .btn-close-modal:hover {
        background: #f0f4f9;
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
                <button class="add-session-btn" 
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="ایجاد جلسه درس جدید"
                        onclick="openModal('create')">
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
                    <a href="#" id="questionTeacherBtn" class="action-icon-btn" data-tooltip="ثبت سوال">
                        <i class="fas fa-question-circle"></i>
                    </a>
                    <a href="#" id="homeworkTeacherBtn" class="action-icon-btn" data-tooltip="مدیریت تکالیف">
                        <i class="fas fa-file-alt"></i>
                    </a>
                    <button class="action-icon-btn" onclick="openProfExModal()" data-tooltip="مدیریت گزارش">
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
    <div class="modal-box" style="max-width: 700px;">
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
                <div style="background:#f8fafc;border-radius:12px;padding:20px;border-right:4px solid #1e6f9f;margin-bottom:16px;">
                    <p style="font-size:14px;line-height:2;color:#1a2332;margin:0;font-weight:600;color:#1e6f9f;">
                        <i class="fas fa-quote-right" style="margin-left:6px;"></i>
                        متن راهنما:
                    </p>
                    <div id="reportDescriptionDisplay" style="font-size:14px;line-height:2;color:#1a2332;margin-top:8px;padding:12px 16px;background:#fff;border-radius:8px;">
                        <span style="color:#6b7a8f;">در حال بارگذاری...</span>
                    </div>
                </div>
                
                <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;padding-bottom:16px;border-bottom:2px solid #f0f4f9;margin-bottom:16px;">
                    <button class="btn-settings" onclick="openEditReportModal()" style="background:linear-gradient(135deg,#ff9800,#e65100);">
                        <i class="fas fa-edit"></i>
                        پیام نحوه نوشتن گزارش به دانشجو
                    </button>
                    <button class="btn-close-modal" onclick="closeProfExModal()">
                        <i class="fas fa-times"></i>
                        بستن
                    </button>
                </div>
                
                <div style="background:#e3f2fd;border-radius:12px;padding:16px 20px;font-size:13px;color:#1a2332;">
                    <i class="fas fa-info-circle" style="color:#1e6f9f;"></i>
                    <strong>توجه:</strong> در صورت عدم تکمیل این بخش، توضیحات پیش فرض ثبت شده در تنظیمات به دانشجو نمایش داده خواهد شد.
                </div>
                
                <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;padding-top:16px;">
                    <a href="{{ route('courses.setting', $course->id) }}#activity-settings" class="btn-settings">
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
                <i class="fas fa-edit" style="color:#ff9800;"></i>
                ویرایش متن راهنمای ارسال گزارش
            </h4>
            <button class="modal-close-btn" onclick="closeEditReportModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="reportDescForm">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <div class="form-group">
                    <label for="reportDescEditor">متن راهنما <span class="required">*</span></label>
                    <textarea class="jodit-editor" id="reportDescEditor" name="description" 
                              placeholder="متن راهنمای ارسال گزارش را وارد کنید..."></textarea>
                </div>
                
                <div style="display:flex;gap:12px;flex-wrap:wrap;padding-top:16px;border-top:2px solid #f0f4f9;margin-top:10px;">
                    <button type="submit" class="btn-settings" id="saveReportDescBtn" style="background:linear-gradient(135deg,#4caf50,#388e3c);">
                        <i class="fas fa-save"></i>
                        ذخیره تغییرات
                    </button>
                    <button type="button" class="btn-close-modal" onclick="closeEditReportModal()">
                        <i class="fas fa-times"></i>
                        انصراف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL برای ایجاد/ویرایش جلسه (با فریم محتوای آموزشی) --}}
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

        <form class="modal-form pt-0" id="sessionForm" method="POST" enctype="multipart/form-data">
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

            {{-- ===== طرح درس یا محتوای درس (خارج از فریم) ===== --}}
            <div class="form-group">
                <label>طرح درس یا محتوای درس (اختیاری)</label>
                <textarea class="jodit-editor" name="text" id="modalEditor" 
                          placeholder="متن جلسه را وارد کنید..."></textarea>
            </div>

            {{-- ===== فریم محتوای آموزشی ===== --}}
            <div class="content-frame">
                <div class="content-frame-title">
                    <i class="fas fa-graduation-cap"></i>
                    محتوای آموزشی جلسه (انتخاب حداقل یک گزینه الزامی است.)
                </div>
                <div class="content-frame-body">
                    {{-- لینک درس --}}
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

                    {{-- لینک آپارات --}}
                    <div class="form-group">
                        <label for="modalAparat">لینک آپارات (اختیاری)</label>
                        <input type="text" class="form-control" name="aparat" id="modalAparat" 
                               placeholder="کد اسکریپت آپارات را وارد کنید">
                        <small style="color: #6b7a8f; font-size: 12px;">کد اسکریپت آپارات را به همراه iframe یا embed کپی کنید</small>
                    </div>

                    {{-- بارگذاری فایل --}}
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


@endsection

@section('js')
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
    let reportJoditEditor = null;
    let currentReportDesc = '';
    const courseId = '{{ $course->id }}';
    
    // ==========================================
    // اعتبارسنجی فرم جلسه - حداقل یک گزینه محتوای آموزشی
    // ==========================================
    document.getElementById('sessionForm').addEventListener('submit', function(e) {
        const link = document.getElementById('modalLink').value.trim();
        const majazi = document.getElementById('modalMajazi').value.trim();
        const aparat = document.getElementById('modalAparat').value.trim();
        const file = document.getElementById('modalFileUpload').files[0];
        const existingFile = document.getElementById('modalExistingFile').style.display !== 'none';
    
        // بررسی اینکه حداقل یکی از گزینه‌ها پر شده باشد
        if (!link && !majazi && !aparat && !file && !existingFile) {
            e.preventDefault();
            alert('لطفاً حداقل یکی از گزینه‌های محتوای آموزشی (لینک درس، لینک فیلم ضبط شده، لینک آپارات یا بارگذاری فایل) را انتخاب کنید.');
            return false;
        }
    
        return true;
    });

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
            closeEditReportModal();
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
    // تابع اصلی برای تغییر جلسه
    // ==========================================
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

        const titleDisplay = document.getElementById('sessionTitleDisplay');
        if (titleDisplay) {
            titleDisplay.innerHTML = `<h5>${number} : ${title}</h5>`;
        }

        const sessionDescription = document.getElementById('sessionDescription');
        if (sessionDescription) {
            if (description && description.trim() !== '') {
                sessionDescription.innerHTML = description;
            } else {
                sessionDescription.innerHTML = '<p class="text-muted">هیچ توضیحی برای این جلسه ثبت نشده است</p>';
            }
        }

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

        const pdfOpenBtn = document.getElementById('pdfOpenBtn');
        if (pdfOpenBtn) {
            if (pdfUrl) {
                pdfOpenBtn.setAttribute('href', pdfUrl);
                pdfOpenBtn.style.display = 'inline-flex';
            } else {
                pdfOpenBtn.style.display = 'none';
            }
        }

        const questionTeacherBtn = document.getElementById('questionTeacherBtn');
        if (questionTeacherBtn) {
            questionTeacherBtn.setAttribute('href', `/teacher/questions/create/${sessionId}`);
        }

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
        loadReportDescription();
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
    // توابع مودال ویرایش توضیحات گزارش
    // ==========================================
    function openEditReportModal() {
        const modal = document.getElementById('editReportModal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        fetch('/teacher/courses/settings/get-report-desc?course_id=' + courseId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentReportDesc = data.data.description || '';
                    if (reportJoditEditor) {
                        reportJoditEditor.value = currentReportDesc;
                    }
                } else {
                    alert('خطا در دریافت توضیحات: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('خطا در ارتباط با سرور');
            });
    }

    function closeEditReportModal() {
        document.getElementById('editReportModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.getElementById('editReportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditReportModal();
        }
    });

    // ==========================================
    // بارگذاری توضیحات و نمایش در مودال اصلی
    // ==========================================
    function loadReportDescription() {
        fetch('/teacher/courses/settings/get-report-desc?course_id=' + courseId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const display = document.getElementById('reportDescriptionDisplay');
                    if (display) {
                        const desc = data.data.description || 'موضوع اصلی این جلسه چه بود و چه هدفی داشت؟ لطفاً یک نکتهٔ آموزنده از مطالب ارائه شده را با بیانی دیگر (به زبان خودتان) بازنویسی کنید.';
                        display.innerHTML = desc;
                    }
                }
            })
            .catch(error => {
                console.error('Error loading report description:', error);
            });
    }

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
        // مقداردهی Jodit Editor برای مودال جلسات
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
        // مقداردهی Jodit Editor برای مودال ویرایش گزارش
        // ==========================================
        const reportEditorElement = document.getElementById('reportDescEditor');
        if (reportEditorElement) {
            reportJoditEditor = new Jodit('#reportDescEditor', {
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
        // تنظیم دکمه‌ها برای جلسه اول
        // ==========================================
        const firstSession = document.querySelector('.session-item.active');
        if (firstSession) {
            const sessionId = firstSession.dataset.session;
            
            const questionTeacherBtn = document.getElementById('questionTeacherBtn');
            if (questionTeacherBtn) {
                questionTeacherBtn.setAttribute('href', `/teacher/questions/create/${sessionId}`);
            }
            
            const homeworkTeacherBtn = document.getElementById('homeworkTeacherBtn');
            if (homeworkTeacherBtn) {
                homeworkTeacherBtn.setAttribute('href', `/teacher/courses/exercises/show/${sessionId}`);
            }
        }
        
        // ==========================================
        // بارگذاری توضیحات گزارش در مودال
        // ==========================================
        loadReportDescription();
    });

    // ==========================================
    // ذخیره توضیحات گزارش
    // ==========================================
    document.getElementById('reportDescForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('saveReportDescBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ذخیره...';
        
        const description = reportJoditEditor ? reportJoditEditor.value : document.getElementById('reportDescEditor').value;
        
        fetch('/teacher/courses/settings/update-report-desc', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                description: description,
                course_id: courseId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('توضیحات با موفقیت به‌روزرسانی شد');
                
                const display = document.getElementById('reportDescriptionDisplay');
                if (display) {
                    display.innerHTML = data.data.description;
                }
                
                closeEditReportModal();
            } else {
                let errorMsg = 'خطا در ذخیره: ';
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMsg += errors.join(', ');
                } else {
                    errorMsg += data.message || 'مشخص نیست';
                }
                alert(errorMsg);
            }
        })
        .catch(error => {
            alert('خطا در ارتباط با سرور');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> ذخیره تغییرات';
        });
    });
</script>

@endsection