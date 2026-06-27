@extends('layout.master')

@section('title')
ملیسان | ارسال گزارش
@endsection

@section('head')
<style>
    .report-container {
        max-width: 850px;
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 28px;
    }

    .report-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
    }

    .report-header h3 i {
        color: #1e6f9f;
        margin-left: 10px;
    }

    .report-header .course-badge {
        background: #f0f4f9;
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 13px;
        color: #4a5a6e;
    }

    .report-header .course-badge i {
        color: #1e6f9f;
        margin-left: 6px;
    }

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

    @media (max-width: 768px) {
        .report-card {
            padding: 20px 16px;
        }

        .report-header {
            flex-direction: column;
            align-items: stretch;
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
                ارسال گزارش
            </h3>
            <div class="course-badge">
                <i class="fas fa-book-open"></i>
                {{ $course->name }}
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

@section('script')
    <script src="{{ asset('textEditor/assets/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('textEditor/assets/js/select2/select2.full.min.js') }}"></script>
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
                'link', 'unlink', 'image', '|',
                'fullsize', 'preview', '|', 'about'
            ],
            uploader: {
                url: '{{ route("upload.image") }}',
                format: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                filesVariableName: 'file',
                insertImageAsBase64URI: false,
                imagesExtensions: ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
                process: function (resp) {
                    if (resp.files && resp.files[0] && resp.files[0].url) {
                        return {
                            files: [{
                                url: resp.files[0].url,
                                name: resp.files[0].name || 'image',
                                size: resp.files[0].size || 0
                            }],
                            error: null
                        };
                    }
                    return { error: 'خطا در آپلود فایل' };
                }
            }
        });

        // ===== اگر مقدار old وجود داشت، تنظیم کن =====
        @if(old('text'))
            editor.value = `{!! old('text') !!}`;
        @endif
    });
</script>
@endsection