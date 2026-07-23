@extends('layout.master')

@section('title')
ملیسان | نمرات دانشجویان
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-grades-list.css')}}">
@endsection

@section('mohtava')
<div class="grades-container">
    <div class="grades-header">
        <h4 class="grades-title">نمرات دانشجویان : <span>{{ $course->name ?? 'عنوان درس' }}</span></h4>
        @include('layout.backbtn')
    </div>

    <div class="table-wrapper">
        <form action="{{ route('grades.save', $course->id) }}" method="POST">
            @csrf
            <table class="grades-table" id="gradesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام و نام خانوادگی</th>
                        <th>نمره ارزشیابی (از ۲۰)</th>
                        <th>میانگین نمره آزمون (از ۲۰)</th>
                        @if(isset($setting) && $setting->final_nomre > 0)
                            <th>نمره پایان ترم (از ۲۰)</th>
                        @endif
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
                            <td>
                                <span class="mostamar-badge">{{ number_format($user->mostamar_nomre ?? 0, 2) }}</span>
                            </td>
                            <td>
                                @if(isset($user->exam_avg) && $user->exam_avg !== null)
                                    <span class="exam-badge">{{ number_format($user->exam_avg, 2) }}</span>
                                @else
                                    <span class="exam-badge none">—</span>
                                @endif
                            </td>
                            @if(isset($setting) && $setting->final_nomre > 0)
                                <td>
                                    <input type="hidden" name="ind[]" value="{{ $user->id }}">
                                    <input
                                        type="number"
                                        name="final[{{ $user->id }}]"
                                        class="grade-input"
                                        step="0.01"
                                        min="0"
                                        max="20"
                                        placeholder="—"
                                        value="{{ $user->final ?? '' }}"
                                    >
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="20" style="text-align:center;padding:40px;color:#6b7a8f;">
                                <i class="fas fa-users" style="font-size:32px;display:block;margin-bottom:12px;color:#d0d7e2;"></i>
                                هیچ دانشجویی در این درس ثبت‌نام نکرده است
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(isset($setting) && $setting->final_nomre > 0 && $users->count() > 0)
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
    // ============================================
    // DataTable
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
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
    });

    // ============================================
    // اعتبارسنجی ورودی نمرات (0 تا 20)
    // ============================================
    document.querySelectorAll('.grade-input').forEach(function(input) {
        input.addEventListener('change', function() {
            var val = parseFloat(this.value);
            if (val < 0) {
                this.value = 0;
                showToast('نمره نمی‌تواند منفی باشد', 'warning');
            } else if (val > 20) {
                this.value = 20;
                showToast('نمره نمی‌تواند بیشتر از ۲۰ باشد', 'warning');
            }
        });

        input.addEventListener('blur', function() {
            if (this.value === '' || this.value === null || this.value === '-') {
                return;
            }
            var val = parseFloat(this.value);
            if (isNaN(val)) {
                this.value = '';
            }
        });
    });

    // ============================================
    // TOAST NOTIFICATION
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
        
        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.4s';
            setTimeout(function() {
                toast.remove();
            }, 400);
        }, 3500);
    }
</script>
@endsection