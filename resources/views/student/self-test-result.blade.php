@extends('layout.master')

@section('title')
ملیسان | نتیجه خودآزمایی
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/self-test-result.css') }}">
@endsection

@section('mohtava')
<div class="result-container">
    <div class="result-card">
        {{-- ===== SCORE CARD ===== --}}
        <div class="result-score-wrapper">
            <div class="score-grid">
                {{-- Score Circle --}}
                <div style="text-align:center;">
                    <div class="score-circle">
                        <span class="score-number">{{ number_format($score, 1) }}</span>
                        <span class="score-label">از ۲۰</span>
                    </div>
                </div>

                {{-- Stats --}}
                <div>
                    <div class="score-stats">
                        <div class="score-stat-item">
                            <span class="stat-number gold">{{ $correctAnswers }}</span>
                            <span class="stat-label">پاسخ صحیح</span>
                        </div>
                        <div class="score-stat-item">
                            <span class="stat-number">{{ $totalQuestions }}</span>
                            <span class="stat-label">تعداد سوال</span>
                        </div>
                        <div class="score-stat-item">
                            <span class="stat-number">{{ round(($correctAnswers / max($totalQuestions, 1)) * 100) }}%</span>
                            <span class="stat-label">درصد موفقیت</span>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="result-progress">
                        <div class="progress-track">
                            <div class="progress-fill" style="width: {{ ($correctAnswers / max($totalQuestions, 1)) * 100 }}%;"></div>
                        </div>
                        <div class="progress-labels">
                            <span>شروع</span>
                            <span>پایان</span>
                        </div>
                    </div>
                </div>

                {{-- Motivational --}}
                <div>
                    @if($motivational)
                        <div class="motivational-box">
                            <i class="fas fa-star"></i>
                            {!! $motivational->text !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== QUESTIONS LIST ===== --}}
        @php $qNum = 1; @endphp
        @foreach ($questions as $question)
            @php
                $userAnswer = $question['user_answer'] ?? null;
                $shuffledOptions = $question['shuffled_options'] ?? [];
                $shuffledCorrectIndex = $question['shuffled_correct_index'] ?? null;
                $userAnswerIndex = $question['user_answer_index'] ?? null;

                // بررسی صحت پاسخ با استفاده از اندیس شافل شده
                $isCorrect = $userAnswer && ($userAnswer->answer == ($shuffledCorrectIndex + 1));
                $cardClass = $isCorrect ? 'correct' : 'wrong';
            @endphp

            <div class="question-result-card {{ $cardClass }}">
                <div class="question-result-header">
                    <span class="q-number-badge">سوال {{ $qNum }}</span>
                    <span class="status-badge {{ $isCorrect ? 'correct' : 'wrong' }}">
                        @if($isCorrect)
                            <i class="fas fa-check-circle"></i> صحیح
                        @else
                            <i class="fas fa-times-circle"></i> نادرست
                        @endif
                    </span>
                </div>

                <div class="question-result-text">
                    {{ $question->question }}
                </div>

                <div class="options-grid">
                    @if(isset($shuffledOptions) && count($shuffledOptions) > 0)
                        {{-- نمایش گزینه‌های شافل شده --}}
                        @foreach ($shuffledOptions as $opt)
                            @php
                                $optNum = $loop->index; // ۰ تا ۳
                                $isCorrectOpt = ($optNum == $shuffledCorrectIndex);
                                $isSelectedOpt = ($userAnswerIndex !== null && $userAnswerIndex == $optNum);
                                $optClass = '';
                                if ($isCorrectOpt) {
                                    $optClass = 'correct-option';
                                } elseif ($isSelectedOpt && !$isCorrectOpt) {
                                    $optClass = 'wrong-option';
                                }
                            @endphp
                            <div class="option-result {{ $optClass }}">
                                <span class="opt-label">
                                    @switch($loop->index)
                                        @case(0) الف @break
                                        @case(1) ب @break
                                        @case(2) ج @break
                                        @case(3) د @break
                                    @endswitch
                                </span>
                                <span>{{ $opt['value'] }}</span>
                                @if($isCorrectOpt)
                                    <span class="status-icon" style="color:#4caf50;">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @elseif($isSelectedOpt && !$isCorrectOpt)
                                    <span class="status-icon" style="color:#f44336;">
                                        <i class="fas fa-times-circle"></i>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback: گزینه‌های اصلی --}}
                        @php
                            $options = [
                                1 => $question->answer1,
                                2 => $question->answer2,
                                3 => $question->answer3,
                                4 => $question->answer4,
                            ];
                            $correctIndex = $question->answer;
                            $userAnsIndex = $userAnswer ? (int) $userAnswer->answer : null;
                        @endphp
                        @foreach ($options as $optNum => $optText)
                            @php
                                $isCorrectOpt = $optNum == $correctIndex;
                                $isSelectedOpt = $optNum == $userAnsIndex;
                                $optClass = '';
                                if ($isCorrectOpt) {
                                    $optClass = 'correct-option';
                                } elseif ($isSelectedOpt && !$isCorrectOpt) {
                                    $optClass = 'wrong-option';
                                }
                            @endphp
                            <div class="option-result {{ $optClass }}">
                                <span class="opt-label">
                                    @switch($optNum)
                                        @case(1) الف @break
                                        @case(2) ب @break
                                        @case(3) ج @break
                                        @case(4) د @break
                                    @endswitch
                                </span>
                                <span>{{ $optText }}</span>
                                @if($isCorrectOpt)
                                    <span class="status-icon" style="color:#4caf50;">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @elseif($isSelectedOpt && !$isCorrectOpt)
                                    <span class="status-icon" style="color:#f44336;">
                                        <i class="fas fa-times-circle"></i>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @php $qNum++; @endphp
        @endforeach

        {{-- ===== ACTIONS ===== --}}
        <div class="result-actions">
            <a href="{{ route('view.coure.St', $course->id) }}" class="btn-result btn-result-outline">
                <i class="fas fa-arrow-right"></i>
                بازگشت به دوره
            </a>
            <a href="{{ route('student.selfTest.start', $course->id) }}" class="btn-result btn-result-primary">
                <i class="fas fa-redo"></i>
                شروع مجدد
            </a>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // انیمیشن شمارنده امتیاز
        const scoreElement = document.querySelector('.score-number');
        if (scoreElement) {
            const target = parseFloat(scoreElement.textContent);
            let current = 0;
            const duration = 1500;
            const steps = 30;
            const increment = target / steps;
            let step = 0;

            const timer = setInterval(() => {
                step++;
                if (step >= steps) {
                    scoreElement.textContent = target.toFixed(1);
                    clearInterval(timer);
                } else {
                    current += increment;
                    scoreElement.textContent = current.toFixed(1);
                }
            }, duration / steps);
        }

        // انیمیشن نوار پیشرفت
        const progressFill = document.querySelector('.progress-fill');
        if (progressFill) {
            const width = progressFill.style.width;
            progressFill.style.width = '0%';
            setTimeout(() => {
                progressFill.style.width = width;
            }, 400);
        }
    });
</script>
@endsection