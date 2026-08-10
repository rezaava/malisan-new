@extends('layout.master')

@section('title')
    ملیسان | نمرات دانشجویان
@endsection

@section('head')
    <link rel="stylesheet" href="{{asset('css/style-grades-list.css')}}">
    <link rel="stylesheet" href="{{asset('css/badge.css')}}">
    <link rel="stylesheet" href="{{asset('css/style-course.css')}}">
@endsection

@section('mohtava')
    <div class="grades-container">
        <div class="grades-header">
            <div class="info-badge course-badge">
                <span class="badge-icon">
                    <i class="fas fa-book-open"></i>
                </span>
                <span class="badge-label">نمرات دانشجویان در درس:</span>
                <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
            </div>
            <div>
                @include('layout.backbtn')
                <a href="{{ route('courses.setting', $course->id) }}?open_section=barmbandi" class="action-btn settings-btn">
                    <i class="fas fa-cog"></i>
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <form action="{{ route('grades.save', $course->id) }}" method="POST">
                @csrf
                <table class="grades-table" id="gradesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام و نام خانوادگی</th>
                            <th>نمره ارزشیابی (از {{ $setting->mostamar_nomre ?? 20 }})</th>
                            @foreach($scoreSections as $key => $section)
                                <th>{{ $section['title'] }} (از {{ $setting->$key ?? 20 }})</th>
                            @endforeach
                            <th>تشویقی/ارفاق (خارج از ۲۰)</th>
                            <th>نمره نهایی (از ۲۰)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users ?? [] as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="name-cell">
                                        <span class="online-dot {{ $user->online == 1 ? 'dot-online' : 'dot-offline' }}"
                                            title="{{ $user->online == 1 ? 'آنلاین' : 'آفلاین' }}"></span>
                                        {{ $user->name ?? '' }} {{ $user->family ?? '' }}
                                    </div>
                                </td>
                                {{-- ستون ارزشیابی (ورودی با max = بارم واقعی) --}}
                                <td>
                                    <input type="number"
                                           name="mostamar[{{ $user->id }}]"
                                           class="grade-input section-grade"
                                           data-user-id="{{ $user->id }}"
                                           step="0.01"
                                           min="0"
                                           max="{{ $setting->mostamar_nomre ?? 20 }}"
                                           placeholder="—"
                                           value="{{ $user->amali_scores[8] ?? $user->mostamar_nomre ?? '' }}">
                                </td>
                                @foreach($scoreSections as $key => $section)
                                    <td>
                                        @if($key == 'payan_term_nomre')
                                            <input type="number" name="final[{{ $user->id }}]" class="grade-input section-grade"
                                                data-user-id="{{ $user->id }}" step="0.01" min="0" max="{{ $setting->$key ?? 20 }}"
                                                placeholder="—" value="{{ $user->final ?? '' }}">
                                        @else
                                            <input type="number" name="scores[{{ $user->id }}][{{ $section['type'] }}]"
                                                class="grade-input section-grade" data-user-id="{{ $user->id }}" step="0.01" min="0"
                                                max="{{ $setting->$key ?? 20 }}" placeholder="—"
                                                value="{{ $user->amali_scores[$section['type']] ?? '' }}">
                                        @endif
                                    </td>
                                @endforeach
                                {{-- ستون تشویقی/ارفاق --}}
                                <td>
                                    <input type="number" name="extra[{{ $user->id }}]" class="grade-input section-grade"
                                        data-user-id="{{ $user->id }}" step="0.01" min="0" placeholder="—"
                                        value="{{ $user->amali_scores[7] ?? '' }}">
                                </td>
                                {{-- ستون نمره نهایی --}}
                                <td>
                                    <span class="final-grade" data-user-id="{{ $user->id }}">0.00</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 5 + count($scoreSections) }}"
                                    style="text-align:center;padding:40px;color:#6b7a8f;">
                                    <i class="fas fa-users"
                                        style="font-size:32px;display:block;margin-bottom:12px;color:#d0d7e2;"></i>
                                    هیچ دانشجویی در این درس ثبت‌نام نکرده است
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if(count($scoreSections) > 0 && $users->count() > 0)
                    <div class="action-bar">
                        <button type="submit" class="save-grades-btn">
                            <i class="fas fa-save"></i>
                            ثبت نمرات
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ============================================
            // DataTable
            // ============================================
            const table = document.getElementById('gradesTable');
            if (table && typeof $ !== 'undefined' && $.fn.DataTable) {
                $('#gradesTable').DataTable({
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "همه"]],
                    language: {
                        search: "جستجو:",
                        lengthMenu: "نمایش _MENU_ رکورد در هر صفحه",
                        info: "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
                        infoEmpty: "هیچ رکوردی یافت نشد",
                        zeroRecords: "موردی یافت نشد",
                        paginate: {
                            first: "ابتدا",
                            last: "انتها",
                            next: "بعدی",
                            previous: "قبلی"
                        }
                    },
                    order: [[0, "asc"]]
                });
            }

            // ============================================
            // محاسبه نمره نهایی (جمع تمام ورودی‌های section-grade)
            // ============================================
            function updateFinalGrade(userId) {
                const inputs = document.querySelectorAll(`.section-grade[data-user-id="${userId}"]`);
                let total = 0;
                inputs.forEach(input => {
                    let val = parseFloat(input.value);
                    if (!isNaN(val) && val >= 0) {
                        total += val;
                    }
                });
                const finalSpan = document.querySelector(`.final-grade[data-user-id="${userId}"]`);
                if (finalSpan) {
                    finalSpan.textContent = total.toFixed(2);
                }
            }

            // اتصال رویداد به همه ورودی‌ها و محاسبه اولیه
            document.querySelectorAll('.section-grade').forEach(input => {
                input.addEventListener('input', function () {
                    const userId = this.dataset.userId;
                    updateFinalGrade(userId);
                });
                const userId = input.dataset.userId;
                updateFinalGrade(userId);
            });

            // ============================================
            // اعتبارسنجی ورودی‌ها (حداقل 0 و حداکثر max)
            // ============================================
            document.querySelectorAll('.grade-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    var val = parseFloat(this.value);
                    var maxVal = parseFloat(this.getAttribute('max'));
                    if (maxVal !== null && !isNaN(maxVal)) {
                        if (val < 0) {
                            this.value = 0;
                            showToast('نمره نمی‌تواند منفی باشد', 'warning');
                        } else if (val > maxVal) {
                            this.value = maxVal;
                            showToast('نمره نمی‌تواند بیشتر از ' + maxVal + ' باشد', 'warning');
                        }
                    } else {
                        if (val < 0) {
                            this.value = 0;
                            showToast('نمره نمی‌تواند منفی باشد', 'warning');
                        }
                    }
                    const userId = this.dataset.userId;
                    if (userId) updateFinalGrade(userId);
                });

                input.addEventListener('blur', function () {
                    if (this.value === '' || this.value === null || this.value === '-') {
                        return;
                    }
                    var val = parseFloat(this.value);
                    if (isNaN(val)) {
                        this.value = '';
                    }
                });
            });
        });

        // ============================================
        // Toast Notifications
        // ============================================
        function showToast(message, type = 'info') {
            var existingToast = document.querySelector('.toast-notification');
            if (existingToast) {
                existingToast.remove();
            }

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

            setTimeout(function () {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.4s';
                setTimeout(function () {
                    toast.remove();
                }, 400);
            }, 3500);
        }
    </script>
@endsection