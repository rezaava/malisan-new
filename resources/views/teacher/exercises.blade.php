@extends('layout.master')

@section('title')
ملیسان | تمرین‌های {{ $session->name }}
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/teacher-exercises.css') }}">
<link rel="stylesheet" href="{{ asset('css/badge.css') }}">
@endsection

@section('mohtava')
<div class="exercise-container">
    {{-- HEADER --}}
    <div class="exercise-header">
        <div>
            <div class="info-badge course-badge">
                <span class="badge-icon">
                    <i class="fas fa-book-open"></i>
                </span>
                <span class="badge-label">بانک سوالات در درس:</span>
                <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
            </div>
            <div class="info-badge topic-badge">
                <span class="badge-icon">
                    <i class="fas fa-tag"></i>
                </span>
                <span class="badge-label">موضوع:</span>
                <span class="badge-value">{{ $session->name }}</span>
            </div>
        </div>
        @include('layout.backbtn')
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
                            <textarea class="jodit-editor" id="answerEditor{{ $key }}" name="text" 
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
                    <textarea class="jodit-editor" id="createExerciseEditor" name="text" 
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

@section('js')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تنظیم تمام ادیتورهای موجود در صفحه
        document.querySelectorAll('.jodit-editor').forEach(function(element) {
            const editorId = element.id || 'editor-' + Math.random().toString(36).substr(2, 9);
            if (!element.id) {
                element.id = editorId;
            }
            
            new Jodit('#' + editorId, {
                width: '100%',
                height: 200,
                allowResize: true,
                allowResizeImages: true,
                direction: 'rtl',
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