@extends('layout.master')

@section('title')
ملیسان | فعالیت دانشجویان
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-activities.css')}}">
@endsection

@section('mohtava')
<div class="activities-container">
    <div class="activities-header">
        <h4 class="activities-title">فعالیت دانشجویان</h4>
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
                            <a href="{{ route('studentQuestions',$user->id) }}" class="action-btn questions-btn" title="سوال‌ها">
                                <i class="fas fa-question-circle"></i>
                                <span>{{ $user->questions_count ?? 0 }}</span>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('studentReports',$user->id) }}" class="action-btn reports-btn" title="گزارش‌ها">
                                <i class="fas fa-file-alt"></i>
                                <span>{{ $user->reports_count ?? 0 }}</span>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('studentHomeworks',$user->id) }}" class="action-btn homeworks-btn" title="تکالیف">
                                <i class="fas fa-tasks"></i>
                                <span>{{ $user->homeworks_count ?? 0 }}</span>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('studentSelfTests',$user->id) }}" class="action-btn self-tests-btn" title="خودآزمایی‌ها">
                                <i class="fas fa-brain"></i>
                                <span>{{ $user->self_tests_count ?? 0 }}</span>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('studentOfficialExams',$user->id) }}" class="action-btn exams-btn" title="آزمون‌های رسمی">
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