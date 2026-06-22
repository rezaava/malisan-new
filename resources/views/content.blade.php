@extends('layout.master')

@section('title')
ملیسان | تولید محتوا
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-content.css')}}">
@endsection

@section('mohtava')
<div class="content-header">
    <h2>گزینه‌های طول صفحه</h2>
</div>

<div class="search-section">
    <div class="search-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="search-input" placeholder="جستجو...">
    </div>
</div>

<div class="table-section">
    <table class="content-table">
        <thead>
            <tr>
                <th>درس</th>
                <th>سطح</th>
                <th>عنوان</th>
                <th>کاربران</th>
                <th>ثبت سوال</th>
                <th>سوالات من</th>
                <th>بانک سوالات</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ریاضیات پایه</td>
                <td>پیشرفته</td>
                <td>مشتق و انتگرال</td>
                <td><a href="/users/1" class="table-btn users-btn">کاربران</a></td>
                <td><a href="/questions/create/1" class="table-btn register-btn">ثبت</a></td>
                <td><a href="/my-questions/1" class="table-btn questions-btn">سوالات</a></td>
                <td><a href="/question-bank/1" class="table-btn bank-btn">بانک</a></td>
            </tr>
            <tr>
                <td>فیزیک عمومی</td>
                <td>متوسط</td>
                <td>مکانیک سیالات</td>
                <td><a href="/users/2" class="table-btn users-btn">کاربران</a></td>
                <td><a href="/questions/create/2" class="table-btn register-btn">ثبت</a></td>
                <td><a href="/my-questions/2" class="table-btn questions-btn">سوالات</a></td>
                <td><a href="/question-bank/2" class="table-btn bank-btn">بانک</a></td>
            </tr>
            <tr>
                <td>برنامه‌نویسی وب</td>
                <td>پیشرفته</td>
                <td>فریمورک لاراول</td>
                <td><a href="/users/3" class="table-btn users-btn">کاربران</a></td>
                <td><a href="/questions/create/3" class="table-btn register-btn">ثبت</a></td>
                <td><a href="/my-questions/3" class="table-btn questions-btn">سوالات</a></td>
                <td><a href="/question-bank/3" class="table-btn bank-btn">بانک</a></td>
            </tr>
            <tr>
                <td>پایگاه داده</td>
                <td>مقدماتی</td>
                <td>SQL و NoSQL</td>
                <td><a href="/users/4" class="table-btn users-btn">کاربران</a></td>
                <td><a href="/questions/create/4" class="table-btn register-btn">ثبت</a></td>
                <td><a href="/my-questions/4" class="table-btn questions-btn">سوالات</a></td>
                <td><a href="/question-bank/4" class="table-btn bank-btn">بانک</a></td>
            </tr>
            <tr>
                <td>هوش مصنوعی</td>
                <td>پیشرفته</td>
                <td>یادگیری عمیق</td>
                <td><a href="/users/5" class="table-btn users-btn">کاربران</a></td>
                <td><a href="/questions/create/5" class="table-btn register-btn">ثبت</a></td>
                <td><a href="/my-questions/5" class="table-btn questions-btn">سوالات</a></td>
                <td><a href="/question-bank/5" class="table-btn bank-btn">بانک</a></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection