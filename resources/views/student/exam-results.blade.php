@extends('layout.master')

@section('title')
ملیسان | نتیجه آزمون
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .result-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .result-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 30px rgba(0, 0, 0, 0.06);
        padding: 35px;
    }

    .result-header {
        text-align: center;
        padding-bottom: 24px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 28px;
    }

    .result-header h3 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .result-header h3 i {
        color: #ffd700;
        margin-left: 10px;
    }

    .result-header .subtitle {
        font-size: 14px;
        color: #6b7a8f;
        margin-top: 4px;
    }

    /* ===== SCORE ===== */
    .score-wrapper {
        background: linear-gradient(145deg, #1e6f9f 0%, #0d4a6e 100%);
        border-radius: 20px;
        padding: 35px 30px;
        margin-bottom: 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .score-wrapper::before {
        content: '✦';
        position: absolute;
        top: -30px;
        right: -10px;
        font-size: 150px;
        color: rgba(255, 255, 255, 0.04);
        transform: rotate(15deg);
    }

    .score-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 5px solid rgba(255, 255, 255, 0.3);
        flex-direction: column;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(8px);
        margin-bottom: 16px;
        position: relative;
        z-index: 2;
    }

    .score-number {
        font-size: 3.2rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }

    .score-label {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 2px;
    }

    .score-stats {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
        position: relative;
        z-index: 2;
        color: rgba(255, 255, 255, 0.9);
    }

    .score-stats .stat-item .stat-number {
        display: block;
        font-size: 1.6rem;
        font-weight: 700;
        color: #fff;
    }

    .score-stats .stat-item .stat-number.gold {
        color: #ffd700;
    }

    .score-stats .stat-item .stat-number.green {
        color: #4caf50;
    }

    .score-stats .stat-item .stat-number.red {
        color: #f44336;
    }

    .score-stats .stat-item .stat-label {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    .motivational-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border-radius: 14px;
        padding: 16px 24px;
        font-size: 1.1rem;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.08);
        margin-top: 16px;
        position: relative;
        z-index: 2;
    }

    .motivational-box i {
        color: #ffd700;
        margin-left: 8px;
    }

    /* ===== QUESTIONS ===== */
    .question-result-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
        padding: 20px 24px;
        margin-bottom: 16px;
        border-right: 5px solid #e8edf3;
        transition: all 0.2s ease;
    }

    .question-result-card.correct {
        border-right-color: #4caf50;
    }

    .question-result-card.wrong {
        border-right-color: #f44336;
    }

    .question-result-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .q-number {
        font-size: 13px;
        font-weight: 600;
        color: #6b7a8f;
    }

    .status-badge {
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.correct {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge.wrong {
        background: #ffebee;
        color: #c62828;
    }

    .question-text-result {
        font-size: 15px;
        font-weight: 500;
        color: #1a2332;
        line-height: 1.7;
        margin-bottom: 12px;
        padding: 10px 14px;
        background: #f8fafc;
        border-radius: 10px;
    }

    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    @media (max-width: 600px) {
        .options-grid {
            grid-template-columns: 1fr;
        }
    }

    .option-result {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        background: #fafbfc;
        border: 2px solid #eef2f7;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .option-result .opt-label {
        font-weight: 700;
        color: #6b7a8f;
        min-width: 20px;
        font-size: 12px;
    }

    .option-result.correct-option {
        background: #e8f5e9;
        border-color: #4caf50;
    }

    .option-result.wrong-option {
        background: #ffebee;
        border-color: #f44336;
    }

    /* ===== ACTIONS ===== */
    .result-actions {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        justify-content: center;
        padding-top: 24px;
        border-top: 2px solid #f0f4f9;
        margin-top: 10px;
    }

    .btn-result {
        padding: 12px 32px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-family: inherit;
    }

    .btn-result-primary {
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
    }

    .btn-result-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 111, 159, 0.3);
        color: #fff;
    }

    .btn-result-outline {
        background: transparent;
        color: #1e6f9f;
        border: 2px solid #1e6f9f;
    }

    .btn-result-outline:hover {
        background: #1e6f9f;
        color: #fff;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .result-card {
            padding: 20px 16px;
        }

        .score-circle {
            width: 110px;
            height: 110px;
        }

        .score-number {
            font-size: 2.5rem;
        }

        .score-stats {
            gap: 20px;
        }

        .score-stats .stat-item .stat-number {
            font-size: 1.3rem;
        }

        .question-result-card {
            padding: 16px;
        }

        .result-actions {
            flex-direction: column;
        }

        .btn-result {
            justify-content: center;
        }
    }
</style>
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