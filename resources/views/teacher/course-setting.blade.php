@extends('layout.master')

@section('title')
ملیسان | تنظیمات درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-setting.css')}}">
@endsection

@section('mohtava')
<div class="settings-container">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
        <div class="info-badge course-badge">
            <span class="badge-icon">
                <i class="fas fa-book-open"></i>
            </span>
            <span class="badge-label">تنظیمات درس:</span>
            <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
        </div>
        <div>
            @include('layout.backbtn')
        </div>
    </div>
    <form action="/teacher/courses/edit-setting" method="post" enctype="multipart/form-data" id="settingsForm">
        @csrf
        <input name="course_id" value="{{ $course->id }}" hidden>

        <div class="accordion-wrapper">
            <!-- ==========================================
                 بارم بندی
                 ========================================== -->
            <div class="accordion-item {{ Request::has('open_section') && Request::get('open_section') == 'barmbandi' ? 'active' : '' }}">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <i class="fas fa-balance-scale"></i>
                    <span>بارم بندی (مجموع : ۱۰۰)</span>
                    <i class="fas fa-chevron-down accordion-icon"></i>
                </div>
                <div class="accordion-body {{ Request::has('open_section') && Request::get('open_section') == 'barmbandi' ? 'active' : '' }}" 
                     style="{{ Request::has('open_section') && Request::get('open_section') == 'barmbandi' ? 'display: block; max-height: 2000px; padding-top: 20px; padding-bottom: 20px;' : '' }}">
                    <table class="settings-table">
                        <thead>
                            <tr>
                                <th style="width: 30%;">موضوع</th>
                                <th style="width: 20%;">امتیاز</th>
                                <th style="width: 50%;">توضیح</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ارزشیابی مستمر</td>
                                <td>
                                    <input type="number" name="mostamar_nomre" id="mostamar_nomre" value="{{ $setting->mostamar_nomre ?? 12 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="100">
                                </td>
                                <td>
                                    <div class="score-description-cell">
                                        <i class="fas fa-info-circle"></i>
                                        یک سوم از امتیاز در نظر گرفته شده به «تلاش و فعالیت» و دو سوم آن به «پیشرفت درسی» اختصاص می‌یابد؛ امتیاز هر دو بخش توسط سیستم و بر اساس میزان تلاش و عملکرد دانشجو محاسبه خواهد شد.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>تکلیف یا سمینار</td>
                                <td>
                                    <input type="number" name="taklif_seminar_nomre" id="taklif_nomre" value="{{ $setting->taklif_seminar_nomre ?? 0 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="100">
                                </td>
                                <td>
                                    <div class="score-description-cell">
                                        <i class="fas fa-info-circle"></i>
                                        {{ $setting->taklif_seminar_desc ?? 'نمره این بخش بر اساس تعداد تکالیف یا سمینارهایی که دانشجو تا پایان ترم ارائه می دهد، محاسبه خواهد شد.' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>آزمون</td>
                                <td>
                                    <input type="number" name="azmon_nomre" id="azmon_nomre" value="{{ $setting->azmon_nomre ?? 0 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="100">
                                </td>
                                <td>
                                    <div class="score-description-cell">
                                        <i class="fas fa-info-circle"></i>
                                        {{ $setting->azmon_desc ?? 'نمرهٔ نهایی بر اساس تعداد آزمون هایی که دانشجو تا پایان ترم شرکت می کند، محاسبه خواهد شد.' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>حضور و غیاب</td>
                                <td>
                                    <input type="number" name="hozor_ghayab_nomre" id="hozor_ghayab_nomre" value="{{ $setting->hozor_ghayab_nomre ?? 0 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="100">
                                </td>
                                <td>
                                    <div class="score-description-cell">
                                        <i class="fas fa-info-circle"></i>
                                        {{ 'حضور و غیاب بر اساس فعالیت‌های دانشجو در هر جلسه محاسبه خواهد شد که شامل ارسال گزارش و سؤال، داوری و انجام خودآزمایی است. نمرهٔ نهایی در نظر گرفته شده، با توجه به میزان حضور و مشارکت فعال دانشجو تعیین خواهد شد.' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>میان ترم</td>
                                <td>
                                    <input type="number" name="miyan_term_nomre" id="miyan_term_nomre" value="{{ $setting->miyan_term_nomre ?? 0 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="100">
                                </td>
                                <td>
                                    <div class="score-description-cell">
                                        <i class="fas fa-info-circle"></i>
                                        اگر تمایل دارید آزمون میان‌ترم از طریق سامانه برگزار شود، در بخش «تعریف آزمون»، نوع آزمون را «میان‌ترم» انتخاب کنید.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>کار عملی (بازدید|آزمایشگاه|کارگاه)</td>
                                <td>
                                    <input type="number" name="kar_amali_nomre" id="kar_amali_nomre" value="{{ $setting->kar_amali_nomre ?? 0 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="100">
                                </td>
                                <td>
                                    <div class="score-description-cell">
                                        <i class="fas fa-info-circle"></i>
                                        اگر نمره‌ای برای این بخش در نظر گرفتید، باید آن را به‌صورت دستی در قسمت «نمرات دانشجویان» ثبت کنید.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>پایان ترم</td>
                                <td>
                                    <input type="number" name="payan_term_nomre" id="payan_term_nomre" value="{{ $setting->payan_term_nomre ?? 6 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="100">
                                </td>
                                <td>
                                    <div class="score-description-cell">
                                        <i class="fas fa-info-circle"></i>
اگر تمایل دارید آزمون پایان‌ترم از طریق سامانه برگزار شود، در بخش «تعریف آزمون»، نوع آزمون را «پایان‌ترم» انتخاب کنید. در غیر این صورت، نمرهٔ پایان‌ترم را باید به‌صورت دستی در قسمت «نمرات دانشجویان» وارد کنید.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <!-- پیام خطای مجموع نمرات -->
                    <div class="validation-error" id="scoreValidationError">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="scoreErrorMessage"></span>
                    </div>

                    <div class="total-score" id="totalScoreBox">
                        <span class="score-text">
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
                        </span>
                        <button type="button" class="btn-default-score" onclick="setDefaultScore()">
                            <i class="fas fa-undo-alt"></i>
                            بارم بندی پیش فرض
                        </button>
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
                                <td>تعداد جلسات</td>
                                <td>
                                    <input type="number" name="jalasat" value="{{ $setting->jalasat ?? 16 }}" class="form-input" min="1">
                                </td>
                            </tr>
                            <tr>
                                <td>تعداد تکلیف/سمینار</td>
                                <td>
                                    <input type="number" name="max_taklif" value="{{ $setting->max_taklif ?? 3 }}" class="form-input" min="0">
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
            <button type="submit" class="save-btn" id="submitBtn">
                <i class="fas fa-save"></i>
                ذخیره اطلاعات
            </button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    // ==========================================
    // محاسبه مجموع نمرات
    // ==========================================
    function calculateTotal() {
        var azmon = parseFloat(document.getElementById('azmon_nomre').value) || 0;
        var taklif = parseFloat(document.getElementById('taklif_nomre').value) || 0;
        var mostamar = parseFloat(document.getElementById('mostamar_nomre').value) || 0;
        var hozor = parseFloat(document.getElementById('hozor_ghayab_nomre').value) || 0;
        var miyan = parseFloat(document.getElementById('miyan_term_nomre').value) || 0;
        var karAmali = parseFloat(document.getElementById('kar_amali_nomre').value) || 0;
        var payan = parseFloat(document.getElementById('payan_term_nomre').value) || 0;
        
        return azmon + taklif + mostamar + hozor + miyan + karAmali + payan;
    }

    function updateTotalDisplay() {
        var total = calculateTotal();
        document.getElementById('majmo').textContent = total;
        return total;
    }

    // ==========================================
    // اعتبارسنجی مجموع نمرات
    // ==========================================
    function validateScores() {
        var total = updateTotalDisplay();
        var errorDiv = document.getElementById('scoreValidationError');
        var errorMessage = document.getElementById('scoreErrorMessage');
        var totalBox = document.getElementById('totalScoreBox');
        var submitBtn = document.getElementById('submitBtn');
        var scoreInputs = document.querySelectorAll('.score-input');

        // حذف کلاس error از همه فیلدها
        scoreInputs.forEach(input => input.classList.remove('error'));

        if (total !== 100) {
            // نمایش خطا
            if (total > 100) {
                errorMessage.textContent = 'مجموع نمرات نمی‌تواند از ۱۰۰ بیشتر باشد (مجموع فعلی: ' + total + ')';
            } else {
                errorMessage.textContent = 'مجموع نمرات باید دقیقاً برابر با ۱۰۰ باشد (مجموع فعلی: ' + total + ')';
            }
            errorDiv.classList.add('show');
            totalBox.className = 'total-score error';
            submitBtn.disabled = true;
            return false;
        } else {
            // اعتبارسنجی موفق
            errorDiv.classList.remove('show');
            totalBox.className = 'total-score success';
            submitBtn.disabled = false;
            return true;
        }
    }

    // ==========================================
    // اجرای اعتبارسنجی هنگام بارگذاری
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        validateScores();
        
        // جلوگیری از ارسال فرم در صورت نامعتبر بودن
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            if (!validateScores()) {
                e.preventDefault();
                showToast('error', 'لطفاً مجموع نمرات را به ۱۰۰ برسانید');
                
                // باز کردن اکیاردین بارم بندی
                var accordionHeader = document.querySelector('.accordion-item:first-child .accordion-header');
                if (accordionHeader) {
                    var body = accordionHeader.nextElementSibling;
                    if (!body.classList.contains('active')) {
                        toggleAccordion(accordionHeader);
                    }
                }
                
                // اسکرول به بخش خطا
                document.getElementById('scoreValidationError').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            }
        });
    });

    // ==========================================
    // بارم بندی پیش فرض
    // ==========================================
    function setDefaultScore() {
        // تنظیم مقادیر پیش فرض
        document.getElementById('mostamar_nomre').value = 12;
        document.getElementById('taklif_nomre').value = 0;
        document.getElementById('azmon_nomre').value = 0;
        document.getElementById('hozor_ghayab_nomre').value = 0;
        document.getElementById('miyan_term_nomre').value = 0;
        document.getElementById('kar_amali_nomre').value = 0;
        document.getElementById('payan_term_nomre').value = 88;
        
        // اعتبارسنجی و به‌روزرسانی
        validateScores();
        
        // نمایش پیام موفقیت
        showToast('success', 'بارم بندی به حالت پیش فرض (ارزشیابی مستمر: ۱۲، پایان ترم: ۸۸) تنظیم شد');
    }

    // ==========================================
    // توابع کمکی
    // ==========================================
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
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
</script>
@endsection