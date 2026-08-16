@extends('layout.master')

@section('title')
ملیسان | داوری
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/judgment-index.css') }}">
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
                        {!! is_array($item['title']) ? ($item['title']['question'] ?? json_encode($item['title'])) : $item['title'] !!}
                        
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

                {{-- JUDGMENT FORMS --}}
                @if($item['score_count'] < 3)
                    <div class="judgment-forms-container">
                        <div class="row g-3">
                            {{-- فرم رد (سمت چپ) --}}
                            <div class="col-md-6">
                                <div class="form-wrapper">
                                    <form method="POST" action="{{ route('student.judgment.store') }}" class="judgment-form" onsubmit="return validateReject(this)">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                        <input type="hidden" name="type" value="{{ $item['type'] }}">
                                        <input type="hidden" name="action" value="reject">

                                        <div class="reject-form-wrapper">
                                            <div class="form-title">
                                                <i class="fas fa-times-circle"></i> رد محتوا
                                            </div>

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

                                            <div class="form-group">
                                                <label>توضیحات <span class="sub">(اجباری برای رد)</span></label>
                                                <textarea name="comment" placeholder="دلیل رد را بنویسید..."></textarea>
                                            </div>

                                            <button type="submit" class="btn-judge btn-danger">
                                                <i class="fas fa-times"></i> رد
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- فرم تایید (سمت راست) --}}
                            <div class="col-md-6">
                                <div class="form-wrapper">
                                    <form method="POST" action="{{ route('student.judgment.store') }}" class="judgment-form" onsubmit="return validateApprove(this)">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                        <input type="hidden" name="type" value="{{ $item['type'] }}">
                                        <input type="hidden" name="action" value="approve">

                                        <div class="approve-form-wrapper">
                                            <div class="form-title">
                                                <i class="fas fa-check-circle"></i> تایید محتوا
                                            </div>

                                            <div class="form-group">
                                                <label>نمره <span class="sub">(در صورت تایید)</span></label>
                                                <select name="score">
                                                    <option value="">انتخاب کنید...</option>
                                                    <option value="1">🌟 عالی</option>
                                                    <option value="2">✅ خوب</option>
                                                    <option value="3">📊 متوسط</option>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn-judge btn-success">
                                                <i class="fas fa-check"></i> تایید
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-success mt-3 text-center" style="margin-top:16px;padding:12px 16px;background:#e8f5e9;border-radius:10px;color:#2e7d32;text-align:center;">
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

@endsection
@section('js')
<script>
    function validateReject(form) {
        const comment = form.querySelector('textarea[name="comment"]');
        const issues = form.querySelectorAll('input[name="negaresh"], input[name="gozine"], input[name="dark"]');
        const hasIssue = Array.from(issues).some(cb => cb.checked);
        
        if (!comment.value.trim()) {
            alert('برای رد کردن، لطفاً توضیح دهید که مشکل چیست.');
            comment.focus();
            return false;
        }
        if (!hasIssue) {
            alert('برای رد کردن، لطفاً حداقل یک مشکل را انتخاب کنید.');
            return false;
        }
        return confirm('آیا مطمئن هستید که می‌خواهید این محتوا را رد کنید؟');
    }

    function validateApprove(form) {
        const score = form.querySelector('select[name="score"]');
        if (!score.value) {
            alert('لطفاً نمره را انتخاب کنید.');
            return false;
        }
        return true;
    }
</script>
@endsection