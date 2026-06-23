@extends('layout.master')

@section('title')
ملیسان | گزارشات دانشجو
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-reports.css')}}">
@endsection

@section('mohtava')
<div class="reports-container">
    <div class="reports-header">
        <div class="header-left">
            <a href="/student-activities" class="back-btn">
                <i class="fas fa-arrow-right"></i>
                بازگشت
            </a>
        </div>
        <div class="header-center">
            <h4 class="reports-title"><i class="fas fa-file-alt"></i> گزارشات دانشجو</h4>
            <p class="reports-subtitle">لیست تمام گزارشات ارسال شده توسط دانشجو</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="reports-table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>عنوان گزارش</th>
                    <th>تاریخ</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>گزارش پروژه برنامه‌نویسی - سیستم مدیریت کتابخانه</td>
                    <td>۱۴۰۴/۰۳/۱۵</td>
                    <td><span class="status-badge approved">تایید شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showReportDetail(1, 'گزارش پروژه برنامه‌نویسی - سیستم مدیریت کتابخانه', '۱۴۰۴/۰۳/۱۵', 'تایید شده', 'این گزارش مربوط به پروژه سیستم مدیریت کتابخانه است که با استفاده از زبان جاوا و پایگاه داده MySQL پیاده‌سازی شده است.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>گزارش آزمایشگاه - اندازه‌گیری سرعت نور</td>
                    <td>۱۴۰۴/۰۳/۱۴</td>
                    <td><span class="status-badge pending">در انتظار بررسی</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showReportDetail(2, 'گزارش آزمایشگاه - اندازه‌گیری سرعت نور', '۱۴۰۴/۰۳/۱۴', 'در انتظار بررسی', 'در این گزارش روش‌های مختلف اندازه‌گیری سرعت نور شامل روش فیزو و روش مایکلسون مورد بررسی قرار گرفته است.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>گزارش تحلیل الگوریتم‌های مرتب‌سازی</td>
                    <td>۱۴۰۴/۰۳/۱۲</td>
                    <td><span class="status-badge approved">تایید شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showReportDetail(3, 'گزارش تحلیل الگوریتم‌های مرتب‌سازی', '۱۴۰۴/۰۳/۱۲', 'تایید شده', 'این گزارش به تحلیل و مقایسه الگوریتم‌های مرتب‌سازی مختلف از نظر پیچیدگی زمانی و فضایی پرداخته است.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>گزارش کارآموزی - شرکت نرم‌افزاری</td>
                    <td>۱۴۰۴/۰۳/۱۰</td>
                    <td><span class="status-badge rejected">رد شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showReportDetail(4, 'گزارش کارآموزی - شرکت نرم‌افزاری', '۱۴۰۴/۰۳/۱۰', 'رد شده', 'گزارش دوره کارآموزی در شرکت نرم‌افزاری که شامل تجربیات و پروژه‌های انجام شده در این دوره می‌باشد.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>گزارش پایان ترم - طراحی پایگاه داده</td>
                    <td>۱۴۰۴/۰۳/۰۸</td>
                    <td><span class="status-badge pending">در انتظار بررسی</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showReportDetail(5, 'گزارش پایان ترم - طراحی پایگاه داده', '۱۴۰۴/۰۳/۰۸', 'در انتظار بررسی', 'گزارش نهایی درس طراحی پایگاه داده شامل مدل‌سازی، نرمال‌سازی و پیاده‌سازی یک سیستم مدیریت فروشگاهی است.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>گزارش تحقیقاتی - یادگیری ماشین</td>
                    <td>۱۴۰۴/۰۳/۰۵</td>
                    <td><span class="status-badge approved">تایید شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showReportDetail(6, 'گزارش تحقیقاتی - یادگیری ماشین', '۱۴۰۴/۰۳/۰۵', 'تایید شده', 'این گزارش به بررسی الگوریتم‌های یادگیری ماشین شامل رگرسیون، درخت تصمیم و شبکه‌های عصبی پرداخته است.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail -->
<div class="detail-overlay" id="detailModal">
    <div class="detail-content">
        <div class="detail-header">
            <h4><i class="fas fa-file-alt"></i> <span id="detailTitle">جزئیات گزارش</span></h4>
            <button class="detail-close" onclick="closeDetail()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="detail-body" id="detailBody">
            <!-- اطلاعات با جاوااسکریپت پر میشه -->
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function showReportDetail(id, title, date, status, description) {
        var modal = document.getElementById('detailModal');
        var titleEl = document.getElementById('detailTitle');
        var body = document.getElementById('detailBody');

        var statusClass = {
            'تایید شده': 'approved',
            'در انتظار بررسی': 'pending',
            'رد شده': 'rejected'
        };

        titleEl.textContent = 'جزئیات گزارش';

        body.innerHTML = `
            <div class="detail-info">
                <div class="detail-item">
                    <span class="detail-label">عنوان گزارش :</span>
                    <span class="detail-value report-title">${title}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">تاریخ ارسال :</span>
                    <span class="detail-value">${date}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">وضعیت :</span>
                    <span class="detail-value"><span class="status-badge ${statusClass[status] || 'pending'}">${status}</span></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">توضیحات :</span>
                    <span class="detail-value description-text">${description}</span>
                </div>
            </div>
            <div class="detail-actions">
                <a href="/reports/${id}" class="detail-btn primary">مشاهده کامل</a>
                <button class="detail-btn secondary" onclick="closeDetail()">بستن</button>
            </div>
        `;

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDetail() {
        var modal = document.getElementById('detailModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetail();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetail();
        }
    });
</script>
@endsection