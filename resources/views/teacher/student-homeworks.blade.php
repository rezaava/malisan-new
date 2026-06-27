@extends('layout.master')

@section('title')
ملیسان | تکالیف {{ $user->name }} {{ $user->family }}
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
    .header { display: flex; align-items: center; gap: 20px; margin-bottom: 25px; flex-wrap: wrap; }
    .back-btn { padding: 8px 20px; background: #f0f4f9; border-radius: 10px; text-decoration: none; color: #1a2332; display: inline-flex; align-items: center; gap: 8px; }
    .back-btn:hover { background: #e3e8ef; }
    .title { font-size: 20px; font-weight: 700; color: #1a2332; margin: 0; }
    .title i { color: #1e6f9f; margin-left: 10px; }
    .subtitle { font-size: 14px; color: #6b7a8f; margin: 4px 0 0; }
    .table-wrapper { background: #fff; border-radius: 20px; box-shadow: 0 2px 20px rgba(0,0,0,0.06); overflow: hidden; }
    .table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .table thead { background: #f8fafc; border-bottom: 2px solid #eef2f7; }
    .table thead th { padding: 14px 20px; text-align: right; font-weight: 700; color: #4a5a6e; }
    .table tbody tr { border-bottom: 1px solid #f0f4f9; transition: all 0.2s; }
    .table tbody tr:hover { background: #f8fafc; }
    .table tbody td { padding: 14px 20px; vertical-align: middle; color: #1a2332; }
    .status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .status-badge.accepted { background: #e8f5e9; color: #2e7d32; }
    .status-badge.pending { background: #fff3cd; color: #e65100; }
    .status-badge.rejected { background: #ffebee; color: #c62828; }
    .status-badge.rated { background: #e3f2fd; color: #1e6f9f; }
    .view-btn { padding: 6px 12px; background: #e3f2fd; border-radius: 8px; color: #1e6f9f; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; cursor: pointer; border: none; font-size: 14px; }
    .view-btn:hover { background: #1e6f9f; color: #fff; }
    .empty-state { text-align: center; padding: 60px 20px; color: #6b7a8f; }
    .empty-state i { font-size: 48px; color: #d0d7e2; display: block; margin-bottom: 16px; }
    .rate-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 12px; border-radius: 12px; font-size: 11px; font-weight: 600; }
    .rate-badge.excellent { background: #e8f5e9; color: #2e7d32; }
    .rate-badge.good { background: #e3f2fd; color: #1e6f9f; }
    .rate-badge.medium { background: #fff3e0; color: #e65100; }
    .rate-badge.weak { background: #ffebee; color: #c62828; }
    @media (max-width: 768px) { .table thead { display: none; } .table tbody tr { display: block; padding: 12px; } .table tbody td { display: flex; justify-content: space-between; padding: 6px 0; border: none; } .table tbody td::before { content: attr(data-label); font-weight: 700; color: #4a5a6e; } }
</style>
@endsection

@section('mohtava')
<div class="container">
    <div class="header">
        <a href="{{ route('studentActivities', $user->courses->first()->id ?? 0) }}" class="back-btn">
            <i class="fas fa-arrow-right"></i> بازگشت
        </a>
        <div>
            <h4 class="title"><i class="fas fa-tasks"></i> تکالیف {{ $user->name }} {{ $user->family }}</h4>
            <p class="subtitle">لیست تمام تکالیف ارسال شده توسط دانشجو</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>عنوان تکلیف</th>
                    <th>تاریخ تحویل</th>
                    <th>ارزیابی</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($homeworks as $key => $homework)
                    @php
                        $rateLabels = [
                            'excellent' => 'عالی',
                            'good' => 'خوب',
                            'medium' => 'متوسط',
                            'weak' => 'بد'
                        ];
                        $statusMap = [
                            'scored' => ['class' => 'rated', 'text' => 'ارزیابی شده'],
                            'rated' => ['class' => 'rated', 'text' => 'ارزیابی شده'],
                        ];
                        $status = $statusMap[$homework->status] ?? ['class' => 'pending', 'text' => 'در انتظار بررسی'];
                    @endphp
                    <tr>
                        <td data-label="ردیف">{{ $key + 1 }}</td>
                        <td data-label="عنوان تکلیف">{{ Str::limit($homework->exercise->text ?? 'بدون عنوان', 40) }}</td>
                        <td data-label="تاریخ تحویل">{{ \Hekmatinasser\Verta\Verta::instance($homework->created_at)->format('Y/m/d') }}</td>
                        <td data-label="ارزیابی">
                            @if($homework->status == 'rated' || $homework->status == 'scored')
                                <span class="rate-badge {{ $homework->rate ?? '' }}">
                                    {{ $rateLabels[$homework->rate] ?? 'نامشخص' }}
                                </span>
                            @else
                                <span style="color:#6b7a8f;font-size:13px;">-</span>
                            @endif
                        </td>
                        <td data-label="وضعیت">
                            <span class="status-badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                        </td>
                        <td data-label="عملیات">
                            <a href="{{ route('exercise.answers', $homework->exercise_id) }}" class="view-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>هیچ تکلیفی توسط این دانشجو ارسال نشده است.</p>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection