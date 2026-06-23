@extends('layout.master')

@section('title')
ملیسان | پروفایل کاربر
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-profile.css')}}">
<style>
    .profile-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .profile-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 30px;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 24px;
        padding-bottom: 24px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 30px;
    }

    .profile-avatar {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        background: #f0f4f9;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-upload {
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.6);
        padding: 6px;
        text-align: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .profile-avatar:hover .avatar-upload {
        opacity: 1;
    }

    .upload-btn {
        color: #fff;
        cursor: pointer;
        font-size: 18px;
        display: inline-block;
    }

    .upload-btn:hover {
        color: #1e6f9f;
    }

    #avatar-upload {
        display: none;
    }

    .profile-name h4 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
    }

    .profile-role {
        display: inline-block;
        padding: 4px 14px;
        background: #e8edf3;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        color: #6b7a8f;
        margin-top: 6px;
    }

    .profile-role.student {
        background: #e3f2fd;
        color: #1e6f9f;
    }

    .profile-role.teacher {
        background: #fce4ec;
        color: #c62828;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1a2332;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9aa8b9;
        font-size: 16px;
        pointer-events: none;
    }

    .input-wrapper input,
    .input-wrapper select {
        width: 100%;
        padding: 10px 45px 10px 16px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        direction: rtl;
        appearance: none;
        -webkit-appearance: none;
    }

    .input-wrapper select {
        cursor: pointer;
    }

    .input-wrapper input:focus,
    .input-wrapper select:focus {
        border-color: #1e6f9f;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.08);
        outline: none;
    }

    .input-wrapper input:disabled {
        background: #f0f4f9;
        color: #6b7a8f;
        cursor: not-allowed;
    }

    .file-upload-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        padding: 16px;
        border: 2px dashed #e8edf3;
        border-radius: 10px;
        background: #fafbfc;
        transition: all 0.3s ease;
    }

    .file-upload-wrapper:hover {
        border-color: #1e6f9f;
        background: #f0f7fe;
    }

    .file-upload-input {
        display: none;
    }

    .file-upload-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        background: #1e6f9f;
        color: #fff;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .file-upload-label:hover {
        background: #155a82;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 111, 159, 0.3);
    }

    .file-name {
        color: #6b7a8f;
        font-size: 14px;
    }

    .form-actions {
        margin-top: 30px;
        padding-top: 24px;
        border-top: 2px solid #f0f4f9;
        display: flex;
        gap: 12px;
        justify-content: flex-start;
    }

    .save-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 32px;
        background: linear-gradient(135deg, #1e6f9f 0%, #155a82 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
    }

    .save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(30, 111, 159, 0.4);
    }

    .cancel-btn {
        padding: 12px 28px;
        border: 2px solid #e8edf3;
        background: #fff;
        color: #6b7a8f;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cancel-btn:hover {
        background: #f0f4f9;
        border-color: #d0d7e2;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: #f0f4f9;
        color: #1a2332;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        margin-bottom: 20px;
    }

    .back-btn:hover {
        background: #e8edf3;
        transform: translateX(4px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .profile-card {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .save-btn,
        .cancel-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
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

        <form class="profile-form" action="{{ route('studentProfile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
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