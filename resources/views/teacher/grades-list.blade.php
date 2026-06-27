@extends('layout.master')

@section('title')
ملیسان | نمرات دانشجویان
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-grades-list.css')}}">
<style>
    .grades-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .grades-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .grades-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .grades-title span {
        color: #1e6f9f;
    }

    .students-profile-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: #f0f4f9;
        color: #1a2332;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .students-profile-btn:hover {
        background: #e8edf3;
        transform: translateX(-4px);
        text-decoration: none;
        color: #1a2332;
    }

    .students-profile-btn i {
        font-size: 16px;
    }

    .table-wrapper {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        padding: 20px;
        overflow-x: auto;
    }

    .grades-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        min-width: 700px;
    }

    .grades-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #e8edf3;
    }

    .grades-table thead th {
        padding: 12px 16px;
        text-align: center;
        font-weight: 700;
        color: #1a2332;
        font-size: 13px;
        white-space: nowrap;
    }

    .grades-table tbody tr {
        border-bottom: 1px solid #f0f4f9;
        transition: background 0.2s;
    }

    .grades-table tbody tr:hover {
        background: #f8fafc;
    }

    .grades-table tbody td {
        padding: 10px 16px;
        text-align: center;
        vertical-align: middle;
        color: #333;
    }

    .name-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }

    .online-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .dot-online {
        background: #4CAF50;
        box-shadow: 0 0 8px rgba(76, 175, 80, 0.5);
    }

    .dot-offline {
        background: #9e9e9e;
    }

    .mostamar-badge {
        display: inline-block;
        background: linear-gradient(135deg, #1976d2, #42a5f5);
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        border-radius: 6px;
        padding: 4px 14px;
        min-width: 50px;
        text-align: center;
    }

    .exam-badge {
        display: inline-block;
        background: linear-gradient(135deg, #388e3c, #66bb6a);
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        border-radius: 6px;
        padding: 4px 14px;
        min-width: 50px;
        text-align: center;
    }

    .exam-badge.none {
        background: #e0e0e0;
        color: #999;
    }

    .grade-input {
        max-width: 80px;
        border: 2px solid #e8edf3;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 14px;
        text-align: center;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        outline: none;
    }

    .grade-input:focus {
        border-color: #1e6f9f;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.08);
    }

    .grade-input::-webkit-inner-spin-button {
        opacity: 0.5;
    }

    .action-bar {
        display: flex;
        justify-content: flex-end;
        padding-top: 20px;
        margin-top: 16px;
        border-top: 1px solid #f0f4f9;
    }

    .save-grades-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 32px;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
    }

    .save-grades-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(30, 111, 159, 0.4);
    }

    .save-grades-btn i {
        font-size: 18px;
    }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: #6b7a8f;
    }

    @media (max-width: 768px) {
        .grades-container {
            padding: 10px;
        }
        .grades-header {
            flex-direction: column;
            align-items: stretch;
        }
        .grades-title {
            text-align: center;
        }
        .students-profile-btn {
            justify-content: center;
        }
        .table-wrapper {
            padding: 10px;
        }
        .grades-table {
            font-size: 12px;
            min-width: 600px;
        }
        .grades-table thead th,
        .grades-table tbody td {
            padding: 6px 10px;
        }
        .grade-input {
            max-width: 60px;
            padding: 4px 6px;
            font-size: 12px;
        }
        .mostamar-badge,
        .exam-badge {
            font-size: 12px;
            padding: 2px 10px;
            min-width: 40px;
        }
        .save-grades-btn {
            width: 100%;
            justify-content: center;
        }
        .action-bar {
            justify-content: center;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="grades-container">
    <div class="grades-header">
        <h4 class="grades-title">نمرات دانشجویان : <span>{{ $course->name ?? 'عنوان درس' }}</span></h4>
        <a href="{{ route('studentsList', $course->id) }}" class="students-profile-btn">
            <i class="fas fa-arrow-left"></i>
            مشخصات دانشجویان
        </a>
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