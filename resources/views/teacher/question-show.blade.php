@extends('layout.master')

@section('title')
ملیسان | جزئیات سوال
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/question-show.css') }}">
@endsection

@section('mohtava')
<div class="question-container">
    <div class="question-card">
        {{-- HEADER --}}
        <div class="question-header">
            <h3>
                <i class="fas fa-question-circle"></i>
                جزئیات سوال
            </h3>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                @if($averageScore)
                    <span class="average-score">
                        <i class="fas fa-star" style="color:#ffd700;"></i>
                        میانگین نمرات: <span class="score-num">{{ $averageScore }}</span>
                    </span>
                @endif
                <span class="badge-status {{ $question->status === null ? 'pending' : ($question->status == 0 ? 'returned' : ($question->status == 1 ? 'excellent' : ($question->status == 2 ? 'good' : ($question->status == 3 ? 'medium' : 'weak')))) }}">
                    @if($question->status === null)
                        <i class="fas fa-clock"></i> در انتظار داوری
                    @elseif($question->status == 0)
                        <i class="fas fa-undo"></i> برگشت خورده
                    @elseif($question->status == 1)
                        <i class="fas fa-star"></i> عالی
                    @elseif($question->status == 2)
                        <i class="fas fa-check-circle"></i> خوب
                    @elseif($question->status == 3)
                        <i class="fas fa-minus-circle"></i> متوسط
                    @else
                        <i class="fas fa-times-circle"></i> بد
                    @endif
                </span>
            </div>
        </div>

        {{-- BODY --}}
        <div class="question-body">
            {{-- اطلاعات اصلی --}}
            <div class="info-row">
                <span class="info-label">سوال</span>
                <span class="info-value">{{ $question->question }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">طراح</span>
                <span class="info-value">
                    <i class="fas fa-user-graduate" style="color:#1e6f9f;"></i>
                    {{ $designerName }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">درس</span>
                <span class="info-value">
                    <i class="fas fa-book-open" style="color:#1e6f9f;"></i>
                    {{ $question->session->course->name ?? 'نامشخص' }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">جلسه</span>
                <span class="info-value">
                    <i class="fas fa-video" style="color:#1e6f9f;"></i>
                    {{ $question->session->name ?? 'نامشخص' }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">تاریخ ثبت</span>
                <span class="info-value">
                    <i class="fas fa-calendar-alt" style="color:#1e6f9f;"></i>
                    {{ \Hekmatinasser\Verta\Verta::instance($question->created_at)->format('Y/m/d H:i') }}
                </span>
            </div>

            {{-- گزینه‌ها --}}
            <div class="info-row" style="flex-direction:column;gap:8px;align-items:stretch;">
                <span class="info-label">گزینه‌ها</span>
                <div class="options-grid">
                    @php
                        $options = [
                            1 => ['label' => 'الف', 'value' => $question->answer1],
                            2 => ['label' => 'ب', 'value' => $question->answer2],
                            3 => ['label' => 'ج', 'value' => $question->answer3],
                            4 => ['label' => 'د', 'value' => $question->answer4],
                        ];
                    @endphp
                    @foreach($options as $key => $option)
                        <div class="option-item {{ $key == $question->answer ? 'correct' : '' }}">
                            <span class="opt-label">{{ $option['label'] }}</span>
                            <span>{{ $option['value'] }}</span>
                            @if($key == $question->answer)
                                <span class="correct-icon"><i class="fas fa-check-circle"></i></span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- داوری‌ها --}}
            <div class="info-row" style="flex-direction:column;gap:8px;align-items:stretch;padding-top:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                    <span class="info-label" style="min-width:auto;">داوری‌ها</span>
                    <span style="font-size:13px;color:#6b7a8f;">
                        <i class="fas fa-users"></i>
                        {{ $scores->count() }} داوری
                        @php
                            $approvedCount = $scores->where('status', 'approved')->count();
                        @endphp
                        @if($approvedCount > 0)
                            ({{ $approvedCount }} تایید شده)
                        @endif
                    </span>
                </div>

                @if($scores->count() > 0)
                    @foreach($scores as $score)
                        <div class="score-item">
                            <span class="score-user">
                                <i class="fas fa-user" style="color:#1e6f9f;"></i>
                                {{ $score->user->name ?? 'ناشناس' }} {{ $score->user->family ?? '' }}
                            </span>

                            @if($score->status === 'approved')
                                <span class="score-value 
                                    @if($score->score == 1) excellent
                                    @elseif($score->score == 2) good
                                    @elseif($score->score == 3) medium
                                    @endif">
                                    @if($score->score == 1) عالی
                                    @elseif($score->score == 2) خوب
                                    @elseif($score->score == 3) متوسط
                                    @endif
                                </span>
                            @endif

                            @if($score->negaresh == 1 || $score->gozine == 1 || $score->dark == 1)
                                <span style="font-size:12px;color:#f44336;">
                                    @if($score->negaresh == 1) ❌ نگارشی @endif
                                    @if($score->gozine == 1) ❌ گزینه‌ها @endif
                                    @if($score->dark == 1) ❌ گویایی @endif
                                </span>
                            @endif

                            <span class="score-status {{ $score->status }}">
                                @if($score->status === 'approved')
                                    <i class="fas fa-check-circle"></i> تایید
                                @elseif($score->status === 'rejected')
                                    <i class="fas fa-times-circle"></i> رد
                                @elseif($score->status === 'returned')
                                    <i class="fas fa-undo"></i> برگشت
                                @else
                                    <i class="fas fa-clock"></i> در انتظار
                                @endif
                            </span>

                            @if($score->comment)
                                <span class="score-comment">
                                    <i class="fas fa-comment" style="color:#6b7a8f;"></i>
                                    {{ $score->comment }}
                                </span>
                            @endif

                            <span style="font-size:11px;color:#6b7a8f;white-space:nowrap;">
                                {{ \Hekmatinasser\Verta\Verta::instance($score->created_at)->format('Y/m/d H:i') }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>هیچ داوری‌ای برای این سوال ثبت نشده است.</p>
                    </div>
                @endif
            </div>

            {{-- تغییر وضعیت --}}
            <div class="status-form">
                <form id="statusForm" method="POST" action="{{ route('question.updateStatus', $question->id) }}">
                    @csrf
                    @method('PUT')
                    <select name="status" id="statusSelect">
                        <option value="">در انتظار داوری</option>
                        <option value="0" {{ $question->status === 0 ? 'selected' : '' }}>برگشت خورده</option>
                        <option value="1" {{ $question->status == 1 ? 'selected' : '' }}>عالی</option>
                        <option value="2" {{ $question->status == 2 ? 'selected' : '' }}>خوب</option>
                        <option value="3" {{ $question->status == 3 ? 'selected' : '' }}>متوسط</option>
                        <option value="4" {{ $question->status == 4 ? 'selected' : '' }}>بد</option>
                    </select>
                    <button type="submit" class="btn-status">
                        <i class="fas fa-save"></i>
                        تغییر وضعیت
                    </button>
                </form>
            </div>

            {{-- دکمه‌های اقدام --}}
            <div class="action-buttons">
                <a href="{{ url()->previous() }}" class="btn-action btn-action-back">
                    <i class="fas fa-arrow-right"></i>
                    بازگشت
                </a>

                @if(Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin'))
                    <form method="POST" action="{{ route('question.destroy', $question->id) }}" 
                          onsubmit="return confirm('آیا مطمئن هستید که می‌خواهید این سوال را حذف کنید؟')" 
                          style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-action-danger">
                            <i class="fas fa-trash-alt"></i>
                            حذف سوال
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const url = form.action;
        const btn = form.querySelector('.btn-status');
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال تغییر...';
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // به‌روزرسانی بج وضعیت در هدر
                location.reload();
            } else {
                alert('خطا در تغییر وضعیت');
            }
        })
        .catch(error => {
            alert('خطا در ارتباط با سرور');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> تغییر وضعیت';
        });
    });
</script>
@endsection