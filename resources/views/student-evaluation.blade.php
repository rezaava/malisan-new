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
        <h4 class="evaluation-title">نمره ارزشیابی مستمر از ۲۰ : <span class="score">۳.۸۰</span></h4>
        <p class="evaluation-subtitle">برای مشاهده جزئیات گزینه مورد نظر را انتخاب کنید</p>
    </div>

    <div class="evaluation-grid">
        <div class="evaluation-list">
            <!-- فعالیت کلاسی -->
            <div class="accordion-item">
                <div class="accordion-header active" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>فعالیت کلاسی</span>
                    </div>
                    <div class="accordion-stats">
                        <span>سقف امتیاز: ۳۰</span>
                        <span class="score-badge">تاکنون: ۰.۰۰</span>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                </div>
                <div class="accordion-body active">
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
                                <td>۰</td>
                                <td class="score-value">۰.۰۰</td>
                                <td>۸</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 0%;"></div>
                                        <span class="progress-label">۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>ارسال گزارش / تحقیق</td>
                                <td>۰</td>
                                <td class="score-value">۰.۰۰</td>
                                <td>۵</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 0%;"></div>
                                        <span class="progress-label">۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>انجام داوری</td>
                                <td>۰</td>
                                <td class="score-value">۰.۰۰</td>
                                <td>۸</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 0%;"></div>
                                        <span class="progress-label">۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>شرکت در خودآزمایی</td>
                                <td>۰</td>
                                <td class="score-value">۰.۰۰</td>
                                <td>۹</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 0%;"></div>
                                        <span class="progress-label">۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- پیشرفت درسی -->
            <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <i class="fas fa-graduation-cap"></i>
                        <span>پیشرفت درسی</span>
                    </div>
                    <div class="accordion-stats">
                        <span>سقف امتیاز: ۷۰</span>
                        <span class="score-badge">تاکنون: ۱۹.۰۰</span>
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
                                <td>۰.۰۰</td>
                                <td class="score-value">۰.۰۰</td>
                                <td>۱۲</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 0%;"></div>
                                        <span class="progress-label">۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>گزارش</td>
                                <td>۰.۰۰</td>
                                <td class="score-value">۰.۰۰</td>
                                <td>۱۰</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 0%;"></div>
                                        <span class="progress-label">۰.۰٪</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>خودآزمایی</td>
                                <td>۰.۰۰</td>
                                <td class="score-value">۰.۰۰</td>
                                <td>۲۴</td>
                                <td>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-bar" style="width: 0%;"></div>
                                        <span class="progress-label">۰.۰٪</span>
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
                                <td>۱.۲۵</td>
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

            <!-- ارزشیابی مستمر -->
            <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <i class="fas fa-clipboard-check"></i>
                        <span>ارزشیابی مستمر</span>
                    </div>
                    <div class="accordion-stats">
                        <span>سقف امتیاز: ۲۰</span>
                        <span class="score-badge">تاکنون: ۳.۸۰</span>
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
            data: [0.00, 0.00, 0.00, 0.00, 0.00]
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

    function toggleAccordion(header) {
        var body = header.nextElementSibling;
        var icon = header.querySelector('.accordion-icon');

        if (body.classList.contains('active')) {
            body.classList.remove('active');
            header.classList.remove('active');
            icon.style.transform = 'rotate(0deg)';
        } else {
            document.querySelectorAll('.accordion-body').forEach(function(b) {
                b.classList.remove('active');
            });
            document.querySelectorAll('.accordion-header').forEach(function(h) {
                h.classList.remove('active');
            });
            document.querySelectorAll('.accordion-icon').forEach(function(i) {
                i.style.transform = 'rotate(0deg)';
            });
            body.classList.add('active');
            header.classList.add('active');
            icon.style.transform = 'rotate(180deg)';
        }
    }
</script>
@endsection