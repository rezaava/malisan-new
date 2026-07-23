@extends('layout.master')

@section('title')
ملیسان | ارزشیابی دانشجو
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-evaluation.css')}}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('mohtava')
<div class="evaluation-container">
    <div class="evaluation-header">
        <h4 class="evaluation-title">
            نمره ارزشیابی مستمر از ۲۰ : 
            <span class="score">{{ number_format($mostamer_score ?? 0, 2) }}</span>
        </h4>
        <p class="evaluation-subtitle">برای مشاهده جزئیات گزینه مورد نظر را انتخاب کنید</p>
    </div>

    <div class="evaluation-grid">
        <div class="evaluation-list">
            <!-- ==========================================
                 فعالیت کلاسی
                 ========================================== -->
            <div class="accordion-item">
                <div class="accordion-header active" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>فعالیت کلاسی</span>
                    </div>
                    <div class="accordion-stats">
                        <span>سقف امتیاز: ۳۰</span>
                        <span class="score-badge">تاکنون: {{ number_format($kelasi ?? 0, 2) }}</span>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                </div>
                <div class="accordion-body active">
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>فعالیت</th>
                                <th style="text-align:center;">تعداد</th>
                                <th style="text-align:center;">امتیاز</th>
                                <th style="text-align:center;">سقف</th>
                                <th>نسبت به سقف</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $kelasiRows = [
                                    ['label'=>'طرح سوال', 'count'=>$questions['all'] ?? 0, 'score'=>$score_soal ?? 0, 'max'=>8],
                                    ['label'=>'ارسال گزارش / تحقیق', 'count'=>$discs['all'] ?? 0, 'score'=>$score_gozaresh ?? 0, 'max'=>5],
                                    ['label'=>'انجام داوری', 'count'=>($davarii['q'] ?? 0) + ($davarii['gozaresh'] ?? 0), 'score'=>$score_davari ?? 0, 'max'=>8],
                                    ['label'=>'شرکت در خودآزمایی', 'count'=>$count_azmoon ?? 0, 'score'=>$score_azmoon ?? 0, 'max'=>9],
                                ];
                            @endphp
                            @foreach($kelasiRows as $row)
                                @php $pct = $row['max'] > 0 ? min(($row['score']/$row['max'])*100, 100) : 0; @endphp
                                <tr>
                                    <td>{{ $row['label'] }}</td>
                                    <td style="text-align:center;">{{ $row['count'] }}</td>
                                    <td style="text-align:center; font-weight:600; color:#1e6f9f;">{{ number_format($row['score'], 2) }}</td>
                                    <td style="text-align:center;">{{ $row['max'] }}</td>
                                    <td>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar">
                                                <div class="fill" style="width: {{ $pct }}%;"></div>
                                            </div>
                                            <span class="progress-label">{{ number_format($pct, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ==========================================
                 پیشرفت درسی
                 ========================================== -->
            <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <i class="fas fa-graduation-cap"></i>
                        <span>پیشرفت درسی</span>
                    </div>
                    <div class="accordion-stats">
                        <span>سقف امتیاز: ۷۰</span>
                        <span class="score-badge">تاکنون: {{ number_format($pishraft ?? 0, 2) }}</span>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                </div>
                <div class="accordion-body">
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>فعالیت</th>
                                <th style="text-align:center;">وضعیت</th>
                                <th style="text-align:center;">امتیاز</th>
                                <th style="text-align:center;">سقف</th>
                                <th>نسبت به سقف</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $pishRows = [
                                    ['label'=>'سوال', 'status'=>$q_scores ?? 0, 'score'=>$score_pish_soal ?? 0, 'max'=>12],
                                    ['label'=>'گزارش', 'status'=>$d_scores ?? 0, 'score'=>$score_pish_gozaresh ?? 0, 'max'=>10],
                                    ['label'=>'خودآزمایی', 'status'=>$qu_scores ?? 0, 'score'=>$score_pish_azmoon ?? 0, 'max'=>24],
                                    ['label'=>'سطح داوری', 'status'=>10, 'score'=>5, 'max'=>10],
                                    ['label'=>'کیفیت فعالیت‌ها', 'status'=>($q_scores ?? 0 + $d_scores ?? 0 + $qu_scores ?? 0 + 5) / 4, 'score'=>$score_keifiat ?? 0, 'max'=>14],
                                ];
                            @endphp
                            @foreach($pishRows as $row)
                                @php $pct = $row['max'] > 0 ? min(($row['score']/$row['max'])*100, 100) : 0; @endphp
                                <tr>
                                    <td>{{ $row['label'] }}</td>
                                    <td style="text-align:center;">{{ number_format($row['status'], 2) }}</td>
                                    <td style="text-align:center; font-weight:600; color:#1e6f9f;">{{ number_format($row['score'], 2) }}</td>
                                    <td style="text-align:center;">{{ $row['max'] }}</td>
                                    <td>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar">
                                                <div class="fill" style="width: {{ $pct }}%;"></div>
                                            </div>
                                            <span class="progress-label">{{ number_format($pct, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ==========================================
                 ارزشیابی مستمر
                 ========================================== -->
            <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <i class="fas fa-clipboard-check"></i>
                        <span>ارزشیابی مستمر</span>
                    </div>
                    <div class="accordion-stats">
                        <span>سقف امتیاز: ۲۰</span>
                        <span class="score-badge">تاکنون: {{ number_format($mostamer_score ?? 0, 2) }}</span>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                </div>
                <div class="accordion-body">
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>بخش</th>
                                <th style="text-align:center;">امتیاز</th>
                                <th style="text-align:center;">سقف</th>
                                <th>نسبت به سقف</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $mostamerRows = [
                                    ['label'=>'فعالیت کلاسی', 'score'=>$kelasi ?? 0, 'max'=>30],
                                    ['label'=>'پیشرفت درسی', 'score'=>$pishraft ?? 0, 'max'=>70],
                                    ['label'=>'نمره نهایی', 'score'=>$mostamer_score ?? 0, 'max'=>20],
                                ];
                            @endphp
                            @foreach($mostamerRows as $row)
                                @php $pct = $row['max'] > 0 ? min(($row['score']/$row['max'])*100, 100) : 0; @endphp
                                <tr>
                                    <td>{{ $row['label'] }}</td>
                                    <td style="text-align:center; font-weight:600; color:#1e6f9f;">{{ number_format($row['score'], 2) }}</td>
                                    <td style="text-align:center;">{{ $row['max'] }}</td>
                                    <td>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar">
                                                <div class="fill" style="width: {{ $pct }}%;"></div>
                                            </div>
                                            <span class="progress-label">{{ number_format($pct, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==========================================
             نمودار
             ========================================== -->
        <div class="evaluation-chart">
            <div class="chart-card">
                <div class="chart-container">
                    <div id="chartColumnStacked"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    var sColStacked = {
        chart: {
            height: 350,
            type: 'bar',
            stacked: true,
            toolbar: {
                show: false
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                    position: 'bottom',
                    offsetX: -10,
                    offsetY: 0
                }
            }
        }],
        plotOptions: {
            bar: {
                horizontal: false
            }
        },
        series: [{
            name: 'نمره کسب شده',
            data: [
                {{ number_format($nomre['q'] ?? 0, 2) }},
                {{ number_format($nomre['d'] ?? 0, 2) }},
                {{ number_format($nomre['e'] ?? 0, 2) }},
                {{ number_format($nomre['pish'] ?? 0, 2) }},
                {{ number_format($nomre['talash'] ?? 0, 2) }}
            ]
        }],
        xaxis: {
            categories: ['سوال', 'گزارش', 'تکلیف', 'پیشرفت', 'تلاش']
        },
        yaxis: {
            labels: {
                offsetX: -20
            }
        },
        legend: {
            position: 'right',
            offsetY: 40
        },
        fill: {
            opacity: 1
        },
        colors: ['#1e6f9f']
    }

    var chart = new ApexCharts(document.querySelector("#chartColumnStacked"), sColStacked);
    chart.render();

    function toggleAccordion(header) {
        var body = header.nextElementSibling;
        var icon = header.querySelector('.accordion-icon');

        if (body.classList.contains('active')) {
            body.classList.remove('active');
            header.classList.remove('active');
            if (icon) icon.style.transform = 'rotate(0deg)';
        } else {
            document.querySelectorAll('.accordion-body').forEach(function(b) {
                b.classList.remove('active');
            });
            document.querySelectorAll('.accordion-header').forEach(function(h) {
                h.classList.remove('active');
            });
            document.querySelectorAll('.accordion-icon').forEach(function(i) {
                if (i) i.style.transform = 'rotate(0deg)';
            });
            body.classList.add('active');
            header.classList.add('active');
            if (icon) icon.style.transform = 'rotate(180deg)';
        }
    }

    // باز کردن اولین آیتم به صورت پیش‌فرض
    document.addEventListener('DOMContentLoaded', function() {
        var firstBody = document.querySelector('.accordion-body.active');
        if (firstBody) {
            firstBody.style.display = 'block';
            firstBody.style.maxHeight = firstBody.scrollHeight + 'px';
        }
    });
</script>
@endsection