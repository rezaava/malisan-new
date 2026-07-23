@extends('layout.master')

@section('title')
ملیسان | جزئیات گزارش
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/discussion-detail.css')}}">

@endsection

@section('mohtava')
<div class="detail-container">
    <div class="detail-card">
        <div class="detail-header">
            <h3>
                <i class="fas fa-file-alt"></i>
                جزئیات گزارش
            </h3>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                @if($averageScore)
                    <span class="average-score">
                        <i class="fas fa-star" style="color:#ffd700;"></i>
                        میانگین نمرات: {{ $averageScore }}
                    </span>
                @endif
                <span class="status-badge 
                    @if($discussion->status === null) pending
                    @elseif($discussion->status == 0) returned
                    @elseif($discussion->status == 1) excellent
                    @elseif($discussion->status == 2) good
                    @elseif($discussion->status == 3) medium
                    @else weak @endif">
                    @if($discussion->status === null)
                        <i class="fas fa-clock"></i> در انتظار داوری
                    @elseif($discussion->status == 0)
                        <i class="fas fa-undo"></i> برگشت خورده
                    @elseif($discussion->status == 1)
                        <i class="fas fa-star"></i> عالی
                    @elseif($discussion->status == 2)
                        <i class="fas fa-check-circle"></i> خوب
                    @elseif($discussion->status == 3)
                        <i class="fas fa-minus-circle"></i> متوسط
                    @else
                        <i class="fas fa-times-circle"></i> بد
                    @endif
                </span>
            </div>
        </div>

        <div class="detail-body">
            {{-- عنوان گزارش --}}
            <div class="info-row">
                <span class="info-label">عنوان</span>
                <span class="info-value">{{ $discussion->title ?? 'بدون عنوان' }}</span>
            </div>

            {{-- طراح --}}
            <div class="info-row">
                <span class="info-label">ارسال کننده</span>
                <span class="info-value">
                    <i class="fas fa-user-graduate" style="color:#1e6f9f;"></i>
                    {{ $discussion->user->name ?? 'نامشخص' }} {{ $discussion->user->family ?? '' }}
                </span>
            </div>

            {{-- درس --}}
            <div class="info-row">
                <span class="info-label">درس</span>
                <span class="info-value">
                    <i class="fas fa-book-open" style="color:#1e6f9f;"></i>
                    {{ $discussion->session->course->name ?? 'نامشخص' }}
                </span>
            </div>

            {{-- جلسه --}}
            <div class="info-row">
                <span class="info-label">جلسه</span>
                <span class="info-value">
                    <i class="fas fa-video" style="color:#1e6f9f;"></i>
                    {{ $discussion->session->name ?? 'نامشخص' }}
                </span>
            </div>

            {{-- تاریخ ثبت --}}
            <div class="info-row">
                <span class="info-label">تاریخ ثبت</span>
                <span class="info-value">
                    <i class="fas fa-calendar-alt" style="color:#1e6f9f;"></i>
                    {{ \Hekmatinasser\Verta\Verta::instance($discussion->created_at)->format('Y/m/d H:i') }}
                </span>
            </div>

            {{-- متن گزارش --}}
            <div class="info-row" style="flex-direction:column;gap:8px;align-items:stretch;">
                <span class="info-label">متن گزارش</span>
                <div class="info-value">
                    <div class="report-text">
                        {!! $discussion->text ?? 'متن گزارش موجود نیست' !!}
                    </div>
                </div>
            </div>

            {{-- داوری‌ها --}}
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
                            <span class="score-user">
                                <i class="fas fa-user" style="color:#1e6f9f;"></i>
                                {{ $score->user->name ?? 'ناشناس' }} {{ $score->user->family ?? '' }}
                            </span>

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
                        <p>هیچ داوری‌ای برای این گزارش ثبت نشده است.</p>
                    </div>
                @endif
            </div>

            {{-- دکمه بازگشت --}}
            <div style="margin-top:24px;padding-top:20px;border-top:2px solid #f0f4f9;">
                <a href="{{ route('student.my.activities', $discussion->session->course_id) }}" class="btn-back">
                    <i class="fas fa-arrow-right"></i>
                    بازگشت به فعالیت‌ها
                </a>
            </div>
        </div>
    </div>
</div>
@endsection