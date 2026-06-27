@extends('layout.master')

@section('title')
ملیسان | بانک سوالات
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-question-bank.css')}}">
<style>
    .bank-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    .bank-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .bank-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .bank-title span {
        color: #1e6f9f;
    }

    /* استایل آمار */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 16px 20px;
        text-align: center;
        border: 1px solid #e8edf3;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #1a2332;
    }

    .stat-label {
        font-size: 12px;
        color: #6b7a8f;
        margin-top: 4px;
    }

    .stat-card.excellent .stat-number { color: #4caf50; }
    .stat-card.good .stat-number { color: #2196f3; }
    .stat-card.medium .stat-number { color: #ff9800; }
    .stat-card.bad .stat-number { color: #f44336; }
    .stat-card.pending .stat-number { color: #9e9e9e; }
    .stat-card.starred .stat-number { color: #ffc107; }

    /* جستجو و فیلتر */
    .bank-controls {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 10px 44px 10px 16px;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        direction: rtl;
        outline: none;
    }

    .search-box input:focus {
        border-color: #1e6f9f;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.08);
    }

    .search-box .search-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9aa8b9;
    }

    .filter-select {
        padding: 10px 16px;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        font-size: 14px;
        background: #fafbfc;
        color: #1a2332;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
        min-width: 140px;
    }

    .filter-select:focus {
        border-color: #1e6f9f;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.08);
    }

    /* سوالات */
    .questions-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .question-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8edf3;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .question-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .question-header {
        padding: 16px 20px;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 8px;
        cursor: pointer;
        border-bottom: 1px solid #f0f4f9;
    }

    .question-header .question-text {
        font-size: 15px;
        font-weight: 600;
        color: #1a2332;
        flex: 1;
    }

    .question-header .question-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 12px;
        color: #6b7a8f;
        flex-wrap: wrap;
    }

    .question-header .question-meta .designer {
        color: #1e6f9f;
        font-weight: 600;
    }

    .level-badge {
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .level-badge.excellent { background: #e8f5e9; color: #2e7d32; }
    .level-badge.good { background: #e3f2fd; color: #1565c0; }
    .level-badge.medium { background: #fff3e0; color: #e65100; }
    .level-badge.bad { background: #ffebee; color: #c62828; }
    .level-badge.pending { background: #f5f5f5; color: #616161; }

    .star-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        color: #d0d7e2;
        transition: all 0.3s ease;
        padding: 4px;
    }

    .star-btn.active {
        color: #ffc107;
    }

    .star-btn:hover {
        transform: scale(1.2);
    }

    .question-body {
        padding: 16px 20px;
        display: none;
    }

    .question-body.open {
        display: block;
    }

    .options-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 16px;
        margin-bottom: 16px;
    }

    .option-item {
        padding: 8px 12px;
        border-radius: 8px;
        background: #f8fafc;
        font-size: 14px;
        color: #1a2332;
        border-right: 3px solid #e8edf3;
        word-break: break-word;
    }

    .option-item.correct {
        border-right-color: #4caf50;
        background: #e8f5e9;
    }

    .option-item .option-label {
        font-weight: 600;
        margin-left: 6px;
    }

    .nazars-section {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f0f4f9;
    }

    .nazars-section .nazar-title {
        font-size: 13px;
        font-weight: 600;
        color: #1a2332;
        margin-bottom: 8px;
    }

    .nazar-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 8px;
        font-size: 13px;
        color: #333;
        background: #f8fafc;
        border-radius: 6px;
        margin-bottom: 4px;
    }

    .nazar-item .nazar-user {
        font-weight: 600;
        color: #1a2332;
    }

    .nazar-item .nazar-score {
        font-weight: 700;
    }

    .nazar-item .nazar-score.score-1 { color: #4caf50; }
    .nazar-item .nazar-score.score-2 { color: #2196f3; }
    .nazar-item .nazar-score.score-3 { color: #ff9800; }
    .nazar-item .nazar-score.score-4 { color: #f44336; }

    .question-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f0f4f9;
        flex-wrap: wrap;
    }

    .question-actions .btn {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-edit {
        background: #e3f2fd;
        color: #1565c0;
    }

    .btn-edit:hover {
        background: #bbdefb;
    }

    .btn-delete {
        background: #ffebee;
        color: #c62828;
    }

    .btn-delete:hover {
        background: #ffcdd2;
    }

    /* مودال نظر */
    .score-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 12px 0;
    }

    .score-options {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .score-options label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        cursor: pointer;
    }

    .score-options input[type="radio"] {
        accent-color: #1e6f9f;
        width: 16px;
        height: 16px;
    }

    .comment-input {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        direction: rtl;
        resize: vertical;
        min-height: 60px;
        font-family: inherit;
    }

    .comment-input:focus {
        border-color: #1e6f9f;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.08);
        outline: none;
    }

    .btn-submit-score {
        padding: 8px 24px;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        align-self: flex-start;
    }

    .btn-submit-score:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 111, 159, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7a8f;
        background: #fff;
        border-radius: 16px;
        border: 2px dashed #e8edf3;
    }

    .empty-state i {
        font-size: 48px;
        color: #d0d7e2;
        display: block;
        margin-bottom: 16px;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 8px;
        background: #f8fafc;
        font-size: 14px;
        color: #1a2332;
        border-right: 3px solid #e8edf3;
        word-break: break-word;
        position: relative;
        transition: all 0.3s ease;
    }

    .option-item.correct {
        border-right-color: #4caf50;
        background: #e8f5e9;
    }

    .option-item .option-label {
        font-weight: 600;
        margin-left: 6px;
    }

    .correct-badge {
        margin-right: auto;
        color: #4caf50;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .correct-badge i {
        font-size: 16px;
    }

    .btn-create-course{
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        .bank-container { padding: 10px; }
        .bank-header { flex-direction: column; align-items: stretch; }
        .bank-title { text-align: center; }
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
        .options-list { grid-template-columns: 1fr; }
        .question-header { flex-direction: column; }
        .question-header .question-meta { width: 100%; justify-content: flex-start; }
        .bank-controls { flex-direction: column; }
        .search-box { min-width: auto; }
        .filter-select { width: 100%; }
    }
    .nazar-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 0;
    border-bottom: 1px solid #f0f4f9;
    font-size: 13px;
}

.nazar-item:last-child {
    border-bottom: none;
}

.nazar-item.my-judgment {
    background: #e3f2fd;
    padding: 4px 8px;
    border-radius: 6px;
}

.nazar-score {
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.nazar-score.excellent {
    background: #e8f5e9;
    color: #2e7d32;
}

.nazar-score.good {
    background: #e3f2fd;
    color: #1e6f9f;
}

.nazar-score.medium {
    background: #fff3e0;
    color: #e65100;
}

.nazar-score.weak {
    background: #ffebee;
    color: #c62828;
}

.nazar-score.very-weak {
    background: #f44336;
    color: #fff;
}

.nazar-user {
    font-weight: 600;
    color: #1a2332;
}
</style>
@endsection

@section('mohtava')
<div class="bank-container">
    <div class="bank-header">
        <h4 class="bank-title">بانک سوالات : <span>{{ $course->name ?? 'عنوان درس' }}</span></h4>
    </div>

    <!-- آمار -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
            <div class="stat-label">کل سوالات</div>
        </div>
        <div class="stat-card excellent">
            <div class="stat-number">{{ $stats['excellent'] ?? 0 }}</div>
            <div class="stat-label">عالی</div>
        </div>
        <div class="stat-card good">
            <div class="stat-number">{{ $stats['good'] ?? 0 }}</div>
            <div class="stat-label">خوب</div>
        </div>
        <div class="stat-card medium">
            <div class="stat-number">{{ $stats['medium'] ?? 0 }}</div>
            <div class="stat-label">متوسط</div>
        </div>
        <div class="stat-card bad">
            <div class="stat-number">{{ $stats['bad'] ?? 0 }}</div>
            <div class="stat-label">ضعیف</div>
        </div>
        <div class="stat-card pending">
            <div class="stat-number">{{ $stats['pending'] ?? 0 }}</div>
            <div class="stat-label">در انتظار تایید</div>
        </div>
        <div class="stat-card starred">
            <div class="stat-number">{{ $stats['starred'] ?? 0 }}</div>
            <div class="stat-label">ستاره دار</div>
        </div>
    </div>

    <!-- کنترل‌ها -->
    <div class="bank-controls">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchInput" placeholder="جستجوی سوال...">
        </div>
        <select class="filter-select" id="filterLevel">
            <option value="all">همه سوالات</option>
            <option value="1">عالی</option>
            <option value="2">خوب</option>
            <option value="3">متوسط</option>
            <option value="4">ضعیف</option>
            <option value="null">در انتظار تایید</option>
        </select>
        <button class="btn-create-course btn btn-sm rounded" id="toggleAllBtn">
            <i class="fas fa-eye"></i>
            نمایش همه
        </button>
    </div>

    <!-- لیست سوالات -->
    <div class="questions-list" id="questionsList">
        @forelse($questions ?? [] as $question)
            <div class="question-card" data-level="{{ $question->status ?? 'null' }}">
                <div class="question-header" onclick="toggleQuestion(this)">
                    <div class="question-text">
                        <button class="star-btn {{ $question->star == 1 ? 'active' : '' }}" 
                                onclick="event.stopPropagation(); toggleStar({{ $question->id }})">
                            <i class="fas fa-star"></i>
                        </button>
                        {{ $question->question }}
                    </div>
                    <div class="question-meta">
                        <span class="designer">
                            <i class="fas fa-user"></i> {{ $question->designer_name ?? 'نامشخص' }}
                        </span>
                        <span class="level-badge {{ 
                            $question->status == 1 ? 'excellent' : 
                            ($question->status == 2 ? 'good' : 
                            ($question->status == 3 ? 'medium' : 
                            ($question->status == 4 ? 'bad' : 'pending'))) 
                        }}">
                            {{ $question->level_text ?? 'نامشخص' }}
                        </span>
                        <i class="fas fa-chevron-down" style="color:#6b7a8f;font-size:12px;"></i>
                    </div>
                </div>
                <div class="question-body">
                    <div class="options-list">
                        @php
                            $options = [
                                ['label' => 'الف', 'value' => $question->answer1, 'index' => 1],
                                ['label' => 'ب', 'value' => $question->answer2, 'index' => 2],
                                ['label' => 'ج', 'value' => $question->answer3, 'index' => 3],
                                ['label' => 'د', 'value' => $question->answer4, 'index' => 4],
                            ];
                        @endphp

                        @foreach($options as $option)
                            <div class="option-item {{ $option['index'] == $question->answer ? 'correct' : '' }}">
                                <span class="option-label">{{ $option['label'] }}.</span>
                                {{ $option['value'] }}
                                @if($option['index'] == $question->answer)
                                    <span class="correct-badge">
                                        <i class="fas fa-check-circle"></i> پاسخ صحیح
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <!-- نظرات -->
                    @if(isset($question->nazars) && $question->nazars->count() > 0)
                        <div class="nazars-section">
                            <div class="nazar-title">نظرات داوران:</div>
                            @foreach($question->nazars as $key => $nazar)
                                <div class="nazar-item {{ $nazar->user_id == Auth::id() ? 'my-judgment' : '' }}">
                                    <span class="nazar-user">{{ $nazar->user->name ?? 'ناشناس' }} {{ $nazar->user->family ?? '' }}</span>
                                    <span class="nazar-score score-{{ $nazar->score_class }}">
                                        {{ $nazar->score_label }}
                                    </span>
                                    @if($nazar->comment1)
                                        <span style="color:#6b7a8f;font-size:12px;">({{ $nazar->comment1 }})</span>
                                    @endif
                                    @if($nazar->user_id == Auth::id())
                                        <span style="color:#1e6f9f;font-size:11px;font-weight:600;">(شما)</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- عملیات -->
                    <div class="question-actions">
                        <a href="/teacher/questions/delete/{{ $question->id }}" class="btn btn-delete" 
                           onclick="return confirm('آیا از حذف این سوال اطمینان دارید؟')">
                            <i class="fas fa-trash"></i> حذف
                        </a>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    </div>
</div>
@endsection

@section('js')
<script>
    // ============================================
    // تاگل نمایش/مخفی کردن گزینه‌ها
    // ============================================
    function toggleQuestion(header) {
        var body = header.nextElementSibling;
        var icon = header.querySelector('.fa-chevron-down');
        
        if (body.classList.contains('open')) {
            body.classList.remove('open');
            icon.style.transform = 'rotate(0deg)';
        } else {
            body.classList.add('open');
            icon.style.transform = 'rotate(180deg)';
        }
    }

    // ============================================
    // تاگل ستاره
    // ============================================
    function toggleStar(questionId) {
        var btn = document.querySelector('.star-btn[onclick*="toggleStar(' + questionId + ')"]');
        if (!btn) return;
        
        var originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;
        
        fetch('/teacher/courses/bank/star/' + questionId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                if (data.star === 1) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
                showToast('وضعیت ستاره تغییر کرد', 'success');
            } else {
                showToast(data.message || 'خطا در تغییر وضعیت ستاره', 'error');
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            showToast('خطا در ارتباط با سرور', 'error');
        })
        .finally(function() {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    }

    // ============================================
    // جستجو
    // ============================================
    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var searchText = this.value.toLowerCase().trim();
            var cards = document.querySelectorAll('.question-card');
            
            cards.forEach(function(card) {
                var text = card.querySelector('.question-text').textContent.toLowerCase();
                if (text.includes(searchText)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // ============================================
    // فیلتر سطح
    // ============================================
    var filterLevel = document.getElementById('filterLevel');
    if (filterLevel) {
        filterLevel.addEventListener('change', function() {
            var selected = this.value;
            var cards = document.querySelectorAll('.question-card');
            
            cards.forEach(function(card) {
                var level = card.getAttribute('data-level');
                if (selected === 'all' || level === selected) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // ============================================
    // نمایش همه
    // ============================================
    var toggleAllBtn = document.getElementById('toggleAllBtn');
    if (toggleAllBtn) {
        var allOpen = false;
        toggleAllBtn.addEventListener('click', function() {
            var bodies = document.querySelectorAll('.question-body');
            var icons = document.querySelectorAll('.fa-chevron-down');
            
            allOpen = !allOpen;
            
            bodies.forEach(function(body, index) {
                if (allOpen) {
                    body.classList.add('open');
                    if (icons[index]) icons[index].style.transform = 'rotate(180deg)';
                } else {
                    body.classList.remove('open');
                    if (icons[index]) icons[index].style.transform = 'rotate(0deg)';
                }
            });
            
            toggleAllBtn.innerHTML = allOpen ? 
                '<i class="fas fa-eye-slash"></i> مخفی همه' : 
                '<i class="fas fa-eye"></i> نمایش همه';
        });
    }

    // ============================================
    // TOAST NOTIFICATION
    // ============================================
    function showToast(message, type) {
        var existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();

        var toast = document.createElement('div');
        toast.className = 'toast-notification';
        
        var colors = {
            success: '#4CAF50',
            error: '#f44336',
            info: '#2196F3',
            warning: '#FF9800'
        };

        toast.style.cssText = `
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: ${colors[type] || colors.info};
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            z-index: 100000;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            animation: slideUp 0.4s ease;
            direction: rtl;
            max-width: 90%;
            text-align: center;
        `;

        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.4s';
            setTimeout(function() { toast.remove(); }, 400);
        }, 3500);
    }
</script>
@endsection