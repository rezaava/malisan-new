@extends('layout.master')

@section('title')
ملیسان | لیست گزارش‌ها
@endsection

@section('head')
<style>
    .reports-container {
        max-width: 1100px;
        margin: 30px auto;
        padding: 0 20px;
    }
    .reports-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }
    .reports-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }
    .reports-header h2 i {
        color: #1e6f9f;
        margin-left: 10px;
    }
    .reports-header .subtitle {
        font-size: 14px;
        color: #6b7a8f;
        margin-top: 4px;
    }
    .btn-back {
        padding: 8px 20px;
        background: #f0f4f9;
        border-radius: 10px;
        text-decoration: none;
        color: #1a2332;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
    }
    .btn-back:hover {
        background: #e3e8ef;
    }
    .stats-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }
    .stat-box {
        flex: 1;
        min-width: 120px;
        background: #fff;
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        text-align: center;
        border-right: 4px solid #1e6f9f;
    }
    .stat-box .number {
        font-size: 28px;
        font-weight: 800;
        color: #1a2332;
    }
    .stat-box .label {
        font-size: 13px;
        color: #6b7a8f;
    }
    .stat-box.pending { border-right-color: #ff9800; }
    .stat-box.approved { border-right-color: #4caf50; }
    .stat-box.rejected { border-right-color: #f44336; }
    .table-wrapper {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    .table thead {
        background: #f8fafc;
        border-bottom: 2px solid #eef2f7;
    }
    .table thead th {
        padding: 14px 20px;
        text-align: right;
        font-weight: 700;
        color: #4a5a6e;
        font-size: 13px;
    }
    .table tbody tr {
        border-bottom: 1px solid #f0f4f9;
        transition: all 0.2s ease;
    }
    .table tbody tr:hover {
        background: #f8fafc;
    }
    .table tbody td {
        padding: 14px 20px;
        vertical-align: middle;
        color: #1a2332;
    }
    .table .report-title a {
        color: #1e6f9f;
        text-decoration: none;
        font-weight: 600;
    }
    .table .report-title a:hover {
        text-decoration: underline;
    }
    .table .user-name {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .table .user-name i {
        color: #1e6f9f;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-badge.pending { background: #fff3cd; color: #e65100; }
    .status-badge.excellent { background: #e8f5e9; color: #2e7d32; }
    .status-badge.good { background: #e3f2fd; color: #1e6f9f; }
    .status-badge.medium { background: #fff3e0; color: #e65100; }
    .status-badge.weak { background: #ffebee; color: #c62828; }
    .btn-view {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        background: #e3f2fd;
        color: #1e6f9f;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-view:hover {
        background: #1e6f9f;
        color: #fff;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state .empty-icon {
        font-size: 60px;
        color: #d0d7e2;
        display: block;
        margin-bottom: 20px;
    }
    .empty-state h4 {
        color: #1a2332;
        font-size: 18px;
        margin-bottom: 8px;
    }
    .empty-state p {
        color: #6b7a8f;
        font-size: 14px;
    }
    @media (max-width: 768px) {
        .table thead { display: none; }
        .table tbody tr { display: block; padding: 16px 18px; }
        .table tbody td { display: flex; justify-content: space-between; padding: 6px 0; border: none; }
        .table tbody td::before { content: attr(data-label); font-weight: 700; color: #4a5a6e; font-size: 12px; }
        .table tbody td:last-child { padding-top: 10px; border-top: 1px solid #f0f4f9; margin-top: 6px; }
        .stats-row { flex-direction: column; }
    }

    /* ===== MODAL ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-box {
        background: #fff;
        border-radius: 20px;
        width: 100%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        animation: slideUp 0.3s ease;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .modal-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        border-bottom: 2px solid #f0f4f9;
        position: sticky;
        top: 0;
        background: #fff;
        border-radius: 20px 20px 0 0;
        z-index: 5;
    }
    .modal-head h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1a2332;
    }
    .modal-head h4 i {
        color: #1e6f9f;
        margin-left: 8px;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 22px;
        cursor: pointer;
        color: #6b7a8f;
        padding: 4px 8px;
        border-radius: 8px;
        transition: 0.3s;
    }
    .modal-close:hover {
        background: #ffebee;
        color: #c62828;
    }
    .modal-body {
        padding: 24px;
    }
    .modal-loading {
        text-align: center;
        padding: 40px 0;
        color: #6b7a8f;
    }
    .modal-loading i {
        font-size: 32px;
        display: block;
        margin-bottom: 12px;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .info-row {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid #f0f4f9;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #4a5a6e;
        min-width: 90px;
        font-size: 14px;
    }
    .info-value {
        color: #1a2332;
        font-size: 14px;
        flex: 1;
    }
    .info-value .badge {
        display: inline-block;
        padding: 2px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge.pending { background: #fff3cd; color: #e65100; }
    .badge.excellent { background: #e8f5e9; color: #2e7d32; }
    .badge.good { background: #e3f2fd; color: #1e6f9f; }
    .badge.medium { background: #fff3e0; color: #e65100; }
    .badge.weak { background: #ffebee; color: #c62828; }
    .info-text {
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 10px;
        line-height: 1.8;
        margin-top: 4px;
        border-right: 3px solid #1e6f9f;
    }
    .modal-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 2px solid #f0f4f9;
    }
    .modal-actions .btn {
        padding: 8px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.3s;
    }
    .modal-actions .btn-primary {
        background: #1e6f9f;
        color: #fff;
    }
    .modal-actions .btn-primary:hover {
        background: #155a82;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30,111,159,0.3);
    }
    .modal-actions .btn-secondary {
        background: #f0f4f9;
        color: #4a5a6e;
    }
    .modal-actions .btn-secondary:hover {
        background: #e3e8ef;
    }
    .error-msg {
        text-align: center;
        padding: 30px 0;
        color: #c62828;
    }
    .error-msg i {
        font-size: 40px;
        display: block;
        margin-bottom: 12px;
    }
    @media (max-width: 768px) {
        .modal-body { padding: 16px; }
        .info-row { flex-direction: column; gap: 4px; }
        .info-label { min-width: auto; }
        .modal-actions { flex-direction: column; }
        .modal-actions .btn { width: 100%; justify-content: center; }
    }
</style>
@endsection

@section('mohtava')
<div class="reports-container">
    {{-- HEADER --}}
    <div class="reports-header">
        <div>
            <h2><i class="fas fa-file-alt"></i> لیست گزارش‌ها</h2>
            <div class="subtitle"><i class="fas fa-book-open" style="margin-left:6px;color:#1e6f9f;"></i> {{ $course->name }}</div>
        </div>
        <a href="{{ route('view.coure', $course->id) }}" class="btn-back"><i class="fas fa-arrow-right"></i> بازگشت به درس</a>
    </div>

    {{-- STATS --}}
    <div class="stats-row">
        <div class="stat-box"><div class="number">{{ $stats['total'] ?? 0 }}</div><div class="label">کل گزارش‌ها</div></div>
        <div class="stat-box pending"><div class="number">{{ $stats['pending'] ?? 0 }}</div><div class="label">در انتظار داوری</div></div>
        <div class="stat-box approved"><div class="number">{{ $stats['approved'] ?? 0 }}</div><div class="label">تایید شده</div></div>
        <div class="stat-box rejected"><div class="number">{{ $stats['rejected'] ?? 0 }}</div><div class="label">رد شده</div></div>
    </div>

    {{-- TABLE --}}
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>دانشجو</th>
                    <th>جلسه</th>
                    <th>تاریخ</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $key => $report)
                    @php
                        $labels = [1=>'عالی',2=>'خوب',3=>'متوسط',4=>'بد'];
                        $classes = [1=>'excellent',2=>'good',3=>'medium',4=>'weak'];
                        $statusText = $report->status ? ($labels[$report->status] ?? 'نامشخص') : 'در انتظار داوری';
                        $statusClass = $report->status ? ($classes[$report->status] ?? 'pending') : 'pending';
                    @endphp
                    <tr>
                        <td data-label="ردیف">{{ $key + 1 }}</td>
                        <td data-label="دانشجو" class="user-name"><i class="fas fa-user-graduate"></i> {{ $report->user->name ?? 'نامشخص' }} {{ $report->user->family ?? '' }}</td>
                        <td data-label="جلسه">{{ $report->session->name ?? 'نامشخص' }}</td>
                        <td data-label="تاریخ">{{ \Hekmatinasser\Verta\Verta::instance($report->created_at)->format('Y/m/d H:i') }}</td>
                        <td data-label="وضعیت"><span class="status-badge {{ $statusClass }}">{{ $statusText }}</span></td>
                        <td data-label="عملیات"><button class="btn-view" onclick="showDetail({{ $report->id }})"><i class="fas fa-eye"></i> مشاهده</button></td>
                    </tr>
                @empty
                    <tr><td colspan="7">
                        <div class="empty-state">
                            <span class="empty-icon"><i class="fas fa-inbox"></i></span>
                            <h4>هیچ گزارشی ثبت نشده است</h4>
                            <p>هنوز دانشجویی در این درس گزارشی ثبت نکرده است.</p>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL --}}
<div class="modal-overlay" id="reportModal">
    <div class="modal-box">
        <div class="modal-head">
            <h4><i class="fas fa-file-alt"></i> <span id="modalTitle">جزئیات گزارش</span></h4>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="modalBody">
            <div class="modal-loading"><i class="fas fa-spinner"></i> در حال بارگذاری...</div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// ==========================================
// توابع مودال
// ==========================================

function showDetail(id) {
    const modal = document.getElementById('reportModal');
    const body = document.getElementById('modalBody');
    
    if (!modal) {
        console.error('Modal not found');
        return;
    }
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    body.innerHTML = `<div class="modal-loading"><i class="fas fa-spinner"></i> در حال بارگذاری...</div>`;

    fetch(`/teacher/courses/report/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const r = data.data;
                const labels = {1:'عالی',2:'خوب',3:'متوسط',4:'بد'};
                const classes = {1:'excellent',2:'good',3:'medium',4:'weak'};
                const statusText = r.status ? (labels[r.status] || 'نامشخص') : 'در انتظار داوری';
                const statusClass = r.status ? (classes[r.status] || 'pending') : 'pending';
                const name = r.user ? r.user.name + ' ' + (r.user.family || '') : 'نامشخص';

                body.innerHTML = `
                    <div class="info-row"><span class="info-label">عنوان</span><span class="info-value"><strong>${r.title || 'بدون عنوان'}</strong></span></div>
                    <div class="info-row"><span class="info-label">دانشجو</span><span class="info-value">${name}</span></div>
                    <div class="info-row"><span class="info-label">جلسه</span><span class="info-value">${r.session ? r.session.name : 'نامشخص'}</span></div>
                    <div class="info-row"><span class="info-label">تاریخ</span><span class="info-value">${r.created_at}</span></div>
                    <div class="info-row"><span class="info-label">وضعیت</span><span class="info-value"><span class="badge ${statusClass}">${statusText}</span></span></div>
                    <div class="info-row" style="flex-direction:column;gap:6px;">
                        <span class="info-label">متن گزارش</span>
                        <div class="info-text">${r.text || 'متن گزارش موجود نیست'}</div>
                    </div>
                    <div class="modal-actions">
                        <a href="/teacher/courses/judgment/${r.session.course_id}" class="btn btn-primary"><i class="fas fa-gavel"></i> رفتن به داوری</a>
                        <button class="btn btn-secondary" onclick="closeModal()"><i class="fas fa-times"></i> بستن</button>
                    </div>
                `;
            } else {
                body.innerHTML = `<div class="error-msg"><i class="fas fa-exclamation-circle"></i> خطا در دریافت اطلاعات</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            body.innerHTML = `<div class="error-msg"><i class="fas fa-exclamation-circle"></i> خطا در ارتباط با سرور</div>`;
        });
}

function closeModal() {
    const modal = document.getElementById('reportModal');
    if (modal) {
        modal.classList.remove('active');
    }
    document.body.style.overflow = 'auto';
}

// ==========================================
// رویدادها
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    // بستن مودال با کلیک روی overlay
    const modal = document.getElementById('reportModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    }
    
    // بستن مودال با دکمه Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});
</script>
@endsection