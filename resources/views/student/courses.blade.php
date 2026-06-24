@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-courses.css')}}">
<style>
    .btn-join-course {
        padding: 10px 20px;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-join-course:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(40, 167, 69, 0.3);
        color: #fff;
    }

    .btn-join-course i {
        font-size: 16px;
    }

    .empty-state {
        width: 80vw;
        margin-top: 30vh;
        text-align: center;
    }
</style>
@endsection

@section('mohtava')
<div class="content-header">
    <button class="courses-btn active">
        <i class="fas fa-book"></i>
        <span>درس‌ها</span>
    </button>

    <!-- دکمه عضویت در کلاس -->
    <button class="btn-join-course" onclick="openJoinModal()" style="margin-right: auto;">
        <i class="fas fa-user-plus"></i>
        عضویت در کلاس
    </button>
</div>

<div class="courses-grid">
    @forelse ($courses as $cours)
        <div class="course-card">
            <a href="{{ route('view.coure.St',$cours->id)}}" class="course-link">
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
        </div>
    @empty
        <div class="empty-state text-center">
            <i class="fas fa-book-open"></i>
            <p>شما هیچ درسی ندارید</p>
        </div>
    @endforelse
</div>

<!-- ============================================
     مودال عضویت در کلاس (Join Course)
     ============================================ -->
<div class="modal-overlay" id="joinCourseModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3>عضویت در کلاس</h3>
            <button class="modal-close" onclick="closeJoinModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="form-group">
                <label for="courseCode">
                    کد درس <span class="required">*</span>
                </label>
                <input type="text" id="courseCode" name="code" class="form-control" 
                       placeholder="کد درس را وارد کنید" maxlength="10" autofocus>
                <div class="error-message" id="codeError"></div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeJoinModal()">انصراف</button>
            <button type="button" class="btn-submit" id="joinSubmitBtn" onclick="submitJoin()">
                <i class="fas fa-user-plus"></i>
                <span>عضویت</span>
            </button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // ============================================
    // JOIN MODAL FUNCTIONS
    // ============================================
    
    function openJoinModal() {
        const modal = document.getElementById('joinCourseModal');
        const input = document.getElementById('courseCode');
        const error = document.getElementById('codeError');
        
        input.value = '';
        error.classList.remove('show');
        error.textContent = '';
        modal.classList.add('active');
        
        setTimeout(() => input.focus(), 300);
    }

    function closeJoinModal() {
        document.getElementById('joinCourseModal').classList.remove('active');
        document.getElementById('courseCode').value = '';
        document.getElementById('codeError').classList.remove('show');
    }

    document.getElementById('joinCourseModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeJoinModal();
        }
    });

    // Enter key در مودال عضویت
    document.getElementById('courseCode')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitJoin();
        }
    });

    // ============================================
    // SUBMIT JOIN
    // ============================================
    
    function submitJoin() {
        const code = document.getElementById('courseCode').value.trim();
        const errorEl = document.getElementById('codeError');
        const submitBtn = document.getElementById('joinSubmitBtn');
        
        // اعتبارسنجی سمت کلاینت
        if (!code) {
            errorEl.textContent = 'لطفاً کد درس را وارد کنید';
            errorEl.classList.add('show');
            return;
        }
        
        if (code.length > 10) {
            errorEl.textContent = 'کد درس نباید بیشتر از ۱۰ کاراکتر باشد';
            errorEl.classList.add('show');
            return;
        }
        
        // پاک کردن خطا
        errorEl.classList.remove('show');
        errorEl.textContent = '';
        
        // غیرفعال کردن دکمه
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> در حال عضویت...';
        
        // ارسال درخواست
        fetch('{{ route("join.course") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // موفقیت
                showToast(data.message, 'success');
                closeJoinModal();
                
                // ریدایرکت به صفحه دوره
                setTimeout(() => {
                    window.location.href = data.redirect || '/dashboard';
                }, 1500);
            } else {
                // خطا
                showToast(data.message, 'error');
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorEl.textContent = errors[0];
                    errorEl.classList.add('show');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('خطا در ارتباط با سرور', 'error');
        })
        .finally(() => {
            // فعال کردن دکمه
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> <span>عضویت</span>';
        });
    }

    // ============================================
    // CREATE MODAL FUNCTIONS (همان کد قبلی)
    // ============================================
    
    function openCreateModal(copyId = null) {
        const modal = document.getElementById('createCourseModal');
        const modalTitle = document.getElementById('modalTitle');
        const submitButtonText = document.getElementById('submitButtonText');
        
        if (copyId) {
            modalTitle.textContent = 'کپی درس';
            submitButtonText.textContent = 'کپی درس';
            document.getElementById('copyCourseId').value = copyId;
            
            showToast('در حال بارگذاری اطلاعات درس...', 'info');
            
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
                    
                    document.getElementById('name').value = data.name;
                    document.getElementById('majazi').value = data.majazi || '';
                    
                    modal.classList.add('active');
                    showToast('اطلاعات درس بارگذاری شد.', 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('خطا در بارگذاری اطلاعات درس', 'error');
                    modal.classList.add('active');
                });
        } else {
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
        }
    }

    function editCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        showToast('در حال ویرایش درس...', 'info');
    }

    function shareCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
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
        }
    }

    function toggleCourseStatus(courseId) {
        event.preventDefault();
        event.stopPropagation();
        showToast('در حال تغییر وضعیت درس...', 'info');
    }

    // ============================================
    // TOAST NOTIFICATION
    // ============================================
    
    function showToast(message, type = 'info') {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        
        const colors = {
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
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.4s';
            setTimeout(() => toast.remove(), 400);
        }, 3500);
    }

    // ============================================
    // KEYBOARD SHORTCUTS
    // ============================================
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const joinModal = document.getElementById('joinCourseModal');
            if (joinModal.classList.contains('active')) {
                closeJoinModal();
            }
            
            const createModal = document.getElementById('createCourseModal');
            if (createModal.classList.contains('active')) {
                closeModal();
            }
        }
    });

    // ============================================
    // FORM SUBMISSION HANDLING
    // ============================================
    
    document.getElementById('createCourseForm')?.addEventListener('submit', function(e) {
        const submitButton = this.querySelector('.btn-submit');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<span class="spinner"></span> در حال ارسال...';
        submitButton.disabled = true;
        
        setTimeout(() => {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }, 5000);
    });

    console.log('✅ Course management loaded successfully!');
</script>
@endsection