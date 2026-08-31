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
        .category-card-wrapper {
            position: relative;
            height: 100%;
        }

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

        .category-actions {
            position: absolute;
            top: 10px;
            left: 10px;
            display: flex;
            gap: 6px;
            opacity: 0;
            transition: opacity .3s ease;
            z-index: 10;
        }

        .category-card-wrapper:hover .category-actions {
            opacity: 1;
        }

        .category-actions .btn {
            width: 32px;
            height: 32px;
            padding: 0;
            border-radius: 50%;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid #dee2e6;
            color: #6c757d;
            transition: all .2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }

        .category-actions .btn:hover {
            background: #f8f9fa;
            transform: scale(1.05);
        }

        .category-actions .btn-edit:hover {
            background: #cfe2ff;
            border-color: #0d6efd;
            color: #0d6efd;
        }

        .category-actions .btn-delete:hover {
            background: #f8d7da;
            border-color: #dc3545;
            color: #dc3545;
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
            margin-bottom: 6px;
            word-break: break-word;
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

        /* =========================
           Modal
        ========================= */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,.12);
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 18px 24px;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 14px 24px;
        }

        /* =========================
           Responsive
        ========================= */
        @media (max-width: 576px) {
            .category-actions {
                opacity: 1;
                top: 6px;
                left: 6px;
            }
            
            .category-actions .btn {
                width: 28px;
                height: 28px;
                font-size: 11px;
            }

            .cat-name {
                font-size: 16px;
            }

            .cat-icon {
                font-size: 26px;
            }

            .toggle-container {
                padding: 12px 16px;
            }
        }
    </style>
@endsection

@section('mohtava')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h4 class="mb-1 fw-bold">
                        <i class="fas fa-poll text-primary me-2"></i>
                        مدیریت نظرسنجی‌ها
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-folder-open me-1"></i>
                        انتخاب دسته‌بندی نظرسنجی
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill">
                        <i class="fas fa-folder-open me-2"></i>
                        <span id="categoryCount">{{ $categories->count() }}</span>
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
                            <div class="loading-spinner" id="studentSpinner"></div>
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
                            <div class="loading-spinner" id="teacherSpinner"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- دسته‌بندی‌ها --}}
    <div class="row g-4" id="categoriesContainer">
        @forelse($categories as $category)
            <div class="col-md-4 col-lg-3 category-item" data-category-id="{{ $category->id }}">
                <div class="category-card-wrapper">
                    <a href="{{ route('admin.survey.category', $category->id) }}" class="category-card">
                        <div class="cat-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div class="cat-name">{{ $category->name }}</div>
                        <div class="cat-count">
                            <span>{{ $category->surveys_count ?? 0 }}</span>
                            سوال
                        </div>
                    </a>
                    <div class="category-actions">
                        <button
                            class="btn btn-edit"
                            onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}')"
                            title="ویرایش"
                        >
                            <i class="fas fa-pen"></i>
                        </button>
                        <button
                            class="btn btn-delete"
                            onclick="deleteCategory({{ $category->id }})"
                            title="حذف"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12" id="emptyCategories">
                <div class="card">
                    <div class="empty-state">
                        <i class="fas fa-folder"></i>
                        <h6 class="fw-normal">هیچ دسته‌بندی ثبت نشده است</h6>
                        <p class="text-muted small">برای ایجاد دسته‌بندی روی «افزودن دسته» کلیک کنید.</p>
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
                        <label for="categoryName" class="form-label fw-semibold">
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
                        <div class="invalid-feedback" id="categoryNameError"></div>
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
                        <span id="addCategoryLoading" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            در حال ثبت...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =====================================================
     Modal ویرایش دسته‌بندی
===================================================== --}}
<div
    class="modal fade"
    id="editCategoryModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editCategoryForm">
                <input type="hidden" id="editCategoryId" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit text-primary me-2"></i>
                        ویرایش دسته‌بندی
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label fw-semibold">
                            نام دسته‌بندی
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="editCategoryName"
                            name="name"
                            placeholder="نام دسته‌بندی را وارد کنید"
                            autocomplete="off"
                            required
                        >
                        <div class="invalid-feedback" id="editCategoryNameError"></div>
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
                        id="editCategorySubmit"
                    >
                        <span id="editCategoryText">
                            <i class="fas fa-save me-1"></i>
                            ذخیره تغییرات
                        </span>
                        <span id="editCategoryLoading" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            در حال ذخیره...
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
    function toggleSurvey(type, element) {
        const isStudent = type === 'student';
        const spinner = document.getElementById(isStudent ? 'studentSpinner' : 'teacherSpinner');
        const badge = document.getElementById(isStudent ? 'studentStatusBadge' : 'teacherStatusBadge');
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
                toastr.error(data.message || 'خطا در تغییر وضعیت');
                return;
            }

            const status = data.status === true;
            element.checked = status;
            badge.classList.toggle('active', status);
            badge.classList.toggle('inactive', !status);
            badge.textContent = status ? 'فعال' : 'غیرفعال';
            toastr.success(data.message);
        })
        .catch(error => {
            console.error(error);
            spinner.style.display = 'none';
            element.disabled = false;
            element.checked = previousStatus;
            toastr.error('خطا در ارتباط با سرور');
        });
    }

    /* =====================================================
       افزودن دسته‌بندی
    ===================================================== */
    document.getElementById('addCategoryForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const form = this;
        const input = document.getElementById('categoryName');
        const error = document.getElementById('categoryNameError');
        const submitButton = document.getElementById('addCategorySubmit');
        const submitText = document.getElementById('addCategoryText');
        const loading = document.getElementById('addCategoryLoading');
        const name = input.value.trim();

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

        fetch('{{ route("admin.survey.category.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) throw data;
            return data;
        })
        .then(data => {
            if (!data.success) throw data;

            const category = data.category;
            const container = document.getElementById('categoriesContainer');
            const empty = document.getElementById('emptyCategories');

            if (empty) {
                empty.remove();
            }

            const item = document.createElement('div');
            item.className = 'col-md-4 col-lg-3 category-item';
            item.dataset.categoryId = category.id;
            item.innerHTML = `
                <div class="category-card-wrapper">
                    <a href="{{ url('/admin/survey/category') }}/${category.id}" class="category-card">
                        <div class="cat-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div class="cat-name">${escapeHtml(category.name)}</div>
                        <div class="cat-count">
                            <span>0</span>
                            سوال
                        </div>
                    </a>
                    <div class="category-actions">
                        <button class="btn btn-edit" onclick="editCategory(${category.id}, '${escapeHtml(category.name)}')" title="ویرایش">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="btn btn-delete" onclick="deleteCategory(${category.id})" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            container.prepend(item);

            const countElement = document.getElementById('categoryCount');
            countElement.textContent = parseInt(countElement.textContent) + 1;

            form.reset();
            const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
            modal.hide();

            toastr.success(data.message);
        })
        .catch(error => {
            console.error(error);
            if (error.errors && error.errors.name) {
                input.classList.add('is-invalid');
                error.textContent = error.errors.name[0];
            } else {
                toastr.error(error.message || 'خطا در افزودن دسته‌بندی');
            }
        })
        .finally(() => {
            submitButton.disabled = false;
            submitText.classList.remove('d-none');
            loading.classList.add('d-none');
        });
    });

    /* =====================================================
       ویرایش دسته‌بندی
    ===================================================== */
    function editCategory(id, name) {
        document.getElementById('editCategoryId').value = id;
        document.getElementById('editCategoryName').value = name;
        document.getElementById('editCategoryName').classList.remove('is-invalid');
        document.getElementById('editCategoryNameError').textContent = '';

        const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        modal.show();
    }

    document.getElementById('editCategoryForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const id = document.getElementById('editCategoryId').value;
        const name = document.getElementById('editCategoryName').value.trim();
        const error = document.getElementById('editCategoryNameError');
        const input = document.getElementById('editCategoryName');
        const submitButton = document.getElementById('editCategorySubmit');
        const submitText = document.getElementById('editCategoryText');
        const loading = document.getElementById('editCategoryLoading');

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

        fetch(`/admin/survey/category/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) throw data;
            return data;
        })
        .then(data => {
            if (!data.success) throw data;

            const category = data.category;
            const card = document.querySelector(`.category-item[data-category-id="${category.id}"]`);
            if (card) {
                const nameElement = card.querySelector('.cat-name');
                if (nameElement) {
                    nameElement.textContent = category.name;
                }
                // به‌روزرسانی دکمه ویرایش
                const editBtn = card.querySelector('.btn-edit');
                if (editBtn) {
                    editBtn.setAttribute('onclick', `editCategory(${category.id}, '${escapeHtml(category.name)}')`);
                }
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
            modal.hide();
            toastr.success(data.message);
        })
        .catch(error => {
            console.error(error);
            if (error.errors && error.errors.name) {
                input.classList.add('is-invalid');
                error.textContent = error.errors.name[0];
            } else {
                toastr.error(error.message || 'خطا در ویرایش دسته‌بندی');
            }
        })
        .finally(() => {
            submitButton.disabled = false;
            submitText.classList.remove('d-none');
            loading.classList.add('d-none');
        });
    });

    /* =====================================================
       حذف دسته‌بندی
    ===================================================== */
    function deleteCategory(id) {
        if (!confirm('آیا از حذف این دسته‌بندی مطمئن هستید؟\nتوجه: اگر دسته‌بندی دارای سوال باشد، قابل حذف نیست.')) {
            return;
        }

        fetch(`/admin/survey/category/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) throw data;
            return data;
        })
        .then(data => {
            if (!data.success) throw data;

            const item = document.querySelector(`.category-item[data-category-id="${id}"]`);
            if (item) {
                item.remove();
            }

            const countElement = document.getElementById('categoryCount');
            countElement.textContent = parseInt(countElement.textContent) - 1;

            const remainingItems = document.querySelectorAll('.category-item');
            if (remainingItems.length === 0) {
                const container = document.getElementById('categoriesContainer');
                container.innerHTML = `
                    <div class="col-12" id="emptyCategories">
                        <div class="card">
                            <div class="empty-state">
                                <i class="fas fa-folder"></i>
                                <h6 class="fw-normal">هیچ دسته‌بندی ثبت نشده است</h6>
                                <p class="text-muted small">برای ایجاد دسته‌بندی روی «افزودن دسته» کلیک کنید.</p>
                            </div>
                        </div>
                    </div>
                `;
            }

            toastr.success(data.message);
        })
        .catch(error => {
            console.error(error);
            toastr.error(error.message || 'خطا در حذف دسته‌بندی');
        });
    }

    /* =====================================================
       حذف کاراکترهای HTML برای جلوگیری از XSS
    ===================================================== */
    function escapeHtml(value) {
        if (!value) return '';
        const div = document.createElement('div');
        div.textContent = value;
        return div.innerHTML;
    }

    /* =====================================================
       پاک کردن خطای input
    ===================================================== */
    document.getElementById('categoryName').addEventListener('input', function() {
        this.classList.remove('is-invalid');
        document.getElementById('categoryNameError').textContent = '';
    });

    document.getElementById('editCategoryName').addEventListener('input', function() {
        this.classList.remove('is-invalid');
        document.getElementById('editCategoryNameError').textContent = '';
    });

    /* =====================================================
       پاک کردن مودال هنگام بسته شدن
    ===================================================== */
    document.getElementById('addCategoryModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('addCategoryForm').reset();
        document.getElementById('categoryName').classList.remove('is-invalid');
        document.getElementById('categoryNameError').textContent = '';
    });

    document.getElementById('editCategoryModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('editCategoryForm').reset();
        document.getElementById('editCategoryName').classList.remove('is-invalid');
        document.getElementById('editCategoryNameError').textContent = '';
    });
</script>
@endsection