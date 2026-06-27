@extends('layout.master')

@section('title')
ملیسان | نظرسنجی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-surveys.css')}}">
<script src="{{ asset('ChartJS.js') }}"></script>
<style>
    /* ============================================
       استایل‌های اصلی
       ============================================ */
    .surveys-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    .surveys-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .surveys-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .surveys-title span {
        color: #1e6f9f;
    }

    .surveys-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .surveys-form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        padding: 24px;
        border: 1px solid #e8edf3;
    }

    .surveys-list-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        padding: 24px;
        border: 1px solid #e8edf3;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f4f9;
    }

    .card-title i {
        color: #1e6f9f;
        margin-left: 8px;
    }

    /* ============================================
       فرم
       ============================================ */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1a2332;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .form-group .required {
        color: #e74c3c;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        direction: rtl;
        font-family: inherit;
    }

    .form-control:focus {
        border-color: #1e6f9f;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.08);
        outline: none;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .form-select {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e8edf3;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        direction: rtl;
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
    }

    .form-select:focus {
        border-color: #1e6f9f;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.08);
        outline: none;
    }

    .btn-submit {
        padding: 10px 32px;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
    }

    .btn-submit i {
        font-size: 16px;
    }

    .options-group {
        display: block;
    }

    .options-group.hidden {
        display: none;
    }

    .helper-text {
        color: #6b7a8f;
        font-size: 12px;
        display: block;
        margin-top: 5px;
    }

    /* ============================================
       پیام‌ها
       ============================================ */
    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 16px;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 16px;
        border: 1px solid #f5c6cb;
    }

    .alert-error ul {
        margin: 0;
        padding-right: 20px;
    }

    /* ============================================
       جدول
       ============================================ */
    .table-wrapper {
        overflow-x: auto;
    }

    .surveys-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        min-width: 700px;
    }

    .surveys-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #e8edf3;
    }

    .surveys-table thead th {
        padding: 12px 16px;
        text-align: right;
        font-weight: 700;
        color: #1a2332;
        font-size: 13px;
        white-space: nowrap;
    }

    .surveys-table tbody tr {
        border-bottom: 1px solid #f0f4f9;
        transition: background 0.2s;
    }

    .surveys-table tbody tr:hover {
        background: #f8fafc;
    }

    .surveys-table tbody td {
        padding: 10px 16px;
        vertical-align: middle;
        color: #333;
    }

    .survey-question {
        font-weight: 600;
        color: #1a2332;
        text-decoration: none;
        cursor: pointer;
    }

    .survey-question:hover {
        color: #1e6f9f;
        text-decoration: underline;
    }

    .details-container {
        display: none;
        margin-top: 8px;
        font-size: 13px;
        color: #6b7a8f;
        background: #f8fafc;
        padding: 12px;
        border-radius: 8px;
    }

    .details-container.show {
        display: block;
    }

    .details-container p {
        margin: 0 0 4px 0;
    }

    .details-container .options-list {
        margin: 4px 0 0 0;
        padding-right: 20px;
    }

    /* ============================================
       اکشن دکمه‌ها
       ============================================ */
    .action-btns {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #fff;
        font-size: 14px;
        text-decoration: none;
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    .action-btn.view {
        background: #2196f3;
    }
    .action-btn.view:hover { background: #1976d2; }

    .action-btn.edit {
        background: #ff9800;
    }
    .action-btn.edit:hover { background: #f57c00; }

    .action-btn.delete {
        background: #f44336;
    }
    .action-btn.delete:hover { background: #d32f2f; }

    .action-btn.publish {
        background: #4caf50;
    }
    .action-btn.publish:hover { background: #388e3c; }

    .action-btn.unpublish {
        background: #9e9e9e;
    }
    .action-btn.unpublish:hover { background: #757575; }

    .action-btn.results {
        background: #9c27b0;
    }
    .action-btn.results:hover { background: #7b1fa2; }

    /* ============================================
       وضعیت و نوع
       ============================================ */
    .badge-status {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-status.active {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-status.inactive {
        background: #f5f5f5;
        color: #616161;
    }

    .badge-type {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #e3f2fd;
        color: #1565c0;
    }

    /* ============================================
       مودال نتایج
       ============================================ */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-container {
        background: #fff;
        border-radius: 16px;
        max-width: 700px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalSlideIn 0.3s ease;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    @keyframes modalSlideIn {
        from { transform: translateY(-30px) scale(0.95); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e8edf3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        border-radius: 16px 16px 0 0;
    }

    .modal-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        color: #fff;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        padding: 16px 24px 24px;
        border-top: 1px solid #e8edf3;
        display: flex;
        justify-content: flex-end;
    }

    .btn-close-modal {
        padding: 10px 24px;
        background: #f0f4f9;
        color: #1a2332;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-close-modal:hover {
        background: #e8edf3;
    }

    /* ============================================
       نتایج
       ============================================ */
    .results-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        margin-top: 16px;
    }

    .results-table thead th {
        padding: 10px 12px;
        text-align: right;
        background: #f8fafc;
        font-weight: 700;
        border-bottom: 2px solid #e8edf3;
    }

    .results-table tbody td {
        padding: 8px 12px;
        border-bottom: 1px solid #f0f4f9;
    }

    .results-table tbody tr:last-child td {
        border-bottom: none;
    }

    .chart-container {
        max-width: 350px;
        margin: 0 auto 20px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7a8f;
        background: #f8fafc;
        border-radius: 12px;
    }

    .empty-state i {
        font-size: 48px;
        color: #d0d7e2;
        display: block;
        margin-bottom: 16px;
    }

    .empty-state p {
        font-size: 15px;
        margin: 0;
    }

    .loading-state {
        text-align: center;
        padding: 40px 0;
    }

    .loading-state .spinner {
        font-size: 32px;
        color: #1e6f9f;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loading-state p {
        margin-top: 12px;
        color: #6b7a8f;
    }

    .error-state {
        text-align: center;
        padding: 30px 0;
        color: #f44336;
    }

    .no-data {
        text-align: center;
        padding: 30px 0;
        color: #6b7a8f;
    }

    .total-answers {
        text-align: center;
        color: #6b7a8f;
        margin-bottom: 12px;
    }

    .total-answers strong {
        font-weight: 700;
    }

    /* ============================================
       ریسپانسیو
       ============================================ */
    @media (max-width: 992px) {
        .surveys-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .surveys-container {
            padding: 10px;
        }
        .surveys-header {
            flex-direction: column;
            align-items: stretch;
        }
        .surveys-title {
            text-align: center;
        }
        .modal-container {
            width: 95%;
            margin: 10px;
        }
        .action-btns {
            flex-wrap: wrap;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="surveys-container">
    <div class="surveys-header">
        <h4 class="surveys-title">نظرسنجی : <span>{{ $course->name ?? $category->name ?? 'همه' }}</span></h4>
    </div>

    <div class="surveys-grid">
        <!-- فرم ایجاد نظرسنجی -->
        <div class="surveys-form-card">
            <div class="card-title">
                <i class="fas fa-plus-circle"></i> ایجاد نظرسنجی جدید
            </div>

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('survey.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="cat_id" value="{{ $catId ?? $course->id ?? '' }}">

                <div class="form-group">
                    <label for="question">عنوان سوال <span class="required">*</span></label>
                    <textarea id="question" name="question" class="form-control" rows="3" placeholder="متن سوال را وارد کنید...">{{ old('question') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="answer">نوع پاسخ دهی <span class="required">*</span></label>
                    <select id="answer" name="answer" class="form-select" onchange="toggleOptions(this.value)">
                        <option value="1">پاسخ کوتاه</option>
                        <option value="2" selected>چند گزینه‌ای (انتخاب یک گزینه)</option>
                        <option value="3">چند گزینه‌ای (انتخاب چند گزینه)</option>
                    </select>
                </div>

                <div class="form-group options-group" id="optionsGroup">
                    <label for="options">گزینه‌ها <span class="required">*</span></label>
                    <textarea id="options" name="options" class="form-control" rows="4" placeholder="هر گزینه را در یک خط وارد کنید...">{{ old('options') }}</textarea>
                    <span class="helper-text">هر گزینه را در یک خط جداگانه وارد کنید</span>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i>
                    ایجاد نظرسنجی
                </button>
            </form>
        </div>

        <!-- لیست نظرسنجی‌ها -->
        <div class="surveys-list-card">
            <div class="card-title">
                <i class="fas fa-list"></i> نظرسنجی‌های ثبت شده
            </div>

            <div class="table-wrapper">
                <table class="surveys-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان سوال</th>
                            <th>نوع</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($surveys ?? [] as $survey)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="survey-question" onclick="toggleDetails({{ $survey->id }})">
                                        {{ Str::limit($survey->text, 50) }}
                                    </span>
                                    <div class="details-container" id="details-{{ $survey->id }}">
                                        <p><strong>گیرندگان:</strong> {{ $survey->recipient ?? 'همه' }}</p>
                                        <p><strong>نوع:</strong> {{ $survey->type_text }}</p>
                                        @if(isset($survey->options) && $survey->options->count() > 0)
                                            <p><strong>گزینه‌ها:</strong></p>
                                            <ul class="options-list">
                                                @foreach($survey->options as $option)
                                                    <li>{{ $option->option }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge-type">{{ $survey->type_text ?? 'نامشخص' }}</span></td>
                                <td>
                                    <span class="badge-status {{ $survey->active == 1 ? 'active' : 'inactive' }}">
                                        {{ $survey->active == 1 ? 'فعال' : 'غیر فعال' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <button class="action-btn view" onclick="toggleDetails({{ $survey->id }})" title="مشاهده">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="/teacher/courses/survey/active/{{ $survey->id }}" class="action-btn {{ $survey->active == 1 ? 'unpublish' : 'publish' }}" title="{{ $survey->active == 1 ? 'غیرفعال' : 'فعال' }}">
                                            <i class="fas {{ $survey->active == 1 ? 'fa-pause' : 'fa-play' }}"></i>
                                        </a>
                                        <button class="action-btn results" onclick="showResults({{ $survey->id }})" title="نتایج">
                                            <i class="fas fa-chart-bar"></i>
                                        </button>
                                        <a href="/teacher/courses/survey/remove/{{ $survey->id }}" class="action-btn delete" title="حذف" onclick="return confirm('آیا از حذف این نظرسنجی اطمینان دارید؟')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-poll"></i>
                                        <p>هیچ نظرسنجی ثبت نشده است</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- مودال نتایج -->
<div class="modal-overlay" id="resultsModal">
    <div class="modal-container">
        <div class="modal-header">
            <h4 id="modalTitle">نتایج نظرسنجی</h4>
            <button class="modal-close" onclick="closeResultsModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="modalBody">
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin spinner"></i>
                <p>در حال بارگذاری...</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-close-modal" onclick="closeResultsModal()">بستن</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // ============================================
    // نمایش/مخفی کردن جزئیات
    // ============================================
    function toggleDetails(id) {
        var el = document.getElementById('details-' + id);
        if (el) {
            el.classList.toggle('show');
        }
    }

    // ============================================
    // نمایش/مخفی کردن گزینه‌ها بر اساس نوع
    // ============================================
    function toggleOptions(value) {
        var optionsGroup = document.getElementById('optionsGroup');
        if (value == '1') {
            optionsGroup.classList.add('hidden');
        } else {
            optionsGroup.classList.remove('hidden');
        }
    }

    // اجرا هنگام لود صفحه
    document.addEventListener('DOMContentLoaded', function() {
        var answerSelect = document.getElementById('answer');
        if (answerSelect) {
            toggleOptions(answerSelect.value);
        }
    });

    // ============================================
    // مودال نتایج
    // ============================================
    var pieChartInstance = null;

    function showResults(id) {
        var modal = document.getElementById('resultsModal');
        var title = document.getElementById('modalTitle');
        var body = document.getElementById('modalBody');

        modal.classList.add('active');
        title.textContent = 'در حال بارگذاری...';
        body.innerHTML = `
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin spinner"></i>
                <p>در حال بارگذاری...</p>
            </div>
        `;

        fetch('/teacher/courses/survey/results/' + id, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Server error: ' + response.status);
            }
            return response.json();
        })
        .then(function(data) {
            title.textContent = data.survey || 'نتایج نظرسنجی';

            if (data.type === 'descriptive') {
                // پاسخ کوتاه
                var rows = '';
                if (data.answers && data.answers.length > 0) {
                    data.answers.forEach(function(answer) {
                        rows += '<tr><td>' + answer + '</td></tr>';
                    });
                } else {
                    rows = '<tr><td class="no-data">پاسخی ثبت نشده است</td></tr>';
                }

                body.innerHTML = `
                    <table class="results-table">
                        <thead>
                            <tr><th>پاسخ‌های داده شده</th></tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                `;

            } else {
                // چند گزینه‌ای
                if (!data.results || data.results.length === 0) {
                    body.innerHTML = '<p class="no-data">پاسخی ثبت نشده است</p>';
                    return;
                }

                var labels = data.results.map(function(r) { return r.label; });
                var percents = data.results.map(function(r) { return r.percent; });
                var counts = data.results.map(function(r) { return r.count; });
                var colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF', '#7BC8A4'];

                var tableRows = data.results.map(function(r) {
                    return '<tr><td>' + r.label + '</td><td>' + r.count + '</td><td><strong>' + r.percent + '%</strong></td></tr>';
                }).join('');

                body.innerHTML = `
                    <div class="chart-container">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <p class="total-answers">
                        مجموع پاسخ‌ها: <strong>${data.total}</strong>
                    </p>
                    <table class="results-table">
                        <thead>
                            <tr><th>گزینه</th><th>تعداد</th><th>درصد</th></tr>
                        </thead>
                        <tbody>${tableRows}</tbody>
                    </table>
                `;

                // رسم نمودار
                if (pieChartInstance) {
                    pieChartInstance.destroy();
                    pieChartInstance = null;
                }

                setTimeout(function() {
                    var canvas = document.getElementById('pieChart');
                    if (!canvas) return;

                    pieChartInstance = new Chart(canvas, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: percents,
                                backgroundColor: colors.slice(0, labels.length),
                                borderWidth: 2,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { font: { size: 13 } }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            var idx = ctx.dataIndex;
                                            return ctx.label + ': ' + ctx.raw + '% (' + counts[idx] + ' نفر)';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }, 150);
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            title.textContent = 'خطا';
            body.innerHTML = '<p class="error-state">خطا در دریافت اطلاعات. لطفاً دوباره تلاش کنید.</p>';
        });
    }

    function closeResultsModal() {
        document.getElementById('resultsModal').classList.remove('active');
        if (pieChartInstance) {
            pieChartInstance.destroy();
            pieChartInstance = null;
        }
    }

    // بستن مودال با کلیک روی پس‌زمینه
    document.getElementById('resultsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeResultsModal();
        }
    });

    // بستن با کلید ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var modal = document.getElementById('resultsModal');
            if (modal.classList.contains('active')) {
                closeResultsModal();
            }
        }
    });
</script>
@endsection