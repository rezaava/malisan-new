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
                <tr>
                    <td>1</td>
                    <td>احمد رضایی</td>
                    <td>
                        <a href="/student/1/questions" class="action-btn questions-btn" title="سوال‌ها">
                            <i class="fas fa-question-circle"></i>
                            <span>۱۲</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/1/reports" class="action-btn reports-btn" title="گزارش‌ها">
                            <i class="fas fa-file-alt"></i>
                            <span>۸</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/1/homeworks" class="action-btn homeworks-btn" title="تکالیف">
                            <i class="fas fa-tasks"></i>
                            <span>۵</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/1/self-tests" class="action-btn self-tests-btn" title="خودآزمایی‌ها">
                            <i class="fas fa-brain"></i>
                            <span>۱۰</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/1/official-exams" class="action-btn exams-btn" title="آزمون‌های رسمی">
                            <i class="fas fa-pencil-alt"></i>
                            <span>۳</span>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>مریم کریمی</td>
                    <td>
                        <a href="/student/2/questions" class="action-btn questions-btn" title="سوال‌ها">
                            <i class="fas fa-question-circle"></i>
                            <span>۱۵</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/2/reports" class="action-btn reports-btn" title="گزارش‌ها">
                            <i class="fas fa-file-alt"></i>
                            <span>۱۰</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/2/homeworks" class="action-btn homeworks-btn" title="تکالیف">
                            <i class="fas fa-tasks"></i>
                            <span>۷</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/2/self-tests" class="action-btn self-tests-btn" title="خودآزمایی‌ها">
                            <i class="fas fa-brain"></i>
                            <span>۱۲</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/2/official-exams" class="action-btn exams-btn" title="آزمون‌های رسمی">
                            <i class="fas fa-pencil-alt"></i>
                            <span>۴</span>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>علی محمدی</td>
                    <td>
                        <a href="/student/3/questions" class="action-btn questions-btn" title="سوال‌ها">
                            <i class="fas fa-question-circle"></i>
                            <span>۲۰</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/3/reports" class="action-btn reports-btn" title="گزارش‌ها">
                            <i class="fas fa-file-alt"></i>
                            <span>۱۴</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/3/homeworks" class="action-btn homeworks-btn" title="تکالیف">
                            <i class="fas fa-tasks"></i>
                            <span>۹</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/3/self-tests" class="action-btn self-tests-btn" title="خودآزمایی‌ها">
                            <i class="fas fa-brain"></i>
                            <span>۱۵</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/3/official-exams" class="action-btn exams-btn" title="آزمون‌های رسمی">
                            <i class="fas fa-pencil-alt"></i>
                            <span>۵</span>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>سارا احمدی</td>
                    <td>
                        <a href="/student/4/questions" class="action-btn questions-btn" title="سوال‌ها">
                            <i class="fas fa-question-circle"></i>
                            <span>۸</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/4/reports" class="action-btn reports-btn" title="گزارش‌ها">
                            <i class="fas fa-file-alt"></i>
                            <span>۵</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/4/homeworks" class="action-btn homeworks-btn" title="تکالیف">
                            <i class="fas fa-tasks"></i>
                            <span>۳</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/4/self-tests" class="action-btn self-tests-btn" title="خودآزمایی‌ها">
                            <i class="fas fa-brain"></i>
                            <span>۶</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/4/official-exams" class="action-btn exams-btn" title="آزمون‌های رسمی">
                            <i class="fas fa-pencil-alt"></i>
                            <span>۲</span>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>محمد حسینی</td>
                    <td>
                        <a href="/student/5/questions" class="action-btn questions-btn" title="سوال‌ها">
                            <i class="fas fa-question-circle"></i>
                            <span>۱۸</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/5/reports" class="action-btn reports-btn" title="گزارش‌ها">
                            <i class="fas fa-file-alt"></i>
                            <span>۱۲</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/5/homeworks" class="action-btn homeworks-btn" title="تکالیف">
                            <i class="fas fa-tasks"></i>
                            <span>۸</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/5/self-tests" class="action-btn self-tests-btn" title="خودآزمایی‌ها">
                            <i class="fas fa-brain"></i>
                            <span>۱۴</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/5/official-exams" class="action-btn exams-btn" title="آزمون‌های رسمی">
                            <i class="fas fa-pencil-alt"></i>
                            <span>۴</span>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>زهرا رضایی</td>
                    <td>
                        <a href="/student/6/questions" class="action-btn questions-btn" title="سوال‌ها">
                            <i class="fas fa-question-circle"></i>
                            <span>۱۰</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/6/reports" class="action-btn reports-btn" title="گزارش‌ها">
                            <i class="fas fa-file-alt"></i>
                            <span>۷</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/6/homeworks" class="action-btn homeworks-btn" title="تکالیف">
                            <i class="fas fa-tasks"></i>
                            <span>۴</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/6/self-tests" class="action-btn self-tests-btn" title="خودآزمایی‌ها">
                            <i class="fas fa-brain"></i>
                            <span>۸</span>
                        </a>
                    </td>
                    <td>
                        <a href="/student/6/official-exams" class="action-btn exams-btn" title="آزمون‌های رسمی">
                            <i class="fas fa-pencil-alt"></i>
                            <span>۳</span>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection