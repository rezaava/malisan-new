@extends('auth.layout.master')

@section('title')
پرسش اولیه
@endsection

@section('head')
<style>
    /* ============================================
       استایل‌های اصلی
       ============================================ */
    .onboarding-container {
        max-width: 700px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .onboarding-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        padding: 40px;
        text-align: center;
    }

    .onboarding-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 36px;
        color: #fff;
    }

    .onboarding-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 8px;
    }

    .onboarding-subtitle {
        font-size: 15px;
        color: #6b7a8f;
        margin-bottom: 30px;
    }

    .onboarding-question-box {
        background: #f8fafc;
        padding: 20px 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        text-align: right;
        border-right: 4px solid #1e6f9f;
    }

    .onboarding-question-text {
        font-size: 18px;
        font-weight: 600;
        color: #1a2332;
        margin: 0;
    }

    /* ============================================
       گزینه‌ها
       ============================================ */
    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 24px;
        text-align: right;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafbfc;
        user-select: none;
    }

    .option-item:hover {
        border-color: #1e6f9f;
        background: #f0f7fe;
    }

    .option-item.selected {
        border-color: #1e6f9f;
        background: #e3f2fd;
    }

    .option-item .option-radio {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #d0d7e2;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .option-item.selected .option-radio {
        border-color: #1e6f9f;
        background: #1e6f9f;
    }

    .option-item.selected .option-radio::after {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #fff;
    }

    .option-item .option-text {
        font-size: 15px;
        color: #1a2332;
        flex: 1;
    }

    .option-item input[type="radio"],
    .option-item input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        pointer-events: none;
    }

    /* ============================================
       پاسخ کوتاه (نظر کاربر)
       ============================================ */
    .text-input-wrapper {
        margin-bottom: 24px;
        text-align: right;
    }

    .text-input-wrapper label {
        display: block;
        font-weight: 600;
        color: #1a2332;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .text-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        direction: rtl;
        font-family: inherit;
        resize: vertical;
        min-height: 80px;
    }

    .text-input:focus {
        border-color: #1e6f9f;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.08);
        outline: none;
    }

    /* ============================================
       دکمه‌ها
       ============================================ */
    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-submit {
        padding: 12px 40px;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-skip {
        padding: 12px 32px;
        background: #f0f4f9;
        color: #1a2332;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-skip:hover {
        background: #e8edf3;
        transform: translateY(-2px);
    }

    /* ============================================
       پیام‌ها
       ============================================ */
    .error-message {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 16px;
        border: 1px solid #f5c6cb;
        text-align: right;
    }

    .error-message ul {
        margin: 0;
        padding-right: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 16px;
        border: 1px solid #c3e6cb;
        text-align: right;
    }

    /* ============================================
       ریسپانسیو
       ============================================ */
    @media (max-width: 768px) {
        .onboarding-card {
            padding: 24px;
        }
        .options-grid {
            grid-template-columns: 1fr;
        }
        .onboarding-title {
            font-size: 20px;
        }
        .form-actions {
            flex-direction: column;
        }
        .btn-submit,
        .btn-skip {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="onboarding-container">
    <div class="onboarding-card">
        <div class="onboarding-icon">
            <i class="fas fa-hand-wave"></i>
        </div>
        
        <h2 class="onboarding-title">خوش آمدید {{ Auth::user()->name ?? '' }}!</h2>
        <p class="onboarding-subtitle">لطفاً به این سوال پاسخ دهید</p>

        @if($errors->any())
            <div class="error-message">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('student.onboarding.submit') }}" id="onboardingForm">
            @csrf
            <input type="hidden" name="survey_id" value="{{ $survey->id ?? '' }}">
            <input type="hidden" name="survey_type" value="{{ $survey->type ?? 2 }}" id="surveyType">

            <div class="onboarding-question-box">
                <p class="onboarding-question-text">
                    {{ $survey->text ?? 'هدف شما از شرکت در این دوره چیست؟' }}
                </p>
            </div>

            {{-- ==========================================
                 نوع 1: پاسخ کوتاه (نظر کاربر)
                 ========================================== --}}
            @if(isset($survey) && $survey->type == 1)
                <div class="text-input-wrapper">
                    <label for="shortAnswer">پاسخ خود را وارد کنید:</label>
                    <textarea id="shortAnswer" name="answer" class="text-input" rows="4" placeholder="نظر خود را وارد کنید...">{{ old('answer') }}</textarea>
                </div>

            {{-- ==========================================
                 نوع 2 و 3: چند گزینه‌ای
                 ========================================== --}}
            @elseif(isset($options) && $options->count() > 0)
                <div class="options-grid" id="optionsGrid">
                    @foreach($options as $option)
                        <div class="option-item" onclick="selectOption(this, '{{ $option->option }}')">
                            @if($survey->type == 2)
                                <input type="radio" name="answer" value="{{ $option->option }}">
                            @else
                                <input type="checkbox" name="answers[]" value="{{ $option->option }}">
                            @endif
                            <div class="option-radio"></div>
                            <span class="option-text">{{ $option->option }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:#6b7a8f;text-align:center;padding:20px 0;">هیچ گزینه‌ای برای این سوال وجود ندارد</p>
            @endif

            <div class="form-actions">
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-check"></i>
                    ثبت پاسخ
                </button>
                <a href="{{ route('student.onboarding.skip') }}" class="btn-skip">
                    <i class="fas fa-forward"></i>
                    رد کردن
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    // ============================================
    // انتخاب گزینه (برای نوع ۲ و ۳)
    // ============================================a
    function selectOption(element, value) {
        var surveyType = document.getElementById('surveyType').value;
        var isCheckbox = surveyType == 3;
        
        if (isCheckbox) {
            // چند جوابی - toggle
            element.classList.toggle('selected');
            var checkbox = element.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
            }
        } else {
            // تک جوابی - فقط یکی
            document.querySelectorAll('.option-item').forEach(function(item) {
                item.classList.remove('selected');
            });
            element.classList.add('selected');
            var radio = element.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
        }
        
        // فعال کردن دکمه ثبت
        document.getElementById('submitBtn').disabled = false;
    }

    // ============================================
    // اعتبارسنجی فرم
    // ============================================
    document.getElementById('onboardingForm').addEventListener('submit', function(e) {
        var surveyType = document.getElementById('surveyType').value;
        var isValid = false;

        if (surveyType == 1) {
            // پاسخ کوتاه
            var textarea = document.getElementById('shortAnswer');
            if (textarea && textarea.value.trim().length > 0) {
                isValid = true;
            }
        } else if (surveyType == 2) {
            // تک جوابی
            var selected = document.querySelector('input[name="answer"]:checked');
            if (selected) {
                isValid = true;
            }
        } else if (surveyType == 3) {
            // چند جوابی
            var checked = document.querySelectorAll('input[name="answers[]"]:checked');
            if (checked && checked.length > 0) {
                isValid = true;
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert('لطفاً یک گزینه را انتخاب کنید یا پاسخ خود را وارد کنید');
        }
    });

    // ============================================
    // اگر گزینه‌ای از قبل انتخاب شده بود
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        var surveyType = document.getElementById('surveyType').value;
        
        if (surveyType == 2) {
            var checkedRadio = document.querySelector('input[name="answer"]:checked');
            if (checkedRadio) {
                var parent = checkedRadio.closest('.option-item');
                if (parent) {
                    parent.classList.add('selected');
                    document.getElementById('submitBtn').disabled = false;
                }
            }
        }
        
        if (surveyType == 3) {
            var checkedCheckboxes = document.querySelectorAll('input[name="answers[]"]:checked');
            if (checkedCheckboxes && checkedCheckboxes.length > 0) {
                checkedCheckboxes.forEach(function(cb) {
                    var parent = cb.closest('.option-item');
                    if (parent) {
                        parent.classList.add('selected');
                    }
                });
                document.getElementById('submitBtn').disabled = false;
            }
        }
    });
</script>
@endsection