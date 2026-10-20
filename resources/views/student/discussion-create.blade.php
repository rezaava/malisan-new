@extends('layout.master')

@section('title')
    ملیسان | {{ isset($discussion) && $discussion ? 'ویرایش گزارش' : 'گزارش' }}
@endsection

@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
    <link rel="stylesheet" href="{{ asset('css/discussion-create.css') }}">
@endsection

@section('mohtava')
<div class="report-container">
    <div class="report-card">
        <div class="report-header">
            <h3>
                <i class="fas fa-file-alt"></i>
                {{ isset($discussion) && $discussion ? 'ویرایش گزارش' : 'گزارش' }}
            </h3>

            <div class="info-badges">
                <div class="info-badge course-badge">
                    <span class="badge-icon">
                        <i class="fas fa-book-open"></i>
                    </span>
                    <span class="badge-label">درس:</span>
                    <span class="badge-value">{{ $course->name }}</span>
                </div>

                <div class="info-badge session-badge">
                    <span class="badge-icon">
                        <i class="fas fa-hashtag"></i>
                    </span>
                    <span class="badge-label">جلسه:</span>
                    <span class="badge-value">{{ $session->number }}</span>
                </div>

                <div class="info-badge topic-badge">
                    <span class="badge-icon">
                        <i class="fas fa-tag"></i>
                    </span>
                    <span class="badge-label">موضوع:</span>
                    <span class="badge-value">{{ $session->name }}</span>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert-danger-custom">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>خطا!</strong>
                    لطفاً خطاهای زیر را برطرف کنید:

                    <ul style="margin:4px 0 0 20px;padding:0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form
            method="POST"
            action="{{ isset($discussion) && $discussion
                ? route('student.discussion.update', $discussion->id)
                : route('student.discussion.store') }}"
        >
            @csrf

            @if(!isset($discussion) || !$discussion)
                <input type="hidden" name="session_id" value="{{ $session->id }}">
            @endif

            @php
                $guideText = $course->settings->ersal_gozaresh_desc
                    ?? 'موضوع اصلی این جلسه چه بود و چه هدفی داشت؟ لطفاً یک نکتهٔ آموزنده از مطالب ارائه شده را با بیانی دیگر (به زبان خودتان) بازنویسی کنید.';

                $guideText = strip_tags($guideText);
                $guideText = trim($guideText);
                $guideText = preg_replace('/^["\']+|["\']+$/', '', $guideText);
            @endphp

            <div class="guide-text-box">
                <i class="fas fa-lightbulb"></i>
                <div>
                    <span class="guide-label">راهنما:</span>
                    {{ $guideText }}
                </div>
            </div>

            <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
                <label>
                    متن گزارش <span class="required">*</span>
                </label>

                <textarea
                    class="jodit-editor"
                    name="text"
                    id="reportEditor"
                    placeholder="متن گزارش خود را وارد کنید..."
                >{{ old('text', isset($discussion) && $discussion ? $discussion->text : '') }}</textarea>

                @if($errors->has('text'))
                    <span class="error-text">
                        <i class="fas fa-times-circle"></i>
                        {{ $errors->first('text') }}
                    </span>
                @endif

                <div class="help-text">
                    <i class="fas fa-info-circle"></i>
                    می‌توانید از ابزارهای ویرایشگر برای فرمت‌دهی متن استفاده کنید.
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit btn-submit-primary">
                    <i class="fas {{ isset($discussion) && $discussion ? 'fa-save' : 'fa-paper-plane' }}"></i>
                    {{ isset($discussion) && $discussion ? 'ذخیره تغییرات' : 'ارسال گزارش' }}
                </button>

                <a
                    href="{{ route('view.coure.St', $course->id) }}"
                    class="btn-submit btn-submit-outline"
                >
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
document.addEventListener('DOMContentLoaded', function () {
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
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.accept = 'image/*';

                    input.onchange = () => {
                        const file = input.files[0];

                        if (!file) return;

                        const formData = new FormData();
                        formData.append('file', file);

                        fetch('{{ route("upload.image") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('خطا در ارتباط با سرور');
                            }

                            return response.json();
                        })
                        .then(data => {
                            if (data.files && data.files[0] && data.files[0].url) {
                                const img = document.createElement('img');
                                img.src = data.files[0].url;
                                img.style.maxWidth = '100%';
                                editor.s.insertNode(img);
                            } else {
                                alert('خطا در آپلود تصویر');
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            alert('خطا در آپلود تصویر');
                        });
                    };

                    input.click();
                }
            },
            {
                name: 'uploadVideo',
                iconURL: 'https://cdn-icons-png.flaticon.com/512/727/727245.png',
                tooltip: 'آپلود ویدیو',
                exec: (editor) => {
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.accept = 'video/*';

                    input.onchange = () => {
                        const file = input.files[0];

                        if (!file) return;

                        const formData = new FormData();
                        formData.append('file', file);

                        fetch('{{ route("upload.video") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('خطا در ارتباط با سرور');
                            }

                            return response.json();
                        })
                        .then(data => {
                            if (data.files && data.files[0] && data.files[0].url) {
                                const wrapper = document.createElement('div');
                                wrapper.classList.add('video-wrapper');

                                const video = document.createElement('video');
                                video.setAttribute('controls', '');
                                video.src = data.files[0].url;
                                video.style.maxWidth = '100%';

                                wrapper.appendChild(video);
                                editor.s.insertNode(wrapper);
                            } else {
                                alert('خطا در آپلود ویدیو');
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            alert('خطا در آپلود ویدیو');
                        });
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
});
</script>
@endsection