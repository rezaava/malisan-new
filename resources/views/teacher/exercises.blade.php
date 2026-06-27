@extends('layout.master')

@section('title')
ملیسان | تمرین‌های {{ $session->name }}
@endsection

@section('head')
<style>
    .exercise-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .exercise-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 30px;
    }

    .exercise-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .exercise-header h2 i {
        color: #1e6f9f;
        margin-left: 10px;
    }

    .exercise-header .subtitle {
        font-size: 14px;
        color: #6b7a8f;
        margin-top: 4px;
    }

    .btn-back {
        padding: 10px 24px;
        background: #f0f4f9;
        color: #1a2332;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #e3e8ef;
        transform: translateY(-2px);
    }

    .exercise-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 28px 30px;
        margin-bottom: 24px;
        transition: all 0.3s ease;
        border-right: 4px solid #1e6f9f;
    }

    .exercise-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.1);
    }

    .exercise-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }

    .exercise-card .card-header .exercise-number {
        font-size: 13px;
        font-weight: 600;
        color: #6b7a8f;
    }

    .exercise-card .card-header .exercise-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-action-primary {
        background: #e3f2fd;
        color: #1e6f9f;
    }
    .btn-action-primary:hover {
        background: #1e6f9f;
        color: #fff;
    }

    .btn-action-success {
        background: #e8f5e9;
        color: #2e7d32;
    }
    .btn-action-success:hover {
        background: #4caf50;
        color: #fff;
    }

    .btn-action-danger {
        background: #ffebee;
        color: #c62828;
    }
    .btn-action-danger:hover {
        background: #f44336;
        color: #fff;
    }

    .exercise-card .exercise-text {
        font-size: 15px;
        color: #1a2332;
        line-height: 1.8;
        margin-bottom: 12px;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 10px;
    }

    .exercise-card .exercise-file {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: #f0f4f9;
        border-radius: 10px;
        color: #1e6f9f;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .exercise-card .exercise-file:hover {
        background: #e3f2fd;
        transform: translateY(-2px);
    }

    .answer-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .answer-status.submitted {
        background: #e8f5e9;
        color: #2e7d32;
    }
    .answer-status.not-submitted {
        background: #ffebee;
        color: #c62828;
    }
    .answer-status.scored {
        background: #e3f2fd;
        color: #1e6f9f;
    }

    .create-exercise-form {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 28px 30px;
        margin-top: 30px;
    }

    .create-exercise-form h4 {
        font-size: 18px;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 20px;
    }

    .create-exercise-form h4 i {
        color: #1e6f9f;
        margin-left: 8px;
    }

    .form-group {
        margin-bottom: 18px;
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
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 111, 159, 0.3);
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .file-input-wrapper input[type="file"] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-input-wrapper .file-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: #f0f4f9;
        border: 2px dashed #c5cdd8;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        color: #4a5a6e;
    }

    .file-input-wrapper .file-label:hover {
        border-color: #1e6f9f;
        background: #e3f2fd;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8fafc;
        border-radius: 20px;
    }

    .empty-state .empty-icon {
        font-size: 60px;
        color: #d0d7e2;
        margin-bottom: 20px;
        display: block;
    }

    .empty-state h4 {
        color: #1a2332;
        font-size: 18px;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #6b7a8f;
        font-size: 14px;
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
        .exercise-card {
            padding: 18px 16px;
        }
        .create-exercise-form {
            padding: 18px 16px;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="exercise-container">
    {{-- HEADER --}}
    <div class="exercise-header">
        <div>
            <h2>
                <i class="fas fa-tasks"></i>
                تمرین‌های {{ $session->name }}
            </h2>
            <div class="subtitle">
                <i class="fas fa-book-open" style="margin-left:6px;color:#1e6f9f;"></i>
                {{ $course->name }}
            </div>
        </div>
        <a href="{{ route('view.coure', $course->id) }}" class="btn-back">
            <i class="fas fa-arrow-right"></i>
            بازگشت به درس
        </a>
    </div>

    {{-- EXERCISES LIST --}}
    @if($exercises->count() > 0)
        @foreach($exercises as $key => $exercise)
            <div class="exercise-card">
                <div class="card-header">
                    <span class="exercise-number">
                        <i class="fas fa-hashtag" style="color:#6b7a8f;"></i>
                        تمرین {{ $key + 1 }}
                    </span>
                    <div class="exercise-actions">
                        @if(Auth::user()->hasRole('teacher'))
                            <a href="{{ route('exercise.edit', ['exercise_id' => $exercise->id]) }}" class="btn-action btn-action-primary">
                                <i class="fas fa-edit"></i> ویرایش
                            </a>
                            <a href="{{ route('exercise.answers', $exercise->id) }}" class="btn-action btn-action-success">
                                <i class="fas fa-users"></i> پاسخ‌ها ({{ $exercise->answers_count ?? 0 }})
                            </a>
                            <a href="{{ route('exercise.delete', $exercise->id) }}" class="btn-action btn-action-danger"
                               onclick="return confirm('آیا مطمئن هستید؟')">
                                <i class="fas fa-trash-alt"></i> حذف
                            </a>
                        @else
                            @if(isset($exercise->user_answer))
                                @if($exercise->user_answer->status == 'scored')
                                    <span class="answer-status scored">
                                        <i class="fas fa-check-circle"></i> نمره: {{ $exercise->user_answer->score }}
                                    </span>
                                @else
                                    <span class="answer-status submitted">
                                        <i class="fas fa-check-circle"></i> پاسخ ارسال شده
                                    </span>
                                @endif
                            @else
                                <span class="answer-status not-submitted">
                                    <i class="fas fa-clock"></i> پاسخ داده نشده
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="exercise-text">
                    {!! $exercise->text !!}
                </div>

                @if($exercise->file)
                    <a href="{{ asset($exercise->file) }}" class="exercise-file" target="_blank">
                        <i class="fas fa-paperclip"></i>
                        دانلود فایل پیوست
                    </a>
                @endif

                {{-- Answer Form for Students --}}
                @if(Auth::user()->hasRole('student'))
                    <form method="POST" action="{{ route('exercise.answer') }}" enctype="multipart/form-data" style="margin-top:16px;padding-top:16px;border-top:2px solid #f0f4f9;">
                        @csrf
                        <input type="hidden" name="exercise_id" value="{{ $exercise->id }}">

                        <div class="form-group">
                            <label>پاسخ شما</label>
                            <textarea class="jodit-editor" name="text" id="answerEditor{{ $key }}" 
                                      placeholder="پاسخ خود را وارد کنید...">{{ isset($exercise->user_answer) ? $exercise->user_answer->text : '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>فایل پیوست (اختیاری)</label>
                            <div class="file-input-wrapper">
                                <span class="file-label">
                                    <i class="fas fa-upload"></i>
                                    انتخاب فایل
                                </span>
                                <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                            </div>
                            @if(isset($exercise->user_answer) && $exercise->user_answer->file)
                                <div class="file-name" style="font-size:13px;color:#6b7a8f;margin-top:4px;">
                                    <i class="fas fa-check-circle" style="color:#4caf50;"></i>
                                    فایل قبلی: <a href="{{ asset($exercise->user_answer->file) }}" target="_blank" style="color:#1e6f9f;">دانلود</a>
                                </div>
                            @endif
                        </div>

                        @if(isset($exercise->user_answer) && $exercise->user_answer->status != 'scored')
                            <button type="submit" class="btn-submit" style="background:linear-gradient(135deg,#ff9800,#e65100);">
                                <i class="fas fa-edit"></i>
                                بروزرسانی پاسخ
                            </button>
                        @elseif(!isset($exercise->user_answer))
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane"></i>
                                ارسال پاسخ
                            </button>
                        @endif

                        @if(isset($exercise->user_answer) && $exercise->user_answer->status == 'scored')
                            <div style="margin-top:12px;padding:12px 16px;background:#e3f2fd;border-radius:10px;">
                                <strong style="color:#1e6f9f;">نمره: {{ $exercise->user_answer->score }}</strong>
                                @if($exercise->user_answer->comment)
                                    <p style="margin:4px 0 0;color:#4a5a6e;font-size:14px;">
                                        <i class="fas fa-comment"></i> {{ $exercise->user_answer->comment }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </form>
                @endif
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <span class="empty-icon">
                <i class="fas fa-file-alt"></i>
            </span>
            <h4>هیچ تمرینی برای این جلسه ثبت نشده است</h4>
            <p>هنوز تمرینی برای این جلسه ایجاد نشده است.</p>
        </div>
    @endif

    {{-- CREATE EXERCISE FORM (TEACHER ONLY) --}}
    @if(Auth::user()->hasRole('teacher'))
        <div class="create-exercise-form">
            <h4>
                <i class="fas fa-plus-circle"></i>
                ایجاد تمرین جدید
            </h4>

            <form method="POST" action="{{ route('exercise.create') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="session_id" value="{{ $session->id }}">

                <div class="form-group">
                    <label>
                        متن تمرین <span class="required">*</span>
                    </label>
                    <textarea class="jodit-editor" name="text" id="createExerciseEditor" 
                              placeholder="متن تمرین را وارد کنید...">{{ old('text') }}</textarea>
                    @error('text')
                        <span style="color:#f44336;font-size:13px;margin-top:4px;display:block;">
                            <i class="fas fa-times-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>فایل پیوست (اختیاری)</label>
                    <div class="file-input-wrapper">
                        <span class="file-label">
                            <i class="fas fa-upload"></i>
                            انتخاب فایل
                        </span>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                    </div>
                    @error('file')
                        <span style="color:#f44336;font-size:13px;margin-top:4px;display:block;">
                            <i class="fas fa-times-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-plus"></i>
                    ایجاد تمرین
                </button>
            </form>
        </div>
    @endif
</div>
@endsection

@section('script')
<script src="{{ asset('textEditor/text.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== تنظیمات عمومی Jodit =====
        const baseConfig = {
            width: '100%',
            height: 250,
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
        };

        // ===== فعال‌سازی همه ادیتورها =====
        document.querySelectorAll('.jodit-editor').forEach(function(editor) {
            const value = editor.value;
            const jodit = new Jodit(editor, baseConfig);
            if (value) {
                jodit.value = value;
            }
        });
    });
</script>
@endsection