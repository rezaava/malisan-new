@extends('layout.master')

@section('title')
ملیسان | لیست آزمون‌ها
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/azmon-list.css') }}">
<link rel="stylesheet" href="{{asset('css/badge.css')}}">

@endsection

@section('mohtava')
<div class="azmon-container">
    {{-- ===== HEADER ===== --}}
    <div class="azmon-header">
        <div class="info-badge course-badge">
            <span class="badge-icon">
                <i class="fas fa-book-open"></i>
            </span>
            <span class="badge-label">لیست آزمون‌ها در درس:</span>
            <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
        </div>
        <div>
            <a href="{{ route('azmon.create', ['id' => $course->id]) }}" class="btn-create-azmon">
                <i class="fas fa-plus-circle"></i>
                آزمون جدید
            </a>
            @include('layout.backbtn')
        </div>
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