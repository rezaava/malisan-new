@extends('layout.master')

@section('title')
ملیسان | {{ $azmon->title }}
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .exam-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 0 20px;
    }

    .exam-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 30px rgba(0, 0, 0, 0.06);
        padding: 30px 35px;
    }

    .exam-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 20px;
    }

    .exam-header .exam-info h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1a2332;
    }

    .exam-header .exam-info h4 i {
        color: #1e6f9f;
        margin-left: 8px;
    }

    .exam-header .exam-info .exam-meta {
        font-size: 13px;
        color: #6b7a8f;
        margin-top: 4px;
    }

    .exam-header .exam-info .exam-meta span {
        margin-left: 16px;
    }

    .exam-header .exam-timer {
        background: #f0f4f9;
        padding: 8px 20px;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 700;
        color: #1e6f9f;
        direction: ltr;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .exam-header .exam-timer i {
        color: #f44336;
        font-size: 16px;
    }

    .exam-header .exam-timer.warning {
        color: #f44336;
        animation: blink 1s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .exam-progress {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 12px;
    }

    .exam-progress .progress-text {
        font-size: 14px;
        color: #4a5a6e;
    }

    .exam-progress .progress-text .highlight {
        font-weight: 700;
        color: #1a2332;
    }

    .exam-progress .progress-bar {
        flex: 1;
        min-width: 100px;
        height: 6px;
        background: #e8edf3;
        border-radius: 10px;
        overflow: hidden;
    }

    .exam-progress .progress-bar .fill {
        height: 100%;
        background: linear-gradient(90deg, #4caf50, #1e6f9f);
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .question-number {
        font-size: 14px;
        color: #6b7a8f;
        margin-bottom: 10px;
    }

    .question-number span {
        font-weight: 700;
        color: #1a2332;
    }

    .question-text {
        font-size: 18px;
        font-weight: 600;
        color: #1a2332;
        line-height: 1.8;
        padding: 16px 20px;
        background: #f8fafc;
        border-radius: 12px;
        border-right: 4px solid #1e6f9f;
        margin-bottom: 24px;
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
        flex: 1;
        font-size: 15px;
        color: #1a2332;
    }

    .feedback-box {
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-weight: 600;
        text-align: center;
        display: none;
    }

    .feedback-box.show {
        display: block;
    }

    .feedback-box.warning {
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
    }

    .exam-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        padding-top: 20px;
        border-top: 2px solid #f0f4f9;
        flex-wrap: wrap;
    }

    .btn-exam {
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
        font-family: inherit;
    }

    .btn-exam:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
    }

    .btn-exam:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-exam-outline {
        background: transparent;
        color: #1e6f9f;
        border: 2px solid #1e6f9f;
    }

    .btn-exam-outline:hover {
        background: #1e6f9f;
        color: #fff;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .exam-card {
            padding: 16px;
        }
        .exam-header {
            flex-direction: column;
            align-items: stretch;
        }
        .exam-header .exam-timer {
            justify-content: center;
        }
        .exam-progress {
            flex-direction: column;
        }
        .exam-progress .progress-bar {
            width: 100%;
        }
        .question-text {
            font-size: 16px;
            padding: 12px 16px;
        }
        .option-item {
            padding: 12px 16px;
        }
        .exam-actions {
            flex-direction: column;
        }
        .btn-exam {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="exam-container">
    <div class="exam-card">
        {{-- HEADER --}}
        <div class="exam-header">
            <div class="exam-info">
                <h4>
                    <i class="fas fa-clipboard-list"></i>
                    {{ $azmon->title }}
                </h4>
                <div class="exam-meta">
                    <span><i class="fas fa-book-open"></i> {{ $course->name }}</span>
                    <span><i class="fas fa-clock"></i> {{ $azmon->time }} دقیقه</span>
                    @if($settings['show_state'] ?? 0)
                        <span><i class="fas fa-question-circle"></i> سوال {{ $currentNumber }} از {{ $totalQuestions }}</span>
                    @endif
                </div>
            </div>
            @if($settings['show_remain'] ?? 0)
                <div class="exam-timer" id="examTimer">
                    <i class="fas fa-hourglass-half"></i>
                    <span id="timerDisplay">--:--</span>
                </div>
            @endif
        </div>

        {{-- PROGRESS --}}
        <div class="exam-progress">
            <span class="progress-text">
                پیشرفت: <span class="highlight">{{ $currentNumber }}</span> از {{ $totalQuestions }} سوال
            </span>
            <div class="progress-bar">
                <div class="fill" style="width: {{ ($currentNumber / $totalQuestions) * 100 }}%;"></div>
            </div>
        </div>

        {{-- FORM --}}
        <form id="examForm" method="POST" action="{{ route('exam.next') }}">
            @csrf
            <input type="hidden" name="answer_id" value="{{ $answer->id }}">

            {{-- Question --}}
            <div class="question-number">
                سوال <span id="currentNum">{{ $currentNumber }}</span> از <span id="totalQ">{{ $totalQuestions }}</span>
            </div>

            <div class="question-text" id="questionText">
                {{ $question->question }}
            </div>

            {{-- Options --}}
            <div class="options-list" id="optionsList">
                @foreach($options as $option)
                    <div class="option-item" data-value="{{ $option['index'] }}" onclick="selectOption(this)">
                        <div class="option-radio"></div>
                        <span class="option-text">{{ $option['value'] }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Actions --}}
            <div class="exam-actions">
                <button type="button" class="btn-exam" id="submitBtn" disabled>
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
        // حذف انتخاب قبلی
        document.querySelectorAll('.option-item').forEach(item => {
            item.classList.remove('selected');
        });
        
        // انتخاب جدید
        element.classList.add('selected');
        selectedOption = element;
        
        // فعال کردن دکمه
        document.getElementById('submitBtn').disabled = false;
    }

    // ===== SUBMIT =====
    document.getElementById('submitBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        // اگر دکمه غیرفعال است
        if (this.disabled) {
            return;
        }
        
        const form = document.getElementById('examForm');
        
        // اگر گزینه‌ای انتخاب نشده
        if (!selectedOption) {
            showFeedback('لطفاً یک گزینه را انتخاب کنید', 'warning');
            return;
        }
        
        // اضافه کردن پاسخ به فرم
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'answer';
        hiddenInput.value = selectedOption.dataset.value;
        form.appendChild(hiddenInput);
        
        // غیرفعال کردن دکمه
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ارسال...';
        
        // غیرفعال کردن کلیک روی گزینه‌ها
        document.querySelectorAll('.option-item').forEach(function(item) {
            item.style.pointerEvents = 'none';
        });
        
        // ارسال فرم
        form.submit();
    });

    // ===== FEEDBACK =====
    function showFeedback(message, type) {
        // حذف باکس قبلی
        const oldBox = document.querySelector('.feedback-box');
        if (oldBox) {
            oldBox.remove();
        }
        
        // ایجاد باکس جدید
        const newBox = document.createElement('div');
        newBox.className = 'feedback-box show ' + (type || 'warning');
        newBox.textContent = message;
        
        // قرار دادن باکس بعد از سوال
        const questionText = document.querySelector('.question-text');
        if (questionText) {
            questionText.after(newBox);
        }
        
        // حذف خودکار بعد از 3 ثانیه
        setTimeout(function() {
            newBox.classList.remove('show');
            setTimeout(function() {
                newBox.remove();
            }, 300);
        }, 3000);
    }

    // ===== TIMER =====
    @if(isset($endTime) && ($settings['show_remain'] ?? 0) == 1)
        function startTimer() {
            const endTime = new Date('{{ $endTime->toIso8601String() }}').getTime();
            const timerDisplay = document.getElementById('timerDisplay');
            const timerContainer = document.getElementById('examTimer');
            
            if (!timerDisplay || !timerContainer) return;
            
            function updateTimer() {
                const now = new Date().getTime();
                const distance = endTime - now;
                
                if (distance < 0) {
                    timerDisplay.textContent = '00:00';
                    timerContainer.classList.add('warning');
                    document.getElementById('examForm').submit();
                    return;
                }
                
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                timerDisplay.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
                
                if (minutes < 5) {
                    timerContainer.classList.add('warning');
                }
            }
            
            updateTimer();
            setInterval(updateTimer, 1000);
        }
        
        document.addEventListener('DOMContentLoaded', startTimer);
    @endif
</script>
@endsection