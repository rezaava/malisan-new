@extends('layout.master')

@section('title')
ملیسان | دانشجویان درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-students-list.css')}}">
<style>
    /* استایل دکمه نمایش دانشجویان اخراج شده */
    .removed-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        text-decoration: none;
    }

    .removed-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(231, 76, 60, 0.3);
        color: #fff;
    }

    .removed-btn i {
        font-size: 16px;
    }

    .removed-count {
        background: rgba(255, 255, 255, 0.3);
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    /* استایل مودال اخراج شده‌ها */
    .restore-btn {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .restore-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
    }

    .restore-btn i {
        font-size: 12px;
    }

    .removed-badge {
        display: inline-block;
        background: #e74c3c;
        color: #fff;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .removed-date {
        font-size: 12px;
        color: #6b7a8f;
    }

    .empty-removed {
        text-align: center;
        padding: 40px;
        color: #6b7a8f;
    }

    .empty-removed i {
        font-size: 48px;
        color: #d0d7e2;
        display: block;
        margin-bottom: 16px;
    }
</style>
@endsection

@section('mohtava')
<div class="students-container">
    <div class="students-header">
        <h4 class="students-title">دانشجویان درس : <span>{{ $course->name ?? 'عنوان درس' }}</span></h4>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="/export/students?course_id={{ $course->id ?? 0 }}" class="export-btn">
                <i class="fas fa-file-excel"></i>
                خروجی فایل اکسل
            </a>
            <button class="removed-btn" onclick="openRemovedModal()">
                <i class="fas fa-user-slash"></i>
                دانشجویان اخراج شده
                <span class="removed-count" id="removedCountBadge">{{ $removedCount ?? 0 }}</span>
            </button>
        </div>
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
                            <a href="{{ route('studentProfile', $user->id) }}" class="action-btn profile-btn" title="مشخصات">
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
                                        onclick="openAdjModal({{ $user->id }}, '{{ addslashes($user->name . ' ' . $user->family) }}', {{ $user->student_adjectives_count ?? 0 }})"
                                        title="صفات">
                                    <i class="fas fa-list-ul"></i>
                                    <span class="badge-count">{{ $user->student_adjectives_count ?? 0 }}</span>
                                </button>
                                <span class="badge-tooltip">ثبت صفت جدید</span>
                            </div>
                        </td>
                        <td>
                            <div class="badge-btn-wrap">
                                <button type="button" class="action-btn events-btn"
                                        onclick="openEvtModal({{ $user->id }}, '{{ addslashes($user->name . ' ' . $user->family) }}', {{ $user->student_events_count ?? 0 }})"
                                        title="رویداد">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="badge-count">{{ $user->student_events_count ?? 0 }}</span>
                                </button>
                                <span class="badge-tooltip">ثبت رویداد جدید</span>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="action-btn remove-btn" 
                                    onclick="removeStudent({{ $user->id }}, {{ $course->id }}, '{{ addslashes($user->name . ' ' . $user->family) }}')"
                                    title="اخراج">
                                <i class="fas fa-user-minus"></i>
                            </button>
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

<!-- ============================================
     مودال دانشجویان اخراج شده (Removed Students)
     ============================================ -->
<div class="modal-overlay" id="removedModalOverlay">
    <div class="modal-container" style="max-width: 700px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
            <h4><i class="fas fa-user-slash"></i> دانشجویان اخراج شده</h4>
            <button class="modal-close" onclick="closeRemovedModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="removedStudentsList">
                <!-- لیست دانشجویان اخراج شده با AJAX بارگذاری می‌شود -->
                <div class="text-center" style="padding:20px;">
                    <i class="fas fa-spinner fa-spin" style="font-size:24px;color:#1e6f9f;"></i>
                    <p>در حال بارگذاری...</p>
                </div>
            </div>
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
    // مودال دانشجویان اخراج شده
    // ============================================
    function openRemovedModal() {
        document.getElementById('removedModalOverlay').classList.add('open');
        loadRemovedStudents();
    }

    function closeRemovedModal() {
        document.getElementById('removedModalOverlay').classList.remove('open');
    }

    document.getElementById('removedModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeRemovedModal();
    });

    function loadRemovedStudents() {
        const container = document.getElementById('removedStudentsList');
        container.innerHTML = '<div class="text-center" style="padding:20px;"><i class="fas fa-spinner fa-spin" style="font-size:24px;color:#1e6f9f;"></i><p>در حال بارگذاری...</p></div>';

        fetch('/teacher/courses/students/removed/{{ $course->id }}')
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    container.innerHTML = `
                        <div class="empty-removed">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>${data.message || 'خطا در بارگذاری اطلاعات'}</p>
                        </div>
                    `;
                    return;
                }

                if (!data.data || data.data.length === 0) {
                    container.innerHTML = `
                        <div class="empty-removed">
                            <i class="fas fa-user-check"></i>
                            <p>هیچ دانشجویی از این دوره اخراج نشده است</p>
                        </div>
                    `;
                    return;
                }

                let html = `
                    <div style="margin-bottom:16px;padding:12px 16px;background:#fff5f5;border-radius:10px;border-right:3px solid #e74c3c;">
                        <span style="font-weight:700;color:#e74c3c;">تعداد دانشجویان اخراج شده: ${data.data.length}</span>
                    </div>
                    <table class="history-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="col-main">نام دانشجو</th>
                                <th>تاریخ اخراج</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.data.forEach((item, index) => {
                    const userName = item.user ? (item.user.name + ' ' + item.user.family) : 'کاربر حذف شده';
                    const deletedAt = item.deleted_at ? new Date(item.deleted_at).toLocaleDateString('fa-IR') : '-';
                    
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td class="col-main">${userName}</td>
                            <td>${deletedAt}</td>
                            <td>
                                <button class="restore-btn" onclick="restoreStudent(${item.user_id}, ${item.course_id})">
                                    <i class="fas fa-undo"></i>
                                    برگرداندن
                                </button>
                            </td>
                        </tr>
                    `;
                });

                html += `
                        </tbody>
                    </table>
                `;

                container.innerHTML = html;

                // بروزرسانی تعداد در Badge
                document.getElementById('removedCountBadge').textContent = data.data.length;

            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = `
                    <div class="empty-removed">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>خطا در ارتباط با سرور</p>
                    </div>
                `;
            });
    }

    function restoreStudent(userId, courseId) {
        if (!confirm('آیا از بازگرداندن این دانشجو به دوره اطمینان دارید؟')) {
            return;
        }

        fetch(`/teacher/courses/students/restore/${userId}/${courseId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                // بارگذاری مجدد لیست
                loadRemovedStudents();
                // بروزرسانی تعداد در Badge
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('خطا در ارتباط با سرور', 'error');
        });
    }

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
        
        fetch('/teacher/courses/adjectives/' + studentId)
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
        
        fetch('/teacher/courses/adjectives', {
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
        
        fetch('/teacher/courses/events/' + studentId)
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
        
        fetch('/teacher/courses/events', {
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
    // اخراج دانشجو
    // ============================================
    function removeStudent(userId, courseId, userName) {
        if (confirm(`آیا از اخراج دانشجو "${userName}" از این دوره اطمینان دارید؟`)) {
            fetch(`/teacher/courses/students/remove/${userId}/${courseId}`, {
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    // بروزرسانی تعداد در Badge
                    const badge = document.getElementById('removedCountBadge');
                    if (badge) {
                        const current = parseInt(badge.textContent) || 0;
                        badge.textContent = current + 1;
                    }
                    location.reload();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('خطا در ارتباط با سرور', 'error');
            });
        }
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

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            direction: rtl;
        `;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endsection