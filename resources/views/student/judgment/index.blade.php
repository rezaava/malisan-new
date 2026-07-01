@extends('layout.master')

@section('title')
ملیسان | داوری
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .judgment-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .judgment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .judgment-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .judgment-header h2 i {
        color: #1e6f9f;
        margin-left: 10px;
    }

    .judgment-header .subtitle {
        font-size: 14px;
        color: #6b7a8f;
        margin-top: 4px;
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
    .stat-box.done { border-right-color: #4caf50; }

    .judgment-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        padding: 28px 30px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .judgment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 30px rgba(0,0,0,0.1);
    }

    .judgment-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }

    .badge-type {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-type.question { background: #e3f2fd; color: #1e6f9f; }
    .badge-type.discussion { background: #fce4ec; color: #c62828; }
    .badge-type.exercise { background: #e8f5e9; color: #2e7d32; }

    .user-info {
        font-size: 14px;
        color: #6b7a8f;
    }

    .user-info i {
        color: #1e6f9f;
        margin-left: 4px;
    }

    .course-badge {
        display: inline-block;
        padding: 2px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        background: #f0f4f9;
        color: #4a5a6e;
        margin-left: 8px;
    }

    .content {
        font-size: 15px;
        color: #1a2332;
        line-height: 1.8;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 10px;
        margin: 10px 0 16px;
    }

    .options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin: 10px 0 16px;
    }

    @media (max-width: 600px) {
        .options {
            grid-template-columns: 1fr;
        }
    }

    .option-item {
        padding: 8px 14px;
        border-radius: 8px;
        background: #fafbfc;
        border: 2px solid #eef2f7;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .option-item .opt-label {
        font-weight: 700;
        color: #6b7a8f;
        min-width: 24px;
    }

    .option-item.correct {
        border-color: #4caf50;
        background: #e8f5e9;
    }

    .score-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        background: #f5f5f5;
        color: #6b7a8f;
    }

    .score-badge i {
        color: #ffd700;
    }

    /* ===== JUDGMENT FORM ===== */
    .judgment-form {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 2px solid #f0f4f9;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 13px;
        color: #1a2332;
    }

    .form-group label .sub {
        font-weight: 400;
        color: #6b7a8f;
        font-size: 12px;
    }

    .form-group select,
    .form-group textarea,
    .form-group input[type="number"] {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        background: #fafbfc;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-group select:focus,
    .form-group textarea:focus,
    .form-group input[type="number"]:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30,111,159,0.1);
        background: #fff;
    }

    .form-group textarea {
        min-height: 60px;
        resize: vertical;
    }

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 8px 0;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        font-size: 14px;
        color: #1a2332;
    }

    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #f44336;
        cursor: pointer;
    }

    .checkbox-item input[type="checkbox"]:checked {
        accent-color: #f44336;
    }

    .btn-judge {
        padding: 10px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        align-self: flex-end;
        margin-top: 4px;
    }

    .btn-judge:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30,111,159,0.3);
    }

    .btn-judge:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-judge.btn-danger {
        background: linear-gradient(135deg, #f44336, #c62828);
    }

    .btn-judge.btn-danger:hover:not(:disabled) {
        box-shadow: 0 4px 15px rgba(244,67,54,0.3);
    }

    .btn-judge.btn-success {
        background: linear-gradient(135deg, #4caf50, #388e3c);
    }

    .btn-judge.btn-success:hover:not(:disabled) {
        box-shadow: 0 4px 15px rgba(76,175,80,0.3);
    }

    .btn-judge.btn-warning {
        background: linear-gradient(135deg, #ff9800, #e65100);
    }

    .btn-judge.btn-warning:hover:not(:disabled) {
        box-shadow: 0 4px 15px rgba(255,152,0,0.3);
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

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8fafc;
        border-radius: 20px;
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

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        margin-top: 8px;
    }

    @media (max-width: 768px) {
        .judgment-card {
            padding: 16px;
        }
        .action-buttons {
            flex-direction: column;
        }
        .btn-judge {
            width: 100%;
            justify-content: center;
        }
        .stats-row {
            flex-direction: column;
        }
        .checkbox-group {
            flex-direction: column;
            gap: 6px;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="judgment-container">
    {{-- HEADER --}}
    <div class="judgment-header">
        <div>
            <h2>
                <i class="fas fa-gavel"></i>
                داوری محتوا
            </h2>
            <div class="subtitle">
                <i class="fas fa-users" style="margin-left:6px;color:#1e6f9f;"></i>
                شما به عنوان داور، محتوای دانشجویان دیگر را بررسی می‌کنید
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('student.judgment.returned') }}" class="btn-back" style="background:#fff3cd;color:#856404;">
                <i class="fas fa-undo"></i> برگشت‌خورده‌ها
            </a>
            <a href="{{ route('student.judgment.stats') }}" class="btn-back" style="background:#e3f2fd;color:#1e6f9f;">
                <i class="fas fa-chart-bar"></i> آمار
            </a>
        </div>
    </div>

    {{-- STATS --}}
    <div class="stats-row">
        <div class="stat-box pending">
            <div class="number">{{ $stats['total'] ?? 0 }}</div>
            <div class="label">در انتظار داوری</div>
        </div>
        <div class="stat-box done">
            <div class="number">{{ $stats['my_judgments'] ?? 0 }}</div>
            <div class="label">تعداد داوری‌های من</div>
        </div>
    </div>

    {{-- ITEMS --}}
    @if(count($items) > 0)
        @foreach($items as $index => $item)
            <div class="judgment-card">
                <div class="card-header">
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <span class="badge-type {{ $item['type'] }}">
                            <i class="fas {{ $item['type'] == 'question' ? 'fa-question-circle' : ($item['type'] == 'discussion' ? 'fa-file-alt' : 'fa-tasks') }}"></i>
                            {{ $item['type_label'] }}
                        </span>
                        <span class="course-badge">
                            <i class="fas fa-book-open"></i> {{ $item['course_name'] ?? 'نامشخص' }}
                        </span>
                        <span class="user-info">
                            <i class="fas fa-user-graduate"></i>
                            {{ $item['user']->name ?? 'ناشناس' }} {{ $item['user']->family ?? '' }}
                        </span>
                        <span class="score-badge">
                            <i class="fas fa-star"></i>
                            {{ $item['score_count'] ?? 0 }} از ۳ داوری
                        </span>
                    </div>
                    <span style="font-size:13px;color:#6b7a8f;">
                        {{ \Hekmatinasser\Verta\Verta::instance($item['created_at'])->format('Y/m/d H:i') }}
                    </span>
                </div>

                {{-- CONTENT --}}
                <div class="content">
                    @if($item['type'] == 'question')
                        <strong>سوال:</strong> 
                        {{ is_array($item['title']) ? ($item['title']['question'] ?? json_encode($item['title'])) : $item['title'] }}
                        
                        @if(isset($item['answers']) && is_array($item['answers']))
                            <div class="options">
                                @foreach($item['answers'] as $key => $answer)
                                    @php
                                        if (is_array($answer)) {
                                            $displayValue = $answer['value'] ?? json_encode($answer);
                                        } else {
                                            $displayValue = $answer;
                                        }
                                        $label = ['الف', 'ب', 'ج', 'د'][$key] ?? $key;
                                    @endphp
                                    <div class="option-item {{ ($key + 1) == $item['correct_answer'] ? 'correct' : '' }}">
                                        <span class="opt-label">{{ $label }}</span>
                                        <span>{{ $displayValue }}</span>
                                        @if(($key + 1) == $item['correct_answer'])
                                            <span style="color:#4caf50;margin-right:auto;">✓</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                    @elseif($item['type'] == 'discussion')
                        <strong>عنوان:</strong> 
                        {{ $item['title'] }}
                        @if(isset($item['text']))
                            <div style="margin-top:8px;color:#4a5a6e;">
                                {{ $item['text'] }}
                            </div>
                        @endif
                        
                    @elseif($item['type'] == 'exercise')
                        <strong>تکلیف:</strong> 
                        {{ is_array($item['title']) ? json_encode($item['title']) : $item['title'] }}
                        @if(isset($item['answer_text']))
                            <div style="margin-top:8px;color:#4a5a6e;">
                                <strong>پاسخ:</strong> 
                                {{ is_array($item['answer_text']) ? json_encode($item['answer_text']) : $item['answer_text'] }}
                            </div>
                        @endif
                    @endif
                </div>

                {{-- JUDGMENT FORM --}}
                @if($item['score_count'] < 3)
                    <form method="POST" action="{{ route('student.judgment.store') }}" class="judgment-form" onsubmit="return validateForm(this)">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                        <input type="hidden" name="type" value="{{ $item['type'] }}">

                        {{-- مشکلات (چک‌باکس‌ها) --}}
                        <div class="form-row" style="grid-template-columns:1fr;">
                            <div class="form-group">
                                <label>مشکلات محتوا <span class="sub">(در صورت وجود تیک بزنید)</span></label>
                                <div class="checkbox-group">
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="negaresh" value="1">
                                        <span>❌ ایراد نگارشی</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="gozine" value="1">
                                        <span>❌ ایراد گزینه‌ها</span>
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="dark" value="1">
                                        <span>❌ ایراد گویایی</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- نمره و کامنت --}}
                        <div class="form-row">
                            <div class="form-group">
                                <label>نمره <span class="sub">(در صورت تایید)</span></label>
                                <select name="score">
                                    <option value="">انتخاب کنید...</option>
                                    <option value="1">🌟 عالی</option>
                                    <option value="2">✅ خوب</option>
                                    <option value="3">📊 متوسط</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>توضیحات <span class="sub">(در صورت رد، اجباری)</span></label>
                                <textarea name="comment" placeholder="نظر خود را وارد کنید..."></textarea>
                            </div>
                        </div>

                        {{-- دکمه‌های اقدام --}}
                        <div class="action-buttons">
                            <button type="submit" name="action" value="approve" class="btn-judge btn-success">
                                <i class="fas fa-check"></i> تایید
                            </button>
                            <button type="submit" name="action" value="reject" class="btn-judge btn-danger" onclick="return confirmReject()">
                                <i class="fas fa-times"></i> رد
                            </button>
                        </div>
                    </form>
                @else
                    <div style="margin-top:16px;padding:12px 16px;background:#e8f5e9;border-radius:10px;color:#2e7d32;text-align:center;">
                        <i class="fas fa-check-circle"></i>
                        این آیتم قبلاً توسط ۳ نفر داوری شده و وضعیت آن تعیین شده است.
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <span class="empty-icon">
                <i class="fas fa-check-circle" style="color:#4caf50;"></i>
            </span>
            <h4>همه چیز داوری شده است!</h4>
            <p>هیچ محتوایی برای داوری وجود ندارد.</p>
        </div>
    @endif
</div>

<script>
    function validateForm(form) {
        const action = form.querySelector('button[type="submit"][clicked="true"]');
        if (!action) return true;
        
        const comment = form.querySelector('textarea[name="comment"]');
        const issues = form.querySelectorAll('input[name="negaresh"], input[name="gozine"], input[name="dark"]');
        const hasIssue = Array.from(issues).some(cb => cb.checked);
        
        // اگر رد است و توضیح ندارد
        if (action.value === 'reject' && !comment.value.trim()) {
            alert('برای رد کردن، لطفاً توضیح دهید که مشکل چیست.');
            comment.focus();
            return false;
        }
        
        // اگر رد است و هیچ ایرادی انتخاب نشده
        if (action.value === 'reject' && !hasIssue) {
            alert('برای رد کردن، لطفاً حداقل یک مشکل را انتخاب کنید.');
            return false;
        }
        
        // اگر تایید است و نمره انتخاب نشده
        if (action.value === 'approve' && !form.querySelector('select[name="score"]').value) {
            alert('لطفاً نمره را انتخاب کنید.');
            return false;
        }
        
        return true;
    }
    
    function confirmReject() {
        return confirm('آیا مطمئن هستید که می‌خواهید این محتوا را رد کنید؟');
    }
    
    // تشخیص اینکه کدام دکمه کلیک شده
    document.querySelectorAll('.btn-judge').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-judge').forEach(b => b.removeAttribute('clicked'));
            this.setAttribute('clicked', 'true');
        });
    });
</script>
@endsection