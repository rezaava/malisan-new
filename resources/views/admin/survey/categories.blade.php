@extends('layout.master')

@section('title')
    ملیسان | دسته‌بندی نظرسنجی‌ها
@endsection


@section('head')

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>

        /* =========================
           Toggle
        ========================= */

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
            inset: 0;
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

        .status-toggle input:checked + .slider {
            background-color: #0d6efd;
        }

        .status-toggle input:checked + .slider:before {
            transform: translateX(26px);
        }


        /* =========================
           Status
        ========================= */

        .status-badge {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-badge.active {
            background-color: #d1e7dd;
            color: #0a3622;
        }

        .status-badge.inactive {
            background-color: #f8d7da;
            color: #58151c;
        }


        /* =========================
           Toggle Container
        ========================= */

        .toggle-container {
            background: #f8f9fa;
            border-radius: 14px;
            padding: 16px 22px;
            margin-bottom: 14px;
            border: 1px solid #e9ecef;
            transition: all .3s ease;
        }

        .toggle-container:hover {
            background: white;
            border-color: #dee2e6;
            box-shadow: 0 2px 12px rgba(0,0,0,.04);
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


        /* =========================
           Loading
        ========================= */

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid #e9ecef;
            border-top-color: #0d6efd;
            border-radius: 50%;
            animation: spin .8s linear infinite;
            margin-left: 12px;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }


        /* =========================
           Card
        ========================= */

        .card {
            border: none;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            border-radius: 16px;
            overflow: hidden;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #e9ecef;
            padding: 18px 24px;
        }

        .card-body {
            padding: 24px;
        }


        /* =========================
           Category
        ========================= */

        .category-card {
            background: white;
            border-radius: 16px;
            padding: 24px 20px;
            border: 1px solid #e9ecef;
            transition: all .3s ease;
            height: 100%;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0,0,0,.08);
            border-color: #0d6efd;
        }

        .cat-icon {
            font-size: 32px;
            color: #0d6efd;
            margin-bottom: 12px;
        }

        .cat-name {
            font-size: 18px;
            font-weight: 600;
            color: #1a2332;
        }

        .cat-count {
            font-size: 14px;
            color: #6c757d;
            margin-top: 6px;
        }

        .cat-count span {
            background: #e9ecef;
            padding: 3px 10px;
            border-radius: 30px;
            font-weight: 600;
            color: #0d6efd;
        }


        /* =========================
           Empty
        ========================= */

        .empty-state {
            padding: 50px 20px;
            text-align: center;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: .3;
        }


        /* =========================
           Add Category Button
        ========================= */

        .add-category-btn {
            border-radius: 10px;
            padding: 9px 18px;
        }

    </style>

@endsection


@section('mohtava')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="row mb-4">

        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-1 fw-bold">
                        مدیریت نظرسنجی‌ها
                    </h4>

                    <p class="text-muted mb-0">
                        انتخاب دسته‌بندی نظرسنجی
                    </p>

                </div>

                <div class="d-flex align-items-center gap-2">

                    <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill">

                        <i class="fas fa-folder-open me-2"></i>

                        <span id="categoryCount">
                            {{ $categories->count() }}
                        </span>

                        دسته

                    </span>

                    <button
                        type="button"
                        class="btn btn-primary add-category-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#addCategoryModal"
                    >

                        <i class="fas fa-plus me-1"></i>

                        افزودن دسته

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- تنظیمات نظرسنجی --}}
    <div class="row mb-4">

        <div class="col-12">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-toggle-on me-2 text-primary"></i>

                        وضعیت نظرسنجی‌ها

                    </h5>

                </div>


                <div class="card-body">

                    {{-- دانشجو --}}
                    <div class="toggle-container d-flex flex-wrap align-items-center justify-content-between">

                        <div class="toggle-label d-flex align-items-center gap-3">

                            <i class="fas fa-user-graduate"></i>

                            <div>

                                <div class="toggle-title">
                                    نظرسنجی دانشجویان
                                </div>

                                <div class="toggle-description">
                                    فعال/غیرفعال کردن نظرسنجی از دانشجویان در زمان ورود
                                </div>

                            </div>

                        </div>


                        <div class="d-flex align-items-center mt-2 mt-sm-0">

                            <span
                                class="status-badge {{ $settings->enable_student_survey ? 'active' : 'inactive' }} me-3"
                                id="studentStatusBadge"
                            >
                                {{ $settings->enable_student_survey ? 'فعال' : 'غیرفعال' }}
                            </span>


                            <label class="status-toggle">

                                <input
                                    type="checkbox"
                                    id="studentSurveyToggle"
                                    {{ $settings->enable_student_survey ? 'checked' : '' }}
                                    onchange="toggleSurvey('student', this)"
                                >

                                <span class="slider"></span>

                            </label>


                            <div
                                class="loading-spinner"
                                id="studentSpinner"
                            ></div>

                        </div>

                    </div>


                    {{-- استاد --}}
                    <div class="toggle-container d-flex flex-wrap align-items-center justify-content-between">

                        <div class="toggle-label d-flex align-items-center gap-3">

                            <i class="fas fa-chalkboard-teacher"></i>

                            <div>

                                <div class="toggle-title">
                                    نظرسنجی اساتید
                                </div>

                                <div class="toggle-description">
                                    فعال/غیرفعال کردن نظرسنجی از اساتید در زمان ورود
                                </div>

                            </div>

                        </div>


                        <div class="d-flex align-items-center mt-2 mt-sm-0">

                            <span
                                class="status-badge {{ $settings->enable_teacher_survey ? 'active' : 'inactive' }} me-3"
                                id="teacherStatusBadge"
                            >
                                {{ $settings->enable_teacher_survey ? 'فعال' : 'غیرفعال' }}
                            </span>


                            <label class="status-toggle">

                                <input
                                    type="checkbox"
                                    id="teacherSurveyToggle"
                                    {{ $settings->enable_teacher_survey ? 'checked' : '' }}
                                    onchange="toggleSurvey('teacher', this)"
                                >

                                <span class="slider"></span>

                            </label>


                            <div
                                class="loading-spinner"
                                id="teacherSpinner"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- دسته‌بندی‌ها --}}
    <div
        class="row g-4"
        id="categoriesContainer"
    >

        @forelse($categories as $category)

            <div
                class="col-md-4 col-lg-3 category-item"
                data-category-id="{{ $category->id }}"
            >

                <a
                    href="{{ route('admin.survey.category', $category->id) }}"
                    class="category-card"
                >

                    <div class="cat-icon">

                        <i class="fas fa-folder-open"></i>

                    </div>

                    <div class="cat-name">
                        {{ $category->name }}
                    </div>

                    <div class="cat-count">

                        <span>
                            {{ $category->surveys_count ?? 0 }}
                        </span>

                        سوال

                    </div>

                </a>

            </div>

        @empty

            <div
                class="col-12"
                id="emptyCategories"
            >

                <div class="card">

                    <div class="empty-state">

                        <i class="fas fa-folder"></i>

                        <h6 class="fw-normal">
                            هیچ دسته‌بندی ثبت نشده است
                        </h6>

                        <p class="text-muted small">
                            برای ایجاد دسته‌بندی روی «افزودن دسته» کلیک کنید.
                        </p>

                    </div>

                </div>

            </div>

        @endforelse

    </div>

</div>


{{-- =====================================================
     Modal افزودن دسته‌بندی
===================================================== --}}

<div
    class="modal fade"
    id="addCategoryModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form id="addCategoryForm">

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="fas fa-folder-plus text-primary me-2"></i>

                        افزودن دسته‌بندی

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label
                            for="categoryName"
                            class="form-label"
                        >
                            نام دسته‌بندی
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="categoryName"
                            name="name"
                            placeholder="مثلاً رضایت از استاد"
                            autocomplete="off"
                            required
                        >

                        <div
                            class="invalid-feedback"
                            id="categoryNameError"
                        ></div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        انصراف
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="addCategorySubmit"
                    >

                        <span id="addCategoryText">

                            <i class="fas fa-plus me-1"></i>

                            افزودن

                        </span>

                        <span
                            id="addCategoryLoading"
                            class="d-none"
                        >

                            <span
                                class="spinner-border spinner-border-sm me-1"
                            ></span>

                            در حال ثبت...

                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-left',
    timeOut: 3500,
    rtl: true
};


/* =====================================================
   Toggle نظرسنجی
===================================================== */

function toggleSurvey(type, element)
{
    const isStudent = type === 'student';

    const spinner = document.getElementById(
        isStudent ? 'studentSpinner' : 'teacherSpinner'
    );

    const badge = document.getElementById(
        isStudent ? 'studentStatusBadge' : 'teacherStatusBadge'
    );

    const url = isStudent
        ? '{{ route("admin.toggle-student-survey") }}'
        : '{{ route("admin.toggle-teacher-survey") }}';

    const previousStatus = !element.checked;

    spinner.style.display = 'inline-block';

    element.disabled = true;

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

        if (!data.success) {

            element.checked = previousStatus;

            toastr.error(
                data.message || 'خطا در تغییر وضعیت'
            );

            return;
        }

        const status = data.status === true;

        element.checked = status;

        badge.classList.toggle('active', status);
        badge.classList.toggle('inactive', !status);

        badge.textContent = status
            ? 'فعال'
            : 'غیرفعال';

        toastr.success(data.message);

    })

    .catch(error => {

        console.error(error);

        spinner.style.display = 'none';

        element.disabled = false;

        element.checked = previousStatus;

        toastr.error(
            'خطا در ارتباط با سرور'
        );

    });
}


/* =====================================================
   افزودن دسته‌بندی
===================================================== */

document
    .getElementById('addCategoryForm')
    .addEventListener('submit', function(event)
    {
        event.preventDefault();

        const form = this;

        const input = document.getElementById('categoryName');

        const error = document.getElementById('categoryNameError');

        const submitButton = document.getElementById('addCategorySubmit');

        const submitText = document.getElementById('addCategoryText');

        const loading = document.getElementById('addCategoryLoading');

        const name = input.value.trim();


        /* اعتبارسنجی سمت کلاینت */

        if (!name) {

            input.classList.add('is-invalid');

            error.textContent = 'نام دسته‌بندی را وارد کنید.';

            return;
        }


        input.classList.remove('is-invalid');

        error.textContent = '';


        submitButton.disabled = true;

        submitText.classList.add('d-none');

        loading.classList.remove('d-none');


        fetch(
            '{{ route("admin.survey.category.store") }}',
            {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),

                    'Accept': 'application/json'

                },

                body: JSON.stringify({
                    name: name
                })

            }
        )

        .then(async response => {

            const data = await response.json();

            if (!response.ok) {

                throw data;
            }

            return data;

        })

        .then(data => {

            if (!data.success) {

                throw data;
            }


            const category = data.category;

            const container = document.getElementById(
                'categoriesContainer'
            );

            const empty = document.getElementById(
                'emptyCategories'
            );


            /* حذف پیام خالی */

            if (empty) {
                empty.remove();
            }


            /* ساخت کارت جدید */

            const item = document.createElement('div');

            item.className =
                'col-md-4 col-lg-3 category-item';

            item.dataset.categoryId =
                category.id;


            item.innerHTML = `

                <a
                    href="{{ url('/admin/survey/category') }}/${category.id}"
                    class="category-card"
                >

                    <div class="cat-icon">

                        <i class="fas fa-folder-open"></i>

                    </div>

                    <div class="cat-name">
                        ${escapeHtml(category.name)}
                    </div>

                    <div class="cat-count">

                        <span>
                            0
                        </span>

                        سوال

                    </div>

                </a>

            `;


            container.prepend(item);


            /* بروزرسانی تعداد دسته‌ها */

            const countElement =
                document.getElementById('categoryCount');

            countElement.textContent =
                parseInt(countElement.textContent) + 1;


            /* پاک کردن فرم */

            form.reset();


            /* بستن modal */

            const modalElement =
                document.getElementById('addCategoryModal');

            const modal =
                bootstrap.Modal.getInstance(modalElement);

            modal.hide();


            toastr.success(data.message);

        })

        .catch(error => {

            console.error(error);


            if (error.errors && error.errors.name) {

                input.classList.add('is-invalid');

                error.textContent =
                    error.errors.name[0];

            } else {

                toastr.error(
                    error.message ||
                    'خطا در افزودن دسته‌بندی'
                );

            }

        })

        .finally(() => {

            submitButton.disabled = false;

            submitText.classList.remove('d-none');

            loading.classList.add('d-none');

        });

    });


/* =====================================================
   حذف کاراکترهای HTML برای جلوگیری از XSS
===================================================== */

function escapeHtml(value)
{
    const div = document.createElement('div');

    div.textContent = value;

    return div.innerHTML;
}


/* =====================================================
   پاک کردن خطای input
===================================================== */

document
    .getElementById('categoryName')
    .addEventListener('input', function()
    {
        this.classList.remove('is-invalid');

        document
            .getElementById('categoryNameError')
            .textContent = '';
    });

</script>

@endsection