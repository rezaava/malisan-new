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
        {!! $message->text ?? 'به سامانه ملیسان خوش آمدید' !!}
    </div>
</div>

<div class="dashboard-cards">
    <div class="row g-4">
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin_angizesh') }}" class="card-dash shadow">
                <div class="card-icon"><i class="fas fa-clipboard-list"></i></div>
                <div class="card-title">پیام های انگیزشی</div>
                <div class="card-count">{{ $massage }}</div>
                <div class="card-text-sm">پیام های انگیزشی</div>
            </a>
        </div>
    </div>
</div>
@endsection