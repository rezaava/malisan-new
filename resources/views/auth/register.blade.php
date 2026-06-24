@extends('auth.layout.master')

@section('title')
ثبت نام
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-auth.css')}}">
<style>
    /* استایل‌های اختصاصی صفحه ثبت نام */
    .auth-container {
        max-width: 430px;
        margin: 0 auto;
        padding: 35px 30px;
        background: rgba(255,255,255,.92);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        box-shadow: 0 20px 45px rgba(0,0,0,.25);
    }
    .auth-header {
        text-align: center;
        margin-bottom: 28px;
    }
    .auth-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #222;
        margin: 8px 0 4px;
    }
    .auth-header p {
        color: #666;
        font-size: 14px;
    }
    .logo-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 8px;
        background: linear-gradient(45deg,#7367f0,#9c27b0);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 28px;
    }
    .form-row {
        display: flex;
        gap: 15px;
    }
    .form-row .form-group {
        flex: 1;
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
    .error-text {
        margin-top: 6px;
        color: #e53935;
        font-size: 12px;
    }
</style>
@endsection

@section('mohtava')
<div class="auth-container">
    <div class="auth-header">
        <div class="logo-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h2>ثبت نام</h2>
        <p>اکنون به ما بپیوندید!</p>
    </div>

    @if(session('danger'))
        <div class="error-text" style="margin-bottom:15px;text-align:center;">
            {{ session('danger') }}
        </div>
    @endif

    <form method="post" action="{{ route('registerPost') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label for="name">نام</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="name" name="name" placeholder="نام خود را وارد کنید" required value="{{ old('name') }}">
                </div>
                @if($errors->has('name'))
                    <div class="error-text">{{ $errors->first('name') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="family">نام خانوادگی</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="family" name="family" placeholder="نام خانوادگی خود را وارد کنید" required value="{{ old('family') }}">
                </div>
                @if($errors->has('family'))
                    <div class="error-text">{{ $errors->first('family') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="national-code">کد ملی</label>
            <div class="input-wrapper">
                <i class="fas fa-id-card"></i>
                <input type="text" id="national-code" name="national" pattern="[0-9]{10}" placeholder="کد ملی خود را وارد کنید" required value="{{ old('national') }}">
            </div>
            @if($errors->has('national'))
                <div class="error-text">{{ $errors->first('national') }}</div>
            @endif
        </div>

        <div class="form-group">
            <label for="mobile">موبایل</label>
            <div class="input-wrapper">
                <i class="fas fa-mobile-alt"></i>
                <input type="tel" id="mobile" name="mobile" pattern="[0-9]{11}" placeholder="شماره موبایل خود را وارد کنید" required value="{{ old('mobile') }}">
            </div>
            @if($errors->has('mobile'))
                <div class="error-text">{{ $errors->first('mobile') }}</div>
            @endif
        </div>

        <div class="form-group">
            <label for="password">رمز عبور</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="رمز عبور خود را وارد کنید" required>
            </div>
            @if($errors->has('password'))
                <div class="error-text">{{ $errors->first('password') }}</div>
            @endif
        </div>

        {{-- TYPE مخفی --}}
        <div hidden>
            @if (\Route::current()->getName() == 'global')
                <input type="radio" id="teacher" name="type" checked value="1">
            @else
                <input type="radio" id="student" name="type" checked value="2">
            @endif
        </div>

        <button type="submit" class="auth-btn">ثبت نام</button>
    </form>

    <div class="auth-link">
        قبلاً ثبت نام کرده‌اید؟ <a href="{{ route('login') }}">ورود</a>
    </div>
</div>
@endsection