@extends('auth.layout.master')

@section('title')
پرسش اولیه
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/onboarding.css') }}">
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