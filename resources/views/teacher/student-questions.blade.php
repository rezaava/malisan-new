@extends('layout.master')

@section('title')
ملیسان | سوالات {{ $user->name }} {{ $user->family }}
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/student-questions.css') }}">
@endsection

@section('mohtava')
<div class="container">
    <div class="header">
        <a href="{{ route('studentActivities', $user->courses->first()->id ?? 0) }}" class="back-btn">
            <i class="fas fa-arrow-right"></i> بازگشت
        </a>
        <div>
            <h4 class="title"><i class="fas fa-question-circle"></i> سوالات {{ $user->name }} {{ $user->family }}</h4>
            <p class="subtitle">لیست تمام سوالات ثبت شده توسط دانشجو</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>سوال</th>
                    <th>تاریخ</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $key => $question)
                    <tr>
                        <td data-label="ردیف">{{ $key + 1 }}</td>
                        <td data-label="سوال">{{ Str::limit($question->question, 50) }}</td>
                        <td data-label="تاریخ">{{ \Hekmatinasser\Verta\Verta::instance($question->created_at)->format('Y/m/d') }}</td>
                        <td data-label="وضعیت">
                            @php
                                $statusMap = [
                                    1 => ['class' => 'answered', 'text' => 'عالی'],
                                    2 => ['class' => 'answered', 'text' => 'خوب'],
                                    3 => ['class' => 'pending', 'text' => 'متوسط'],
                                    4 => ['class' => 'rejected', 'text' => 'رد شده'],
                                ];
                                $status = $statusMap[$question->status] ?? ['class' => 'pending', 'text' => 'در انتظار پاسخ'];
                            @endphp
                            <span class="status-badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>هیچ سوالی توسط این دانشجو ثبت نشده است.</p>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection