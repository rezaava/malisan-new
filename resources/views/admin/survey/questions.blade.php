@extends('layout.master')

@section('title')
    ملیسان | سوالات {{ $category->name }}
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-surveys.css')}}">
<link rel="stylesheet" href="{{asset('css/badge.css')}}">
<script src="{{ asset('ChartJS.js') }}"></script>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>

.card {
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
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

.table > :not(caption) > * > * {
    padding: 12px;
    vertical-align: middle;
}

.table thead th {
    background-color: #f8f9fa;
    font-weight: 600;
    font-size: 13px;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr {
    transition: background 0.15s;
}

.table tbody tr:hover {
    background-color: #f8f9ff;
}

.survey-text {
    max-width: 350px;
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

.action-btn {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    color: white;
    background: #0dcaf0;
}

.action-btn:hover {
    transform: scale(1.05);
    color: white;
}

.empty-state {
    padding: 50px 20px;
    text-align: center;
    color: #6c757d;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.3;
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

.modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}


/* ============================================
   فرم ایجاد نظرسنجی
============================================ */

.create-survey-card {
    margin-bottom: 24px;
}

.create-survey-card .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.create-survey-title {
    margin: 0;
    font-weight: 600;
}

.create-survey-title i {
    color: #0d6efd;
    margin-left: 8px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #343a40;
}

.required {
    color: #dc3545;
}

.form-control,
.form-select {
    width: 100%;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 11px 14px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.1);
    outline: none;
}

.helper-text {
    display: block;
    margin-top: 7px;
    font-size: 12px;
    color: #6c757d;
}

.options-group.hidden {
    display: none;
}

.btn-submit {
    border: none;
    border-radius: 10px;
    padding: 11px 22px;
    background: #0d6efd;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-submit:hover {
    background: #0b5ed7;
    transform: translateY(-1px);
}

.alert-success,
.alert-error {
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d1e7dd;
    color: #0f5132;
}

.alert-error {
    background: #f8d7da;
    color: #842029;
}

.alert-error ul {
    margin: 0;
    padding-right: 20px;
}

</style>

@endsection


@section('mohtava')

<div class="container-fluid py-4">

    {{-- عنوان صفحه --}}
    <div class="row mb-4">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-1 fw-bold">
                        سوالات نظرسنجی
                    </h4>

                    <p class="text-muted mb-0">
                        دسته:
                        <strong>
                            {{ $category->name }}
                        </strong>
                    </p>
                </div>

                <a
                    href="{{ route('admin_survey') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="fas fa-arrow-right me-1"></i>
                    بازگشت به دسته‌ها
                </a>

            </div>

        </div>
    </div>


    {{-- ============================================
         فرم ایجاد نظرسنجی
    ============================================ --}}

    <div class="row mb-4">

        <div class="col-12">

            <div class="card create-survey-card">

                <div class="card-header">

                    <h5 class="create-survey-title">
                        <i class="fas fa-plus-circle"></i>
                        ایجاد نظرسنجی جدید
                    </h5>

                </div>


                <div class="card-body">

                    @if(session('success'))

                        <div class="alert-success">
                            {{ session('success') }}
                        </div>

                    @endif


                    @if(session('error'))

                        <div class="alert-error">
                            {{ session('error') }}
                        </div>

                    @endif


                    @if($errors->any())

                        <div class="alert-error">

                            <ul>

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('survey.store') }}"
                        enctype="multipart/form-data"
                    >

                        @csrf

                        {{-- دسته‌بندی --}}
                        <input
                            type="hidden"
                            name="cat_id"
                            value="{{ $category->id }}"
                        >


                        {{-- متن سوال --}}
                        <div class="form-group">

                            <label for="question">
                                عنوان سوال
                                <span class="required">*</span>
                            </label>

                            <textarea
                                id="question"
                                name="question"
                                class="form-control"
                                rows="3"
                                placeholder="متن سوال را وارد کنید..."
                            >{{ old('question') }}</textarea>

                        </div>


                        {{-- نوع پاسخ --}}
                        <div class="form-group">

                            <label for="answer">
                                نوع پاسخ دهی
                                <span class="required">*</span>
                            </label>

                            <select
                                id="answer"
                                name="answer"
                                class="form-select"
                                onchange="toggleOptions(this.value)"
                            >

                                <option value="1"
                                    {{ old('answer') == 1 ? 'selected' : '' }}>
                                    پاسخ کوتاه
                                </option>

                                <option value="2"
                                    {{ old('answer', 2) == 2 ? 'selected' : '' }}>
                                    چند گزینه‌ای (انتخاب یک گزینه)
                                </option>

                                <option value="3"
                                    {{ old('answer') == 3 ? 'selected' : '' }}>
                                    چند گزینه‌ای (انتخاب چند گزینه)
                                </option>

                            </select>

                        </div>


                        {{-- گزینه‌ها --}}
                        <div
                            class="form-group options-group"
                            id="optionsGroup"
                        >

                            <label for="options">

                                گزینه‌ها
                                <span class="required">*</span>

                            </label>

                            <textarea
                                id="options"
                                name="options"
                                class="form-control"
                                rows="4"
                                placeholder="هر گزینه را در یک خط وارد کنید..."
                            >{{ old('options') }}</textarea>

                            <span class="helper-text">
                                هر گزینه را در یک خط جداگانه وارد کنید
                            </span>

                        </div>


                        {{-- دکمه ثبت --}}
                        <button
                            type="submit"
                            class="btn-submit"
                        >

                            <i class="fas fa-save"></i>

                            ایجاد نظرسنجی

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================
         جدول سوالات
    ============================================ --}}

    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-list me-2 text-primary"></i>

                        سوالات دسته «{{ $category->name }}»

                    </h5>

                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">

                        <i class="fas fa-database me-1"></i>

                        {{ $surveys->count() }} مورد

                    </span>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th style="width: 60px;">
                                        #
                                    </th>

                                    <th>
                                        متن سوال
                                    </th>

                                    <th style="width: 120px;">
                                        نوع
                                    </th>

                                    <th style="width: 120px;">
                                        وضعیت
                                    </th>

                                    <th style="width: 140px;">
                                        تاریخ ایجاد
                                    </th>

                                    <th style="width: 70px;">
                                        عملیات
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($surveys as $survey)

                                    <tr>

                                        <td class="fw-bold text-muted">
                                            {{ $survey->id }}
                                        </td>


                                        <td>

                                            <span
                                                class="survey-text"
                                                title="{{ $survey->text }}"
                                            >
                                                {{ Str::limit($survey->text, 70) }}
                                            </span>

                                        </td>


                                        <td>

                                            @switch($survey->type)

                                                @case(1)

                                                    <span class="badge bg-info bg-opacity-15 text-info badge-type">
                                                        متن باز
                                                    </span>

                                                @break


                                                @case(2)

                                                    <span class="badge bg-primary bg-opacity-15 text-primary badge-type">
                                                        تک‌انتخابی
                                                    </span>

                                                @break


                                                @case(3)

                                                    <span class="badge bg-warning bg-opacity-15 text-warning badge-type">
                                                        چندانتخابی
                                                    </span>

                                                @break


                                                @default

                                                    <span class="badge bg-secondary badge-type">
                                                        نامشخص
                                                    </span>

                                            @endswitch

                                        </td>


                                        <td>

                                            <span
                                                class="status-badge {{ $survey->active ? 'active' : 'inactive' }}"
                                            >
                                                {{ $survey->active ? 'فعال' : 'غیرفعال' }}
                                            </span>

                                        </td>


                                        <td>

                                            <span
                                                class="text-muted"
                                                style="font-size: 13px;"
                                            >
                                                {{ \Hekmatinasser\Verta\Verta::instance($survey->created_at)->format('Y/m/d') }}
                                            </span>

                                        </td>


                                        <td>

                                            <button
                                                class="action-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#surveyDetailModal"
                                                data-id="{{ $survey->id }}"
                                                title="مشاهده جزئیات"
                                            >

                                                <i class="fas fa-eye"></i>

                                            </button>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6">

                                            <div class="empty-state">

                                                <i class="fas fa-poll-h"></i>

                                                <h6 class="fw-normal">
                                                    هیچ سوالی در این دسته وجود ندارد
                                                </h6>

                                                <p class="text-muted small">
                                                    برای این دسته هنوز سوالی ثبت نشده است.
                                                </p>

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

</div>


{{-- ============================================
     Modal جزئیات
============================================ --}}

<div
    class="modal fade"
    id="surveyDetailModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title fw-semibold">

                    <i class="fas fa-poll-h me-2 text-primary"></i>

                    جزئیات سوال

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div
                class="modal-body"
                id="surveyDetailBody"
            >

                <div class="text-center py-5">

                    <div
                        class="spinner-border text-primary"
                        role="status"
                    ></div>

                    <p class="mt-3 text-muted">
                        در حال دریافت اطلاعات...
                    </p>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >

                    <i class="fas fa-times me-1"></i>

                    بستن

                </button>

            </div>

        </div>

    </div>

</div>

@endsection


@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

/*
|--------------------------------------------------------------------------
| نمایش / مخفی کردن گزینه‌ها
|--------------------------------------------------------------------------
*/

function toggleOptions(value) {

    var optionsGroup = document.getElementById('optionsGroup');

    if (!optionsGroup) {
        return;
    }

    if (value == '1') {

        optionsGroup.classList.add('hidden');

    } else {

        optionsGroup.classList.remove('hidden');

    }
}


/*
|--------------------------------------------------------------------------
| اجرای اولیه فرم
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    var answerSelect = document.getElementById('answer');

    if (answerSelect) {

        toggleOptions(answerSelect.value);

    }


    /*
    |--------------------------------------------------------------------------
    | Modal جزئیات
    |--------------------------------------------------------------------------
    */

    const modal = document.getElementById('surveyDetailModal');

    if (!modal) {
        return;
    }


    modal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;

        const surveyId = button.getAttribute('data-id');

        const body = document.getElementById('surveyDetailBody');


        body.innerHTML = `

            <div class="text-center py-5">

                <div
                    class="spinner-border text-primary"
                    role="status"
                    style="width: 3rem; height: 3rem;"
                ></div>

                <p class="mt-3 text-muted">
                    در حال دریافت اطلاعات...
                </p>

            </div>

        `;


        fetch(
            '{{ route("admin.survey.show", ":id") }}'
                .replace(':id', surveyId),
            {
                method: 'GET',

                headers: {
                    'Accept': 'application/json'
                }
            }
        )

        .then(response => response.json())

        .then(data => {

            if (!data.success) {

                body.innerHTML = `

                    <div class="alert alert-danger">
                        خطا در دریافت اطلاعات
                    </div>

                `;

                return;
            }


            const survey = data.survey;

            const options = data.options;

            const totalVotes = data.total_votes;


            let typeText = 'نامشخص';


            if (survey.type == 1) {
                typeText = 'متن باز';
            }

            if (survey.type == 2) {
                typeText = 'تک‌انتخابی';
            }

            if (survey.type == 3) {
                typeText = 'چندانتخابی';
            }


            let html = `

                <div class="detail-section">

                    <h6>

                        <i class="fas fa-quote-right me-2 text-primary"></i>

                        متن سوال

                    </h6>

                    <p class="bg-white p-3 rounded border">

                        ${survey.text}

                    </p>

                </div>


                <div class="row g-3 mb-3">

                    <div class="col-md-6">

                        <div class="detail-section">

                            <h6>

                                <i class="fas fa-tag me-2 text-primary"></i>

                                نوع

                            </h6>

                            <p>

                                <span class="badge bg-primary px-3 py-2">

                                    ${typeText}

                                </span>

                            </p>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="detail-section">

                            <h6>

                                <i class="fas fa-circle me-2 text-primary"></i>

                                وضعیت

                            </h6>

                            <p>

                                <span class="status-badge ${survey.active ? 'active' : 'inactive'}">

                                    ${survey.active ? 'فعال' : 'غیرفعال'}

                                </span>

                            </p>

                        </div>

                    </div>

                </div>


                <div class="detail-section">

                    <h6>

                        <i class="fas fa-users me-2 text-primary"></i>

                        تعداد کل پاسخ‌ها

                    </h6>

                    <p>

                        <span class="badge bg-primary rounded-pill px-4 py-2 fs-6">

                            ${totalVotes}

                        </span>

                    </p>

                </div>

            `;


            if (survey.type > 1 && options.length > 0) {

                html += `

                    <div class="detail-section">

                        <h6>

                            <i class="fas fa-list-ul me-2 text-primary"></i>

                            گزینه‌ها و نتایج

                        </h6>

                        <div class="bg-white rounded border p-2">

                            <ul class="list-group list-group-flush">

                `;


                options.forEach(function (option) {

                    html += `

                        <li class="list-group-item d-flex justify-content-between align-items-center">

                            <span>
                                ${option.text}
                            </span>

                            <span class="badge bg-info rounded-pill px-3 py-2">

                                ${option.count}

                                (${option.percentage}%)

                            </span>

                        </li>

                    `;

                });


                html += `

                            </ul>

                        </div>

                    </div>

                `;

            }


            body.innerHTML = html;

        })


        .catch(function () {

            body.innerHTML = `

                <div class="alert alert-danger">

                    <i class="fas fa-exclamation-circle me-2"></i>

                    خطا در ارتباط با سرور

                </div>

            `;

        });

    });

});

</script>

@endsection