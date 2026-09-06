@extends('layout.master')

@section('title')
    ملیسان | پیشرفت درسی
@endsection

@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="{{ asset('css/progress.css') }}">
@endsection

@section('mohtava')
    <div class="progress-container">
        {{-- HEADER --}}
        <div class="progress-header">
            <div>
                <h2>
                    <i class="fas fa-chart-line"></i>
                    پیشرفت درسی
                </h2>
                <div class="subtitle">
                    <i class="fas fa-book-open" style="margin-left:6px;color:#1e6f9f;"></i>
                    {{ $course->name }}
                </div>
            </div>
            @include('layout.backbtn')
        </div>

        {{-- SCORE CARD --}}
        <div class="score-card">
            <div class="total-score">{{ number_format($nomre['total'], 2) }}</div>
            <div class="total-label">نمره نهایی (از ۱۰۰)</div>
            <div class="score-sub">
                <div class="item">
                    <span class="num">{{ number_format($nomre['q'], 2) }}</span>
                    <span class="label">سوالات</span>
                </div>
                <div class="item">
                    <span class="num">{{ number_format($nomre['d'], 2) }}</span>
                    <span class="label">گزارش‌ها</span>
                </div>
                <div class="item">
                    <span class="num">{{ number_format($nomre['e'], 2) }}</span>
                    <span class="label">تکالیف</span>
                </div>
                <div class="item">
                    <span class="num">{{ number_format($nomre['pish'], 2) }}</span>
                    <span class="label">پیشرفت</span>
                </div>
                <div class="item">
                    <span class="num">{{ number_format($nomre['talash'], 2) }}</span>
                    <span class="label">تلاش</span>
                </div>
                @if($nomre['final'] !== null)
                    <div class="item">
                        <span class="num" style="color:#ffd700;">{{ number_format($nomre['final'], 2) }}</span>
                        <span class="label">پایان ترم</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- COLLAPSIBLE SECTIONS --}}

        {{-- 1. فعالیت کلاسی --}}
        <div class="collapsible-section">
            <div class="collapsible-header" onclick="toggleCollapsible(this)">
                <div class="title">
                    <i class="fas fa-users"></i>
                    فعالیت کلاسی
                </div>
                <div class="info">
                    <span>سقف: <strong>30</strong></span>
                    <span>تاکنون: <strong class="score">{{ number_format($kelasi, 2) }}</strong></span>
                    <span><i class="fas fa-chevron-down" style="font-size:12px;color:#6b7a8f;"></i></span>
                </div>
            </div>
            <div class="collapsible-body">
                <div class="table-wrap">
                    <table class="info-table">
                        <thead>
                            <tr>
                                <th>فعالیت</th>
                                <th style="text-align:center;">تعداد</th>
                                <th style="text-align:center;">امتیاز</th>
                                <th style="text-align:center;">سقف</th>
                                <th>پیشرفت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $kelasiRows = [
                                    ['label' => 'طرح سوال', 'count' => $questions['all'], 'score' => $score_soal, 'max' => 8],
                                    ['label' => 'ارسال گزارش', 'count' => $discs['all'], 'score' => $score_gozaresh, 'max' => 5],
                                    ['label' => 'انجام داوری', 'count' => $davarii['q'] + $davarii['gozaresh'], 'score' => $score_davari, 'max' => 8],
                                    ['label' => 'شرکت در خودآزمایی', 'count' => $count_azmoon, 'score' => $score_azmoon, 'max' => 9],
                                ];
                            @endphp
                            @foreach($kelasiRows as $row)
                                @php $pct = $row['max'] > 0 ? min(($row['score'] / $row['max']) * 100, 100) : 0; @endphp
                                <tr>
                                    <td data-label="فعالیت">{{ $row['label'] }}</td>
                                    <td data-label="تعداد" style="text-align:center;">{{ $row['count'] }}</td>
                                    <td data-label="امتیاز" style="text-align:center;font-weight:700;color:#1e6f9f;">
                                        {{ number_format($row['score'], 2) }}</td>
                                    <td data-label="سقف" style="text-align:center;">{{ $row['max'] }}</td>
                                    <td data-label="پیشرفت">
                                        <div class="progress-bar-wrap">
                                            <div class="bar">
                                                <div class="fill" style="width:{{ $pct }}%;"></div>
                                            </div>
                                            <span class="pct">{{ number_format($pct, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 2. پیشرفت درسی --}}
        <div class="collapsible-section">
            <div class="collapsible-header" onclick="toggleCollapsible(this)">
                <div class="title">
                    <i class="fas fa-graduation-cap"></i>
                    پیشرفت درسی
                </div>
                <div class="info">
                    <span>سقف: <strong>70</strong></span>
                    <span>تاکنون: <strong class="score">{{ number_format($pishraft, 2) }}</strong></span>
                    <span><i class="fas fa-chevron-down" style="font-size:12px;color:#6b7a8f;"></i></span>
                </div>
            </div>
            <div class="collapsible-body">
                <div class="table-wrap">
                    <table class="info-table">
                        <thead>
                            <tr>
                                <th>فعالیت</th>
                                <th style="text-align:center;">وضعیت</th>
                                <th style="text-align:center;">امتیاز</th>
                                <th style="text-align:center;">سقف</th>
                                <th>پیشرفت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $pishRows = [
                                    ['label' => 'کیفیت سوال', 'status' => $q_scores, 'score' => $score_pish_soal, 'max' => 12],
                                    ['label' => 'کیفیت گزارش', 'status' => $d_scores, 'score' => $score_pish_gozaresh, 'max' => 10],
                                    ['label' => 'کیفیت خودآزمایی', 'status' => $qu_scores, 'score' => $score_pish_azmoon, 'max' => 24],
                                    ['label' => 'کیفیت داوری ها', 'status' => ($q_scores + $d_scores + $qu_scores + 5) / 4, 'score' => $score_keifiat, 'max' => 14],
                                ];
                            @endphp
                            @foreach($pishRows as $row)
                                @php $pct = $row['max'] > 0 ? min(($row['score'] / $row['max']) * 100, 100) : 0; @endphp
                                <tr>
                                    <td data-label="فعالیت">{{ $row['label'] }}</td>
                                    <td data-label="وضعیت" style="text-align:center;">{{ number_format($row['status'], 2) }}
                                    </td>
                                    <td data-label="امتیاز" style="text-align:center;font-weight:700;color:#1e6f9f;">
                                        {{ number_format($row['score'], 2) }}</td>
                                    <td data-label="سقف" style="text-align:center;">{{ $row['max'] }}</td>
                                    <td data-label="پیشرفت">
                                        <div class="progress-bar-wrap">
                                            <div class="bar">
                                                <div class="fill" style="width:{{ $pct }}%;"></div>
                                            </div>
                                            <span class="pct">{{ number_format($pct, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 3. ارزشیابی مستمر --}}
        <div class="collapsible-section" style="border-right-color:#ff9800;">
            <div class="collapsible-header" onclick="toggleCollapsible(this)">
                <div class="title">
                    <i class="fas fa-clipboard-check"></i>
                    ارزشیابی مستمر
                </div>
                <div class="info">
                    <span>سقف: <strong>20</strong></span>
                    <span>تاکنون: <strong class="score"
                            style="color:#ff9800;">{{ number_format($mostamer_score, 2) }}</strong></span>
                    <span><i class="fas fa-chevron-down" style="font-size:12px;color:#6b7a8f;"></i></span>
                </div>
            </div>
            <div class="collapsible-body">
                <div class="table-wrap">
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <td data-label="نمره خام">نمره خام</td>
                                <td data-label="مقدار" style="font-weight:700;">{{ number_format($mostamer, 2) }}</td>
                            </tr>
                            <tr>
                                <td data-label="نمره نهایی">نمره نهایی (از ۲۰)</td>
                                <td data-label="مقدار" style="font-weight:700;color:#ff9800;font-size:18px;">
                                    {{ number_format($mostamer_score, 2) }}</td>
                            </tr>
                            <tr>
                                <td data-label="توضیح">توضیح</td>
                                <td data-label="مقدار">نمره ارزشیابی مستمر بر اساس فعالیت کلاسی و پیشرفت درسی محاسبه می‌شود.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- CHART --}}
        <div class="chart-wrapper">
            <h4>
                <i class="fas fa-chart-bar"></i>
                نمودار عملکرد
            </h4>
            <div id="progressChart"></div>
        </div>
    </div>

    <script>
        // ===== Toggle Collapsible =====
        function toggleCollapsible(header) {
            const body = header.nextElementSibling;
            const icon = header.querySelector('.fa-chevron-down');
            body.classList.toggle('open');
            icon.style.transform = body.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
        }

        // ===== Open first collapsible by default =====
        document.addEventListener('DOMContentLoaded', function () {
            const firstBody = document.querySelector('.collapsible-body');
            if (firstBody) {
                firstBody.classList.add('open');
                const firstIcon = document.querySelector('.collapsible-header .fa-chevron-down');
                if (firstIcon) firstIcon.style.transform = 'rotate(180deg)';
            }
        });

        // ===== Chart =====
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded',
                        borderRadius: 4
                    }
                },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                series: [{
                    name: 'نمره کسب شده',
                    data: [
                        {{ number_format($nomre['q'], 2) }},
                        {{ number_format($nomre['d'], 2) }},
                        {{ number_format($nomre['e'], 2) }},
                        {{ number_format($nomre['pish'], 2) }},
                        {{ number_format($nomre['talash'], 2) }}
                    ]
                }],
                xaxis: {
                    categories: ['سوال', 'گزارش', 'تکلیف', 'پیشرفت', 'تلاش']
                },
                yaxis: {
                    title: { text: 'نمره' },
                    min: 0,
                    max: function () {
                        return Math.max(
                            {{ $nomre['q'] + 5 }},
                            {{ $nomre['d'] + 5 }},
                            {{ $nomre['e'] + 5 }},
                            {{ $nomre['pish'] + 5 }},
                            {{ $nomre['talash'] + 5 }},
                            20
                        );
                    }
                },
        fill: { opacity: 1 },
        colors: ['#1e6f9f', '#4caf50', '#ff9800', '#9c27b0', '#f44336'],
            legend: {
            position: 'bottom',
                horizontalAlign: 'center'
        }
            };

        var chart = new ApexCharts(document.querySelector("#progressChart"), options);
        chart.render();
        });
    </script>
@endsection