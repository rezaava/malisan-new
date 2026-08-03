@extends('layout.master')

@section('title')
ملیسان | مدیریت نظرسنجی
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .status-toggle {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        cursor: pointer;
    }
    
    .status-toggle input {
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
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: #28a745;
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    
    .status-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    
    .status-badge.active {
        background-color: #28a745;
        color: white;
    }
    
    .status-badge.inactive {
        background-color: #dc3545;
        color: white;
    }
    
    .survey-stat-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .survey-stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-number {
        font-size: 32px;
        font-weight: bold;
        color: #4a6cf7;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 14px;
        margin-top: 5px;
    }
    
    .toggle-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 20px;
        background: white;
        border-radius: 12px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .toggle-container:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }
    
    .toggle-label {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .toggle-label i {
        font-size: 24px;
        color: #4a6cf7;
    }
    
    .toggle-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .toggle-description {
        font-size: 13px;
        color: #6c757d;
        margin-top: 2px;
    }
    
    .loading-spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #4a6cf7;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 10px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .survey-list {
        margin-top: 30px;
    }
    
    .survey-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        background: white;
        border-radius: 10px;
        margin-bottom: 10px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .survey-item:hover {
        background: #f8f9fa;
        transform: translateX(5px);
    }
    
    .survey-item .survey-title {
        font-weight: 500;
        color: #2c3e50;
    }
    
    .survey-item .survey-status {
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 20px;
    }
    
    .survey-item .survey-status.active {
        background: #d4edda;
        color: #155724;
    }
    
    .survey-item .survey-status.inactive {
        background: #f8d7da;
        color: #721c24;
    }
</style>
@endsection

@section('mohtava')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">مدیریت نظرسنجی‌ها</h4>
            <p class="text-muted">در این بخش می‌توانید وضعیت نظرسنجی‌های دانشجویان و اساتید را مدیریت کنید.</p>
        </div>
    </div>

    <!-- آمار -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="survey-stat-card">
                <div class="stat-number">{{ $totalSurveys ?? 0 }}</div>
                <div class="stat-label">تعداد کل نظرسنجی‌ها</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="survey-stat-card">
                <div class="stat-number">{{ $answeredSurveys ?? 0 }}</div>
                <div class="stat-label">نظرسنجی‌های پاسخ داده شده</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="survey-stat-card">
                <div class="stat-number">{{ ($totalSurveys ?? 0) - ($answeredSurveys ?? 0) }}</div>
                <div class="stat-label">نظرسنجی‌های پاسخ داده نشده</div>
            </div>
        </div>
    </div>

    <!-- تنظیمات فعال/غیرفعال کردن نظرسنجی‌ها -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">وضعیت نظرسنجی‌ها</h5>
                </div>
                <div class="card-body">
                    <!-- نظرسنجی دانشجو -->
                    <div class="toggle-container">
                        <div class="toggle-label">
                            <i class="fas fa-user-graduate"></i>
                            <div>
                                <div class="toggle-title">نظرسنجی دانشجویان</div>
                                <div class="toggle-description">
                                    فعال/غیرفعال کردن نظرسنجی از دانشجویان در زمان ورود
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="status-badge {{ $settings->enable_student_survey ? 'active' : 'inactive' }} me-3" id="studentStatusBadge">
                                {{ $settings->enable_student_survey ? 'فعال' : 'غیرفعال' }}
                            </span>
                            <label class="status-toggle">
                                <input type="checkbox" 
                                       id="studentSurveyToggle" 
                                       {{ $settings->enable_student_survey ? 'checked' : '' }}
                                       onchange="toggleSurvey('student', this)">
                                <span class="slider"></span>
                            </label>
                            <div class="loading-spinner" id="studentSpinner"></div>
                        </div>
                    </div>

                    <!-- نظرسنجی استاد -->
                    <div class="toggle-container">
                        <div class="toggle-label">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <div>
                                <div class="toggle-title">نظرسنجی اساتید</div>
                                <div class="toggle-description">
                                    فعال/غیرفعال کردن نظرسنجی از اساتید در زمان ورود
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="status-badge {{ $settings->enable_teacher_survey ? 'active' : 'inactive' }} me-3" id="teacherStatusBadge">
                                {{ $settings->enable_teacher_survey ? 'فعال' : 'غیرفعال' }}
                            </span>
                            <label class="status-toggle">
                                <input type="checkbox" 
                                       id="teacherSurveyToggle" 
                                       {{ $settings->enable_teacher_survey ? 'checked' : '' }}
                                       onchange="toggleSurvey('teacher', this)">
                                <span class="slider"></span>
                            </label>
                            <div class="loading-spinner" id="teacherSpinner"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- لیست نظرسنجی‌ها -->
    @if(isset($surveys) && $surveys->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">لیست نظرسنجی‌ها</h5>
                </div>
                <div class="card-body">
                    <div class="survey-list">
                        @foreach($surveys as $survey)
                        <div class="survey-item">
                            <div>
                                <span class="survey-title">{{ $survey->title ?? 'نظرسنجی' }}</span>
                                <span class="badge bg-secondary ms-2">نوع {{ $survey->type ?? 1 }}</span>
                            </div>
                            <span class="survey-status {{ $survey->active ? 'active' : 'inactive' }}">
                                {{ $survey->active ? 'فعال' : 'غیرفعال' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    // تنظیمات Toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-left",
        "timeOut": "3000",
        "rtl": true
    };

    /**
     * تغییر وضعیت نظرسنجی با AJAX
     */
    function toggleSurvey(type, element) {
        // نمایش اسپینر
        const spinnerId = type === 'student' ? 'studentSpinner' : 'teacherSpinner';
        const badgeId = type === 'student' ? 'studentStatusBadge' : 'teacherStatusBadge';
        document.getElementById(spinnerId).style.display = 'inline-block';
        
        // غیرفعال کردن چک‌باکس تا پایان درخواست
        element.disabled = true;

        // تعیین URL بر اساس نوع
        const url = type === 'student' 
            ? '{{ route("admin.toggle-student-survey") }}' 
            : '{{ route("admin.toggle-teacher-survey") }}';

        // ارسال درخواست AJAX
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            // مخفی کردن اسپینر
            document.getElementById(spinnerId).style.display = 'none';
            element.disabled = false;

            if (data.success) {
                // به‌روزرسانی وضعیت چک‌باکس
                element.checked = data.status;
                
                // به‌روزرسانی وضعیت نمایشی (Badge)
                const badge = document.getElementById(badgeId);
                if (data.status) {
                    badge.className = 'status-badge active';
                    badge.textContent = 'فعال';
                } else {
                    badge.className = 'status-badge inactive';
                    badge.textContent = 'غیرفعال';
                }

                // نمایش پیام موفقیت
                toastr.success(data.message);
            } else {
                // برگرداندن وضعیت قبلی چک‌باکس
                element.checked = !element.checked;
                toastr.error(data.message || 'خطا در تغییر وضعیت');
            }
        })
        .catch(error => {
            // مخفی کردن اسپینر
            document.getElementById(spinnerId).style.display = 'none';
            element.disabled = false;
            
            // برگرداندن وضعیت قبلی
            element.checked = !element.checked;
            
            console.error('Error:', error);
            toastr.error('خطا در ارتباط با سرور');
        });
    }

    // بارگذاری وضعیت اولیه (اختیاری)
    document.addEventListener('DOMContentLoaded', function() {
        // می‌توانید وضعیت اولیه را از سرور دریافت کنید
        fetch('{{ route("admin.get-settings") }}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // به‌روزرسانی وضعیت‌ها در صورت نیاز
                const studentToggle = document.getElementById('studentSurveyToggle');
                const teacherToggle = document.getElementById('teacherSurveyToggle');
                
                studentToggle.checked = data.data.enable_student_survey;
                teacherToggle.checked = data.data.enable_teacher_survey;
            }
        })
        .catch(error => console.error('Error loading settings:', error));
    });
</script>
@endsection