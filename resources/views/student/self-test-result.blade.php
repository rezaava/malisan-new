@extends('layout.master')

@section('title')
ملیسان | نتیجه خودآزمایی
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== CONTAINER ===== */
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
        overflow: hidden;
    }

    /* ===== HEADER ===== */
    .result-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .result-header .title-section h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
    }

    .result-header .title-section h3 i {
        color: #ffd700;
        margin-left: 10px;
    }

    .result-header .title-section .subtitle {
        font-size: 14px;
        color: #6b7a8f;
        margin-top: 4px;
    }

    .result-header .date-badge {
        background: #f0f4f9;
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 13px;
        color: #4a5a6e;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .result-header .date-badge i {
        color: #1e6f9f;
    }

    /* ===== SCORE WRAPPER ===== */
    .result-score-wrapper {
        background: linear-gradient(145deg, #1e6f9f 0%, #0d4a6e 100%);
        border-radius: 20px;
        padding: 35px 30px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 35px rgba(30, 111, 159, 0.25);
    }

    .result-score-wrapper::before {
        content: '✦';
        position: absolute;
        top: -30px;
        right: -10px;
        font-size: 150px;
        color: rgba(255, 255, 255, 0.04);
        transform: rotate(15deg);
    }

    .result-score-wrapper::after {
        content: '✦';
        position: absolute;
        bottom: -40px;
        left: -20px;
        font-size: 120px;
        color: rgba(255, 255, 255, 0.03);
        transform: rotate(-10deg);
    }

    .score-grid {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 30px;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    @media (max-width: 768px) {
        .score-grid {
            grid-template-columns: 1fr;
            gap: 20px;
            text-align: center;
        }
    }

    /* Score Circle */
    .score-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        flex-direction: column;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(8px);
        flex-shrink: 0;
    }

    .score-number {
        font-size: 3rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }

    .score-label {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 2px;
    }

    /* Score Stats */
    .score-stats {
        display: flex;
        gap: 35px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .score-stat-item {
        text-align: center;
        color: rgba(255, 255, 255, 0.9);
    }

    .score-stat-item .stat-number {
        display: block;
        font-size: 1.8rem;
        font-weight: 700;
        color: #fff;
    }

    .score-stat-item .stat-label {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    .score-stat-item .stat-number.gold {
        color: #ffd700;
    }

    /* Progress Bar */
    .result-progress {
        margin-top: 15px;
        width: 100%;
    }

    .result-progress .progress-track {
        width: 100%;
        height: 6px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        overflow: hidden;
    }

    .result-progress .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #ffd700, #ff6b6b);
        border-radius: 10px;
        transition: width 1.5s ease;
        width: 0%;
    }

    .result-progress .progress-labels {
        display: flex;
        justify-content: space-between;
        color: rgba(255, 255, 255, 0.6);
        font-size: 12px;
        margin-top: 5px;
    }

    /* Motivational */
    .motivational-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border-radius: 14px;
        padding: 18px 24px;
        font-size: 1rem;
        line-height: 1.8;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.08);
        min-width: 200px;
        text-align: center;
    }

    .motivational-box i {
        color: #ffd700;
        margin-left: 8px;
    }

    /* ===== QUESTION CARDS ===== */
    .question-result-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
        padding: 24px 28px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border-right: 5px solid #e8edf3;
    }

    .question-result-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
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
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .q-number-badge {
        display: inline-block;
        background: #f0f4f9;
        color: #4a5a6e;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 14px;
        border-radius: 20px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
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

    .question-result-text {
        font-size: 16px;
        font-weight: 600;
        color: #1a2332;
        line-height: 1.7;
        margin-bottom: 16px;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 12px;
    }

    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    @media (max-width: 600px) {
        .options-grid {
            grid-template-columns: 1fr;
        }
    }

    .option-result {
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 14px;
        color: #1a2332;
        background: #fafbfc;
        border: 2px solid #eef2f7;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .option-result .opt-label {
        font-weight: 700;
        color: #6b7a8f;
        min-width: 22px;
        font-size: 13px;
    }

    .option-result.correct-option {
        background: #e8f5e9;
        border-color: #4caf50;
    }

    .option-result.correct-option .opt-label {
        color: #2e7d32;
    }

    .option-result.wrong-option {
        background: #ffebee;
        border-color: #f44336;
    }

    .option-result.wrong-option .opt-label {
        color: #c62828;
    }

    .option-result .status-icon {
        margin-right: auto;
        font-size: 16px;
    }

    /* ===== ACTIONS ===== */
    .result-actions {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 35px;
        padding-top: 30px;
        border-top: 2px solid #f0f4f9;
    }

    .btn-result {
        padding: 11px 30px;
        border-radius: 12px;
        font-weight: 600;
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

    .btn-result-success {
        background: linear-gradient(135deg, #4caf50, #388e3c);
        color: #fff;
    }

    .btn-result-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
        color: #fff;
    }

    .btn-result-outline-success {
        background: transparent;
        color: #4caf50;
        border: 2px solid #4caf50;
    }

    .btn-result-outline-success:hover {
        background: #4caf50;
        color: #fff;
        transform: translateY(-2px);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .result-card {
            padding: 20px 16px;
        }

        .result-score-wrapper {
            padding: 25px 18px;
        }

        .score-circle {
            width: 100px;
            height: 100px;
        }

        .score-number {
            font-size: 2.2rem;
        }

        .score-stats {
            gap: 20px;
        }

        .score-stat-item .stat-number {
            font-size: 1.4rem;
        }

        .question-result-card {
            padding: 16px;
        }

        .question-result-text {
            font-size: 14px;
            padding: 10px 12px;
        }

        .result-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-result {
            justify-content: center;
        }

        .motivational-box {
            font-size: 0.9rem;
            padding: 14px 18px;
        }
    }
</style>
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
                            {{ $motivational->text }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== QUESTIONS LIST ===== --}}
        @php $qNum = 1; @endphp
        @foreach ($questions as $question)
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

            <a href="{{ route('student.selfTest.history') }}" class="btn-result btn-result-outline-success">
                <i class="fas fa-history"></i>
                تاریخچه
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