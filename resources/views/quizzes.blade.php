@extends('layout.master')

@section('title')
ملیسان | مسابقات
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-quizzes.css')}}">
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
                <th>نام مسابقه</th>
                <th>داوران</th>
                <th>داوری</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>مسابقه برنامه‌نویسی</td>
                <td><a href="/judges/1" class="table-btn users-btn">داوران</a></td>
                <td><a href="/judging/1" class="table-btn register-btn">داوری</a></td>
            </tr>
            <tr>
                <td>مسابقه طراحی</td>
                <td><a href="/judges/2" class="table-btn users-btn">داوران</a></td>
                <td><a href="/judging/2" class="table-btn register-btn">داوری</a></td>
            </tr>
            <tr>
                <td>مسابقه مقاله‌نویسی</td>
                <td><a href="/judges/3" class="table-btn users-btn">داوران</a></td>
                <td><a href="/judging/3" class="table-btn register-btn">داوری</a></td>
            </tr>
            <tr>
                <td>مسابقه هوش مصنوعی</td>
                <td><a href="/judges/4" class="table-btn users-btn">داوران</a></td>
                <td><a href="/judging/4" class="table-btn register-btn">داوری</a></td>
            </tr>
            <tr>
                <td>مسابقه رباتیک</td>
                <td><a href="/judges/5" class="table-btn users-btn">داوران</a></td>
                <td><a href="/judging/5" class="table-btn register-btn">داوری</a></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection