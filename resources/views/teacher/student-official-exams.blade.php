@extends('layout.master')

@section('title')
ملیسان | آزمون‌های رسمی {{ $user->name }} {{ $user->family }}
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/student-official-exams.css') }}">
@endsection

@section('mohtava')
<div class="container">
    <div class="header">
        <a href="{{ route('studentActivities', $user->courses->first()->id ?? 0) }}" class="back-btn">
            <i class="fas fa-arrow-right"></i> بازگشت
        </a>
        <div>
            <h4 class="title"><i class="fas fa-pencil-alt"></i> آزمون‌های رسمی {{ $user->name }} {{ $user->family }}</h4>
            <p class="subtitle">لیست تمام آزمون‌های رسمی برگزار شده</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>عنوان آزمون</th>
                    <th>تاریخ برگزاری</th>
                    <th>نمره</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($officialExams as $key => $exam)
                    @php
                        $score = $exam->score ?? 0;
                        $scoreClass = $score >= 15 ? 'high' : ($score >= 10 ? 'medium' : 'low');
                        $status = $score >= 10 ? 'passed' : 'failed';
                        $statusText = $score >= 10 ? 'پذیرفته شده' : 'رد شده';
                    @endphp
                    <tr>
                        <td data-label="ردیف">{{ $key + 1 }}</td>
                        <td data-label="عنوان آزمون">{{ $exam->azmon->title ?? 'بدون عنوان' }}</td>
                        <td data-label="تاریخ برگزاری">{{ \Hekmatinasser\Verta\Verta::instance($exam->created_at)->format('Y/m/d') }}</td>
                        <td data-label="نمره">
                            <span class="score-badge {{ $scoreClass }}">
                                {{ number_format($score, 2) }}
                            </span>
                        </td>
                        <td data-label="وضعیت">
                            <span class="status-badge {{ $status }}">{{ $statusText }}</span>
                        </td>
                        <td data-label="عملیات">
                            <a href="{{ route('exam.results', $exam->id) }}" class="view-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>هیچ آزمون رسمی توسط این دانشجو برگزار نشده است.</p>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection