@extends('layout.master')

@section('title')
ملیسان | ایجاد جلسه
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-create-session.css')}}">
<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
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
</style>
@endsection

@section('mohtava')
<div class="session-container">
    <div class="session-card">
        <div class="session-header">
            <h4 class="session-title">محتوای جلسه 1</h4>
        </div>

        <form class="session-form" action="/dashboard/courses/sessions/create?course_id=238" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group" hidden>
                <label class="form-label" for="number">شماره جلسه</label>
                <input class="form-input" id="number" name="number" type="number" value="1" readonly>
            </div>

            <div class="form-group">
                <label class="form-label" for="name">عنوان (موضوع درس در جلسه جاری)</label>
                <div class="input-wrapper">
                    <i class="fas fa-align-right input-icon"></i>
                    <input class="form-input" id="name" name="name" type="text" required placeholder="عنوان جلسه را وارد کنید">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="link">لینک درس اگر در جای دیگر بارگذاری شده است (اختیاری)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-link input-icon"></i>
                        <input class="form-input" id="link" name="link" type="text" placeholder="https://example.com">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="majazi">لینک فیلم ضبط شده کلاس (اختیاری)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-video input-icon"></i>
                        <input class="form-input" id="majazi" name="majazi" type="text" placeholder="https://example.com">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="aparat">لینک آپارات (اختیاری) (کد script آپارات را کپی کنید)</label>
                <div class="input-wrapper">
                    <i class="fas fa-film input-icon"></i>
                    <input class="form-input" id="aparat" name="aparat" type="text" placeholder="کد آپارات را وارد کنید">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">بارگذاری محتوای درس (اختیاری)</label>
                <div class="file-upload-wrapper">
                    <input type="file" id="file-upload" name="file" class="file-upload-input">
                    <label for="file-upload" class="file-upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>انتخاب فایل</span>
                    </label>
                    <span class="file-name" id="file-name">هیچ فایلی انتخاب نشده است</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">طرح درس یا محتوای درس (اختیاری)</label>
                <div class="editor-wrapper">
                    <textarea class="form-textarea" id="editor" name="text"></textarea>
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
<script src="https://malisan.ir/app-assets/js/vendors.min.js"></script>
<script src="https://malisan.ir/app-assets/js/plugins.min.js"></script>
<script src="https://malisan.ir/app-assets/js/search.min.js"></script>
<script src="https://malisan.ir/app-assets/js-rtl/custom/custom-script-rtl.min.js"></script>
<script src="https://malisan.ir/app-assets/js/scripts/ui-alerts.min.js"></script>
<script src="https://malisan.ir/app-assets/js/axios.min.js"></script>
<script src="https://malisan.ir/cuba-style/assets/ckeditor/ckeditor.js"></script>
<script src="https://malisan.ir/cuba-style/assets/js/select2/select2.full.min.js"></script>

<script>
    document.getElementById('file-upload').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'هیچ فایلی انتخاب نشده است';
        document.getElementById('file-name').textContent = fileName;
    });

    // CKEditor Configuration - دقیقا مثل کد قدیم
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

    $(document).ready(function() {
        $('#categories').select2();
    });
    $(document).ready(function() {
        $('#tags').select2();
    });
</script>
@endsection