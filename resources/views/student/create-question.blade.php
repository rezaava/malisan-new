@extends('layout.master')

@section('title')
ملیسان | طرح سوال
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-create-question.css')}}">
{{-- اضافه کردن استایل Jodit --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">

<style>
    /* ===== دکمه‌های هدر ===== */
    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        border-bottom: 2px solid #f0f4f9;
        padding-bottom: 20px;
        margin-bottom: 28px;
    }

    .question-header .header-left h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .question-header .header-left h4 i.fa-question-circle {
        color: #1e6f9f;
    }

    .question-header .header-left h4 .help-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #e3f2fd;
        color: #1e6f9f;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .question-header .header-left h4 .help-icon:hover {
        background: #1e6f9f;
        color: #fff;
        transform: scale(1.1);
    }

    .question-header .header-left p {
        margin: 6px 0 0;
        color: #6b7a8f;
        font-size: 14px;
    }

    .question-header .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .header-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-family: inherit;
    }

    .header-btn-secondary {
        background: #f0f4f9;
        color: #4a5a6e;
    }

    .header-btn-secondary:hover {
        background: #e3e8ef;
        transform: translateY(-2px);
    }

    .header-btn-primary {
        background: #1e6f9f;
        color: #fff;
    }

    .header-btn-primary:hover {
        background: #155a82;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 111, 159, 0.3);
    }

    /* ===== کلاس‌های جایگزین استایل‌های اینلاین ===== */

    /* کلاس‌های مربوط به پیام‌های موفقیت و خطا */
    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 15px;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 15px;
        border: 1px solid #f5c6cb;
    }

    .alert-danger ul {
        margin: 0;
        padding-right: 20px;
    }

    /* کلاس‌های مربوط به مودال و وضعیت‌های بارگذاری */
    .loading-container {
        text-align: center;
        padding: 30px;
    }

    .loading-spinner {
        color: #1e6f9f;
    }

    .loading-text {
        color: #6b7a8f;
        margin-top: 10px;
    }

    /* کلاس‌های مربوط به بخش توضیحات تنظیمات */
    .setting-score-box {
        margin-top: 16px;
        padding: 12px 16px;
        background: #e3f2fd;
        border-radius: 10px;
        font-size: 14px;
    }

    .setting-score-icon {
        color: #ff9800;
    }

    /* کلاس‌های مربوط به آیتم‌های سوال در لیست */
    .question-list-item .q-options .correct-option {
        color: #4caf50;
        font-weight: 600;
    }

    /* ===== مودال ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-box {
        background: #fff;
        border-radius: 20px;
        width: 90%;
        max-width: 750px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 30px;
        position: relative;
        animation: slideUp 0.3s ease;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-box::-webkit-scrollbar {
        width: 6px;
    }

    .modal-box::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .modal-box::-webkit-scrollbar-thumb {
        background: #1e6f9f;
        border-radius: 10px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 20px;
    }

    .modal-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1a2332;
    }

    .modal-header h4 i {
        color: #1e6f9f;
        margin-left: 8px;
    }

    .modal-close-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: #f0f4f9;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4a5a6e;
    }

    .modal-close-btn:hover {
        background: #e74c3c;
        color: #fff;
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 5px 0;
    }

    /* ===== لیست سوالات در مودال ===== */
    .question-list-item {
        padding: 14px 16px;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
        background: #fafbfc;
    }

    .question-list-item:hover {
        border-color: #1e6f9f;
        background: #fff;
    }

    .question-list-item .q-text {
        font-size: 14px;
        color: #1a2332;
        line-height: 1.7;
        margin-bottom: 8px;
    }

    .question-list-item .q-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
        font-size: 13px;
        color: #4a5a6e;
    }

    .question-list-item .q-options .correct {
        color: #4caf50;
        font-weight: 600;
    }

    .question-list-item .q-meta {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        font-size: 12px;
        color: #6b7a8f;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #e8edf3;
    }

    .question-list-item .q-meta .badge {
        padding: 2px 10px;
        border-radius: 12px;
        font-weight: 600;
    }

    .badge-excellent {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-good {
        background: #e3f2fd;
        color: #1e6f9f;
    }

    .badge-medium {
        background: #fff3e0;
        color: #e65100;
    }

    .badge-bad {
        background: #ffebee;
        color: #c62828;
    }

    .badge-pending {
        background: #f5f5f5;
        color: #6b7a8f;
    }

    .badge-returned {
        background: #ffebee;
        color: #c62828;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state .empty-icon {
        font-size: 50px;
        color: #d0d7e2;
        display: block;
        margin-bottom: 16px;
    }

    .empty-state h5 {
        color: #1a2332;
        font-size: 16px;
        margin-bottom: 6px;
    }

    .empty-state p {
        color: #6b7a8f;
        font-size: 14px;
        max-width: 400px;
        margin: 0 auto;
    }

    /* ===== توضیحات تنظیمات ===== */
    .setting-description {
        padding: 16px 20px;
        background: #f8fafc;
        border-radius: 12px;
        border-right: 4px solid #1e6f9f;
        line-height: 1.8;
        color: #1a2332;
        font-size: 14px;
    }

    /* ===== فرم ===== */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #1a2332;
        margin-bottom: 6px;
    }

    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .option-item {
        transition: all 0.3s ease;
        padding: 10px;
        border-radius: 8px;
        border: 2px solid transparent;
    }

    .option-item.is-correct {
        background: #e8f5e9;
        border-color: #4caf50;
    }

    .option-item.is-correct .badge-correct {
        display: inline-block !important;
    }

    .option-item.is-correct .correct-label {
        display: inline-block !important;
    }

    .option-item .badge-correct {
        display: none;
        background: #4caf50;
        color: white;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        margin-right: 5px;
    }

    .option-item .correct-label {
        display: none;
    }

    .option-input-wrapper {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .option-input-wrapper .form-input {
        flex: 1;
        padding: 10px 14px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        font-family: inherit;
    }

    .option-input-wrapper .form-input:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.08);
        background: #fff;
    }

    .set-correct-btn {
        background: none;
        border: 2px solid #ddd;
        border-radius: 50%;
        width: 42px;
        height: 42px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        color: #999;
        flex-shrink: 0;
        font-size: 18px;
    }

    .set-correct-btn:hover {
        border-color: #4caf50;
        color: #4caf50;
        transform: scale(1.1);
    }

    .set-correct-btn.is-correct-btn {
        border-color: #4caf50;
        background: #4caf50;
        color: white;
    }

    .set-correct-btn.is-correct-btn:hover {
        background: #388e3c;
        border-color: #388e3c;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 2px solid #f0f4f9;
    }

    .submit-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 32px;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
    }

    .reset-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 32px;
        background: transparent;
        color: #4a5a6e;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .reset-btn:hover {
        background: #f0f4f9;
    }

    /* ===== انیمیشن‌ها ===== */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .question-header {
            flex-direction: column;
            align-items: stretch;
        }

        .question-header .header-actions {
            justify-content: flex-start;
        }

        .modal-box {
            padding: 20px;
            width: 95%;
        }

        .question-list-item .q-options {
            grid-template-columns: 1fr;
        }

        .header-btn {
            padding: 8px 14px;
            font-size: 13px;
        }

        .question-header .header-left h4 {
            font-size: 18px;
        }

        .options-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .submit-btn, .reset-btn {
            justify-content: center;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="question-container">
    <div class="question-card">
        {{-- ===== HEADER با دکمه‌ها ===== --}}
        <div class="question-header">
            <div class="header-left">
                <h4>
                    طرح سوال برای جلسه {{ $session->number }}: {{ $session->name }}
                    <button class="help-icon" onclick="openSettingModal()" title="راهنمای طرح سوال">
                        <i class="fas fa-question"></i>
                    </button>
                </h4>
                <p>سوال خود را با دقت وارد کنید و گزینه صحیح را مشخص نمایید</p>
            </div>
            <div class="header-actions">
                {{-- دکمه سوالات دوستان --}}
                <button class="header-btn header-btn-secondary" onclick="openFriendsQuestionsModal()">
                    <i class="fas fa-users"></i>
                    سوالات دوستان
                </button>
                {{-- دکمه سوالات من --}}
                <button class="header-btn header-btn-primary" onclick="openMyQuestionsModal()">
                    <i class="fas fa-user"></i>
                    سوالات من
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="question-form" action="{{ route('student.question.store', $session->id) }}" method="POST">
            @csrf
            
            {{-- ===== متن سوال با Jodit ===== --}}
            <div class="form-group">
                <label for="question-text">متن سوال <span style="color:red;">*</span></label>
                <textarea class="jodit-editor" id="questionEditor" name="question" 
                          placeholder="متن سوال را وارد کنید...">{{ old('question') }}</textarea>
            </div>

            {{-- ===== گزینه‌ها ===== --}}
            <div class="options-grid">
                @for($i = 0; $i < 4; $i++)
                    <div class="form-group option-item" data-option="{{ $i }}">
                        <label for="option-{{ $i }}">
                            گزینه {{ $i + 1 }}
                            <span class="badge-correct">✓</span>
                            <span class="correct-label">(گزینه صحیح)</span>
                        </label>
                        <div class="option-input-wrapper">
                            <input type="text" id="option-{{ $i }}" name="options[]" class="form-input" 
                                   placeholder="متن گزینه {{ $i + 1 }} را وارد کنید" 
                                   value="{{ old('options.'.$i) }}">
                            <button type="button" class="set-correct-btn" data-option="{{ $i }}" title="تنظیم به عنوان پاسخ صحیح">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </div>
                    </div>
                @endfor
            </div>

            <input type="hidden" name="correct_answer" id="correct_answer" value="{{ old('correct_answer', 0) }}">

            <div class="form-actions">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-save"></i>
                    ثبت سوال
                </button>
                <button type="reset" class="reset-btn">
                    <i class="fas fa-undo"></i>
                    لغو
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================== --}}
{{-- مودال ۱: سوالات دوستان --}}
{{-- ========================================== --}}
<div class="modal-overlay" id="friendsQuestionsModal">
    <div class="modal-box">
        <div class="modal-header">
            <h4>
                <i class="fas fa-users"></i>
                سوالات دوستان
            </h4>
            <button class="modal-close-btn" onclick="closeModal('friendsQuestionsModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="friendsQuestionsModalBody">
            {{-- محتوا توسط جاوااسکریپت پر می‌شود --}}
            <div class="loading-container">
                <i class="fas fa-spinner fa-spin fa-2x loading-spinner"></i>
                <p class="loading-text">در حال بارگذاری...</p>
            </div>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- مودال ۲: سوالات من --}}
{{-- ========================================== --}}
<div class="modal-overlay" id="myQuestionsModal">
    <div class="modal-box">
        <div class="modal-header">
            <h4>
                <i class="fas fa-user"></i>
                سوالات من در این جلسه
            </h4>
            <button class="modal-close-btn" onclick="closeModal('myQuestionsModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="myQuestionsModalBody">
            {{-- محتوا توسط جاوااسکریپت پر می‌شود --}}
            <div class="loading-container">
                <i class="fas fa-spinner fa-spin fa-2x loading-spinner"></i>
                <p class="loading-text">در حال بارگذاری...</p>
            </div>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- مودال ۳: توضیحات تنظیمات --}}
{{-- ========================================== --}}
<div class="modal-overlay" id="settingModal">
    <div class="modal-box">
        <div class="modal-header">
            <h4>
                <i class="fas fa-info-circle"></i>
                راهنمای طرح سوال
            </h4>
            <button class="modal-close-btn" onclick="closeModal('settingModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="setting-description">
                {!! $settingDescription !!}
            </div>
            @if($settingScore > 0)
                <div class="setting-score-box">
                    <i class="fas fa-star setting-score-icon"></i>
                    <strong>امتیاز این فعالیت:</strong> {{ $settingScore }} امتیاز
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
{{-- اضافه کردن اسکریپت Jodit --}}
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ==========================================
        // مقداردهی Jodit Editor
        // ==========================================
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

        // ==========================================
        // منطق انتخاب گزینه صحیح
        // ==========================================
        const optionItems = document.querySelectorAll('.option-item');
        const correctAnswerInput = document.getElementById('correct_answer');
        
        function clearCorrectStyles() {
            optionItems.forEach(item => {
                item.classList.remove('is-correct');
                const btn = item.querySelector('.set-correct-btn');
                if (btn) {
                    btn.classList.remove('is-correct-btn');
                }
            });
        }
        
        function setCorrectOption(optionIndex) {
            clearCorrectStyles();
            
            const selectedItem = document.querySelector(`.option-item[data-option="${optionIndex}"]`);
            if (selectedItem) {
                selectedItem.classList.add('is-correct');
                const btn = selectedItem.querySelector('.set-correct-btn');
                if (btn) {
                    btn.classList.add('is-correct-btn');
                }
                correctAnswerInput.value = optionIndex;
            }
        }
        
        document.querySelectorAll('.set-correct-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const optionIndex = this.getAttribute('data-option');
                setCorrectOption(parseInt(optionIndex));
            });
        });
        
        const oldCorrect = correctAnswerInput.value;
        if (oldCorrect) {
            setCorrectOption(parseInt(oldCorrect));
        } else {
            setCorrectOption(0);
        }
    });

    // ==========================================
    // توابع مودال
    // ==========================================
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
        document.body.style.overflow = '';
    }

    // بستن مودال با کلیک روی پس‌زمینه
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // بستن مودال با کلید ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
    });

    // ==========================================
    // باز کردن مودال سوالات دوستان
    // ==========================================
    function openFriendsQuestionsModal() {
        openModal('friendsQuestionsModal');
        
        const sessionId = '{{ $session->id }}';
        const body = document.getElementById('friendsQuestionsModalBody');
        
        body.innerHTML = `
            <div class="loading-container">
                <i class="fas fa-spinner fa-spin fa-2x loading-spinner"></i>
                <p class="loading-text">در حال بارگذاری سوالات دوستان...</p>
            </div>
        `;
        
        fetch('/student/questions/list/' + sessionId)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.questions.length > 0) {
                    const userId = {{ Auth::id() }};
                    const otherQuestions = data.questions.filter(q => q.user_id != userId);
                    
                    if (otherQuestions.length > 0) {
                        let html = '';
                        otherQuestions.forEach((q, index) => {
                            const options = [
                                q.answer1,
                                q.answer2,
                                q.answer3,
                                q.answer4
                            ];
                            
                            const statusBadge = getStatusBadge(q.status);
                            
                            html += `
                                <div class="question-list-item">
                                    <div class="q-text">
                                        <strong>سوال ${index + 1}:</strong> ${q.question}
                                    </div>
                                    <div class="q-options">
                                        ${options.map((opt, i) => `
                                            <span class="${q.answer == (i + 1) ? 'correct' : ''}">
                                                ${i + 1}. ${opt} ${q.answer == (i + 1) ? '✓' : ''}
                                            </span>
                                        `).join('')}
                                    </div>
                                    <div class="q-meta">
                                        <span>تاریخ: ${q.date || ''}</span>
                                    </div>
                                </div>
                            `;
                        });
                        body.innerHTML = html;
                    } else {
                        body.innerHTML = `
                            <div class="empty-state">
                                <span class="empty-icon"><i class="fas fa-inbox"></i></span>
                                <h5>هنوز دوستانت سوالی طرح نکرده‌اند</h5>
                                <p>اولین نفری باش که سوال خود را طراحی می‌کند!</p>
                            </div>
                        `;
                    }
                } else {
                    body.innerHTML = `
                        <div class="empty-state">
                            <span class="empty-icon"><i class="fas fa-inbox"></i></span>
                            <h5>تاکنون سوالی برای این جلسه طرح نشده است</h5>
                            <p>شما می‌توانید اولین سوال را طرح کنید.</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                body.innerHTML = `
                    <div class="empty-state">
                        <span class="empty-icon"><i class="fas fa-exclamation-triangle" style="color:#f44336;"></i></span>
                        <h5>خطا در بارگذاری سوالات</h5>
                        <p>مشکلی در ارتباط با سرور رخ داده است. لطفاً مجدداً تلاش کنید.</p>
                    </div>
                `;
            });
    }

    // ==========================================
    // باز کردن مودال سوالات من
    // ==========================================
    function openMyQuestionsModal() {
        openModal('myQuestionsModal');
        
        const sessionId = '{{ $session->id }}';
        const body = document.getElementById('myQuestionsModalBody');
        
        body.innerHTML = `
            <div class="loading-container">
                <i class="fas fa-spinner fa-spin fa-2x loading-spinner"></i>
                <p class="loading-text">در حال بارگذاری سوالات من...</p>
            </div>
        `;
        
        fetch('/student/questions/list/' + sessionId)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.questions.length > 0) {
                    const userId = {{ Auth::id() }};
                    const myQuestions = data.questions.filter(q => q.user_id == userId);
                    
                    if (myQuestions.length > 0) {
                        let html = '';
                        myQuestions.forEach((q, index) => {
                            const options = [
                                q.answer1,
                                q.answer2,
                                q.answer3,
                                q.answer4
                            ];
                            
                            const statusBadge = getStatusBadge(q.status);
                            
                            html += `
                                <div class="question-list-item">
                                    <div class="q-text">
                                        <strong>سوال ${index + 1}:</strong> ${q.question}
                                    </div>
                                    <div class="q-options">
                                        ${options.map((opt, i) => `
                                            <span class="${q.answer == (i + 1) ? 'correct' : ''}">
                                                ${i + 1}. ${opt} ${q.answer == (i + 1) ? '✓' : ''}
                                            </span>
                                        `).join('')}
                                    </div>
                                    <div class="q-meta">
                                        <span>طراح: ${q.user_name || 'نامشخص'}</span>
                                        <span>${statusBadge}</span>
                                        <span>تاریخ: ${q.date || ''}</span>
                                    </div>
                                </div>
                            `;
                        });
                        body.innerHTML = html;
                    } else {
                        body.innerHTML = `
                            <div class="empty-state">
                                <span class="empty-icon"><i class="fas fa-inbox"></i></span>
                                <h5>شما هنوز سوالی برای این جلسه طرح نکرده‌اید</h5>
                                <p>اولین سوال خود را طراحی کنید!</p>
                            </div>
                        `;
                    }
                } else {
                    body.innerHTML = `
                        <div class="empty-state">
                            <span class="empty-icon"><i class="fas fa-inbox"></i></span>
                            <h5>تاکنون سوالی برای این جلسه طرح نشده است</h5>
                            <p>شما می‌توانید اولین سوال را طرح کنید.</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                body.innerHTML = `
                    <div class="empty-state">
                        <span class="empty-icon"><i class="fas fa-exclamation-triangle" style="color:#f44336;"></i></span>
                        <h5>خطا در بارگذاری سوالات</h5>
                        <p>مشکلی در ارتباط با سرور رخ داده است. لطفاً مجدداً تلاش کنید.</p>
                    </div>
                `;
            });
    }

    // ==========================================
    // باز کردن مودال تنظیمات
    // ==========================================
    function openSettingModal() {
        openModal('settingModal');
    }

    // ==========================================
    // تابع کمکی برای تبدیل وضعیت به بج
    // ==========================================
    function getStatusBadge(status) {
        if (status === null || status === undefined) {
            return `<span class="badge badge-pending">در انتظار تایید</span>`;
        }
        
        if (status === 0) {
            return `<span class="badge badge-returned">برگشت خورده</span>`;
        }
        
        const statusMap = {
            1: { text: 'عالی', class: 'badge-excellent' },
            2: { text: 'خوب', class: 'badge-good' },
            3: { text: 'متوسط', class: 'badge-medium' },
            4: { text: 'بد', class: 'badge-bad' }
        };
        
        const s = statusMap[status];
        if (s) {
            return `<span class="badge ${s.class}">${s.text}</span>`;
        }
        
        return `<span class="badge badge-pending">نامشخص</span>`;
    }
</script>
@endsection