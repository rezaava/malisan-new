@extends('layout.master')

@section('title')
ملیسان | پروفایل کاربر
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-profile.css')}}">
@endsection

@section('mohtava')
<div class="profile-container">

    <!-- دکمه بازگشت -->
    <a href="{{ url()->previous() }}" class="back-btn">
        <i class="fas fa-arrow-right"></i>
        بازگشت
    </a>

    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <img src="{{ $user->image ? asset($user->image) : asset('files/useravatar.png') }}" alt="پروفایل">
                <div class="avatar-upload">
                    <label for="avatar-upload" class="upload-btn">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatar-upload" accept="image/*">
                </div>
            </div>
            <div class="profile-name">
                <h4>{{ $user->name ?? '' }} {{ $user->family ?? '' }}</h4>
                <span class="profile-role {{ $user->hasRole('student') ? 'student' : ($user->hasRole('teacher') ? 'teacher' : '') }}">
                    @if($user->hasRole('student'))
                        دانشجو
                    @elseif($user->hasRole('teacher'))
                        مدرس
                    @else
                        کاربر
                    @endif
                </span>
            </div>
        </div>

        <form class="profile-form" action="{{ route('teacherProfile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="name">نام</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="نام خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="family">نام خانوادگی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="family" name="family" value="{{ old('family', $user->family ?? '') }}" placeholder="نام خانوادگی خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="national">کد ملی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" id="national" name="national" value="{{ old('national', $user->national ?? '') }}" placeholder="کد ملی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="shenasname">شماره شناسنامه</label>
                    <div class="input-wrapper">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" id="shenasname" name="shenasname" value="{{ old('shenasname', $user->shenasname ?? '') }}" placeholder="شماره شناسنامه خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="personal">شماره دانشجویی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-graduation-cap input-icon"></i>
                        <input type="text" id="personal" name="personal" value="{{ old('personal', $user->personal ?? '') }}" placeholder="شماره دانشجویی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="gender">جنسیت</label>
                    <div class="input-wrapper">
                        <i class="fas fa-venus-mars input-icon"></i>
                        <select id="gender" name="gender">
                            <option value="">جنسیت را مشخص کنید</option>
                            <option value="0" {{ old('gender', $user->gender ?? '') == '0' ? 'selected' : '' }}>زن</option>
                            <option value="1" {{ old('gender', $user->gender ?? '') == '1' ? 'selected' : '' }}>مرد</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="birthdate">تاریخ تولد</label>
                    <div class="input-wrapper">
                        <i class="fas fa-calendar-alt input-icon"></i>
                        <input type="text" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->birthdate ?? '') }}" placeholder="تاریخ تولد خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="city">شهر</label>
                    <div class="input-wrapper">
                        <i class="fas fa-city input-icon"></i>
                        <input type="text" id="city" name="city" value="{{ old('city', $user->city ?? '') }}" placeholder="شهر سکونت خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="address">آدرس پستی</label>
                <div class="input-wrapper">
                    <i class="fas fa-map-marker-alt input-icon"></i>
                    <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}" placeholder="آدرس پستی خود را وارد کنید">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="postal">کد پستی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-mailbox input-icon"></i>
                        <input type="text" id="postal" name="postal" value="{{ old('postal', $user->postal ?? '') }}" placeholder="کد پستی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="tel">تلفن ثابت</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="text" id="tel" name="tel" value="{{ old('tel', $user->tel ?? '') }}" placeholder="تلفن ثابت خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="mobile">تلفن همراه</label>
                    <div class="input-wrapper">
                        <i class="fas fa-mobile-alt input-icon"></i>
                        <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $user->mobile ?? '') }}" placeholder="تلفن همراه خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="tel_work">تلفن کار</label>
                    <div class="input-wrapper">
                        <i class="fas fa-building input-icon"></i>
                        <input type="text" id="tel_work" name="tel_work" value="{{ old('tel_work', $user->tel_work ?? '') }}" placeholder="تلفن محل کار خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">پست الکترونیکی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="ایمیل خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="uni_email">ایمیل دانشگاه</label>
                    <div class="input-wrapper">
                        <i class="fas fa-university input-icon"></i>
                        <input type="email" id="uni_email" name="uni_email" value="{{ old('uni_email', $user->uni_email ?? '') }}" placeholder="ایمیل دانشگاه خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="web">سایت</label>
                    <div class="input-wrapper">
                        <i class="fas fa-globe input-icon"></i>
                        <input type="text" id="web" name="web" value="{{ old('web', $user->web ?? '') }}" placeholder="آدرس وب‌سایت خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="scholar">صفحه اسکولار</label>
                    <div class="input-wrapper">
                        <i class="fas fa-graduation-cap input-icon"></i>
                        <input type="text" id="scholar" name="scholar" value="{{ old('scholar', $user->scholar ?? '') }}" placeholder="آدرس صفحه اسکولار خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="social">شبکه اجتماعی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-share-alt input-icon"></i>
                        <input type="text" id="social" name="social" value="{{ old('social', $user->social ?? '') }}" placeholder="آدرس شبکه اجتماعی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="degree">مقطع تحصیلی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-certificate input-icon"></i>
                        <input type="text" id="degree" name="degree" value="{{ old('degree', $user->degree ?? '') }}" placeholder="مقطع تحصیلی خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="field">رشته</label>
                    <div class="input-wrapper">
                        <i class="fas fa-book input-icon"></i>
                        <input type="text" id="field" name="field" value="{{ old('field', $user->field ?? '') }}" placeholder="رشته تحصیلی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="trend">گرایش</label>
                    <div class="input-wrapper">
                        <i class="fas fa-arrow-right input-icon"></i>
                        <input type="text" id="trend" name="trend" value="{{ old('trend', $user->trend ?? '') }}" placeholder="گرایش خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="trend_en">گرایش به انگلیسی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-language input-icon"></i>
                        <input type="text" id="trend_en" name="trend_en" value="{{ old('trend_en', $user->trend_en ?? '') }}" placeholder="گرایش به انگلیسی را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="research">حوزه پژوهشی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-flask input-icon"></i>
                        <input type="text" id="research" name="research" value="{{ old('research', $user->research ?? '') }}" placeholder="حوزه پژوهشی خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="shaba">شبا</label>
                    <div class="input-wrapper">
                        <i class="fas fa-credit-card input-icon"></i>
                        <input type="text" id="shaba" name="shaba" value="{{ old('shaba', $user->shaba ?? '') }}" placeholder="شماره شبای بانکی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="turn">دوره</label>
                    <div class="input-wrapper">
                        <i class="fas fa-clock input-icon"></i>
                        <input type="text" id="turn" name="turn" value="{{ old('turn', $user->turn ?? '') }}" placeholder="روزانه، شبانه، مجازی و ...">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="password">تغییر رمز عبور</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="رمز عبور جدید را وارد کنید">
                </div>
                <small style="color: #6b7a8f; font-size: 12px;">در صورت تمایل به تغییر رمز عبور، این قسمت را پر کنید</small>
            </div>

            <div class="form-group">
                <label for="image">تغییر عکس</label>
                <div class="file-upload-wrapper">
                    <input type="file" id="image" name="image" class="file-upload-input" accept="image/*">
                    <label for="image" class="file-upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>انتخاب عکس</span>
                    </label>
                    <span class="file-name" id="file-name">هیچ فایلی انتخاب نشده است</span>
                </div>
                @if($user->image)
                    <small style="color: #6b7a8f; font-size: 12px; display: block; margin-top: 6px;">
                        عکس فعلی: <a href="{{ asset($user->image) }}" target="_blank">مشاهده</a>
                    </small>
                @endif
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">
                    <i class="fas fa-save"></i>
                    ذخیره
                </button>
                <button type="button" class="cancel-btn" onclick="window.history.back()">لغو</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // نمایش نام فایل انتخاب شده
    document.getElementById('image')?.addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'هیچ فایلی انتخاب نشده است';
        document.getElementById('file-name').textContent = fileName;
    });

    // نمایش نام فایل آواتار
    document.getElementById('avatar-upload')?.addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'هیچ فایلی انتخاب نشده است';
        document.getElementById('file-name').textContent = fileName;
        
        // پیش‌نمایش عکس
        var reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.profile-avatar img').src = e.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    });
</script>
@endsection