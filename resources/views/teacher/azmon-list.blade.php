@extends('layout.master')

@section('title')
ملیسان | لیست آزمون‌ها
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ... استایل‌های قبلی ... */
    .azmon-container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .azmon-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 30px;
    }

    .azmon-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .azmon-header h2 i {
        color: #1e6f9f;
        margin-left: 10px;
    }

    .azmon-header .subtitle {
        font-size: 14px;
        color: #6b7a8f;
        margin-top: 4px;
    }

    .btn-create-azmon {
        padding: 12px 28px;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-create-azmon:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 111, 159, 0.3);
        color: #fff;
    }

    /* ===== AZMON CARDS ===== */
    .azmon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 24px;
    }

    @media (max-width: 768px) {
        .azmon-grid {
            grid-template-columns: 1fr;
        }
    }

    .azmon-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 24px 28px;
        transition: all 0.3s ease;
        border-right: 5px solid #1e6f9f;
        position: relative;
        overflow: hidden;
    }

    .azmon-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 35px rgba(0, 0, 0, 0.1);
    }

    .azmon-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 120px;
        height: 120px;
        background: rgba(30, 111, 159, 0.04);
        border-radius: 50%;
    }

    .azmon-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .azmon-card .card-title {
        font-size: 17px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .azmon-card .card-code {
        background: #f0f4f9;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #1e6f9f;
        direction: ltr;
        display: inline-block;
    }

    .azmon-card .card-body {
        margin-bottom: 18px;
    }

    .azmon-card .card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 13px;
        color: #6b7a8f;
        margin-bottom: 10px;
    }

    .azmon-card .card-meta .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .azmon-card .card-meta .meta-item i {
        color: #1e6f9f;
        width: 16px;
    }

    .azmon-card .card-meta .meta-item .highlight {
        font-weight: 700;
        color: #1a2332;
    }

    .azmon-card .card-description {
        font-size: 14px;
        color: #4a5a6e;
        line-height: 1.7;
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 12px;
        margin-top: 10px;
        max-height: 60px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .azmon-card .card-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding-top: 16px;
        border-top: 2px solid #f0f4f9;
    }

    .btn-action {
        padding: 7px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-1px);
    }

    .btn-action-primary {
        background: #e3f2fd;
        color: #1e6f9f;
    }

    .btn-action-primary:hover {
        background: #1e6f9f;
        color: #fff;
    }

    .btn-action-success {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .btn-action-success:hover {
        background: #4caf50;
        color: #fff;
    }

    .btn-action-warning {
        background: #fff3e0;
        color: #e65100;
    }

    .btn-action-warning:hover {
        background: #ff9800;
        color: #fff;
    }

    .btn-action-danger {
        background: #ffebee;
        color: #c62828;
    }

    .btn-action-danger:hover {
        background: #f44336;
        color: #fff;
    }

    .btn-action-info {
        background: #e0f7fa;
        color: #00838f;
    }

    .btn-action-info:hover {
        background: #00bcd4;
        color: #fff;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-status.active {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-status.inactive {
        background: #ffebee;
        color: #c62828;
    }

    .badge-status.participants {
        background: #e3f2fd;
        color: #1e6f9f;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8fafc;
        border-radius: 20px;
    }

    .empty-state .empty-icon {
        font-size: 60px;
        color: #d0d7e2;
        margin-bottom: 20px;
        display: block;
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

    /* ===== MODAL ===== */
    #statsOverlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9998;
        animation: fadeIn 0.2s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    #statsModal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 24px 64px rgba(0,0,0,0.18);
        width: 460px;
        max-width: 95vw;
        overflow: hidden;
        animation: slideUp 0.25s cubic-bezier(.4,0,.2,1);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translate(-50%, calc(-50% + 20px)); }
        to { opacity: 1; transform: translate(-50%, -50%); }
    }

    #statsModal .modal-header {
        background: linear-gradient(135deg, #1e6f9f 0%, #0d4a6e 100%);
        color: #fff;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #statsModal .modal-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    #statsModal .modal-close-btn {
        background: rgba(255,255,255,0.15);
        border: none;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    #statsModal .modal-close-btn:hover {
        background: rgba(255,255,255,0.3);
    }

    #statsModal .modal-body {
        padding: 24px 20px 18px;
        min-height: 140px;
    }

    #statsModal .modal-footer {
        padding: 14px 20px;
        background: #f8fafc;
        border-top: 1px solid #e8edf2;
        text-align: left;
    }

    .stats-close-btn {
        padding: 9px 22px;
        border: none;
        border-radius: 8px;
        background: #64748b;
        color: #fff;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: background 0.2s;
    }

    .stats-close-btn:hover {
        background: #475569;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .stat-card {
        border-radius: 12px;
        padding: 16px 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        text-align: center;
    }

    .stat-card.card-count { background: #eff6ff; border: 1.5px solid #bfdbfe; }
    .stat-card.card-min   { background: #fff1f2; border: 1.5px solid #fecdd3; }
    .stat-card.card-max   { background: #f0fdf4; border: 1.5px solid #bbf7d0; }
    .stat-card.card-avg   { background: #fffbeb; border: 1.5px solid #fde68a; }

    .stat-card .stat-icon { font-size: 22px; line-height: 1; }
    .stat-card .stat-label { font-size: 12px; font-weight: 600; color: #64748b; }
    .stat-card .stat-value { font-size: 28px; font-weight: 800; line-height: 1.1; }
    .stat-card .stat-unit { font-size: 11px; color: #94a3b8; }

    .card-count .stat-value { color: #1d4ed8; }
    .card-min .stat-value { color: #dc2626; }
    .card-max .stat-value { color: #16a34a; }
    .card-avg .stat-value { color: #d97706; }

    .custom-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100px;
    }

    .custom-spinner::after {
        content: '';
        width: 40px;
        height: 40px;
        border: 4px solid #e2e8f0;
        border-top-color: #1e6f9f;
        border-radius: 50%;
        animation: spin 0.75s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .stats-empty {
        text-align: center;
        padding: 24px 0 8px;
        color: #94a3b8;
    }

    .stats-empty .empty-icon {
        font-size: 36px;
        display: block;
        margin-bottom: 8px;
    }
</style>
@endsection

@section('mohtava')
<div class="azmon-container">
    {{-- ===== HEADER ===== --}}
    <div class="azmon-header">
        <div>
            <h2>
                <i class="fas fa-tasks"></i>
                لیست آزمون‌ها
            </h2>
            <div class="subtitle">
                <i class="fas fa-book-open" style="margin-left:6px;color:#1e6f9f;"></i>
                {{ $course->name ?? 'دوره' }}
            </div>
        </div>
        <a href="{{ route('azmon.create', ['id' => $course->id]) }}" class="btn-create-azmon">
            <i class="fas fa-plus-circle"></i>
            آزمون جدید
        </a>
    </div>

    {{-- ===== AZMON CARDS ===== --}}
    @if($azmons->count() > 0)
        <div class="azmon-grid">
            @foreach($azmons as $azmon)
                <div class="azmon-card">
                    {{-- Header --}}
                    <div class="card-header">
                        <h5 class="card-title">{{ $azmon->title }}</h5>
                        <span class="card-code">
                            <i class="fas fa-key" style="font-size:10px;"></i>
                            {{ $azmon->code }}
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="card-body">
                        <div class="card-meta">
                            <span class="meta-item">
                                <i class="fas fa-users"></i>
                                <span class="highlight">{{ $azmon->participant_count ?? 0 }}</span>
                                شرکت‌کننده
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-clock"></i>
                                {{ $azmon->time }} دقیقه
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-question-circle"></i>
                                {{ $azmon->num }} سوال
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                {{-- استفاده از Verta به جای Jalalian --}}
                                {{ \Hekmatinasser\Verta\Verta::instance($azmon->start)->format('Y/m/d H:i') }}
                            </span>
                        </div>

                        @if($azmon->description)
                            <div class="card-description">
                                {!! Str::limit(strip_tags($azmon->description), 80) !!}
                            </div>
                        @endif

                        {{-- Status Badges --}}
                        <div style="display:flex;gap:6px;margin-top:10px;flex-wrap:wrap;">
                            @php
                                $now = \Carbon\Carbon::now();
                            @endphp
                            @if($now < $azmon->start)
                                <span class="badge-status inactive" style="background:#fff3e0;color:#e65100;">
                                    <i class="fas fa-clock"></i> در انتظار شروع
                                </span>
                            @elseif($now > $azmon->end)
                                <span class="badge-status inactive">
                                    <i class="fas fa-times-circle"></i> تمام شده
                                </span>
                            @else
                                <span class="badge-status active">
                                    <i class="fas fa-play-circle"></i> در حال اجرا
                                </span>
                            @endif

                            @if($azmon->zarib == 1)
                                <span class="badge-status participants">
                                    <i class="fas fa-star"></i> تأثیر در نمره
                                </span>
                            @else
                                <span class="badge-status inactive">
                                    <i class="fas fa-star-half-alt"></i> بدون تأثیر
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card-actions">
                        @if($azmon->expire == 0)
                            <a href="{{ route('azmon.edit', ['id' => $azmon->id]) }}" class="btn-action btn-action-primary">
                                <i class="fas fa-edit"></i> ویرایش
                            </a>
                        @endif

                        <button onclick="shareCode('{{ $azmon->code }}', '{{ addslashes($azmon->title) }}', '{{ addslashes($course->name) }}')" 
                                class="btn-action btn-action-success">
                            <i class="fas fa-share-alt"></i> اشتراک‌گذاری
                        </button>

                        <button onclick="showStats({{ $azmon->id }}, '{{ addslashes($azmon->title) }}')" 
                                class="btn-action btn-action-info">
                            <i class="fas fa-chart-bar"></i> آمار
                        </button>

                        <form action="{{ route('azmon.toggleZarib', $azmon->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-action btn-action-warning">
                                <i class="fas fa-balance-scale"></i>
                                {{ $azmon->zarib == 1 ? 'بی‌اثر' : 'اثر' }}
                            </button>
                        </form>

                        <a href="{{ route('azmon.delete', $azmon->id) }}" 
                           class="btn-action btn-action-danger"
                           onclick="return confirm('آیا مطمئن هستید که می‌خواهید این آزمون را حذف کنید؟')">
                            <i class="fas fa-trash-alt"></i> حذف
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="empty-state">
            <span class="empty-icon">
                <i class="fas fa-file-alt"></i>
            </span>
            <h4>هیچ آزمونی برای این دوره وجود ندارد</h4>
            <p>برای شروع، اولین آزمون خود را ایجاد کنید.</p>
            <a href="{{ route('azmon.create', ['id' => $course->id]) }}" 
               class="btn-create-azmon" style="margin-top:16px;display:inline-flex;">
                <i class="fas fa-plus-circle"></i>
                ایجاد آزمون جدید
            </a>
        </div>
    @endif
</div>

{{-- ===== STATS MODAL ===== --}}
<div id="statsOverlay" onclick="closeStats()"></div>
<div id="statsModal">
    <div class="modal-header">
        <h5 id="stats-title">وضعیت آزمون</h5>
        <button class="modal-close-btn" onclick="closeStats()">✕</button>
    </div>
    <div class="modal-body" id="stats-body">
        <div class="custom-spinner"></div>
    </div>
    <div class="modal-footer">
        <button onclick="closeStats()" class="stats-close-btn">بستن</button>
    </div>
</div>
@endsection

@section('script')
<script>
    // ===== Share Code =====
    function shareCode(code, title, courseName) {
        const text = `دانشجوی عزیز در سامانه ملیسان با رفتن به بخش آزمون‌ها در آزمون "${title}" درس "${courseName}" شرکت کنید. کد ورود به آزمون: ${code}`;
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ متن کپی شد!\n\n' + text);
            }).catch(() => {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    }

    function fallbackCopy(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            alert('✅ متن کپی شد!\n\n' + text);
        } catch (err) {
            alert('❌ کپی نشد. لطفاً متن زیر را کپی کنید:\n\n' + text);
        }
        document.body.removeChild(textarea);
    }

    // ===== Stats Modal =====
    function openStats() {
        document.getElementById('statsOverlay').style.display = 'block';
        document.getElementById('statsModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeStats() {
        document.getElementById('statsOverlay').style.display = 'none';
        document.getElementById('statsModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeStats();
    });

    function showStats(id, title) {
        document.getElementById('stats-title').innerHTML = 'وضعیت آزمون: ' + title;
        document.getElementById('stats-body').innerHTML = '<div class="custom-spinner"></div>';
        openStats();

        fetch('/teacher/courses/azmon/stats/' + id, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(res) {
            if (!res.ok) throw new Error('server error');
            return res.json();
        })
        .then(function(data) {
            if (data.count === 0) {
                document.getElementById('stats-body').innerHTML =
                    '<div class="stats-empty">' +
                        '<span class="empty-icon">📭</span>' +
                        '<p>هنوز کسی در این آزمون شرکت نکرده است.</p>' +
                    '</div>';
                return;
            }

            document.getElementById('stats-body').innerHTML =
                '<div class="stats-cards">' +
                    '<div class="stat-card card-count">' +
                        '<span class="stat-icon">👥</span>' +
                        '<span class="stat-label">تعداد شرکت‌کننده</span>' +
                        '<span class="stat-value">' + data.count + '</span>' +
                        '<span class="stat-unit">نفر</span>' +
                    '</div>' +
                    '<div class="stat-card card-avg">' +
                        '<span class="stat-icon">📊</span>' +
                        '<span class="stat-label">میانگین نمرات</span>' +
                        '<span class="stat-value">' + data.average + '</span>' +
                        '<span class="stat-unit">از ۲۰</span>' +
                    '</div>' +
                    '<div class="stat-card card-min">' +
                        '<span class="stat-icon">📉</span>' +
                        '<span class="stat-label">کمترین نمره</span>' +
                        '<span class="stat-value">' + data.min + '</span>' +
                        '<span class="stat-unit">از ۲۰</span>' +
                    '</div>' +
                    '<div class="stat-card card-max">' +
                        '<span class="stat-icon">📈</span>' +
                        '<span class="stat-label">بیشترین نمره</span>' +
                        '<span class="stat-value">' + data.max + '</span>' +
                        '<span class="stat-unit">از ۲۰</span>' +
                    '</div>' +
                '</div>';
        })
        .catch(function() {
            document.getElementById('stats-body').innerHTML =
                '<p style="text-align:center;color:#e7515a;padding:20px 0;">❌ خطا در دریافت اطلاعات. لطفاً دوباره تلاش کنید.</p>';
        });
    }
</script>
@endsection