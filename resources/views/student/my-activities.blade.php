@extends('layout.master')

@section('title')
    ملیسان | فعالیت‌های من
@endsection

@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/my-activities.css') }}">
@endsection

@section('mohtava')
    <div class="activities-container">
        {{-- HEADER --}}
        <div class="activities-header">
            <div>
                <h2>
                    <i class="fas fa-user-graduate"></i>
                    فعالیت‌های من
                </h2>
                <div class="subtitle">
                    <i class="fas fa-book-open" style="margin-left:6px;color:#1e6f9f;"></i>
                    {{ $course->name ?? 'درس' }}
                </div>
            </div>
            @include('layout.backbtn')
        </div>

        {{-- STATS --}}
        <div class="stats-row">
            <div class="stat-box total">
                <div class="number">
                    {{ $stats['total_questions'] + $stats['total_discussions'] + $stats['total_exercises'] }}
                </div>
                <div class="label">کل فعالیت‌ها</div>
            </div>
            <div class="stat-box pending">
                <div class="number">
                    {{ $stats['pending_questions'] + $stats['pending_discussions'] + $stats['pending_exercises'] }}
                </div>
                <div class="label">در انتظار داوری</div>
            </div>
            <div class="stat-box approved">
                <div class="number">
                    {{ $stats['approved_questions'] + $stats['approved_discussions'] + $stats['approved_exercises'] }}
                </div>
                <div class="label">تایید شده</div>
            </div>
            <div class="stat-box rejected">
                <div class="number">
                    {{ $stats['rejected_questions'] + $stats['rejected_discussions'] + $stats['rejected_exercises'] }}
                </div>
                <div class="label">رد شده</div>
            </div>
        </div>

        {{-- TABS --}}
        <div class="tabs">
            <button class="tab-btn active" data-tab="all">
                <i class="fas fa-list"></i> همه
                <span class="badge">{{ $questions->count() + $discussions->count() + $exercises->count() }}</span>
            </button>
            <button class="tab-btn" data-tab="questions">
                <i class="fas fa-question-circle"></i> سوالات
                <span class="badge">{{ $questions->count() }}</span>
            </button>
            <button class="tab-btn" data-tab="discussions">
                <i class="fas fa-file-alt"></i> گزارش‌ها
                <span class="badge">{{ $discussions->count() }}</span>
            </button>
            <button class="tab-btn" data-tab="exercises">
                <i class="fas fa-tasks"></i> تکالیف
                <span class="badge">{{ $exercises->count() }}</span>
            </button>
        </div>

        {{-- ===== ALL ITEMS ===== --}}
        <div class="items-container active" id="tab-all">
            @php
                $allItems = collect();

                foreach ($questions as $q) {
                    $allItems->push([
                        'type' => 'question',
                        'type_label' => 'سوال',
                        'type_icon' => 'fa-question-circle',
                        'id' => $q->id,
                        'title' => $q->question,
                        'session_name' => $q->session->name ?? 'نامشخص',
                        'created_at' => $q->created_at,
                        'display_status' => $q->display_status,
                        'status_label' => $q->status_label,
                        'scores_count' => $q->scores_count ?? 0,
                        'approved_count' => $q->approved_count ?? 0,
                        'is_returned' => $q->status === 0,
                    ]);
                }

                foreach ($discussions as $d) {
                    $allItems->push([
                        'type' => 'discussion',
                        'type_label' => 'گزارش',
                        'type_icon' => 'fa-file-alt',
                        'id' => $d->id,
                        'title' => $d->session->name ?? 'بدون عنوان',
                        'session_name' => $d->session->name ?? 'نامشخص',
                        'created_at' => $d->created_at,
                        'display_status' => $d->display_status,
                        'status_label' => $d->status_label,
                        'scores_count' => $d->scores_count ?? 0,
                        'approved_count' => $d->approved_count ?? 0,
                        'is_returned' => $d->status === 0,
                    ]);
                }

                foreach ($exercises as $e) {
                    $allItems->push([
                        'type' => 'exercise',
                        'type_label' => 'تکلیف',
                        'type_icon' => 'fa-tasks',
                        'id' => $e->id,
                        'title' => $e->exercise->text ?? 'بدون عنوان',
                        'session_name' => $e->exercise->session->name ?? 'نامشخص',
                        'created_at' => $e->created_at,
                        'display_status' => $e->display_status,
                        'status_label' => $e->status_label,
                        'scores_count' => $e->scores_count ?? 0,
                        'approved_count' => $e->approved_count ?? 0,
                        'is_returned' => $e->status === 'returned',
                    ]);
                }

                $allItems = $allItems->sortByDesc('created_at');
            @endphp

            @if($allItems->count() > 0)
                @foreach($allItems as $item)
                    <div class="item-card">
                        <div class="item-header">
                            <div style="flex:1;min-width:150px;">
                                <div class="item-title">
                                    <span class="badge-type"
                                        style="display:inline-flex;align-items:center;gap:4px;padding:2px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#e3f2fd;color:#1e6f9f;margin-left:8px;">
                                        <i class="fas {{ $item['type_icon'] }}"></i>
                                        {{ $item['type_label'] }}
                                    </span>
                                    {!! Str::limit($item['title'], 20) !!}
                                </div>
                                <div class="item-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-video"></i>
                                        {{ $item['session_name'] }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ \Hekmatinasser\Verta\Verta::instance($item['created_at'])->format('Y/m/d H:i') }}
                                    </span>
                                    @if($item['scores_count'] > 0)
                                        <span class="meta-item">
                                            <i class="fas fa-star" style="color:#ffd700;"></i>
                                            {{ $item['approved_count'] }}/{{ $item['scores_count'] }} داوری
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                <span class="status-badge {{ $item['display_status'] }}">
                                    @if($item['display_status'] == 'pending')
                                        <i class="fas fa-clock"></i>
                                    @elseif($item['display_status'] == 'approved')
                                        <i class="fas fa-check-circle"></i>
                                    @elseif($item['display_status'] == 'rejected')
                                        <i class="fas fa-times-circle"></i>
                                    @elseif($item['display_status'] == 'returned')
                                        <i class="fas fa-undo"></i>
                                    @endif
                                    {{ $item['status_label'] }}
                                </span>
                                <div class="item-actions">
                                    {{-- برای سوالات --}}
                                    @if($item['type'] == 'question')
                                        <a href="{{ route('student.question.view', $item['id']) }}">
                                            مشاهده
                                        </a>
                                    @endif

                                    {{-- برای گزارش‌ها --}}
                                    @if($item['type'] == 'discussion')
                                        <a href="{{ route('student.discussion.view', $item['id']) }}">
                                            مشاهده
                                        </a>
                                    @endif

                                    {{-- برای تکالیف --}}
                                    @if($item['type'] == 'exercise')
                                        <a href="{{ route('student.exercise.view', $item['id']) }}">
                                            مشاهده
                                        </a>
                                    @endif
                                    @if($item['is_returned'])
                                        <a href="{{ route('student.judgment.returned') }}" class="btn-edit">
                                            <i class="fas fa-edit"></i> اصلاح
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <span class="empty-icon"><i class="fas fa-inbox"></i></span>
                    <h4>هیچ فعالیتی ثبت نشده است</h4>
                    <p>شما هنوز هیچ سوال، گزارش یا تکلیفی در این درس ارسال نکرده‌اید.</p>
                </div>
            @endif
        </div>

        {{-- ===== QUESTIONS TAB ===== --}}
        <div class="items-container" id="tab-questions">
            @if($questions->count() > 0)
                @foreach($questions as $question)
                    <div class="item-card">
                        <div class="item-header">
                            <div style="flex:1;min-width:150px;">
                                <div class="item-title">
                                    {{ Str::limit($question->question, 80) }}
                                </div>
                                <div class="item-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-video"></i>
                                        {{ $question->session->name ?? 'نامشخص' }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ \Hekmatinasser\Verta\Verta::instance($question->created_at)->format('Y/m/d H:i') }}
                                    </span>
                                    @if($question->scores_count > 0)
                                        <span class="meta-item">
                                            <i class="fas fa-star" style="color:#ffd700;"></i>
                                            {{ $question->approved_count }}/{{ $question->scores_count }} داوری
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                <span class="status-badge {{ $question->display_status }}">
                                    @if($question->display_status == 'pending')
                                        <i class="fas fa-clock"></i>
                                    @elseif($question->display_status == 'approved')
                                        <i class="fas fa-check-circle"></i>
                                    @elseif($question->display_status == 'rejected')
                                        <i class="fas fa-times-circle"></i>
                                    @elseif($question->display_status == 'returned')
                                        <i class="fas fa-undo"></i>
                                    @endif
                                    {{ $question->status_label }}
                                </span>
                                @if($question->status === 0)
                                    <a href="{{ route('student.judgment.returned') }}" class="btn-edit">
                                        <i class="fas fa-edit"></i> اصلاح
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <span class="empty-icon"><i class="fas fa-question-circle"></i></span>
                    <h4>هیچ سوالی ثبت نشده است</h4>
                    <p>شما هنوز هیچ سوالی در این درس ارسال نکرده‌اید.</p>
                </div>
            @endif
        </div>

        {{-- ===== DISCUSSIONS TAB ===== --}}
        <div class="items-container" id="tab-discussions">
            @if($discussions->count() > 0)
                @foreach($discussions as $discussion)
                    <div class="item-card">
                        <div class="item-header">
                            <div style="flex:1;min-width:150px;">
                                <div class="item-title">
                                    {{ Str::limit($discussion->title ?? 'بدون عنوان', 80) }}
                                </div>
                                <div class="item-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-video"></i>
                                        {{ $discussion->session->name ?? 'نامشخص' }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ \Hekmatinasser\Verta\Verta::instance($discussion->created_at)->format('Y/m/d H:i') }}
                                    </span>
                                    @if($discussion->scores_count > 0)
                                        <span class="meta-item">
                                            <i class="fas fa-star" style="color:#ffd700;"></i>
                                            {{ $discussion->approved_count }}/{{ $discussion->scores_count }} داوری
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                <span class="status-badge {{ $discussion->display_status }}">
                                    @if($discussion->display_status == 'pending')
                                        <i class="fas fa-clock"></i>
                                    @elseif($discussion->display_status == 'approved')
                                        <i class="fas fa-check-circle"></i>
                                    @elseif($discussion->display_status == 'rejected')
                                        <i class="fas fa-times-circle"></i>
                                    @elseif($discussion->display_status == 'returned')
                                        <i class="fas fa-undo"></i>
                                    @endif
                                    {{ $discussion->status_label }}
                                </span>
                                @if($discussion->status === 0)
                                    <a href="{{ route('student.judgment.returned') }}" class="btn-edit">
                                        <i class="fas fa-edit"></i> اصلاح
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <span class="empty-icon"><i class="fas fa-file-alt"></i></span>
                    <h4>هیچ گزارشی ثبت نشده است</h4>
                    <p>شما هنوز هیچ گزارشی در این درس ارسال نکرده‌اید.</p>
                </div>
            @endif
        </div>

        {{-- ===== EXERCISES TAB ===== --}}
        <div class="items-container" id="tab-exercises">
            @if($exercises->count() > 0)
                @foreach($exercises as $exercise)
                    <div class="item-card">
                        <div class="item-header">
                            <div style="flex:1;min-width:150px;">
                                <div class="item-title">
                                    {!! Str::limit($exercise->exercise->text ?? 'بدون عنوان', 80) !!}
                                </div>
                                <div class="item-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-video"></i>
                                        {{ $exercise->exercise->session->name ?? 'نامشخص' }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ \Hekmatinasser\Verta\Verta::instance($exercise->created_at)->format('Y/m/d H:i') }}
                                    </span>
                                    @if($exercise->scores_count > 0)
                                        <span class="meta-item">
                                            <i class="fas fa-star" style="color:#ffd700;"></i>
                                            {{ $exercise->approved_count }}/{{ $exercise->scores_count }} داوری
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                <span class="status-badge {{ $exercise->display_status }}">
                                    @if($exercise->display_status == 'pending')
                                        <i class="fas fa-clock"></i>
                                    @elseif($exercise->display_status == 'approved')
                                        <i class="fas fa-check-circle"></i>
                                    @elseif($exercise->display_status == 'rejected')
                                        <i class="fas fa-times-circle"></i>
                                    @elseif($exercise->display_status == 'returned')
                                        <i class="fas fa-undo"></i>
                                    @endif
                                    {{ $exercise->status_label }}
                                </span>
                                @if($exercise->status === 'returned')
                                    <a href="{{ route('student.judgment.returned') }}" class="btn-edit">
                                        <i class="fas fa-edit"></i> اصلاح
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <span class="empty-icon"><i class="fas fa-tasks"></i></span>
                    <h4>هیچ تکلیفی ثبت نشده است</h4>
                    <p>شما هنوز هیچ تکلیفی در این درس ارسال نکرده‌اید.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // ===== TABS =====
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                document.querySelectorAll('.items-container').forEach(c => c.classList.remove('active'));

                const tabId = this.dataset.tab;
                document.getElementById('tab-' + tabId).classList.add('active');
            });
        });
    </script>
@endsection