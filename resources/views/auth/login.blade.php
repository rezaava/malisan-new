<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dfghjkl;</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h2>ورود</h2>
            <p>به سامانه آموزشی ملیسان خوش آمدید</p>
        </div>

        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('loginPost') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="national">کد ملی</label>
                <div class="input-wrapper">
                    <i class="fas fa-id-card"></i>
                    <input type="text" id="national" name="national" placeholder="کد ملی خود را وارد کنید" value="{{ old('national') }}">
                </div>
                @error('national')
                    <span style="color: red; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">رمز عبور</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="رمز عبور خود را وارد کنید">
                </div>
                @error('password')
                    <span style="color: red; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <input type="checkbox" name="remember" id="remember" value="1">
                <label for="remember" style="margin: 0; cursor: pointer;">مرا به خاطر بسپار</label>
            </div>

            <button type="submit" class="auth-btn">ورود</button>
        </form>

        <div class="auth-link">
            ثبت نام نکرده‌اید؟ <a href="{{route('register')}}">ثبت نام</a>
        </div>
    </div>
</body>
</html>