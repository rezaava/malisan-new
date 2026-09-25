@extends('layout.master')
@section('title')
ملیسان | {{ isset($azmon) ? 'ویرایش' : 'ایجاد' }} آزمون
@endsection
@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
<link rel="stylesheet" href="{{ asset('css/azmon-create.css') }}">
<style>
.settings-group {
    margin-top: 25px;
}
.settings-title {
    display: block;
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 14px;
}
.settings-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}
.setting-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 76px;
    padding: 12px 14px;
    margin: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all .2s ease;
    direction: rtl;
}
.setting-card:hover {
    border-color: #b8c7d9;
    box-shadow: 0 4px 14px rgba(0,0,0,.06);
    transform: translateY(-1px);
}
.setting-icon {
    width: 42px;
    height: 42px;
    flex: 0 0 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #f1f5f9;
    color: #2563eb;
    font-size: 17px;
}
.setting-content {
    flex: 1;
    min-width: 0;
}
.setting-label {
    display: block;
    color: #1f2937;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 4px;
}
.setting-content small {
    display: block;
    color: #8a94a6;
    font-size: 11px;
    line-height: 1.7;
}
.setting-switch {
    position: relative;
    flex: 0 0 auto;
    width: 44px;
    height: 24px;
}
.setting-switch input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.switch-slider {
    position: absolute;
    inset: 0;
    background: #d1d5db;
    border-radius: 30px;
    transition: .25s ease;
}
.switch-slider::before {
    content: "";
    position: absolute;
    width: 18px;
    height: 18px;
    right: 3px;
    top: 3px;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
    transition: .25s ease;
}
.setting-switch input:checked + .switch-slider {
    background: #2563eb;
}
.setting-switch input:checked + .switch-slider::before {
    transform: translateX(-20px);
}
.setting-card:has(input:checked) {
    border-color: #93c5fd;
    background: #f8fbff;
}
.setting-card:has(input:checked) .setting-icon {
    background: #dbeafe;
    color: #2563eb;
}
@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
@section('mohtava')
<div class="azmon-form-container">
    <div class="azmon-form-card">
        {{-- HEADER --}}
        <div class="azmon-form-header">
            <h3>
                <i class="fas {{ isset($azmon) ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                {{ isset($azmon) ? 'ویرایش آزمون' : 'ایجاد آزمون جدید' }}
            </h3>
            <div class="course-badge">
                <i class="fas fa-book-open"></i>
                {{ $course->name }}
            </div>
        </div>
        {{-- ERRORS --}}
        @if($errors->any())
            <div class="alert-danger-custom">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>خطا!</strong> لطفاً خطاهای زیر را برطرف کنید:
                    <ul style="margin:4px 0 0 20px;padding:0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        {{-- FORM --}}
        <form method="POST"
              action="{{ isset($azmon) ? '/teacher/courses/azmon/edit/'.$azmon->id : '/teacher/courses/azmon/create' }}"
              enctype="multipart/form-data"
              id="azmonForm">
            @csrf
            @if(isset($azmon))
                @method('PUT')
            @endif
            <input type="hidden" name="id" value="{{ $course->id }}">
            {{-- نحوه دسترسی --}}
            <div class="form-group {{ $errors->has('access_type') ? 'has-error' : '' }}">
                <label>نحوه دسترسی به آزمون <span class="required">*</span></label>
                <select class="form-control" name="access_type" id="accessType" required>
                    <option value="code" {{ old('access_type', isset($azmon) ? ($azmon->code ? 'code' : 'free') : 'code') == 'code' ? 'selected' : '' }}>
                        با در اختیار داشتن کد آزمون
                    </option>
                    <option value="free" {{ old('access_type', isset($azmon) ? ($azmon->code ? 'code' : 'free') : 'code') == 'free' ? 'selected' : '' }}>
                        آزاد و بدون نیاز به کد آزمون
                    </option>
                </select>
                @if($errors->has('access_type'))
                    <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('access_type') }}</span>
                @endif
            </div>
            {{-- Code --}}
            <div class="form-group" id="codeFieldGroup">
                <label>کد آزمون</label>
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           id="examCode"
                           name="code"
                           value="{{ isset($azmon) ? $azmon->code : '' }}"
                           readonly
                           style="background-color:#f8f9fa;direction:ltr;text-align:center;font-weight:bold;letter-spacing:5px;">
                    <button type="button" class="btn btn-outline-primary rounded" id="generateCodeBtn">
                        <i class="fas fa-sync-alt"></i> تولید مجدد
                    </button>
                </div>
                <div class="help-text">کد ۵ رقمی که دانشجو برای ورود به آزمون نیاز دارد</div>
                @if($errors->has('code'))
                    <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('code') }}</span>
                @endif
            </div>
            {{-- Title --}}
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label>عنوان آزمون <span class="required">*</span></label>
                <input type="text"
                       class="form-control"
                       name="title"
                       placeholder="مثال: ریاضی - فصل اول"
                       value="{{ old('title', isset($azmon) ? $azmon->title : '') }}"
                       required>
                @if($errors->has('title'))
                    <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('title') }}</span>
                @endif
            </div>
            {{-- Description --}}
            <div class="form-group">
                <label>توضیحات (اختیاری)</label>
                <textarea class="jodit-editor"
                          id="descriptionEditor"
                          name="description"
                          placeholder="توضیحات آزمون را وارد کنید...">{{ old('description', isset($azmon) ? $azmon->description : '') }}</textarea>
            </div>
            {{-- Level & Num --}}
            <div class="form-row">
                <div class="form-group {{ $errors->has('sath') ? 'has-error' : '' }}">
                    <label>سطح سوالات <span class="required">*</span></label>
                    <select class="form-control" name="sath" required>
                        <option value="3" {{ old('sath', isset($azmon) ? $azmon->sath : 3) == 3 ? 'selected' : '' }}>عالی و خوب</option>
                        <option value="1" {{ old('sath', isset($azmon) ? $azmon->sath : 3) == 1 ? 'selected' : '' }}>عالی</option>
                        <option value="2" {{ old('sath', isset($azmon) ? $azmon->sath : 3) == 2 ? 'selected' : '' }}>خوب</option>
                        <option value="4" {{ old('sath', isset($azmon) ? $azmon->sath : 3) == 4 ? 'selected' : '' }}>سوالات ستاره‌دار</option>
                        <option value="5" {{ old('sath', isset($azmon) ? $azmon->sath : 3) == 5 ? 'selected' : '' }}>فقط سوالات استاد</option>
                    </select>
                    @if($errors->has('sath'))
                        <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('sath') }}</span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('num') ? 'has-error' : '' }}">
                    <label>تعداد سوالات <span class="required">*</span></label>
                    <input type="number"
                           class="form-control"
                           name="num"
                           min="1"
                           max="100"
                           value="{{ old('num', isset($azmon) ? $azmon->num : 10) }}"
                           required>
                    @if($errors->has('num'))
                        <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('num') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="type">نوع آزمون <span class="required">*</span></label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="periodic" @selected(old('type', isset($azmon) ? $azmon->type : 'periodic') === 'periodic')>
                            دوره ای
                        </option>
                        <option value="mid-term" @selected(old('type', isset($azmon) ? $azmon->type : 'periodic') === 'mid-term')>
                            میان ترم
                        </option>
                        <option value="final" @selected(old('type', isset($azmon) ? $azmon->type : 'periodic') === 'final')>
                            پایانی
                        </option>
                    </select>
                </div>
            </div>
            {{-- Sessions --}}
            <div class="form-group {{ $errors->has('sessions') ? 'has-error' : '' }}">
                <label>جلسات <span class="required">*</span></label>
                <select name="sessions[]" id="sessionsSelect" class="form-control" multiple required>
                    <option value="all"
                        @if(old('sessions', isset($selectedSessions) ? $selectedSessions : []))
                            @if(in_array('all', old('sessions', isset($selectedSessions) ? $selectedSessions : []))) selected @endif
                        @endif>
                        تمام جلسات
                    </option>
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}"
                            @if(old('sessions', isset($selectedSessions) ? $selectedSessions : []))
                                @if(in_array($session->id, old('sessions', isset($selectedSessions) ? $selectedSessions : []))) selected @endif
                            @endif>
                            {{ $session->name }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('sessions'))
                    <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('sessions') }}</span>
                @endif
            </div>
            {{-- Time --}}
            <div class="form-group {{ $errors->has('time') ? 'has-error' : '' }}">
                <label>زمان آزمون (دقیقه) <span class="required">*</span></label>
                <input type="number"
                       class="form-control"
                       name="time"
                       min="1"
                       max="300"
                       value="{{ old('time', isset($azmon) ? $azmon->time : 60) }}"
                       required>
                @if($errors->has('time'))
                    <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('time') }}</span>
                @endif
            </div>
            {{-- Date & Time --}}
            <div class="form-group">
                <label>تاریخ و زمان شروع و پایان <span class="required">*</span></label>
                @php
                    $oldStartDate = old('start_date', '');
                    $oldStartH = old('start_h', '');
                    $oldStartM = old('start_m', '');
                    $oldEndDate = old('end_date', '');
                    $oldEndH = old('end_h', '');
                    $oldEndM = old('end_m', '');
                    if (!$oldStartDate && isset($azmon) && $azmon->start) {
                        $startParts = explode(' ', $azmon->start);
                        $oldStartDate = $startParts[0] ?? '';
                        $timeParts = explode(':', $startParts[1] ?? '00:00');
                        $oldStartH = $timeParts[0] ?? '';
                        $oldStartM = $timeParts[1] ?? '';
                    }
                    if (!$oldEndDate && isset($azmon) && $azmon->end) {
                        $endParts = explode(' ', $azmon->end);
                        $oldEndDate = $endParts[0] ?? '';
                        $timeParts = explode(':', $endParts[1] ?? '00:00');
                        $oldEndH = $timeParts[0] ?? '';
                        $oldEndM = $timeParts[1] ?? '';
                    }
                @endphp
                {{-- Start --}}
                <div class="datetime-group {{ $errors->has('start_date') || $errors->has('start_h') || $errors->has('start_m') ? 'has-error' : '' }}" style="margin-bottom:12px;">
                    <span class="label-text"><i class="fas fa-play-circle" style="color:#4caf50;"></i> شروع:</span>
                    <input type="text"
                           class="date-input jalali-date"
                           id="start-date"
                           name="start_date"
                           placeholder="تاریخ (مثال: 1402/01/01)"
                           data-jdp
                           value="{{ $oldStartDate }}">
                    <span class="separator">|</span>
                    <input type="number"
                           class="time-input"
                           id="start-h"
                           name="start_h"
                           min="0"
                           max="23"
                           placeholder="ساعت"
                           value="{{ $oldStartH }}">
                    <span class="separator">:</span>
                    <input type="number"
                           class="time-input"
                           id="start-m"
                           name="start_m"
                           min="0"
                           max="59"
                           placeholder="دقیقه"
                           value="{{ $oldStartM }}">
                </div>
                @if($errors->has('start_date') || $errors->has('start_h') || $errors->has('start_m'))
                    <span class="error-text">
                        <i class="fas fa-times-circle"></i>
                        @if($errors->has('start_date')) {{ $errors->first('start_date') }} @endif
                        @if($errors->has('start_h')) {{ $errors->first('start_h') }} @endif
                        @if($errors->has('start_m')) {{ $errors->first('start_m') }} @endif
                    </span>
                @endif
                {{-- End --}}
                <div class="datetime-group {{ $errors->has('end_date') || $errors->has('end_h') || $errors->has('end_m') ? 'has-error' : '' }}">
                    <span class="label-text"><i class="fas fa-stop-circle" style="color:#f44336;"></i> پایان:</span>
                    <input type="text"
                           class="date-input jalali-date"
                           id="end-date"
                           name="end_date"
                           placeholder="تاریخ (مثال: 1402/01/01)"
                           data-jdp
                           value="{{ $oldEndDate }}">
                    <span class="separator">|</span>
                    <input type="number"
                           class="time-input"
                           id="end-h"
                           name="end_h"
                           min="0"
                           max="23"
                           placeholder="ساعت"
                           value="{{ $oldEndH }}">
                    <span class="separator">:</span>
                    <input type="number"
                           class="time-input"
                           id="end-m"
                           name="end_m"
                           min="0"
                           max="59"
                           placeholder="دقیقه"
                           value="{{ $oldEndM }}">
                </div>
                @if($errors->has('end_date') || $errors->has('end_h') || $errors->has('end_m'))
                    <span class="error-text">
                        <i class="fas fa-times-circle"></i>
                        @if($errors->has('end_date')) {{ $errors->first('end_date') }} @endif
                        @if($errors->has('end_h')) {{ $errors->first('end_h') }} @endif
                        @if($errors->has('end_m')) {{ $errors->first('end_m') }} @endif
                    </span>
                @endif
                <div class="help-text">بازه زمانی که دانشجو می‌تواند در آزمون شرکت کند</div>
            </div>
            {{-- Settings --}}
            <div class="form-group settings-group">
                <label class="settings-title">تنظیمات نمایش</label>
                <div class="settings-grid">
                    <label class="setting-card">
                        <div class="setting-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="setting-content">
                            <span class="setting-label">نمایش نمره آزمون</span>
                            <small>نمره آزمون به دانشجو نشان داده شود</small>
                        </div>
                        <div class="setting-switch">
                            <input type="checkbox"
                                   name="show_nomre"
                                   {{ old('show_nomre', isset($azmon) ? $azmon->show_nomre : false) ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </div>
                    </label>
                    <label class="setting-card">
                        <div class="setting-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="setting-content">
                            <span class="setting-label">نمایش پاسخ سوالات</span>
                            <small>پاسخ سوالات به دانشجو نشان داده شود</small>
                        </div>
                        <div class="setting-switch">
                            <input type="checkbox"
                                   name="show_ans"
                                   {{ old('show_ans', isset($azmon) ? $azmon->show_ans : false) ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </div>
                    </label>
                    <label class="setting-card">
                        <div class="setting-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="setting-content">
                            <span class="setting-label">تغییر پاسخ</span>
                            <small>دانشجو امکان تغییر پاسخ داشته باشد</small>
                        </div>
                        <div class="setting-switch">
                            <input type="checkbox"
                                   name="changeable"
                                   {{ old('changeable', isset($azmon) ? $azmon->changeable : false) ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </div>
                    </label>
                    <label class="setting-card">
                        <div class="setting-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="setting-content">
                            <span class="setting-label">زمان باقیمانده</span>
                            <small>نمایش زمان باقیمانده به دانشجو</small>
                        </div>
                        <div class="setting-switch">
                            <input type="checkbox"
                                   name="show_remain"
                                   {{ old('show_remain', isset($azmon) ? $azmon->show_remain : false) ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </div>
                    </label>
                    <label class="setting-card">
                        <div class="setting-icon">
                            <i class="fas fa-list-ol"></i>
                        </div>
                        <div class="setting-content">
                            <span class="setting-label">موقعیت سوال</span>
                            <small>نمایش موقعیت سوال در حال پاسخگویی</small>
                        </div>
                        <div class="setting-switch">
                            <input type="checkbox"
                                   name="show_state"
                                   {{ old('show_state', isset($azmon) ? $azmon->show_state : false) ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </div>
                    </label>
                </div>
            </div>
            {{-- Actions --}}
            <div class="form-actions">
                <button type="submit" class="btn-submit btn-submit-primary" id="submitBtn">
                    <i class="fas {{ isset($azmon) ? 'fa-save' : 'fa-plus' }}"></i>
                    {{ isset($azmon) ? 'بروزرسانی آزمون' : 'ایجاد آزمون' }}
                </button>
                <a href="{{ route('azmon.list', ['id' => $course->id]) }}" class="btn-submit btn-submit-outline">
                    <i class="fas fa-arrow-right"></i> بازگشت به لیست
                </a>
                @if(isset($azmon))
                    <a href="{{ route('azmon.delete', $azmon->id) }}"
                       class="btn-submit btn-submit-danger"
                       onclick="return confirm('آیا مطمئن هستید؟')">
                        <i class="fas fa-trash-alt"></i> حذف
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/persian-date@latest/dist/persian-date.js"></script>
<script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const accessType = document.getElementById('accessType');
    const codeFieldGroup = document.getElementById('codeFieldGroup');
    const examCodeInput = document.getElementById('examCode');
    const generateBtn = document.getElementById('generateCodeBtn');

    function generateRandomCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = '';
        for (let i = 0; i < 5; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return code;
    }

    function toggleCodeField() {
        if (accessType.value === 'code') {
            codeFieldGroup.style.display = 'block';
            if (!examCodeInput.value) {
                examCodeInput.value = generateRandomCode();
            }
        } else {
            codeFieldGroup.style.display = 'none';
            examCodeInput.value = '';
        }
    }

    toggleCodeField();

    accessType.addEventListener('change', function() {
        toggleCodeField();
    });

    generateBtn.addEventListener('click', function() {
        if (accessType.value === 'code') {
            examCodeInput.value = generateRandomCode();
        }
    });

    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('#sessionsSelect').select2({
            placeholder: 'جلسات را انتخاب کنید',
            allowClear: true,
            width: '100%',
            dir: 'rtl',
            language: 'fa'
        });

        $('#sessionsSelect').on('select2:select', function(e) {
            var data = e.params.data;

            if (data.id === 'all') {
                var allOptions = $('#sessionsSelect option');
                var allIds = [];

                allOptions.each(function() {
                    if ($(this).val() !== 'all') {
                        allIds.push($(this).val());
                    }
                });

                $(this).val(allIds).trigger('change');
            }
        });

        $('#sessionsSelect').on('select2:unselect', function(e) {
            var data = e.params.data;

            if (data.id === 'all') {
                $(this).val([]).trigger('change');
            }
        });

        $('#sessionsSelect').on('select2:selecting', function(e) {
            var currentVal = $(this).val() || [];
            var selectedId = e.params.args.data.id;

            if (currentVal.includes('all') && selectedId !== 'all') {
                $(this).val([]).trigger('change');
            }
        });
    }

    if (typeof $.fn.persianDatepicker !== 'undefined') {
        $('.jalali-date').persianDatepicker({
            format: 'YYYY/MM/DD',
            responsive: true,
            toolbox: {
                submitButton: {
                    enabled: true
                }
            },
            initialValue: true
        });
    }

    document.querySelectorAll('.jodit-editor').forEach(function(element) {
        if (typeof Jodit !== 'undefined') {
            const editorId = element.id || 'editor-' + Math.random().toString(36).substr(2, 9);

            if (!element.id) {
                element.id = editorId;
            }

            new Jodit('#' + editorId, {
                width: '100%',
                height: 250,
                allowResize: true,
                allowResizeImages: true,
                direction: 'rtl',
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
                        exec: function(editor) {
                            let input = document.createElement('input');
                            input.type = 'file';
                            input.accept = 'image/*';

                            input.onchange = function() {
                                let file = input.files[0];

                                if (!file) {
                                    return;
                                }

                                let formData = new FormData();
                                formData.append('file', file);

                                fetch('{{ route("upload.image") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(function(res) {
                                    return res.json();
                                })
                                .then(function(data) {
                                    if (data.files && data.files[0].url) {
                                        let img = document.createElement('img');
                                        img.src = data.files[0].url;
                                        img.style.maxWidth = '100%';
                                        editor.s.insertNode(img);
                                    } else {
                                        alert('خطا در آپلود تصویر');
                                    }
                                })
                                .catch(function(err) {
                                    alert('Upload error: ' + err);
                                });
                            };

                            input.click();
                        }
                    },
                    {
                        name: 'uploadVideo',
                        iconURL: 'https://cdn-icons-png.flaticon.com/512/727/727245.png',
                        tooltip: 'آپلود ویدیو',
                        exec: function(editor) {
                            let input = document.createElement('input');
                            input.type = 'file';
                            input.accept = 'video/*';

                            input.onchange = function() {
                                let file = input.files[0];

                                if (!file) {
                                    return;
                                }

                                let formData = new FormData();
                                formData.append('file', file);

                                fetch('{{ route("upload.video") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(function(res) {
                                    return res.json();
                                })
                                .then(function(data) {
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
                                .catch(function(err) {
                                    alert('Upload error: ' + err);
                                });
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
    });

    var form = document.getElementById('azmonForm');

    if (form) {
        form.addEventListener('submit', function(e) {
            var startDate = document.getElementById('start-date').value.trim();
            var endDate = document.getElementById('end-date').value.trim();

            if (!startDate) {
                e.preventDefault();
                alert('لطفاً تاریخ شروع را وارد کنید.');
                return false;
            }

            if (!endDate) {
                e.preventDefault();
                alert('لطفاً تاریخ پایان را وارد کنید.');
                return false;
            }
        });
    }
});
</script>
@endsection