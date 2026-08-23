@extends('layout.master')

@section('title')
    ملیسان | صفحه اصلی
@endsection

@section('head')
    <link rel="stylesheet" href="{{asset('css/style-index.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('mohtava')
    <div class="motivation-banner">
        <div class="motivation-text-en">
            {!! $message->text ?? 'به سامانه ملیسان خوش آمدید' !!}
        </div>
    </div>

    <div class="dashboard-cards">
        <div class="row g-4">
            <div class="col-md-4 col-sm-6">
                <a href="{{ route('courses.st') }}" class="card-dash shadow">
                    <div class="card-icon"><i class="fas fa-chalkboard"></i></div>
                    <div class="card-title">درس‌ها</div>
                    <div class="card-count">{{ Auth::user()->courses()->count() }}</div>
                    <div class="card-text-sm">درس فعال</div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="{{ route('skill.st') }}" class="card-dash shadow">
                    <div class="card-icon"><i class="fas fa-layer-group"></i></div>
                    <div class="card-title">دوره های مهارتی</div>
                    <div class="card-count">{{ $course_count ?? 0 }}</div>
                    <div class="card-text-sm">دوره در حال برگزاری</div>
                </a>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card-dash shadow">
                    <div class="card-icon"><i class="fas fa-clipboard-list"></i></div>
                    <div class="card-title">جعبه های یادگیری</div>
                    <div class="card-count">{{ $active_exam_count ?? 0 }}</div>
                    <div class="card-text-sm">جعبه های فعال</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ACTIVE EXAMS ===== --}}
    @if(isset($activeExams) && $activeExams->count() > 0)
        <div class="active-exams-section">
            <div class="section-header">
                <h4><i class="fas fa-circle"></i> جعبه‌های فعال</h4>
                <span class="badge-count">{{ $activeExams->count() }}</span>
            </div>
            @foreach($activeExams->take(5) as $exam)
                <div class="exam-item">
                    <div class="exam-info">
                        <span class="exam-title">{{ $exam->title }}</span>
                        <span class="exam-course"><i class="fas fa-book-open" style="font-size:10px;"></i>
                            {{ $exam->course->name ?? 'نامشخص' }}</span>
                    </div>
                    <div class="exam-time">
                        <i class="fas fa-clock" style="color:#1e6f9f;"></i>
                        {{ \Hekmatinasser\Verta\Verta::instance($exam->end)->format('H:i') }}
                    </div>
                    <button type="button" class="btn-start-exam-sm" onclick="openExamModal({{ $exam->id }})">
                        <i class="fas fa-play"></i> شروع
                    </button>
                </div>
            @endforeach
        </div>
    @endif
@endsection

{{-- ===== EXAM MODAL ===== --}}
<div class="modal fade" id="examModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="examModalTitle">جعبه</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- توضیحات آزمون --}}
                <div class="exam-description-box" id="examDescription">
                    <i class="fas fa-info-circle"></i>
                    <span>در حال بارگذاری توضیحات...</span>
                </div>

                {{-- فیلد کد آزمون (مخفی در ابتدا) --}}
                <div class="code-field" id="examCodeField" style="display: none;">
                    <label for="examCodeInput">
                        <i class="fas fa-key" style="color:#1e6f9f;"></i> کد آزمون
                    </label>
                    <div class="input-group mt-2">
                        <input type="text" class="form-control" id="examCodeInput" placeholder="کد آزمون را وارد کنید"
                            maxlength="20" autocomplete="off">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <div class="code-error" id="examCodeError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span id="examCodeErrorMessage">کد وارد شده صحیح نیست</span>
                    </div>
                </div>

                {{-- فرم مخفی برای ارسال --}}
                <form id="examStartForm" action="{{ route('student.exam.start') }}" method="POST"
                    style="display: none;">
                    @csrf
                    <input type="hidden" name="azmon_id" id="examModalId" value="">
                    <input type="hidden" name="exam_code" id="examCodeHidden" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-success" id="startExamBtn">
                    <i class="fas fa-play"></i> شروع آزمون
                </button>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ==========================================
        // متغیرهای سراسری
        // ==========================================
        let currentExamId = null;
        let examHasCode = false;
        let modalInstance = null;
        let isLoading = false;

        // ==========================================
        // باز کردن مودال
        // ==========================================
        function openExamModal(examId) {
            // Check if all required elements exist
            const descBox = document.getElementById('examDescription');
            const titleElement = document.getElementById('examModalTitle');
            const codeField = document.getElementById('examCodeField');
            const codeError = document.getElementById('examCodeError');
            const codeInput = document.getElementById('examCodeInput');
            const startBtn = document.getElementById('startExamBtn');

            if (!descBox || !titleElement || !codeField || !codeError || !codeInput || !startBtn) {
                console.error('Required DOM elements not found');
                alert('خطا در بارگذاری صفحه. لطفاً صفحه را مجدداً بارگذاری کنید.');
                return;
            }

            currentExamId = examId;

            // نمایش حالت بارگذاری
            descBox.innerHTML =
                '<i class="fas fa-spinner fa-spin"></i> <span>در حال بارگذاری اطلاعات آزمون...</span>';
            titleElement.textContent = 'آزمون';

            // مخفی کردن فیلد کد
            codeField.style.display = 'none';
            codeError.style.display = 'none';
            codeInput.value = '';
            codeInput.classList.remove('is-invalid');

            // غیرفعال کردن دکمه شروع
            startBtn.disabled = true;

            // نمایش مودال
            const modalElement = document.getElementById('examModal');
            if (!modalElement) {
                console.error('Modal element not found');
                return;
            }

            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
            }
            modalInstance.show();

            // دریافت اطلاعات آزمون
            fetch(`/student/exam/info/${examId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('خطا در دریافت اطلاعات');
                    }
                    return response.json();
                })
                .then(data => {
                    // نمایش توضیحات
                    if (data.description && data.description.trim() !== '') {
                        descBox.innerHTML = `<i class="fas fa-info-circle"></i> ${data.description}`;
                    } else {
                        descBox.innerHTML = `<i class="fas fa-info-circle"></i> توضیحاتی برای این آزمون وجود ندارد.`;
                    }

                    // تنظیم عنوان
                    titleElement.textContent = data.title || 'آزمون';

                    // بررسی وجود کد
                    examHasCode = data.has_code || false;

                    if (examHasCode) {
                        codeField.style.display = 'block';
                        codeInput.value = '';
                        codeInput.focus();
                    } else {
                        codeField.style.display = 'none';
                    }

                    // فعال کردن دکمه شروع
                    startBtn.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    descBox.innerHTML =
                        '<i class="fas fa-exclamation-triangle" style="color:#f44336;"></i> ' +
                        '<span style="color:#f44336;">خطا در دریافت اطلاعات آزمون. لطفاً مجدداً تلاش کنید.</span>';
                    startBtn.disabled = true;
                });
        }

        // ==========================================
        // شروع آزمون
        // ==========================================
        document.addEventListener('DOMContentLoaded', function () {
            const startBtn = document.getElementById('startExamBtn');
            const codeInput = document.getElementById('examCodeInput');
            const codeError = document.getElementById('examCodeError');
            const errorMessage = document.getElementById('examCodeErrorMessage');
            const examForm = document.getElementById('examStartForm');
            const modalId = document.getElementById('examModalId');
            const codeHidden = document.getElementById('examCodeHidden');
            const modalElement = document.getElementById('examModal');

            // Check if all required elements exist
            if (!startBtn || !codeInput || !codeError || !errorMessage ||
                !examForm || !modalId || !codeHidden || !modalElement) {
                console.error('Some required elements are missing');
                return;
            }

            // شروع آزمون
            startBtn.addEventListener('click', function () {
                if (isLoading) return;

                const codeError = document.getElementById('examCodeError');
                const errorMessage = document.getElementById('examCodeErrorMessage');

                // اگر آزمون کد دارد
                if (examHasCode) {
                    const enteredCode = codeInput.value.trim();

                    // بررسی خالی بودن کد
                    if (!enteredCode) {
                        errorMessage.textContent = 'لطفاً کد آزمون را وارد کنید';
                        codeError.style.display = 'block';
                        codeInput.classList.add('is-invalid');
                        codeInput.focus();
                        return;
                    }

                    // بررسی کد
                    isLoading = true;
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال بررسی...';

                    fetch(`/student/exam/verify-code/${currentExamId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ code: enteredCode })
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('خطا در بررسی کد');
                            }
                            return response.json();
                        })
                        .then(data => {
                            isLoading = false;
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-play"></i> شروع آزمون';

                            if (data.valid) {
                                // کد صحیح است - شروع آزمون
                                if (codeHidden) {
                                    codeHidden.value = enteredCode;
                                }
                                startExam();
                            } else {
                                // کد اشتباه است
                                errorMessage.textContent = 'کد وارد شده صحیح نیست';
                                codeError.style.display = 'block';
                                codeInput.classList.add('is-invalid');
                                codeInput.value = '';
                                codeInput.focus();
                            }
                        })
                        .catch(error => {
                            isLoading = false;
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-play"></i> شروع آزمون';
                            console.error('Error:', error);
                            alert('خطا در بررسی کد. لطفاً مجدداً تلاش کنید.');
                        });
                } else {
                    // بدون کد - شروع مستقیم
                    startExam();
                }
            });

            // پاک کردن خطا هنگام تایپ
            codeInput.addEventListener('input', function () {
                this.classList.remove('is-invalid');
                const errorEl = document.getElementById('examCodeError');
                if (errorEl) {
                    errorEl.style.display = 'none';
                }
            });

            // ارسال با کلید Enter
            codeInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const btn = document.getElementById('startExamBtn');
                    if (btn) btn.click();
                }
            });

            // پاکسازی مودال هنگام بسته شدن
            modalElement.addEventListener('hidden.bs.modal', function () {
                const codeInput = document.getElementById('examCodeInput');
                const codeError = document.getElementById('examCodeError');
                const startBtn = document.getElementById('startExamBtn');

                if (codeInput) {
                    codeInput.value = '';
                    codeInput.classList.remove('is-invalid');
                }
                if (codeError) {
                    codeError.style.display = 'none';
                }
                if (startBtn) {
                    startBtn.disabled = false;
                    startBtn.innerHTML = '<i class="fas fa-play"></i> شروع آزمون';
                }
                isLoading = false;
            });
        });

        // ==========================================
        // شروع آزمون (ارسال فرم)
        // ==========================================
        function startExam() {
            const form = document.getElementById('examStartForm');
            const modalId = document.getElementById('examModalId');

            if (!form || !modalId) {
                console.error('Form or modal ID element not found');
                return;
            }

            modalId.value = currentExamId;

            // بستن مودال
            if (modalInstance) {
                modalInstance.hide();
            }

            // ارسال فرم
            form.submit();
        }
    </script>
@endsection