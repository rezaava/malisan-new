@extends('layout.master')
@section('title')
    ملیسان | {{ isset($azmon) ? 'ویرایش' : 'ایجاد' }} آزمون
@endsection
@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
    <link rel="stylesheet" href="{{ asset('css/azmon-create.css') }}">
    <style>
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

.time-limit-description {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 12px;
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
        .settings-group {
            margin-top: 25px
        }

        .settings-title {
            display: block;
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 14px
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px
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
            direction: rtl
        }

        .setting-card:hover {
            border-color: #b8c7d9;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .06);
            transform: translateY(-1px)
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
            font-size: 17px
        }

        .setting-content {
            flex: 1;
            min-width: 0
        }

        .setting-label {
            display: block;
            color: #1f2937;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px
        }

        .setting-content small {
            display: block;
            color: #8a94a6;
            font-size: 11px;
            line-height: 1.7
        }

        .setting-switch {
            position: relative;
            flex: 0 0 auto;
            width: 44px;
            height: 24px
        }

        .setting-switch input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0
        }

        .switch-slider {
            position: absolute;
            inset: 0;
            background: #d1d5db;
            border-radius: 30px;
            transition: .25s ease
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
            box-shadow: 0 1px 4px rgba(0, 0, 0, .2);
            transition: .25s ease
        }

        .setting-switch input:checked+.switch-slider {
            background: #2563eb
        }

        .setting-switch input:checked+.switch-slider::before {
            transform: translateX(-20px)
        }

        .setting-card:has(input:checked) {
            border-color: #93c5fd;
            background: #f8fbff
        }

        .setting-card:has(input:checked) .setting-icon {
            background: #dbeafe;
            color: #2563eb
        }

        .time-limit-box {
            margin-top: 18px;
            padding: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #f8fafc
        }

        .time-limit-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px
        }

        .time-limit-title {
            font-size: 15px;
            font-weight: 700;
            color: #1f2937
        }

        .time-limit-description {
            font-size: 12px;
            color: #8a94a6;
            margin-top: 4px
        }

        .time-limit-options {
            display: none;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb
        }

        .time-limit-options.active {
            display: block
        }

        .time-type-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px
        }

        .time-type-option {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            background: #fff;
            cursor: pointer
        }

        .time-type-option input {
            margin: 0
        }

        .time-type-option:has(input:checked) {
            border-color: #2563eb;
            background: #eff6ff
        }

        .time-input-box {
            display: none
        }

        .time-input-box.active {
            display: block
        }

        @media(max-width:768px) {
            .settings-grid {
                grid-template-columns: 1fr
            }

            .time-limit-header {
                align-items: flex-start
            }
        }
    </style>
@endsection
@section('mohtava')
    <div class="azmon-form-container">
        <div class="azmon-form-card">
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
            @if ($errors->any())
                <div class="alert-danger-custom">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>خطا!</strong> لطفاً خطاهای زیر را برطرف کنید:
                        <ul style="margin:4px 0 0 20px;padding:0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <form method="POST"
                action="{{ isset($azmon) ? '/teacher/courses/azmon/edit/' . $azmon->id : '/teacher/courses/azmon/create' }}"
                enctype="multipart/form-data" id="azmonForm">
                @csrf
                @if (isset($azmon))
                    @method('PUT')
                @endif
                <input type="hidden" name="id" value="{{ $course->id }}">
                <div class="form-group {{ $errors->has('access_type') ? 'has-error' : '' }}">
                    <label>نحوه دسترسی به آزمون <span class="required">*</span></label>
                    <select class="form-control" name="access_type" id="accessType" required>
                        <option value="code"
                            {{ old('access_type', isset($azmon) ? ($azmon->code ? 'code' : 'free') : 'code') == 'code' ? 'selected' : '' }}>
                            با در اختیار داشتن کد آزمون</option>
                        <option value="free"
                            {{ old('access_type', isset($azmon) ? ($azmon->code ? 'code' : 'free') : 'code') == 'free' ? 'selected' : '' }}>
                            آزاد و بدون نیاز به کد آزمون</option>
                    </select>
                    @if ($errors->has('access_type'))
                        <span class="error-text"><i class="fas fa-times-circle"></i>
                            {{ $errors->first('access_type') }}</span>
                    @endif
                </div>
                <div class="form-group" id="codeFieldGroup">
                    <label>کد آزمون</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="examCode" name="code"
                            value="{{ isset($azmon) ? $azmon->code : $code }}" readonly
                            style="background-color:#f8f9fa;direction:ltr;text-align:center;font-weight:bold;letter-spacing:5px;">
                        <button type="button" class="btn btn-outline-primary rounded" id="generateCodeBtn">
                            <i class="fas fa-sync-alt"></i> تولید مجدد
                        </button>
                    </div>
                    <div class="help-text">کد ۵ رقمی که دانشجو برای ورود به آزمون نیاز دارد</div>
                    @if ($errors->has('code'))
                        <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('code') }}</span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label>عنوان آزمون <span class="required">*</span></label>
                    <input type="text" class="form-control" name="title" placeholder="مثال: ریاضی - فصل اول"
                        value="{{ old('title', isset($azmon) ? $azmon->title : '') }}" required>
                    @if ($errors->has('title'))
                        <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('title') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label>توضیحات (اختیاری)</label>
                    <textarea class="jodit-editor" id="descriptionEditor" name="description" placeholder="توضیحات آزمون را وارد کنید...">{{ old('description', isset($azmon) ? $azmon->description : '') }}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group {{ $errors->has('sath') ? 'has-error' : '' }}">
                        <label>سطح سوالات <span class="required">*</span></label>
                        <select class="form-control" name="sath" required>
                            <option value="3"
                                {{ old('sath', isset($azmon) ? $azmon->sath : 3) == 3 ? 'selected' : '' }}>عالی و خوب
                            </option>
                            <option value="1"
                                {{ old('sath', isset($azmon) ? $azmon->sath : 3) == 1 ? 'selected' : '' }}>عالی</option>
                            <option value="2"
                                {{ old('sath', isset($azmon) ? $azmon->sath : 3) == 2 ? 'selected' : '' }}>خوب</option>
                            <option value="4"
                                {{ old('sath', isset($azmon) ? $azmon->sath : 3) == 4 ? 'selected' : '' }}>سوالات ستاره‌دار
                            </option>
                            <option value="5"
                                {{ old('sath', isset($azmon) ? $azmon->sath : 3) == 5 ? 'selected' : '' }}>فقط سوالات استاد
                            </option>
                        </select>
                        @if ($errors->has('sath'))
                            <span class="error-text"><i class="fas fa-times-circle"></i>
                                {{ $errors->first('sath') }}</span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('num') ? 'has-error' : '' }}">
                        <label>تعداد سوالات <span class="required">*</span></label>
                        <input type="number" class="form-control" name="num" min="1" max="100"
                            value="{{ old('num', isset($azmon) ? $azmon->num : 10) }}" required>
                        @if ($errors->has('num'))
                            <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('num') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="type">نوع آزمون <span class="required">*</span></label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="periodic" @selected(old('type', isset($azmon) ? $azmon->type : 'periodic') === 'periodic')>دوره ای</option>
                            <option value="mid-term" @selected(old('type', isset($azmon) ? $azmon->type : 'periodic') === 'mid-term')>میان ترم</option>
                            <option value="final" @selected(old('type', isset($azmon) ? $azmon->type : 'periodic') === 'final')>پایانی</option>
                        </select>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('sessions') ? 'has-error' : '' }}">
                    <label>جلسات <span class="required">*</span></label>
                    <select name="sessions[]" id="sessionsSelect" class="form-control" multiple required>
                        <option value="all" @if (in_array('all', old('sessions', isset($selectedSessions) ? $selectedSessions : []))) selected @endif>تمام جلسات</option>
                        @foreach ($sessions as $session)
                            <option value="{{ $session->id }}" @if (in_array($session->id, old('sessions', isset($selectedSessions) ? $selectedSessions : []))) selected @endif>
                                {{ $session->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('sessions'))
                        <span class="error-text"><i class="fas fa-times-circle"></i>
                            {{ $errors->first('sessions') }}</span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('time') ? 'has-error' : '' }}">
                    <label>زمان پیش‌فرض آزمون (دقیقه) <span class="required">*</span></label>
                    <input type="number" class="form-control" name="time" min="1" max="300"
                        value="{{ old('time', isset($azmon) ? $azmon->time : 60) }}" required>
                    <div class="help-text">اگر محدودیت زمانی اختصاصی فعال نباشد، همین زمان برای کل آزمون استفاده می‌شود.
                    </div>
                    @if ($errors->has('time'))
                        <span class="error-text"><i class="fas fa-times-circle"></i> {{ $errors->first('time') }}</span>
                    @endif
                </div>
<div class="quiz-time-section {{ old('time_limit_khod', isset($azmon) ? $azmon->time_limit_khod : 0) ? 'active' : '' }}"
    id="timeSection">

    <div class="section-header">
        <i class="fas fa-clock"></i>
        <span>تنظیمات محدودیت زمانی</span>

        <span class="badge-time" id="statusBadge">
            {{ old('time_limit_khod', isset($azmon) ? $azmon->time_limit_khod : 0) ? 'فعال' : 'غیرفعال' }}
        </span>

        <label class="setting-switch">
            <input type="checkbox"
                id="timeLimitKhod"
                name="time_limit_khod"
                value="1"
                {{ old('time_limit_khod', isset($azmon) ? $azmon->time_limit_khod : 0) ? 'checked' : '' }}>
            <span class="switch-slider"></span>
        </label>
    </div>

    <div class="time-limit-description">
        می‌توانید زمان آزمون را به صورت کل آزمون یا برای هر سؤال جداگانه تعیین کنید.
    </div>

    <div class="quiz-time-type"
        id="timeTypeGroup"
        style="{{ old('time_limit_khod', isset($azmon) ? $azmon->time_limit_khod : 0) ? '' : 'opacity: 0.5; pointer-events: none;' }}">

        <label class="time-option
            {{ old('time_type', isset($azmon) ? $azmon->time_type : 'per_question') === 'per_question' ? 'selected' : '' }}"
            id="perQuestionOption">

            <input type="radio"
                name="time_type"
                value="per_question"
                {{ old('time_type', isset($azmon) ? $azmon->time_type : 'per_question') === 'per_question' ? 'checked' : '' }}>

            <span class="option-icon">⏱️</span>

            <div>
                <div class="option-label">به ازای هر سؤال</div>
                <div class="option-desc">تنظیم زمان برای هر سؤال</div>
            </div>
        </label>

        <label class="time-option
            {{ old('time_type', isset($azmon) ? $azmon->time_type : 'per_question') === 'total' ? 'selected' : '' }}"
            id="totalOption">

            <input type="radio"
                name="time_type"
                value="total"
                {{ old('time_type', isset($azmon) ? $azmon->time_type : 'per_question') === 'total' ? 'checked' : '' }}>

            <span class="option-icon">⏳</span>

            <div>
                <div class="option-label">کل آزمون</div>
                <div class="option-desc">تنظیم زمان برای کل آزمون</div>
            </div>
        </label>
    </div>

    <div id="per-question-time"
        class="quiz-time-input
        {{ old('time_type', isset($azmon) ? $azmon->time_type : 'per_question') === 'per_question' ? 'visible' : '' }}"
        style="{{ old('time_limit_khod', isset($azmon) ? $azmon->time_limit_khod : 0) ? '' : 'opacity: 0.5;' }}">

        <div class="input-row">
            <label>زمان هر سؤال:</label>

            <input type="number"
                name="time_per_question"
                id="time_per_question"
                value="{{ old('time_per_question', isset($azmon) ? $azmon->time_per_question : 45) }}"
                class="form-input"
                min="1"
                max="300"
                {{ old('time_limit_khod', isset($azmon) ? $azmon->time_limit_khod : 0) ? '' : 'disabled' }}>

            <span class="unit">ثانیه</span>

            <span class="hint">
                زمان اختصاص داده شده به هر سؤال
            </span>
        </div>

        <div class="quiz-time-calc">
            <i class="fas fa-calculator"></i>

            <span>
                زمان کل تقریبی:
            </span>

            <strong id="perQuestionResult">-</strong>
        </div>
    </div>

    <div id="total-time"
        class="quiz-time-input
        {{ old('time_type', isset($azmon) ? $azmon->time_type : 'per_question') === 'total' ? 'visible' : '' }}"
        style="{{ old('time_limit_khod', isset($azmon) ? $azmon->time_limit_khod : 0) ? '' : 'opacity: 0.5;' }}">

        <div class="input-row">
            <label>زمان کل آزمون:</label>

            <input type="number"
                name="total_time_limit"
                id="total_time_limit"
                value="{{ old('total_time_limit', isset($azmon) ? $azmon->total_time_limit : 60) }}"
                class="form-input"
                min="1"
                max="3000"
                {{ old('time_limit_khod', isset($azmon) ? $azmon->time_limit_khod : 0) ? '' : 'disabled' }}>

            <span class="unit">دقیقه</span>

            <span class="hint">
                زمان کلی برای پاسخگویی به تمام سوالات
            </span>
        </div>

        <div class="quiz-time-calc">
            <i class="fas fa-calculator"></i>

            <span>
                میانگین زمان هر سؤال:
            </span>

            <strong>
                <span id="avg-time-per-question">-</span>
                ثانیه
            </strong>

            <span class="calc-result" id="totalResult">
                -
            </span>
        </div>
    </div>

    @if ($errors->has('time_limit_khod'))
        <span class="error-text">
            <i class="fas fa-times-circle"></i>
            {{ $errors->first('time_limit_khod') }}
        </span>
    @endif

    @if ($errors->has('time_type'))
        <span class="error-text">
            <i class="fas fa-times-circle"></i>
            {{ $errors->first('time_type') }}
        </span>
    @endif

    @if ($errors->has('total_time_limit'))
        <span class="error-text">
            <i class="fas fa-times-circle"></i>
            {{ $errors->first('total_time_limit') }}
        </span>
    @endif

    @if ($errors->has('time_per_question'))
        <span class="error-text">
            <i class="fas fa-times-circle"></i>
            {{ $errors->first('time_per_question') }}
        </span>
    @endif
</div>
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
                    <div class="datetime-group {{ $errors->has('start_date') || $errors->has('start_h') || $errors->has('start_m') ? 'has-error' : '' }}"
                        style="margin-bottom:12px;">
                        <span class="label-text"><i class="fas fa-play-circle" style="color:#4caf50;"></i> شروع:</span>
                        <input type="text" class="date-input jalali-date" id="start-date" name="start_date"
                            placeholder="تاریخ (مثال: 1402/01/01)" data-jdp value="{{ $oldStartDate }}">
                        <span class="separator">|</span>
                        <input type="number" class="time-input" id="start-h" name="start_h" min="0"
                            max="23" placeholder="ساعت" value="{{ $oldStartH }}">
                        <span class="separator">:</span>
                        <input type="number" class="time-input" id="start-m" name="start_m" min="0"
                            max="59" placeholder="دقیقه" value="{{ $oldStartM }}">
                    </div>
                    @if ($errors->has('start_date') || $errors->has('start_h') || $errors->has('start_m'))
                        <span class="error-text"><i class="fas fa-times-circle"></i>
                            @if ($errors->has('start_date'))
                                {{ $errors->first('start_date') }}
                            @endif
                            @if ($errors->has('start_h'))
                                {{ $errors->first('start_h') }}
                            @endif
                            @if ($errors->has('start_m'))
                                {{ $errors->first('start_m') }}
                            @endif
                        </span>
                    @endif
                    <div
                        class="datetime-group {{ $errors->has('end_date') || $errors->has('end_h') || $errors->has('end_m') ? 'has-error' : '' }}">
                        <span class="label-text"><i class="fas fa-stop-circle" style="color:#f44336;"></i> پایان:</span>
                        <input type="text" class="date-input jalali-date" id="end-date" name="end_date"
                            placeholder="تاریخ (مثال: 1402/01/01)" data-jdp value="{{ $oldEndDate }}">
                        <span class="separator">|</span>
                        <input type="number" class="time-input" id="end-h" name="end_h" min="0"
                            max="23" placeholder="ساعت" value="{{ $oldEndH }}">
                        <span class="separator">:</span>
                        <input type="number" class="time-input" id="end-m" name="end_m" min="0"
                            max="59" placeholder="دقیقه" value="{{ $oldEndM }}">
                    </div>
                    @if ($errors->has('end_date') || $errors->has('end_h') || $errors->has('end_m'))
                        <span class="error-text"><i class="fas fa-times-circle"></i>
                            @if ($errors->has('end_date'))
                                {{ $errors->first('end_date') }}
                            @endif
                            @if ($errors->has('end_h'))
                                {{ $errors->first('end_h') }}
                            @endif
                            @if ($errors->has('end_m'))
                                {{ $errors->first('end_m') }}
                            @endif
                        </span>
                    @endif
                    <div class="help-text">بازه زمانی که دانشجو می‌تواند در آزمون شرکت کند</div>
                </div>
                <div class="form-group settings-group">
                    <label class="settings-title">تنظیمات نمایش</label>
                    <div class="settings-grid">
                        <label class="setting-card">
                            <div class="setting-icon"><i class="fas fa-star"></i></div>
                            <div class="setting-content">
                                <span class="setting-label">نمایش نمره آزمون</span>
                                <small>نمره آزمون به دانشجو نشان داده شود</small>
                            </div>
                            <div class="setting-switch">
                                <input type="checkbox" name="show_nomre"
                                    {{ old('show_nomre', isset($azmon) ? $azmon->show_nomre : false) ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                            </div>
                        </label>
                        <label class="setting-card">
                            <div class="setting-icon"><i class="fas fa-check-circle"></i></div>
                            <div class="setting-content">
                                <span class="setting-label">نمایش پاسخ سوالات</span>
                                <small>پاسخ سوالات به دانشجو نشان داده شود</small>
                            </div>
                            <div class="setting-switch">
                                <input type="checkbox" name="show_ans"
                                    {{ old('show_ans', isset($azmon) ? $azmon->show_ans : false) ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                            </div>
                        </label>
                        <label class="setting-card">
                            <div class="setting-icon"><i class="fas fa-edit"></i></div>
                            <div class="setting-content">
                                <span class="setting-label">تغییر پاسخ</span>
                                <small>دانشجو امکان تغییر پاسخ داشته باشد</small>
                            </div>
                            <div class="setting-switch">
                                <input type="checkbox" name="changeable"
                                    {{ old('changeable', isset($azmon) ? $azmon->changeable : false) ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                            </div>
                        </label>
                        <label class="setting-card">
                            <div class="setting-icon"><i class="fas fa-clock"></i></div>
                            <div class="setting-content">
                                <span class="setting-label">زمان باقیمانده</span>
                                <small>نمایش زمان باقیمانده به دانشجو</small>
                            </div>
                            <div class="setting-switch">
                                <input type="checkbox" name="show_remain"
                                    {{ old('show_remain', isset($azmon) ? $azmon->show_remain : false) ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                            </div>
                        </label>
                        <label class="setting-card">
                            <div class="setting-icon"><i class="fas fa-list-ol"></i></div>
                            <div class="setting-content">
                                <span class="setting-label">موقعیت سوال</span>
                                <small>نمایش موقعیت سوال در حال پاسخگویی</small>
                            </div>
                            <div class="setting-switch">
                                <input type="checkbox" name="show_state"
                                    {{ old('show_state', isset($azmon) ? $azmon->show_state : false) ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-submit btn-submit-primary" id="submitBtn">
                        <i class="fas {{ isset($azmon) ? 'fa-save' : 'fa-plus' }}"></i>
                        {{ isset($azmon) ? 'بروزرسانی آزمون' : 'ایجاد آزمون' }}
                    </button>
                    <a href="{{ route('azmon.list', ['id' => $course->id]) }}" class="btn-submit btn-submit-outline">
                        <i class="fas fa-arrow-right"></i> بازگشت به لیست
                    </a>
                    @if (isset($azmon))
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
                for (let i = 0; i < 5; i++) code += chars.charAt(Math.floor(Math.random() * chars.length));
                return code;
            }

            function toggleCodeField() {
                if (accessType.value === 'code') {
                    codeFieldGroup.style.display = 'block';
                    if (!examCodeInput.value) examCodeInput.value = generateRandomCode();
                } else {
                    codeFieldGroup.style.display = 'none';
                    examCodeInput.value = '';
                }
            }
            toggleCodeField();
            accessType.addEventListener('change', toggleCodeField);
            generateBtn.addEventListener('click', function() {
                if (accessType.value === 'code') examCodeInput.value = generateRandomCode();
            });
const timeLimitKhod = document.getElementById('timeLimitKhod');
const timeSection = document.getElementById('timeSection');
const timeTypeGroup = document.getElementById('timeTypeGroup');
const statusBadge = document.getElementById('statusBadge');

const perQuestionOption = document.getElementById('perQuestionOption');
const totalOption = document.getElementById('totalOption');

const perQuestionTime = document.getElementById('per-question-time');
const totalTime = document.getElementById('total-time');

const perQuestionInput = document.getElementById('time_per_question');
const totalTimeInput = document.getElementById('total_time_limit');

const qNumInput = document.querySelector('input[name="num"]');

function updateTimeCalculations() {
    const qNum = parseInt(qNumInput?.value) || 10;
    const timeType = document.querySelector('input[name="time_type"]:checked');

    if (!timeType) return;

    if (timeType.value === 'per_question') {
        const timePerQuestion = parseInt(perQuestionInput.value) || 45;

        const totalSeconds = qNum * timePerQuestion;
        const totalMinutes = Math.ceil(totalSeconds / 60);

        document.getElementById('perQuestionResult').textContent =
            totalMinutes + ' دقیقه (' + totalSeconds + ' ثانیه)';

    } else {
        const totalMinutes = parseInt(totalTimeInput.value) || 60;

        const avgSeconds = Math.round(
            (totalMinutes * 60) / qNum
        );

        document.getElementById('avg-time-per-question').textContent =
            avgSeconds;

        document.getElementById('totalResult').textContent =
            totalMinutes + ' دقیقه';
    }
}

function updateTimeLimitUI() {
    const enabled = timeLimitKhod.checked;

    if (enabled) {
        timeSection.classList.add('active');
        statusBadge.textContent = 'فعال';

        timeTypeGroup.style.opacity = '1';
        timeTypeGroup.style.pointerEvents = 'auto';

        perQuestionInput.disabled = false;
        totalTimeInput.disabled = false;

        perQuestionTime.style.opacity = '1';
        totalTime.style.opacity = '1';

        const selected = document.querySelector(
            'input[name="time_type"]:checked'
        );

        if (selected && selected.value === 'total') {
            perQuestionTime.classList.remove('visible');
            totalTime.classList.add('visible');

            perQuestionOption.classList.remove('selected');
            totalOption.classList.add('selected');
        } else {
            perQuestionTime.classList.add('visible');
            totalTime.classList.remove('visible');

            perQuestionOption.classList.add('selected');
            totalOption.classList.remove('selected');
        }
    } else {
        timeSection.classList.remove('active');
        statusBadge.textContent = 'غیرفعال';

        timeTypeGroup.style.opacity = '0.5';
        timeTypeGroup.style.pointerEvents = 'none';

        perQuestionTime.style.opacity = '0.5';
        totalTime.style.opacity = '0.5';

        perQuestionInput.disabled = true;
        totalTimeInput.disabled = true;

        perQuestionTime.classList.remove('visible');
        totalTime.classList.remove('visible');
    }

    updateTimeCalculations();
}

timeLimitKhod.addEventListener('change', updateTimeLimitUI);

document.querySelectorAll('input[name="time_type"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.time-option').forEach(function (option) {
            option.classList.remove('selected');
        });

        if (this.value === 'per_question') {
            perQuestionOption.classList.add('selected');

            perQuestionTime.classList.add('visible');
            totalTime.classList.remove('visible');
        } else {
            totalOption.classList.add('selected');

            totalTime.classList.add('visible');
            perQuestionTime.classList.remove('visible');
        }

        updateTimeCalculations();
    });
});

if (qNumInput) {
    qNumInput.addEventListener('input', updateTimeCalculations);
    qNumInput.addEventListener('change', updateTimeCalculations);
}

perQuestionInput.addEventListener('input', updateTimeCalculations);
perQuestionInput.addEventListener('change', updateTimeCalculations);

totalTimeInput.addEventListener('input', updateTimeCalculations);
totalTimeInput.addEventListener('change', updateTimeCalculations);

updateTimeLimitUI();
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
                            if ($(this).val() !== 'all') allIds.push($(this).val());
                        });
                        $(this).val(allIds).trigger('change');
                    }
                });
                $('#sessionsSelect').on('select2:unselect', function(e) {
                    if (e.params.data.id === 'all') $(this).val([]).trigger('change');
                });
                $('#sessionsSelect').on('select2:selecting', function(e) {
                    var currentVal = $(this).val() || [];
                    var selectedId = e.params.args.data.id;
                    if (currentVal.includes('all') && selectedId !== 'all') $(this).val([]).trigger(
                        'change');
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
                    if (!element.id) element.id = editorId;
                    new Jodit('#' + editorId, {
                        width: '100%',
                        height: 250,
                        allowResize: true,
                        allowResizeImages: true,
                        direction: 'rtl',
                        buttons: [
                            'source', '|', 'undo', 'redo', '|', 'bold', 'italic', 'underline',
                            'strikethrough', '|',
                            'font', 'fontsize', 'brush', 'paragraph', '|', 'ul', 'ol',
                            'outdent', 'indent', '|',
                            'align', 'hr', 'table', '|', 'link', 'unlink',
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
                                        if (!file) return;
                                        let formData = new FormData();
                                        formData.append('file', file);
                                        fetch('{{ route('upload.image') }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: formData
                                        }).then(function(res) {
                                            return res.json();
                                        }).then(function(data) {
                                            if (data.files && data.files[0]
                                                .url) {
                                                let img = document
                                                    .createElement('img');
                                                img.src = data.files[0].url;
                                                img.style.maxWidth = '100%';
                                                editor.s.insertNode(img);
                                            } else {
                                                alert('خطا در آپلود تصویر');
                                            }
                                        }).catch(function(err) {
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
                                        if (!file) return;
                                        let formData = new FormData();
                                        formData.append('file', file);
                                        fetch('{{ route('upload.video') }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: formData
                                        }).then(function(res) {
                                            return res.json();
                                        }).then(function(data) {
                                            if (data.files && data.files[0]
                                                .url) {
                                                let wrapper = document
                                                    .createElement('div');
                                                wrapper.classList.add(
                                                    'video-wrapper');
                                                let video = document
                                                    .createElement('video');
                                                video.setAttribute('controls',
                                                    '');
                                                video.src = data.files[0].url;
                                                video.style.maxWidth = '100%';
                                                wrapper.appendChild(video);
                                                editor.s.insertNode(wrapper);
                                            } else {
                                                alert('خطا در آپلود ویدیو');
                                            }
                                        }).catch(function(err) {
                                            alert('Upload error: ' + err);
                                        });
                                    };
                                    input.click();
                                }
                            },
                            '|', 'symbols', 'emoticons', '|', 'print', 'fullsize', 'preview'
                        ],
                        defaultFont: 'Vazir, Tahoma, Arial, sans-serif',
                        defaultFontSize: '14px',
                        fonts: ['Vazir', 'Tahoma', 'Arial', 'Courier New']
                    });
                }
            });
            const form = document.getElementById('azmonForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const startDate = document.getElementById('start-date').value.trim();
                    const endDate = document.getElementById('end-date').value.trim();
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
                    if (timeLimitKhod.checked) {
                        const selected = document.querySelector('input[name="time_type"]:checked');
                        if (!selected) {
                            e.preventDefault();
                            alert('لطفاً نوع محدودیت زمانی را انتخاب کنید.');
                            return false;
                        }
                        if (selected.value === 'total' && (!totalTimeInput.value || parseInt(totalTimeInput
                                .value) < 1)) {
                            e.preventDefault();
                            alert('لطفاً زمان کل آزمون را وارد کنید.');
                            return false;
                        }
                        if (selected.value === 'per_question' && (!perQuestionInput.value || parseInt(
                                perQuestionInput.value) < 1)) {
                            e.preventDefault();
                            alert('لطفاً زمان هر سؤال را وارد کنید.');
                            return false;
                        }
                    }
                });
            }
        });
    </script>
@endsection