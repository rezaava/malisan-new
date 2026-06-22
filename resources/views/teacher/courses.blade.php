@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-courses.css')}}">
@endsection

@section('mohtava')
<div class="content-header">
    <button class="archive-btn">
        <i class="fas fa-archive"></i>
        <span>آرشیوها</span>
    </button>
    <button class="courses-btn active">
        <i class="fas fa-book"></i>
        <span>درس‌ها</span>
    </button>
    
    <!-- Create Course Button -->
    <button class="btn-create-course" onclick="openCreateModal()" style="margin-right: auto;">
        <i class="fas fa-plus"></i>
        ایجاد درس جدید
    </button>
</div>

<div class="courses-grid">
    @forelse ($courses as $cours)
        <div class="course-card">
            <a href="/courses/{{ $cours->id }}" class="course-link">
                <div class="course-image">
                    <img src="{{ asset('images/course-default.jpg') }}" alt="{{ $cours->name }}">
                    <div class="course-badge">
                        @if ($cours->archieve == 1)
                            غیر فعال
                        @else
                            فعال
                        @endif
                    </div>
                </div>
                <div class="course-info">
                    <h3 class="course-title">{{ $cours->name }}</h3>
                    <p class="course-code">کد: {{ $cours->code }}</p>
                </div>
            </a>
            <div class="course-actions">
                <div class="action-item" data-action="حذف" onclick="event.preventDefault(); event.stopPropagation(); deleteCourse({{ $cours->id }})">
                    <i class="fas fa-trash-alt"></i>
                    <span class="action-tooltip">حذف</span>
                </div>
                <div class="action-item" data-action="ویرایش" onclick="event.preventDefault(); event.stopPropagation(); editCourse({{ $cours->id }})">
                    <i class="fas fa-edit"></i>
                    <span class="action-tooltip">ویرایش</span>
                </div>
                <div class="action-item" data-action="اشتراک گذاری" onclick="event.preventDefault(); event.stopPropagation(); shareCourse({{ $cours->id }})">
                    <i class="fas fa-share-alt"></i>
                    <span class="action-tooltip">اشتراک گذاری</span>
                </div>
                <div class="action-item" data-action="کپی" onclick="copyCourse({{ $cours->id }})">
                    <i class="fas fa-copy"></i>
                    <span class="action-tooltip">کپی</span>
                </div>
                <div class="action-item" data-action="آرشیو" onclick="event.preventDefault(); event.stopPropagation(); archiveCourse({{ $cours->id }})">
                    <i class="fas fa-archive"></i>
                    <span class="action-tooltip">آرشیو</span>
                </div>
                <div class="action-item" data-action="فعال/غیرفعال" onclick="event.preventDefault(); event.stopPropagation(); toggleCourseStatus({{ $cours->id }})">
                    <i class="fas fa-toggle-on"></i>
                    <span class="action-tooltip">فعال/غیرفعال</span>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <p>شما هیچ درسی ندارید</p>
            <button class="btn-create-course" onclick="openCreateModal()">
                <i class="fas fa-plus"></i>
                اولین درس خود را ایجاد کنید
            </button>
        </div>
    @endforelse
</div>

<!-- Modal -->
<div class="modal-overlay" id="createCourseModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="modalTitle">ایجاد درس جدید</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="createCourseForm" action="{{ route('courses.store') }}" method="POST">
            @csrf
            <input type="hidden" name="copy" id="copyCourseId" value="">
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">عنوان درس <span class="required">*</span></label>
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
                    <span id="submitButtonText">ایجاد درس</span>
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
        const modal = document.getElementById('createCourseModal');
        const modalTitle = document.getElementById('modalTitle');
        const submitButtonText = document.getElementById('submitButtonText');
        
        if (copyId) {
            // Copy mode
            modalTitle.textContent = 'کپی درس';
            submitButtonText.textContent = 'کپی درس';
            document.getElementById('copyCourseId').value = copyId;
            
            // Show loading
            showToast('در حال بارگذاری اطلاعات درس...', 'info');
            
            // Fetch course data
            fetch(`/teacher/courses/copy/${copyId}`)
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
                    showToast('اطلاعات درس بارگذاری شد. نام درس را ویرایش کنید.', 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('خطا در بارگذاری اطلاعات درس', 'error');
                    // Still open modal empty
                    modal.classList.add('active');
                });
        } else {
            // New course mode
            modalTitle.textContent = 'ایجاد درس جدید';
            submitButtonText.textContent = 'ایجاد درس';
            document.getElementById('name').value = '';
            document.getElementById('majazi').value = '';
            document.getElementById('copyCourseId').value = '';
            modal.classList.add('active');
        }
    }

    function closeModal() {
        const modal = document.getElementById('createCourseModal');
        modal.classList.remove('active');
        document.getElementById('createCourseForm').reset();
        document.getElementById('copyCourseId').value = '';
    }

    // Close modal on overlay click
    document.getElementById('createCourseModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // ============================================
    // COURSE ACTIONS
    // ============================================
    
    function copyCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        openCreateModal(courseId);
    }

    function deleteCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        if (confirm('آیا از حذف این درس اطمینان دارید؟')) {
            showToast('در حال حذف درس...', 'info');
            // Add your delete logic here
        }
    }

    function editCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        showToast('در حال ویرایش درس...', 'info');
        // Add your edit logic here
    }

    function shareCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        // Get the course code
        const courseCode = prompt('کد درس را برای اشتراک گذاری کپی کنید:');
        if (courseCode) {
            navigator.clipboard.writeText(courseCode).then(() => {
                showToast('کد درس کپی شد!', 'success');
            }).catch(() => {
                showToast('خطا در کپی کردن کد', 'error');
            });
        }
    }

    function archiveCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        if (confirm('آیا از آرشیو کردن این درس اطمینان دارید؟')) {
            showToast('در حال آرشیو کردن درس...', 'info');
            // Add your archive logic here
        }
    }

    function toggleCourseStatus(courseId) {
        event.preventDefault();
        event.stopPropagation();
        showToast('در حال تغییر وضعیت درس...', 'info');
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
    
    document.getElementById('createCourseForm')?.addEventListener('submit', function(e) {
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
            const modal = document.getElementById('createCourseModal');
            if (modal.classList.contains('active')) {
                closeModal();
            }
        }
    });

    console.log('✅ Course management loaded successfully!');
</script>
@endsection