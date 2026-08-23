@extends('layout.master')

@section('title')
ملیسان | مدیریت نظرسنجی
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>

    /* استایل‌های اختصاصی */
    .status-toggle {
        position: relative;
        display: inline-block;
        width: 56px;
        height: 30px;
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
        background-color: #ced4da;
        transition: .3s;
        border-radius: 30px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    input:checked + .slider {
        background-color: #0d6efd;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
    }
    .status-badge.active {
        background-color: #d1e7dd;
        color: #0a3622;
    }
    .status-badge.inactive {
        background-color: #f8d7da;
        color: #58151c;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 22px 20px;
        border: none;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.10);
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 12px;
    }
    .stat-icon.blue { background: linear-gradient(135deg, #4a6cf7, #6a8cff); }
    .stat-icon.green { background: linear-gradient(135deg, #28a745, #34ce57); }
    .stat-icon.orange { background: linear-gradient(135deg, #ffc107, #ffca2c); }
    
    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #1a2332;
        line-height: 1.2;
    }
    .stat-label {
        color: #6c757d;
        font-size: 14px;
        font-weight: 500;
        margin-top: 4px;
    }
    
    .toggle-container {
        background: #f8f9fa;
        border-radius: 14px;
        padding: 16px 22px;
        margin-bottom: 14px;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    .toggle-container:hover {
        background: white;
        border-color: #dee2e6;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .toggle-label i {
        font-size: 22px;
        color: #0d6efd;
        width: 40px;
        text-align: center;
    }
    .toggle-title {
        font-size: 15px;
        font-weight: 600;
        color: #1a2332;
    }
    .toggle-description {
        font-size: 13px;
        color: #6c757d;
    }
    
    .loading-spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 3px solid #e9ecef;
        border-top: 3px solid #0d6efd;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-left: 12px;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .survey-text {
        max-width: 280px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        vertical-align: middle;
    }
    
    .badge-type {
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 50px;
        font-weight: 500;
    }
    
    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
        color: white;
        background: #0d6efd;
    }
    .action-btn:hover {
        transform: scale(1.05);
        opacity: 0.9;
        color: white;
    }
    .action-btn.info {
        background: #0dcaf0;
    }
    .action-btn.info:hover {
        background: #0bb5d8;
    }
    
    .table > :not(caption) > * > * {
        padding: 12px 12px;
        vertical-align: middle;
    }
    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 13px;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .table tbody tr {
        transition: background 0.15s;
    }
    .table tbody tr:hover {
        background-color: #f8f9ff;
    }
    
    .card-header {
        background: transparent;
        border-bottom: 1px solid #e9ecef;
        padding: 18px 24px;
    }
    .card {
        border: none;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border-radius: 16px;
        overflow: hidden;
    }
    .card-body {
        padding: 24px;
    }
    
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 20px 24px;
    }
    .modal-body {
        padding: 24px;
    }
    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 16px 24px;
    }
    
    .detail-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 16px;
    }
    .detail-section h6 {
        font-weight: 600;
        color: #1a2332;
        margin-bottom: 6px;
        font-size: 14px;
    }
    .detail-section p {
        margin: 0;
        color: #212529;
    }
    
    .empty-state {
        padding: 40px 20px;
        text-align: center;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 12px;
        opacity: 0.3;
    }

    .category-card {
        background: white;
        border-radius: 16px;
        padding: 24px 20px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        border-color: #0d6efd;
    }
    .category-card .cat-icon {
        font-size: 32px;
        color: #0d6efd;
        margin-bottom: 12px;
    }
    .category-card .cat-name {
        font-size: 18px;
        font-weight: 600;
        color: #1a2332;
    }
    .category-card .cat-count {
        font-size: 14px;
        color: #6c757d;
        margin-top: 4px;
    }
    .category-card .cat-count span {
        background: #e9ecef;
        padding: 2px 10px;
        border-radius: 30px;
        font-weight: 600;
        color: #0d6efd;
    }

    .back-btn {
        margin-bottom: 20px;
    }
</style>
@endsection

@section('mohtava')
<div class="container-fluid py-4">
    <!-- عنوان صفحه -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold">مدیریت نظرسنجی‌ها</h4>
                    <p class="text-muted mb-0">
                        @if(isset($selectedCategory))
                            {{ $selectedCategory->name }}
                        @else
                            مدیریت و بررسی نظرسنجی‌ها بر اساس دسته‌بندی
                        @endif
                    </p>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill">
                    <i class="fas fa-poll me-2"></i> 
                    @if(isset($selectedCategory))
                        {{ $surveys->count() }} نظرسنجی
                    @else
                        {{ $categories->count() }} دسته
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- تنظیمات فعال/غیرفعال -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-toggle-on me-2 text-primary"></i> وضعیت نظرسنجی‌ها
                    </h5>
                </div>
                <div class="card-body">
                    <!-- نظرسنجی دانشجو -->
                    <div class="toggle-container d-flex flex-wrap align-items-center justify-content-between">
                        <div class="toggle-label d-flex align-items-center gap-3">
                            <i class="fas fa-user-graduate"></i>
                            <div>
                                <div class="toggle-title">نظرسنجی دانشجویان</div>
                                <div class="toggle-description">فعال/غیرفعال کردن نظرسنجی از دانشجویان در زمان ورود</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-2 mt-sm-0">
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
                    <div class="toggle-container d-flex flex-wrap align-items-center justify-content-between">
                        <div class="toggle-label d-flex align-items-center gap-3">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <div>
                                <div class="toggle-title">نظرسنجی اساتید</div>
                                <div class="toggle-description">فعال/غیرفعال کردن نظرسنجی از اساتید در زمان ورود</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-2 mt-sm-0">
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

    <!-- بخش اصلی: نمایش دسته‌بندی‌ها یا نظرسنجی‌ها -->
    @if(isset($categoryId) && isset($selectedCategory))
        <!-- نمایش نظرسنجی‌های دسته انتخاب‌شده -->
        <div class="row">
            <div class="col-12">
                <div class="back-btn">
                    <a href="{{ route('admin_survey') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-1"></i> بازگشت به دسته‌بندی‌ها
                    </a>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-list me-2 text-primary"></i> نظرسنجی‌های دسته «{{ $selectedCategory->name }}»
                        </h5>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                            <i class="fas fa-database me-1"></i> {{ $surveys->count() }} مورد
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>متن نظرسنجی</th>
                                        <th style="width: 120px;">نوع</th>
                                        <th style="width: 120px;">وضعیت</th>
                                        <th style="width: 140px;">تاریخ ایجاد</th>
                                        <th style="width: 70px;">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($surveys as $survey)
                                    <tr>
                                        <td class="fw-bold text-muted">{{ $survey->id }}</td>
                                        <td>
                                            <span class="survey-text" title="{{ $survey->text }}">
                                                {{ Str::limit($survey->text, 55) }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($survey->type)
                                                @case(1)
                                                    <span class="badge bg-info bg-opacity-15 text-info badge-type">متن باز</span>
                                                    @break
                                                @case(2)
                                                    <span class="badge bg-primary bg-opacity-15 text-primary badge-type">تک‌انتخابی</span>
                                                    @break
                                                @case(3)
                                                    <span class="badge bg-warning bg-opacity-15 text-warning badge-type">چندانتخابی</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary bg-opacity-15 text-secondary badge-type">نامشخص</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $survey->active ? 'active' : 'inactive' }}">
                                                {{ $survey->active ? 'فعال' : 'غیرفعال' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted" style="font-size: 13px;">
                                                {{ \Hekmatinasser\Verta\Verta::instance($survey->created_at)->format('Y/m/d') }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="action-btn info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#surveyDetailModal" 
                                                    data-id="{{ $survey->id }}"
                                                    title="مشاهده جزئیات">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <i class="fas fa-poll-h"></i>
                                                <h6 class="fw-normal">هیچ نظرسنجی در این دسته وجود ندارد</h6>
                                                <p class="text-muted small">برای این دسته نظرسنجی ثبت نشده است.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- نمایش دسته‌بندی‌ها -->
        <div class="row g-4">
            @forelse($categories as $category)
            <div class="col-md-4 col-lg-3">
                <a href="{{ route('admin_survey', ['category' => $category->id]) }}" class="category-card">
                    <div class="cat-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div class="cat-name">{{ $category->name }}</div>
                    <div class="cat-count">
                        <span>{{ $category->surveys_count ?? 0 }}</span> نظرسنجی
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-folder"></i>
                    <h6 class="fw-normal">هیچ دسته‌بندی ثبت نشده است</h6>
                    <p class="text-muted small">برای ایجاد دسته‌بندی از بخش مدیریت دسته‌ها اقدام کنید.</p>
                </div>
            </div>
            @endforelse
        </div>
    @endif
</div>

<!-- مودال جزئیات نظرسنجی -->
<div class="modal fade" id="surveyDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">
                    <i class="fas fa-poll-h me-2 text-primary"></i> جزئیات نظرسنجی
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="surveyDetailBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">در حال بارگذاری...</span>
                    </div>
                    <p class="mt-3 text-muted">در حال دریافت اطلاعات...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> بستن
                </button>
            </div>
        </div>
    </div>
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
    "timeOut": "3500",
    "rtl": true
};

/**
 * تغییر وضعیت نظرسنجی با AJAX (فعال/غیرفعال)
 */
function toggleSurvey(type, element) {

    const spinnerId = type === 'student'
        ? 'studentSpinner'
        : 'teacherSpinner';

    const badgeId = type === 'student'
        ? 'studentStatusBadge'
        : 'teacherStatusBadge';

    const spinner = document.getElementById(spinnerId);
    const badge = document.getElementById(badgeId);

    // وضعیت جدیدی که کاربر با کلیک انتخاب کرده
    const requestedStatus = element.checked;

    spinner.style.display = 'inline-block';
    element.disabled = true;

    const url = type === 'student'
        ? '{{ route("admin.toggle-student-survey") }}'
        : '{{ route("admin.toggle-teacher-survey") }}';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {

        spinner.style.display = 'none';
        element.disabled = false;

        if (data.success) {

            // تبدیل دقیق پاسخ Laravel
            const newStatus = data.status === true;

            /*
             * اعمال وضعیت جدید
             */
            element.checked = newStatus;

            /*
             * یک بار دیگر بعد از اتمام event
             * وضعیت را روی input اعمال می‌کنیم.
             */
            setTimeout(function () {
                element.checked = newStatus;
            }, 0);

            /*
             * تغییر متن وضعیت
             */
            if (newStatus) {

                badge.classList.remove('inactive');
                badge.classList.add('active');
                badge.textContent = 'فعال';

            } else {

                badge.classList.remove('active');
                badge.classList.add('inactive');
                badge.textContent = 'غیرفعال';

            }

            toastr.success(data.message);

        } else {

            // اگر Backend خطا داد، برگرد به وضعیت قبل
            element.checked = !requestedStatus;

            toastr.error(
                data.message || 'خطا در تغییر وضعیت'
            );
        }

    })
    .catch(error => {

        spinner.style.display = 'none';
        element.disabled = false;

        // اگر درخواست AJAX شکست خورد
        element.checked = !requestedStatus;

        console.error('Error:', error);

        toastr.error('خطا در ارتباط با سرور');
    });
}

/**
 * بارگذاری اطلاعات نظرسنجی در مودال هنگام باز شدن (بدون jQuery)
 */
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('surveyDetailModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var surveyId = button.getAttribute('data-id');
        var body = document.getElementById('surveyDetailBody');

        // نمایش اسپینر
        body.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">در حال بارگذاری...</span>
                </div>
                <p class="mt-3 text-muted">در حال دریافت اطلاعات...</p>
            </div>
        `;

        // درخواست AJAX
        fetch('{{ route("admin.survey.show", "") }}/' + surveyId, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var survey = data.survey;
                var options = data.options;
                var totalVotes = data.total_votes;

                var html = `
                    <div class="detail-section">
                        <h6><i class="fas fa-quote-right me-2 text-primary"></i> متن نظرسنجی</h6>
                        <p class="bg-white p-3 rounded border">${survey.text}</p>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="detail-section">
                                <h6><i class="fas fa-tag me-2 text-primary"></i> نوع</h6>
                                <p><span class="badge bg-primary bg-opacity-15 text- px-3 py-2">${survey.type == 1 ? 'متن باز' : (survey.type == 2 ? 'تک‌انتخابی' : 'چندانتخابی')}</span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-section">
                                <h6><i class="fas fa-circle me-2 text-primary"></i> وضعیت</h6>
                                <p><span class="status-badge ${survey.active ? 'active' : 'inactive'}">${survey.active ? 'فعال' : 'غیرفعال'}</span></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <h6><i class="fas fa-users me-2 text-primary"></i> تعداد کل پاسخ‌ها</h6>
                        <p><span class="badge bg-primary rounded-pill px-4 py-2 fs-6">${totalVotes}</span></p>
                    </div>
                `;

                if (survey.type > 1 && options.length > 0) {
                    html += `
                        <div class="detail-section">
                            <h6><i class="fas fa-list-ul me-2 text-primary"></i> گزینه‌ها و نتایج</h6>
                            <div class="bg-white rounded border p-2">
                                <ul class="list-group list-group-flush">
                    `;
                    options.forEach(function(opt) {
                        html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                                <span>${opt.text}</span>
                                <span class="badge bg-info bg-opacity-15 text-info rounded-pill px-3 py-2">
                                    ${opt.count} (${opt.percentage}%)
                                </span>
                            </li>
                        `;
                    });
                    html += `
                                </ul>
                            </div>
                        </div>
                    `;
                } else if (survey.type === 1) {
                    html += `
                        <div class="detail-section">
                            <h6><i class="fas fa-info-circle me-2 text-primary"></i> توضیح</h6>
                            <p class="text-muted">این نظرسنجی از نوع متن باز است و گزینه‌ای ندارد.</p>
                        </div>
                    `;
                }

                body.innerHTML = html;
            } else {
                body.innerHTML = `
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        خطا در دریافت اطلاعات
                    </div>
                `;
            }
        })
        .catch(function() {
            body.innerHTML = `
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    خطا در ارتباط با سرور
                </div>
            `;
        });
    });
});
</script>
@endsection