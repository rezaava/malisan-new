@extends('layout.master')

@section('title')
ملیسان | ایجاد جلسه
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-create-session.css')}}">
{{-- اضافه کردن استایل Jodit --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">

<style>
    /* ===== JODIT EDITOR ===== */
    .jodit-container {
        border-radius: 12px !important;
        overflow: hidden;
        border: 2px solid #e8edf3 !important;
        transition: all 0.3s ease;
    }

    .jodit-container:focus-within {
        border-color: #1e6f9f !important;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.08) !important;
    }

    .jodit-container .jodit-toolbar {
        background: #f8fafc !important;
        border-bottom: 1px solid #e8edf3 !important;
        border-radius: 12px 12px 0 0 !important;
    }

    .jodit-container .jodit-workplace {
        min-height: 300px;
    }

    .jodit-container .jodit-wysiwyg {
        padding: 12px 16px !important;
        font-family: 'Vazir', Tahoma, Arial, sans-serif !important;
        font-size: 14px !important;
        direction: rtl !important;
        min-height: 300px !important;
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
        gap: 15px;
        flex-wrap: wrap;
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
        text-decoration: none;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(30, 111, 159, 0.4);
        color: #fff;
    }

    .submit-btn i {
        font-size: 18px;
    }

    .btn-outline {
        background: transparent;
        color: #1e6f9f;
        border: 2px solid #1e6f9f;
        box-shadow: none;
    }

    .btn-outline:hover {
        background: #1e6f9f;
        color: #fff;
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
    }

    /* ===== NEW: Educational Content Section ===== */
    .content-section {
        background: #f8faff;
        border: 2px solid #e8edf3;
        border-radius: 16px;
        padding: 20px 25px;
        margin: 30px 0;
        transition: all 0.3s ease;
    }

    .content-section:hover {
        border-color: #c0d0e0;
    }

    .content-section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px dashed #dce4ec;
    }

    .content-section-header h5 {
        font-size: 16px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .content-section-header .badge-required {
        background: #e74c3c;
        color: white;
        font-size: 11px;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    .content-section .form-group:last-child {
        margin-bottom: 0;
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
            flex-direction: column;
        }

        .submit-btn {
            width: 100%;
            justify-content: center;
        }

        .session-title {
            font-size: 18px;
        }

        .jodit-container .jodit-toolbar {
            flex-wrap: wrap !important;
        }

        .content-section {
            padding: 15px;
        }
    }

    /* ===== ERROR STYLES ===== */
    .form-group.has-error .form-input {
        border-color: #e74c3c;
        background: #fff5f5;
    }

    .form-group.has-error .jodit-container {
        border-color: #e74c3c !important;
        background: #fff5f5;
    }

    .form-group .error-text {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 5px;
        display: block;
    }

    .form-group .error-text i {
        margin-left: 4px;
    }

    /* ===== ALERT ===== */
    .alert-danger-custom {
        background: #ffebee;
        border: 1px solid #e74c3c;
        color: #c62828;
        padding: 12px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-danger-custom i {
        font-size: 18px;
    }

    .alert-danger-custom ul {
        margin: 4px 0 0 20px;
        padding: 0;
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

        {{-- ERRORS --}}
        @if($errors->any())
            <div class="alert-danger-custom">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>خطا!</strong> لطفاً خطاهای زیر را برطرف کنید:
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- FORM --}}
        <form class="session-form" action="{{ route('sessions.store', $course->id) }}" method="post" enctype="multipart/form-data" id="sessionForm">
            @csrf

            <div class="form-group" hidden>
                <label class="form-label" for="number">شماره جلسه</label>
                <div class="input-wrapper">
                    <i class="fas fa-sort-numeric-up input-icon"></i>
                    <input class="form-input" id="number" name="number" type="number" value="{{ $nextSessionNumber }}" readonly>
                </div>
            </div>

            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
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
                    <span class="error-text"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
                <label class="form-label">طرح درس یا محتوای درس (اختیاری)</label>
                <textarea class="jodit-editor" id="sessionEditor" name="text" 
                          placeholder="متن جلسه را وارد کنید...">{{ old('text') }}</textarea>
                @error('text')
                    <span class="error-text"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            {{-- ===== NEW: EDUCATIONAL CONTENT SECTION ===== --}}
            <div class="content-section">
                <div class="content-section-header">
                    <h5>📚 محتوای آموزشی جلسه</h5>
                    <span class="badge-required">انتخاب حداقل یک گزینه الزامی است.</span>
                </div>

                <div class="form-row">
                    <div class="form-group {{ $errors->has('link') ? 'has-error' : '' }}">
                        <label class="form-label" for="link">لینک درس (اختیاری)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-link input-icon"></i>
                            <input class="form-input" id="link" name="link" type="text" 
                                   placeholder="https://example.com" value="{{ old('link') }}">
                        </div>
                        @error('link')
                            <span class="error-text"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group {{ $errors->has('majazi') ? 'has-error' : '' }}">
                        <label class="form-label" for="majazi">لینک فیلم ضبط شده کلاس (اختیاری)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-video input-icon"></i>
                            <input class="form-input" id="majazi" name="majazi" type="text" 
                                   placeholder="https://example.com" value="{{ old('majazi') }}">
                        </div>
                        @error('majazi')
                            <span class="error-text"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group {{ $errors->has('aparat') ? 'has-error' : '' }}">
                    <label class="form-label" for="aparat">لینک آپارات (اختیاری)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-film input-icon"></i>
                        <input class="form-input" id="aparat" name="aparat" type="text" 
                               placeholder="کد اسکریپت آپارات را وارد کنید" value="{{ old('aparat') }}">
                    </div>
                    <small style="color: #6b7a8f; font-size: 12px;">کد اسکریپت آپارات را به همراه iframe یا embed کپی کنید</small>
                    @error('aparat')
                        <span class="error-text"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group {{ $errors->has('file') ? 'has-error' : '' }}">
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
                        <span class="error-text"><i class="fas fa-times-circle"></i> {{ $message }}</span>
                    @enderror
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
                <a href="{{ route('view.coure', $course->id) }}" class="submit-btn btn-outline">
                    <i class="fas fa-arrow-right"></i>
                    بازگشت به درس
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
{{-- اضافه کردن Jodit --}}
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>
    // نمایش نام فایل انتخاب شده
    document.getElementById('file-upload').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'هیچ فایلی انتخاب نشده است';
        document.getElementById('file-name').textContent = fileName;
    });

    // ===== CLIENT-SIDE VALIDATION: At least one content option is required =====
    document.getElementById('sessionForm').addEventListener('submit', function(e) {
        var link = document.getElementById('link').value.trim();
        var majazi = document.getElementById('majazi').value.trim();
        var aparat = document.getElementById('aparat').value.trim();
        var file = document.getElementById('file-upload').files.length > 0;

        if (!link && !majazi && !aparat && !file) {
            e.preventDefault();
            alert('⚠️ لطفاً حداقل یکی از گزینه‌های محتوای آموزشی (لینک درس، لینک فیلم، لینک آپارات یا بارگذاری فایل) را وارد کنید.');
            // Highlight the section
            document.querySelector('.content-section').style.borderColor = '#e74c3c';
            document.querySelector('.content-section').style.background = '#fff5f5';
        } else {
            // Reset style if valid
            document.querySelector('.content-section').style.borderColor = '#e8edf3';
            document.querySelector('.content-section').style.background = '#f8faff';
        }
    });

    // ===== Jodit Editor Configuration =====
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.jodit-editor').forEach(function(element) {
            const editorId = element.id || 'editor-' + Math.random().toString(36).substr(2, 9);
            if (!element.id) {
                element.id = editorId;
            }
            
            new Jodit('#' + editorId, {
                width: '100%',
                height: 350,
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
        });
    });
</script>
@endsection