@extends('layout.master')

@section('title')
ملیسان | خودآزمایی‌های دانشجو
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-self-tests.css')}}">
@endsection

@section('mohtava')
<div class="self-tests-container">
    <div class="self-tests-header">
        <div class="header-left">
            <a href="/student-activities" class="back-btn">
                <i class="fas fa-arrow-right"></i>
                بازگشت
            </a>
        </div>
        <div class="header-center">
            <h4 class="self-tests-title"><i class="fas fa-brain"></i> خودآزمایی‌های دانشجو</h4>
            <p class="self-tests-subtitle">لیست تمام خودآزمایی‌های انجام شده توسط دانشجو</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="self-tests-table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>عنوان خودآزمایی</th>
                    <th>تاریخ</th>
                    <th>نمره</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>خودآزمایی ریاضیات پایه - فصل ۱</td>
                    <td>۱۴۰۴/۰۳/۱۵</td>
                    <td>۱۸.۵۰</td>
                    <td><span class="status-badge passed">قبول</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showSelfTestDetail(1, 'خودآزمایی ریاضیات پایه - فصل ۱', '۱۴۰۴/۰۳/۱۵', '۱۸.۵۰', 'قبول', 'این خودآزمایی شامل ۲۰ سوال از مبحث اعداد مختلط، ماتریس و دترمینان بود.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>خودآزمایی فیزیک عمومی - فصل ۲</td>
                    <td>۱۴۰۴/۰۳/۱۴</td>
                    <td>۱۶.۰۰</td>
                    <td><span class="status-badge passed">قبول</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showSelfTestDetail(2, 'خودآزمایی فیزیک عمومی - فصل ۲', '۱۴۰۴/۰۳/۱۴', '۱۶.۰۰', 'قبول', 'خودآزمایی از مباحث حرکت شناسی، دینامیک و قوانین نیوتن شامل ۱۵ سوال تستی و تشریحی.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>خودآزمایی برنامه‌نویسی - مقدماتی</td>
                    <td>۱۴۰۴/۰۳/۱۲</td>
                    <td>۱۹.۰۰</td>
                    <td><span class="status-badge passed">قبول</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showSelfTestDetail(3, 'خودآزمایی برنامه‌نویسی - مقدماتی', '۱۴۰۴/۰۳/۱۲', '۱۹.۰۰', 'قبول', 'آزمون شامل مبانی پایتون، حلقه‌ها، شرط‌ها و توابع با ۲۵ سوال عملی و تئوری.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>خودآزمایی پایگاه داده - فصل ۳</td>
                    <td>۱۴۰۴/۰۳/۱۰</td>
                    <td>۱۲.۰۰</td>
                    <td><span class="status-badge failed">رد</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showSelfTestDetail(4, 'خودآزمایی پایگاه داده - فصل ۳', '۱۴۰۴/۰۳/۱۰', '۱۲.۰۰', 'رد', 'آزمون از مباحث SQL پیشرفته، JOIN ها و زیرسوالات با ۲۰ سوال تستی.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>خودآزمایی هوش مصنوعی - فصل ۱</td>
                    <td>۱۴۰۴/۰۳/۰۸</td>
                    <td>۱۷.۵۰</td>
                    <td><span class="status-badge passed">قبول</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showSelfTestDetail(5, 'خودآزمایی هوش مصنوعی - فصل ۱', '۱۴۰۴/۰۳/۰۸', '۱۷.۵۰', 'قبول', 'آزمون شامل مبانی هوش مصنوعی، جستجو و الگوریتم‌های بهینه‌سازی با ۱۸ سوال.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>خودآزمایی زبان انگلیسی - تخصصی</td>
                    <td>۱۴۰۴/۰۳/۰۵</td>
                    <td>۱۴.۰۰</td>
                    <td><span class="status-badge passed">قبول</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showSelfTestDetail(6, 'خودآزمایی زبان انگلیسی - تخصصی', '۱۴۰۴/۰۳/۰۵', '۱۴.۰۰', 'قبول', 'آزمون شامل گرامر پیشرفته، درک مطلب و واژگان تخصصی با ۳۰ سوال.')">
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
            <h4><i class="fas fa-brain"></i> <span id="detailTitle">جزئیات خودآزمایی</span></h4>
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
    function showSelfTestDetail(id, title, date, score, status, description) {
        var modal = document.getElementById('detailModal');
        var titleEl = document.getElementById('detailTitle');
        var body = document.getElementById('detailBody');
        
        var statusClass = {
            'قبول': 'passed',
            'رد': 'failed'
        };
        
        titleEl.textContent = 'جزئیات خودآزمایی';
        
        body.innerHTML = `
            <div class="detail-info">
                <div class="detail-item">
                    <span class="detail-label">عنوان خودآزمایی :</span>
                    <span class="detail-value test-title">${title}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">تاریخ انجام :</span>
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
                <a href="/self-tests/${id}" class="detail-btn primary">مشاهده کامل</a>
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