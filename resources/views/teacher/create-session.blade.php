@extends('layout.master')

@section('title')
ملیسان | ایجاد جلسه
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-create-session.css')}}">
<!-- CKEditor CDN -->
<script src="{{asset('textEditor/text.js')}}"></script>
<style>
    .ck-editor__editable {
        min-height: 300px;
        border-radius: 12px !important;
    }

    .ck-editor__top {
        border-radius: 12px 12px 0 0 !important;
    }

    .ck-editor__bottom {
        border-radius: 0 0 12px 12px !important;
    }

    .ck.ck-editor {
        border: 2px solid #e8edf3;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .ck.ck-editor:focus-within {
        border-color: #1e6f9f;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.08);
    }

    .session-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .session-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 30px;
    }

    .session-header {
        border-bottom: 2px solid #f0f4f9;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .session-title {
        color: #1a2332;
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .session-title small {
        font-weight: 400;
        color: #6b7a8f;
        font-size: 14px;
        margin-right: 10px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #1a2332;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-label .required {
        color: #e74c3c;
        margin-right: 4px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9aa8b9;
        font-size: 16px;
        pointer-events: none;
    }

    .form-input {
        width: 100%;
        padding: 12px 45px 12px 16px;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        direction: rtl;
    }

    .form-input:focus {
        border-color: #1e6f9f;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.08);
        outline: none;
    }

    .form-input:read-only {
        background: #f0f4f9;
        color: #6b7a8f;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .file-upload-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        padding: 20px;
        border: 2px dashed #e8edf3;
        border-radius: 12px;
        background: #fafbfc;
        transition: all 0.3s ease;
    }

    .file-upload-wrapper:hover {
        border-color: #1e6f9f;
        background: #f0f7fe;
    }

    .file-upload-input {
        display: none;
    }

    .file-upload-label {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 24px;
        background: #1e6f9f;
        color: #fff;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .file-upload-label:hover {
        background: #155a82;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 111, 159, 0.3);
    }

    .file-upload-label i {
        font-size: 18px;
    }

    .file-name {
        color: #6b7a8f;
        font-size: 14px;
    }

    .checkbox-group {
        padding-top: 10px;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        font-size: 14px;
    }

    .checkbox-label input[type="checkbox"] {
        display: none;
    }

    .checkbox-custom {
        width: 20px;
        height: 20px;
        border: 2px solid #d0d7e2;
        border-radius: 6px;
        display: inline-block;
        position: relative;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .checkbox-label input:checked + .checkbox-custom {
        background: #1e6f9f;
        border-color: #1e6f9f;
    }

    .checkbox-label input:checked + .checkbox-custom::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
        font-weight: bold;
    }

    .checkbox-label:hover .checkbox-custom {
        border-color: #1e6f9f;
    }

    .form-actions {
        margin-top: 35px;
        padding-top: 25px;
        border-top: 2px solid #f0f4f9;
        display: flex;
        justify-content: flex-start;
    }

    .submit-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 40px;
        background: linear-gradient(135deg, #1e6f9f 0%, #155a82 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(30, 111, 159, 0.4);
    }

    .submit-btn i {
        font-size: 18px;
    }

    .form-textarea {
        display: none;
    }

    .editor-wrapper {
        border-radius: 12px;
        overflow: hidden;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .session-card {
            padding: 20px;
        }

        .file-upload-wrapper {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }

        .file-upload-label {
            justify-content: center;
        }

        .form-actions {
            justify-content: center;
        }

        .submit-btn {
            width: 100%;
            justify-content: center;
        }

        .session-title {
            font-size: 18px;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="session-container">
    <div class="session-card">
        <div class="session-header">
            <h4 class="session-title">
                محتوای جلسه {{ $nextSessionNumber }}
                <small>دوره: {{ $course->name }}</small>
            </h4>
        </div>

        {{-- اصلاح مسیر فرم --}}
        <form class="session-form" action="{{ route('sessions.store', $course->id) }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group" hidden>
                <label class="form-label" for="number">شماره جلسه</label>
                <div class="input-wrapper">
                    <i class="fas fa-sort-numeric-up input-icon"></i>
                    <input class="form-input" id="number" name="number" type="number" value="{{ $nextSessionNumber }}" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="name">
                    عنوان (موضوع درس در جلسه جاری)
                    <span class="required">*</span>
                </label>
                <div class="input-wrapper">
                    <i class="fas fa-heading input-icon"></i>
                    <input class="form-input" id="name" name="name" type="text" required 
                           placeholder="عنوان جلسه را وارد کنید" value="{{ old('name') }}">
                </div>
                @error('name')
                    <small style="color: #e74c3c; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="link">لینک درس (اختیاری)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-link input-icon"></i>
                        <input class="form-input" id="link" name="link" type="text" 
                               placeholder="https://example.com" value="{{ old('link') }}">
                    </div>
                    @error('link')
                        <small style="color: #e74c3c; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="majazi">لینک فیلم ضبط شده کلاس (اختیاری)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-video input-icon"></i>
                        <input class="form-input" id="majazi" name="majazi" type="text" 
                               placeholder="https://example.com" value="{{ old('majazi') }}">
                    </div>
                    @error('majazi')
                        <small style="color: #e74c3c; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="aparat">لینک آپارات (اختیاری)</label>
                <div class="input-wrapper">
                    <i class="fas fa-film input-icon"></i>
                    <input class="form-input" id="aparat" name="aparat" type="text" 
                           placeholder="کد اسکریپت آپارات را وارد کنید" value="{{ old('aparat') }}">
                </div>
                <small style="color: #6b7a8f; font-size: 12px;">کد اسکریپت آپارات را به همراه iframe یا embed کپی کنید</small>
            </div>

            <div class="form-group">
                <label class="form-label">بارگذاری محتوای درس (اختیاری)</label>
                <div class="file-upload-wrapper">
                    <input type="file" id="file-upload" name="file" class="file-upload-input" accept=".pdf,.doc,.docx,.ppt,.pptx">
                    <label for="file-upload" class="file-upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>انتخاب فایل</span>
                    </label>
                    <span class="file-name" id="file-name">هیچ فایلی انتخاب نشده است</span>
                </div>
                <small style="color: #6b7a8f; font-size: 12px; display: block; margin-top: 5px;">
                    فرمت‌های مجاز: PDF، Word، PowerPoint | حداکثر حجم: 20 مگابایت
                </small>
                @error('file')
                    <small style="color: #e74c3c; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">طرح درس یا محتوای درس (اختیاری)</label>
                <div class="editor-wrapper">
                    <textarea class="form-textarea" id="editor" name="text">{{ old('text') }}</textarea>
                </div>
            </div>

            <div class="form-group checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="active" id="active" checked>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-text" style="color: #1e6f9f; font-weight: 600;">درس به دانشجو نشان داده شود؟</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-check"></i>
                    تائید و ثبت اطلاعات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    // نمایش نام فایل انتخاب شده
    document.getElementById('file-upload').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'هیچ فایلی انتخاب نشده است';
        document.getElementById('file-name').textContent = fileName;
    });

    // CKEditor Configuration - اصلاح شده برای اطمینان از اجرا
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('editor', {
                filebrowserUploadUrl: '/admin/panel/upload-image',
                filebrowserImageUploadUrl: '/admin/panel/upload-image',
                contentsLangDirection: 'rtl',
                toolbar: [
                    ['Styles', 'Format', 'Font', 'FontSize', 'UploadImage'],
                    '/',
                    ['Bold', 'Italic', 'Underline', 'StrikeThrough', '-', 'Undo', 'Redo', '-', 'Cut', 'Copy', 'Paste', 'Find', 'Replace', '-', 'Outdent', 'Indent', '-', 'Print'],
                    '/',
                    ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'],
                    ['Table', '-', 'Link', 'Smiley', 'TextColor', 'BGColor']
                ],
                height: 350,
                removePlugins: 'elementspath',
                resize_enabled: true
            });
        } else {
            console.error('CKEditor not loaded');
        }
    });
</script>
@endsection