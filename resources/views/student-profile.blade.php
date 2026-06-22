@extends('layout.master')

@section('title')
ملیسان | پروفایل کاربر
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-profile.css')}}">
@endsection

@section('mohtava')
<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <img src="/files/useravatar.png" alt="پروفایل">
                <div class="avatar-upload">
                    <label for="avatar-upload" class="upload-btn">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatar-upload" accept="image/*">
                </div>
            </div>
            <div class="profile-name">
                <h4>نازنین احمدی</h4>
                <span class="profile-role">دانشجو</span>
            </div>
        </div>

        <form class="profile-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="username">نام کاربری</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="username" value="نازنین" placeholder="نام خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="family">نام خانوادگی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="family" value="احمدی" placeholder="نام خانوادگی خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="national">کد ملی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" id="national" value="0550328939" placeholder="کد ملی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="shenasname">شماره شناسنامه</label>
                    <div class="input-wrapper">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" id="shenasname" placeholder="شماره شناسنامه خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="personal">شماره دانشجویی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-graduation-cap input-icon"></i>
                        <input type="text" id="personal" placeholder="شماره دانشجویی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="gender">جنسیت</label>
                    <div class="input-wrapper">
                        <i class="fas fa-venus-mars input-icon"></i>
                        <select id="gender">
                            <option value="">جنسیت را مشخص کنید</option>
                            <option value="0" selected>زن</option>
                            <option value="1">مرد</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="birthdate">تاریخ تولد</label>
                    <div class="input-wrapper">
                        <i class="fas fa-calendar-alt input-icon"></i>
                        <input type="text" id="birthdate" placeholder="تاریخ تولد خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="city">شهر</label>
                    <div class="input-wrapper">
                        <i class="fas fa-city input-icon"></i>
                        <input type="text" id="city" placeholder="شهر سکونت خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="address">آدرس پستی</label>
                <div class="input-wrapper">
                    <i class="fas fa-map-marker-alt input-icon"></i>
                    <input type="text" id="address" placeholder="آدرس پستی خود را وارد کنید">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="postal">کد پستی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-mailbox input-icon"></i>
                        <input type="text" id="postal" placeholder="کد پستی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="tel">تلفن ثابت</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="text" id="tel" placeholder="تلفن ثابت خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="mobile">تلفن همراه</label>
                    <div class="input-wrapper">
                        <i class="fas fa-mobile-alt input-icon"></i>
                        <input type="text" id="mobile" value="09139843314" placeholder="تلفن همراه خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="tel_work">تلفن کار</label>
                    <div class="input-wrapper">
                        <i class="fas fa-building input-icon"></i>
                        <input type="text" id="tel_work" placeholder="تلفن محل کار خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">پست الکترونیکی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" placeholder="ایمیل خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="uni_email">ایمیل دانشگاه</label>
                    <div class="input-wrapper">
                        <i class="fas fa-university input-icon"></i>
                        <input type="email" id="uni_email" placeholder="ایمیل دانشگاه خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="web">سایت</label>
                    <div class="input-wrapper">
                        <i class="fas fa-globe input-icon"></i>
                        <input type="text" id="web" placeholder="آدرس وب‌سایت خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="scholar">صفحه اسکولار</label>
                    <div class="input-wrapper">
                        <i class="fas fa-graduation-cap input-icon"></i>
                        <input type="text" id="scholar" placeholder="آدرس صفحه اسکولار خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="social">شبکه اجتماعی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-share-alt input-icon"></i>
                        <input type="text" id="social" placeholder="آدرس شبکه اجتماعی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="degree">مقطع تحصیلی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-certificate input-icon"></i>
                        <input type="text" id="degree" placeholder="مقطع تحصیلی خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="field">رشته</label>
                    <div class="input-wrapper">
                        <i class="fas fa-book input-icon"></i>
                        <input type="text" id="field" placeholder="رشته تحصیلی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="trend">گرایش</label>
                    <div class="input-wrapper">
                        <i class="fas fa-arrow-right input-icon"></i>
                        <input type="text" id="trend" placeholder="گرایش خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="trend_en">گرایش به انگلیسی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-language input-icon"></i>
                        <input type="text" id="trend_en" placeholder="گرایش به انگلیسی را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="research">حوزه پژوهشی</label>
                    <div class="input-wrapper">
                        <i class="fas fa-flask input-icon"></i>
                        <input type="text" id="research" placeholder="حوزه پژوهشی خود را وارد کنید">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="shaba">شبا</label>
                    <div class="input-wrapper">
                        <i class="fas fa-credit-card input-icon"></i>
                        <input type="text" id="shaba" placeholder="شماره شبای بانکی خود را وارد کنید">
                    </div>
                </div>
                <div class="form-group">
                    <label for="turn">دوره</label>
                    <div class="input-wrapper">
                        <i class="fas fa-clock input-icon"></i>
                        <input type="text" id="turn" placeholder="روزانه، شبانه، مجازی و ...">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="password">تغییر رمز عبور</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" placeholder="رمز عبور جدید را وارد کنید">
                </div>
            </div>

            <div class="form-group">
                <label for="image">تغییر عکس</label>
                <div class="file-upload-wrapper">
                    <input type="file" id="image" class="file-upload-input">
                    <label for="image" class="file-upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>انتخاب عکس</span>
                    </label>
                    <span class="file-name" id="file-name">هیچ فایلی انتخاب نشده است</span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">
                    <i class="fas fa-save"></i>
                    ذخیره
                </button>
                <button type="button" class="cancel-btn">لغو</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'هیچ فایلی انتخاب نشده است';
        document.getElementById('file-name').textContent = fileName;
    });
</script>
@endsection