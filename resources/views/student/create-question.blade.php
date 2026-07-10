@extends('layout.master')

@section('title')
ملیسان | طرح سوال
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-create-question.css')}}">
{{-- اضافه کردن استایل Jodit --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
@endsection

@section('mohtava')
<div class="question-container">
    <div class="question-card">
        <div class="question-header">
            <h4><i class="fas fa-question-circle"></i> طرح سوال</h4>
            <p>سوال خود را با دقت وارد کنید و گزینه صحیح را مشخص نمایید</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                <ul style="margin: 0; padding-right: 20px;">
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
                <div class="form-group option-item" data-option="0">
                    <label for="option-0">
                        گزینه ۱ 
                        <span class="badge-correct" style="display: none;">✓ صحیح</span>
                        <span class="correct-label" style="display: none; color: #4caf50; font-weight: 600; font-size: 13px;">(گزینه صحیح)</span>
                    </label>
                    <div class="option-input-wrapper">
                        <input type="text" id="option-0" name="options[]" class="form-input" placeholder="متن گزینه ۱ را وارد کنید" value="{{ old('options.0') }}">
                        <button type="button" class="set-correct-btn" data-option="0" title="تنظیم به عنوان پاسخ صحیح">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group option-item" data-option="1">
                    <label for="option-1">
                        گزینه ۲
                        <span class="badge-correct" style="display: none;">✓ صحیح</span>
                        <span class="correct-label" style="display: none; color: #4caf50; font-weight: 600; font-size: 13px;">(گزینه صحیح)</span>
                    </label>
                    <div class="option-input-wrapper">
                        <input type="text" id="option-1" name="options[]" class="form-input" placeholder="متن گزینه ۲ را وارد کنید" value="{{ old('options.1') }}">
                        <button type="button" class="set-correct-btn" data-option="1" title="تنظیم به عنوان پاسخ صحیح">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group option-item" data-option="2">
                    <label for="option-2">
                        گزینه ۳
                        <span class="badge-correct" style="display: none;">✓ صحیح</span>
                        <span class="correct-label" style="display: none; color: #4caf50; font-weight: 600; font-size: 13px;">(گزینه صحیح)</span>
                    </label>
                    <div class="option-input-wrapper">
                        <input type="text" id="option-2" name="options[]" class="form-input" placeholder="متن گزینه ۳ را وارد کنید" value="{{ old('options.2') }}">
                        <button type="button" class="set-correct-btn" data-option="2" title="تنظیم به عنوان پاسخ صحیح">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group option-item" data-option="3">
                    <label for="option-3">
                        گزینه ۴
                        <span class="badge-correct" style="display: none;">✓ صحیح</span>
                        <span class="correct-label" style="display: none; color: #4caf50; font-weight: 600; font-size: 13px;">(گزینه صحیح)</span>
                    </label>
                    <div class="option-input-wrapper">
                        <input type="text" id="option-3" name="options[]" class="form-input" placeholder="متن گزینه ۴ را وارد کنید" value="{{ old('options.3') }}">
                        <button type="button" class="set-correct-btn" data-option="3" title="تنظیم به عنوان پاسخ صحیح">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </div>
                </div>
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

<style>
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
    }
    
    .set-correct-btn {
        background: none;
        border: 2px solid #ddd;
        border-radius: 50%;
        width: 40px;
        height: 40px;
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
</style>
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
</script>
@endsection