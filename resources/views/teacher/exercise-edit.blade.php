@extends('layout.master')

@section('title')
ملیسان | ویرایش تمرین
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@4.0.12/build/jodit.min.css">
<link rel="stylesheet" href="{{ asset('css/exercise-edit.css') }}">
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