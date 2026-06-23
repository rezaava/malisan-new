@extends('layout.master')

@section('title')
ملیسان | تنظیمات درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-setting.css')}}">
@endsection

@section('mohtava')
<div class="settings-container">
    <form class="settings-form" action="/dashboard/courses/edit-setting" method="post" enctype="multipart/form-data">
        @csrf
        <input name="course_id" value="232" hidden>

        <div class="accordion-wrapper">
            <!-- بارم بندی -->
            <div class="accordion-item active">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <i class="fas fa-balance-scale"></i>
                    <span>بارم بندی (مجموع : ۱۰۰)</span>
                    <i class="fas fa-chevron-down accordion-icon"></i>
                </div>
                <div class="accordion-body active">
                    <table class="settings-table">
                        <thead>
                            <tr>
                                <th>موضوع</th>
                                <th>امتیاز</th>
                                <th>توضیح برای دانشجو</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>تعداد جلسات</td>
                                <td>
                                    <input type="text" name="jalasat" value="16" class="form-input">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>چند نمره برای ارزشیابی مستمر در نظر می گیرید؟</td>
                                <td>
                                    <input type="text" name="mostamar_nomre" id="mostamar_nomre" value="12" class="form-input" onkeyup="jam()">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>تکلیف یا سمینار</td>
                                <td>
                                    <input type="text" name="taklif_seminar_nomre" id="taklif_nomre" value="0" class="form-input" onkeyup="jam()">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-label">برآورد تعداد تکلیف یا سمینار</span>
                                        <input type="text" name="max_taklif" value="8" class="form-input">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>آزمون</td>
                                <td>
                                    <input type="text" name="azmon_nomre" id="azmon_nomre" value="0" class="form-input" onkeyup="jam()">
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="total-score">
                        مجموع نمرات : <span id="majmo">12</span>
                    </div>
                    <div class="score-info">
                        <p>پیشرفت درسی: 70 درصد نمره ارزشیابی مستمر (محاسبه توسط سیستم)</p>
                        <p>تلاش و فعالیت: 30 درصد نمره ارزشیابی مستمر (محاسبه توسط سیستم)</p>
                    </div>
                </div>
            </div>

            <!-- فعالیت ها -->
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
                                <th>موضوع</th>
                                <th>وضعیت</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>دانشجو فقط برای آخرین جلسه درس مجاز به ثبت سوال است</td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="soal_last" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>دانشجو فقط برای آخرین جلسه درس مجاز به ارسال گزارش است</td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="gozaresh_last" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>دانشجو فقط برای آخرین جلسه درس مجاز به ارسال تکلیف است</td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="taklif_last">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>حداکثر تعداد سوالاتی که توسط دانشجو در هر جلسه طرح می شود</td>
                                <td>
                                    <input type="text" name="max_soal" value="3" class="form-input">
                                </td>
                            </tr>
                            <tr>
                                <td>طراحی سوال</td>
                                <td>
                                    <textarea name="tarahi_soal_desc" class="form-textarea" rows="2">قبل از طرح سوال کلیه سوالاتی که برای این جلسه تا بحال طرح شده است را مشاهده کنید و سوالی طرح کنید که تکراری نباشد</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>ارسال گزارش</td>
                                <td>
                                    <textarea name="ersal_gozaresh_desc" class="form-textarea" rows="2">خلاصه ای از آنچه در این جلسه یاد گرفته اید بنویسید یا اگر مطلب جدیدی دارید با ذکر منبع بیان کنید</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- خودآزمایی -->
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
                                <th>موضوع</th>
                                <th>وضعیت</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>حداقل دفعات شرکت در خود آزمایی در طول هفته</td>
                                <td>
                                    <input type="number" name="min_w_khod" value="14" class="form-input">
                                </td>
                            </tr>
                            <tr>
                                <td>تعداد سوالات در هر خودآزمایی</td>
                                <td>
                                    <input type="number" name="q_num" value="10" class="form-input">
                                </td>
                            </tr>
                            <tr>
                                <td>سطح سوالات در هر خودآزمایی</td>
                                <td>
                                    <select name="sath_khod" class="form-select">
                                        <option value="1">عالی</option>
                                        <option value="2" selected>عالی و خوب</option>
                                        <option value="3">خوب</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>دانشجو بلافاصله بعد از آزمون نمره خود را ببیند</td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="hidden" name="natije" value="0">
                                        <input type="checkbox" name="natije" value="1" checked class="toggle-text" data-target="natije-text">
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span id="natije-text" class="toggle-label">بله</span>
                                </td>
                            </tr>
                            <tr>
                                <td>پاسخ سوالات خودآزمایی به دانشجو نشان داده شود</td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="hidden" name="show_quiz" value="0">
                                        <input type="checkbox" name="show_quiz" value="1" checked class="toggle-text" data-target="quiz-text">
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span id="quiz-text" class="toggle-label">بله</span>
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

    <!-- وضعیت درس -->
    <div class="status-card">
        <div class="status-header">
            <h5><i class="fas fa-sliders-h"></i> وضعیت درس</h5>
            <p>مدرس گرامی! شما میتوانید تنظیمات بیشتر درس خود را در باکس زیر کنترل نمایید.</p>
            <p><code class="status-active">فعال بودن</code> به معنای وجود دسترسی و <code class="status-inactive">غیر فعال بودن</code> به معنای نبود دسترسی میباشد.</p>
        </div>
        <div class="status-grid">
            <label class="status-item">
                <input type="checkbox" checked>
                <span class="status-text">در حال برگزاری</span>
                <span class="status-check"><i class="fas fa-check-circle"></i></span>
            </label>
            <label class="status-item">
                <input type="checkbox">
                <span class="status-text">آرشیو</span>
                <span class="status-check"><i class="fas fa-check-circle"></i></span>
            </label>
            <label class="status-item">
                <input type="checkbox">
                <span class="status-text">انتشار به صورت دوره</span>
                <span class="status-check"><i class="fas fa-check-circle"></i></span>
            </label>
            <label class="status-item">
                <input type="checkbox" checked>
                <span class="status-text">نمایش جلسات درس</span>
                <span class="status-check"><i class="fas fa-check-circle"></i></span>
            </label>
            <label class="status-item">
                <input type="checkbox" checked>
                <span class="status-text">امکان انجام داوری</span>
                <span class="status-check"><i class="fas fa-check-circle"></i></span>
            </label>
            <label class="status-item">
                <input type="checkbox" checked>
                <span class="status-text">شرکت در خود آزمایی</span>
                <span class="status-check"><i class="fas fa-check-circle"></i></span>
            </label>
            <label class="status-item">
                <input type="checkbox" checked>
                <span class="status-text">مشاهده فعالیت ها</span>
                <span class="status-check"><i class="fas fa-check-circle"></i></span>
            </label>
            <label class="status-item">
                <input type="checkbox" checked>
                <span class="status-text">مشاهده پیشرفت درسی</span>
                <span class="status-check"><i class="fas fa-check-circle"></i></span>
            </label>
        </div>
    </div>
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
                body.style.paddingTop = '1.5rem';
                body.style.paddingBottom = '1.5rem';
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
        firstBody.style.paddingTop = '1.5rem';
        firstBody.style.paddingBottom = '1.5rem';
    }
</script>
@endsection