@extends('layout.master')

@section('title')
ملیسان | گزارشات {{ $user->name }} {{ $user->family }}
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/student-reports.css') }}">
@endsection

@section('mohtava')
<div class="container">
    <div class="header">
        <a href="{{ route('studentActivities', $user->courses->first()->id ?? 0) }}" class="back-btn">
            <i class="fas fa-arrow-right"></i> بازگشت
        </a>
        <div>
            <h4 class="title"><i class="fas fa-file-alt"></i> گزارشات {{ $user->name }} {{ $user->family }}</h4>
            <p class="subtitle">لیست تمام گزارشات ارسال شده توسط دانشجو</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>درس گزارش</th>
                    <th>تاریخ</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $key => $report)
                    <tr>
                        <td data-label="ردیف">{{ $key + 1 }}</td>
                        <td data-label="درس گزارش">{{ Str::limit($report->session->name ?? 'بدون عنوان', 50) }}</td>
                        <td data-label="تاریخ">{{ \Hekmatinasser\Verta\Verta::instance($report->created_at)->format('Y/m/d') }}</td>
                        <td data-label="وضعیت">
                            <span class="status-badge pending">در انتظار بررسی</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>هیچ گزارشی توسط این دانشجو ارسال نشده است.</p>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection