@extends('layout.master')

@section('title')
ملیسان | پاسخ‌های تمرین
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .answers-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .answers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 30px;
    }

    .answers-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .answers-header h2 i {
        color: #1e6f9f;
        margin-left: 10px;
    }

    .answers-header .subtitle {
        font-size: 14px;
        color: #6b7a8f;
        margin-top: 4px;
    }

    .btn-back {
        padding: 10px 24px;
        background: #f0f4f9;
        color: #1a2332;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #e3e8ef;
        transform: translateY(-2px);
    }

    .answer-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 24px 28px;
        margin-bottom: 20px;
        border-right: 4px solid #1e6f9f;
        transition: all 0.3s ease;
    }

    .answer-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.1);
    }

    .answer-card .answer-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }

    .answer-card .student-name {
        font-weight: 700;
        color: #1a2332;
        font-size: 16px;
    }

    .answer-card .student-name i {
        color: #1e6f9f;
        margin-left: 8px;
    }

    .answer-card .answer-date {
        font-size: 13px;
        color: #6b7a8f;
    }

    .answer-card .answer-text {
        font-size: 15px;
        color: #1a2332;
        line-height: 1.8;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 12px;
    }

    .answer-card .answer-file {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: #f0f4f9;
        border-radius: 10px;
        color: #1e6f9f;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        margin-bottom: 12px;
    }

    .answer-card .answer-file:hover {
        background: #e3f2fd;
        transform: translateY(-2px);
    }

    .answer-card .rating-form {
        padding-top: 16px;
        border-top: 2px solid #f0f4f9;
        margin-top: 12px;
    }

    .rating-form .form-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }

    .rating-form .form-row .form-group {
        flex: 1;
        min-width: 150px;
    }

    .rating-form .form-row .form-group label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        color: #1a2332;
        margin-bottom: 4px;
    }

    .rating-form .form-row .form-group select,
    .rating-form .form-row .form-group textarea {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        font-family: inherit;
    }

    .rating-form .form-row .form-group select:focus,
    .rating-form .form-row .form-group textarea:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
        background: #fff;
    }

    .rating-form .form-row .form-group textarea {
        min-height: 60px;
        resize: vertical;
    }

    .btn-rate {
        padding: 8px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        align-self: flex-end;
    }

    .btn-rate:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 111, 159, 0.3);
    }

    .rate-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }

    .rate-badge.excellent {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .rate-badge.good {
        background: #e3f2fd;
        color: #1e6f9f;
    }

    .rate-badge.medium {
        background: #fff3e0;
        color: #e65100;
    }

    .rate-badge.weak {
        background: #ffebee;
        color: #c62828;
    }

    .rate-badge.pending {
        background: #f5f5f5;
        color: #6b7a8f;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8fafc;
        border-radius: 20px;
    }

    .empty-state .empty-icon {
        font-size: 60px;
        color: #d0d7e2;
        margin-bottom: 20px;
        display: block;
    }

    .empty-state h4 {
        color: #1a2332;
        font-size: 18px;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #6b7a8f;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .answer-card {
            padding: 18px 16px;
        }

        .rating-form .form-row {
            flex-direction: column;
        }

        .rating-form .form-row .form-group {
            width: 100%;
        }

        .btn-rate {
            width: 100%;
            justify-content: center;
        }
    }
</style>
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