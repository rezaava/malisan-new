@extends('layout.master')

@section('title')
ملیسان | {{ $azmon->title }}
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/exam.css') }}">

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
                {!! $question->question !!}
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