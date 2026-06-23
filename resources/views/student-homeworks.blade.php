@extends('layout.master')

@section('title')
ملیسان | تکالیف دانشجو
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-homeworks.css')}}">
@endsection

@section('mohtava')
<div class="homeworks-container">
    <div class="homeworks-header">
        <div class="header-left">
            <a href="/student-activities" class="back-btn">
                <i class="fas fa-arrow-right"></i>
                بازگشت
            </a>
        </div>
        <div class="header-center">
            <h4 class="homeworks-title"><i class="fas fa-tasks"></i> تکالیف دانشجو</h4>
            <p class="homeworks-subtitle">لیست تمام تکالیف ارسال شده توسط دانشجو</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="homeworks-table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>عنوان تکلیف</th>
                    <th>تاریخ تحویل</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>تکلیف شماره ۱ - الگوریتم‌های مرتب‌سازی</td>
                    <td>۱۴۰۴/۰۳/۲۰</td>
                    <td><span class="status-badge accepted">تحویل شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showHomeworkDetail(1, 'تکلیف شماره ۱ - الگوریتم‌های مرتب‌سازی', '۱۴۰۴/۰۳/۲۰', 'تحویل شده', 'این تکلیف شامل پیاده‌سازی الگوریتم‌های Bubble Sort، Quick Sort و Merge Sort در زبان پایتون می‌باشد.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>تکلیف شماره ۲ - طراحی پایگاه داده</td>
                    <td>۱۴۰۴/۰۳/۲۵</td>
                    <td><span class="status-badge pending">در انتظار بررسی</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showHomeworkDetail(2, 'تکلیف شماره ۲ - طراحی پایگاه داده', '۱۴۰۴/۰۳/۲۵', 'در انتظار بررسی', 'طراحی پایگاه داده برای سیستم مدیریت کتابخانه شامل جداول کتاب، عضو، امانت و نویسنده با روابط بین آنها.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>تکلیف شماره ۳ - برنامه‌نویسی شیءگرا</td>
                    <td>۱۴۰۴/۰۳/۲۸</td>
                    <td><span class="status-badge accepted">تحویل شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showHomeworkDetail(3, 'تکلیف شماره ۳ - برنامه‌نویسی شیءگرا', '۱۴۰۴/۰۳/۲۸', 'تحویل شده', 'پیاده‌سازی مفاهیم کلاس، آبجکت، وراثت و چندریختی در زبان جاوا با استفاده از یک مثال عملی.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>تکلیف شماره ۴ - وب‌سایت شخصی</td>
                    <td>۱۴۰۴/۰۴/۰۲</td>
                    <td><span class="status-badge rejected">رد شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showHomeworkDetail(4, 'تکلیف شماره ۴ - وب‌سایت شخصی', '۱۴۰۴/۰۴/۰۲', 'رد شده', 'طراحی و پیاده‌سازی یک وب‌سایت شخصی با استفاده از HTML، CSS و جاوااسکریپت شامل صفحات درباره من، نمونه کارها و تماس.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>تکلیف شماره ۵ - تحلیل داده</td>
                    <td>۱۴۰۴/۰۴/۰۵</td>
                    <td><span class="status-badge pending">در انتظار بررسی</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showHomeworkDetail(5, 'تکلیف شماره ۵ - تحلیل داده', '۱۴۰۴/۰۴/۰۵', 'در انتظار بررسی', 'تحلیل داده‌های فروش یک فروشگاه با استفاده از کتابخانه Pandas در پایتون و رسم نمودارهای مختلف.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>تکلیف شماره ۶ - امنیت وب</td>
                    <td>۱۴۰۴/۰۴/۰۸</td>
                    <td><span class="status-badge accepted">تحویل شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showHomeworkDetail(6, 'تکلیف شماره ۶ - امنیت وب', '۱۴۰۴/۰۴/۰۸', 'تحویل شده', 'بررسی آسیب‌پذیری‌های رایج وب مانند SQL Injection، XSS و CSRF و روش‌های مقابله با آنها.')">
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
            <h4><i class="fas fa-tasks"></i> <span id="detailTitle">جزئیات تکلیف</span></h4>
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
    function showHomeworkDetail(id, title, date, status, description) {
        var modal = document.getElementById('detailModal');
        var titleEl = document.getElementById('detailTitle');
        var body = document.getElementById('detailBody');
        
        var statusClass = {
            'تحویل شده': 'accepted',
            'در انتظار بررسی': 'pending',
            'رد شده': 'rejected'
        };
        
        titleEl.textContent = 'جزئیات تکلیف';
        
        body.innerHTML = `
            <div class="detail-info">
                <div class="detail-item">
                    <span class="detail-label">عنوان تکلیف :</span>
                    <span class="detail-value homework-title">${title}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">تاریخ تحویل :</span>
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
                <a href="/homeworks/${id}" class="detail-btn primary">مشاهده کامل</a>
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