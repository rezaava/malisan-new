@extends('layout.master')

@section('title')
ملیسان | سوالات دانشجو
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-student-questions.css')}}">
@endsection

@section('mohtava')
<div class="questions-container">
    <div class="questions-header">
        <div class="header-left">
            <a href="/student-activities" class="back-btn">
                <i class="fas fa-arrow-right"></i>
                بازگشت
            </a>
        </div>
        <div class="header-center">
            <h4 class="questions-title"><i class="fas fa-question-circle"></i> سوالات دانشجو</h4>
            <p class="questions-subtitle">لیست تمام سوالات ثبت شده توسط دانشجو</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="questions-table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>سوال</th>
                    <th>تاریخ</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>تفاوت بین کلاس و آبجکت در جاوا چیست؟</td>
                    <td>۱۴۰۴/۰۳/۱۵</td>
                    <td><span class="status-badge answered">پاسخ داده شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showQuestionDetail(1, 'تفاوت بین کلاس و آبجکت در جاوا چیست؟', '۱۴۰۴/۰۳/۱۵', 'پاسخ داده شده', 'در جاوا، کلاس یک قالب یا الگو برای ایجاد آبجکت‌ها است. آبجکت نمونه‌ای از کلاس است که در حافظه ساخته می‌شود.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>چگونه می‌توان یک آرایه را در پایتون مرتب کرد؟</td>
                    <td>۱۴۰۴/۰۳/۱۴</td>
                    <td><span class="status-badge pending">در انتظار پاسخ</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showQuestionDetail(2, 'چگونه می‌توان یک آرایه را در پایتون مرتب کرد؟', '۱۴۰۴/۰۳/۱۴', 'در انتظار پاسخ', 'برای مرتب‌سازی آرایه در پایتون می‌توان از متد sort() یا تابع sorted() استفاده کرد.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>مفهوم وراثت در برنامه‌نویسی شیءگرا را توضیح دهید</td>
                    <td>۱۴۰۴/۰۳/۱۲</td>
                    <td><span class="status-badge answered">پاسخ داده شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showQuestionDetail(3, 'مفهوم وراثت در برنامه‌نویسی شیءگرا را توضیح دهید', '۱۴۰۴/۰۳/۱۲', 'پاسخ داده شده', 'وراثت یکی از مفاهیم اصلی برنامه‌نویسی شیءگرا است که به کلاس‌ها اجازه می‌دهد ویژگی‌ها و متدهای کلاس دیگر را به ارث ببرند.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>سیکل حیات یک کامپوننت در ری‌اکت را شرح دهید</td>
                    <td>۱۴۰۴/۰۳/۱۰</td>
                    <td><span class="status-badge rejected">رد شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showQuestionDetail(4, 'سیکل حیات یک کامپوننت در ری‌اکت را شرح دهید', '۱۴۰۴/۰۳/۱۰', 'رد شده', 'سیکل حیات کامپوننت‌های ری‌اکت شامل مراحل Mounting، Updating و Unmounting است.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>تفاوت بین SQL و NoSQL چیست؟</td>
                    <td>۱۴۰۴/۰۳/۰۸</td>
                    <td><span class="status-badge pending">در انتظار پاسخ</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showQuestionDetail(5, 'تفاوت بین SQL و NoSQL چیست؟', '۱۴۰۴/۰۳/۰۸', 'در انتظار پاسخ', 'SQL پایگاه‌های داده رابطه‌ای با ساختار جدولی هستند در حالی که NoSQL پایگاه‌های داده غیررابطه‌ای با ساختارهای انعطاف‌پذیر هستند.')">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>چگونه می‌توان یک API RESTful طراحی کرد؟</td>
                    <td>۱۴۰۴/۰۳/۰۵</td>
                    <td><span class="status-badge answered">پاسخ داده شده</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-btn" onclick="showQuestionDetail(6, 'چگونه می‌توان یک API RESTful طراحی کرد؟', '۱۴۰۴/۰۳/۰۵', 'پاسخ داده شده', 'برای طراحی API RESTful باید از متدهای HTTP مانند GET، POST، PUT و DELETE استفاده کرد و از ساختار URL مناسب پیروی نمود.')">
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
            <h4><i class="fas fa-question-circle"></i> <span id="detailTitle">جزئیات سوال</span></h4>
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
    function showQuestionDetail(id, title, date, status, answer) {
        var modal = document.getElementById('detailModal');
        var titleEl = document.getElementById('detailTitle');
        var body = document.getElementById('detailBody');

        var statusClass = {
            'پاسخ داده شده': 'answered',
            'در انتظار پاسخ': 'pending',
            'رد شده': 'rejected'
        };

        titleEl.textContent = 'جزئیات سوال';

        body.innerHTML = `
            <div class="detail-info">
                <div class="detail-item">
                    <span class="detail-label">سوال :</span>
                    <span class="detail-value question-text">${title}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">تاریخ ثبت :</span>
                    <span class="detail-value">${date}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">وضعیت :</span>
                    <span class="detail-value"><span class="status-badge ${statusClass[status] || 'pending'}">${status}</span></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">پاسخ :</span>
                    <span class="detail-value answer-text">${answer || 'هنوز پاسخی ثبت نشده است'}</span>
                </div>
            </div>
            <div class="detail-actions">
                <a href="/questions/${id}" class="detail-btn primary">مشاهده کامل</a>
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