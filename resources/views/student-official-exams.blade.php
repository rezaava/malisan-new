@extends('layout.master')

@section('title')
ملیسان | آزمون‌های رسمی دانشجو
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-official-exams.css')}}">
@endsection

@section('mohtava')
<div class="official-exams-container">
    <div class="official-exams-header">
        <div class="header-left">
            <a href="/student-activities" class="back-btn">
                <i class="fas fa-arrow-right"></i>
                بازگشت
            </a>
        </div>
        <div class="header-center">
            <h4 class="official-exams-title"><i class="fas fa-pencil-alt"></i> آزمون‌های رسمی دانشجو</h4>
            <p class="official-exams-subtitle">لیست تمام آزمون‌های رسمی برگزار شده</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="official-exams-table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>عنوان آزمون</th>
                    <th>تاریخ برگزاری</th>
                    <th>نمره</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>آزمون میان‌ترم - ریاضیات پایه</td>
                    <td>۱۴۰۴/۰۳/۲۰</td>
                    <td>۱۸.۵۰</td>
                    <td><span class="status-badge passed">پذیرفته شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showOfficialExamDetail(1, 'آزمون میان‌ترم - ریاضیات پایه', '۱۴۰۴/۰۳/۲۰', '۱۸.۵۰', 'پذیرفته شده', 'آزمون میان‌ترم ریاضیات پایه شامل مباحث اعداد مختلط، ماتریس‌ها و دستگاه‌های معادلات خطی با ۲۵ سوال تستی.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>آزمون پایان‌ترم - فیزیک عمومی</td>
                    <td>۱۴۰۴/۰۳/۲۸</td>
                    <td>۱۶.۰۰</td>
                    <td><span class="status-badge passed">پذیرفته شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showOfficialExamDetail(2, 'آزمون پایان‌ترم - فیزیک عمومی', '۱۴۰۴/۰۳/۲۸', '۱۶.۰۰', 'پذیرفته شده', 'آزمون پایان‌ترم فیزیک عمومی شامل مباحث حرکت شناسی، دینامیک، کار و انرژی با ۲۰ سوال تستی و ۱۰ سوال تشریحی.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>آزمون میان‌ترم - برنامه‌نویسی</td>
                    <td>۱۴۰۴/۰۴/۰۲</td>
                    <td>۱۹.۰۰</td>
                    <td><span class="status-badge passed">پذیرفته شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showOfficialExamDetail(3, 'آزمون میان‌ترم - برنامه‌نویسی', '۱۴۰۴/۰۴/۰۲', '۱۹.۰۰', 'پذیرفته شده', 'آزمون میان‌ترم برنامه‌نویسی شامل مبانی پایتون، ساختارهای داده و توابع با ۳۰ سوال عملی.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>آزمون پایان‌ترم - پایگاه داده</td>
                    <td>۱۴۰۴/۰۴/۱۰</td>
                    <td>۱۲.۰۰</td>
                    <td><span class="status-badge failed">رد شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showOfficialExamDetail(4, 'آزمون پایان‌ترم - پایگاه داده', '۱۴۰۴/۰۴/۱۰', '۱۲.۰۰', 'رد شده', 'آزمون پایان‌ترم پایگاه داده شامل SQL پیشرفته، نرمال‌سازی و طراحی پایگاه داده با ۲۵ سوال.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>آزمون میان‌ترم - هوش مصنوعی</td>
                    <td>۱۴۰۴/۰۴/۱۵</td>
                    <td>۱۷.۵۰</td>
                    <td><span class="status-badge passed">پذیرفته شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showOfficialExamDetail(5, 'آزمون میان‌ترم - هوش مصنوعی', '۱۴۰۴/۰۴/۱۵', '۱۷.۵۰', 'پذیرفته شده', 'آزمون میان‌ترم هوش مصنوعی شامل مباحث جستجو، الگوریتم‌های بهینه‌سازی و شبکه‌های عصبی با ۲۰ سوال.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>آزمون پایان‌ترم - زبان تخصصی</td>
                    <td>۱۴۰۴/۰۴/۲۰</td>
                    <td>۱۴.۰۰</td>
                    <td><span class="status-badge passed">پذیرفته شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showOfficialExamDetail(6, 'آزمون پایان‌ترم - زبان تخصصی', '۱۴۰۴/۰۴/۲۰', '۱۴.۰۰', 'پذیرفته شده', 'آزمون پایان‌ترم زبان تخصصی شامل گرامر پیشرفته، درک مطلب و واژگان تخصصی با ۴۰ سوال.')">
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
            <h4><i class="fas fa-pencil-alt"></i> <span id="detailTitle">جزئیات آزمون رسمی</span></h4>
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
    function showOfficialExamDetail(id, title, date, score, status, description) {
        var modal = document.getElementById('detailModal');
        var titleEl = document.getElementById('detailTitle');
        var body = document.getElementById('detailBody');
        
        var statusClass = {
            'پذیرفته شده': 'passed',
            'رد شده': 'failed'
        };
        
        titleEl.textContent = 'جزئیات آزمون رسمی';
        
        body.innerHTML = `
            <div class="detail-info">
                <div class="detail-item">
                    <span class="detail-label">عنوان آزمون :</span>
                    <span class="detail-value exam-title">${title}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">تاریخ برگزاری :</span>
                    <span class="detail-value">${date}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">نمره :</span>
                    <span class="detail-value score-value">${score}</span>
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
                <a href="/official-exams/${id}" class="detail-btn primary">مشاهده کامل</a>
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