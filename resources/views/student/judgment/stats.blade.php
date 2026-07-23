@extends('layout.master')

@section('title')
ملیسان | آمار داوری
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/stats.css') }}">
@endsection

@section('mohtava')
<div class="stats-container">
    <div class="stats-header">
        <h2>
            <i class="fas fa-chart-bar"></i>
            آمار داوری
        </h2>
        <a href="{{ route('student.judgment.index') }}" class="btn-back">
            <i class="fas fa-arrow-right"></i>
            بازگشت به داوری
        </a>
    </div>

    <div class="stats-grid">
        {{-- سوالات --}}
        <div class="stats-card" style="border-right-color:#1e6f9f;">
            <div class="card-title">
                <i class="fas fa-question-circle"></i>
                سوالات
            </div>
            <div class="stat-item">
                <span class="label">کل سوالات</span>
                <span class="value blue">{{ $questionStats['total'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="label">در انتظار داوری</span>
                <span class="value orange">{{ $questionStats['pending'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="label">تایید شده</span>
                <span class="value green">{{ $questionStats['approved'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="label">رد شده</span>
                <span class="value red">{{ $questionStats['rejected'] ?? 0 }}</span>
            </div>
        </div>

        {{-- گزارش‌ها --}}
        <div class="stats-card" style="border-right-color:#ff9800;">
            <div class="card-title">
                <i class="fas fa-file-alt"></i>
                گزارش‌ها
            </div>
            <div class="stat-item">
                <span class="label">کل گزارش‌ها</span>
                <span class="value blue">{{ $discussionStats['total'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="label">در انتظار داوری</span>
                <span class="value orange">{{ $discussionStats['pending'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="label">تایید شده</span>
                <span class="value green">{{ $discussionStats['approved'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="label">رد شده</span>
                <span class="value red">{{ $discussionStats['rejected'] ?? 0 }}</span>
            </div>
        </div>

        {{-- تکالیف --}}
        <div class="stats-card" style="border-right-color:#4caf50;">
            <div class="card-title">
                <i class="fas fa-tasks"></i>
                تکالیف
            </div>
            <div class="stat-item">
                <span class="label">کل تکالیف</span>
                <span class="value blue">{{ $exerciseStats['total'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="label">در انتظار داوری</span>
                <span class="value orange">{{ $exerciseStats['pending'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="label">ارزیابی شده</span>
                <span class="value green">{{ $exerciseStats['scored'] ?? 0 }}</span>
            </div>
        </div>

        {{-- داوری‌های من --}}
        <div class="stats-card" style="border-right-color:#9c27b0;">
            <div class="card-title">
                <i class="fas fa-user-graduate"></i>
                داوری‌های من
            </div>
            <div class="stat-item">
                <span class="label">سوالات داوری شده</span>
                <span class="value blue">{{ $myStats['questions'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="label">گزارش‌های داوری شده</span>
                <span class="value blue">{{ $myStats['discussions'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="label">تکالیف داوری شده</span>
                <span class="value blue">{{ $myStats['exercises'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <span class="label">مجموع داوری‌ها</span>
                <span class="value green">{{ ($myStats['questions'] ?? 0) + ($myStats['discussions'] ?? 0) + ($myStats['exercises'] ?? 0) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection