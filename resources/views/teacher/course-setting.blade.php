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
    }
</style>
@endsection

@section('mohtava')
<div class="settings-container">
    <form class="settings-form" action="{{ route('courses.setting.update') }}" method="post" enctype="multipart/form-data">
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
                        </tbody>
                    </table>
                    <div class="total-score">
                        مجموع نمرات : <span id="majmo">{{ ($setting->mostamar_nomre ?? 12) + ($setting->taklif_seminar_nomre ?? 0) + ($setting->azmon_nomre ?? 0) }}</span>
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
            <div class="accordion-item">
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
                                <td>طراحی سوال</td>
                                <td>
                                    <textarea name="tarahi_soal_desc" class="form-textarea" rows="3">{{ $setting->tarahi_soal_desc ?? 'قبل از طرح سوال کلیه سوالاتی که برای این جلسه تا بحال طرح شده است را مشاهده کنید و سوالی طرح کنید که تکراری نباشد' }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>ارسال گزارش</td>
                                <td>
                                    <textarea name="ersal_gozaresh_desc" class="form-textarea" rows="3">{{ $setting->ersal_gozaresh_desc ?? 'خلاصه ای از آنچه در این جلسه یاد گرفته اید بنویسید یا اگر مطلب جدیدی دارید با ذکر منبع بیان کنید' }}</textarea>
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
        document.getElementById('majmo').textContent = azmon + taklif + mostamar;
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
</script>
@endsection
