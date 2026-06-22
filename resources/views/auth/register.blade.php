<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت نام</title>
    <link rel="stylesheet" href="{{asset('css/style-auth.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h2>ثبت نام</h2>
            <p>اطلاعات خود را برای ثبت نام وارد کنید</p>
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

        <form action="{{ route('registerPost') }}" method="post">
            @csrf
            <div class="form-row" style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label for="name">نام</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name" placeholder="نام خود را وارد کنید" value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <span style="color: red; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="family">نام خانوادگی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="family" name="family" placeholder="نام خانوادگی خود را وارد کنید" value="{{ old('family') }}">
                    </div>
                    @error('family')
                        <span style="color: red; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

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
                <label for="mobile">موبایل</label>
                <div class="input-wrapper">
                    <i class="fas fa-mobile-alt"></i>
                    <input type="tel" id="mobile" name="mobile" placeholder="شماره موبایل خود را وارد کنید" value="{{ old('mobile') }}">
                </div>
                @error('mobile')
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

            <div class="form-group">
                <label for="password_confirmation">تکرار رمز عبور</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="رمز عبور را مجدداً وارد کنید">
                </div>
            </div>

            <button type="submit" class="auth-btn">ثبت نام</button>
        </form>

        <div class="auth-link">
            قبلاً ثبت نام کرده‌اید؟ <a href="{{route('login')}}">ورود</a>
        </div>
    </div>
</body>
</html>