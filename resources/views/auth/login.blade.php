@extends('auth.layout.master')

@section('title')
ورود
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-auth.css')}}">
<style>
    /* استایل‌های اختصاصی صفحه ورود */
    .auth-container {
        max-width: 420px;
        margin: 0 auto;
        padding: 40px 30px;
        background: rgba(255,255,255,.92);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        box-shadow: 0 15px 40px rgba(0,0,0,.25);
    }
    .auth-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .auth-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #333;
        margin: 10px 0 5px;
    }
    .auth-header p {
        color: #666;
        font-size: 14px;
    }
    .logo-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 10px;
        background: linear-gradient(45deg,#7367f0,#9c27b0);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 28px;
    }
    .form-group {
        margin-bottom: 22px;
    }
    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #444;
        font-size: 14px;
    }
    .input-wrapper {
        position: relative;
    }
    .input-wrapper i {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 16px;
    }
    .input-wrapper input {
        width: 100%;
        padding: 14px 45px 14px 14px;
        border: 1px solid #dcdfe6;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        transition: .2s;
        background: #fff;
    }
    .input-wrapper input:focus {
        border-color: #7367f0;
        box-shadow: 0 0 0 4px rgba(115,103,240,.12);
    }
    .auth-btn {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(45deg,#7367f0,#9c27b0);
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
    }
    .auth-btn:hover {
        transform: translateY(-1px);
        opacity: .95;
    }
    .auth-link {
        text-align: center;
        margin-top: 22px;
        font-size: 14px;
    }
    .auth-link a {
        color: #7367f0;
        text-decoration: none;
        font-weight: 700;
    }
    .auth-link a:hover {
        text-decoration: underline;
    }
    .alert {
        background: #ffe5e5;
        color: #c0392b;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 15px;
        font-size: 13px;
        text-align: center;
    }
    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 18px;
    }
    .form-check input {
        margin-left: 8px;
    }
    .form-check label {
        font-size: 14px;
        color: #555;
    }
</style>
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

    <form method="post" action="{{ route('loginPost') }}">
        @csrf
        @if (session()->has('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif

        <div class="form-group">
            <label for="national-code">کد ملی</label>
            <div class="input-wrapper">
                <i class="fas fa-id-card"></i>
                <input type="text" id="national-code" name="national" placeholder="کد ملی خود را وارد کنید" required value="{{ old('national') }}">
            </div>
        </div>

        <div class="form-group">
            <label for="password">رمز عبور</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="رمز عبور خود را وارد کنید" required>
            </div>
        </div>

        <div class="form-check">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">مرا به خاطر بسپار</label>
        </div>

        <button type="submit" class="auth-btn">ورود</button>
    </form>

    <div class="auth-link">
        ثبت نام نکرده‌اید؟ <a href="{{ route('register') }}">ثبت نام</a>
    </div>
</div>
@endsection