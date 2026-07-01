@extends('layout.master')

@section('title')
ملیسان | گزارش‌های ایراد سوال
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== CONTAINER ===== */
    .reports-container {
        max-width: 1100px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .reports-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .reports-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .reports-header h2 i {
        color: #f44336;
        margin-left: 10px;
    }

    .reports-header .subtitle {
        font-size: 14px;
        color: #6b7a8f;
        margin-top: 4px;
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

    /* ===== STATS ===== */
    .stats-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .stat-box {
        flex: 1;
        min-width: 100px;
        background: #fff;
        border-radius: 16px;
        padding: 14px 18px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        text-align: center;
        border-right: 4px solid #1e6f9f;
    }

    .stat-box .number {
        font-size: 24px;
        font-weight: 800;
        color: #1a2332;
    }

    .stat-box .label {
        font-size: 12px;
        color: #6b7a8f;
    }

    .stat-box.pending { border-right-color: #ff9800; }
    .stat-box.reviewed { border-right-color: #2196f3; }
    .stat-box.resolved { border-right-color: #4caf50; }
    .stat-box.rejected { border-right-color: #f44336; }

    /* ===== REPORT CARDS ===== */
    .report-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.04);
        padding: 20px 24px;
        margin-bottom: 16px;
        border-right: 4px solid #e8edf3;
        transition: all 0.3s ease;
    }

    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0,0,0,0.08);
    }

    .report-card.pending { border-right-color: #ff9800; }
    .report-card.reviewed { border-right-color: #2196f3; }
    .report-card.resolved { border-right-color: #4caf50; }
    .report-card.rejected { border-right-color: #f44336; }

    .report-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 10px;
    }

    .report-card .card-header .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .report-card .card-header .user-info .avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e3f2fd;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1e6f9f;
        font-weight: 700;
        font-size: 14px;
    }

    .report-card .card-header .user-info .name {
        font-weight: 600;
        color: #1a2332;
    }

    .report-card .card-header .user-info .date {
        font-size: 12px;
        color: #6b7a8f;
    }

    .report-card .card-header .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .report-card .card-header .action-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .status-badge.pending { background: #fff3cd; color: #e65100; }
    .status-badge.reviewed { background: #e3f2fd; color: #1e6f9f; }
    .status-badge.resolved { background: #e8f5e9; color: #2e7d32; }
    .status-badge.rejected { background: #ffebee; color: #c62828; }

    .btn-edit-question {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff3e0;
        color: #e65100;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-edit-question:hover {
        background: #ff9800;
        color: #fff;
    }

    /* ===== QUESTION BOX ===== */
    .question-box {
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 10px;
        margin: 10px 0 12px;
        border-right: 3px solid #1e6f9f;
    }

    .question-box .q-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7a8f;
    }

    .question-box .q-text {
        font-size: 14px;
        color: #1a2332;
        margin-top: 4px;
    }

    .question-box .q-text.has-margin {
        margin-bottom: 10px;
    }

    /* ===== OPTIONS GRID ===== */
    .options-grid-inline {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 6px;
    }

    @media (max-width: 600px) {
        .options-grid-inline {
            grid-template-columns: 1fr;
        }
    }

    .option-item-inline {
        padding: 6px 12px;
        border-radius: 8px;
        background: #fafbfc;
        border: 2px solid #eef2f7;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .option-item-inline.correct {
        border-color: #4caf50;
    }

    .option-item-inline .opt-label {
        font-weight: 700;
        color: #6b7a8f;
        min-width: 20px;
    }

    .option-item-inline .opt-text {
        color: #1a2332;
    }

    .option-item-inline .opt-icon {
        color: #4caf50;
        margin-right: auto;
    }

    /* ===== DESCRIPTION BOX ===== */
    .description-box {
        background: #fff3cd;
        padding: 12px 16px;
        border-radius: 10px;
        border-right: 3px solid #ff9800;
        margin: 8px 0 12px;
    }

    .description-box .d-label {
        font-size: 12px;
        font-weight: 600;
        color: #856404;
    }

    .description-box .d-text {
        font-size: 14px;
        color: #1a2332;
        margin-top: 4px;
    }

    /* ===== RESPONSE BOX ===== */
    .response-box {
        background: #e8f5e9;
        padding: 12px 16px;
        border-radius: 10px;
        border-right: 3px solid #4caf50;
        margin: 8px 0 12px;
    }

    .response-box .r-label {
        font-size: 12px;
        font-weight: 600;
        color: #2e7d32;
    }

    .response-box .r-text {
        font-size: 14px;
        color: #1a2332;
        margin-top: 4px;
    }

    /* ===== QUESTION STATUS ===== */
    .question-status {
        font-size: 13px;
        color: #6b7a8f;
        margin-bottom: 6px;
    }

    .question-status .status-value {
        font-weight: 600;
        color: #1a2332;
    }

    /* ===== SCORES SECTION ===== */
    .scores-section {
        margin: 10px 0 12px;
        padding: 12px 16px;
        background: #f5f7fa;
        border-radius: 10px;
        border-right: 3px solid #9c27b0;
    }

    .scores-section .scores-title {
        font-size: 12px;
        font-weight: 600;
        color: #6b7a8f;
        margin-bottom: 8px;
    }

    .score-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 0;
        border-bottom: 1px solid #eef2f7;
        font-size: 13px;
        flex-wrap: wrap;
    }

    .score-item:last-child {
        border-bottom: none;
    }

    .score-item .judge-name {
        font-weight: 600;
        color: #1a2332;
        min-width: 100px;
    }

    .score-item .score-value {
        padding: 2px 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
    }

    .score-value.excellent { background: #e8f5e9; color: #2e7d32; }
    .score-value.good { background: #e3f2fd; color: #1e6f9f; }
    .score-value.medium { background: #fff3e0; color: #e65100; }

    .score-item .score-status {
        font-size: 11px;
        padding: 2px 10px;
        border-radius: 12px;
        font-weight: 600;
    }

    .score-status.approved { background: #e8f5e9; color: #2e7d32; }
    .score-status.rejected { background: #ffebee; color: #c62828; }
    .score-status.returned { background: #fff3cd; color: #e65100; }
    .score-status.pending { background: #e3f2fd; color: #1e6f9f; }

    .score-item .score-comment {
        color: #6b7a8f;
        font-size: 12px;
        flex: 1;
    }

    .score-item .score-date {
        font-size: 11px;
        color: #6b7a8f;
        white-space: nowrap;
    }

    .no-scores {
        text-align: center;
        padding: 10px 0;
        color: #6b7a8f;
        font-size: 13px;
    }

    .average-score-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 14px;
        border-radius: 14px;
        font-size: 13px;
        font-weight: 700;
        background: #e3f2fd;
        color: #1e6f9f;
    }

    /* ===== CARD FOOTER ===== */
    .report-card .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 12px;
        border-top: 1px solid #f0f4f9;
        margin-top: 8px;
    }

    .report-card .card-footer .reviewer-info {
        font-size: 12px;
        color: #6b7a8f;
    }

    .report-card .card-footer .reviewer-info .reviewer-name {
        font-weight: 600;
        color: #1a2332;
    }

    /* ===== ACTION FORM ===== */
    .action-form {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
    }

    .action-form select {
        padding: 8px 14px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 13px;
        background: #fafbfc;
        font-family: inherit;
        min-width: 140px;
    }

    .action-form select:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30,111,159,0.1);
    }

    .action-form input[type="text"] {
        flex: 1;
        min-width: 150px;
        padding: 8px 14px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 13px;
        background: #fafbfc;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .action-form input[type="text"]:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30,111,159,0.1);
        background: #fff;
    }

    .btn-action {
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30,111,159,0.3);
    }

    .btn-action:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-action-success {
        background: linear-gradient(135deg, #4caf50, #388e3c);
    }

    .btn-action-success:hover {
        box-shadow: 0 4px 15px rgba(76,175,80,0.3);
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8fafc;
        border-radius: 16px;
    }

    .empty-state .empty-icon {
        font-size: 60px;
        color: #d0d7e2;
        display: block;
        margin-bottom: 16px;
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

    /* ===== EDIT QUESTION MODAL ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 99999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-container {
        background: #fff;
        border-radius: 24px;
        width: 100%;
        max-width: 650px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 24px 64px rgba(0, 0, 0, 0.2);
        animation: slideUp 0.3s ease;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 28px;
        border-bottom: 2px solid #f0f4f9;
        position: sticky;
        top: 0;
        background: #fff;
        border-radius: 24px 24px 0 0;
        z-index: 10;
    }

    .modal-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1a2332;
    }

    .modal-header h4 i {
        color: #1e6f9f;
        margin-left: 8px;
    }

    .modal-close {
        background: #f0f4f9;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4a5a6e;
        font-size: 18px;
    }

    .modal-close:hover {
        background: #ffebee;
        color: #c62828;
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 24px 28px 30px;
    }

    .modal-body .form-group {
        margin-bottom: 16px;
    }

    .modal-body .form-group label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        color: #1a2332;
        margin-bottom: 4px;
    }

    .modal-body .form-group label .required {
        color: #f44336;
        margin-right: 3px;
    }

    .modal-body .form-group .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        background: #fafbfc;
        font-family: inherit;
        transition: all 0.3s ease;
        color: #1a2332;
    }

    .modal-body .form-group .form-control:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
        background: #fff;
    }

    .modal-body .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    @media (max-width: 600px) {
        .modal-body .options-grid {
            grid-template-columns: 1fr;
        }
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 2px solid #f0f4f9;
    }

    .btn-modal {
        padding: 10px 28px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: inherit;
        text-decoration: none;
    }

    .btn-modal-primary {
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
    }

    .btn-modal-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 111, 159, 0.3);
        color: #fff;
    }

    .btn-modal-secondary {
        background: #f0f4f9;
        color: #4a5a6e;
    }

    .btn-modal-secondary:hover {
        background: #e3e8ef;
    }

    /* ===== TOAST ===== */
    .toast-message {
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 14px 24px;
        border-radius: 12px;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        z-index: 999999;
        animation: slideUp 0.4s ease;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        max-width: 400px;
    }

    .toast-message.success {
        background: linear-gradient(135deg, #4caf50, #388e3c);
    }

    .toast-message.error {
        background: linear-gradient(135deg, #f44336, #c62828);
    }

    .toast-message .toast-close {
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
        margin-left: 12px;
        font-size: 16px;
        opacity: 0.7;
        transition: all 0.2s;
    }

    .toast-message .toast-close:hover {
        opacity: 1;
    }

    /* ===== LOADING ===== */
    .text-center { text-align: center; }
    .py-5 { padding-top: 3rem; padding-bottom: 3rem; }
    .py-4 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
    .mt-2 { margin-top: 0.5rem; }
    .mt-4 { margin-top: 1.5rem; }
    .text-muted { color: #6b7a8f; }
    .text-danger { color: #f44336; }
    .text-primary { color: #1e6f9f; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .report-card {
            padding: 16px;
        }

        .report-card .card-header {
            flex-direction: column;
        }

        .report-card .card-header .action-buttons {
            width: 100%;
        }

        .stats-row {
            flex-direction: column;
        }

        .stat-box {
            min-width: auto;
        }

        .action-form {
            flex-direction: column;
        }

        .action-form input[type="text"] {
            width: 100%;
            min-width: auto;
        }

        .action-form select {
            width: 100%;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .reports-header {
            flex-direction: column;
            align-items: stretch;
        }

        .score-item {
            flex-wrap: wrap;
        }

        .modal-container {
            max-height: 95vh;
            border-radius: 20px;
        }

        .modal-header {
            padding: 16px 18px;
        }

        .modal-body {
            padding: 18px 16px 24px;
        }

        .modal-actions {
            flex-direction: column;
        }

        .btn-modal {
            width: 100%;
            justify-content: center;
        }

        .toast-message {
            bottom: 15px;
            right: 15px;
            left: 15px;
            max-width: none;
        }

        .options-grid-inline {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="reports-container">
    {{-- HEADER --}}
    <div class="reports-header">
        <div>
            <h2>
                <i class="fas fa-flag"></i>
                گزارش‌های ایراد سوال
            </h2>
            <div class="subtitle">
                <i class="fas fa-book-open" style="margin-left:6px;color:#1e6f9f;"></i>
                {{ $course->name ?? 'همه درس‌ها' }}
            </div>
        </div>
        <a href="{{ route('view.coure', $course->id ?? 0) }}" class="btn-back">
            <i class="fas fa-arrow-right"></i>
            بازگشت به درس
        </a>
    </div>

    {{-- STATS --}}
    <div class="stats-row">
        <div class="stat-box">
            <div class="number">{{ $stats['total'] ?? 0 }}</div>
            <div class="label">کل گزارش‌ها</div>
        </div>
        <div class="stat-box pending">
            <div class="number">{{ $stats['pending'] ?? 0 }}</div>
            <div class="label">در انتظار بررسی</div>
        </div>
        <div class="stat-box reviewed">
            <div class="number">{{ $stats['reviewed'] ?? 0 }}</div>
            <div class="label">بررسی شده</div>
        </div>
        <div class="stat-box resolved">
            <div class="number">{{ $stats['resolved'] ?? 0 }}</div>
            <div class="label">رفع شده</div>
        </div>
        <div class="stat-box rejected">
            <div class="number">{{ $stats['rejected'] ?? 0 }}</div>
            <div class="label">رد شده</div>
        </div>
    </div>

    {{-- REPORTS --}}
    @if($reports->count() > 0)
        @foreach($reports as $report)
            @php
                $statusLabels = [
                    'pending' => 'در انتظار بررسی',
                    'reviewed' => 'بررسی شده',
                    'resolved' => 'رفع شده',
                    'rejected' => 'رد شده',
                ];
                $statusClasses = [
                    'pending' => 'pending',
                    'reviewed' => 'reviewed',
                    'resolved' => 'resolved',
                    'rejected' => 'rejected',
                ];
                $designerName = $report->question->user->name ?? 'نامشخص';
                $designerFamily = $report->question->user->family ?? '';
                
                $scoreLabels = [
                    1 => 'عالی',
                    2 => 'خوب',
                    3 => 'متوسط',
                ];
                $scoreClasses = [
                    1 => 'excellent',
                    2 => 'good',
                    3 => 'medium',
                ];
            @endphp
            <div class="report-card {{ $report->status }}">
                <div class="card-header">
                    <div class="user-info">
                        <div class="avatar">{{ substr($report->user->name ?? '?', 0, 1) }}</div>
                        <div>
                            <div class="name">
                                {{ $report->user->name ?? 'نامشخص' }} {{ $report->user->family ?? '' }}
                                <span class="reporter-label">
                                    (گزارش‌دهنده)
                                </span>
                            </div>
                            <div class="date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ \Hekmatinasser\Verta\Verta::instance($report->created_at)->format('Y/m/d H:i') }}
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        @if($report->average_score)
                            <span class="average-score-badge">
                                <i class="fas fa-star"></i>
                                میانگین: {{ $report->average_score }}
                            </span>
                        @endif
                        <span class="status-badge {{ $report->status }}">
                            <i class="fas {{ $report->status == 'pending' ? 'fa-clock' : ($report->status == 'reviewed' ? 'fa-eye' : ($report->status == 'resolved' ? 'fa-check-circle' : 'fa-times-circle')) }}"></i>
                            {{ $statusLabels[$report->status] ?? 'نامشخص' }}
                        </span>
                        <button class="btn-edit-question" onclick="openEditModal({{ $report->question->id }})">
                            <i class="fas fa-edit"></i> ویرایش سوال
                        </button>
                    </div>
                </div>

                {{-- سوال با گزینه‌ها --}}
                <div class="question-box">
                    <div class="q-label">
                        <i class="fas fa-question-circle" style="color:#1e6f9f;"></i>
                        سوال (طراح: {{ $designerName }} {{ $designerFamily }})
                    </div>
                    <div class="q-text has-margin">{{ $report->question->question ?? 'سوال حذف شده است' }}</div>
                    
                    {{-- گزینه‌ها --}}
                    <div class="options-grid-inline">
                        @php
                            $options = [
                                1 => ['label' => 'الف', 'value' => $report->question->answer1],
                                2 => ['label' => 'ب', 'value' => $report->question->answer2],
                                3 => ['label' => 'ج', 'value' => $report->question->answer3],
                                4 => ['label' => 'د', 'value' => $report->question->answer4],
                            ];
                        @endphp
                        @foreach($options as $key => $option)
                            <div class="option-item-inline {{ $key == $report->question->answer ? 'correct' : '' }}">
                                <span class="opt-label">{{ $option['label'] }}</span>
                                <span class="opt-text">{{ $option['value'] }}</span>
                                @if($key == $report->question->answer)
                                    <span class="opt-icon"><i class="fas fa-check-circle"></i></span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- توضیح ایراد --}}
                <div class="description-box">
                    <div class="d-label">
                        <i class="fas fa-exclamation-triangle"></i>
                        توضیح ایراد:
                    </div>
                    <div class="d-text">{{ $report->description }}</div>
                </div>

                {{-- پاسخ مدیر (اگر وجود داشته باشد) --}}
                @if($report->admin_response)
                    <div class="response-box">
                        <div class="r-label">
                            <i class="fas fa-reply"></i>
                            پاسخ مدیر:
                        </div>
                        <div class="r-text">{{ $report->admin_response }}</div>
                    </div>
                @endif

                {{-- وضعیت داوری سوال --}}
                <div class="question-status">
                    <i class="fas fa-star" style="color:#ffd700;"></i>
                    وضعیت سوال: 
                    <span class="status-value">
                        @if($report->question->status === null)
                            در انتظار داوری
                        @elseif($report->question->status == 1)
                            عالی
                        @elseif($report->question->status == 2)
                            خوب
                        @elseif($report->question->status == 3)
                            متوسط
                        @elseif($report->question->status == 4)
                            بد
                        @else
                            نامشخص
                        @endif
                    </span>
                </div>

                {{-- داوران و نمرات --}}
                <div class="scores-section">
                    <div class="scores-title">
                        <i class="fas fa-users" style="color:#9c27b0;"></i>
                        داوران و نمرات
                        @if($report->scores->count() > 0)
                            ({{ $report->scores->count() }} داوری)
                        @endif
                    </div>

                    @if($report->scores->count() > 0)
                        @foreach($report->scores as $score)
                            <div class="score-item">
                                <span class="judge-name">
                                    <i class="fas fa-user" style="color:#1e6f9f;"></i>
                                    {{ $score->user->name ?? 'نامشخص' }} {{ $score->user->family ?? '' }}
                                </span>

                                @if($score->status === 'approved')
                                    <span class="score-value {{ $scoreClasses[$score->score] ?? '' }}">
                                        {{ $scoreLabels[$score->score] ?? 'نامشخص' }}
                                    </span>
                                @endif

                                @if($score->negaresh == 1 || $score->gozine == 1 || $score->dark == 1)
                                    <span class="score-issues">
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

                                <span class="score-date">
                                    {{ \Hekmatinasser\Verta\Verta::instance($score->created_at)->format('Y/m/d H:i') }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="no-scores">
                            <i class="fas fa-info-circle"></i>
                            هنوز داوری‌ای برای این سوال ثبت نشده است.
                        </div>
                    @endif
                </div>

                {{-- فرم تغییر وضعیت گزارش --}}
                <form class="action-form" data-report-id="{{ $report->id }}" onsubmit="updateReportStatus(event, {{ $report->id }})">
                    @csrf
                    @method('PUT')
                    <select name="status">
                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>در انتظار بررسی</option>
                        <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>بررسی شده</option>
                        <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>رفع شده</option>
                        <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>رد شده</option>
                    </select>
                    <input type="text" name="admin_response" placeholder="پاسخ مدیر (اختیاری)" value="{{ $report->admin_response ?? '' }}">
                    <button type="submit" class="btn-action btn-action-success">
                        <i class="fas fa-save"></i>
                        بروزرسانی
                    </button>
                </form>

                @if($report->reviewed_by)
                    <div class="card-footer">
                        <div class="reviewer-info">
                            <i class="fas fa-user-check" style="color:#1e6f9f;"></i>
                            بررسی شده توسط: 
                            <span class="reviewer-name">
                                {{ $report->reviewer->name ?? 'نامشخص' }} {{ $report->reviewer->family ?? '' }}
                            </span>
                            @if($report->reviewed_at)
                                <span class="reviewer-date">
                                    ({{ \Hekmatinasser\Verta\Verta::instance($report->reviewed_at)->format('Y/m/d H:i') }})
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <span class="empty-icon"><i class="fas fa-inbox"></i></span>
            <h4>هیچ گزارشی ثبت نشده است</h4>
            <p>هنوز دانشجویی برای سوالات این درس گزارشی ثبت نکرده است.</p>
        </div>
    @endif
</div>

{{-- ===== MODAL EDIT QUESTION ===== --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-container">
        <div class="modal-header">
            <h4><i class="fas fa-edit" style="color:#1e6f9f;"></i> ویرایش سوال</h4>
            <button class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="editModalBody">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">در حال بارگذاری...</p>
            </div>
        </div>
    </div>
</div>

<script>
    // ===== UPDATE REPORT STATUS =====
    function updateReportStatus(event, reportId) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال بروزرسانی...';
        
        fetch(`/teacher/question-report/${reportId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
                showToast('✅ وضعیت گزارش با موفقیت بروزرسانی شد.', 'success');
            } else {
                showToast('❌ خطا در بروزرسانی وضعیت.', 'error');
            }
        })
        .catch(error => {
            showToast('❌ خطا در ارتباط با سرور.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> بروزرسانی';
        });
    }

    // ===== EDIT QUESTION MODAL =====
    let currentQuestionId = null;

    function openEditModal(questionId) {
        currentQuestionId = questionId;
        const modal = document.getElementById('editModal');
        const body = document.getElementById('editModalBody');
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        body.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">در حال بارگذاری...</p>
            </div>
        `;
        
        fetch(`/teacher/questions/show/${questionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const q = data.data;
                    
                    body.innerHTML = `
                        <form id="editQuestionForm" onsubmit="updateQuestion(event, ${q.id})">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label>متن سوال <span class="required">*</span></label>
                                <input type="text" class="form-control" name="question" value="${q.question}" required>
                            </div>
                            
                            <div class="options-grid">
                                <div class="form-group">
                                    <label>گزینه الف <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="answer1" value="${q.answer1}" required>
                                </div>
                                <div class="form-group">
                                    <label>گزینه ب <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="answer2" value="${q.answer2}" required>
                                </div>
                                <div class="form-group">
                                    <label>گزینه ج <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="answer3" value="${q.answer3}" required>
                                </div>
                                <div class="form-group">
                                    <label>گزینه د <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="answer4" value="${q.answer4}" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>گزینه صحیح <span class="required">*</span></label>
                                <select class="form-control" name="correct_answer" required>
                                    <option value="1" ${q.answer == 1 ? 'selected' : ''}>الف</option>
                                    <option value="2" ${q.answer == 2 ? 'selected' : ''}>ب</option>
                                    <option value="3" ${q.answer == 3 ? 'selected' : ''}>ج</option>
                                    <option value="4" ${q.answer == 4 ? 'selected' : ''}>د</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>وضعیت سوال</label>
                                <select class="form-control" name="status">
                                    <option value="">در انتظار داوری</option>
                                    <option value="0" ${q.status === 0 ? 'selected' : ''}>برگشت خورده</option>
                                    <option value="1" ${q.status == 1 ? 'selected' : ''}>عالی</option>
                                    <option value="2" ${q.status == 2 ? 'selected' : ''}>خوب</option>
                                    <option value="3" ${q.status == 3 ? 'selected' : ''}>متوسط</option>
                                    <option value="4" ${q.status == 4 ? 'selected' : ''}>بد</option>
                                </select>
                            </div>
                            
                            <div class="modal-actions">
                                <button type="submit" class="btn-modal btn-modal-primary">
                                    <i class="fas fa-save"></i> ذخیره تغییرات
                                </button>
                                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeEditModal()">
                                    <i class="fas fa-times"></i> انصراف
                                </button>
                            </div>
                        </form>
                    `;
                } else {
                    body.innerHTML = `
                        <div class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                            <p class="mt-2">خطا در دریافت اطلاعات سوال</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                body.innerHTML = `
                    <div class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                        <p class="mt-2">خطا در ارتباط با سرور</p>
                    </div>
                `;
            });
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // ===== UPDATE QUESTION =====
    function updateQuestion(event, questionId) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ذخیره...';
        
        fetch(`/teacher/questions/status/${questionId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeEditModal();
                location.reload();
                showToast('✅ سوال با موفقیت بروزرسانی شد.', 'success');
            } else {
                let errorMsg = 'خطا در بروزرسانی سوال';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('\n');
                }
                showToast('❌ ' + errorMsg, 'error');
            }
        })
        .catch(error => {
            showToast('❌ خطا در ارتباط با سرور.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> ذخیره تغییرات';
        });
    }

    // ===== TOAST =====
    function showToast(message, type = 'success') {
        const oldToast = document.querySelector('.toast-message');
        if (oldToast) oldToast.remove();
        
        const toast = document.createElement('div');
        toast.className = 'toast-message ' + type;
        toast.innerHTML = `
            <span>${message}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                toast.style.transition = 'all 0.4s ease';
                setTimeout(() => toast.remove(), 400);
            }
        }, 5000);
    }

    // ===== CLOSE MODAL ON ESCAPE =====
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
        }
    });

    // ===== CLOSE MODAL ON OVERLAY CLICK =====
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endsection