@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-exams.css')}}">
<link rel="stylesheet" href="{{asset('css/style-courses.css')}}">
@endsection

@section('mohtava')
<div class="content-header">
    <button class="archive-btn">
        <i class="fas fa-archive"></i>
        <span>آرشیوها</span>
    </button>
    <button class="exams-btn active">
        <i class="fas fa-book"></i>
        <span>آزمون‌ها</span>
    </button>
    
    <!-- Create exam Button -->
    <button class="btn-create-exam" onclick="openCreateModal()" style="margin-right: auto;">
        <i class="fas fa-plus"></i>
        ایجاد آزمون جدید
    </button>
</div>
    @forelse ($exams as $exam)
        <div class="exam-card">
            <a href="#" class="exam-link">
                    <div class="exam-badge">
                        @if ($exam->archieve == 1)
                            غیر فعال
                        @else
                            فعال
                        @endif
                    </div>
                <div class="exam-info">
                    <div class="exam-between-div">
                        <div class="inline-block">
                            <h3 class="exam-title">{{ $exam->name }}</h3>
                            <p class="exam-code">کد: {{ $exam->code }}</p>
                        </div>
                        <p class="exam-level">سطح: {{ $exam->sath }}</p>
                    </div>
                    <div class="exam-between-div">
                        <p class="exam-tarikh">شروع: 28/12/05 12:30</p>
                        <p class="exam-tarikh">پایان: 28/12/05 13:30</p>
                    </div>
                </div>
            </a>
            <div class="exam-actions">
                <div class="action-item" data-action="حذف" onclick="event.preventDefault(); event.stopPropagation(); deleteexam({{ $exam->id }})">
                    <i class="fas fa-trash-alt"></i>
                    <span class="action-tooltip">حذف</span>
                </div>
                <div class="action-item" data-action="ویرایش" onclick="event.preventDefault(); event.stopPropagation(); editexam({{ $exam->id }})">
                    <i class="fas fa-edit"></i>
                    <span class="action-tooltip">ویرایش</span>
                </div>
                <div class="action-item" data-action="اشتراک گذاری" onclick="event.preventDefault(); event.stopPropagation(); shareexam({{ $exam->id }})">
                    <i class="fas fa-share-alt"></i>
                    <span class="action-tooltip">اشتراک گذاری</span>
                </div>
                <div class="action-item" data-action="کپی" onclick="copyexam({{ $exam->id }})">
                    <i class="fas fa-copy"></i>
                    <span class="action-tooltip">کپی</span>
                </div>
                <div class="action-item" data-action="آرشیو" onclick="event.preventDefault(); event.stopPropagation(); archiveexam({{ $exam->id }})">
                    <i class="fas fa-archive"></i>
                    <span class="action-tooltip">آرشیو</span>
                </div>
                <div class="action-item" data-action="فعال/غیرفعال" onclick="event.preventDefault(); event.stopPropagation(); toggleexamStatus({{ $exam->id }})">
                    <i class="fas fa-toggle-on"></i>
                    <span class="action-tooltip">فعال/غیرفعال</span>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <p>شما هیچ آزمونی ندارید</p>
            <button class="btn-create-exam" onclick="openCreateModal()">
                <i class="fas fa-plus"></i>
                اولین آزمون خود را ایجاد کنید
            </button>
        </div>
    @endforelse
</div>

<!-- Modal -->
<div class="modal-overlay" id="createexamModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="modalTitle">ایجاد آزمون جدید</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="createexamForm" action="#" method="POST">
            @csrf
            <input type="hidden" name="copy" id="copyexamId" value="">
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">عنوان آزمون <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required class="form-control" 
                           placeholder="مثال: ریاضیات پایه">
                </div>
                
                <div class="form-group">
                    <label for="majazi">لینک کلاس مجازی (اختیاری)</label>
                    <input type="url" id="majazi" name="majazi" class="form-control" 
                           placeholder="https://example.com/class">
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">انصراف</button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i>
                    <span id="submitButtonText">ایجاد آزمون</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    // ============================================
    // MODAL FUNCTIONS
    // ============================================
    
    function openCreateModal(copyId = null) {
        const modal = document.getElementById('createexamModal');
        const modalTitle = document.getElementById('modalTitle');
        const submitButtonText = document.getElementById('submitButtonText');
        
        if (copyId) {
            // Copy mode
            modalTitle.textContent = 'کپی آزمون';
            submitButtonText.textContent = 'کپی آزمون';
            document.getElementById('copyexamId').value = copyId;
            
            // Show loading
            showToast('در حال بارگذاری اطلاعات آزمون...', 'info');
            
            // Fetch exam data
            fetch(`/teacher/exams/copy/${copyId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        showToast(data.error, 'error');
                        return;
                    }
                    
                    // Fill the form
                    document.getElementById('name').value = data.name;
                    document.getElementById('majazi').value = data.majazi || '';
                    
                    modal.classList.add('active');
                    showToast('اطلاعات آزمون بارگذاری شد. نام آزمون را ویرایش کنید.', 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('خطا در بارگذاری اطلاعات آزمون', 'error');
                    // Still open modal empty
                    modal.classList.add('active');
                });
        } else {
            // New exam mode
            modalTitle.textContent = 'ایجاد آزمون جدید';
            submitButtonText.textContent = 'ایجاد آزمون';
            document.getElementById('name').value = '';
            document.getElementById('majazi').value = '';
            document.getElementById('copyexamId').value = '';
            modal.classList.add('active');
        }
    }

    function closeModal() {
        const modal = document.getElementById('createexamModal');
        modal.classList.remove('active');
        document.getElementById('createexamForm').reset();
        document.getElementById('copyexamId').value = '';
    }

    // Close modal on overlay click
    document.getElementById('createexamModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // ============================================
    // exam ACTIONS
    // ============================================
    
    function copyexam(examId) {
        event.preventDefault();
        event.stopPropagation();
        openCreateModal(examId);
    }

    function deleteexam(examId) {
        event.preventDefault();
        event.stopPropagation();
        if (confirm('آیا از حذف این آزمون اطمینان دارید؟')) {
            showToast('در حال حذف آزمون...', 'info');
            // Add your delete logic here
        }
    }

    function editexam(examId) {
        event.preventDefault();
        event.stopPropagation();
        showToast('در حال ویرایش آزمون...', 'info');
        // Add your edit logic here
    }

    function shareexam(examId) {
        event.preventDefault();
        event.stopPropagation();
        // Get the exam code
        const examCode = prompt('کد آزمون را برای اشتراک گذاری کپی کنید:');
        if (examCode) {
            navigator.clipboard.writeText(examCode).then(() => {
                showToast('کد آزمون کپی شد!', 'success');
            }).catch(() => {
                showToast('خطا در کپی کردن کد', 'error');
            });
        }
    }

    function archiveexam(examId) {
        event.preventDefault();
        event.stopPropagation();
        if (confirm('آیا از آرشیو کردن این آزمون اطمینان دارید؟')) {
            showToast('در حال آرشیو کردن آزمون...', 'info');
            // Add your archive logic here
        }
    }

    function toggleexamStatus(examId) {
        event.preventDefault();
        event.stopPropagation();
        showToast('در حال تغییر وضعیت آزمون...', 'info');
        // Add your toggle status logic here
    }

    // ============================================
    // TOAST NOTIFICATION
    // ============================================
    
    function showToast(message, type = 'info') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        
        // Add styles inline for reliability
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: ${type === 'error' ? '#f44336' : type === 'success' ? '#4CAF50' : '#2196F3'};
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            z-index: 10000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideUp 0.3s ease;
            direction: rtl;
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ============================================
    // FORM SUBMISSION HANDLING
    // ============================================
    
    document.getElementById('createexamForm')?.addEventListener('submit', function(e) {
        const submitButton = this.querySelector('.btn-submit');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ارسال...';
        submitButton.disabled = true;
        
        // Re-enable after submission (in case of error)
        setTimeout(() => {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }, 5000);
    });

    // ============================================
    // KEYBOARD SHORTCUTS
    // ============================================
    
    document.addEventListener('keydown', function(e) {
        // ESC key to close modal
        if (e.key === 'Escape') {
            const modal = document.getElementById('createexamModal');
            if (modal.classList.contains('active')) {
                closeModal();
            }
        }
    });

    console.log('✅ exam management loaded successfully!');
</script>
@endsection