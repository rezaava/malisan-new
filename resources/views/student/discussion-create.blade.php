@extends('layout.master')

@section('title')
ملیسان | گزارش
@endsection

@section('head')
{{-- اضافه کردن استایل Jodit --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">

<style>
    .report-container {
        max-width: 1100px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .report-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 30px rgba(0, 0, 0, 0.06);
        padding: 35px 40px;
    }

    .report-header {
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 28px;
    }

    .report-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .report-header h3 i {
        color: #1e6f9f;
    }

    /* ===== INFO BADGES ===== */
    .info-badges {
        display: flex;
        flex-wrap: nowrap;
        gap: 12px;
        margin-top: 14px;
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 13px;
        color: #1a2332;
        border: 1px solid #e8edf3;
        transition: all 0.3s ease;
    }

    .info-badge {
        background: #f0f7fe;
        border-color: #1e6f9f;
    }

    .info-badge .badge-icon {
        width: 28px;
        height: 28px;
        background: #e3f2fd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1e6f9f;
        font-size: 12px;
        flex-shrink: 0;
    }

    .info-badge .badge-label {
        color: #6b7a8f;
        font-weight: 500;
    }

    .info-badge .badge-value {
        font-weight: 600;
        color: #1a2332;
    }

    .info-badge.course-badge {
        background: linear-gradient(135deg, #f0f7fe, #e3f2fd);
        border-color: #1e6f9f;
    }

    .info-badge.course-badge .badge-icon {
        background: #1e6f9f;
        color: #fff;
    }

    .info-badge.session-badge .badge-icon {
        background: #fff3e0;
        color: #e65100;
    }

    .info-badge.topic-badge .badge-icon {
        background: #e8f5e9;
        color: #2e7d32;
    }

    /* ===== FORM ===== */
    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #1a2332;
        margin-bottom: 6px;
    }

    .form-group label .required {
        color: #f44336;
        margin-right: 3px;
    }

    .form-group .help-text {
        font-size: 12px;
        color: #6b7a8f;
        margin-top: 4px;
    }

    .form-control {
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

    .form-control:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
        background: #fff;
    }

    .form-control:disabled {
        background: #f0f4f9;
        cursor: not-allowed;
    }

    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    /* ===== JODIT EDITOR ===== */
    .jodit-container {
        border-radius: 12px !important;
        overflow: hidden;
        border: 2px solid #e8edf3 !important;
        transition: all 0.3s ease;
    }

    .jodit-container:focus-within {
        border-color: #1e6f9f !important;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
    }

    .jodit-container .jodit-toolbar {
        background: #f8fafc !important;
        border-bottom: 1px solid #e8edf3 !important;
    }

    .jodit-container .jodit-workplace {
        min-height: 200px;
    }

    .jodit-container .jodit-wysiwyg {
        padding: 12px 16px !important;
        font-family: 'Vazir', Tahoma, Arial, sans-serif !important;
        font-size: 14px !important;
        direction: rtl !important;
        min-height: 200px !important;
    }

    .guide-text-box {
        background: #f0f7fe;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 18px;
        border-right: 4px solid #1e6f9f;
        font-size: 14px;
        color: #1a2332;
        line-height: 1.9;
    }

    .guide-text-box i {
        color: #1e6f9f;
        font-size: 18px;
        margin-left: 10px;
    }

    .guide-text-box .guide-label {
        font-weight: 600;
        color: #1e6f9f;
        display: inline-block;
        margin-left: 6px;
    }

    /* ===== FORM ACTIONS ===== */
    .form-actions {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        padding-top: 24px;
        border-top: 2px solid #f0f4f9;
        margin-top: 10px;
    }

    .btn-submit {
        padding: 12px 40px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: inherit;
        text-decoration: none;
    }

    .btn-submit-primary {
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
    }

    .btn-submit-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 111, 159, 0.3);
        color: #fff;
    }

    .btn-submit-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn-submit-outline {
        background: transparent;
        color: #1e6f9f;
        border: 2px solid #1e6f9f;
    }

    .btn-submit-outline:hover {
        background: #1e6f9f;
        color: #fff;
        transform: translateY(-2px);
    }

    /* ===== ERROR STYLES ===== */
    .form-group.has-error .form-control {
        border-color: #f44336;
        background: #fff5f5;
    }

    .form-group.has-error .jodit-container {
        border-color: #f44336 !important;
        background: #fff5f5;
    }

    .form-group .error-text {
        color: #f44336;
        font-size: 13px;
        margin-top: 6px;
        display: block;
    }

    .form-group .error-text i {
        margin-left: 4px;
    }

    /* ===== ALERT ===== */
    .alert-danger-custom {
        background: #ffebee;
        border: 1px solid #f44336;
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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .report-card {
            padding: 20px 16px;
        }

        .info-badges {
            flex-direction: column;
            gap: 8px;
        }

        .info-badge {
            width: 100%;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit {
            justify-content: center;
        }

        .jodit-container .jodit-toolbar {
            flex-wrap: wrap !important;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="report-container">
    <div class="report-card">
        {{-- HEADER --}}
        <div class="report-header">
            <h3>
                <i class="fas fa-file-alt"></i>
                گزارش
            </h3>

            {{-- INFO BADGES --}}
            <div class="info-badges">
                {{-- درس --}}
                <div class="info-badge course-badge">
                    <span class="badge-icon">
                        <i class="fas fa-book-open"></i>
                    </span>
                    <span class="badge-label">درس:</span>
                    <span class="badge-value">{{ $course->name }}</span>
                </div>

                {{-- شماره جلسه --}}
                <div class="info-badge session-badge">
                    <span class="badge-icon">
                        <i class="fas fa-hashtag"></i>
                    </span>
                    <span class="badge-label">جلسه:</span>
                    <span class="badge-value">{{ $session->number }}</span>
                </div>

                {{-- موضوع جلسه --}}
                <div class="info-badge topic-badge">
                    <span class="badge-icon">
                        <i class="fas fa-tag"></i>
                    </span>
                    <span class="badge-label">موضوع:</span>
                    <span class="badge-value">{{ $session->name }}</span>
                </div>
            </div>
        </div>

        {{-- ERRORS --}}
        @if($errors->any())
            <div class="alert-danger-custom">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>خطا!</strong> لطفاً خطاهای زیر را برطرف کنید:
                    <ul style="margin:4px 0 0 20px;padding:0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('student.discussion.store') }}">
            @csrf
            <input type="hidden" name="session_id" value="{{ $session->id }}">

            {{-- راهنما --}}
            @php
                $guideText = $course->settings->ersal_gozaresh_desc ?? 'موضوع اصلی این جلسه چه بود و چه هدفی داشت؟ لطفاً یک نکتهٔ آموزنده از مطالب ارائه شده را با بیانی دیگر (به زبان خودتان) بازنویسی کنید.';
                // حذف کاراکترهای اضافی مثل نقل قول‌ها و تگ‌های HTML
                $guideText = strip_tags($guideText);
                $guideText = trim($guideText);
                // حذف نقل قول‌های اضافی
                $guideText = preg_replace('/^["\']+|["\']+$/', '', $guideText);
            @endphp
            <div class="guide-text-box">
                <i class="fas fa-lightbulb"></i>
                <div>
                    <span class="guide-label">راهنما:</span>
                    {{ $guideText }}
                </div>
            </div>

            {{-- Text --}}
            <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
                <label>
                    متن گزارش <span class="required">*</span> 
                </label>
                <textarea class="jodit-editor" name="text" id="reportEditor" 
                          placeholder="متن گزارش خود را وارد کنید...">{{ old('text') }}</textarea>
                @if($errors->has('text'))
                    <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('text') }}</span>
                @endif
                <div class="help-text">
                    <i class="fas fa-info-circle"></i> 
                    می‌توانید از ابزارهای ویرایشگر برای فرمت‌دهی متن استفاده کنید.
                </div>
            </div>

            {{-- Actions --}}
            <div class="form-actions">
                <button type="submit" class="btn-submit btn-submit-primary">
                    <i class="fas fa-paper-plane"></i>
                    ارسال گزارش
                </button>

                <a href="{{ route('view.coure.St', $course->id) }}" class="btn-submit btn-submit-outline">
                    <i class="fas fa-arrow-right"></i>
                    بازگشت به درس
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== Jodit Editor =====
        const editor = new Jodit('#reportEditor', {
            width: '100%',
            height: 300,
            direction: 'rtl',
            language: 'fa',
            defaultFont: 'Vazir, Tahoma, Arial, sans-serif',
            defaultFontSize: '14px',
            fonts: ['Vazir', 'Tahoma', 'Arial', 'Courier New'],
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
            }
        });

        // ===== اگر مقدار old وجود داشت، تنظیم کن =====
        @if(old('text'))
            editor.value = `{!! old('text') !!}`;
        @endif
    });
</script>
@endsection