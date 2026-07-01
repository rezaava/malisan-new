@extends('layout.master')

@section('title')
ملیسان | تکالیف {{ $course->name }}
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
{{-- اضافه کردن استایل Jodit --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">

<style>
    .exercises-container {
        max-width: 1100px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .exercises-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .exercises-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .exercises-header h2 i {
        color: #1e6f9f;
        margin-left: 10px;
    }

    .exercises-header .subtitle {
        font-size: 14px;
        color: #6b7a8f;
        margin-top: 4px;
    }

    .btn-back {
        padding: 8px 20px;
        background: #f0f4f9;
        border-radius: 10px;
        text-decoration: none;
        color: #1a2332;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
    }

    .btn-back:hover {
        background: #e3e8ef;
    }

    /* ===== STATS ===== */
    .stats-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .stat-box {
        flex: 1;
        min-width: 120px;
        background: #fff;
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        text-align: center;
        border-right: 4px solid #1e6f9f;
    }

    .stat-box .number {
        font-size: 26px;
        font-weight: 800;
        color: #1a2332;
    }

    .stat-box .label {
        font-size: 12px;
        color: #6b7a8f;
    }

    .stat-box.total { border-right-color: #1e6f9f; }
    .stat-box.answered { border-right-color: #4caf50; }
    .stat-box.pending { border-right-color: #ff9800; }
    .stat-box.scored { border-right-color: #9c27b0; }

    /* ===== SESSIONS ===== */
    .session-section {
        margin-bottom: 30px;
    }

    .session-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a2332;
        padding: 12px 20px;
        background: #f8fafc;
        border-radius: 12px;
        border-right: 4px solid #1e6f9f;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .session-title .badge-count {
        font-size: 13px;
        font-weight: 600;
        color: #6b7a8f;
    }

    /* ===== EXERCISE CARDS ===== */
    .exercise-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.04);
        padding: 20px 24px;
        margin-bottom: 14px;
        border-right: 4px solid #e8edf3;
        transition: all 0.3s ease;
    }

    .exercise-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0,0,0,0.08);
    }

    .exercise-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 10px;
    }

    .exercise-card .exercise-number {
        font-size: 13px;
        font-weight: 600;
        color: #6b7a8f;
    }

    .exercise-card .exercise-text {
        font-size: 15px;
        color: #1a2332;
        line-height: 1.8;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 12px;
    }

    .exercise-card .exercise-file {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        background: #e3f2fd;
        border-radius: 8px;
        color: #1e6f9f;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .exercise-card .exercise-file:hover {
        background: #1e6f9f;
        color: #fff;
    }

    /* ===== STATUS BADGES ===== */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.answered {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge.not-answered {
        background: #ffebee;
        color: #c62828;
    }

    .status-badge.scored {
        background: #e3f2fd;
        color: #1e6f9f;
    }

    .status-badge.returned {
        background: #fff3cd;
        color: #e65100;
    }

    /* ===== ANSWER FORM ===== */
    .answer-form {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 2px solid #f0f4f9;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        color: #1a2332;
        margin-bottom: 4px;
    }

    .form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        background: #fafbfc;
        transition: all 0.3s ease;
        min-height: 80px;
        resize: vertical;
    }

    .form-group textarea:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30,111,159,0.1);
        background: #fff;
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
        box-shadow: 0 0 0 4px rgba(30,111,159,0.1);
    }

    .jodit-container .jodit-toolbar {
        background: #f8fafc !important;
        border-bottom: 1px solid #e8edf3 !important;
    }

    .jodit-container .jodit-workplace {
        min-height: 150px;
    }

    .jodit-container .jodit-wysiwyg {
        padding: 12px 16px !important;
        font-family: 'Vazir', Tahoma, Arial, sans-serif !important;
        font-size: 14px !important;
        direction: rtl !important;
        min-height: 150px !important;
    }

    .file-upload-wrapper {
        position: relative;
        display: inline-block;
    }

    .file-upload-wrapper input[type="file"] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-upload-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: #f0f4f9;
        border: 2px dashed #c5cdd8;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 500;
        color: #4a5a6e;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .file-upload-label:hover {
        border-color: #1e6f9f;
        background: #e3f2fd;
    }

    .btn-submit {
        padding: 10px 28px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30,111,159,0.3);
    }

    .btn-submit-success {
        background: linear-gradient(135deg, #4caf50, #388e3c);
    }

    .btn-submit-success:hover {
        box-shadow: 0 4px 15px rgba(76,175,80,0.3);
    }

    /* ===== EMPTY ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8fafc;
        border-radius: 16px;
    }

    .empty-state .empty-icon {
        font-size: 60px;
        color: #d0d7e2;
        display: block;
        margin-bottom: 16px;
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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .exercise-card {
            padding: 16px;
        }

        .exercise-card .card-header {
            flex-direction: column;
        }

        .stats-row {
            flex-direction: column;
        }

        .stat-box {
            min-width: auto;
        }

        .session-title {
            font-size: 16px;
            padding: 10px 16px;
        }

        .jodit-container .jodit-toolbar {
            flex-wrap: wrap !important;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="exercises-container">
    {{-- HEADER --}}
    <div class="exercises-header">
        <div>
            <h2>
                <i class="fas fa-tasks"></i>
                تکالیف
            </h2>
            <div class="subtitle">
                <i class="fas fa-book-open" style="margin-left:6px;color:#1e6f9f;"></i>
                {{ $course->name }}
            </div>
        </div>
        <a href="{{ route('view.coure.St', $course->id) }}" class="btn-back">
            <i class="fas fa-arrow-right"></i>
            بازگشت به درس
        </a>
    </div>

    {{-- STATS --}}
    <div class="stats-row">
        <div class="stat-box total">
            <div class="number">{{ $stats['total'] }}</div>
            <div class="label">کل تکالیف</div>
        </div>
        <div class="stat-box answered">
            <div class="number">{{ $stats['answered'] }}</div>
            <div class="label">پاسخ داده شده</div>
        </div>
        <div class="stat-box pending">
            <div class="number">{{ $stats['not_answered'] }}</div>
            <div class="label">پاسخ داده نشده</div>
        </div>
        <div class="stat-box scored">
            <div class="number">{{ $stats['scored'] }}</div>
            <div class="label">ارزیابی شده</div>
        </div>
    </div>

    {{-- EXERCISES BY SESSION --}}
    @if($exercises->count() > 0)
        @foreach($sessions as $session)
            @php
                $sessionExercises = $exercises->filter(function($e) use ($session) {
                    return $e->session_id == $session->id;
                });
            @endphp
            @if($sessionExercises->count() > 0)
                <div class="session-section">
                    <div class="session-title">
                        <span>
                            <i class="fas fa-video" style="color:#1e6f9f;"></i>
                            {{ $session->name }}
                        </span>
                        <span class="badge-count">
                            {{ $sessionExercises->count() }} تکلیف
                        </span>
                    </div>

                    @foreach($sessionExercises as $key => $exercise)
                        <div class="exercise-card">
                            <div class="card-header">
                                <span class="exercise-number">
                                    <i class="fas fa-hashtag" style="color:#6b7a8f;"></i>
                                    تکلیف {{ $key + 1 }}
                                </span>
                                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                    @if(isset($exercise->user_answer))
                                        @if($exercise->user_answer->status == 'scored')
                                            <span class="status-badge scored">
                                                <i class="fas fa-check-circle"></i> ارزیابی شده
                                            </span>
                                        @elseif($exercise->user_answer->status == 'returned')
                                            <span class="status-badge returned">
                                                <i class="fas fa-undo"></i> برگشت خورده
                                            </span>
                                        @else
                                            <span class="status-badge answered">
                                                <i class="fas fa-check-circle"></i> پاسخ ارسال شده
                                            </span>
                                        @endif
                                    @else
                                        <span class="status-badge not-answered">
                                            <i class="fas fa-clock"></i> پاسخ داده نشده
                                        </span>
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

                            {{-- ANSWER FORM --}}
                            <div class="answer-form">
                                <form method="POST" action="{{ route('student.exercise.answer') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="exercise_id" value="{{ $exercise->id }}">

                                    <div class="form-group">
                                        <label>پاسخ شما</label>
                                        {{-- تبدیل به Jodit Editor --}}
                                        <textarea class="jodit-editor" name="text" id="answerEditor{{ $exercise->id }}" 
                                                  placeholder="پاسخ خود را وارد کنید...">{{ isset($exercise->user_answer) ? $exercise->user_answer->answer : '' }}</textarea>
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
                                        @if(isset($exercise->user_answer) && $exercise->user_answer->file)
                                            <div style="font-size:13px;color:#6b7a8f;margin-top:4px;">
                                                <i class="fas fa-check-circle" style="color:#4caf50;"></i>
                                                فایل قبلی: <a href="{{ asset($exercise->user_answer->file) }}" target="_blank" style="color:#1e6f9f;">دانلود</a>
                                            </div>
                                        @endif
                                    </div>

                                    @if(isset($exercise->user_answer) && $exercise->user_answer->status == 'scored')
                                        <div style="padding:12px 16px;background:#e3f2fd;border-radius:10px;margin-bottom:12px;">
                                            <strong style="color:#1e6f9f;">نمره: 
                                                @if($exercise->user_answer->rate == 'excellent') عالی
                                                @elseif($exercise->user_answer->rate == 'good') خوب
                                                @elseif($exercise->user_answer->rate == 'medium') متوسط
                                                @elseif($exercise->user_answer->rate == 'weak') بد
                                                @endif
                                            </strong>
                                            @if($exercise->user_answer->comment)
                                                <p style="margin:4px 0 0;color:#4a5a6e;font-size:14px;">
                                                    <i class="fas fa-comment"></i> {{ $exercise->user_answer->comment }}
                                                </p>
                                            @endif
                                        </div>
                                    @elseif(isset($exercise->user_answer) && $exercise->user_answer->status != 'scored')
                                        <button type="submit" class="btn-submit btn-submit-success">
                                            <i class="fas fa-edit"></i>
                                            بروزرسانی پاسخ
                                        </button>
                                    @elseif(!isset($exercise->user_answer))
                                        <button type="submit" class="btn-submit">
                                            <i class="fas fa-paper-plane"></i>
                                            ارسال پاسخ
                                        </button>
                                    @endif
                                </form>
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
{{-- اضافه کردن Jodit --}}
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // مقداردهی تمام ادیتورهای موجود در صفحه
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