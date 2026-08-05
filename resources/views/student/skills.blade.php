@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-courses.css')}}">
<style>
    /* ===== استایل‌های کارت ===== */
    .course-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    }

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

    /* ===== توضیحات ===== */
    .course-description-wrapper {
        margin: 4px 0 6px 0;
        position: relative;
    }

    .course-card .course-description {
        font-size: 11px;
        color: #495057;
        padding: 4px 10px;
        background: #f8f9fa;
        border-radius: 8px;
        border-right: 3px solid #1e6f9f;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.6;
        min-height: 34px;
        word-break: break-word;
        transition: all 0.2s;
    }

    .course-card .course-description.empty {
        color: #adb5bd;
        font-style: italic;
        border-right-color: #dee2e6;
        background: #f8f9fa;
    }

    .more-btn {
        display: none;
        background: none;
        border: none;
        color: #1e6f9f;
        font-size: 11px;
        cursor: pointer;
        padding: 2px 6px;
        margin-top: 2px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .more-btn:hover {
        color: #0d47a1;
        text-decoration: underline;
        transform: translateX(-2px);
    }

    .course-card .course-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        padding: 2px 10px;
        border-radius: 20px;
        margin-top: 4px;
        font-weight: 500;
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
        font-size: 7px;
    }
    
    .course-badge.active {
        background: #4CAF50;
        color: white;
    }
    
    .course-badge.inactive {
        background: #f44336;
        color: white;
    }

    /* ===== مودال عضویت ===== */
    #joinCourseModal .modal-container {
        border-radius: 20px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        overflow: hidden;
    }
    #joinCourseModal .modal-header {
        background: linear-gradient(135deg, #1a3a5c, #1e6f9f);
        padding: 18px 24px;
        border-bottom: none;
    }
    #joinCourseModal .modal-header h3 {
        color: #fff;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        font-size: 20px;
    }
    #joinCourseModal .modal-header h3::before {
        content: '\f0c0';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        color: #ffd966;
        font-size: 22px;
    }
    #joinCourseModal .modal-close {
        background: rgba(255,255,255,0.15);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.25s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #joinCourseModal .modal-close:hover {
        background: rgba(255,255,255,0.3);
        transform: rotate(90deg);
    }
    #joinCourseModal .modal-body {
        padding: 24px 28px 12px;
    }
    #joinCourseModal .modal-footer {
        padding: 12px 28px 24px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    #joinCourseModal .btn-submit {
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        border: none;
        color: #fff;
        padding: 10px 28px;
        border-radius: 40px;
        font-weight: 600;
        transition: all 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    #joinCourseModal .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(30,111,159,0.35);
    }
    #joinCourseModal .btn-cancel {
        background: #f1f3f5;
        border: none;
        padding: 10px 24px;
        border-radius: 40px;
        font-weight: 600;
        color: #495057;
        transition: all 0.25s;
        cursor: pointer;
    }
    #joinCourseModal .btn-cancel:hover {
        background: #e9ecef;
    }

    /* ===== مودال توضیحات (طراحی جدید) ===== */
    #descriptionModal .modal-container {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border-radius: 24px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
        max-width: 620px;
        width: 95%;
        animation: modalFadeIn 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        overflow: hidden;
    }

    @keyframes modalFadeIn {
        0% {
            opacity: 0;
            transform: scale(0.92) translateY(30px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    #descriptionModal .modal-header {
        background: linear-gradient(135deg, #1a3a5c, #1e6f9f);
        padding: 20px 28px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #descriptionModal .modal-header h3 {
        color: #fff;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
    }

    #descriptionModal .modal-header h3 i {
        color: #ffd966;
        font-size: 22px;
    }

    #descriptionModal .modal-close {
        background: rgba(255, 255, 255, 0.12);
        border: none;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #descriptionModal .modal-close:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: rotate(90deg);
    }

    #descriptionModal .modal-body {
        padding: 28px 32px 20px;
        max-height: 60vh;
        overflow-y: auto;
        color: #1e293b;
        font-size: 15px;
        line-height: 1.9;
        white-space: pre-wrap;
        word-break: break-word;
        background: transparent;
    }

    #descriptionModal .modal-body::-webkit-scrollbar {
        width: 6px;
    }
    #descriptionModal .modal-body::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.04);
        border-radius: 10px;
    }
    #descriptionModal .modal-body::-webkit-scrollbar-thumb {
        background: #1e6f9f;
        border-radius: 10px;
    }

    #descriptionModal .modal-footer {
        padding: 12px 32px 28px;
        border-top: 1px solid rgba(0, 0, 0, 0.04);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    #descriptionModal .modal-footer .btn-cancel {
        background: #f1f5f9;
        color: #334155;
        border: none;
        padding: 10px 32px;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    #descriptionModal .modal-footer .btn-cancel:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* ===== ریسپانسیو ===== */
    @media (max-width: 768px) {
        .course-card .course-info-badges .mini-badge {
            font-size: 9px;
            padding: 1px 6px;
        }
        .course-card .course-description {
            font-size: 10px;
            padding: 3px 8px;
            min-height: 28px;
        }
        #descriptionModal .modal-body {
            font-size: 14px;
            padding: 20px 20px 16px;
        }
        #descriptionModal .modal-header {
            padding: 16px 20px;
        }
        #descriptionModal .modal-header h3 {
            font-size: 17px;
        }
        #descriptionModal .modal-footer {
            padding: 12px 20px 20px;
        }
        #descriptionModal .modal-footer .btn-cancel {
            padding: 8px 24px;
            font-size: 13px;
        }
        #joinCourseModal .modal-body {
            padding: 18px 20px 8px;
        }
        #joinCourseModal .modal-footer {
            padding: 8px 20px 18px;
        }
    }
</style>
@endsection

@section('mohtava')
<div class="content-header">
    <button class="courses-btn active">
        <i class="fas fa-book"></i>
        <span>مهارت ها</span>
    </button>

    <!-- دکمه عضویت در مهارت -->
    <button class="btn-join-course" onclick="openJoinModal()" style="margin-right: auto;">
        <i class="fas fa-user-plus"></i>
        عضویت در مهارت
    </button>
</div>

<div class="courses-grid">
    @forelse ($skills as $skill)
        @php
            $teacher = $skill->teachers()->first();
            $teacherName = $teacher ? $teacher->name : 'نامشخص';
            $isEnded = $skill->is_ended ?? 0;
        @endphp
        <div class="course-card">
            <a href="{{ route('view.skill.St', $skill->id) }}" class="course-link">
                <div class="course-image">
                    <img src="{{ asset('/files/icons/' . $skill->header . '.jpg') }}" alt="{{ $skill->name }}">
                    <div class="course-badge {{ $skill->archieve == 1 ? 'inactive' : 'active' }}">
                        {{ $skill->archieve == 1 ? 'غیر فعال' : 'فعال' }}
                    </div>
                </div>
                <div class="course-info">
                    <h3 class="course-title">{{ $skill->name }}</h3>
                    <p class="course-code">کد: {{ $skill->code }}</p>
                    
                    {{-- بج‌های اطلاعات --}}
                    <div class="course-info-badges">
                        <span class="mini-badge teacher">
                            <i class="fas fa-chalkboard-teacher"></i>
                            {{ $teacherName }}
                        </span>
                        <span class="mini-badge duration">
                            <i class="fas fa-clock"></i>
                            {{ $skill->length ?? '-' }} روز
                        </span>
                        <span class="mini-badge sessions">
                            <i class="fas fa-video"></i>
                            {{ $skill->sessions_length ?? '-' }} جلسه
                        </span>
                    </div>
                    
                    {{-- توضیحات با دکمه بیشتر --}}
                    <div class="course-description-wrapper">
                        @if(!empty($skill->desc))
                            <div class="course-description" data-full="{{ $skill->desc }}">
                                {{ $skill->desc }}
                            </div>
                            <button class="more-btn" data-name="{{ $skill->name }}" data-text="{{ $skill->desc }}">
                                بیشتر
                            </button>
                        @else
                            <div class="course-description empty">
                                <i class="fas fa-edit"></i> توضیحاتی ثبت نشده است
                            </div>
                        @endif
                    </div>
                    
                    {{-- وضعیت --}}
                    <span class="course-status {{ $isEnded ? 'ended' : 'active' }}">
                        <i class="fas fa-circle"></i>
                        {{ $isEnded ? 'خاتمه یافته' : 'در حال برگزاری' }}
                    </span>
                    
                    {{-- نمایش لینک مهارت مجازی --}}
                    @if(isset($skill->majazi))
                        @php
                            $baseUrl = 'https://testnn.malisan.ir/teacher/';
                            $cleanUrl = str_replace($baseUrl, '', $skill->majazi);
                        @endphp
                        <div class="text-center mt-2">
                            <a href="https://{{ $cleanUrl }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>
                                مهارت مجازی
                            </a>
                        </div>
                    @endif
                </div>
            </a>
        </div>
    @empty
        <div class="empty-state text-center">
            <i class="fas fa-book-open"></i>
            <p>شما هیچ مهارتی ندارید</p>
        </div>
    @endforelse
</div>

<!-- ============================================
     مودال عضویت در مهارت (Join Skill)
     ============================================ -->
<div class="modal-overlay" id="joinCourseModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3>عضویت در مهارت</h3>
            <button class="modal-close" onclick="closeJoinModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="form-group">
                <label for="courseCode">
                    کد مهارت <span class="required">*</span>
                </label>
                <input type="text" id="courseCode" name="code" class="form-control" 
                       placeholder="کد مهارت را وارد کنید" maxlength="10" autofocus>
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

<!-- ============================================
     مودال نمایش کامل توضیحات (طراحی جدید)
     ============================================ -->
<div class="modal-overlay" id="descriptionModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3>
                <i class="fas fa-align-left"></i>
                <span id="descModalTitle">توضیحات مهارت</span>
            </h3>
            <button class="modal-close" onclick="closeDescriptionModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="descModalBody">
            <!-- متن کامل توضیحات در اینجا قرار می‌گیرد -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeDescriptionModal()">
                <i class="fas fa-check"></i> متوجه شدم
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
        
        if (!code) {
            errorEl.textContent = 'لطفاً کد مهارت را وارد کنید';
            errorEl.classList.add('show');
            return;
        }
        
        if (code.length > 10) {
            errorEl.textContent = 'کد مهارت نباید بیشتر از ۱۰ کاراکتر باشد';
            errorEl.classList.add('show');
            return;
        }
        
        errorEl.classList.remove('show');
        errorEl.textContent = '';
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> در حال عضویت...';
        
        fetch('{{ route("join.skill") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                code: code,
                type: 'skill'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                closeJoinModal();
                
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
            } else {
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
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> <span>عضویت</span>';
        });
    }

    // ============================================
    // DESCRIPTION MODAL FUNCTIONS
    // ============================================
    
    function openDescriptionModal(title, text) {
        document.getElementById('descModalTitle').textContent = title || 'توضیحات مهارت';
        document.getElementById('descModalBody').textContent = text || 'متن توضیحات موجود نیست.';
        document.getElementById('descriptionModal').classList.add('active');
    }

    function closeDescriptionModal() {
        document.getElementById('descriptionModal').classList.remove('active');
    }

    // کلیک روی overlay مودال توضیحات برای بستن
    document.getElementById('descriptionModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDescriptionModal();
        }
    });

    // ============================================
    // DETECT OVERFLOW & SHOW "بیشتر" BUTTON
    // ============================================
    
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.course-description-wrapper').forEach(function(wrapper) {
            const desc = wrapper.querySelector('.course-description');
            const moreBtn = wrapper.querySelector('.more-btn');
            
            if (desc && moreBtn && !desc.classList.contains('empty')) {
                requestAnimationFrame(function() {
                    if (desc.scrollHeight > desc.clientHeight) {
                        moreBtn.style.display = 'inline-block';
                    }
                });
            }
        });
    });

    // ============================================
    // CLICK HANDLER FOR "بیشتر" BUTTONS (Event Delegation)
    // ============================================
    
    document.addEventListener('click', function(e) {
        const target = e.target.closest('.more-btn');
        if (target) {
            e.preventDefault();
            const name = target.getAttribute('data-name') || 'توضیحات مهارت';
            const text = target.getAttribute('data-text') || '';
            openDescriptionModal(name, text);
        }
    });

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
            const descModal = document.getElementById('descriptionModal');
            if (descModal.classList.contains('active')) {
                closeDescriptionModal();
            }
        }
    });

    console.log('✅ Skill management loaded successfully!');
</script>
@endsection