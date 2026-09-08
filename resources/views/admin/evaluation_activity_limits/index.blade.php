@extends('layout.master')

@section('title')
    ملیسان | سقف فعالیت‌های ارزشیابی
@endsection

@section('head')
    <style>
        .page-wrapper {
            padding: 25px;
        }

        .page-header {
            background: #fff;
            border-radius: 15px;
            padding: 20px 25px;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .page-header h4 {
            margin: 0;
            font-weight: 700;
            color: #333;
        }

        .page-header p {
            margin: 7px 0 0;
            color: #888;
            font-size: 14px;
        }

        .content-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            color: #555;
            font-weight: 700;
            padding: 15px;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 15px;
        }

        .activity-name {
            font-weight: 600;
            color: #333;
        }

        .score-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 70px;
            padding: 7px 14px;
            border-radius: 20px;
            background: #f0f7ff;
            color: #0d6efd;
            font-weight: 700;
        }

        .btn-edit {
            border: none;
            background: #fff3cd;
            color: #856404;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-edit:hover {
            background: #ffe69c;
        }

        .btn-add {
            border: none;
            background: #198754;
            color: #fff;
            padding: 10px 18px;
            border-radius: 9px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-add:hover {
            background: #157347;
            color: #fff;
        }

        .empty-box {
            text-align: center;
            padding: 50px 20px;
            color: #888;
        }

        .empty-box i {
            font-size: 45px;
            margin-bottom: 15px;
            color: #ccc;
        }

        .form-label {
            font-weight: 600;
            color: #444;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            font-weight: 700;
        }

        .form-control {
            border-radius: 8px;
            min-height: 45px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #86b7fe;
        }

        .btn-save {
            border: none;
            background: #0d6efd;
            color: #fff;
            border-radius: 8px;
            padding: 10px 25px;
        }

        .btn-save:hover {
            background: #0b5ed7;
        }

        .total-row {
            background: #f8f9fa;
            font-weight: 700;
        }

        .total-score {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 80px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
        }

        .total-score.valid {
            background: #d1e7dd;
            color: #0f5132;
        }

        .total-score.invalid {
            background: #f8d7da;
            color: #842029;
        }

        .score-error {
            margin-bottom: 20px;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .page-wrapper {
                padding: 15px;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .page-header .btn-add {
                width: 100%;
            }

            .table-responsive {
                border-radius: 10px;
            }
        }
    </style>
@endsection

@section('mohtava')

    @php
        $totalScore = $activities->sum('max_score');
    @endphp

    <div class="page-wrapper">

        <div class="page-header">

            <div>
                <h4>
                    <i class="fas fa-chart-line me-2"></i>
                    سقف فعالیت‌های ارزشیابی
                </h4>

                <p>
                    مدیریت فعالیت‌ها و تعیین سقف امتیاز قابل دریافت
                </p>
            </div>

            <button type="button"
                    class="btn-add"
                    data-bs-toggle="modal"
                    data-bs-target="#addActivityModal">

                <i class="fas fa-plus me-1"></i>
                افزودن فعالیت

            </button>

        </div>

        {{-- خطای مجموع امتیازها --}}
        @if($totalScore != 100)

            <div class="alert alert-danger alert-dismissible fade show score-error"
                 role="alert">

                <i class="fas fa-exclamation-triangle me-1"></i>

                <strong>خطا:</strong>

                مجموع سقف امتیاز فعالیت‌ها باید دقیقاً
                <strong>۱۰۰</strong>
                باشد.

                <span class="ms-2">
                    مجموع فعلی:
                    <strong>{{ number_format($totalScore) }}</strong>
                </span>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>

            </div>

        @endif

        {{-- خطاهای Validation --}}
        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif

        <div class="content-card">

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                    <tr>

                        <th style="width: 80px;">
                            ردیف
                        </th>

                        <th>
                            فعالیت
                        </th>

                        <th style="width: 180px;">
                            سقف امتیاز
                        </th>

                        <th style="width: 130px;">
                            ویرایش
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($activities as $index => $activity)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>

                                <span class="activity-name">
                                    {{ $activity->activity }}
                                </span>

                            </td>

                            <td>

                                <span class="score-badge">
                                    {{ number_format($activity->max_score) }}
                                </span>

                            </td>

                            <td>

                                <button type="button"
                                        class="btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editActivityModal"
                                        data-id="{{ $activity->id }}"
                                        data-activity="{{ $activity->activity }}"
                                        data-score="{{ $activity->max_score }}"
                                        onclick="editActivity(this)">

                                    <i class="fas fa-edit me-1"></i>

                                    ویرایش

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4">

                                <div class="empty-box">

                                    <i class="fas fa-chart-line d-block"></i>

                                    <div>
                                        هنوز فعالیتی ثبت نشده است.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    @if($activities->count() > 0)

                        <tr class="total-row">

                            <td colspan="2">
                                مجموع امتیازها
                            </td>

                            <td>

                                <span class="total-score {{ $totalScore == 100 ? 'valid' : 'invalid' }}">

                                    {{ number_format($totalScore) }}

                                </span>

                            </td>

                            <td>

                                @if($totalScore == 100)

                                    <span class="text-success">
                                        <i class="fas fa-check-circle"></i>
                                        صحیح
                                    </span>

                                @else

                                    <span class="text-danger">
                                        <i class="fas fa-times-circle"></i>
                                        نامعتبر
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endif

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Modal افزودن فعالیت --}}

    <div class="modal fade"
         id="addActivityModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form action="{{ route('admin.evaluation-activity-limits.store') }}"
                      method="POST">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">
                            افزودن فعالیت
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">
                                فعالیت
                            </label>

                            <input type="text"
                                   name="activity"
                                   class="form-control"
                                   placeholder="مثلاً پاسخ به سوالات"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                سقف امتیاز
                            </label>

                            <input type="number"
                                   name="max_score"
                                   class="form-control"
                                   min="0"
                                   placeholder="مثلاً 100"
                                   required>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                            انصراف

                        </button>

                        <button type="submit"
                                class="btn-save">

                            <i class="fas fa-save me-1"></i>

                            ذخیره

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- Modal ویرایش فعالیت --}}

    <div class="modal fade"
         id="editActivityModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form id="editActivityForm"
                      method="POST">

                    @csrf
                    @method('PUT')

                    <div class="modal-header">

                        <h5 class="modal-title">
                            ویرایش فعالیت
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">
                                فعالیت
                            </label>

                            <input type="text"
                                   id="editActivity"
                                   name="activity"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                سقف امتیاز
                            </label>

                            <input type="number"
                                   id="editScore"
                                   name="max_score"
                                   class="form-control"
                                   min="0"
                                   required>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                            انصراف

                        </button>

                        <button type="submit"
                                class="btn-save">

                            <i class="fas fa-save me-1"></i>

                            ذخیره تغییرات

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

@section('js')
    <script>

        function editActivity(button) {

            const id = button.getAttribute('data-id');

            const activity = button.getAttribute('data-activity');

            const score = button.getAttribute('data-score');

            document.getElementById('editActivity').value = activity;

            document.getElementById('editScore').value = score;

            document.getElementById('editActivityForm').action =
                "{{ url('/admin/evaluation-activity-limits/update') }}/" + id;

        }

    </script>
@endsection