@extends('fakeBlade.layout.master')

@section('title','ایجاد حساب کاربری')

@section('content')

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:IRANSans,sans-serif;
}

body{
    direction:rtl;
    background:#f5f6fa;
}

/* ================= PAGE ================= */

.auth-page{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px 15px;
    background-image:url("{{ asset('app-assets/images/backgrounds/login-bg.jpg') }}");
    background-size:cover;
    background-position:center;
    position:relative;
}

.auth-page::before{
    content:"";
    position:absolute;
    inset:0;
    background:rgba(0,0,0,.5);
}

/* ================= CARD ================= */

.auth-card{
    position:relative;
    width:100%;
    max-width:430px;
    padding:35px 30px;
    border-radius:18px;
    background:rgba(255,255,255,.92);
    backdrop-filter:blur(10px);
    box-shadow:0 20px 45px rgba(0,0,0,.25);
}

/* ================= TITLE ================= */

.auth-title{
    text-align:center;
    margin-bottom:8px;
    font-size:24px;
    font-weight:700;
    color:#222;
}

.auth-subtitle{
    text-align:center;
    color:#666;
    margin-bottom:28px;
    font-size:14px;
}

/* ================= FORM ================= */

.form-group{
    position:relative;
    margin-bottom:22px;
}

.form-input{
    width:100%;
    height:54px;
    border:1px solid #dcdfe6;
    border-radius:10px;
    padding:18px 14px 8px;
    outline:none;
    background:#fff;
    transition:.2s;
    font-size:14px;
}

.form-input:focus{
    border-color:#7367f0;
    box-shadow:0 0 0 4px rgba(115,103,240,.12);
}

/* floating label */

.form-label{
    position:absolute;
    top:16px;
    right:14px;
    background:#fff;
    padding:0 4px;
    color:#777;
    font-size:14px;
    transition:.2s;
    pointer-events:none;
}

.form-input:focus + .form-label,
.form-input:not(:placeholder-shown) + .form-label{
    top:-8px;
    font-size:12px;
    color:#7367f0;
}

/* ================= BUTTON ================= */

.auth-btn{
    width:100%;
    height:52px;
    border:none;
    border-radius:10px;
    background:linear-gradient(45deg,#7367f0,#9c27b0);
    color:#fff;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    transition:.2s;
}

.auth-btn:hover{
    transform:translateY(-1px);
    opacity:.95;
}

/* ================= LINK ================= */

.auth-link{
    margin-top:22px;
    text-align:center;
    font-size:14px;
}

.auth-link a{
    color:#7367f0;
    text-decoration:none;
    font-weight:700;
}

.auth-link a:hover{
    text-decoration:underline;
}

/* ================= ERRORS ================= */

.error-text{
    margin-top:6px;
    color:#e53935;
    font-size:12px;
}

</style>


<div class="auth-page">

    <div class="auth-card">

        <div class="auth-title">
            ثبت نام
        </div>

        <div class="auth-subtitle">
            اکنون به ما بپیوندید!
        </div>
        
        @if(session('danger'))
            <div class="error-text" style="margin-bottom:15px;text-align:center;">
                {{ session('danger') }}
            </div>
        @endif



        <form method="post" action="{{ route('registerPost') }}" enctype="multipart/form-data">

            @csrf


            {{-- NAME --}}
            <div class="form-group">

<input
    id="name"
    name="name"
    type="text"
    class="form-input"
    placeholder=" "
    required
    value="{{ old('name') }}"
>


                <label for="name" class="form-label">
                    نام
                </label>

                @if($errors->has('name'))
                    <div class="error-text">
                        {{ $errors->first('name') }}
                    </div>
                @endif

            </div>


            {{-- FAMILY --}}
            <div class="form-group">

<input
    id="family"
    name="family"
    type="text"
    class="form-input"
    placeholder=" "
    required
    value="{{ old('family') }}"
>

                <label for="family" class="form-label">
                    نام خانوادگی
                </label>

                @if($errors->has('family'))
                    <div class="error-text">
                        {{ $errors->first('family') }}
                    </div>
                @endif

            </div>


            {{-- NATIONAL --}}
            <div class="form-group">

<input
    id="national"
    name="national"
    type="text"
    pattern="[0-9]{10}"
    class="form-input"
    placeholder=" "
    required
    value="{{ old('national') }}"
>


                <label for="national" class="form-label">
                    کد ملی
                </label>

                @if($errors->has('national'))
                    <div class="error-text">
                        {{ $errors->first('national') }}
                    </div>
                @endif

            </div>


            {{-- MOBILE --}}
            <div class="form-group">

<input
    id="mobile"
    name="mobile"
    type="text"
    pattern="[0-9]{11}"
    class="form-input"
    placeholder=" "
    required
    value="{{ old('mobile') }}"
>

                <label for="mobile" class="form-label">
                    موبایل
                </label>

                @if($errors->has('mobile'))
                    <div class="error-text">
                        {{ $errors->first('mobile') }}
                    </div>
                @endif

            </div>


            {{-- PASSWORD --}}
            <div class="form-group">

<input
    id="password"
    name="password"
    type="password"
    class="form-input"
    placeholder=" "
    required
>

                <label for="password" class="form-label">
                    رمز عبور
                </label>

                @if($errors->has('password'))
                    <div class="error-text">
                        {{ $errors->first('password') }}
                    </div>
                @endif

            </div>


            {{-- TYPE --}}
            <div hidden>

                @if (\Route::current()->getName() == 'global')

                    <input
                        type="radio"
                        id="teacher"
                        name="type"
                        checked
                        value="1"
                    >

                @else

                    <input
                        type="radio"
                        id="student"
                        name="type"
                        checked
                        value="2"
                    >

                @endif

            </div>


            {{-- SUBMIT --}}
            <button type="submit" class="auth-btn">
                ثبت نام
            </button>


            {{-- LOGIN --}}
            <div class="auth-link">

                از قبل حساب دارید؟

                <a href="{{ route('login') }}">
                    وارد شوید
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
