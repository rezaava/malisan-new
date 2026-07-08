@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-index.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== ACTIVE EXAMS SECTION ===== */
    .active-exams-section {
        margin-top: 30px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 20px 24px;
    }

    .active-exams-section .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }

    .active-exams-section .section-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1a2332;
    }

    .active-exams-section .section-header h4 i {
        color: #f44336;
        margin-left: 8px;
    }

    .active-exams-section .section-header .badge-count {
        background: #f44336;
        color: #fff;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .exam-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #f0f4f9;
        transition: all 0.2s ease;
        flex-wrap: wrap;
        gap: 10px;
    }

    .exam-item:last-child {
        border-bottom: none;
    }

    .exam-item:hover {
        background: #f8fafc;
        border-radius: 10px;
    }

    .exam-item .exam-info {
        flex: 1;
        min-width: 150px;
    }

    .exam-item .exam-info .exam-title {
        font-weight: 600;
        color: #1a2332;
        font-size: 14px;
    }

    .exam-item .exam-info .exam-course {
        font-size: 13px;
        color: #6b7a8f;
        display: block;
    }

    .exam-item .exam-time {
        font-size: 13px;
        color: #6b7a8f;
        direction: ltr;
    }

    .btn-start-exam-sm {
        padding: 6px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        background: linear-gradient(135deg, #4caf50, #388e3c);
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-start-exam-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        color: #fff;
    }

    .no-exam-message {
        text-align: center;
        padding: 20px;
        color: #6b7a8f;
        font-size: 14px;
    }

    .no-exam-message i {
        font-size: 30px;
        display: block;
        margin-bottom: 8px;
        color: #d0d7e2;
    }

    /* ===== CARD DASH ===== */
    .card-dash shadow {
        display: block;
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
    }

    .card-dash shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: inherit;
    }

    .card-dash shadow .card-icon {
        font-size: 32px;
        margin-bottom: 10px;
        color: #1e6f9f;
    }

    .card-dash shadow .card-title {
        font-size: 14px;
        font-weight: 600;
        color: #1a2332;
        margin-bottom: 4px;
    }

    .card-dash shadow .card-count {
        font-size: 28px;
        font-weight: 800;
        color: #1a2332;
    }

    .card-dash shadow .card-text-sm {
        font-size: 12px;
        color: #6b7a8f;
    }

    .card-dash shadow .exam-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #f44336;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 20px;
    }

    /* ===== MODAL STYLES ===== */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    .modal-header {
        border-bottom: 1px solid #f0f4f9;
        padding: 20px 24px;
    }
    .modal-header .modal-title {
        font-weight: 700;
        color: #1a2332;
    }
    .modal-body {
        padding: 24px;
    }
    .modal-body .exam-description-box {
        background: #f8fafc;
        padding: 16px 20px;
        border-radius: 12px;
        color: #3d4a5c;
        font-size: 14px;
        line-height: 1.8;
        min-height: 60px;
    }
    .modal-body .exam-description-box i {
        color: #1e6f9f;
        margin-left: 8px;
    }
    .modal-body .code-field {
        margin-top: 20px;
    }
    .modal-body .code-field label {
        font-weight: 600;
        color: #1a2332;
        font-size: 14px;
    }
    .modal-body .code-field .input-group {
        direction: ltr;
    }
    .modal-body .code-field .input-group input {
        border-radius: 10px 0 0 10px !important;
        border: 2px solid #e8edf3;
        padding: 10px 16px;
        font-size: 16px;
        letter-spacing: 2px;
        font-weight: 600;
        text-align: center;
    }
    .modal-body .code-field .input-group input:focus {
        border-color: #1e6f9f;
        box-shadow: 0 0 0 3px rgba(30, 111, 159, 0.1);
    }
    .modal-body .code-field .input-group .input-group-text {
        border-radius: 0 10px 10px 0 !important;
        background: #f8fafc;
        border: 2px solid #e8edf3;
        border-right: none;
        color: #6b7a8f;
    }
    .modal-body .code-error {
        color: #f44336;
        font-size: 13px;
        margin-top: 8px;
        display: none;
    }
    .modal-body .code-error i {
        margin-left: 6px;
    }
    .modal-footer {
        border-top: 1px solid #f0f4f9;
        padding: 16px 24px;
        gap: 10px;
    }
    .modal-footer .btn-secondary {
        background: #f0f4f9;
        border: none;
        color: #4a5a6e;
        padding: 8px 24px;
        border-radius: 10px;
        font-weight: 600;
    }
    .modal-footer .btn-secondary:hover {
        background: #e4e9f0;
    }
    .modal-footer .btn-success {
        background: linear-gradient(135deg, #4caf50, #388e3c);
        border: none;
        padding: 8px 28px;
        border-radius: 10px;
        font-weight: 600;
    }
    .modal-footer .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }
    .modal-footer .btn-success:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
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
            <div class="card-dash shadow">
                <div class="card-icon"><i class="fas fa-layer-group"></i></div>
                <div class="card-title">دوره‌ها</div>
                <div class="card-count">{{ $course_count ?? 0 }}</div>
                <div class="card-text-sm">دوره در حال برگزاری</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card-dash shadow">
                <div class="card-icon"><i class="fas fa-clipboard-list"></i></div>
                <div class="card-title">آزمون‌ها</div>
                <div class="card-count">{{ $active_exam_count ?? 0 }}</div>
                <div class="card-text-sm">آزمون فعال</div>
            </div>
        </div>
    </div>
</div>

{{-- ===== ACTIVE EXAMS ===== --}}
@if(isset($activeExams) && $activeExams->count() > 0)
    <div class="active-exams-section">
        <div class="section-header">
            <h4><i class="fas fa-circle"></i> آزمون‌های فعال</h4>
            <span class="badge-count">{{ $activeExams->count() }}</span>
        </div>
        @foreach($activeExams->take(5) as $exam)
            <div class="exam-item">
                <div class="exam-info">
                    <span class="exam-title">{{ $exam->title }}</span>
                    <span class="exam-course"><i class="fas fa-book-open" style="font-size:10px;"></i> {{ $exam->course->name ?? 'نامشخص' }}</span>
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
                <h5 class="modal-title" id="examModalTitle">آزمون</h5>
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
                        <input type="text" class="form-control" id="examCodeInput" 
                               placeholder="کد آزمون را وارد کنید" 
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
                <form id="examStartForm" action="{{ route('student.exam.start') }}" method="POST" style="display: none;">
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
        currentExamId = examId;
        
        // نمایش حالت بارگذاری
        document.getElementById('examDescription').innerHTML = 
            '<i class="fas fa-spinner fa-spin"></i> <span>در حال بارگذاری اطلاعات آزمون...</span>';
        document.getElementById('examModalTitle').textContent = 'آزمون';
        
        // مخفی کردن فیلد کد
        document.getElementById('examCodeField').style.display = 'none';
        document.getElementById('examCodeError').style.display = 'none';
        document.getElementById('examCodeInput').value = '';
        document.getElementById('examCodeInput').classList.remove('is-invalid');
        
        // غیرفعال کردن دکمه شروع
        document.getElementById('startExamBtn').disabled = true;
        
        // نمایش مودال
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(document.getElementById('examModal'), {
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
                const descBox = document.getElementById('examDescription');
                if (data.description && data.description.trim() !== '') {
                    descBox.innerHTML = `<i class="fas fa-info-circle"></i> ${data.description}`;
                } else {
                    descBox.innerHTML = `<i class="fas fa-info-circle"></i> توضیحاتی برای این آزمون وجود ندارد.`;
                }
                
                // تنظیم عنوان
                document.getElementById('examModalTitle').textContent = data.title || 'آزمون';
                
                // بررسی وجود کد
                examHasCode = data.has_code || false;
                const codeField = document.getElementById('examCodeField');
                const codeInput = document.getElementById('examCodeInput');
                
                if (examHasCode) {
                    codeField.style.display = 'block';
                    codeInput.value = '';
                    codeInput.focus();
                } else {
                    codeField.style.display = 'none';
                }
                
                // فعال کردن دکمه شروع
                document.getElementById('startExamBtn').disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('examDescription').innerHTML = 
                    '<i class="fas fa-exclamation-triangle" style="color:#f44336;"></i> ' +
                    '<span style="color:#f44336;">خطا در دریافت اطلاعات آزمون. لطفاً مجدداً تلاش کنید.</span>';
                document.getElementById('startExamBtn').disabled = true;
            });
    }

    // ==========================================
    // شروع آزمون
    // ==========================================
    document.getElementById('startExamBtn').addEventListener('click', function() {
        if (isLoading) return;
        
        const codeInput = document.getElementById('examCodeInput');
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
                    document.getElementById('examCodeHidden').value = enteredCode;
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

    // ==========================================
    // شروع آزمون (ارسال فرم)
    // ==========================================
    function startExam() {
        const form = document.getElementById('examStartForm');
        document.getElementById('examModalId').value = currentExamId;
        
        // بستن مودال
        if (modalInstance) {
            modalInstance.hide();
        }
        
        // ارسال فرم
        form.submit();
    }

    // ==========================================
    // پاک کردن خطا هنگام تایپ
    // ==========================================
    document.getElementById('examCodeInput').addEventListener('input', function() {
        this.classList.remove('is-invalid');
        document.getElementById('examCodeError').style.display = 'none';
    });

    // ==========================================
    // ارسال با کلید Enter
    // ==========================================
    document.getElementById('examCodeInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('startExamBtn').click();
        }
    });

    // ==========================================
    // پاکسازی مودال هنگام بسته شدن
    // ==========================================
    document.getElementById('examModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('examCodeInput').value = '';
        document.getElementById('examCodeInput').classList.remove('is-invalid');
        document.getElementById('examCodeError').style.display = 'none';
        document.getElementById('startExamBtn').disabled = false;
        document.getElementById('startExamBtn').innerHTML = '<i class="fas fa-play"></i> شروع آزمون';
        isLoading = false;
    });
</script>
@endsection