@extends('layout.master')

@section('title')
ملیسان | برگشت‌خورده‌ها
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .returned-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .returned-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .returned-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .returned-header h2 i {
        color: #ff9800;
        margin-left: 10px;
    }

    .returned-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        padding: 24px 28px;
        margin-bottom: 20px;
        border-right: 4px solid #ff9800;
    }

    .returned-card .card-header {
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

    .returned-card .comment-box {
        background: #fff3cd;
        padding: 12px 16px;
        border-radius: 10px;
        border-right: 3px solid #ff9800;
        margin: 10px 0 16px;
        font-size: 14px;
        color: #856404;
    }

    .returned-card .comment-box strong {
        display: block;
        margin-bottom: 4px;
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

    .btn-resubmit {
        padding: 8px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #ff9800, #e65100);
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-resubmit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255,152,0,0.3);
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

    @media (max-width: 768px) {
        .returned-card {
            padding: 16px;
        }
        .returned-card .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
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