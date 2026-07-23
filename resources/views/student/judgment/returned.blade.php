@extends('layout.master')

@section('title')
ملیسان | برگشت‌خورده‌ها
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/returned.css') }}">
@endsection

@section('mohtava')
<div class="returned-container">
    <div class="returned-header">
        <div>
            <h2>
                <i class="fas fa-undo"></i>
                محتوای برگشت‌خورده
            </h2>
            <div class="subtitle" style="font-size:14px;color:#6b7a8f;margin-top:4px;">
                محتوایی که توسط داوران رد شده و نیاز به اصلاح دارد
            </div>
        </div>
        <a href="{{ route('student.judgment.index') }}" class="btn-back">
            <i class="fas fa-arrow-right"></i>
            بازگشت به داوری
        </a>
    </div>

    @php
        $allReturned = collect();
        foreach ($returnedQuestions as $item) {
            $allReturned->push([
                'id' => $item->id,
                'type' => 'question',
                'type_label' => 'سوال',
                'title' => $item->question,
                'comment' => $item->comment,
                'data' => $item,
            ]);
        }
        foreach ($returnedDiscussions as $item) {
            $allReturned->push([
                'id' => $item->id,
                'type' => 'discussion',
                'type_label' => 'گزارش',
                'title' => $item->title ?? 'بدون عنوان',
                'comment' => $item->comment,
                'data' => $item,
            ]);
        }
        foreach ($returnedExercises as $item) {
            $allReturned->push([
                'id' => $item->id,
                'type' => 'exercise',
                'type_label' => 'تکلیف',
                'title' => $item->answer ?? 'بدون عنوان',
                'comment' => $item->comment,
                'data' => $item,
            ]);
        }
    @endphp

    @if($allReturned->count() > 0)
        @foreach($allReturned as $item)
            <div class="returned-card">
                <div class="card-header">
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <span class="badge-type {{ $item['type'] }}">
                            <i class="fas {{ $item['type'] == 'question' ? 'fa-question-circle' : ($item['type'] == 'discussion' ? 'fa-file-alt' : 'fa-tasks') }}"></i>
                            {{ $item['type_label'] }}
                        </span>
                    </div>
                    <span style="font-size:13px;color:#6b7a8f;">
                        {{ \Hekmatinasser\Verta\Verta::instance($item['data']->created_at)->format('Y/m/d H:i') }}
                    </span>
                </div>

                <div style="font-size:15px;color:#1a2332;padding:8px 0;">
                    <strong>محتوا:</strong> {{ Str::limit($item['title'], 100) }}
                </div>

                @if($item['comment'])
                    <div class="comment-box">
                        <strong><i class="fas fa-comment"></i> دلیل برگشت:</strong>
                        {{ $item['comment'] }}
                    </div>
                @endif

                <form method="POST" action="{{ route('student.judgment.resubmit') }}" style="margin-top:12px;">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                    <input type="hidden" name="type" value="{{ $item['type'] }}">
                    
                    <div class="form-group" style="margin-bottom:12px;">
                        <label style="font-weight:600;font-size:14px;display:block;margin-bottom:6px;">
                            متن اصلاح شده <span style="color:#f44336;">*</span>
                        </label>
                        <textarea name="text" class="form-control" rows="4" required 
                            style="width:100%;padding:10px 14px;border:2px solid #e8edf3;border-radius:10px;font-size:14px;background:#fafbfc;font-family:inherit;"
                            placeholder="محتوای اصلاح شده را وارد کنید...">{{ $item['data']->question ?? $item['data']->text ?? $item['data']->answer ?? '' }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn-resubmit">
                        <i class="fas fa-paper-plane"></i>
                        ارسال مجدد
                    </button>
                </form>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <span class="empty-icon">
                <i class="fas fa-check-circle" style="color:#4caf50;"></i>
            </span>
            <h4>هیچ محتوای برگشت‌خورده‌ای وجود ندارد</h4>
            <p>تمامی محتوای شما تایید شده یا در انتظار داوری هستند.</p>
        </div>
    @endif
</div>
@endsection