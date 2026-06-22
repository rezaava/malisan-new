@extends('layout.master')

@section('title')
ثبت نام
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
        <h2>ثبت نام</h2>
        <p>اطلاعات خود را برای ثبت نام وارد کنید</p>
    </div>

    <form>
        <div class="form-row">
            <div class="form-group">
                <label for="name">نام</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="name" placeholder="نام خود را وارد کنید">
                </div>
            </div>
            <div class="form-group">
                <label for="family">نام خانوادگی</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="family" placeholder="نام خانوادگی خود را وارد کنید">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="national-code">کد ملی</label>
            <div class="input-wrapper">
                <i class="fas fa-id-card"></i>
                <input type="text" id="national-code" placeholder="کد ملی خود را وارد کنید">
            </div>
        </div>

        <div class="form-group">
            <label for="mobile">موبایل</label>
            <div class="input-wrapper">
                <i class="fas fa-mobile-alt"></i>
                <input type="tel" id="mobile" placeholder="شماره موبایل خود را وارد کنید">
            </div>
        </div>

        <div class="form-group">
            <label for="password">رمز عبور</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" placeholder="رمز عبور خود را وارد کنید">
            </div>
        </div>

        <button type="submit" class="auth-btn">ثبت نام</button>
    </form>

    <div class="auth-link">
        قبلاً ثبت نام کرده‌اید؟ <a href="{{route('login')}}">ورود</a>
    </div>
</div>
@endsection