@extends('layout.master')

@section('title')
ملیسان | لیست گزارش‌ها
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/reports-list.css') }}">
<link rel="stylesheet" href="{{ asset('css/badge.css') }}">
@endsection

@section('mohtava')
<div class="reports-container">
    {{-- HEADER --}}
    <div class="reports-header">
        <div>
            <div class="info-badge course-badge">
                <span class="badge-icon">
                    <i class="fas fa-book-open"></i>
                </span>
                <span class="badge-label">لیست گزارش‌ها:</span>
                <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
            </div>        </div>
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