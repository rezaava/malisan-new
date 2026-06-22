@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection


@section('head')
<link rel="stylesheet" href="{{asset('css/style-index.css')}}">
@endsection



@section('mohtava')
<div class="motivation-banner">
    <div class="motivation-text-en">
        "Believing in yourself gives you more courage and confidence to go after your dreams and success"
    </div>
    <div class="motivation-text-fa">
        باور به خود به شما شجاعت و اعتماد به نفس بیشتری می‌دهد تا به دنبال رویاها و موفقیت خود بروید.
    </div>
</div>

<div class="dashboard-cards">
    <div class="row g-4">
        <div class="col-md-3 col-sm-6">
            <a href="/courses" class="card-dash">
                <div class="card-icon"><i class="fas fa-chalkboard"></i></div>
                <div class="card-title">درس‌ها</div>
                <div class="card-count">۸</div>
                <div class="card-text-sm">درس فعال</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="/students" class="card-dash">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <div class="card-title">دانشجو‌ها</div>
                <div class="card-count">۱,۲۴۷</div>
                <div class="card-text-sm">دانشجوی فعال</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="/malisan-courses" class="card-dash">
                <div class="card-icon"><i class="fas fa-layer-group"></i></div>
                <div class="card-title">دوره‌ها</div>
                <div class="card-count">۱۲</div>
                <div class="card-text-sm">دوره در حال برگزاری</div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="/exams" class="card-dash">
                <div class="card-icon"><i class="fas fa-clipboard-list"></i></div>
                <div class="card-title">آزمون‌ها</div>
                <div class="card-count">۳</div>
                <div class="card-text-sm">آزمون در حال برگزاری</div>
            </a>
        </div>
    </div>
</div>
@endsection