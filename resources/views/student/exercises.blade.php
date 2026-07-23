@extends('layout.master')

@section('title')
ملیسان | تکالیف {{ $course->name }}
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
<link rel="stylesheet" href="{{ asset('css/exercises.css') }}">

@endsection

@section('mohtava')
<div class="exercises-container">

    @if($exercises->count() > 0)
        @foreach($sessions as $session)
            @php
                $sessionExercises = $exercises->filter(function($e) use ($session) {
                    return $e->session_id == $session->id;
                });
            @endphp
            @if($sessionExercises->count() > 0)
                <div class="session-section">
                    @foreach($sessionExercises as $key => $exercise)
                        @php
                            $hasAnswer = isset($exercise->user_answer);
                            $isScored = $hasAnswer && !is_null($exercise->user_answer->status);
                            $statusText = '';
                            $statusClass = '';
                            $scoreClass = '';
                            $scoreText = '';
                            
                            if ($hasAnswer) {
                                if ($isScored) {
                                    $statusText = 'ارزیابی شده';
                                    $statusClass = 'scored';
                                    $scoreText = ['', 'عالی', 'خوب', 'متوسط', 'بد'][$exercise->user_answer->status] ?? 'نامشخص';
                                    $scoreClass = ['', 'score-excellent', 'score-good', 'score-medium', 'score-weak'][$exercise->user_answer->status] ?? '';
                                } elseif ($exercise->user_answer->status === 'returned') {
                                    $statusText = 'برگشت خورده';
                                    $statusClass = 'returned';
                                } else {
                                    $statusText = 'پاسخ ارسال شده';
                                    $statusClass = 'answered';
                                }
                            } else {
                                $statusText = 'پاسخ داده نشده';
                                $statusClass = 'not-answered';
                            }
                        @endphp
                        
                        <div class="exercise-card">
                            <div class="card-header">
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
                                        <span class="badge-value">{{ $currentSession->number }}</span>
                                    </div>
                    
                                    {{-- موضوع جلسه --}}
                                    <div class="info-badge topic-badge">
                                        <span class="badge-icon">
                                            <i class="fas fa-tag"></i>
                                        </span>
                                        <span class="badge-label">موضوع:</span>
                                        <span class="badge-value">{{ $currentSession->name }}</span>
                                    </div>
                                </div>                                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                    <span class="status-badge {{ $statusClass }}">
                                        @if($statusClass == 'scored')
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($statusClass == 'returned')
                                            <i class="fas fa-undo"></i>
                                        @elseif($statusClass == 'answered')
                                            <i class="fas fa-check-circle"></i>
                                        @else
                                            <i class="fas fa-clock"></i>
                                        @endif
                                        {{ $statusText }}
                                    </span>
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

                            <div class="answer-form">
                                @if($hasAnswer && $isScored)
                                    {{-- پاسخ قفل شده - نمره ثبت شده --}}
                                    <div class="locked-answer">
                                        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                                            <strong style="color:#1a2332;">
                                                <i class="fas fa-lock lock-icon"></i>
                                                پاسخ شما
                                            </strong>
                                            <span class="score-display {{ $scoreClass }}">
                                                <i class="fas fa-star" style="color:#ff9800;"></i>
                                                نمره: {{ $scoreText }}
                                            </span>
                                        </div>
                                        <div class="answer-content">
                                            {!! $exercise->user_answer->answer ?? '<span style="color:#6b7a8f;">(پاسخ متنی ارسال نشده)</span>' !!}
                                        </div>
                                        @if($exercise->user_answer->comment)
                                            <div style="margin-top:8px;padding:8px 12px;background:#e3f2fd;border-radius:8px;font-size:13px;color:#1a2332;">
                                                <i class="fas fa-comment" style="color:#1e6f9f;"></i>
                                                {{ $exercise->user_answer->comment }}
                                            </div>
                                        @endif
                                        @if($exercise->user_answer->file)
                                            <div style="margin-top:8px;">
                                                <a href="{{ asset($exercise->user_answer->file) }}" target="_blank" style="color:#1e6f9f;font-size:13px;">
                                                    <i class="fas fa-paperclip"></i>
                                                    دانلود فایل ارسال شده
                                                </a>
                                            </div>
                                        @endif
                                        <div style="margin-top:10px;padding:8px 12px;background:#f5f5f5;border-radius:8px;font-size:12px;color:#9e9e9e;">
                                            <i class="fas fa-info-circle"></i>
                                            این پاسخ قبلاً ارزیابی شده است و قابل ویرایش نمی‌باشد.
                                        </div>
                                    </div>
                                    
                                    {{-- دکمه غیرفعال --}}
                                    <button class="btn-submit btn-disabled" disabled>
                                        <i class="fas fa-lock"></i>
                                        غیرقابل ویرایش
                                    </button>
                                    
                                @else
                                    {{-- فرم پاسخگویی --}}
                                    <form method="POST" action="{{ route('student.exercise.answer') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="exercise_id" value="{{ $exercise->id }}">

                                        <div class="form-group">
                                            <label>پاسخ شما</label>
                                            <textarea class="jodit-editor" name="text" id="answerEditor{{ $exercise->id }}" 
                                                      placeholder="پاسخ خود را وارد کنید...">{{ $hasAnswer ? $exercise->user_answer->answer : '' }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>فایل پیوست (اختیاری)</label>
                                            <div class="file-upload-wrapper">
                                                <span class="file-upload-label">
                                                    <i class="fas fa-upload"></i>
                                                    انتخاب فایل
                                                </span>
                                                <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                                            </div>
                                            @if($hasAnswer && $exercise->user_answer->file)
                                                <div style="font-size:13px;color:#6b7a8f;margin-top:4px;">
                                                    <i class="fas fa-check-circle" style="color:#4caf50;"></i>
                                                    فایل قبلی: <a href="{{ asset($exercise->user_answer->file) }}" target="_blank" style="color:#1e6f9f;">دانلود</a>
                                                </div>
                                            @endif
                                        </div>

                                        @if($hasAnswer && $exercise->user_answer->status === 'returned')
                                            <button type="submit" class="btn-submit btn-submit-success">
                                                <i class="fas fa-edit"></i>
                                                ویرایش پاسخ
                                            </button>
                                        @elseif($hasAnswer)
                                            <button type="submit" class="btn-submit btn-submit-success">
                                                <i class="fas fa-edit"></i>
                                                بروزرسانی پاسخ
                                            </button>
                                        @else
                                            <button type="submit" class="btn-submit">
                                                <i class="fas fa-paper-plane"></i>
                                                ارسال پاسخ
                                            </button>
                                        @endif
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    @else
        <div class="empty-state">
            <span class="empty-icon"><i class="fas fa-inbox"></i></span>
            <h4>هیچ تکلیفی ثبت نشده است</h4>
            <p>هنوز هیچ تکلیفی برای این درس ایجاد نشده است.</p>
        </div>
    @endif
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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