@extends('layout.master')

@section('title')
ملیسان | فعالیت دانشجویان
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-activities.css')}}">
<link rel="stylesheet" href="{{asset('css/badge.css')}}">
@endsection

@section('mohtava')
<div class="activities-container">
    <div class="activities-header d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
        <div class="info-badge course-badge">
            <span class="badge-icon">
                <i class="fas fa-book-open"></i>
            </span>
            <span class="badge-label">فعالیت دانشجویان در درس:</span>
            <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
        </div>
        @include('layout.backbtn')
    </div>

    <div class="table-wrapper">
        <table class="activities-table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام</th>
                    <th>سوال‌ها</th>
                    <th>گزارش‌ها</th>
                    <th>تکالیف</th>
                    <th>خودآزمایی‌ها</th>
                    <th>آزمون‌های رسمی</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $key => $user)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->name }} {{ $user->family }}</td>

                        <td>
                            <a href="{{ route('studentQuestions', [$user->id, $course->id]) }}" class="action-btn questions-btn" title="سوال‌ها">
                                <i class="fas fa-question-circle"></i>
                                <span>{{ $user->questions_count ?? 0 }}</span>
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('studentReports', [$user->id, $course->id]) }}" class="action-btn reports-btn" title="گزارش‌ها">
                                <i class="fas fa-file-alt"></i>
                                <span>{{ $user->reports_count ?? 0 }}</span>
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('studentHomeworks', [$user->id, $course->id]) }}" class="action-btn homeworks-btn" title="تکالیف">
                                <i class="fas fa-tasks"></i>
                                <span>{{ $user->homeworks_count ?? 0 }}</span>
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('studentSelfTests', [$user->id, $course->id]) }}" class="action-btn self-tests-btn" title="خودآزمایی‌ها">
                                <i class="fas fa-brain"></i>
                                <span>{{ $user->self_tests_count ?? 0 }}</span>
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('studentOfficialExams', [$user->id, $course->id]) }}" class="action-btn exams-btn" title="آزمون‌های رسمی">
                                <i class="fas fa-pencil-alt"></i>
                                <span>{{ $user->official_exams_count ?? 0 }}</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection