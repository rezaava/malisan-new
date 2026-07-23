@extends('layout.master')

@section('title')
ملیسان | نتیجه آزمون
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/exam-results.css') }}">

@endsection

@section('mohtava')
<div class="result-container">
    <div class="result-card">
        {{-- HEADER --}}
        <div class="result-header">
            <h3>
                <i class="fas fa-trophy"></i>
                نتیجه آزمون
            </h3>
            <div class="subtitle">
                <i class="fas fa-book-open" style="margin-left:6px;color:#1e6f9f;"></i>
                {{ $course->name ?? 'دوره' }} - {{ $azmon->title ?? 'آزمون' }}
            </div>
        </div>

        {{-- SCORE --}}
        <div class="score-wrapper">
            <div class="score-circle">
                <span class="score-number">{{ number_format($score, 1) }}</span>
                <span class="score-label">از ۲۰</span>
            </div>

            <div class="score-stats">
                <div class="stat-item">
                    <span class="stat-number gold">{{ $correctAnswers }}</span>
                    <span class="stat-label">پاسخ صحیح</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number red">{{ $wrongAnswers }}</span>
                    <span class="stat-label">پاسخ غلط</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $totalQuestions }}</span>
                    <span class="stat-label">تعداد سوال</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number green">{{ $percentage ?? 0 }}%</span>
                    <span class="stat-label">درصد موفقیت</span>
                </div>
            </div>

            @if($motivational)
                <div class="motivational-box">
                    <i class="fas fa-star"></i>
                    {{ $motivational->text }}
                </div>
            @endif
        </div>

        {{-- QUESTIONS --}}
        @php $qNum = 1; @endphp
        @foreach($questions as $question)
            @php
                $userAnswer = isset($question['user_answer']) ? $question['user_answer'] : null;
                $isCorrect = $userAnswer && $userAnswer->answer == $question->answer;
                $cardClass = $isCorrect ? 'correct' : 'wrong';
                
                $options = [
                    1 => $question->answer1,
                    2 => $question->answer2,
                    3 => $question->answer3,
                    4 => $question->answer4,
                ];
                
                $correctIndex = $question->answer;
                $userAnswerIndex = $userAnswer ? (int) $userAnswer->answer : null;
            @endphp

            <div class="question-result-card {{ $cardClass }}">
                <div class="question-result-header">
                    <span class="q-number">سوال {{ $qNum }}</span>
                    <span class="status-badge {{ $isCorrect ? 'correct' : 'wrong' }}">
                        @if($isCorrect)
                            <i class="fas fa-check-circle"></i> صحیح
                        @else
                            <i class="fas fa-times-circle"></i> نادرست
                        @endif
                    </span>
                </div>

                <div class="question-text-result">
                    {{ $question->question }}
                </div>

                <div class="options-grid">
                    @foreach ($options as $optNum => $optText)
                        @php
                            $isCorrectOpt = $optNum == $correctIndex;
                            $isSelectedOpt = $optNum == $userAnswerIndex;
                            
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
                                <span style="color:#4caf50;margin-right:auto;">✓</span>
                            @elseif($isSelectedOpt && !$isCorrectOpt)
                                <span style="color:#f44336;margin-right:auto;">✗</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @php $qNum++; @endphp
        @endforeach

        {{-- ACTIONS --}}
        <div class="result-actions">
            <a href="{{ route('courses.st', $course->id) }}" class="btn-result btn-result-outline">
                <i class="fas fa-arrow-right"></i>
                بازگشت به دوره
            </a>
        </div>
    </div>
</div>
@endsection