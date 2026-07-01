@extends('layout.master')

@section('title')
ملیسان | جزئیات تکلیف
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .detail-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .detail-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 30px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .detail-header {
        padding: 24px 30px;
        background: linear-gradient(135deg, #f8fafc, #fff);
        border-bottom: 2px solid #f0f4f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .detail-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
    }

    .detail-header h3 i {
        color: #1e6f9f;
        margin-left: 10px;
    }

    .detail-body {
        padding: 30px;
    }

    .info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f0f4f9;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #4a5a6e;
        min-width: 120px;
        font-size: 14px;
        flex-shrink: 0;
    }

    .info-value {
        color: #1a2332;
        font-size: 14px;
        flex: 1;
        line-height: 1.8;
    }

    .info-value .answer-text {
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 10px;
        border-right: 3px solid #1e6f9f;
        line-height: 1.9;
    }

    .info-value .exercise-text {
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 10px;
        border-right: 3px solid #ff9800;
        line-height: 1.9;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.pending { background: #fff3cd; color: #e65100; }
    .status-badge.returned { background: #fff3cd; color: #e65100; }
    .status-badge.scored { background: #e8f5e9; color: #2e7d32; }

    .btn-back {
        padding: 8px 20px;
        background: #f0f4f9;
        border-radius: 10px;
        text-decoration: none;
        color: #1a2332;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
    }

    .btn-back:hover {
        background: #e3e8ef;
    }

    .file-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: #e3f2fd;
        border-radius: 10px;
        color: #1e6f9f;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .file-link:hover {
        background: #1e6f9f;
        color: #fff;
        transform: translateY(-2px);
    }

    .rate-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .rate-badge.excellent { background: #e8f5e9; color: #2e7d32; }
    .rate-badge.good { background: #e3f2fd; color: #1e6f9f; }
    .rate-badge.medium { background: #fff3e0; color: #e65100; }
    .rate-badge.weak { background: #ffebee; color: #c62828; }

    .empty-state {
        text-align: center;
        padding: 30px 20px;
        color: #6b7a8f;
    }

    .empty-state i {
        font-size: 40px;
        color: #d0d7e2;
        display: block;
        margin-bottom: 12px;
    }

    @media (max-width: 768px) {
        .detail-header {
            padding: 18px 16px;
            flex-direction: column;
            align-items: stretch;
        }
        .detail-body {
            padding: 18px 16px;
        }
        .info-row {
            flex-direction: column;
            gap: 4px;
            padding: 10px 0;
        }
        .info-label {
            min-width: auto;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="detail-container">
    <div class="detail-card">
        <div class="detail-header">
            <h3>
                <i class="fas fa-tasks"></i>
                جزئیات تکلیف
            </h3>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <span class="status-badge 
                    @if($exercise->status === null) pending
                    @elseif($exercise->status == 'returned') returned
                    @elseif($exercise->status == 'scored') scored
                    @endif">
                    @if($exercise->status === null)
                        <i class="fas fa-clock"></i> در انتظار بررسی
                    @elseif($exercise->status == 'returned')
                        <i class="fas fa-undo"></i> برگشت خورده
                    @elseif($exercise->status == 'scored')
                        <i class="fas fa-check-circle"></i> ارزیابی شده
                    @endif
                </span>
                @if($exercise->status == 'scored' && $exercise->rate)
                    <span class="rate-badge 
                        @if($exercise->rate == 'excellent') excellent
                        @elseif($exercise->rate == 'good') good
                        @elseif($exercise->rate == 'medium') medium
                        @elseif($exercise->rate == 'weak') weak
                        @endif">
                        <i class="fas fa-star" style="color:#ffd700;"></i>
                        @if($exercise->rate == 'excellent') عالی
                        @elseif($exercise->rate == 'good') خوب
                        @elseif($exercise->rate == 'medium') متوسط
                        @elseif($exercise->rate == 'weak') بد
                        @endif
                    </span>
                @endif
            </div>
        </div>

        <div class="detail-body">
            {{-- متن تکلیف --}}
            <div class="info-row" style="flex-direction:column;gap:8px;align-items:stretch;">
                <span class="info-label">متن تکلیف</span>
                <div class="info-value">
                    <div class="exercise-text">
                        {!! $exercise->exercise->text ?? 'متن تکلیف موجود نیست' !!}
                    </div>
                </div>
            </div>

            {{-- فایل پیوست تکلیف --}}
            @if($exercise->exercise->file)
                <div class="info-row">
                    <span class="info-label">فایل پیوست</span>
                    <span class="info-value">
                        <a href="{{ asset($exercise->exercise->file) }}" class="file-link" target="_blank">
                            <i class="fas fa-paperclip"></i>
                            دانلود فایل تکلیف
                        </a>
                    </span>
                </div>
            @endif

            {{-- پاسخ دانشجو --}}
            <div class="info-row" style="flex-direction:column;gap:8px;align-items:stretch;">
                <span class="info-label">پاسخ شما</span>
                <div class="info-value">
                    <div class="answer-text">
                        {!! $exercise->answer ?? 'پاسخی ارسال نشده است' !!}
                    </div>
                </div>
            </div>

            {{-- فایل پیوست پاسخ --}}
            @if($exercise->file)
                <div class="info-row">
                    <span class="info-label">فایل پاسخ</span>
                    <span class="info-value">
                        <a href="{{ asset($exercise->file) }}" class="file-link" target="_blank">
                            <i class="fas fa-paperclip"></i>
                            دانلود فایل پاسخ
                        </a>
                    </span>
                </div>
            @endif

            {{-- ارسال کننده --}}
            <div class="info-row">
                <span class="info-label">ارسال کننده</span>
                <span class="info-value">
                    <i class="fas fa-user-graduate" style="color:#1e6f9f;"></i>
                    {{ $exercise->user->name ?? 'نامشخص' }} {{ $exercise->user->family ?? '' }}
                </span>
            </div>

            {{-- درس --}}
            <div class="info-row">
                <span class="info-label">درس</span>
                <span class="info-value">
                    <i class="fas fa-book-open" style="color:#1e6f9f;"></i>
                    {{ $exercise->exercise->session->course->name ?? 'نامشخص' }}
                </span>
            </div>

            {{-- جلسه --}}
            <div class="info-row">
                <span class="info-label">جلسه</span>
                <span class="info-value">
                    <i class="fas fa-video" style="color:#1e6f9f;"></i>
                    {{ $exercise->exercise->session->name ?? 'نامشخص' }}
                </span>
            </div>

            {{-- تاریخ ارسال --}}
            <div class="info-row">
                <span class="info-label">تاریخ ارسال</span>
                <span class="info-value">
                    <i class="fas fa-calendar-alt" style="color:#1e6f9f;"></i>
                    {{ \Hekmatinasser\Verta\Verta::instance($exercise->created_at)->format('Y/m/d H:i') }}
                </span>
            </div>

            {{-- کامنت استاد (اگر وجود داشته باشد) --}}
            @if($exercise->comment)
                <div class="info-row" style="flex-direction:column;gap:4px;align-items:stretch;background:#f8fafc;border-radius:10px;padding:16px;margin-top:8px;">
                    <span class="info-label" style="min-width:auto;color:#1e6f9f;">
                        <i class="fas fa-comment"></i> نظر استاد
                    </span>
                    <div class="info-value" style="color:#1a2332;">
                        {{ $exercise->comment }}
                    </div>
                </div>
            @endif

            {{-- دکمه بازگشت --}}
            <div style="margin-top:24px;padding-top:20px;border-top:2px solid #f0f4f9;">
                <a href="{{ route('student.my.activities', $exercise->exercise->session->course_id) }}" class="btn-back">
                    <i class="fas fa-arrow-right"></i>
                    بازگشت به فعالیت‌ها
                </a>
            </div>
        </div>
    </div>
</div>
@endsection