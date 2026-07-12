@extends('layout.master')

@section('title')
ملیسان | تنظیمات درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-setting.css')}}">
<style>
    .settings-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .settings-form {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 30px;
    }

    .accordion-wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .accordion-item {
        border: 1px solid #e8edf3;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .accordion-item.active {
        border-color: #1e6f9f;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.08);
    }

    .accordion-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafbfc;
        user-select: none;
    }

    .accordion-header:hover {
        background: #f0f7fe;
    }

    .accordion-header i:first-child {
        color: #1e6f9f;
        font-size: 18px;
    }

    .accordion-header span {
        flex: 1;
        font-weight: 600;
        color: #1a2332;
        font-size: 15px;
    }

    .accordion-icon {
        transition: transform 0.3s ease;
        color: #6b7a8f;
    }

    .accordion-body {
        display: none;
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: all 0.4s ease;
        background: #fff;
    }

    .accordion-body.active {
        display: block;
        padding: 20px;
        max-height: 2000px;
    }

    .settings-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .settings-table thead th {
        text-align: right;
        padding: 10px 12px;
        background: #f8fafc;
        font-weight: 700;
        color: #1a2332;
        border-bottom: 2px solid #e8edf3;
    }

    .settings-table tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid #f0f4f9;
        vertical-align: middle;
    }

    .settings-table tbody tr:last-child td {
        border-bottom: none;
    }

    .form-input {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e8edf3;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        direction: rtl;
    }

    .form-input:focus {
        border-color: #1e6f9f;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.08);
        outline: none;
    }

    .form-textarea {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e8edf3;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        direction: rtl;
        resize: vertical;
        min-height: 60px;
        font-family: inherit;
    }

    .form-textarea:focus {
        border-color: #1e6f9f;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.08);
        outline: none;
    }

    .form-select {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e8edf3;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        direction: rtl;
        appearance: none;
        -webkit-appearance: none;
    }

    .form-select:focus {
        border-color: #1e6f9f;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.08);
        outline: none;
    }

    .input-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .input-group-label {
        font-size: 12px;
        color: #6b7a8f;
        white-space: nowrap;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
        flex-shrink: 0;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #d0d7e2;
        transition: 0.3s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    .toggle-switch input:checked + .toggle-slider {
        background: #1e6f9f;
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }

    .toggle-label {
        font-size: 13px;
        font-weight: 600;
        color: #1a2332;
        margin-right: 8px;
    }

    .total-score {
        margin-top: 16px;
        padding: 12px 16px;
        background: #f0f7fe;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        color: #1e6f9f;
        text-align: center;
    }

    .score-info {
        margin-top: 12px;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 8px;
        font-size: 13px;
        color: #6b7a8f;
        line-height: 1.8;
    }

    .score-info p {
        margin: 0;
    }

    .form-actions {
        margin-top: 30px;
        padding-top: 24px;
        border-top: 2px solid #f0f4f9;
        display: flex;
        justify-content: center;
    }

    .save-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 48px;
        background: linear-gradient(135deg, #1e6f9f 0%, #155a82 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
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

    .save-btn i {
        font-size: 18px;
    }

    .text-muted {
        color: #6c757d;
    }

    /* ===== استایل نمایش بخش‌ها به دانشجو ===== */
    .visibility-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }

    .vis-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s ease;
        border: 1px solid #eef2f7;
    }

    .vis-item:hover {
        background: #f0f7fe;
        border-color: #d4e4f0;
    }

    .switch-label {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        color: #1a2332;
        width: 100%;
        user-select: none;
    }

    .vis-icon {
        width: 32px;
        height: 32px;
        background: #e3f2fd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1e6f9f;
        font-size: 14px;
        flex-shrink: 0;
    }

    .vis-text {
        flex: 1;
        font-size: 13px;
    }

    .switch {
        position: relative;
        width: 42px;
        height: 24px;
        flex-shrink: 0;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #d1d9e6;
        transition: 0.3s ease;
        border-radius: 34px;
    }

    .slider:before {
        content: "";
        position: absolute;
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background: #fff;
        transition: 0.3s ease;
        border-radius: 50%;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
    }

    .switch input:checked + .slider {
        background: linear-gradient(135deg, #1e6f9f, #155a82);
    }

    .switch input:checked + .slider:before {
        transform: translateX(18px);
    }

    .switch.loading .slider {
        animation: pulse 0.8s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .vis-note {
        margin-top: 16px;
        padding: 10px 16px;
        background: #f0f7fe;
        border-radius: 8px;
        font-size: 13px;
        color: #1e6f9f;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .vis-note i {
        font-size: 16px;
    }

    /* ===== Toast ===== */
    .custom-toast {
        position: fixed;
        bottom: 30px;
        left: 30px;
        padding: 14px 24px;
        border-radius: 12px;
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 10000;
        max-width: 400px;
        direction: rtl;
    }

    .custom-toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    .custom-toast.success {
        background: linear-gradient(135deg, #27ae60, #1e8449);
    }

    .custom-toast.error {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
    }

    .custom-toast i {
        font-size: 20px;
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        .settings-form {
            padding: 16px;
        }
        .settings-table {
            font-size: 13px;
        }
        .settings-table thead th,
        .settings-table tbody td {
            padding: 6px 8px;
        }
        .input-group {
            flex-wrap: wrap;
        }
        .save-btn {
            width: 100%;
            justify-content: center;
        }
        .visibility-grid {
            grid-template-columns: 1fr;
        }
        .custom-toast {
            left: 16px;
            right: 16px;
            bottom: 16px;
            max-width: none;
            padding: 12px 16px;
            font-size: 13px;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="settings-container">
    <div class="text-center mb-3">
        <h4>تنظیمات درس {{ $course->name }}</h4>
    </div>
    <form action="/teacher/courses/edit-setting" method="post" enctype="multipart/form-data">
            @csrf
        <input name="course_id" value="{{ $course->id }}" hidden>

        <div class="accordion-wrapper">
            <!-- ==========================================
                 بارم بندی
                 ========================================== -->
            <div class="accordion-item active">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <i class="fas fa-balance-scale"></i>
                    <span>بارم بندی (مجموع : ۱۰۰)</span>
                    <i class="fas fa-chevron-down accordion-icon"></i>
                </div>
                <div class="accordion-body active" style="display: block; max-height: 2000px; padding: 20px;">
                    <table class="settings-table">
                        <thead>
                            <tr>
                                <th style="width: 30%;">موضوع</th>
                                <th style="width: 25%;">امتیاز</th>
                                <th style="width: 45%;">توضیح برای دانشجو</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>تعداد جلسات</td>
                                <td>
                                    <input type="number" name="jalasat" value="{{ $setting->jalasat ?? 16 }}" class="form-input" min="1">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>چند نمره برای ارزشیابی مستمر در نظر می گیرید؟</td>
                                <td>
                                    <input type="number" name="mostamar_nomre" id="mostamar_nomre" value="{{ $setting->mostamar_nomre ?? 12 }}" class="form-input" onkeyup="jam()" min="0" max="100">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>تکلیف یا سمینار</td>
                                <td>
                                    <input type="number" name="taklif_seminar_nomre" id="taklif_nomre" value="{{ $setting->taklif_seminar_nomre ?? 0 }}" class="form-input" onkeyup="jam()" min="0" max="100">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-label">برآورد تعداد تکلیف یا سمینار</span>
                                        <input type="number" name="max_taklif" value="{{ $setting->max_taklif ?? 8 }}" class="form-input" min="1">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>آزمون</td>
                                <td>
                                    <input type="number" name="azmon_nomre" id="azmon_nomre" value="{{ $setting->azmon_nomre ?? 0 }}" class="form-input" onkeyup="jam()" min="0" max="100">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>حضور و غیاب</td>
                                <td>
                                    <input type="number" name="hozor_ghayab_nomre" id="hozor_ghayab_nomre" value="{{ $setting->hozor_ghayab_nomre ?? 0 }}" class="form-input" onkeyup="jam()" min="0" max="100">
                                </td>
                                <td>
                                    <textarea name="hozor_ghayab_desc" class="form-textarea" rows="2">{{ $setting->hozor_ghayab_desc ?? 'نمره حضور و غیاب' }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>میان ترم</td>
                                <td>
                                    <input type="number" name="miyan_term_nomre" id="miyan_term_nomre" value="{{ $setting->miyan_term_nomre ?? 0 }}" class="form-input" onkeyup="jam()" min="0" max="100">
                                </td>
                                <td>
                                    <textarea name="miyan_term_desc" class="form-textarea" rows="2">{{ $setting->miyan_term_desc ?? 'نمره میان ترم' }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>کار عملی (بازدید|آزمایشگاه|کارگاه)</td>
                                <td>
                                    <input type="number" name="kar_amali_nomre" id="kar_amali_nomre" value="{{ $setting->kar_amali_nomre ?? 0 }}" class="form-input" onkeyup="jam()" min="0" max="100">
                                </td>
                                <td>
                                    <textarea name="kar_amali_desc" class="form-textarea" rows="2">{{ $setting->kar_amali_desc ?? 'نمره کار عملی' }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>پایان ترم</td>
                                <td>
                                    <input type="number" name="payan_term_nomre" id="payan_term_nomre" value="{{ $setting->payan_term_nomre ?? 6 }}" class="form-input" onkeyup="jam()" min="0" max="100">
                                </td>
                                <td>
                                    <textarea name="payan_term_desc" class="form-textarea" rows="2">{{ $setting->payan_term_desc ?? 'نمره پایان ترم' }}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="total-score">
                        مجموع نمرات : <span id="majmo">
                            {{ 
                                ($setting->mostamar_nomre ?? 12) + 
                                ($setting->taklif_seminar_nomre ?? 0) + 
                                ($setting->azmon_nomre ?? 0) +
                                ($setting->hozor_ghayab_nomre ?? 0) +
                                ($setting->miyan_term_nomre ?? 0) +
                                ($setting->kar_amali_nomre ?? 0) +
                                ($setting->payan_term_nomre ?? 6)
                            }}
                        </span>
                    </div>
                    <div class="score-info">
                        <p><i class="fas fa-info-circle"></i> پیشرفت درسی: 70 درصد نمره ارزشیابی مستمر (محاسبه توسط سیستم)</p>
                        <p><i class="fas fa-info-circle"></i> تلاش و فعالیت: 30 درصد نمره ارزشیابی مستمر (محاسبه توسط سیستم)</p>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 فعالیت ها
                 ========================================== -->
            <div class="accordion-item" id="activity-settings">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <i class="fas fa-tasks"></i>
                    <span>فعالیت ها</span>
                    <i class="fas fa-chevron-down accordion-icon"></i>
                </div>
                <div class="accordion-body">
                    <table class="settings-table">
                        <thead>
                            <tr>
                                <th style="width: 55%;">موضوع</th>
                                <th style="width: 45%;">وضعیت</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>دانشجو فقط برای آخرین جلسه درس مجاز به ثبت سوال است</td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="soal_last" {{ $setting->soal_last == 1 ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>دانشجو فقط برای آخرین جلسه درس مجاز به ارسال گزارش است</td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="gozaresh_last" {{ $setting->gozaresh_last == 1 ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>دانشجو فقط برای آخرین جلسه درس مجاز به ارسال تکلیف است</td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="taklif_last" {{ $setting->taklif_last == 1 ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>حداکثر تعداد سوالاتی که توسط دانشجو در هر جلسه طرح می شود</td>
                                <td>
                                    <input type="number" name="max_soal" value="{{ $setting->max_soal ?? 3 }}" class="form-input" min="1">
                                </td>
                            </tr>
                            <tr>
                                <td>هدایت دانشجو در بخش طراحی سوال</td>
                                <td>
                                    <textarea name="tarahi_soal_desc" class="form-textarea" rows="3">{{ $setting->tarahi_soal_desc ?? 'یک سؤال خلاقانه طراحی کنید که به یادگیری دوستانتان کمک کند و به نام خودتان منتشر شود. قبل از ارسال، حتماً سؤالاتی که دیگران طرح کرده اند را مرور کنید تا از تکراری نبودن سوال خود مطمئن شوید.' }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>هدایت دانشجو در بخش ارسال گزارش</td>
                                <td>
                                    <textarea name="ersal_gozaresh_desc" class="form-textarea" rows="3">{{ $setting->ersal_gozaresh_desc ?? 'موضوع اصلی این جلسه چه بود و چه هدفی داشت؟ لطفاً یک نکتهٔ آموزنده از مطالب ارائه شده را با بیانی دیگر (به زبان خودتان) بازنویسی کنید.' }}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ==========================================
                 خودآزمایی
                 ========================================== -->
            <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <i class="fas fa-brain"></i>
                    <span>خودآزمایی</span>
                    <i class="fas fa-chevron-down accordion-icon"></i>
                </div>
                <div class="accordion-body">
                    <table class="settings-table">
                        <thead>
                            <tr>
                                <th style="width: 55%;">موضوع</th>
                                <th style="width: 45%;">وضعیت</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>حداقل دفعات شرکت در خود آزمایی در طول هفته</td>
                                <td>
                                    <input type="number" name="min_w_khod" value="{{ $setting->min_w_khod ?? 14 }}" class="form-input" min="1">
                                </td>
                            </tr>
                            <tr>
                                <td>تعداد سوالات در هر خودآزمایی</td>
                                <td>
                                    <input type="number" name="q_num" value="{{ $setting->q_num ?? 10 }}" class="form-input" min="1">
                                </td>
                            </tr>
                            <tr>
                                <td>سطح سوالات در هر خودآزمایی</td>
                                <td>
                                    <select name="sath_khod" class="form-select">
                                        <option value="1" {{ $setting->sath_khod == 1 ? 'selected' : '' }}>عالی</option>
                                        <option value="2" {{ $setting->sath_khod == 2 ? 'selected' : '' }}>عالی و خوب</option>
                                        <option value="3" {{ $setting->sath_khod == 3 ? 'selected' : '' }}>خوب</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>دانشجو بلافاصله بعد از آزمون نمره خود را ببیند</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <label class="toggle-switch">
                                            <input type="hidden" name="natije" value="0">
                                            <input type="checkbox" name="natije" value="1" {{ $setting->natije == 1 ? 'checked' : '' }} class="toggle-text" data-target="natije-text">
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span id="natije-text" class="toggle-label">{{ $setting->natije == 1 ? 'بله' : 'خیر' }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>پاسخ سوالات خودآزمایی به دانشجو نشان داده شود</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <label class="toggle-switch">
                                            <input type="hidden" name="show_quiz" value="0">
                                            <input type="checkbox" name="show_quiz" value="1" {{ $setting->show_quiz == 1 ? 'checked' : '' }} class="toggle-text" data-target="quiz-text">
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span id="quiz-text" class="toggle-label">{{ $setting->show_quiz == 1 ? 'بله' : 'خیر' }}</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ==========================================
                 نمایش بخش‌ها به دانشجو
                 ========================================== -->
            <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <i class="fas fa-eye"></i>
                    <span>نمایش بخش‌ها به دانشجو</span>
                    <i class="fas fa-chevron-down accordion-icon"></i>
                </div>
                <div class="accordion-body">
                    <div class="visibility-grid">
                        <div class="vis-item">
                            <label class="switch-label">
                                <span class="vis-icon"><i class="fas fa-list-ul"></i></span>
                                <span class="vis-text">نمایش جلسات درس</span>
                                <div class="switch">
                                    <input type="checkbox" class="vis-checkbox" 
                                           data-field="active" 
                                           id="visActive"
                                           {{ $course->active ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </div>
                            </label>
                        </div>
                        <div class="vis-item">
                            <label class="switch-label">
                                <span class="vis-icon"><i class="fas fa-gavel"></i></span>
                                <span class="vis-text">امکان داوری</span>
                                <div class="switch">
                                    <input type="checkbox" class="vis-checkbox" 
                                           data-field="davari" 
                                           id="visDavari"
                                           {{ $course->davari ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </div>
                            </label>
                        </div>
                        <div class="vis-item">
                            <label class="switch-label">
                                <span class="vis-icon"><i class="fas fa-pencil-alt"></i></span>
                                <span class="vis-text">شرکت در خودآزمایی</span>
                                <div class="switch">
                                    <input type="checkbox" class="vis-checkbox" 
                                           data-field="quiz" 
                                           id="visQuiz"
                                           {{ $course->quiz ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </div>
                            </label>
                        </div>
                        <div class="vis-item">
                            <label class="switch-label">
                                <span class="vis-icon"><i class="fas fa-eye"></i></span>
                                <span class="vis-text">مشاهده فعالیت‌ها</span>
                                <div class="switch">
                                    <input type="checkbox" class="vis-checkbox" 
                                           data-field="faaliat" 
                                           id="visFaaliat"
                                           {{ $course->faaliat ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </div>
                            </label>
                        </div>
                        <div class="vis-item">
                            <label class="switch-label">
                                <span class="vis-icon"><i class="fas fa-chart-line"></i></span>
                                <span class="vis-text">مشاهده پیشرفت درسی</span>
                                <div class="switch">
                                    <input type="checkbox" class="vis-checkbox" 
                                           data-field="pishraft" 
                                           id="visPishraft"
                                           {{ $course->pishraft ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="vis-note">
                        <i class="fas fa-info-circle"></i>
                        با فعال کردن هر گزینه، بخش مربوطه برای دانشجو قابل مشاهده خواهد بود
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="save-btn">
                <i class="fas fa-save"></i>
                ذخیره اطلاعات
            </button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    function jam() {
        var azmon = parseFloat(document.getElementById('azmon_nomre').value) || 0;
        var taklif = parseFloat(document.getElementById('taklif_nomre').value) || 0;
        var mostamar = parseFloat(document.getElementById('mostamar_nomre').value) || 0;
        var hozor = parseFloat(document.getElementById('hozor_ghayab_nomre').value) || 0;
        var miyan = parseFloat(document.getElementById('miyan_term_nomre').value) || 0;
        var karAmali = parseFloat(document.getElementById('kar_amali_nomre').value) || 0;
        var payan = parseFloat(document.getElementById('payan_term_nomre').value) || 0;
        
        var total = azmon + taklif + mostamar + hozor + miyan + karAmali + payan;
        document.getElementById('majmo').textContent = total;
    }
    jam();

    function toggleAccordion(header) {
        var body = header.nextElementSibling;
        var icon = header.querySelector('.accordion-icon');
        var parent = header.parentElement;

        if (body.classList.contains('active')) {
            body.classList.remove('active');
            icon.style.transform = 'rotate(0deg)';
            parent.classList.remove('active');
            body.style.maxHeight = '0';
            body.style.paddingTop = '0';
            body.style.paddingBottom = '0';
            setTimeout(function() {
                body.style.display = 'none';
            }, 400);
        } else {
            body.style.display = 'block';
            body.classList.add('active');
            icon.style.transform = 'rotate(180deg)';
            parent.classList.add('active');
            
            var height = body.scrollHeight;
            body.style.maxHeight = '0';
            body.style.paddingTop = '0';
            body.style.paddingBottom = '0';
            
            setTimeout(function() {
                body.style.maxHeight = height + 'px';
                body.style.paddingTop = '20px';
                body.style.paddingBottom = '20px';
            }, 10);
        }
    }

    document.querySelectorAll('.toggle-text').forEach(function(cb) {
        cb.addEventListener('change', function() {
            var spanId = this.getAttribute('data-target');
            var span = document.getElementById(spanId);
            if (this.checked) {
                span.textContent = 'بله';
                this.value = "1";
            } else {
                span.textContent = 'خیر';
                this.value = "0";
            }
        });
    });

    // باز کردن اولین آیتم به صورت پیش‌فرض
    var firstBody = document.querySelector('.accordion-item.active .accordion-body');
    if (firstBody) {
        firstBody.style.display = 'block';
        firstBody.style.maxHeight = firstBody.scrollHeight + 'px';
        firstBody.style.paddingTop = '20px';
        firstBody.style.paddingBottom = '20px';
    }

    // ==========================================
    // مدیریت چک‌باکس‌های نمایش با AJAX
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.vis-checkbox');
        const courseId = {{ $course->id }};
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;
                const switchContainer = this.closest('.switch');
                
                if (switchContainer) {
                    switchContainer.classList.add('loading');
                }
                this.disabled = true;
                
                fetch(`/teacher/courses/toggle-visibility/${courseId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        field: field,
                        value: value
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'خطا در ارتباط با سرور');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        this.checked = !this.checked;
                        showToast('error', data.message || 'خطا در به‌روزرسانی');
                    } else {
                        showToast('success', data.message || 'وضعیت با موفقیت به‌روزرسانی شد');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !this.checked;
                    showToast('error', error.message || 'خطا در ارتباط با سرور');
                })
                .finally(() => {
                    if (switchContainer) {
                        switchContainer.classList.remove('loading');
                    }
                    this.disabled = false;
                });
            });
        });
    });

    function showToast(type, message) {
        const oldToast = document.querySelector('.custom-toast');
        if (oldToast) oldToast.remove();
        
        const toast = document.createElement('div');
        toast.className = `custom-toast ${type}`;
        toast.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endsection