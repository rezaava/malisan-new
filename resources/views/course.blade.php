@extends('layout.master')

@section('title')
ملیسان | مدیریت درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-course.css')}}">
@endsection

@section('mohtava')
<div class="course-detail-container">
    <div class="course-header">
        <h4 class="course-title-main">فرسایش بادی ۴۰۴۲</h4>
    </div>

    <div class="course-actions-bar">
        <a href="/dashboard/courses/setting?course_id=232" class="action-btn settings-btn">
            <i class="fas fa-cog"></i>
        </a>
        <a href="#" class="action-btn back-btn" onclick="history.back()">
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="course-chips">
        <a href="/dashboard/courses/students?course_id=232" class="chip-item">
            <i class="fas fa-user-graduate"></i>
            مشخصات دانشجویان (۱۷)
        </a>
        <a href="#" class="chip-item inactive">
            <i class="fas fa-ban"></i>
            اعتراضات (غیر فعال)
        </a>
        <a href="/dashboard/evaluation?course_id=232" class="chip-item">
            <i class="fas fa-chart-line"></i>
            پایش و ارزیابی
        </a>
        <a href="/dashboard/courses/grades/232" class="chip-item">
            <i class="fas fa-star"></i>
            نمرات دانشجویان
        </a>
        <a href="/dashboard/courses/bank?course_id=232" class="chip-item">
            <i class="fas fa-database"></i>
            بانک سوالات
        </a>
        <a href="/dashboard/azmon?id=232" class="chip-item">
            <i class="fas fa-pencil-alt"></i>
            تعریف آزمون
        </a>
        <a href="/dashboard/allprogress?course_id=232" class="chip-item">
            <i class="fas fa-eye"></i>
            پایش دانشجویان
        </a>
        <a href="/dashboard/survey?course_id=232" class="chip-item">
            <i class="fas fa-poll"></i>
            نظرسنجی
        </a>
        <a href="/dashboard/kholaseha?course_id=232" class="chip-item">
            <i class="fas fa-file-alt"></i>
            لیست گزارش دانشجویان
        </a>
        <a href="/dashboard/courses/232/nomreha" class="chip-item">
            <i class="fas fa-tasks"></i>
            فعالیت های دانشجویان
        </a>
    </div>

    <div class="sessions-section">
        <div class="sessions-sidebar">
            <div class="sessions-header">
                <h5>جلسه های ارائه شده</h5>
                <a href="/dashboard/courses/sessions/create?course_id=232" class="add-session-btn">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
            <div class="sessions-list">
                <a href="#" class="session-item active" data-session="1415" onclick="changeSession(this, '1415', 'https://malisan.ir/files/session/211_1716929537.pdf', 'روش های کنترل فرسایش بادی (احداث بادشکن 2)', 'جلسه 12')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">روش های کنترل فرسایش بادی (احداث بادشکن 2)</span>
                    <small class="session-number">(جلسه 12)</small>
                </a>
                <a href="#" class="session-item" data-session="1414" onclick="changeSession(this, '1414', 'https://malisan.ir/files/session/211_1716929180.pdf', 'روش های کنترل فرسایش بادی (احداث بادشکن 1)', 'جلسه 11')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">روش های کنترل فرسایش بادی (احداث بادشکن 1)</span>
                    <small class="session-number">(جلسه 11)</small>
                </a>
                <a href="#" class="session-item" data-session="1413" onclick="changeSession(this, '1413', 'https://malisan.ir/files/session/211_1716579368.pdf', 'روش های کنترل فرسایش بادی (اقدامات بیولوژیک)', 'جلسه 10')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">روش های کنترل فرسایش بادی (اقدامات بیولوژیک)</span>
                    <small class="session-number">(جلسه 10)</small>
                </a>
                <a href="#" class="session-item" data-session="1412" onclick="changeSession(this, '1412', 'https://malisan.ir/files/session/211_1715862977.pdf', 'روش های کنترل فرسایش بادی (مالچ های غیر نفتی)', 'جلسه 9')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">روش های کنترل فرسایش بادی (مالچ های غیر نفتی)</span>
                    <small class="session-number">(جلسه 9)</small>
                </a>
                <a href="#" class="session-item" data-session="1411" onclick="changeSession(this, '1411', 'https://malisan.ir/files/session/211_1715413699.pdf', 'روش های کنترل فرسایش بادی (مالچ های نفتی)', 'جلسه 8')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">روش های کنترل فرسایش بادی (مالچ های نفتی)</span>
                    <small class="session-number">(جلسه 8)</small>
                </a>
                <a href="#" class="session-item" data-session="1410" onclick="changeSession(this, '1410', 'https://malisan.ir/files/session/211_1714686917.pdf', 'روش های برآورد فرسایش بادی', 'جلسه 7')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">روش های برآورد فرسایش بادی</span>
                    <small class="session-number">(جلسه 7)</small>
                </a>
                <a href="#" class="session-item" data-session="1409" onclick="changeSession(this, '1409', 'https://malisan.ir/files/session/211_1714066421.pdf', 'روش ها و ابزارهای اندازه گیری فرسایش بادی', 'جلسه 6')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">روش ها و ابزارهای اندازه گیری فرسایش بادی</span>
                    <small class="session-number">(جلسه 6)</small>
                </a>
                <a href="#" class="session-item" data-session="1408" onclick="changeSession(this, '1408', 'https://malisan.ir/files/session/211_1713379283.pdf', 'تحلیل نیروهای وارد بر ذرات خاک و دینامیک حرکت ذرات خاک', 'جلسه 5')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">تحلیل نیروهای وارد بر ذرات خاک و دینامیک حرکت ذرات خاک</span>
                    <small class="session-number">(جلسه 5)</small>
                </a>
                <a href="#" class="session-item" data-session="1407" onclick="changeSession(this, '1407', 'https://malisan.ir/files/session/211_1709588588.pdf', 'دینامیک فرسایش بادی', 'جلسه 4')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">دینامیک فرسایش بادی</span>
                    <small class="session-number">(جلسه 4)</small>
                </a>
                <a href="#" class="session-item" data-session="1406" onclick="changeSession(this, '1406', 'https://malisan.ir/files/session/211_1709318787.pdf', 'عوامل موثر در فرسایش بادی', 'جلسه 3')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">عوامل موثر در فرسایش بادی</span>
                    <small class="session-number">(جلسه 3)</small>
                </a>
                <a href="#" class="session-item" data-session="1405" onclick="changeSession(this, '1405', 'https://malisan.ir/files/session/211_1708368767.pdf', 'آشنایی با اصطلاحات فرسایش بادی', 'جلسه 2')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">آشنایی با اصطلاحات فرسایش بادی</span>
                    <small class="session-number">(جلسه 2)</small>
                </a>
                <a href="#" class="session-item" data-session="1404" onclick="changeSession(this, '1404', 'https://malisan.ir/files/session/211_1707159176.pdf', 'سرفصل درس و معرفی ملیسان', 'جلسه 1')">
                    <span class="session-check"><i class="fas fa-check-circle"></i></span>
                    <span class="session-title">سرفصل درس و معرفی ملیسان</span>
                    <small class="session-number">(جلسه 1)</small>
                </a>
            </div>
        </div>

        <div class="session-content">
            <div class="session-content-header">
                <div class="session-title-display">
                    <h5 id="sessionTitleDisplay">جلسه 12 : روش های کنترل فرسایش بادی (احداث بادشکن 2)</h5>
                </div>
                <div class="session-action-buttons">
                    <a href="#" id="questionBtn" class="action-icon-btn" data-position="bottom" data-tooltip="طرح سوال">
                        <i class="fas fa-question-circle"></i>
                    </a>
                    <a href="#" id="homeworkTeacherBtn" class="action-icon-btn" data-position="bottom" data-tooltip="دادن تکلیف">
                        <i class="fas fa-file-alt"></i>
                    </a>
                    <a href="#" id="toggleActiveBtn" class="action-icon-btn" data-position="bottom" data-tooltip="غیرفعال کردن">
                        <i class="fas fa-check-circle"></i>
                    </a>
                    <a href="#" id="homeworkBtn" class="action-icon-btn" data-position="bottom" data-tooltip="تکلیف">
                        <i class="fas fa-tasks"></i>
                    </a>
                    <a href="#" id="editBtn" class="action-icon-btn" data-position="bottom" data-tooltip="ویرایش">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="#" id="profExBtn" class="action-icon-btn" data-position="bottom" data-tooltip="تکلیفها">
                        <i class="fas fa-list-ul"></i>
                    </a>
                </div>
            </div>

            <div class="session-pdf-container">
                <div class="pdf-toolbar">
                    <a href="#" id="pdfOpenBtn" class="pdf-open-btn" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                        باز کردن PDF در صفحه جدید
                    </a>
                </div>
                <div class="pdf-viewer">
                    <object id="pdfViewer" data="https://malisan.ir/files/session/211_1716929537.pdf" type="application/pdf" width="100%" height="550px">
                        <object width="100%" height="550" data="https://docs.google.com/gview?embedded=true&url=https://malisan.ir/files/session/211_1716929537.pdf"></object>
                    </object>
                </div>
            </div>

            <div class="session-description">
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <i class="fas fa-bell"></i>
                        طرح درس یا محتوای درس
                        <i class="fas fa-chevron-down expand-icon"></i>
                    </div>
                    <div class="collapsible-body" id="sessionDescription">
                        <p>این جلسه به معرفی برخی از اصطلاحاتی که در مطالعات فرسایش بادی کاربرد دارند اختصاص داده شده است...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentSessionId = '1415';
    let currentPdfUrl = 'https://malisan.ir/files/session/211_1716929537.pdf';
    let currentSessionTitle = 'روش های کنترل فرسایش بادی (احداث بادشکن 2)';
    let currentSessionNumber = 'جلسه 12';

    function changeSession(element, sessionId, pdfUrl, title, number) {
        document.querySelectorAll('.session-item').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');

        currentSessionId = sessionId;
        currentPdfUrl = pdfUrl;
        currentSessionTitle = title;
        currentSessionNumber = number;

        document.getElementById('sessionTitleDisplay').innerHTML = `<h5>${number} : ${title}</h5>`;

        const pdfViewer = document.getElementById('pdfViewer');
        pdfViewer.setAttribute('data', pdfUrl);
        pdfViewer.innerHTML = `<object width="100%" height="550" data="https://docs.google.com/gview?embedded=true&url=${pdfUrl}"></object>`;

        const pdfOpenBtn = document.getElementById('pdfOpenBtn');
        pdfOpenBtn.setAttribute('href', pdfUrl);

        const editBtn = document.getElementById('editBtn');
        editBtn.setAttribute('href', `/dashboard/courses/sessions/edit/${sessionId}`);

        const questionBtn = document.getElementById('questionBtn');
        questionBtn.setAttribute('href', `/dashboard/question/show?session_id=${sessionId}`);

        const homeworkTeacherBtn = document.getElementById('homeworkTeacherBtn');
        homeworkTeacherBtn.setAttribute('href', `/dashboard/exercise/show/${sessionId}`);

        const homeworkBtn = document.getElementById('homeworkBtn');
        homeworkBtn.setAttribute('href', `/dashboard/exercise/show/${sessionId}`);

        const toggleActiveBtn = document.getElementById('toggleActiveBtn');
        toggleActiveBtn.setAttribute('href', `/dashboard/courses/sessions/active/${sessionId}`);

        const profExBtn = document.getElementById('profExBtn');
        profExBtn.setAttribute('href', `/dashboard/courses/sessions/prof-ex/${sessionId}`);
    }

    document.querySelector('.collapsible-header').addEventListener('click', function() {
        const body = this.nextElementSibling;
        const icon = this.querySelector('.expand-icon');
        if (body.style.display === 'none' || body.style.display === '') {
            body.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        } else {
            body.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        }
    });
</script>
@endsection