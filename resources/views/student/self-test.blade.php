@extends('layout.master')

@section('title')
ملیسان | خودآزمایی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-self-test.css')}}">
<style>
    .self-test-container {
        max-width: 800px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .self-test-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 30px;
    }

    .self-test-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .self-test-header .progress {
        font-weight: 600;
        color: #1e6f9f;
        font-size: 15px;
    }

    .question-number {
        font-size: 14px;
        color: #6b7a8f;
        margin-bottom: 12px;
    }

    .question-number span {
        font-weight: 700;
        color: #1a2332;
    }

    .question-text {
        font-size: 18px;
        font-weight: 600;
        color: #1a2332;
        margin-bottom: 24px;
        line-height: 1.8;
        padding: 16px 20px;
        background: #f8fafc;
        border-radius: 12px;
        border-right: 4px solid #1e6f9f;
    }

    .options-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 24px;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafbfc;
    }

    .option-item:hover {
        border-color: #1e6f9f;
        background: #f0f7fe;
    }

    .option-item.selected {
        border-color: #1e6f9f;
        background: #e3f2fd;
    }

    .option-item.correct {
        border-color: #4caf50;
        background: #e8f5e9;
    }

    .option-item.wrong {
        border-color: #f44336;
        background: #ffebee;
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

    .option-item .option-label {
        font-weight: 600;
        color: #1a2332;
        flex-shrink: 0;
        width: 24px;
    }

    .option-item .option-text {
        flex: 1;
        font-size: 15px;
        color: #1a2332;
    }

    .form-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        padding-top: 20px;
        border-top: 2px solid #f0f4f9;
        flex-wrap: wrap;
    }

    .btn-submit-answer {
        padding: 12px 48px;
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

    .btn-submit-answer:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
    }

    .feedback-box {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: none;
        font-weight: 600;
        text-align: center;
    }

    .feedback-box.show {
        display: block;
    }

    .feedback-box.correct {
        background: #e8f5e9;
        border: 1px solid #4caf50;
        color: #2e7d32;
    }

    .feedback-box.wrong {
        background: #ffebee;
        border: 1px solid #f44336;
        color: #c62828;
    }

    .feedback-box.warning {
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
    }

    @media (max-width: 768px) {
        .self-test-card {
            padding: 16px;
        }
        .question-text {
            font-size: 16px;
            padding: 12px 16px;
        }
        .form-actions {
            flex-direction: column;
        }
        .btn-submit-answer {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="self-test-container">
    <div class="self-test-card">
        <div class="self-test-header">
            <div>
                <h4 style="margin:0;font-size:18px;font-weight:700;color:#1a2332;">
                    <i class="fas fa-brain" style="color:#1e6f9f;"></i>
                    خودآزمایی
                </h4>
                <div class="question-number">
                    سوال <span id="currentNum">{{ $num ?? 1 }}</span> از <span id="totalQ">{{ $q_num ?? 10 }}</span>
                </div>
            </div>
            <div class="progress">
                <i class="fas fa-check-circle" style="color:#4caf50;"></i>
                <span id="answeredCount">{{ $num - 1 }}</span> پاسخ داده شده
            </div>
        </div>

        <div class="feedback-box" id="feedbackBox">
            @if(session('feedback'))
                {{ session('feedback') }}
            @endif
        </div>

        <form id="selfTestForm" method="POST" action="{{ route('student.selfTest.next') }}">
            @csrf
            <input type="hidden" name="answer_id" value="{{ $newAnswer->id ?? $answer->id }}">            

            <div class="question-text" id="questionText">
                {{ $question->question ?? 'سوال یافت نشد' }}
            </div>

            <div class="options-list" id="optionsList">
                @php
                    $options = [
                        ['label' => 'الف', 'value' => $question->answer1, 'index' => 0],
                        ['label' => 'ب', 'value' => $question->answer2, 'index' => 1],
                        ['label' => 'ج', 'value' => $question->answer3, 'index' => 2],
                        ['label' => 'د', 'value' => $question->answer4, 'index' => 3],
                    ];
                    shuffle($options);
                @endphp

                @foreach($options as $option)
                    <div class="option-item" data-value="{{ $option['index'] }}" onclick="selectOption(this)">
                        <div class="option-radio"></div>
                        <span class="option-text">{{ $option['value'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit-answer" id="submitBtn">
                    <i class="fas fa-check"></i>
                    ثبت پاسخ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let selectedOption = null;

    function selectOption(element) {
        if (document.getElementById('submitBtn').disabled) return;
        
        document.querySelectorAll('.option-item').forEach(item => {
            item.classList.remove('selected');
        });
        element.classList.add('selected');
        selectedOption = element;
        document.getElementById('submitBtn').disabled = false;
    }
    document.getElementById('selfTestForm').addEventListener('submit', function(e) {
        if (!selectedOption) {
            e.preventDefault();
            showFeedback('لطفاً یک گزینه را انتخاب کنید', 'warning');
            return;
        }
        
        document.getElementById('submitBtn').disabled = true;
        document.querySelectorAll('.option-item').forEach(item => {
            item.style.pointerEvents = 'none';
        });
        
        // ارسال اندیس گزینه (عدد 0, 1, 2, 3)
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'answer';
        hiddenInput.value = selectedOption.dataset.value; // اینجا عدد 0,1,2,3 است
        this.appendChild(hiddenInput);
    });
    // جلوگیری از ارسال فرم بدون انتخاب گزینه
    document.getElementById('selfTestForm').addEventListener('submit', function(e) {
        if (!selectedOption) {
            e.preventDefault();
            showFeedback('لطفاً یک گزینه را انتخاب کنید', 'warning');
            return;
        }
        
        // غیرفعال کردن دکمه و المان‌ها
        document.getElementById('submitBtn').disabled = true;
        document.querySelectorAll('.option-item').forEach(item => {
            item.style.pointerEvents = 'none';
        });
        
        // اضافه کردن مقدار انتخاب شده به فرم
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'answer';
        hiddenInput.value = selectedOption.dataset.value;
        this.appendChild(hiddenInput);
    });

    function showFeedback(message, type) {
        const box = document.getElementById('feedbackBox');
        box.textContent = message;
        box.className = 'feedback-box show ' + type;
        
        setTimeout(() => {
            box.classList.remove('show');
        }, 3000);
    }

    // اگر پیام قبلی وجود داشت نمایش بده
    @if(session('feedback'))
        document.addEventListener('DOMContentLoaded', function() {
            const box = document.getElementById('feedbackBox');
            box.className = 'feedback-box show';
        });
    @endif
</script>
@endsection