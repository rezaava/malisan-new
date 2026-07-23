@extends('layout.master')

@section('title')
ملیسان | خودآزمایی‌های {{ $user->name }} {{ $user->family }}
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/student-self-tests.css') }}">
@endsection

@section('mohtava')
<div class="container">
    <div class="header">
        <a href="{{ route('studentActivities', $user->courses->first()->id ?? 0) }}" class="back-btn">
            <i class="fas fa-arrow-right"></i> بازگشت
        </a>
        <div>
            <h4 class="title"><i class="fas fa-brain"></i> خودآزمایی‌های {{ $user->name }} {{ $user->family }}</h4>
            <p class="subtitle">لیست تمام خودآزمایی‌های انجام شده توسط دانشجو</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>عنوان</th>
                    <th>تاریخ</th>
                    <th>نمره</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($selfTests as $key => $selfTest)
                    @php
                        $score = $selfTest->score ?? 0;
                        $scoreClass = $score >= 15 ? 'high' : ($score >= 10 ? 'medium' : 'low');
                    @endphp
                    <tr>
                        <td data-label="ردیف">{{ $key + 1 }}</td>
                        <td data-label="عنوان">خودآزمایی {{ \Hekmatinasser\Verta\Verta::instance($selfTest->created_at)->format('Y/m/d') }}</td>
                        <td data-label="تاریخ">{{ \Hekmatinasser\Verta\Verta::instance($selfTest->created_at)->format('Y/m/d H:i') }}</td>
                        <td data-label="نمره">
                            <span class="score-badge {{ $scoreClass }}">
                                {{ number_format($score, 1) }}
                            </span>
                        </td>
                        <td data-label="عملیات">
                            <a href="{{ route('exam.results', $selfTest->id) }}" class="view-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>هیچ خودآزمایی توسط این دانشجو انجام نشده است.</p>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection