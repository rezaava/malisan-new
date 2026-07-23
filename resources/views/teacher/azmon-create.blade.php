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

            {{-- Description with Jodit Editor --}}
            <div class="form-group">
                <label>توضیحات (اختیاری)</label>
                <textarea class="jodit-editor" id="descriptionEditor" name="description" 
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

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/persian-date@latest/dist/persian-date.js"></script>
<script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>
    $(document).ready(function() {
        // Select2
        $('#sessionsSelect').select2({
            placeholder: 'جلسات را انتخاب کنید',
            allowClear: true,
            width: '100%',
            dir: 'rtl',
            language: 'fa'
        });

        // Persian Datepicker
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

    // مقداردهی Jodit Editor
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.jodit-editor').forEach(function(element) {
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
        });
    });

    // قبل از ارسال فرم، اعتبارسنجی
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