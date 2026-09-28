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

        .score-input {
            width: 110px;
            min-height: 42px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            text-align: center;
            font-weight: 700;
            font-size: 15px;
            outline: none;
        }

        .score-input:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
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

        .save-wrapper {
            margin-top: 25px;
            display: flex;
            justify-content: flex-end;
        }

        .btn-save {
            border: none;
            background: #0d6efd;
            color: #fff;
            border-radius: 8px;
            padding: 11px 25px;
            cursor: pointer;
        }

        .btn-save:hover {
            background: #0b5ed7;
        }

        .alert {
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

            .table-responsive {
                border-radius: 10px;
            }

            .save-wrapper {
                justify-content: stretch;
            }

            .btn-save {
                width: 100%;
            }
        }
    </style>
@endsection

@section('mohtava')

    <div class="page-wrapper">

        <div class="page-header">

            <div>
                <h4>
                    <i class="fas fa-chart-line me-2"></i>
                    سقف فعالیت‌های ارزشیابی
                </h4>

                <p>
                    تعیین سقف امتیاز فعالیت‌های ارزشیابی
                </p>
            </div>

        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif

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

        <form action="{{ route('admin.evaluation-activity-limits.update') }}" method="POST">

            @csrf

            @method('PUT')

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

                            <th style="width: 200px;">
                                سقف امتیاز
                            </th>

                        </tr>

                        </thead>

                        <tbody>

                        @foreach($activities as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    <span class="activity-name">
                                        {{ $item['name'] }}
                                    </span>
                                </td>

                                <td>

                                    <input
                                        type="number"
                                        name="{{ $item['key'] }}"
                                        value="{{ old($item['key'], $item['score']) }}"
                                        class="score-input score-field"
                                        min="0"
                                        required
                                    >

                                </td>

                            </tr>

                        @endforeach

                        <tr class="total-row">

                            <td colspan="2">
                                مجموع امتیازها
                            </td>

                            <td>

                                <span
                                    id="totalScore"
                                    class="total-score {{ $totalScore == 100 ? 'valid' : 'invalid' }}"
                                >
                                    {{ number_format($totalScore) }}
                                </span>

                            </td>

                        </tr>

                        </tbody>

                    </table>

                </div>

                <div class="save-wrapper">

                    <button type="submit" class="btn-save">

                        <i class="fas fa-save me-1"></i>

                        ذخیره تغییرات

                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection

@section('js')

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const fields = document.querySelectorAll('.score-field');
            const totalScore = document.getElementById('totalScore');

            function calculateTotal() {

                let total = 0;

                fields.forEach(function (field) {

                    const value = parseInt(field.value) || 0;

                    total += value;

                });

                totalScore.textContent = total.toLocaleString('fa-IR');

                if (total === 100) {

                    totalScore.classList.remove('invalid');
                    totalScore.classList.add('valid');

                } else {

                    totalScore.classList.remove('valid');
                    totalScore.classList.add('invalid');

                }
            }

            fields.forEach(function (field) {

                field.addEventListener('input', calculateTotal);

            });

            calculateTotal();

        });

    </script>

@endsection