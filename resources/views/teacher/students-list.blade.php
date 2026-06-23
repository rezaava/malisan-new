@extends('layout.master')

@section('title')
ملیسان | دانشجویان درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-students-list.css')}}">
<style>
    /* استایل‌های اضافی برای مودال و دکمه‌ها */
    .badge-btn-wrap {
        position: relative;
        display: inline-block;
    }

    .badge-btn-wrap .badge-tooltip {
        visibility: hidden;
        opacity: 0;
        background: #1a2332;
        color: #fff;
        font-size: 11px;
        white-space: nowrap;
        padding: 4px 10px;
        border-radius: 6px;
        position: absolute;
        bottom: calc(100% + 6px);
        left: 50%;
        transform: translateX(-50%);
        transition: all 0.2s;
        pointer-events: none;
        z-index: 10;
    }

    .badge-btn-wrap:hover .badge-tooltip {
        visibility: visible;
        opacity: 1;
    }

    .badge-count {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #e53935;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        line-height: 1;
    }

    .action-btn {
        position: relative;
    }

    /* مودال */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .modal-overlay.open {
        display: flex;
    }

    .modal-container {
        background: #fff;
        border-radius: 16px;
        width: 580px;
        max-width: 95vw;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        animation: modalSlideIn 0.3s ease;
        overflow: hidden;
        direction: rtl;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-30px) scale(0.95);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 16px 24px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .modal-header.purple {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .modal-header.teal {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .modal-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .modal-close {
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
        padding: 4px;
        font-size: 20px;
        border-radius: 50%;
        transition: background 0.2s;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 14px;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 16px;
    }

    .student-info.purple {
        background: #f3f0ff;
    }

    .student-info.teal {
        background: #e8fdf9;
    }

    .student-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 20px;
    }

    .student-avatar.purple {
        background: #9fa8da;
    }

    .student-avatar.teal {
        background: #80cbc4;
    }

    .student-meta strong {
        display: block;
        font-size: 16px;
        font-weight: 700;
        color: #1a2332;
    }

    .student-meta span {
        font-size: 13px;
        color: #6b7a8f;
    }

    .notice-box {
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 13px;
        line-height: 1.8;
        margin-bottom: 16px;
        color: #333;
    }

    .notice-box.purple {
        background: #faf8ff;
        border-right: 3px solid #9fa8da;
    }

    .notice-box.teal {
        background: #f4fdfb;
        border-right: 3px solid #80cbc4;
    }

    .notice-box .notice-label {
        font-weight: 800;
        color: #d32f2f;
    }

    .notice-box .example {
        color: #6b7a8f;
        font-size: 12px;
        display: block;
        margin-top: 4px;
    }

    .add-row {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .add-row input[type="text"] {
        flex: 1;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        border: 2px solid #e8edf3;
        outline: none;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        direction: rtl;
    }

    .add-row input[type="text"]:focus {
        border-color: #1e6f9f;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.08);
    }

    .add-row input[type="text"].purple:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
    }

    .add-row input[type="text"].teal:focus {
        border-color: #11998e;
        box-shadow: 0 0 0 4px rgba(17, 153, 142, 0.15);
    }

    .add-btn {
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .add-btn.purple {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .add-btn.teal {
        background: linear-gradient(135deg, #11998e, #38efd7);
    }

    .add-btn i {
        font-size: 16px;
    }

    .history-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 10px;
        border-bottom: 2px solid #f0f4f9;
        padding-bottom: 8px;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .history-table thead tr {
        background: #f8fafc;
    }

    .history-table.purple thead tr {
        background: #f3f0ff;
    }

    .history-table.teal thead tr {
        background: #e8fdf9;
    }

    .history-table th,
    .history-table td {
        padding: 8px 12px;
        text-align: right;
        border-bottom: 1px solid #f0f4f9;
        color: #333;
    }

    .history-table th {
        font-weight: 700;
        font-size: 12px;
        color: #1a2332;
    }

    .history-table td.col-main {
        width: 65%;
        word-break: break-word;
        white-space: normal;
    }

    .history-table tbody tr:hover {
        background: #f8fafc;
    }

    .history-table .empty-row td {
        text-align: center;
        color: #aaa;
        padding: 20px;
    }

    .flash-message {
        display: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 14px;
    }

    .flash-message.success {
        background: #e8f5e9;
        color: #2e7d32;
        display: block;
    }

    .flash-message.error {
        background: #ffebee;
        color: #c62828;
        display: block;
    }
</style>
@endsection

@section('mohtava')
<div class="students-container">
    <div class="students-header">
        <h4 class="students-title">دانشجویان درس : <span>{{ $course->name ?? 'عنوان درس' }}</span></h4>
        <a href="/export/students?course_id={{ $course->id ?? 0 }}" class="export-btn">
            <i class="fas fa-file-excel"></i>
            خروجی فایل اکسل
        </a>
    </div>

    <div class="table-wrapper">
        <table class="students-table" id="studentsTable">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام کاربر</th>
                    @if(isset($setting) && $setting->amali_nomre > 0)
                        <th>نمره عملی</th>
                    @endif
                    <th>مشخصات</th>
                    <th>پیشرفت درسی</th>
                    <th>خودآزمایی</th>
                    <th>صفات</th>
                    <th>رویداد</th>
                    <th>اخراج</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users ?? [] as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name ?? '' }} {{ $user->family ?? '' }}</td>
                        @if(isset($setting) && $setting->amali_nomre > 0)
                            <td>{{ $user->nomre ?? 0 }}</td>
                        @endif
                        <td>
                            <a href="/dashboard/user/{{ $user->id }}" class="action-btn profile-btn" title="مشخصات">
                                <i class="fas fa-user-circle"></i>
                            </a>
                        </td>
                        <td>
                            <a href="/dashboard/progress?course_id={{ $course->id ?? 0 }}&user={{ $user->id }}" class="action-btn progress-btn" title="پیشرفت درسی">
                                <i class="fas fa-chart-line"></i>
                            </a>
                        </td>
                        <td>
                            <a href="/dashboard/quiz/list?course_id={{ $course->id ?? 0 }}&user={{ $user->id }}" class="action-btn self-test-btn" title="خودآزمایی">
                                <i class="fas fa-brain"></i>
                            </a>
                        </td>
                        <td>
                            <div class="badge-btn-wrap">
                                <button type="button" class="action-btn attributes-btn"
                                        onclick="openAdjModal({{ $user->id }}, '{{ addslashes($user->name . ' ' . $user->family) }}', {{ $user->adjectives_count ?? 0 }})"
                                        title="صفات">
                                    <i class="fas fa-list-ul"></i>
                                    <span class="badge-count">{{ $user->adjectives_count ?? 0 }}</span>
                                </button>
                                <span class="badge-tooltip">ثبت صفت جدید</span>
                            </div>
                        </td>
                        <td>
                            <div class="badge-btn-wrap">
                                <button type="button" class="action-btn events-btn"
                                        onclick="openEvtModal({{ $user->id }}, '{{ addslashes($user->name . ' ' . $user->family) }}', {{ $user->events_count ?? 0 }})"
                                        title="رویداد">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="badge-count">{{ $user->events_count ?? 0 }}</span>
                                </button>
                                <span class="badge-tooltip">ثبت رویداد جدید</span>
                            </div>
                        </td>
                        <td>
                            <a href="/dashboard/courses/destroy-user?u={{ $user->id }}&c={{ $course->id ?? 0 }}" 
                               class="action-btn remove-btn" title="اخراج"
                               onclick="return confirm('آیا از اخراج این دانشجو اطمینان دارید؟')">
                                <i class="fas fa-user-minus"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="20" style="text-align:center;padding:40px;color:#6b7a8f;">
                            <i class="fas fa-users" style="font-size:32px;display:block;margin-bottom:12px;color:#d0d7e2;"></i>
                            هیچ دانشجویی در این درس ثبت‌نام نکرده است
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================
     مودال صفات (Adjectives)
     ============================================ -->
<div class="modal-overlay" id="adjModalOverlay">
    <div class="modal-container">
        <div class="modal-header purple">
            <h4>مدیریت صفات دانشجو</h4>
            <button class="modal-close" onclick="closeAdjModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">

            <div class="student-info purple">
                <div class="student-avatar purple">
                    <i class="fas fa-user"></i>
                </div>
                <div class="student-meta">
                    <strong id="adjStudentName">—</strong>
                    <span id="adjStudentCount">تعداد صفات: ۰</span>
                </div>
            </div>

            <div class="notice-box purple">
                <span class="notice-label">توجه: </span>
                صفاتی را بیان کنید که دانشجو از آنها برخوردار است.
                <span class="example">مثال: خلاق، خجالتی، جسور، محافظه‌کار، مغرور، باادب، خوش‌برخورد، منظم، پرتلاش، منفعل، مشارکت‌جو، رقابتی، متواضع، خودنما، مسئولیت‌پذیر، سهل‌انگار، نقاد، زودباور، مستقل، وابسته، منعطف، سختگیر، صبور، بی‌حوصله، خوش‌بین، بدبین، متهور، محتاط، صریح‌اللهجه</span>
            </div>

            <div class="flash-message" id="adjFlash"></div>

            <div class="add-row">
                <input type="text" id="adjInput" class="purple" placeholder="صفت جدید را وارد کنید..." />
                <button class="add-btn purple" onclick="submitAdjective()">
                    <i class="fas fa-plus"></i>
                    افزودن
                </button>
            </div>

            <div class="history-title">تاریخچه صفات ثبت‌شده</div>
            <table class="history-table purple">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="col-main">صفت</th>
                        <th>تاریخ</th>
                    </tr>
                </thead>
                <tbody id="adjHistoryBody">
                    <tr class="empty-row"><td colspan="3">در حال بارگذاری...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================
     مودال رویدادها (Events)
     ============================================ -->
<div class="modal-overlay" id="evtModalOverlay">
    <div class="modal-container">
        <div class="modal-header teal">
            <h4>مدیریت رویدادهای دانشجو</h4>
            <button class="modal-close" onclick="closeEvtModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">

            <div class="student-info teal">
                <div class="student-avatar teal">
                    <i class="fas fa-user"></i>
                </div>
                <div class="student-meta">
                    <strong id="evtStudentName">—</strong>
                    <span id="evtStudentCount">تعداد رویدادها: ۰</span>
                </div>
            </div>

            <div class="notice-box teal">
                <span class="notice-label">توجه: </span>
                منظور از «رویداد» در اینجا این است که با یک جمله، یک ویژگی رفتاری مشخص از دانشجو توصیف شود.
                <span class="example">مثال: دانشجو در گروه درسی نه تنها مشارکت دارد، بلکه دیگران را نیز به همکاری تشویق می‌کند. | دانشجو در پاسخگویی به پرسش‌ها در کلاس مشارکتی فعال دارد.</span>
            </div>

            <div class="flash-message" id="evtFlash"></div>

            <div class="add-row">
                <input type="text" id="evtInput" class="teal" placeholder="رویداد جدید را وارد کنید..." />
                <button class="add-btn teal" onclick="submitEvent()">
                    <i class="fas fa-plus"></i>
                    افزودن
                </button>
            </div>

            <div class="history-title">تاریخچه رویدادهای ثبت‌شده</div>
            <table class="history-table teal">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="col-main">رویداد</th>
                        <th>تاریخ</th>
                    </tr>
                </thead>
                <tbody id="evtHistoryBody">
                    <tr class="empty-row"><td colspan="3">در حال بارگذاری...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // ============================================
    // متغیرهای عمومی
    // ============================================
    let _adjStudentId = null;
    let _evtStudentId = null;

    // ============================================
    // مودال صفات (Adjectives)
    // ============================================
    function openAdjModal(studentId, studentName, count) {
        _adjStudentId = studentId;
        document.getElementById('adjStudentName').textContent = studentName;
        document.getElementById('adjStudentCount').textContent = 'تعداد صفات: ' + count;
        document.getElementById('adjInput').value = '';
        hideFlash('adjFlash');
        document.getElementById('adjModalOverlay').classList.add('open');
        loadAdjectives(studentId);
    }

    function closeAdjModal() {
        document.getElementById('adjModalOverlay').classList.remove('open');
        _adjStudentId = null;
    }

    document.getElementById('adjModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeAdjModal();
    });

    function loadAdjectives(studentId) {
        const tbody = document.getElementById('adjHistoryBody');
        tbody.innerHTML = '<tr class="empty-row"><td colspan="3">در حال بارگذاری...</td></tr>';
        
        fetch('/dashboard/adjectives/' + studentId)
            .then(r => r.json())
            .then(data => {
                if (!data || !data.length) {
                    tbody.innerHTML = '<tr class="empty-row"><td colspan="3">هیچ صفتی ثبت نشده است</td></tr>';
                    return;
                }
                tbody.innerHTML = data.map((item, i) => `
                    <tr>
                        <td>${i + 1}</td>
                        <td class="col-main">${item.adjective}</td>
                        <td>${item.created_at}</td>
                    </tr>
                `).join('');
            })
            .catch(() => {
                tbody.innerHTML = '<tr class="empty-row"><td colspan="3">خطا در بارگذاری</td></tr>';
            });
    }

    function submitAdjective() {
        const val = document.getElementById('adjInput').value.trim();
        if (!val) {
            showFlash('adjFlash', 'لطفاً یک صفت وارد کنید', 'error');
            return;
        }
        
        fetch('/dashboard/adjectives', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                student_id: _adjStudentId,
                adjective: val
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showFlash('adjFlash', 'صفت با موفقیت ثبت شد', 'success');
                document.getElementById('adjInput').value = '';
                loadAdjectives(_adjStudentId);
                document.getElementById('adjStudentCount').textContent = 'تعداد صفات: ' + data.new_count;
                updateAdjBadge(_adjStudentId, data.new_count);
            } else {
                showFlash('adjFlash', data.message || 'خطایی رخ داد', 'error');
            }
        })
        .catch(() => {
            showFlash('adjFlash', 'خطا در ارتباط با سرور', 'error');
        });
    }

    document.getElementById('adjInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') submitAdjective();
    });

    function updateAdjBadge(studentId, count) {
        const buttons = document.querySelectorAll('.attributes-btn');
        buttons.forEach(btn => {
            const onclick = btn.getAttribute('onclick') || '';
            if (onclick.includes('(' + studentId + ',')) {
                const badge = btn.querySelector('.badge-count');
                if (badge) badge.textContent = count;
                btn.setAttribute('onclick', onclick.replace(/,\s*\d+\s*\)/, ', ' + count + ')'));
            }
        });
    }

    // ============================================
    // مودال رویدادها (Events)
    // ============================================
    function openEvtModal(studentId, studentName, count) {
        _evtStudentId = studentId;
        document.getElementById('evtStudentName').textContent = studentName;
        document.getElementById('evtStudentCount').textContent = 'تعداد رویدادها: ' + count;
        document.getElementById('evtInput').value = '';
        hideFlash('evtFlash');
        document.getElementById('evtModalOverlay').classList.add('open');
        loadEvents(studentId);
    }

    function closeEvtModal() {
        document.getElementById('evtModalOverlay').classList.remove('open');
        _evtStudentId = null;
    }

    document.getElementById('evtModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeEvtModal();
    });

    function loadEvents(studentId) {
        const tbody = document.getElementById('evtHistoryBody');
        tbody.innerHTML = '<tr class="empty-row"><td colspan="3">در حال بارگذاری...</td></tr>';
        
        fetch('/dashboard/events/' + studentId)
            .then(r => r.json())
            .then(data => {
                if (!data || !data.length) {
                    tbody.innerHTML = '<tr class="empty-row"><td colspan="3">هیچ رویدادی ثبت نشده است</td></tr>';
                    return;
                }
                tbody.innerHTML = data.map((item, i) => `
                    <tr>
                        <td>${i + 1}</td>
                        <td class="col-main">${item.event}</td>
                        <td>${item.created_at}</td>
                    </tr>
                `).join('');
            })
            .catch(() => {
                tbody.innerHTML = '<tr class="empty-row"><td colspan="3">خطا در بارگذاری</td></tr>';
            });
    }

    function submitEvent() {
        const val = document.getElementById('evtInput').value.trim();
        if (!val) {
            showFlash('evtFlash', 'لطفاً یک رویداد وارد کنید', 'error');
            return;
        }
        
        fetch('/dashboard/events', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                student_id: _evtStudentId,
                event: val
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showFlash('evtFlash', 'رویداد با موفقیت ثبت شد', 'success');
                document.getElementById('evtInput').value = '';
                loadEvents(_evtStudentId);
                document.getElementById('evtStudentCount').textContent = 'تعداد رویدادها: ' + data.new_count;
                updateEvtBadge(_evtStudentId, data.new_count);
            } else {
                showFlash('evtFlash', data.message || 'خطایی رخ داد', 'error');
            }
        })
        .catch(() => {
            showFlash('evtFlash', 'خطا در ارتباط با سرور', 'error');
        });
    }

    document.getElementById('evtInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') submitEvent();
    });

    function updateEvtBadge(studentId, count) {
        const buttons = document.querySelectorAll('.events-btn');
        buttons.forEach(btn => {
            const onclick = btn.getAttribute('onclick') || '';
            if (onclick.includes('(' + studentId + ',')) {
                const badge = btn.querySelector('.badge-count');
                if (badge) badge.textContent = count;
                btn.setAttribute('onclick', onclick.replace(/,\s*\d+\s*\)/, ', ' + count + ')'));
            }
        });
    }

    // ============================================
    // توابع کمکی
    // ============================================
    function showFlash(id, msg, type) {
        const el = document.getElementById(id);
        el.textContent = msg;
        el.className = 'flash-message ' + type;
    }

    function hideFlash(id) {
        const el = document.getElementById(id);
        el.className = 'flash-message';
        el.textContent = '';
    }
</script>
@endsection