@extends('layout.master')

@section('title')
ملیسان | دسته‌بندی نظرسنجی‌ها
@endsection

@section('head') <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"> <meta name="csrf-token" content="{{ csrf_token() }}">


<style>
    :root {
        --primary: #0d6efd;
        --primary-dark: #0a58ca;
        --primary-light: #eef6ff;
        --text: #172033;
        --muted: #7b8494;
        --border: #e8edf3;
        --bg: #f6f8fb;
        --success: #198754;
        --danger: #dc3545;
        --warning: #f59f00;
    }

    .survey-page {
        min-height: 100vh;
        padding: 28px 0 50px;
        background: var(--bg);
    }

    /* =========================
       PAGE HEADER
    ========================= */

    .page-header {
        position: relative;
        overflow: hidden;
        padding: 27px 30px;
        margin-bottom: 25px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 22px;
        box-shadow: 0 8px 30px rgba(23, 32, 51, .055);
    }

    .page-header::before {
        content: "";
        position: absolute;
        top: -80px;
        left: -50px;
        width: 180px;
        height: 180px;
        background: rgba(13, 110, 253, .045);
        border-radius: 50%;
    }

    .page-header::after {
        content: "";
        position: absolute;
        right: -70px;
        bottom: -90px;
        width: 220px;
        height: 220px;
        background: rgba(13, 110, 253, .035);
        border-radius: 50%;
    }

    .page-header-content {
        position: relative;
        z-index: 2;
    }

    .page-title-wrap {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-title-icon {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #fff;
        background: linear-gradient(135deg, #0d6efd, #3d8bfd);
        border-radius: 16px;
        font-size: 22px;
        box-shadow: 0 8px 20px rgba(13, 110, 253, .22);
    }

    .page-title {
        margin: 0 0 5px;
        color: var(--text);
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -.3px;
    }

    .page-subtitle {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .category-counter {
        display: flex;
        align-items: center;
        gap: 7px;
        min-height: 42px;
        padding: 0 15px;
        color: var(--primary);
        background: var(--primary-light);
        border: 1px solid #dcecff;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
    }

    .category-counter strong {
        font-size: 15px;
    }

    .add-category-btn {
        min-height: 42px;
        padding: 0 17px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #0d6efd, #2f82f7);
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 7px 18px rgba(13, 110, 253, .18);
        transition: .25s ease;
    }

    .add-category-btn:hover {
        background: linear-gradient(135deg, #0a58ca, #2477e8);
        transform: translateY(-2px);
        box-shadow: 0 10px 23px rgba(13, 110, 253, .24);
    }

    /* =========================
       SETTINGS CARD
    ========================= */

    .settings-card {
        overflow: hidden;
        margin-bottom: 28px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 20px;
        box-shadow: 0 7px 28px rgba(23, 32, 51, .05);
    }

    .settings-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 23px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(180deg, #fff, #fbfcfe);
    }

    .settings-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .settings-heading-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        background: var(--primary-light);
        border-radius: 11px;
        font-size: 17px;
    }

    .settings-title {
        margin: 0;
        color: var(--text);
        font-size: 15px;
        font-weight: 800;
    }

    .settings-description {
        margin: 3px 0 0;
        color: var(--muted);
        font-size: 11.5px;
    }

    .settings-body {
        padding: 20px;
    }

    /* =========================
       TOGGLE ROW
    ========================= */

    .survey-toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 17px 18px;
        background: #fbfcfe;
        border: 1px solid #edf0f4;
        border-radius: 15px;
        transition: .25s ease;
    }

    .survey-toggle-row + .survey-toggle-row {
        margin-top: 12px;
    }

    .survey-toggle-row:hover {
        background: #fff;
        border-color: #dbe7f5;
        box-shadow: 0 6px 20px rgba(13, 110, 253, .055);
    }

    .survey-info {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
    }

    .survey-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--primary);
        background: #eaf3ff;
        border-radius: 12px;
        font-size: 18px;
    }

    .survey-name {
        margin-bottom: 4px;
        color: var(--text);
        font-size: 14px;
        font-weight: 750;
    }

    .survey-description {
        color: var(--muted);
        font-size: 11.5px;
        line-height: 1.8;
    }

    .survey-control {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .status-badge {
        min-width: 65px;
        padding: 6px 11px;
        color: var(--success);
        background: #eaf8f0;
        border: 1px solid #ccebd9;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 800;
        text-align: center;
    }

    /* =========================
       SWITCH
    ========================= */

    .status-switch {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 30px;
        margin: 0;
        cursor: pointer;
    }

    .status-switch input {
        position: absolute;
        width: 0;
        height: 0;
        opacity: 0;
    }

    .switch-slider {
        position: absolute;
        inset: 0;
        background: #b9c0c8;
        border-radius: 30px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, .10);
        transition: .25s ease;
    }

    .switch-slider::before {
        content: "";
        position: absolute;
        left: 4px;
        bottom: 4px;
        width: 22px;
        height: 22px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .20);
        transition: .25s ease;
    }

    .status-switch input:checked + .switch-slider {
        background: var(--primary);
        box-shadow: 0 3px 10px rgba(13, 110, 253, .22);
    }

    .status-switch input:checked + .switch-slider::before {
        transform: translateX(25px);
    }

    .status-switch input:disabled + .switch-slider {
        opacity: .6;
        cursor: not-allowed;
    }

    .toggle-loader {
        display: none;
        width: 18px;
        height: 18px;
        border: 2px solid #dfe5eb;
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: rotate .7s linear infinite;
    }

    @keyframes rotate {
        to {
            transform: rotate(360deg);
        }
    }

    /* =========================
       CATEGORY SECTION
    ========================= */

    .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .section-heading-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-heading-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        background: var(--primary-light);
        border-radius: 11px;
        font-size: 15px;
    }

    .section-heading-title {
        margin: 0;
        color: var(--text);
        font-size: 16px;
        font-weight: 800;
    }

    .section-heading-text {
        margin: 2px 0 0;
        color: var(--muted);
        font-size: 11px;
    }

    /* =========================
       CATEGORY CARD
    ========================= */

    .category-card-wrapper {
        position: relative;
        height: 100%;
    }

    .category-card {
        position: relative;
        display: block;
        height: 100%;
        min-height: 185px;
        padding: 21px;
        overflow: hidden;
        color: inherit;
        text-decoration: none;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(23, 32, 51, .045);
        transition: .28s ease;
    }

    .category-card::before {
        content: "";
        position: absolute;
        top: -45px;
        right: -45px;
        width: 125px;
        height: 125px;
        background: var(--primary-light);
        border-radius: 50%;
        transition: .3s ease;
    }

    .category-card::after {
        content: "";
        position: absolute;
        right: 20px;
        bottom: 15px;
        width: 35px;
        height: 2px;
        background: #e9eef5;
        border-radius: 5px;
        transition: .25s ease;
    }

    .category-card:hover {
        color: inherit;
        transform: translateY(-5px);
        border-color: #cfe2ff;
        box-shadow: 0 14px 35px rgba(13, 110, 253, .105);
    }

    .category-card:hover::before {
        transform: scale(1.25);
        background: #e8f2ff;
    }

    .category-card:hover::after {
        width: 55px;
        background: var(--primary);
    }

    .cat-content {
        position: relative;
        z-index: 2;
    }

    .cat-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 17px;
        color: var(--primary);
        background: #edf5ff;
        border: 1px solid #dcecff;
        border-radius: 14px;
        font-size: 20px;
        transition: .25s ease;
    }

    .category-card:hover .cat-icon {
        color: #fff;
        background: var(--primary);
        border-color: var(--primary);
        transform: scale(1.05);
        box-shadow: 0 7px 16px rgba(13, 110, 253, .18);
    }

    .cat-name {
        margin-bottom: 10px;
        color: var(--text);
        font-size: 15px;
        font-weight: 800;
        line-height: 1.8;
        word-break: break-word;
    }

    .cat-count {
        display: flex;
        align-items: center;
        gap: 7px;
        color: var(--muted);
        font-size: 11.5px;
    }

    .cat-count-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 31px;
        height: 25px;
        padding: 0 8px;
        color: var(--primary);
        background: #f0f5fa;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
    }

    /* =========================
       CATEGORY ACTIONS
    ========================= */

    .category-actions {
        position: absolute;
        top: 11px;
        left: 11px;
        z-index: 5;
        display: flex;
        gap: 5px;
        opacity: 0;
        transform: translateY(-4px);
        transition: .2s ease;
    }

    .category-card-wrapper:hover .category-actions {
        opacity: 1;
        transform: translateY(0);
    }

    .category-action {
        width: 31px;
        height: 31px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        background: rgba(255, 255, 255, .97);
        border: 1px solid #e1e6ec;
        border-radius: 50%;
        color: #66717f;
        box-shadow: 0 3px 8px rgba(0, 0, 0, .08);
        transition: .2s ease;
    }

    .category-action:hover {
        transform: scale(1.08);
    }

    .category-action.edit:hover {
        color: var(--primary);
        background: #edf5ff;
        border-color: #c8ddfb;
    }

    .category-action.delete:hover {
        color: var(--danger);
        background: #fff1f2;
        border-color: #f1c1c6;
    }

    .category-action.deactivate:hover {
        color: var(--warning);
        background: #fff8e5;
        border-color: #ffe3a3;
    }

    /* =========================
       EMPTY STATE
    ========================= */

    .empty-state {
        padding: 65px 20px;
        text-align: center;
        background: #fff;
        border: 1px dashed #dce2e9;
        border-radius: 18px;
    }

    .empty-icon {
        width: 76px;
        height: 76px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        color: #9ba5b1;
        background: #f3f5f7;
        border-radius: 50%;
        font-size: 28px;
    }

    .empty-title {
        margin-bottom: 5px;
        color: var(--text);
        font-size: 14px;
        font-weight: 700;
    }

    .empty-text {
        margin: 0;
        color: var(--muted);
        font-size: 11.5px;
    }

    /* =========================
       MODALS
    ========================= */

    .modal-content {
        overflow: hidden;
        border: 0;
        border-radius: 18px;
        box-shadow: 0 20px 55px rgba(23, 32, 51, .16);
    }

    .modal-header {
        padding: 19px 22px;
        background: #fbfcfe;
        border-bottom: 1px solid var(--border);
    }

    .modal-title {
        color: var(--text);
        font-size: 15px;
        font-weight: 800;
    }

    .modal-title i {
        margin-left: 6px;
    }

    .modal-body {
        padding: 23px;
    }

    .modal-footer {
        padding: 14px 22px;
        background: #fbfcfe;
        border-top: 1px solid var(--border);
    }

    .form-label {
        margin-bottom: 8px;
        color: var(--text);
        font-size: 12.5px;
    }

    .form-control {
        min-height: 45px;
        padding: 10px 13px;
        color: var(--text);
        border: 1px solid #dfe5eb;
        border-radius: 11px;
        font-size: 13px;
        transition: .2s ease;
    }

    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .09);
    }

    .modal .btn {
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 768px) {
        .survey-page {
            padding-top: 18px;
        }

        .page-header {
            padding: 20px;
        }

        .header-actions {
            width: 100%;
            margin-top: 17px;
        }

        .category-counter,
        .add-category-btn {
            flex: 1;
            justify-content: center;
        }

        .survey-toggle-row {
            align-items: flex-start;
            flex-direction: column;
        }

        .survey-control {
            width: 100%;
            justify-content: flex-end;
        }
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 18px;
        }

        .page-title-icon {
            width: 47px;
            height: 47px;
            border-radius: 13px;
        }

        .page-header {
            border-radius: 17px;
        }

        .settings-card {
            border-radius: 17px;
        }

        .settings-body {
            padding: 13px;
        }

        .survey-toggle-row {
            padding: 14px;
        }

        .category-card {
            min-height: 165px;
        }

        .category-actions {
            opacity: 1;
            transform: none;
        }
    }
    /* =========================
   ACTIVE CATEGORY
========================= */

.category-card.category-active {
    background: #ffffff;
    border: 1px solid #cfe2ff;
    box-shadow: 0 5px 20px rgba(13, 110, 253, 0.07);
}

.category-card.category-active::before {
    background: #eef6ff;
}

.category-card.category-active .cat-icon {
    color: #0d6efd;
    background: #edf5ff;
    border-color: #d6e8ff;
}

.category-card.category-active .cat-name {
    color: #172033;
}

.category-card.category-active .cat-count-number {
    color: #0d6efd;
    background: #eef5ff;
}


/* =========================
   INACTIVE CATEGORY
========================= */

.category-card.category-inactive {
    background: #f1f3f5;
    border: 1px solid #d8dde3;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.035);
}

.category-card.category-inactive::before {
    background: #e5e7eb;
}

.category-card.category-inactive .cat-icon {
    color: #858d98;
    background: #e4e7eb;
    border-color: #d7dbe0;
}

.category-card.category-inactive .cat-name {
    color: #737b86;
}

.category-card.category-inactive .cat-count {
    color: #858d98;
}

.category-card.category-inactive .cat-count-number {
    color: #737b86;
    background: #e1e4e8;
}


/* =========================
   STATUS
========================= */

.category-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    width: fit-content;
    margin-top: 14px;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
}

.category-status.active {
    color: #198754;
    background: #eaf8f0;
    border: 1px solid #ccebd9;
}

.category-status.inactive {
    color: #6c757d;
    background: #e5e7eb;
    border: 1px solid #d5d9de;
}


/* =========================
   HOVER
========================= */

.category-card.category-active:hover {
    border-color: #9ec5fe;
    box-shadow: 0 14px 35px rgba(13, 110, 253, 0.12);
    transform: translateY(-5px);
}

.category-card.category-inactive:hover {
    border-color: #c4c9cf;
    background: #eceef1;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
    transform: translateY(-4px);
}

.category-card.category-inactive:hover .cat-icon {
    color: #6c757d;
    background: #dfe2e6;
}
</style>


@endsection

@section('mohtava')

<div class="survey-page">
    <div class="container-fluid">
        <div class="page-header">
            <div class="page-header-content">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="page-title-wrap">
                        <div class="page-title-icon">
                            <i class="fas fa-poll"></i>
                        </div>
                        <div>
                            <h1 class="page-title">مدیریت نظرسنجی‌ها</h1>
                            <p class="page-subtitle">
                                مدیریت دسته‌بندی‌ها و وضعیت نظرسنجی‌های سیستم
                            </p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <div class="category-counter">
                            <i class="fas fa-layer-group"></i>
                            <strong id="categoryCount">{{ $categories->count() }}</strong>
                            دسته‌بندی
                        </div>
                        <button type="button" class="btn btn-primary add-category-btn" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="fas fa-plus me-1"></i>
                            افزودن دسته
                        </button>
                    </div>
                </div>
            </div>
        </div>


    <div class="settings-card">
        <div class="settings-header">
            <div class="settings-heading">
                <div class="settings-heading-icon">
                    <i class="fas fa-toggle-on"></i>
                </div>
                <div>
                    <h2 class="settings-title">وضعیت نظرسنجی‌ها</h2>
                    <p class="settings-description">
                        وضعیت نمایش نظرسنجی برای هر گروه را مدیریت کنید
                    </p>
                </div>
            </div>
        </div>

        <div class="settings-body">
            <div class="survey-toggle-row">
                <div class="survey-info">
                    <div class="survey-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <div class="survey-name">نظرسنجی دانشجویان</div>
                        <div class="survey-description">
                            فعال یا غیرفعال کردن نظرسنجی از دانشجویان در زمان ورود
                        </div>
                    </div>
                </div>

                <div class="survey-control">
                    <span class="status-badge" id="studentStatusBadge">
                        {{ $settings->enable_student_survey ? 'فعال' : 'غیرفعال' }}
                    </span>

                    <label class="status-switch">
                        <input
                            type="checkbox"
                            id="studentSurveyToggle"
                            {{ $settings->enable_student_survey ? 'checked' : '' }}
                            onchange="toggleSurvey('student', this)"
                        >
                        <span class="switch-slider"></span>
                    </label>

                    <div class="toggle-loader" id="studentSpinner"></div>
                </div>
            </div>

            <div class="survey-toggle-row">
                <div class="survey-info">
                    <div class="survey-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div>
                        <div class="survey-name">نظرسنجی اساتید</div>
                        <div class="survey-description">
                            فعال یا غیرفعال کردن نظرسنجی از اساتید در زمان ورود
                        </div>
                    </div>
                </div>

                <div class="survey-control">
                    <span class="status-badge" id="teacherStatusBadge">
                        {{ $settings->enable_teacher_survey ? 'فعال' : 'غیرفعال' }}
                    </span>

                    <label class="status-switch">
                        <input
                            type="checkbox"
                            id="teacherSurveyToggle"
                            {{ $settings->enable_teacher_survey ? 'checked' : '' }}
                            onchange="toggleSurvey('teacher', this)"
                        >
                        <span class="switch-slider"></span>
                    </label>

                    <div class="toggle-loader" id="teacherSpinner"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-heading">
        <div class="section-heading-left">
            <div class="section-heading-icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <div>
                <h2 class="section-heading-title">دسته‌بندی‌ها</h2>
                <p class="section-heading-text">دسته‌بندی‌های موجود در سیستم</p>
            </div>
        </div>
    </div>

    <div class="row g-4" id="categoriesContainer">
        @forelse($categories as $category)
            <div class="col-xl-3 col-lg-4 col-md-6 category-item" data-category-id="{{ $category->id }}">
                <div class="category-card-wrapper">

                    <a
                        href="{{ route('admin.survey.category', $category->id) }}"
                        class="category-card {{ $category->is_active == 1 ? 'category-active' : 'category-inactive' }}"
                    >

                        <div class="cat-content">

                            <div class="cat-icon">
                                <i class="fas fa-folder-open"></i>
                            </div>

                            <div class="cat-name">
                                {{ $category->name }}
                            </div>

                            <div class="cat-count">
                                <span class="cat-count-number">
                                    {{ $category->surveys_count ?? 0 }}
                                </span>

                                سوال ثبت شده
                            </div>

                            <div class="category-status {{ $category->is_active == 1 ? 'active' : 'inactive' }}">
                                <i class="fas {{ $category->is_active == 1 ? 'fa-check-circle' : 'fa-circle-xmark' }}"></i>

                                {{ $category->is_active == 1 ? 'فعال' : 'غیرفعال' }}
                            </div>

                        </div>

                    </a>

                    <div class="category-actions">

                        <button
                            type="button"
                            class="category-action deactivate"
                            onclick="deactivateCategorySurveys({{ $category->id }})"
                            title="غیرفعال کردن همه سوالات"
                        >
                            <i class="fas fa-power-off"></i>
                        </button>

                        <button
                            type="button"
                            class="category-action edit"
                            onclick="editCategory({{ $category->id }}, @js($category->name))"
                            title="ویرایش دسته‌بندی"
                        >
                            <i class="fas fa-pen"></i>
                        </button>

                        <button
                            type="button"
                            class="category-action delete"
                            onclick="deleteCategory({{ $category->id }})"
                            title="حذف دسته‌بندی"
                        >
                            <i class="fas fa-trash"></i>
                        </button>

                    </div>

                </div>
            </div>
        @empty
            <div class="col-12" id="emptyCategories">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div class="empty-title">هنوز دسته‌بندی‌ای ثبت نشده است</div>
                    <p class="empty-text">
                        برای شروع، یک دسته‌بندی جدید ایجاد کنید.
                    </p>
                </div>
            </div>
        @endforelse
    </div>
</div>


</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addCategoryForm">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-folder-plus text-primary"></i>
                        افزودن دسته‌بندی جدید
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>


            <div class="modal-body">
                <label for="categoryName" class="form-label">
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

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    انصراف
                </button>

                <button type="submit" class="btn btn-primary" id="addCategorySubmit">
                    <span id="addCategoryText">
                        <i class="fas fa-plus me-1"></i>
                        افزودن دسته
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

<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editCategoryForm">
                <input type="hidden" id="editCategoryId" name="id">


            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit text-primary"></i>
                    ویرایش دسته‌بندی
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label for="editCategoryName" class="form-label">
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

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    انصراف
                </button>

                <button type="submit" class="btn btn-primary" id="editCategorySubmit">
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

function toggleSurvey(type, element) {
    const isStudent = type === 'student';
    const spinner = document.getElementById(isStudent ? 'studentSpinner' : 'teacherSpinner');
    const badge = document.getElementById(isStudent ? 'studentStatusBadge' : 'teacherStatusBadge');
    const url = isStudent
        ? '{{ route("admin.toggle-student-survey") }}'
        : '{{ route("admin.toggle-teacher-survey") }}';

    const previousStatus = !element.checked;

    spinner.style.display = 'block';
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
        badge.textContent = status ? 'فعال' : 'غیرفعال';

        toastr.success(data.message || 'وضعیت با موفقیت تغییر کرد');
    })
    .catch(error => {
        console.error(error);

        spinner.style.display = 'none';
        element.disabled = false;
        element.checked = previousStatus;

        toastr.error('خطا در ارتباط با سرور');
    });
}

function deactivateCategorySurveys(categoryId) {
    if (!confirm('آیا از غیرفعال کردن تمام سوالات این دسته مطمئن هستید؟\n\nتمام سوالات این دسته غیرفعال خواهند شد.')) {
        return;
    }

    const categoryItem = document.querySelector(`.category-item[data-category-id="${categoryId}"]`);
    const button = categoryItem ? categoryItem.querySelector('.btn-deactivate') : null;

    let originalHtml = '';

    if (button) {
        originalHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    }

    fetch(`{{ url('/admin/survey/category') }}/${categoryId}/deactivate-all`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
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

        toastr.success(data.message);
    })
    .catch(error => {
        console.error(error);
        toastr.error(error.message || 'خطا در غیرفعال کردن سوالات');
    })
    .finally(() => {
        if (button) {
            button.disabled = false;
            button.innerHTML = originalHtml;
        }
    });
}

document.getElementById('addCategoryForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const form = this;
    const input = document.getElementById('categoryName');
    const errorElement = document.getElementById('categoryNameError');
    const submitButton = document.getElementById('addCategorySubmit');
    const submitText = document.getElementById('addCategoryText');
    const loading = document.getElementById('addCategoryLoading');
    const name = input.value.trim();

    if (!name) {
        input.classList.add('is-invalid');
        errorElement.textContent = 'نام دسته‌بندی را وارد کنید.';
        return;
    }

    input.classList.remove('is-invalid');
    errorElement.textContent = '';

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
        body: JSON.stringify({
            name: name
        })
    })
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
        const container = document.getElementById('categoriesContainer');
        const empty = document.getElementById('emptyCategories');

        if (empty) {
            empty.remove();
        }

        const item = document.createElement('div');

        item.className = 'col-xl-3 col-lg-4 col-md-6 category-item';
        item.dataset.categoryId = category.id;

        item.innerHTML = `
            <div class="category-card-wrapper">
                <a href="{{ url('/admin/survey/category') }}/${category.id}" class="category-card">
                    <div class="cat-content">
                        <div class="cat-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div class="cat-name">${escapeHtml(category.name)}</div>
                        <div class="cat-count">
                            <span class="cat-count-number">0</span>
                            سوال ثبت شده
                        </div>
                    </div>
                </a>

                <div class="category-actions">
                    <button type="button" class="category-action deactivate" onclick="deactivateCategorySurveys(${category.id})" title="غیرفعال کردن همه سوالات">
                        <i class="fas fa-power-off"></i>
                    </button>

                    <button type="button" class="category-action edit" onclick="editCategory(${category.id}, ${JSON.stringify(category.name)})" title="ویرایش دسته‌بندی">
                        <i class="fas fa-pen"></i>
                    </button>

                    <button type="button" class="category-action delete" onclick="deleteCategory(${category.id})" title="حذف دسته‌بندی">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        container.prepend(item);

        const countElement = document.getElementById('categoryCount');

        countElement.textContent = parseInt(countElement.textContent) + 1;

        form.reset();

        const modal = bootstrap.Modal.getInstance(
            document.getElementById('addCategoryModal')
        );

        modal.hide();

        toastr.success(data.message);
    })
    .catch(error => {
        console.error(error);

        if (error.errors && error.errors.name) {
            input.classList.add('is-invalid');
            errorElement.textContent = error.errors.name[0];
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

function editCategory(id, name) {
    document.getElementById('editCategoryId').value = id;
    document.getElementById('editCategoryName').value = name;
    document.getElementById('editCategoryName').classList.remove('is-invalid');
    document.getElementById('editCategoryNameError').textContent = '';

    const modal = new bootstrap.Modal(
        document.getElementById('editCategoryModal')
    );

    modal.show();
}

document.getElementById('editCategoryForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const id = document.getElementById('editCategoryId').value;
    const name = document.getElementById('editCategoryName').value.trim();

    const input = document.getElementById('editCategoryName');
    const errorElement = document.getElementById('editCategoryNameError');

    const submitButton = document.getElementById('editCategorySubmit');
    const submitText = document.getElementById('editCategoryText');
    const loading = document.getElementById('editCategoryLoading');

    if (!name) {
        input.classList.add('is-invalid');
        errorElement.textContent = 'نام دسته‌بندی را وارد کنید.';
        return;
    }

    input.classList.remove('is-invalid');
    errorElement.textContent = '';

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
        body: JSON.stringify({
            name: name
        })
    })
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

        const card = document.querySelector(
            `.category-item[data-category-id="${category.id}"]`
        );

        if (card) {
            const nameElement = card.querySelector('.cat-name');
            const editButton = card.querySelector('.category-action.edit');

            if (nameElement) {
                nameElement.textContent = category.name;
            }

            if (editButton) {
                editButton.setAttribute(
                    'onclick',
                    `editCategory(${category.id}, ${JSON.stringify(category.name)})`
                );
            }
        }

        const modal = bootstrap.Modal.getInstance(
            document.getElementById('editCategoryModal')
        );

        modal.hide();

        toastr.success(data.message);
    })
    .catch(error => {
        console.error(error);

        if (error.errors && error.errors.name) {
            input.classList.add('is-invalid');
            errorElement.textContent = error.errors.name[0];
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

        if (!response.ok) {
            throw data;
        }

        return data;
    })
    .then(data => {
        if (!data.success) {
            throw data;
        }

        const item = document.querySelector(
            `.category-item[data-category-id="${id}"]`
        );

        if (item) {
            item.remove();
        }

        const countElement = document.getElementById('categoryCount');

        countElement.textContent =
            Math.max(0, parseInt(countElement.textContent) - 1);

        const remainingItems =
            document.querySelectorAll('.category-item');

        if (remainingItems.length === 0) {
            document.getElementById('categoriesContainer').innerHTML = `
                <div class="col-12" id="emptyCategories">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div class="empty-title">
                            هنوز دسته‌بندی‌ای ثبت نشده است
                        </div>
                        <p class="empty-text">
                            برای شروع، یک دسته‌بندی جدید ایجاد کنید.
                        </p>
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

function escapeHtml(value) {
    if (!value) {
        return '';
    }

    const div = document.createElement('div');

    div.textContent = value;

    return div.innerHTML;
}

document.getElementById('categoryName').addEventListener('input', function() {
    this.classList.remove('is-invalid');
    document.getElementById('categoryNameError').textContent = '';
});

document.getElementById('editCategoryName').addEventListener('input', function() {
    this.classList.remove('is-invalid');
    document.getElementById('editCategoryNameError').textContent = '';
});

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