@extends('layout.master')

@section('title')
ملیسان | نمرات دانشجویان
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-grades-list.css')}}">
@endsection

@section('mohtava')
<div class="grades-container">
    <div class="grades-header">
        <h4 class="grades-title">نمرات دانشجویان : <span>فرسایش بادی ۴۰۴۲</span></h4>
        <a href="/students/profile" class="students-profile-btn">
            <i class="fas fa-arrow-left"></i>
            مشخصات دانشجویان
        </a>
    </div>

    <div class="table-wrapper">
        <table class="grades-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>نام و نام خانوادگی</th>
                    <th>ارزشیابی</th>
                    <th>میانگین آزمون</th>
                    <th>پایان ترم</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>احمد رضایی</td>
                    <td>۱۸.۵۰</td>
                    <td>۱۷.۲۵</td>
                    <td>۱۹.۰۰</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>مریم کریمی</td>
                    <td>۱۶.۰۰</td>
                    <td>۱۵.۵۰</td>
                    <td>۱۷.۵۰</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>علی محمدی</td>
                    <td>۱۹.۰۰</td>
                    <td>۱۸.۷۵</td>
                    <td>۲۰.۰۰</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>سارا احمدی</td>
                    <td>۱۴.۵۰</td>
                    <td>۱۳.۲۵</td>
                    <td>۱۶.۰۰</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>محمد حسینی</td>
                    <td>۱۷.۰۰</td>
                    <td>۱۶.۰۰</td>
                    <td>۱۸.۰۰</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>زهرا رضایی</td>
                    <td>۱۵.۵۰</td>
                    <td>۱۴.۷۵</td>
                    <td>۱۶.۵۰</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection