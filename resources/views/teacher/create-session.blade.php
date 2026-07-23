@extends('layout.master')

@section('title')
ملیسان | ایجاد جلسه
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-create-session.css')}}">
{{-- اضافه کردن استایل Jodit --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
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