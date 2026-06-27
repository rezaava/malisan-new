@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-index.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== ACTIVE EXAMS SECTION ===== */
    .active-exams-section {
        margin-top: 30px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 20px 24px;
    }

    .active-exams-section .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }

    .active-exams-section .section-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1a2332;
    }

    .active-exams-section .section-header h4 i {
        color: #f44336;
        margin-left: 8px;
    }

    .active-exams-section .section-header .badge-count {
        background: #f44336;
        color: #fff;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .exam-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #f0f4f9;
        transition: all 0.2s ease;
        flex-wrap: wrap;
        gap: 10px;
    }

    .exam-item:last-child {
        border-bottom: none;
    }

    .exam-item:hover {
        background: #f8fafc;
        border-radius: 10px;
    }

    .exam-item .exam-info {
        flex: 1;
        min-width: 150px;
    }

    .exam-item .exam-info .exam-title {
        font-weight: 600;
        color: #1a2332;
        font-size: 14px;
    }

    .exam-item .exam-info .exam-course {
        font-size: 13px;
        color: #6b7a8f;
        display: block;
    }

    .exam-item .exam-time {
        font-size: 13px;
        color: #6b7a8f;
        direction: ltr;
    }

    .btn-start-exam-sm {
        padding: 6px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        background: linear-gradient(135deg, #4caf50, #388e3c);
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-start-exam-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        color: #fff;
    }

    .no-exam-message {
        text-align: center;
        padding: 20px;
        color: #6b7a8f;
        font-size: 14px;
    }

    .no-exam-message i {
        font-size: 30px;
        display: block;
        margin-bottom: 8px;
        color: #d0d7e2;
    }

    /* ===== CARD DASH ===== */
    .card-dash {
        display: block;
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
    }

    .card-dash:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: inherit;
    }

    .card-dash .card-icon {
        font-size: 32px;
        margin-bottom: 10px;
        color: #1e6f9f;
    }

    .card-dash .card-title {
        font-size: 14px;
        font-weight: 600;
        color: #1a2332;
        margin-bottom: 4px;
    }

    .card-dash .card-count {
        font-size: 28px;
        font-weight: 800;
        color: #1a2332;
    }

    .card-dash .card-text-sm {
        font-size: 12px;
        color: #6b7a8f;
    }

    .card-dash .exam-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #f44336;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 20px;
    }
</style>
@endsection

@section('mohtava')
<div class="motivation-banner">
    <div class="motivation-text-en">
        {!! $message->text ?? 'به سامانه ملیسان خوش آمدید' !!}
    </div>
</div>

<div class="dashboard-cards">
    <div class="row g-4">
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('courses.st') }}" class="card-dash">
                <div class="card-icon"><i class="fas fa-chalkboard"></i></div>
                <div class="card-title">درس‌ها</div>
                <div class="card-count">{{ Auth::user()->courses()->count() }}</div>
                <div class="card-text-sm">درس فعال</div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card-dash">
                <div class="card-icon"><i class="fas fa-layer-group"></i></div>
                <div class="card-title">دوره‌ها</div>
                <div class="card-count">{{ $course_count ?? 0 }}</div>
                <div class="card-text-sm">دوره در حال برگزاری</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card-dash">
                <div class="card-icon"><i class="fas fa-clipboard-list"></i></div>
                <div class="card-title">آزمون‌ها</div>
                <div class="card-count">{{ $active_exam_count ?? 0 }}</div>
                <div class="card-text-sm">آزمون فعال</div>
                @if(($active_exam_count ?? 0) > 0)
                    <span class="exam-badge">{{ $active_exam_count }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ===== ACTIVE EXAMS ===== --}}
@if(isset($activeExams) && $activeExams->count() > 0)
    <div class="active-exams-section">
        @foreach($activeExams->take(5) as $exam)
            <div class="exam-item">
                <div class="exam-info">
                    <span class="exam-title">{{ $exam->title }}</span>
                    <span class="exam-course"><i class="fas fa-book-open" style="font-size:10px;"></i> {{ $exam->course->name ?? 'نامشخص' }}</span>
                </div>
                <div class="exam-time">
                    <i class="fas fa-clock" style="color:#1e6f9f;"></i>
                    {{ \Hekmatinasser\Verta\Verta::instance($exam->end)->format('H:i') }}
                </div>
                <form action="{{ route('exam.start') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="azmon_id" value="{{ $exam->id }}">
                    <button type="submit" class="btn-start-exam-sm">
                        <i class="fas fa-play"></i> شروع
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endif
@endsection