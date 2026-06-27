@extends('layout.master')

@section('title')
ملیسان | آمار داوری
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .stats-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .stats-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .stats-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .stats-header h2 i {
        color: #1e6f9f;
        margin-left: 10px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stats-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 24px 28px;
        border-right: 4px solid #1e6f9f;
    }

    .stats-card .card-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stats-card .card-title i {
        color: #1e6f9f;
        font-size: 20px;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f4f9;
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .stat-item .label {
        color: #6b7a8f;
        font-size: 14px;
    }

    .stat-item .value {
        font-weight: 700;
        font-size: 16px;
        color: #1a2332;
    }

    .stat-item .value.green { color: #4caf50; }
    .stat-item .value.orange { color: #ff9800; }
    .stat-item .value.red { color: #f44336; }
    .stat-item .value.blue { color: #1e6f9f; }

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

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="stats-container">
    <div class="stats-header">
        <h2>
            <i class="fas fa-chart-bar"></i>
            آمار داوری
        </h2>
        <a href="{{ route('judgment.index') }}" class="btn-back">
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
    </div>
</div>
@endsection