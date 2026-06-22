@extends('layout.master')

@section('title')
ورود
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-auth.css')}}">
@endsection

@section('mohtava')
<div class="auth-container">
    <div class="auth-header">
        <div class="logo-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h2>ورود</h2>
        <p>به سامانه آموزشی ملیسان خوش آمدید</p>
    </div>

    <form>
        <div class="form-group">
            <label for="national-code">کد ملی</label>
            <div class="input-wrapper">
                <i class="fas fa-id-card"></i>
                <input type="text" id="national-code" placeholder="کد ملی خود را وارد کنید">
            </div>
        </div>

        <div class="form-group">
            <label for="password">رمز عبور</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" placeholder="رمز عبور خود را وارد کنید">
            </div>
        </div>

        <button type="submit" class="auth-btn">ورود</button>
    </form>

    <div class="auth-link">
        ثبت نام نکرده‌اید؟ <a href="{{route('register')}}">ثبت نام</a>
    </div>
</div>
@endsection