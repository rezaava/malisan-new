@extends('fakeBlade.layout.master')
@section('title','ورود')

@section('content')
<style>
    *{ margin:0; padding:0; box-sizing:border-box; font-family: IRANSans, sans-serif; }
    body{ direction:rtl; background:#f5f6fa; }
    .auth-page{
        min-height:100vh; display:flex; align-items:center; justify-content:center;
        background-image:url("{{asset('app-assets/images/backgrounds/login-bg.jpg')}}");
        background-size:cover; background-position:center; position:relative;
    }
    .auth-page::before{ content:""; position:absolute; inset:0; background:rgba(0,0,0,.45); }
    .auth-card{
        position:relative; width:100%; max-width:420px; padding:40px 30px; border-radius:16px;
        background:rgba(255,255,255,.92); backdrop-filter:blur(10px); box-shadow:0 15px 40px rgba(0,0,0,.25);
    }
    .auth-title{ text-align:center; margin-bottom:30px; font-size:22px; font-weight:700; color:#333; }
    .form-group{ position:relative; margin-bottom:22px; }
    .form-input{
        width:100%; padding:16px 12px 12px; border-radius:8px; border:1px solid #ccc;
        font-size:14px; outline:none; transition:.2s; background:#fff;
    }
    .form-input:focus{ border-color:#4e73df; box-shadow:0 0 0 2px rgba(78,115,223,.15); }
    .form-label{
        position:absolute; top:14px; right:12px; font-size:14px; color:#777;
        pointer-events:none; transition:.2s; background:#fff; padding:0 4px;
    }
    .form-input:focus + .form-label, .form-input:not(:placeholder-shown) + .form-label{
        top:-8px; font-size:12px; color:#4e73df;
    }
    .form-check{ display:flex; align-items:center; margin-bottom:12px; }
    .form-check input{ margin-left:8px; }
    .auth-link{ text-align:center; margin-bottom:18px; font-size:14px; }
    .auth-link a{ color:#4e73df; font-weight:600; text-decoration:none; }
    .auth-btn{
        width:100%; padding:14px; border:none; border-radius:8px; background:#4e73df;
        color:#fff; font-size:15px; font-weight:600; cursor:pointer; transition:.2s;
    }
    .auth-btn:hover{ background:#2e59d9; }
    .alert{ background:#ffe5e5; color:#c0392b; padding:10px; border-radius:6px; margin-bottom:15px; font-size:13px; text-align:center; }
</style>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-title">ورود به حساب کاربری</div>
        <form method="post" action="{{ route('loginPost') }}">
            @csrf
            @if (session()->has('error'))
                <div class="alert">{{ session('error') }}</div>
            @endif

            <div class="form-group">
                <input type="text" name="national" class="form-input" placeholder=" " required value="{{ old('national') }}">
                <label class="form-label">کد ملی</label>
            </div>

            <div class="form-group">
                <input type="password" name="password" class="form-input" placeholder=" " required>
                <label class="form-label">رمز عبور</label>
            </div>

            <div class="form-check">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">مرا به خاطر بسپار</label>
            </div>

            <div class="auth-link">
                حساب کاربری ندارید ؟ <a href="{{ url('/reg') }}">ساخت حساب</a>
            </div>

            <button type="submit" class="auth-btn">ورود</button>
        </form>
    </div>
</div>
@endsection
