@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-courses.css')}}">
<style>
    /* استایل‌های جدید برای کارت‌ها */
    .course-card .course-info-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin: 6px 0 4px 0;
    }
    
    .course-card .course-info-badges .mini-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 2px 8px;
        background: #f1f3f5;
        border-radius: 12px;
        font-size: 10px;
        color: #495057;
        border: 1px solid #e9ecef;
    }
    
    .course-card .course-info-badges .mini-badge i {
        font-size: 10px;
        color: #1e6f9f;
    }
    
    .course-card .course-info-badges .mini-badge.teacher {
        background: #e3f2fd;
        border-color: #bbdefb;
    }
    
    .course-card .course-info-badges .mini-badge.teacher i {
        color: #0d47a1;
    }
    
    .course-card .course-info-badges .mini-badge.duration {
        background: #e8f5e9;
        border-color: #c8e6c9;
    }
    
    .course-card .course-info-badges .mini-badge.duration i {
        color: #2e7d32;
    }
    
    .course-card .course-info-badges .mini-badge.sessions {
        background: #fff3e0;
        border-color: #ffe0b2;
    }
    
    .course-card .course-info-badges .mini-badge.sessions i {
        color: #e65100;
    }
    
    .course-card .course-description {
        font-size: 11px;
        color: #6c757d;
        margin: 4px 0 6px 0;
        padding: 4px 8px;
        background: #f8f9fa;
        border-radius: 6px;
        border-right: 2px solid #1e6f9f;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.5;
        min-height: 32px;
    }
    
    .course-card .course-description.empty {
        color: #adb5bd;
        font-style: italic;
        border-right-color: #dee2e6;
    }
    
    .course-card .course-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 12px;
        margin-top: 4px;
    }
    
    .course-card .course-status.active {
        background: #e8f5e9;
        color: #2e7d32;
    }
    
    .course-card .course-status.ended {
        background: #fbe9e7;
        color: #d32f2f;
    }
    
    .course-card .course-status i {
        font-size: 8px;
    }
    
    .course-badge.active {
        background: #4CAF50;
        color: white;
    }
    
    .course-badge.inactive {
        background: #f44336;
        color: white;
    }
    
    @media (max-width: 768px) {
        .course-card .course-info-badges .mini-badge {
            font-size: 9px;
            padding: 1px 6px;
        }
        
        .course-card .course-description {
            font-size: 10px;
            padding: 3px 6px;
            min-height: 28px;
        }
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
        @php
            $isEnded = $cours->is_ended ?? 0;
        @endphp
        <div class="course-card">
            <a href="{{ route('view.coure.St', $cours->id) }}" class="course-link">
                <div class="course-image">
                    <img src="{{ asset('/files/icons/' . $cours->header . '.jpg') }}" alt="{{ $cours->name }}">
                    <div class="course-badge {{ $cours->archieve == 1 ? 'inactive' : 'active' }}">
                        {{ $cours->archieve == 1 ? 'غیر فعال' : 'فعال' }}
                    </div>
                </div>
                <div class="course-info">
                    <h3 class="course-title">{{ $cours->name }}</h3>
                    <p class="course-code">کد: {{ $cours->code }}</p>

                    {{-- وضعیت --}}
                    <span class="course-status {{ $isEnded ? 'ended' : 'active' }}">
                        <i class="fas fa-circle"></i>
                        {{ $isEnded ? 'خاتمه یافته' : 'در حال برگزاری' }}
                    </span>
                    
                    {{-- نمایش لینک کلاس مجازی --}}
                    @if(isset($cours->majazi))
                        @php
                            $baseUrl = 'https://testnn.malisan.ir/teacher/';
                            $cleanUrl = str_replace($baseUrl, '', $cours->majazi);
                        @endphp
                        <div class="text-center mt-2">
                            <a href="https://{{ $cleanUrl }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>
                                کلاس مجازی
                            </a>
                        </div>
                    @endif
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
            body: JSON.stringify({ 
                code: code,
                type: 'lesson'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // موفقیت
                showToast(data.message, 'success');
                closeJoinModal();
                
                // ریدایرکت به صفحه دوره
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
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
        }
    });

    console.log('✅ Course management loaded successfully!');
</script>
@endsection