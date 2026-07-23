@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-index.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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