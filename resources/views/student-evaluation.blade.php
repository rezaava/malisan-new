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
        <h4 class="evaluation-title">نمره ارزشیابی مستمر از ۲۰ : <span class="score">۱۷.۶۶</span></h4>
        <p class="evaluation-subtitle">برای مشاهده جزئیات گزینه مورد نظر را انتخاب کنید</p>
    </div>

    <div class="evaluation-grid">
        <div class="evaluation-list">
            <div class="accordion-item">
                <div class="accordion-header active">
                    <div class="accordion-title">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>فعالیت کلاسی</span>
                    </div>
                    <div class="accordion-stats">
                        <span>سقف امتیاز: ۳۰</span>
                        <span class="score-badge">تاکنون: ۲۷.۰۴</span>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                </div>
                <div class="accordion-body" style="display: block;">
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>فعالیت</th>
                                <th>تعداد</th>
                                <th>امتیاز</th>
                                <th>سقف</th>
                                <th>نسبت به سقف</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>طرح سوال</td>
                                <td>۳۳</td>
                                <td class="score-value">۶.۶۰</td>
                                <td>۸</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 82.5%;"></div>
                                        <span class="progress-label">۸۲.۵٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>ارسال گزارش / تحقیق</td>
                                <td>۱۱</td>
                                <td class="score-value">۳.۴۴</td>
                                <td>۵</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 68.8%;"></div>
                                        <span class="progress-label">۶۸.۸٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>انجام داوری</td>
                                <td>۲۶۲</td>
                                <td class="score-value">۸.۰۰</td>
                                <td>۸</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 100%;"></div>
                                        <span class="progress-label">۱۰۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>شرکت در خودآزمایی</td>
                                <td>۶۰۵</td>
                                <td class="score-value">۹.۰۰</td>
                                <td>۹</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 100%;"></div>
                                        <span class="progress-label">۱۰۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="accordion-item">
                <div class="accordion-header">
                    <div class="accordion-title">
                        <i class="fas fa-graduation-cap"></i>
                        <span>پیشرفت درسی</span>
                    </div>
                    <div class="accordion-stats">
                        <span>سقف امتیاز: ۷۰</span>
                        <span class="score-badge">تاکنون: ۶۱.۲۶</span>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                </div>
                <div class="accordion-body">
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>فعالیت</th>
                                <th>وضعیت</th>
                                <th>امتیاز</th>
                                <th>سقف</th>
                                <th>نسبت به سقف</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>سوال</td>
                                <td>۲۰.۰۰</td>
                                <td class="score-value">۱۲.۰۰</td>
                                <td>۱۲</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 100%;"></div>
                                        <span class="progress-label">۱۰۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>گزارش</td>
                                <td>۲۰.۰۰</td>
                                <td class="score-value">۱۰.۰۰</td>
                                <td>۱۰</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 100%;"></div>
                                        <span class="progress-label">۱۰۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>خودآزمایی</td>
                                <td>۲۰.۰۰</td>
                                <td class="score-value">۲۰.۲۶</td>
                                <td>۲۴</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 84.4%;"></div>
                                        <span class="progress-label">۸۴.۴٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>سطح داوری</td>
                                <td>۱۰.۰۰</td>
                                <td class="score-value">۵.۰۰</td>
                                <td>۱۰</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 50%;"></div>
                                        <span class="progress-label">۵۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>کیفیت فعالیت‌ها</td>
                                <td>۱۶.۲۵</td>
                                <td class="score-value">۱۴.۰۰</td>
                                <td>۱۴</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 100%;"></div>
                                        <span class="progress-label">۱۰۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="accordion-item">
                <div class="accordion-header">
                    <div class="accordion-title">
                        <i class="fas fa-clipboard-check"></i>
                        <span>ارزشیابی مستمر</span>
                    </div>
                    <div class="accordion-stats">
                        <span>سقف امتیاز: ۲۰</span>
                        <span class="score-badge">تاکنون: ۱۷.۶۶</span>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                </div>
                <div class="accordion-body">
                    <div class="empty-state">
                        <i class="fas fa-info-circle"></i>
                        <p>اطلاعات ارزشیابی مستمر در این بخش نمایش داده می‌شود</p>
                    </div>
                </div>
            </div>
        </div>

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

@section('scripts')
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
            data: [0.00, 0.00, 0.00, 35.83, 21.88]
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
        }
    }

    var chart = new ApexCharts(document.querySelector("#chartColumnStacked"), sColStacked);
    chart.render();

    document.querySelectorAll('.accordion-header').forEach(function(header) {
        header.addEventListener('click', function() {
            var body = this.nextElementSibling;
            var icon = this.querySelector('.accordion-icon');
            var isActive = body.style.display === 'block';

            document.querySelectorAll('.accordion-body').forEach(function(b) {
                b.style.display = 'none';
            });
            document.querySelectorAll('.accordion-header').forEach(function(h) {
                h.classList.remove('active');
            });

            if (!isActive) {
                body.style.display = 'block';
                this.classList.add('active');
            } else {
                body.style.display = 'none';
                this.classList.remove('active');
            }
        });
    });
</script>
@endsection