@extends('layout.master')

@section('title')
ملیسان | دانشجویان درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-students-list.css')}}">
@endsection

@section('mohtava')
<div class="students-container">
    <div class="students-header">
        <h4 class="students-title">دانشجویان درس : <span>فرسایش بادی ۴۰۴۲</span></h4>
        <a href="/export/students" class="export-btn">
            <i class="fas fa-file-excel"></i>
            خروجی فایل اکسل
        </a>
    </div>

    <div class="table-wrapper">
        <table class="students-table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام کاربر</th>
                    <th>مشخصات</th>
                    <th>پیشرفت درسی</th>
                    <th>سابقه</th>
                    <th>خودآزمایی</th>
                    <th>صفات</th>
                    <th>رویداد</th>
                    <th>اخراج</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>احمد رضایی</td>
                    <td>
                        <a href="/students/1/profile" class="action-btn profile-btn" title="مشخصات">
                            <i class="fas fa-user-circle"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/1/progress" class="action-btn progress-btn" title="پیشرفت درسی">
                            <i class="fas fa-chart-line"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/1/history" class="action-btn history-btn" title="سابقه">
                            <i class="fas fa-history"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/1/self-test" class="action-btn self-test-btn" title="خودآزمایی">
                            <i class="fas fa-brain"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/1/attributes" class="action-btn attributes-btn" title="صفات">
                            <i class="fas fa-list-ul"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/1/events" class="action-btn events-btn" title="رویداد">
                            <i class="fas fa-calendar-alt"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/1/remove" class="action-btn remove-btn" title="اخراج">
                            <i class="fas fa-user-minus"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>مریم کریمی</td>
                    <td>
                        <a href="/students/2/profile" class="action-btn profile-btn" title="مشخصات">
                            <i class="fas fa-user-circle"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/2/progress" class="action-btn progress-btn" title="پیشرفت درسی">
                            <i class="fas fa-chart-line"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/2/history" class="action-btn history-btn" title="سابقه">
                            <i class="fas fa-history"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/2/self-test" class="action-btn self-test-btn" title="خودآزمایی">
                            <i class="fas fa-brain"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/2/attributes" class="action-btn attributes-btn" title="صفات">
                            <i class="fas fa-list-ul"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/2/events" class="action-btn events-btn" title="رویداد">
                            <i class="fas fa-calendar-alt"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/2/remove" class="action-btn remove-btn" title="اخراج">
                            <i class="fas fa-user-minus"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>علی محمدی</td>
                    <td>
                        <a href="/students/3/profile" class="action-btn profile-btn" title="مشخصات">
                            <i class="fas fa-user-circle"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/3/progress" class="action-btn progress-btn" title="پیشرفت درسی">
                            <i class="fas fa-chart-line"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/3/history" class="action-btn history-btn" title="سابقه">
                            <i class="fas fa-history"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/3/self-test" class="action-btn self-test-btn" title="خودآزمایی">
                            <i class="fas fa-brain"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/3/attributes" class="action-btn attributes-btn" title="صفات">
                            <i class="fas fa-list-ul"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/3/events" class="action-btn events-btn" title="رویداد">
                            <i class="fas fa-calendar-alt"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/3/remove" class="action-btn remove-btn" title="اخراج">
                            <i class="fas fa-user-minus"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>سارا احمدی</td>
                    <td>
                        <a href="/students/4/profile" class="action-btn profile-btn" title="مشخصات">
                            <i class="fas fa-user-circle"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/4/progress" class="action-btn progress-btn" title="پیشرفت درسی">
                            <i class="fas fa-chart-line"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/4/history" class="action-btn history-btn" title="سابقه">
                            <i class="fas fa-history"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/4/self-test" class="action-btn self-test-btn" title="خودآزمایی">
                            <i class="fas fa-brain"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/4/attributes" class="action-btn attributes-btn" title="صفات">
                            <i class="fas fa-list-ul"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/4/events" class="action-btn events-btn" title="رویداد">
                            <i class="fas fa-calendar-alt"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/students/4/remove" class="action-btn remove-btn" title="اخراج">
                            <i class="fas fa-user-minus"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection