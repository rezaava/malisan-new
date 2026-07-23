@extends('layout.master')

@section('title')
ملیسان | پاسخ‌های تمرین
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/exercise-answers.css') }}">
@endsection

@section('mohtava')
<div class="answers-container">
    <div class="answers-header">
        <div>
            <h2>
                <i class="fas fa-users"></i>
                پاسخ‌های تمرین
            </h2>
            <div class="subtitle">
                <i class="fas fa-book-open" style="margin-left:6px;color:#1e6f9f;"></i>
                {{ $exercise->session->name }} - {{ $course->name }}
            </div>
        </div>
        <a href="{{ route('exercise.show', $session->id) }}" class="btn-back">
            <i class="fas fa-arrow-right"></i>
            بازگشت به تمرین‌ها
        </a>
    </div>

    @if($answers->count() > 0)
        @foreach($answers as $answer)
            @php
                $rateLabels = [
                    'excellent' => 'عالی',
                    'good' => 'خوب',
                    'medium' => 'متوسط',
                    'weak' => 'بد'
                ];
            @endphp
            <div class="answer-card">
                <div class="answer-header">
                    <span class="student-name">
                        <i class="fas fa-user-graduate"></i>
                        {{ $answer->user->name ?? 'نامشخص' }} {{ $answer->user->family ?? '' }}
                    </span>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        @if($answer->status == 'rated')
                            <span class="rate-badge {{ $answer->rate ?? '' }}">
                                <i class="fas fa-star"></i>
                                {{ $rateLabels[$answer->rate] ?? 'نامشخص' }}
                            </span>
                        @else
                            <span class="rate-badge pending">
                                <i class="fas fa-clock"></i>
                                در انتظار ارزیابی
                            </span>
                        @endif
                        <span class="answer-date">
                            <i class="fas fa-calendar-alt"></i>
                            {{ \Hekmatinasser\Verta\Verta::instance($answer->created_at)->format('Y/m/d H:i') }}
                        </span>
                    </div>
                </div>

                <div class="answer-text">
                    {!! $answer->answer !!}
                </div>

                @if($answer->file)
                    <a href="{{ asset($answer->file) }}" class="answer-file" target="_blank">
                        <i class="fas fa-paperclip"></i>
                        دانلود فایل پیوست
                    </a>
                @endif
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <span class="empty-icon">
                <i class="fas fa-inbox"></i>
            </span>
            <h4>هیچ پاسخی ثبت نشده است</h4>
            <p>هنوز هیچ دانشجویی به این تمرین پاسخ نداده است.</p>
        </div>
    @endif
</div>
@endsection