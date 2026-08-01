@extends('layout.master')

@section('title')
ملیسان | تنظیمات درس
@endsection

@section('head')
<!--<link rel="stylesheet" href="{{asset('css/style-student-setting.css')}}">-->
<link rel="stylesheet" href="{{asset('css/badge.css')}}">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
<style>
/* ===== استایل لینک تعریف آزمون ===== */
.definition-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #1e6f9f;
    text-decoration: none;
    font-weight: 500;
    padding: 4px 14px;
    border-radius: 20px;
    background: #f0f7fe;
    border: 1px solid #d4e4f5;
    transition: all 0.3s ease;
    font-size: 13px;
    margin: 4px 0;
}

.definition-link i {
    font-size: 14px;
    color: #1e6f9f;
    transition: transform 0.3s ease;
}

.definition-link:hover {
    background: #e3effa;
    border-color: #1e6f9f;
    transform: translateX(-3px);
    box-shadow: 0 4px 12px rgba(30, 111, 159, 0.15);
}

.definition-link:hover i {
    transform: translateX(-4px);
}

.definition-link .link-arrow {
    display: inline-block;
    transition: transform 0.3s ease;
}

.definition-link:hover .link-arrow {
    transform: translateX(-5px);
}

.definition-link .link-text {
    display: inline;
}

.definition-link .link-highlight {
    color: #0d4a6f;
    font-weight: 600;
    border-bottom: 2px dashed #1e6f9f;
    padding-bottom: 1px;
}

.definition-link.payan {
    background: #fef7f0;
    border-color: #f5dcc8;
    color: #a85c2a;
}

.definition-link.payan i {
    color: #a85c2a;
}

.definition-link.payan:hover {
    background: #fdeee3;
    border-color: #a85c2a;
    box-shadow: 0 4px 12px rgba(168, 92, 42, 0.15);
}

.definition-link.payan .link-highlight {
    color: #7a401a;
    border-bottom-color: #a85c2a;
}

/* ===== استایل پاسخ‌های توضیحات ===== */
.score-description-cell {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 13px;
    color: #4b5563;
    line-height: 1.7;
}

.score-description-cell > i {
    color: #1e6f9f;
    font-size: 16px;
    margin-top: 2px;
    flex-shrink: 0;
}

.score-description-cell .desc-content {
    flex: 1;
}

/* ===== استایل‌های Jodit ===== */
.jodit-container {
    border-radius: 12px !important;
    overflow: hidden;
    border: 2px solid #e8edf3 !important;
    transition: all 0.3s ease;
}

.jodit-container:focus-within {
    border-color: #1e6f9f !important;
    box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.08) !important;
}

.jodit-container .jodit-toolbar {
    background: #f8fafc !important;
    border-bottom: 1px solid #e8edf3 !important;
    border-radius: 12px 12px 0 0 !important;
}

.jodit-container .jodit-workplace {
    min-height: 120px;
}

.jodit-container .jodit-wysiwyg {
    padding: 12px 16px !important;
    font-family: 'Vazir', Tahoma, Arial, sans-serif !important;
    font-size: 14px !important;
    direction: rtl !important;
    min-height: 120px !important;
}

@media (max-width: 768px) {
    .jodit-container .jodit-toolbar {
        flex-wrap: wrap !important;
    }
}

/* ===== استایل‌های اعتبارسنجی ===== */
.score-input.error {
    border-color: #dc3545 !important;
    background-color: #fff5f5 !important;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15) !important;
}

.validation-error {
    display: none;
    background: #fff5f5;
    border: 1px solid #fecaca;
    border-radius: 12px;
    padding: 14px 20px;
    margin: 12px 0 0 0;
    color: #991b1b;
    font-size: 14px;
    align-items: center;
    gap: 10px;
}

.validation-error.show {
    display: flex;
}

.validation-error i {
    font-size: 18px;
    color: #dc2626;
}

.total-score.error {
    background: #fef2f2 !important;
    border-color: #fca5a5 !important;
    color: #991b1b !important;
}

.total-score.success {
    background: #f0fdf4 !important;
    border-color: #86efac !important;
    color: #166534 !important;
}

.total-score.error .score-text {
    color: #991b1b !important;
}

.total-score.success .score-text {
    color: #166534 !important;
}

/* ===== استایل مینیمم نمره ===== */
.min-score-badge {
    display: inline-block;
    font-size: 11px;
    color: #6b7280;
    background: #f3f4f6;
    padding: 2px 10px;
    border-radius: 20px;
    margin-right: 8px;
}

.score-input-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
}

.score-input-wrapper .min-label {
    font-size: 10px;
    color: #6b7280;
    background: #f3f4f6;
    padding: 1px 10px;
    border-radius: 20px;
}

.score-input-wrapper .min-label.required {
    background: #fef3c7;
    color: #92400e;
}

/* ===== استایل جدید بخش خودآزمایی ===== */
.quiz-time-section {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px 20px;
    margin-top: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.quiz-time-section.active {
    border-color: #1e6f9f;
    background: #f0f7fe;
    box-shadow: 0 2px 8px rgba(30, 111, 159, 0.08);
}

.quiz-time-section .section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.quiz-time-section .section-header i {
    color: #1e6f9f;
    font-size: 16px;
}

.quiz-time-section .section-header span {
    font-weight: 600;
    font-size: 14px;
    color: #1f2937;
}

.quiz-time-section .section-header .badge-time {
    background: #1e6f9f;
    color: white;
    font-size: 10px;
    padding: 2px 10px;
    border-radius: 20px;
    margin-right: auto;
}

.quiz-time-type {
    display: flex;
    gap: 20px;
    margin-top: 8px;
    flex-wrap: wrap;
}

.quiz-time-type .time-option {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    padding: 8px 16px;
    border-radius: 8px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    background: white;
}

.quiz-time-type .time-option:hover {
    background: #f3f4f6;
}

.quiz-time-type .time-option.selected {
    border-color: #1e6f9f;
    background: #e8f0fe;
}

.quiz-time-type .time-option input[type="radio"] {
    accent-color: #1e6f9f;
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.quiz-time-type .time-option .option-icon {
    font-size: 18px;
    color: #1e6f9f;
}

.quiz-time-type .time-option .option-label {
    font-size: 14px;
    color: #374151;
    font-weight: 500;
}

.quiz-time-type .time-option .option-desc {
    font-size: 12px;
    color: #6b7280;
}

.quiz-time-input {
    margin-top: 12px;
    padding: 12px 16px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    display: none;
}

.quiz-time-input.visible {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.quiz-time-input .input-row {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.quiz-time-input .input-row label {
    font-size: 13px;
    color: #4b5563;
    font-weight: 500;
}

.quiz-time-input .input-row .form-input {
    width: 90px;
    text-align: center;
    font-weight: 600;
}

.quiz-time-input .input-row .unit {
    font-size: 13px;
    color: #6b7280;
}

.quiz-time-input .input-row .hint {
    font-size: 12px;
    color: #9ca3af;
}

.quiz-time-calc {
    margin-top: 10px;
    padding: 10px 14px;
    background: #f3f4f6;
    border-radius: 6px;
    font-size: 13px;
    color: #4b5563;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.quiz-time-calc i {
    color: #1e6f9f;
}

.quiz-time-calc strong {
    color: #1e6f9f;
    font-weight: 700;
}

.quiz-time-calc .calc-result {
    background: #1e6f9f;
    color: white;
    padding: 2px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
}

/* ===== استایل‌های دیگر ===== */
.time-settings-row {
    display: table-row;
}

.time-section {
    display: none;
}

.time-section.visible {
    display: block;
}

/* ===== استایل دکمه‌ها ===== */
.btn-default-score {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    color: #374151;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-default-score:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn-default-score i {
    font-size: 14px;
}

/* ===== استایل فرم ===== */
.settings-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.accordion-wrapper {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.accordion-item {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    transition: all 0.3s ease;
}

.accordion-item.active {
    border-color: #1e6f9f;
    box-shadow: 0 4px 20px rgba(30, 111, 159, 0.08);
}

.accordion-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    cursor: pointer;
    background: #fafbfc;
    transition: all 0.3s ease;
    user-select: none;
}

.accordion-header:hover {
    background: #f3f4f6;
}

.accordion-header i:first-child {
    font-size: 18px;
    color: #1e6f9f;
    width: 24px;
}

.accordion-header span {
    flex: 1;
    font-weight: 600;
    font-size: 15px;
    color: #1f2937;
}

.accordion-icon {
    transition: transform 0.3s ease;
    color: #9ca3af;
}

.accordion-body {
    display: none;
    max-height: 0;
    overflow: hidden;
    transition: all 0.4s ease;
    padding: 0 20px;
}

.accordion-body.active {
    display: block;
    max-height: 2000px !important;
    padding: 20px;
}

/* ===== استایل جدول ===== */
.settings-table {
    width: 100%;
    border-collapse: collapse;
}

.settings-table thead th {
    text-align: right;
    padding: 12px 16px;
    background: #f8fafc;
    border-bottom: 2px solid #e5e7eb;
    font-weight: 600;
    font-size: 13px;
    color: #4b5563;
}

.settings-table tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
}

.settings-table tbody tr:hover {
    background: #fafbfc;
}

.settings-table tbody tr:last-child td {
    border-bottom: none;
}

/* ===== استایل ورودی‌ها ===== */
.form-input {
    width: 100%;
    padding: 8px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #ffffff;
    color: #1f2937;
}

.form-input:focus {
    outline: none;
    border-color: #1e6f9f;
    box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
}

.form-input[type="number"] {
    max-width: 120px;
}

.form-textarea {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #ffffff;
    color: #1f2937;
    resize: vertical;
    font-family: 'Vazir', Tahoma, Arial, sans-serif;
}

.form-textarea:focus {
    outline: none;
    border-color: #1e6f9f;
    box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
}

.form-select {
    width: 100%;
    padding: 8px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #ffffff;
    color: #1f2937;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: left 12px center;
}

.form-select:focus {
    outline: none;
    border-color: #1e6f9f;
    box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
}

/* ===== استایل Toggle Switch ===== */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 48px;
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
    background: #d1d5db;
    transition: 0.4s;
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
    transition: 0.4s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.toggle-switch input:checked + .toggle-slider {
    background: #1e6f9f;
}

.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(22px);
}

.toggle-label {
    font-size: 14px;
    color: #374151;
    min-width: 40px;
}

/* ===== استایل دکمه ذخیره ===== */
.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 2px solid #e5e7eb;
}

.save-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 32px;
    background: #1e6f9f;
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.save-btn:hover {
    background: #155a82;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(30, 111, 159, 0.3);
}

.save-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.save-btn i {
    font-size: 18px;
}

/* ===== استایل Toast ===== */
.custom-toast {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    padding: 14px 24px;
    border-radius: 12px;
    color: white;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    opacity: 0;
    transition: all 0.4s ease;
    max-width: 90%;
}

.custom-toast.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.custom-toast.success {
    background: #10b981;
}

.custom-toast.error {
    background: #ef4444;
}

.custom-toast.warning {
    background: #f59e0b;
}

.custom-toast i {
    font-size: 20px;
}

/* ===== استایل Visibility Grid ===== */
.visibility-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px;
}

.vis-item {
    padding: 8px 0;
}

.switch-label {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
}

.vis-icon {
    font-size: 18px;
    color: #1e6f9f;
    width: 30px;
}

.vis-text {
    flex: 1;
    font-size: 14px;
    color: #374151;
}

.switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.switch .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #d1d5db;
    transition: 0.4s;
    border-radius: 34px;
}

.switch .slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background: white;
    transition: 0.4s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.switch input:checked + .slider {
    background: #1e6f9f;
}

.switch input:checked + .slider:before {
    transform: translateX(20px);
}

.switch.loading {
    opacity: 0.5;
}

.vis-note {
    margin-top: 16px;
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 8px;
    font-size: 13px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 8px;
}

.vis-note i {
    color: #1e6f9f;
}

/* ===== استایل نمایش مجموع ===== */
.total-score {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 16px;
    padding: 16px 20px;
    border-radius: 12px;
    background: #f8fafc;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.total-score .score-text {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
}

.total-score .score-text #majmo {
    color: #1e6f9f;
    font-size: 20px;
}

/* ===== استایل اطلاعات دوره ===== */
.info-badge {
    display: flex;
    align-items: center;
    gap: 8px;
}

.course-badge {
    background: #f8fafc;
    padding: 8px 16px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}

.badge-icon {
    color: #1e6f9f;
    font-size: 18px;
}

.badge-label {
    font-size: 13px;
    color: #6b7280;
}

.badge-value {
    font-size: 15px;
    font-weight: 600;
    color: #1f2937;
}

/* ===== استایل دکمه برگشت ===== */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #f3f4f6;
    border-radius: 8px;
    color: #374151;
    text-decoration: none;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: #e5e7eb;
    color: #1f2937;
}
</style>
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
                    <span>بارم بندی</span>
                    <i class="fas fa-chevron-down accordion-icon"></i>
                </div>
                <div class="accordion-body {{ Request::has('open_section') && Request::get('open_section') == 'barmbandi' ? 'active' : '' }}" 
                     style="{{ Request::has('open_section') && Request::get('open_section') == 'barmbandi' ? 'display: block; max-height: 2000px; padding-top: 20px; padding-bottom: 20px;' : '' }}">
                    <table class="settings-table">
                        <thead>
                            <tr>
                                <th style="width: 30%;">موضوع</th>
                                <th style="width: 20%;">نمره</th>
                                <th style="width: 50%;">توضیح</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    ارزشیابی مستمر
                                    <span class="min-score-badge required">حداقل ۵ نمره</span>
                                </td>
                                <td>
                                    <div class="score-input-wrapper">
                                        <input type="number" name="mostamar_nomre" id="mostamar_nomre" value="{{ $setting->mostamar_nomre ?? 12 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="20" step="0.5">
                                        <span class="min-label required">حداقل: ۵</span>
                                    </div>
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
                                    <div class="score-input-wrapper">
                                        <input type="number" name="taklif_seminar_nomre" id="taklif_nomre" value="{{ $setting->taklif_seminar_nomre ?? 0 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="20" step="0.5">
                                    </div>
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
                                    <div class="score-input-wrapper">
                                        <input type="number" name="azmon_nomre" id="azmon_nomre" value="{{ $setting->azmon_nomre ?? 0 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="20" step="0.5">
                                    </div>
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
                                    <div class="score-input-wrapper">
                                        <input type="number" name="hozor_ghayab_nomre" id="hozor_ghayab_nomre" value="{{ $setting->hozor_ghayab_nomre ?? 0 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="20" step="0.5">
                                    </div>
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
                                    <div class="score-input-wrapper">
                                        <input type="number" name="miyan_term_nomre" id="miyan_term_nomre" value="{{ $setting->miyan_term_nomre ?? 0 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="20" step="0.5">
                                    </div>
                                </td>
                                <td>
                                    <div class="score-description-cell">
                                        <i class="fas fa-info-circle"></i>
                                        <div class="desc-content">
                                            اگر تمایل دارید آزمون میان‌ترم از طریق سامانه برگزار شود، در بخش 
                                            <a href="/teacher/courses/azmon/list/{{ $course->id }}" class="definition-link">
                                                <i class="fas fa-plus-circle"></i>
                                                <span class="link-text">تعریف آزمون</span>
                                                <span class="link-arrow">←</span>
                                                <span class="link-highlight">میان‌ترم</span>
                                            </a>
                                            را انتخاب کنید.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>کار عملی (بازدید|آزمایشگاه|کارگاه)</td>
                                <td>
                                    <div class="score-input-wrapper">
                                        <input type="number" name="kar_amali_nomre" id="kar_amali_nomre" value="{{ $setting->kar_amali_nomre ?? 0 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="20" step="0.5">
                                    </div>
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
                                    <div class="score-input-wrapper">
                                        <input type="number" name="payan_term_nomre" id="payan_term_nomre" value="{{ $setting->payan_term_nomre ?? 8 }}" class="form-input score-input" onkeyup="validateScores()" min="0" max="20" step="0.5">
                                        <span class="min-label required">حداقل: ۵</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="score-description-cell">
                                        <i class="fas fa-info-circle"></i>
                                        <div class="desc-content">
                                            اگر تمایل دارید آزمون پایان‌ترم از طریق سامانه برگزار شود، در بخش 
                                            <a href="/teacher/courses/azmon/list/{{ $course->id }}" class="definition-link payan">
                                                <i class="fas fa-plus-circle"></i>
                                                <span class="link-text">تعریف آزمون</span>
                                                <span class="link-arrow">←</span>
                                                <span class="link-highlight">پایان‌ترم</span>
                                            </a>
                                            را انتخاب کنید. در غیر این صورت، نمرهٔ پایان‌ترم را باید به‌صورت دستی در قسمت «نمرات دانشجویان» وارد کنید.
                                        </div>
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
                                    ($setting->payan_term_nomre ?? 8)
                                }}
                            </span> از ۲۰
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
                                    {{-- Jodit Editor --}}
                                    <textarea class="jodit-editor" name="ersal_gozaresh_desc" id="reportDescEditor">{{ $setting->ersal_gozaresh_desc ?? 'موضوع اصلی این جلسه چه بود و چه هدفی داشت؟ لطفاً یک نکتهٔ آموزنده از مطالب ارائه شده را با بیانی دیگر (به زبان خودتان) بازنویسی کنید.' }}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="total-score">
                        <button type="button" class="btn-default-score" onclick="setDefaultActivities()">
                            <i class="fas fa-undo-alt"></i>
                            فعالیت‌های پیش فرض
                        </button>
                    </div>
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
                                    <input type="number" name="q_num" id="q_num" value="{{ $setting->q_num ?? 10 }}" class="form-input" min="1">
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
                            <tr>
                                <td>محدودیت زمانی برای خودآزمایی</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <label class="toggle-switch">
                                            <input type="hidden" name="time_limit_khod" value="0">
                                            <input type="checkbox" name="time_limit_khod" id="timeToggle" value="1" {{ $setting->time_limit_khod == 1 ? 'checked' : '' }} class="toggle-text" data-target="time-limit-text">
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span id="time-limit-text" class="toggle-label">{{ $setting->time_limit_khod == 1 ? 'فعال' : 'غیرفعال' }}</span>
                                    </div>
                                </td>
                            </tr>
                            <!-- ردیف تنظیمات زمان - همیشه نمایش داده میشود با استایل جدید -->
                            <tr id="time-settings-row" class="time-settings-row">
                                <td colspan="2" style="padding: 0;">
                                    <div class="quiz-time-section {{ $setting->time_limit_khod == 1 ? 'active' : '' }}" id="timeSection">
                                        <div class="section-header">
                                            <i class="fas fa-clock"></i>
                                            <span>تنظیمات محدودیت زمانی</span>
                                            <span class="badge-time" id="statusBadge">{{ $setting->time_limit_khod == 1 ? 'فعال' : 'غیرفعال' }}</span>
                                        </div>
                                        
                                        <!-- نوع محدودیت -->
                                        <div class="quiz-time-type" id="timeTypeGroup" style="{{ $setting->time_limit_khod == 1 ? '' : 'opacity: 0.5; pointer-events: none;' }}">
                                            <label class="time-option {{ ($setting->time_per_question > 0 && $setting->total_time_limit == 0) || ($setting->time_per_question == 0 && $setting->total_time_limit == 0) ? 'selected' : '' }}" id="perQuestionOption">
                                                <input type="radio" name="time_type" value="per_question" {{ ($setting->time_per_question > 0 && $setting->total_time_limit == 0) || ($setting->time_per_question == 0 && $setting->total_time_limit == 0) ? 'checked' : '' }}>
                                                <span class="option-icon">⏱️</span>
                                                <div>
                                                    <div class="option-label">به ازای هر سوال</div>
                                                    <div class="option-desc">تنظیم زمان برای هر سوال</div>
                                                </div>
                                            </label>
                                            <label class="time-option {{ $setting->total_time_limit > 0 ? 'selected' : '' }}" id="totalOption">
                                                <input type="radio" name="time_type" value="total" {{ $setting->total_time_limit > 0 ? 'checked' : '' }}>
                                                <span class="option-icon">⏳</span>
                                                <div>
                                                    <div class="option-label">کل آزمون</div>
                                                    <div class="option-desc">تنظیم زمان برای کل آزمون</div>
                                                </div>
                                            </label>
                                        </div>
                                        
                                        <!-- ورودی زمان به ازای هر سوال -->
                                        <div id="per-question-time" class="quiz-time-input {{ ($setting->time_per_question > 0 && $setting->total_time_limit == 0) || ($setting->time_per_question == 0 && $setting->total_time_limit == 0) ? 'visible' : '' }}" style="{{ $setting->time_limit_khod == 1 ? '' : 'opacity: 0.5;' }}">
                                            <div class="input-row">
                                                <label>زمان هر سوال:</label>
                                                <input type="number" name="time_per_question" id="time_per_question" value="{{ $setting->time_per_question > 0 ? $setting->time_per_question : 45 }}" class="form-input" min="1" max="300" {{ $setting->time_limit_khod == 1 ? '' : 'disabled' }}>
                                                <span class="unit">ثانیه</span>
                                                <span class="hint">(حداقل ۱، حداکثر ۳۰۰)</span>
                                            </div>
                                            <div class="quiz-time-calc">
                                                <i class="fas fa-calculator"></i>
                                                زمان کل آزمون ≈ 
                                                <strong id="total-time-calc">{{ round((($setting->q_num ?? 10) * ($setting->time_per_question > 0 ? $setting->time_per_question : 45)) / 60) }}</strong>
                                                دقیقه
                                                <span class="calc-result" id="perQuestionResult">
                                                    {{ $setting->time_per_question > 0 ? $setting->time_per_question : 45 }} ثانیه X سوال
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- ورودی زمان کل -->
                                        <div id="total-time" class="quiz-time-input {{ $setting->total_time_limit > 0 ? 'visible' : '' }}" style="{{ $setting->time_limit_khod == 1 ? '' : 'opacity: 0.5;' }}">
                                            <div class="input-row">
                                                <label>زمان کل آزمون:</label>
                                                <input type="number" name="total_time_limit" id="total_time_limit" value="{{ $setting->total_time_limit > 0 ? $setting->total_time_limit : round((($setting->q_num ?? 10) * 45) / 60) }}" class="form-input" min="1" max="120" {{ $setting->time_limit_khod == 1 ? '' : 'disabled' }}>
                                                <span class="unit">دقیقه</span>
                                                <span class="hint">(حداقل ۱، حداکثر ۱۲۰)</span>
                                            </div>
                                            <div class="quiz-time-calc">
                                                <i class="fas fa-calculator"></i>
                                                میانگین زمان هر سوال ≈ 
                                                <strong id="avg-time-per-question">{{ round((($setting->total_time_limit > 0 ? $setting->total_time_limit : round((($setting->q_num ?? 10) * 45) / 60)) * 60) / ($setting->q_num ?? 10)) }}</strong>
                                                ثانیه
                                                <span class="calc-result" id="totalResult">
                                                    {{ $setting->total_time_limit > 0 ? $setting->total_time_limit : round((($setting->q_num ?? 10) * 45) / 60) }} دقیقه
                                                </span>
                                            </div>
                                        </div>
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
{{-- اضافه کردن Jodit --}}
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>
    // ==========================================
    // مقداردهی Jodit Editor برای فیلد ارسال گزارش
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        const reportEditorElement = document.getElementById('reportDescEditor');
        if (reportEditorElement) {
            new Jodit('#reportDescEditor', {
                width: '100%',
                height: 200,
                allowResize: true,
                allowResizeImages: true,
                direction: 'rtl',
                language: 'fa',
                buttons: [
                    'source', '|',
                    'undo', 'redo', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'font', 'fontsize', 'brush', 'paragraph', '|',
                    'ul', 'ol', 'outdent', 'indent', '|',
                    'align', 'hr', 'table', '|',
                    'link', 'unlink',
                    {
                        name: 'uploadImage',
                        iconURL: 'https://cdn-icons-png.flaticon.com/512/1829/1829586.png',
                        tooltip: 'آپلود تصویر',
                        exec: (editor) => {
                            let input = document.createElement('input');
                            input.type = 'file';
                            input.accept = 'image/*';
                            input.onchange = () => {
                                let file = input.files[0];
                                if (!file) return;

                                let formData = new FormData();
                                formData.append('file', file);

                                fetch('{{ route("upload.image") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.files && data.files[0].url) {
                                        let img = document.createElement('img');
                                        img.src = data.files[0].url;
                                        img.style.maxWidth = '100%';
                                        editor.s.insertNode(img);
                                    } else {
                                        alert('خطا در آپلود تصویر');
                                    }
                                })
                                .catch(err => alert('Upload error: ' + err));
                            };
                            input.click();
                        }
                    },
                    {
                        name: 'uploadVideo',
                        iconURL: 'https://cdn-icons-png.flaticon.com/512/727/727245.png',
                        tooltip: 'آپلود ویدیو',
                        exec: (editor) => {
                            let input = document.createElement('input');
                            input.type = 'file';
                            input.accept = 'video/*';
                            input.onchange = () => {
                                let file = input.files[0];
                                if (!file) return;

                                let formData = new FormData();
                                formData.append('file', file);

                                fetch('{{ route("upload.video") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.files && data.files[0].url) {
                                        let wrapper = document.createElement('div');
                                        wrapper.classList.add('video-wrapper');

                                        let video = document.createElement('video');
                                        video.setAttribute('controls', '');
                                        video.src = data.files[0].url;
                                        video.style.maxWidth = '100%';

                                        wrapper.appendChild(video);
                                        editor.s.insertNode(wrapper);
                                    } else {
                                        alert('خطا در آپلود ویدیو');
                                    }
                                })
                                .catch(err => alert('Upload error: ' + err));
                            };
                            input.click();
                        }
                    },
                    '|', 'symbols', 'emoticons', '|',
                    'print', 'fullsize', 'preview'
                ],
                colors: {
                    text: ['#000000', '#ff0000', '#00ff00', '#0000ff', '#ff00ff', '#00ffff'],
                    background: ['#ffffff', '#ffff00', '#00ffff', '#ffcc99']
                },
                defaultFont: 'Vazir, Tahoma, Arial, sans-serif',
                defaultFontSize: '14px',
                fonts: ['Vazir', 'Tahoma', 'Arial', 'Courier New']
            });
        }

        // اجرای اعتبارسنجی اولیه
        validateScores();
        initializeTimeSettings();
    });

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
    // اعتبارسنجی مجموع نمرات با محدودیت‌های جدید
    // ==========================================
    function validateScores() {
        var mostamar = parseFloat(document.getElementById('mostamar_nomre').value) || 0;
        var taklif = parseFloat(document.getElementById('taklif_nomre').value) || 0;
        var azmon = parseFloat(document.getElementById('azmon_nomre').value) || 0;
        var hozor = parseFloat(document.getElementById('hozor_ghayab_nomre').value) || 0;
        var miyan = parseFloat(document.getElementById('miyan_term_nomre').value) || 0;
        var karAmali = parseFloat(document.getElementById('kar_amali_nomre').value) || 0;
        var payan = parseFloat(document.getElementById('payan_term_nomre').value) || 0;
        
        var total = mostamar + taklif + azmon + hozor + miyan + karAmali + payan;
        document.getElementById('majmo').textContent = total;
        
        var errorDiv = document.getElementById('scoreValidationError');
        var errorMessage = document.getElementById('scoreErrorMessage');
        var totalBox = document.getElementById('totalScoreBox');
        var submitBtn = document.getElementById('submitBtn');
        var scoreInputs = document.querySelectorAll('.score-input');
        
        // حذف کلاس error از همه فیلدها
        scoreInputs.forEach(input => input.classList.remove('error'));
        
        // جمع‌آوری خطاها
        var errorMessages = [];
        
        // شرط 1: ارزشیابی مستمر حداقل 25% (5 نمره از 20)
        if (mostamar < 5) {
            errorMessages.push('ارزشیابی مستمر باید حداقل 5 نمره (25%) باشد');
            document.getElementById('mostamar_nomre').classList.add('error');
        }
        
        // شرط 2: پایان ترم حداقل 25% (5 نمره از 20)
        if (payan < 5) {
            errorMessages.push('پایان ترم باید حداقل 5 نمره (25%) باشد');
            document.getElementById('payan_term_nomre').classList.add('error');
        }
        
        // شرط 3: مجموع نمرات دقیقاً 20 باشد
        if (total !== 20) {
            if (total > 20) {
                errorMessages.push('مجموع نمرات نمی‌تواند از ۲۰ بیشتر باشد (مجموع فعلی: ' + total + ')');
            } else {
                errorMessages.push('مجموع نمرات باید دقیقاً برابر با ۲۰ باشد (مجموع فعلی: ' + total + ')');
            }
            // هایلایت کردن تمام فیلدها در صورت عدم تطابق مجموع
            scoreInputs.forEach(input => input.classList.add('error'));
        }
        
        // نمایش خطاها
        if (errorMessages.length > 0) {
            errorMessage.textContent = errorMessages.join(' | ');
            errorDiv.classList.add('show');
            totalBox.className = 'total-score error';
            submitBtn.disabled = true;
            return false;
        } else {
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
                showToast('error', 'لطفاً خطاهای بارم‌بندی را اصلاح کنید');
                
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
    // بارم بندی پیش فرض (طبق بارم‌بندی 10)
    // ==========================================
    function setDefaultScore() {
        // تنظیم مقادیر پیش فرض: ارزشیابی 12، پایان ترم 8، بقیه صفر
        document.getElementById('mostamar_nomre').value = 12;
        document.getElementById('taklif_nomre').value = 0;
        document.getElementById('azmon_nomre').value = 0;
        document.getElementById('hozor_ghayab_nomre').value = 0;
        document.getElementById('miyan_term_nomre').value = 0;
        document.getElementById('kar_amali_nomre').value = 0;
        document.getElementById('payan_term_nomre').value = 8;
        
        // اعتبارسنجی و به‌روزرسانی
        validateScores();
        
        // نمایش پیام موفقیت
        showToast('success', 'بارم بندی به حالت پیش فرض (ارزشیابی مستمر: ۱۲، پایان ترم: ۸) تنظیم شد');
    }

    // ==========================================
    // فعالیت‌های پیش فرض
    // ==========================================
    function setDefaultActivities() {
        // تنظیم چک‌باکس‌ها
        document.querySelectorAll('input[name="soal_last"], input[name="gozaresh_last"], input[name="taklif_last"]').forEach(function(el) {
            el.checked = true;
        });
        
        // تنظیم مقادیر عددی
        var jalasatInput = document.querySelector('input[name="jalasat"]');
        if (jalasatInput) jalasatInput.value = 16;
        
        var maxTaklifInput = document.querySelector('input[name="max_taklif"]');
        if (maxTaklifInput) maxTaklifInput.value = 3;
        
        var maxSoalInput = document.querySelector('input[name="max_soal"]');
        if (maxSoalInput) maxSoalInput.value = 3;
        
        // تنظیم متن طراحی سوال
        var tarahiDesc = document.querySelector('textarea[name="tarahi_soal_desc"]');
        if (tarahiDesc) {
            tarahiDesc.value = 'یک سؤال خلاقانه طراحی کنید که به یادگیری دوستانتان کمک کند و به نام خودتان منتشر شود. قبل از ارسال، حتماً سؤالاتی که دیگران طرح کرده اند را مرور کنید تا از تکراری نبودن سوال خود مطمئن شوید.';
        }
        
        // تنظیم متن ارسال گزارش (ادیتور)
        var reportEditor = document.querySelector('.jodit-editor');
        if (reportEditor) {
            if (reportEditor.jodit) {
                reportEditor.jodit.value = 'موضوع اصلی این جلسه چه بود و چه هدفی داشت؟ لطفاً یک نکتهٔ آموزنده از مطالب ارائه شده را با بیانی دیگر (به زبان خودتان) بازنویسی کنید.';
            } else {
                reportEditor.value = 'موضوع اصلی این جلسه چه بود و چه هدفی داشت؟ لطفاً یک نکتهٔ آموزنده از مطالب ارائه شده را با بیانی دیگر (به زبان خودتان) بازنویسی کنید.';
            }
        }
        
        showToast('success', 'فعالیت‌ها به حالت پیش فرض تنظیم شدند');
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
            // اگر toggle محدودیت زمانی تغییر کرد، تنظیمات زمان را به‌روز کن
            if (this.id === 'timeToggle') {
                toggleTimeSettings();
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

    // ==========================================
    // نمایش Toast
    // ==========================================
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

    // ==========================================
    // مدیریت محدودیت زمانی خودآزمایی - نسخه جدید و زیبا
    // ==========================================
    function initializeTimeSettings() {
        const timeToggle = document.getElementById('timeToggle');
        const timeSection = document.getElementById('timeSection');
        const statusBadge = document.getElementById('statusBadge');
        const timeTypeGroup = document.getElementById('timeTypeGroup');
        const perQuestionInput = document.getElementById('per-question-time');
        const totalInput = document.getElementById('total-time');
        const perQuestionRadio = document.querySelector('input[name="time_type"][value="per_question"]');
        const totalRadio = document.querySelector('input[name="time_type"][value="total"]');
        
        // تنظیم وضعیت اولیه
        if (timeToggle && timeToggle.checked) {
            timeSection.classList.add('active');
            statusBadge.textContent = 'فعال';
            statusBadge.style.background = '#10b981';
            timeTypeGroup.style.opacity = '1';
            timeTypeGroup.style.pointerEvents = 'auto';
            perQuestionInput.style.opacity = '1';
            totalInput.style.opacity = '1';
            
            // غیرفعال کردن disabled ورودی‌ها
            document.querySelectorAll('#per-question-time input, #total-time input').forEach(el => el.disabled = false);
            
            // نمایش بخش مناسب بر اساس انتخاب
            if (perQuestionRadio && perQuestionRadio.checked) {
                perQuestionInput.classList.add('visible');
                perQuestionInput.style.display = 'block';
                totalInput.classList.remove('visible');
                totalInput.style.display = 'none';
                document.getElementById('perQuestionOption').classList.add('selected');
                document.getElementById('totalOption').classList.remove('selected');
            } else if (totalRadio && totalRadio.checked) {
                perQuestionInput.classList.remove('visible');
                perQuestionInput.style.display = 'none';
                totalInput.classList.add('visible');
                totalInput.style.display = 'block';
                document.getElementById('perQuestionOption').classList.remove('selected');
                document.getElementById('totalOption').classList.add('selected');
            } else {
                // حالت پیش‌فرض: به ازای هر سوال
                if (perQuestionRadio) perQuestionRadio.checked = true;
                perQuestionInput.classList.add('visible');
                perQuestionInput.style.display = 'block';
                totalInput.classList.remove('visible');
                totalInput.style.display = 'none';
                document.getElementById('perQuestionOption').classList.add('selected');
                document.getElementById('totalOption').classList.remove('selected');
            }
        } else {
            timeSection.classList.remove('active');
            statusBadge.textContent = 'غیرفعال';
            statusBadge.style.background = '#6b7280';
            timeTypeGroup.style.opacity = '0.5';
            timeTypeGroup.style.pointerEvents = 'none';
            perQuestionInput.style.opacity = '0.5';
            totalInput.style.opacity = '0.5';
            
            // غیرفعال کردن ورودی‌ها
            document.querySelectorAll('#per-question-time input, #total-time input').forEach(el => el.disabled = true);
            
            // مخفی کردن بخش‌ها
            perQuestionInput.classList.remove('visible');
            perQuestionInput.style.display = 'none';
            totalInput.classList.remove('visible');
            totalInput.style.display = 'none';
        }
        
        updateTimeCalculations();
    }

    function toggleTimeSettings() {
        const timeToggle = document.getElementById('timeToggle');
        const timeSection = document.getElementById('timeSection');
        const statusBadge = document.getElementById('statusBadge');
        const timeTypeGroup = document.getElementById('timeTypeGroup');
        const perQuestionInput = document.getElementById('per-question-time');
        const totalInput = document.getElementById('total-time');
        const perQuestionRadio = document.querySelector('input[name="time_type"][value="per_question"]');
        const totalRadio = document.querySelector('input[name="time_type"][value="total"]');
        
        if (timeToggle && timeToggle.checked) {
            timeSection.classList.add('active');
            statusBadge.textContent = 'فعال';
            statusBadge.style.background = '#10b981';
            timeTypeGroup.style.opacity = '1';
            timeTypeGroup.style.pointerEvents = 'auto';
            perQuestionInput.style.opacity = '1';
            totalInput.style.opacity = '1';
            
            document.querySelectorAll('#per-question-time input, #total-time input').forEach(el => el.disabled = false);
            
            // انتخاب پیش‌فرض: به ازای هر سوال
            if (!perQuestionRadio.checked && !totalRadio.checked) {
                if (perQuestionRadio) perQuestionRadio.checked = true;
            }
            
            // نمایش بخش مناسب
            if (perQuestionRadio && perQuestionRadio.checked) {
                perQuestionInput.classList.add('visible');
                perQuestionInput.style.display = 'block';
                totalInput.classList.remove('visible');
                totalInput.style.display = 'none';
                document.getElementById('perQuestionOption').classList.add('selected');
                document.getElementById('totalOption').classList.remove('selected');
            } else if (totalRadio && totalRadio.checked) {
                perQuestionInput.classList.remove('visible');
                perQuestionInput.style.display = 'none';
                totalInput.classList.add('visible');
                totalInput.style.display = 'block';
                document.getElementById('perQuestionOption').classList.remove('selected');
                document.getElementById('totalOption').classList.add('selected');
            }
        } else {
            timeSection.classList.remove('active');
            statusBadge.textContent = 'غیرفعال';
            statusBadge.style.background = '#6b7280';
            timeTypeGroup.style.opacity = '0.5';
            timeTypeGroup.style.pointerEvents = 'none';
            perQuestionInput.style.opacity = '0.5';
            totalInput.style.opacity = '0.5';
            
            document.querySelectorAll('#per-question-time input, #total-time input').forEach(el => el.disabled = true);
            
            perQuestionInput.classList.remove('visible');
            perQuestionInput.style.display = 'none';
            totalInput.classList.remove('visible');
            totalInput.style.display = 'none';
        }
        
        updateTimeCalculations();
    }

    function updateTimeCalculations() {
        const q_num = parseInt(document.getElementById('q_num').value) || 10;
        const timeType = document.querySelector('input[name="time_type"]:checked');
        const timeToggle = document.getElementById('timeToggle');
        
        if (!timeToggle || !timeToggle.checked) {
            return;
        }
        
        if (timeType && timeType.value === 'per_question') {
            const timePerQuestion = parseInt(document.getElementById('time_per_question').value) || 45;
            const totalMinutes = Math.round((q_num * timePerQuestion) / 60);
            
            document.getElementById('total-time-calc').textContent = totalMinutes;
            document.getElementById('perQuestionResult').textContent = timePerQuestion + ' ثانیه X سوال';
            
            // به‌روزرسانی input hidden برای ارسال
            document.getElementById('time_per_question').value = timePerQuestion;
            
        } else if (timeType && timeType.value === 'total') {
            const totalMinutes = parseInt(document.getElementById('total_time_limit').value) || Math.round((q_num * 45) / 60);
            const avgSeconds = Math.round((totalMinutes * 60) / q_num);
            
            document.getElementById('avg-time-per-question').textContent = avgSeconds;
            document.getElementById('totalResult').textContent = totalMinutes + ' دقیقه';
            
            // به‌روزرسانی input hidden برای ارسال
            document.getElementById('total_time_limit').value = totalMinutes;
        }
    }

    // ==========================================
    // رویدادهای به‌روزرسانی زمان
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        // رویدادهای به‌روزرسانی زمان
        const qNumInput = document.getElementById('q_num');
        if (qNumInput) {
            qNumInput.addEventListener('change', updateTimeCalculations);
            qNumInput.addEventListener('input', updateTimeCalculations);
        }
        
        const timePerQuestionInput = document.getElementById('time_per_question');
        if (timePerQuestionInput) {
            timePerQuestionInput.addEventListener('change', updateTimeCalculations);
            timePerQuestionInput.addEventListener('input', updateTimeCalculations);
        }
        
        const totalTimeInput = document.getElementById('total_time_limit');
        if (totalTimeInput) {
            totalTimeInput.addEventListener('change', updateTimeCalculations);
            totalTimeInput.addEventListener('input', updateTimeCalculations);
        }
        
        // رویدادهای رادیو برای انتخاب گزینه
        document.querySelectorAll('input[name="time_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                // به‌روزرسانی کلاس selected
                document.querySelectorAll('.time-option').forEach(opt => opt.classList.remove('selected'));
                if (this.value === 'per_question') {
                    document.getElementById('perQuestionOption').classList.add('selected');
                    document.getElementById('per-question-time').classList.add('visible');
                    document.getElementById('per-question-time').style.display = 'block';
                    document.getElementById('total-time').classList.remove('visible');
                    document.getElementById('total-time').style.display = 'none';
                } else {
                    document.getElementById('totalOption').classList.add('selected');
                    document.getElementById('total-time').classList.add('visible');
                    document.getElementById('total-time').style.display = 'block';
                    document.getElementById('per-question-time').classList.remove('visible');
                    document.getElementById('per-question-time').style.display = 'none';
                }
                updateTimeCalculations();
            });
        });

        // رویداد toggle محدودیت زمانی
        const timeToggle = document.getElementById('timeToggle');
        if (timeToggle) {
            timeToggle.addEventListener('change', toggleTimeSettings);
        }
    });
</script>
@endsection