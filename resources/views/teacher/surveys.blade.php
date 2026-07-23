@extends('layout.master')

@section('title')
ملیسان | نظرسنجی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-surveys.css')}}">
<script src="{{ asset('ChartJS.js') }}"></script>
@endsection

@section('mohtava')
<div class="surveys-container">
    <div class="surveys-header d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
        <h4 class="surveys-title">نظرسنجی : <span>{{ $course->name ?? $category->name ?? 'همه' }}</span></h4>
        <div>
            @include('layout.backbtn')
        </div>
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