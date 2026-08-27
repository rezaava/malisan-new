@extends('layout.master')

@section('title')
ملیسان | تکالیف {{ $user->name }} {{ $user->family }}
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/student-homeworks.css') }}">
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
                        <td data-label="عنوان تکلیف">{!! $homework->exercise->text !!}</td>
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
                            <a href="{{ route('exercise.answers', [$homework->exercise_id, $homework->user_id]) }}" class="view-btn">
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