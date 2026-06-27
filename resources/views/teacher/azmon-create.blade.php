@extends('layout.master')

@section('title')
ملیسان | {{ isset($azmon) ? 'ویرایش' : 'ایجاد' }} آزمون
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.css">

<style>
    /* ... استایل‌های قبلی ... */
    .azmon-form-container {
        max-width: 850px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .azmon-form-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 30px rgba(0, 0, 0, 0.06);
        padding: 35px 40px;
    }

    .azmon-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .azmon-form-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
    }

    .azmon-form-header h3 i {
        color: #1e6f9f;
        margin-left: 10px;
    }

    .azmon-form-header .course-badge {
        background: #f0f4f9;
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 13px;
        color: #4a5a6e;
    }

    .azmon-form-header .course-badge i {
        color: #1e6f9f;
        margin-left: 6px;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #1a2332;
        margin-bottom: 6px;
    }

    .form-group label .required {
        color: #f44336;
        margin-right: 3px;
    }

    .form-group .help-text {
        font-size: 12px;
        color: #6b7a8f;
        margin-top: 4px;
    }

    .form-group.has-error .form-control {
        border-color: #f44336;
        background: #fff5f5;
    }

    .form-group .error-text {
        color: #f44336;
        font-size: 13px;
        margin-top: 6px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        font-family: inherit;
    }

    .form-control:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
        background: #fff;
    }

    .form-control:disabled {
        background: #f0f4f9;
        cursor: not-allowed;
    }

    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .datetime-group {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        background: #fafbfc;
        border-radius: 12px;
        border: 2px solid #e8edf3;
        transition: all 0.3s ease;
    }

    .datetime-group.has-error {
        border-color: #f44336;
        background: #fff5f5;
    }

    .datetime-group .date-input {
        flex: 1;
        min-width: 140px;
        padding: 10px 14px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        background: #fff;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .datetime-group .date-input:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
    }

    .datetime-group .time-input {
        width: 70px;
        padding: 10px 8px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        text-align: center;
        background: #fff;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .datetime-group .time-input:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
    }

    .datetime-group .separator {
        font-weight: 700;
        color: #6b7a8f;
        font-size: 18px;
    }

    .datetime-group .label-text {
        font-weight: 600;
        color: #4a5a6e;
        font-size: 13px;
    }

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        padding: 16px 20px;
        background: #f8fafc;
        border-radius: 12px;
        border: 2px solid #eef2f7;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #1e6f9f;
        cursor: pointer;
        margin: 0;
    }

    .checkbox-item label {
        font-weight: 500;
        font-size: 13px;
        color: #1a2332;
        cursor: pointer;
        margin: 0;
    }

    .form-actions {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        padding-top: 24px;
        border-top: 2px solid #f0f4f9;
        margin-top: 10px;
    }

    .btn-submit {
        padding: 12px 36px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: inherit;
        text-decoration: none;
    }

    .btn-submit-primary {
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
    }

    .btn-submit-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 111, 159, 0.3);
        color: #fff;
    }

    .btn-submit-danger {
        background: linear-gradient(135deg, #f44336, #c62828);
        color: #fff;
    }

    .btn-submit-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(244, 67, 54, 0.3);
        color: #fff;
    }

    .btn-submit-outline {
        background: transparent;
        color: #1e6f9f;
        border: 2px solid #1e6f9f;
    }

    .btn-submit-outline:hover {
        background: #1e6f9f;
        color: #fff;
        transform: translateY(-2px);
    }

    .alert-danger-custom {
        background: #ffebee;
        border: 1px solid #f44336;
        color: #c62828;
        padding: 12px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    @media (max-width: 768px) {
        .azmon-form-card {
            padding: 20px 16px;
        }
        .azmon-form-header {
            flex-direction: column;
            align-items: stretch;
        }
        .datetime-group {
            flex-direction: column;
            align-items: stretch;
        }
        .datetime-group .time-input {
            width: 100%;
        }
        .form-actions {
            flex-direction: column;
        }
        .btn-submit {
            justify-content: center;
        }
        .checkbox-group {
            flex-direction: column;
            gap: 10px;
        }
    }

    .select2-container--default .select2-selection--multiple {
        border: 2px solid #e8edf3 !important;
        border-radius: 12px !important;
        padding: 4px 8px;
        background: #fafbfc;
        min-height: 48px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #e3f2fd;
        border: none;
        border-radius: 20px;
        padding: 4px 12px;
        color: #1e6f9f;
        font-weight: 600;
        font-size: 13px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #1e6f9f;
        margin-left: 6px;
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

            @if(!isset($azmon))
                <input type="hidden" name="code" value="{{ $code }}">
            @endif

            {{-- Code --}}
            <div class="form-group">
                <label>کد آزمون</label>
                <input type="text" class="form-control" disabled 
                       value="{{ isset($azmon) ? $azmon->code : $code }}">
                <div class="help-text">دانشجو با این کد وارد آزمون می‌شود</div>
            </div>

            {{-- Title --}}
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label>عنوان آزمون <span class="required">*</span></label>
                <input type="text" class="form-control" name="title" 
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
                <textarea class="form-control" name="description" id="description"
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
                    <input type="number" class="form-control" name="num" 
                           min="1" max="100"
                           value="{{ old('num', isset($azmon) ? $azmon->num : 10) }}" 
                           required>
                    @if($errors->has('num'))
                        <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('num') }}</span>
                    @endif
                </div>
            </div>

            {{-- Sessions --}}
            <div class="form-group {{ $errors->has('sessions') ? 'has-error' : '' }}">
                <label>جلسات <span class="required">*</span></label>
                <select name="sessions[]" id="sessionsSelect" class="form-control" multiple required>
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
                <input type="number" class="form-control" name="time" 
                       min="1" max="300"
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
                    // دریافت مقادیر old
                    $oldStartDate = old('start_date', '');
                    $oldStartH = old('start_h', '');
                    $oldStartM = old('start_m', '');
                    $oldEndDate = old('end_date', '');
                    $oldEndH = old('end_h', '');
                    $oldEndM = old('end_m', '');
                    
                    // اگر در حالت ویرایش هستیم
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
                    <input type="text" class="date-input jalali-date" id="start-date"
                           name="start_date"
                           placeholder="تاریخ (مثال: 1402/01/01)"
                           data-jdp
                           value="{{ $oldStartDate }}">
                    <span class="separator">|</span>
                    <input type="number" class="time-input" id="start-h"
                           name="start_h"
                           min="0" max="23" placeholder="ساعت"
                           value="{{ $oldStartH }}">
                    <span class="separator">:</span>
                    <input type="number" class="time-input" id="start-m"
                           name="start_m"
                           min="0" max="59" placeholder="دقیقه"
                           value="{{ $oldStartM }}">
                </div>
                @if($errors->has('start_date') || $errors->has('start_h') || $errors->has('start_m'))
                    <span class="error-text"><i class="fas fa-times-circle"></i> 
                        @if($errors->has('start_date')) {{ $errors->first('start_date') }} @endif
                        @if($errors->has('start_h')) {{ $errors->first('start_h') }} @endif
                        @if($errors->has('start_m')) {{ $errors->first('start_m') }} @endif
                    </span>
                @endif

                {{-- End --}}
                <div class="datetime-group {{ $errors->has('end_date') || $errors->has('end_h') || $errors->has('end_m') ? 'has-error' : '' }}">
                    <span class="label-text"><i class="fas fa-stop-circle" style="color:#f44336;"></i> پایان:</span>
                    <input type="text" class="date-input jalali-date" id="end-date"
                           name="end_date"
                           placeholder="تاریخ (مثال: 1402/01/01)"
                           data-jdp
                           value="{{ $oldEndDate }}">
                    <span class="separator">|</span>
                    <input type="number" class="time-input" id="end-h"
                           name="end_h"
                           min="0" max="23" placeholder="ساعت"
                           value="{{ $oldEndH }}">
                    <span class="separator">:</span>
                    <input type="number" class="time-input" id="end-m"
                           name="end_m"
                           min="0" max="59" placeholder="دقیقه"
                           value="{{ $oldEndM }}">
                </div>
                @if($errors->has('end_date') || $errors->has('end_h') || $errors->has('end_m'))
                    <span class="error-text"><i class="fas fa-times-circle"></i> 
                        @if($errors->has('end_date')) {{ $errors->first('end_date') }} @endif
                        @if($errors->has('end_h')) {{ $errors->first('end_h') }} @endif
                        @if($errors->has('end_m')) {{ $errors->first('end_m') }} @endif
                    </span>
                @endif
                <div class="help-text">بازه زمانی که دانشجو می‌تواند در آزمون شرکت کند</div>
            </div>

            {{-- Settings --}}
            <div class="form-group">
                <label>تنظیمات نمایش</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="show_nomre" 
                               {{ old('show_nomre', isset($azmon) ? $azmon->show_nomre : false) ? 'checked' : '' }}>
                        <label>نمره آزمون به دانشجو نشان داده شود</label>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="show_ans" 
                               {{ old('show_ans', isset($azmon) ? $azmon->show_ans : false) ? 'checked' : '' }}>
                        <label>پاسخ سوالات به دانشجو نشان داده شود</label>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="changeable" 
                               {{ old('changeable', isset($azmon) ? $azmon->changeable : false) ? 'checked' : '' }}>
                        <label>دانشجو امکان تغییر پاسخ داشته باشد</label>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="show_remain" 
                               {{ old('show_remain', isset($azmon) ? $azmon->show_remain : false) ? 'checked' : '' }}>
                        <label>نمایش زمان باقیمانده به دانشجو</label>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="show_state" 
                               {{ old('show_state', isset($azmon) ? $azmon->show_state : false) ? 'checked' : '' }}>
                        <label>نمایش موقعیت سوال در حال پاسخگویی</label>
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
                    <a href="{{ route('azmon.delete', $azmon->id) }}" class="btn-submit btn-submit-danger"
                       onclick="return confirm('آیا مطمئن هستید؟')">
                        <i class="fas fa-trash-alt"></i> حذف
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/persian-date@latest/dist/persian-date.js"></script>
<script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.js"></script>

<script>
    $(document).ready(function() {
        $('#sessionsSelect').select2({
            placeholder: 'جلسات را انتخاب کنید',
            allowClear: true,
            width: '100%',
            dir: 'rtl',
            language: 'fa'
        });

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
    });

    // قبل از ارسال فرم، اعتبارسنجی کنید
    document.getElementById('azmonForm').addEventListener('submit', function(e) {
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
</script>
@endsection