@extends('layout.master')

@section('title')
ملیسان | جزئیات سوال
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/question-detail.css') }}">

@endsection

@section('mohtava')
<div class="detail-container">
    <div class="detail-card">
        <div class="detail-header">
            <h3>
                <i class="fas fa-question-circle"></i>
                جزئیات سوال
            </h3>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                @if($averageScore)
                    <span class="average-score">
                        <i class="fas fa-star" style="color:#ffd700;"></i>
                        میانگین نمرات: {{ $averageScore }}
                    </span>
                @endif
                <span class="status-badge 
                    @if($question->status === null) pending
                    @elseif($question->status == 0) returned
                    @elseif($question->status == 1) excellent
                    @elseif($question->status == 2) good
                    @elseif($question->status == 3) medium
                    @else weak @endif">
                    @if($question->status === null)
                        <i class="fas fa-clock"></i> در انتظار داوری
                    @elseif($question->status == 0)
                        <i class="fas fa-undo"></i> برگشت خورده
                    @elseif($question->status == 1)
                        <i class="fas fa-star"></i> عالی
                    @elseif($question->status == 2)
                        <i class="fas fa-check-circle"></i> خوب
                    @elseif($question->status == 3)
                        <i class="fas fa-minus-circle"></i> متوسط
                    @else
                        <i class="fas fa-times-circle"></i> بد
                    @endif
                </span>
            </div>
        </div>

        <div class="detail-body">
            <div class="info-row">
                <span class="info-label">سوال</span>
                <span class="info-value">{{ $question->question }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">طراح</span>
                <span class="info-value">
                    <i class="fas fa-user-graduate" style="color:#1e6f9f;"></i>
                    {{ $question->user->name ?? 'نامشخص' }} {{ $question->user->family ?? '' }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">درس</span>
                <span class="info-value">
                    <i class="fas fa-book-open" style="color:#1e6f9f;"></i>
                    {{ $question->session->course->name ?? 'نامشخص' }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">جلسه</span>
                <span class="info-value">
                    <i class="fas fa-video" style="color:#1e6f9f;"></i>
                    {{ $question->session->name ?? 'نامشخص' }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">تاریخ ثبت</span>
                <span class="info-value">
                    <i class="fas fa-calendar-alt" style="color:#1e6f9f;"></i>
                    {{ \Hekmatinasser\Verta\Verta::instance($question->created_at)->format('Y/m/d H:i') }}
                </span>
            </div>

            <div class="info-row" style="flex-direction:column;gap:8px;align-items:stretch;">
                <span class="info-label">گزینه‌ها</span>
                <div class="options-grid">
                    @php
                        $options = [
                            1 => ['label' => 'الف', 'value' => $question->answer1],
                            2 => ['label' => 'ب', 'value' => $question->answer2],
                            3 => ['label' => 'ج', 'value' => $question->answer3],
                            4 => ['label' => 'د', 'value' => $question->answer4],
                        ];
                    @endphp
                    @foreach($options as $key => $option)
                        <div class="option-item {{ $key == $question->answer ? 'correct' : '' }}">
                            <span class="opt-label">{{ $option['label'] }}</span>
                            <span>{{ $option['value'] }}</span>
                            @if($key == $question->answer)
                                <span style="color:#4caf50;margin-right:auto;"><i class="fas fa-check-circle"></i></span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="info-row" style="flex-direction:column;gap:8px;align-items:stretch;padding-top:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                    <span class="info-label" style="min-width:auto;">داوری‌ها</span>
                    <span style="font-size:13px;color:#6b7a8f;">
                        <i class="fas fa-users"></i>
                        {{ $scores->count() }} داوری
                        @php
                            $approvedCount = $scores->where('status', 'approved')->count();
                        @endphp
                        @if($approvedCount > 0)
                            ({{ $approvedCount }} تایید شده)
                        @endif
                    </span>
                </div>

                @if($scores->count() > 0)
                    @foreach($scores as $score)
                        <div class="score-item">

                            @if($score->status === 'approved')
                                <span class="score-value 
                                    @if($score->score == 1) excellent
                                    @elseif($score->score == 2) good
                                    @elseif($score->score == 3) medium
                                    @endif">
                                    @if($score->score == 1) عالی
                                    @elseif($score->score == 2) خوب
                                    @elseif($score->score == 3) متوسط
                                    @endif
                                </span>
                            @endif

                            @if($score->negaresh == 1 || $score->gozine == 1 || $score->dark == 1)
                                <span style="font-size:12px;color:#f44336;">
                                    @if($score->negaresh == 1) ❌ نگارشی @endif
                                    @if($score->gozine == 1) ❌ گزینه‌ها @endif
                                    @if($score->dark == 1) ❌ گویایی @endif
                                </span>
                            @endif

                            <span class="score-status {{ $score->status }}">
                                @if($score->status === 'approved')
                                    <i class="fas fa-check-circle"></i> تایید
                                @elseif($score->status === 'rejected')
                                    <i class="fas fa-times-circle"></i> رد
                                @elseif($score->status === 'returned')
                                    <i class="fas fa-undo"></i> برگشت
                                @else
                                    <i class="fas fa-clock"></i> در انتظار
                                @endif
                            </span>

                            @if($score->comment)
                                <span style="font-size:13px;color:#6b7a8f;">
                                    <i class="fas fa-comment" style="color:#6b7a8f;"></i>
                                    {{ $score->comment }}
                                </span>
                            @endif

                            <span style="font-size:11px;color:#6b7a8f;white-space:nowrap;">
                                {{ \Hekmatinasser\Verta\Verta::instance($score->created_at)->format('Y/m/d H:i') }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>هیچ داوری‌ای برای این سوال ثبت نشده است.</p>
                    </div>
                @endif
            </div>

            <div style="margin-top:24px;padding-top:20px;border-top:2px solid #f0f4f9;">
                <a href="{{ route('student.my.activities', $question->session->course_id) }}" class="btn-back">
                    <i class="fas fa-arrow-right"></i>
                    بازگشت به فعالیت‌ها
                </a>
            </div>
        </div>
    </div>
</div>
@endsection