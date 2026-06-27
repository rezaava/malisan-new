@extends('layout.master')

@section('title')
ملیسان | ویرایش تمرین
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@4.0.12/build/jodit.min.css">
<style>
    .edit-container {
        max-width: 800px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .edit-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 30px rgba(0, 0, 0, 0.06);
        padding: 35px;
    }

    .edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 28px;
    }

    .edit-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
    }

    .edit-header h3 i {
        color: #1e6f9f;
        margin-left: 10px;
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

    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 20px;
        border-top: 2px solid #f0f4f9;
        margin-top: 10px;
    }

    .btn-submit {
        padding: 12px 36px;
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

    .btn-submit-danger {
        background: linear-gradient(135deg, #f44336, #c62828);
        color: #fff;
    }

    .btn-submit-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(244, 67, 54, 0.3);
        color: #fff;
    }

    .file-info {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: #f0f4f9;
        border-radius: 10px;
        color: #1e6f9f;
        text-decoration: none;
        font-size: 14px;
    }

    .file-info:hover {
        background: #e3f2fd;
    }

    .jodit-container {
        border-radius: 12px !important;
        overflow: hidden;
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
    }

    @media (max-width: 768px) {
        .edit-card {
            padding: 20px 16px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit {
            justify-content: center;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="edit-container">
    <div class="edit-card">
        <div class="edit-header">
            <h3>
                <i class="fas fa-edit"></i>
                ویرایش تمرین
            </h3>
            <span style="font-size:14px;color:#6b7a8f;">
                <i class="fas fa-hashtag"></i> {{ $session->name }}
            </span>
        </div>

        <form method="POST" action="{{ route('exercise.update', $exercise->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>
                    متن تمرین <span class="required">*</span>
                </label>
                <textarea class="jodit-editor" name="text" id="editExerciseEditor">{{ $exercise->text }}</textarea>
                @error('text')
                    <span style="color:#f44336;font-size:13px;margin-top:4px;display:block;">
                        <i class="fas fa-times-circle"></i> {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label>فایل پیوست فعلی</label>
                @if($exercise->file)
                    <a href="{{ asset($exercise->file) }}" class="file-info" target="_blank">
                        <i class="fas fa-paperclip"></i>
                        {{ basename($exercise->file) }}
                    </a>
                @else
                    <span style="color:#6b7a8f;font-size:14px;">هیچ فایلی آپلود نشده است</span>
                @endif
            </div>

            <div class="form-group">
                <label>آپلود فایل جدید (برای جایگزینی)</label>
                <div class="file-input-wrapper">
                    <span class="file-label">
                        <i class="fas fa-upload"></i>
                        انتخاب فایل جدید
                    </span>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                </div>
                @error('file')
                    <span style="color:#f44336;font-size:13px;margin-top:4px;display:block;">
                        <i class="fas fa-times-circle"></i> {{ $message }}
                    </span>
                @enderror
                <div style="font-size:12px;color:#6b7a8f;margin-top:4px;">
                    <i class="fas fa-info-circle"></i> حداکثر حجم: 10MB
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit btn-submit-primary">
                    <i class="fas fa-save"></i>
                    بروزرسانی تمرین
                </button>

                <a href="{{ route('exercise.show', $session->id) }}" class="btn-submit btn-submit-outline">
                    <i class="fas fa-arrow-right"></i>
                    بازگشت
                </a>

                <a href="{{ route('exercise.delete', $exercise->id) }}" class="btn-submit btn-submit-danger"
                   onclick="return confirm('آیا مطمئن هستید که می‌خواهید این تمرین را حذف کنید؟')">
                    <i class="fas fa-trash-alt"></i>
                    حذف تمرین
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/jodit@4.0.12/build/jodit.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const joditConfig = {
            width: '100%',
            height: 300,
            allowResize: true,
            allowResizeImages: true,
            direction: 'rtl',
            language: 'fa',
            defaultFont: 'Vazir, Tahoma, Arial, sans-serif',
            defaultFontSize: '14px',
            fonts: ['Vazir', 'Tahoma', 'Arial', 'Courier New'],
            colors: {
                text: ['#000000', '#ff0000', '#00ff00', '#0000ff', '#ff00ff', '#00ffff', '#4caf50', '#ff9800'],
                background: ['#ffffff', '#ffff00', '#00ffff', '#ffcc99', '#e3f2fd', '#e8f5e9']
            },
            buttons: [
                'source', '|',
                'undo', 'redo', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'font', 'fontsize', 'brush', 'paragraph', '|',
                'ul', 'ol', 'outdent', 'indent', '|',
                'align', 'hr', 'table', '|',
                'link', 'unlink', 'image', 'video', '|',
                'fullsize', 'preview', '|', 'about'
            ],
            uploader: {
                url: '{{ route("upload.image") }}',
                format: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                filesVariableName: 'file',
                withCredentials: false,
                sendFilesFromClipboard: true,
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
                    return {
                        error: 'خطا در آپلود فایل'
                    };
                }
            }
        };

        const editor = new Jodit('#editExerciseEditor', joditConfig);
    });
</script>
@endsection