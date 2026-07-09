@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-courses.css')}}">
<style>
    /* استایل برای وضعیت درس */
    .course-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-top: 4px;
    }
    
    .course-status-active {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #a5d6a7;
    }
    
    .course-status-active i {
        color: #43a047;
        font-size: 10px;
    }
    
    .course-status-inactive {
        background: #f5f5f5;
        color: #757575;
        border: 1px solid #e0e0e0;
    }
    
    .course-status-inactive i {
        color: #9e9e9e;
        font-size: 10px;
    }
    
    /* کارت در حالت غیرفعال (خاتمه یافته) */
    .course-card.inactive {
        background: #f8f9fa !important;
        border-color: #e0e0e0 !important;
        opacity: 0.85;
    }
    
    .course-card.inactive .course-title {
        color: #6c757d !important;
    }
    
    .course-card.inactive .course-info {
        background: #f1f3f5 !important;
    }
    
    .course-card.inactive .course-image {
        filter: grayscale(0.3);
    }
    
    .course-card.inactive .action-item {
        opacity: 0.7;
    }
    
    .course-card.inactive .action-item:hover {
        opacity: 1;
    }
    
    /* استایل badge جدید */
    .course-card .course-image .course-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: #fff;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .course-card .course-image .course-badge.badge-active {
        background: rgba(76, 175, 80, 0.9);
    }
    
    .course-card .course-image .course-badge.badge-inactive {
        background: rgba(108, 117, 125, 0.9);
    }
    
    .course-card .course-image {
        position: relative;
    }

    /* انیمیشن برای به‌روزرسانی کارت */
    @keyframes updatePulse {
        0% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(76, 175, 80, 0); }
        100% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0); }
    }
    
    .course-card.updating {
        animation: updatePulse 0.8s ease;
    }
</style>
@endsection

@section('mohtava')
<div class="content-header">
    <button class="courses-btn">
        <i class="fas fa-book"></i>
        <span>درس‌ها</span>
    </button>
    <!-- Create Course Button -->
    <button class="btn-create-course m-0" onclick="openCreateModal()">
        <i class="fas fa-plus"></i>
        ایجاد درس جدید
    </button>
    <button class="archive-btn me-auto" id="archiveBtn">
        <i class="fas fa-archive"></i>
        <span>آرشیوها</span>
        <span class="archived-count" id="archivedCountBadge">0</span>
    </button>
</div>

<div class="courses-grid" id="coursesGrid">
    @forelse ($courses as $cours)
        @php
            // تعیین وضعیت بر اساس private: 1 = خاتمه یافته، 0 = در حال برگزاری
            $isActive = ($cours->private == 0);
        @endphp
        <div class="course-card {{ $isActive ? '' : 'inactive' }}" data-course-id="{{ $cours->id }}">
            <a href="{{ route('view.coure',$cours->id)}}" class="course-link">
                <div class="course-image">
                    <img src="{{ asset('/files/icons/' . $cours->header . '.jpg') }}" alt="{{ $cours->name }}">
                    <div class="course-badge {{ $isActive ? 'badge-active' : 'badge-inactive' }}">
                        <i class="fas {{ $isActive ? 'fa-play-circle' : 'fa-stop-circle' }}"></i>
                        {{ $isActive ? 'در حال برگزاری' : 'خاتمه یافته' }}
                    </div>
                </div>
                <div class="course-info">
                    <h3 class="course-title">{{ $cours->name }}</h3>
                    <p class="course-code">کد: {{ $cours->code }}</p>
                    
                    <!-- نمایش وضعیت به صورت متن زیر عنوان -->
                    <span class="course-status-badge {{ $isActive ? 'course-status-active' : 'course-status-inactive' }}">
                        <i class="fas fa-circle"></i>
                        {{ $isActive ? '● در حال برگزاری' : '● خاتمه یافته' }}
                    </span>
                    @if(isset($cours->majazi))
                        @php
                            $baseUrl = 'https://testnn.malisan.ir/teacher/';
                            $cleanUrl = str_replace($baseUrl, '', $cours->majazi);
                        @endphp
                        <div class="text-center mt-4 virtual-class-container">
                            <a href="https://{{ $cleanUrl }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="btn btn-primary btn-sm virtual-class-link">
                                <i class="fas fa-arrow-left me-2"></i>
                                کلاس مجازی
                            </a>
                        </div>
                    @endif
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
                    <i class="fas {{ $isActive ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                    <span class="action-tooltip">فعال/غیرفعال</span>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <p>شما هیچ درسی ندارید</p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- مودال‌ها -->
<!-- ========================================== -->

<!-- مودال ایجاد درس -->
<div class="modal-overlay" id="createCourseModal">
    <div class="modal-container">
        <div class="modal-header" style="background: linear-gradient(135deg, #1e6f9f, #155a82);">
            <h3 id="modalTitle" style="color:#fff;">ایجاد درس جدید</h3>
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

<!-- مودال آرشیو -->
<div class="modal-overlay" id="archivedModalOverlay">
    <div class="modal-container" style="max-width: 700px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #6c757d, #495057);">
            <h4><i class="fas fa-archive"></i> دوره‌های آرشیو شده</h4>
            <button class="modal-close" onclick="closeArchivedModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="archivedCoursesList">
                <div class="text-center" style="padding:20px;">
                    <i class="fas fa-spinner fa-spin" style="font-size:24px;color:#1e6f9f;"></i>
                    <p>در حال بارگذاری...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال ویرایش درس -->
<div class="modal-overlay" id="editCourseModal">
    <div class="modal-container" style="max-width: 550px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #ff9800, #e65100);">
            <h3 style="color:#fff;"><i class="fas fa-edit"></i> ویرایش درس</h3>
            <button class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editCourseForm">
            @csrf
            <input type="hidden" name="course_id" id="editCourseId" value="">
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_name">عنوان درس <span class="required">*</span></label>
                    <input type="text" id="edit_name" name="name" required class="form-control" 
                           placeholder="مثال: ریاضیات پایه">
                </div>
                
                <div class="form-group">
                    <label for="edit_majazi">لینک کلاس مجازی (اختیاری)</label>
                    <input type="url" id="edit_majazi" name="majazi" class="form-control" 
                           placeholder="https://example.com/class">
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">انصراف</button>
                <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #ff9800, #e65100);">
                    <i class="fas fa-save"></i>
                    <span id="editSubmitText">ذخیره تغییرات</span>
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
    // مودال آرشیو
    // ============================================
    
    document.addEventListener('DOMContentLoaded', function() {
        var archiveBtn = document.getElementById('archiveBtn');
        if (archiveBtn) {
            archiveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openArchivedModal();
            });
        }

        var modal = document.getElementById('archivedModalOverlay');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeArchivedModal();
                }
            });
        }

        loadArchivedCount();
    });

    function openArchivedModal() {
        var modal = document.getElementById('archivedModalOverlay');
        if (!modal) {
            console.error('Modal not found!');
            showToast('خطا: مودال آرشیو پیدا نشد', 'error');
            return;
        }
        
        modal.classList.add('active');
        loadArchivedCourses();
    }

    function closeArchivedModal() {
        var modal = document.getElementById('archivedModalOverlay');
        if (modal) {
            modal.classList.remove('active');
        }
    }

    function loadArchivedCourses() {
        var container = document.getElementById('archivedCoursesList');
        if (!container) return;
        
        container.innerHTML = '<div class="text-center" style="padding:20px;"><i class="fas fa-spinner fa-spin" style="font-size:24px;color:#1e6f9f;"></i><p>در حال بارگذاری...</p></div>';

        fetch('/teacher/courses/archived')
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function(data) {
                if (!data.success) {
                    container.innerHTML = `
                        <div class="empty-archived">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>${data.message || 'خطا در بارگذاری اطلاعات'}</p>
                        </div>
                    `;
                    return;
                }

                if (!data.data || data.data.length === 0) {
                    container.innerHTML = `
                        <div class="empty-archived">
                            <i class="fas fa-box-open"></i>
                            <p>هیچ دوره‌ای آرشیو نشده است</p>
                        </div>
                    `;
                    return;
                }

                var html = `
                    <div style="margin-bottom:16px;padding:12px 16px;background:#f8f9fa;border-radius:10px;border-right:3px solid #6c757d;">
                        <span style="font-weight:700;color:#495057;">تعداد دوره‌های آرشیو شده: ${data.data.length}</span>
                    </div>
                `;

                data.data.forEach(function(course, index) {
                    var date = new Date(course.updated_at);
                    var persianDate = date.toLocaleDateString('fa-IR');
                    
                    html += `
                        <div class="archived-list-item">
                            <div class="course-info">
                                <span class="course-name">${index + 1}. ${course.name}</span>
                                <span class="course-code">کد: ${course.code}</span>
                                <span class="archived-date">آرشیو شده در: ${persianDate}</span>
                            </div>
                            <button class="restore-btn" onclick="restoreCourse(${course.id})">
                                <i class="fas fa-undo"></i>
                                بازگرداندن
                            </button>
                        </div>
                    `;
                });

                container.innerHTML = html;

                var badge = document.getElementById('archivedCountBadge');
                if (badge) {
                    badge.textContent = data.data.length;
                }

            })
            .catch(function(error) {
                console.error('Error:', error);
                container.innerHTML = `
                    <div class="empty-archived">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>خطا در ارتباط با سرور</p>
                    </div>
                `;
            });
    }

    function loadArchivedCount() {
        fetch('/teacher/courses/archived')
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    var badge = document.getElementById('archivedCountBadge');
                    if (badge) {
                        badge.textContent = data.data ? data.data.length : 0;
                    }
                }
            })
            .catch(function(error) {
                console.error('Error loading archived count:', error);
            });
    }

    function restoreCourse(courseId) {
        if (!confirm('آیا از بازگرداندن این دوره از آرشیو اطمینان دارید؟')) {
            return;
        }
        
        var btns = document.querySelectorAll('.restore-btn');
        var targetBtn = null;
        btns.forEach(function(btn) {
            var onclickAttr = btn.getAttribute('onclick');
            if (onclickAttr && onclickAttr.includes('restoreCourse(' + courseId + ')')) {
                targetBtn = btn;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                btn.disabled = true;
            }
        });
        
        fetch('/teacher/courses/toggle-archive/' + courseId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                showToast(data.message, 'success');
                loadArchivedCourses();
                setTimeout(function() {
                    location.reload();
                }, 2000);
            } else {
                showToast(data.message, 'error');
                if (targetBtn) {
                    targetBtn.innerHTML = '<i class="fas fa-undo"></i> بازگرداندن';
                    targetBtn.disabled = false;
                }
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            showToast('خطا در ارتباط با سرور', 'error');
            if (targetBtn) {
                targetBtn.innerHTML = '<i class="fas fa-undo"></i> بازگرداندن';
                targetBtn.disabled = false;
            }
        });
    }

    // ============================================
    // COURSE ACTIONS
    // ============================================
    
    function copyCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        openCreateModal(courseId);
    }

    // ============================================
    // ویرایش درس - نسخه Ajax
    // ============================================

    function editCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        
        // نمایش وضعیت بارگذاری
        showToast('در حال دریافت اطلاعات درس...', 'info');
        
        // دریافت اطلاعات درس از سرور
        fetch(`/teacher/courses/${courseId}/edit-data`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'خطا در دریافت اطلاعات درس');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // پر کردن فرم با اطلاعات دریافتی
                document.getElementById('edit_name').value = data.course.name;
                document.getElementById('edit_majazi').value = data.course.majazi || '';
                document.getElementById('editCourseId').value = courseId;
                
                // نمایش مودال
                const modal = document.getElementById('editCourseModal');
                modal.classList.add('active');
                showToast('اطلاعات درس بارگذاری شد', 'success');
            } else {
                showToast(data.message || 'خطا در دریافت اطلاعات درس', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'خطا در ارتباط با سرور', 'error');
        });
    }

    function closeEditModal() {
        const modal = document.getElementById('editCourseModal');
        if (modal) {
            modal.classList.remove('active');
            document.getElementById('editCourseForm').reset();
        }
    }

    document.getElementById('editCourseModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // ============================================
    // ارسال فرم ویرایش به صورت Ajax
    // ============================================

    document.getElementById('editCourseForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const courseId = document.getElementById('editCourseId').value;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('.btn-submit');
        const originalText = submitBtn.innerHTML;
        
        // بررسی اعتبار فرم
        const name = document.getElementById('edit_name').value.trim();
        if (!name) {
            showToast('لطفاً عنوان درس را وارد کنید', 'error');
            return;
        }
        
        // نمایش وضعیت در حال ارسال
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> در حال ذخیره...';
        submitBtn.disabled = true;
        
        fetch(`/teacher/courses/${courseId}`, {
            method: 'POST', // استفاده از POST با _method=PUT
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'خطا در ویرایش درس');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message || 'درس با موفقیت ویرایش شد', 'success');
                
                // به‌روزرسانی کارت درس در صفحه
                updateCourseCard(courseId, data.course);
                
                // بستن مودال
                closeEditModal();
            } else {
                // نمایش خطاهای اعتبارسنجی
                if (data.errors) {
                    let errorMessages = '';
                    for (let field in data.errors) {
                        errorMessages += data.errors[field].join('\n') + '\n';
                    }
                    showToast(errorMessages || data.message, 'error');
                } else {
                    showToast(data.message || 'خطا در ویرایش درس', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'خطا در ارتباط با سرور', 'error');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // ============================================
    // تابع به‌روزرسانی کارت درس
    // ============================================

    function updateCourseCard(courseId, courseData) {
        const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
        if (!card) {
            console.warn('Card not found for course:', courseId);
            return;
        }
        
        // افزودن انیمیشن به‌روزرسانی
        card.classList.add('updating');
        
        // به‌روزرسانی عنوان درس
        const title = card.querySelector('.course-title');
        if (title) title.textContent = courseData.name;
        
        // به‌روزرسانی لینک کلاس مجازی
        const virtualContainer = card.querySelector('.virtual-class-container');
        const virtualLink = card.querySelector('.virtual-class-link');
        
        if (courseData.majazi) {
            // اگر لینک مجازی وجود دارد
            const baseUrl = 'https://testnn.malisan.ir/teacher/';
            const cleanUrl = courseData.majazi.replace(baseUrl, '');
            
            if (virtualLink) {
                virtualLink.href = 'https://' + cleanUrl;
                if (virtualContainer) virtualContainer.style.display = 'block';
            } else {
                // اگر لینک وجود ندارد، یک لینک جدید ایجاد کن
                const newContainer = document.createElement('div');
                newContainer.className = 'text-center mt-4 virtual-class-container';
                newContainer.innerHTML = `
                    <a href="https://${cleanUrl}" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="btn btn-primary btn-sm virtual-class-link">
                        <i class="fas fa-arrow-left me-2"></i>
                        کلاس مجازی
                    </a>
                `;
                card.querySelector('.course-info').appendChild(newContainer);
            }
        } else {
            // اگر لینک مجازی وجود ندارد، آن را حذف کن
            if (virtualContainer) {
                virtualContainer.style.display = 'none';
            }
        }
        
        // حذف انیمیشن بعد از 1 ثانیه
        setTimeout(() => {
            card.classList.remove('updating');
        }, 1000);
        
        // نمایش پیام موفقیت
        showToast('✅ درس با موفقیت به‌روزرسانی شد', 'success');
    }

    // ============================================
    // حذف درس
    // ============================================

    function deleteCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'آیا از حذف این درس اطمینان دارید؟',
                text: 'این اقدام غیرقابل بازگشت است!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'بله، حذف شود',
                cancelButtonText: 'انصراف',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performDeleteCourse(courseId);
                }
            });
        } else {
            if (confirm('آیا از حذف این درس اطمینان دارید؟ این اقدام غیرقابل بازگشت است!')) {
                performDeleteCourse(courseId);
            }
        }
    }

    function performDeleteCourse(courseId) {
        const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
        const deleteBtn = card?.querySelector('.action-item[data-action="حذف"]');
        if (deleteBtn) {
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            deleteBtn.style.pointerEvents = 'none';
        }
        
        fetch(`/teacher/courses/${courseId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'خطا در حذف درس');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message || 'درس با موفقیت حذف شد', 'success');
                
                if (card) {
                    card.style.transition = 'all 0.5s ease';
                    card.style.transform = 'scale(0.8)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                    }, 500);
                }
            } else {
                showToast(data.message || 'خطا در حذف درس', 'error');
                if (deleteBtn) {
                    deleteBtn.innerHTML = '<i class="fas fa-trash-alt"></i><span class="action-tooltip">حذف</span>';
                    deleteBtn.style.pointerEvents = 'auto';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'خطا در ارتباط با سرور', 'error');
            if (deleteBtn) {
                deleteBtn.innerHTML = '<i class="fas fa-trash-alt"></i><span class="action-tooltip">حذف</span>';
                deleteBtn.style.pointerEvents = 'auto';
            }
        });
    }

    // ============================================
    // اشتراک گذاری
    // ============================================

    function shareCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        
        const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
        if (!card) {
            showToast('خطا: اطلاعات درس پیدا نشد', 'error');
            return;
        }
        
        const courseName = card.querySelector('.course-title')?.textContent || '';
        const courseCode = card.querySelector('.course-code')?.textContent?.replace('کد: ', '') || '';
        
        const message = `دانشجوی عزیز، برای دسترسی به درس ${courseName} ابتدا از طریق سایت WWW.MALISAN.IR در سامانه آموزشی ملیسان با هویت واقعی ثبت نام کنید، سپس با استفاده از شناسه ${courseCode} در درس ذکر شده عضو شوید.`;
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'اشتراک گذاری درس',
                html: `
                    <div style="text-align: right; direction: rtl;">
                        <p style="color: #6c757d; font-size: 14px; margin-bottom: 16px;">
                            برای دعوت دانشجویان خود به کلاس می‌توانید آن را از طریق شبکه‌های اجتماعی یا پیامک برایشان ارسال کنید.
                        </p>
                        <div style="background: #f8f9fa; padding: 16px; border-radius: 10px; border-right: 4px solid #6f42c1; text-align: right;">
                            <p style="margin: 0; font-size: 14px; line-height: 2; color: #212529;">
                                ${message}
                            </p>
                        </div>
                        <div style="margin-top: 16px; display: flex; gap: 12px; justify-content: center;">
                            <button onclick="copyText('${message.replace(/'/g, "\\'")}')" class="swal2-confirm swal2-styled" style="background: #6f42c1;">
                                <i class="fas fa-copy"></i> کپی پیام
                            </button>
                            <button onclick="copyText('https://www.malisan.ir/join/${courseCode}')" class="swal2-confirm swal2-styled" style="background: #0d6efd;">
                                <i class="fas fa-link"></i> کپی لینک
                            </button>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'بستن',
                cancelButtonColor: '#6c757d',
                width: 600,
                customClass: {
                    popup: 'swal-rtl'
                }
            });
        } else {
            showToast(message, 'info');
            setTimeout(() => {
                if (confirm('آیا می‌خواهید پیام را کپی کنید؟')) {
                    copyText(message);
                }
            }, 2000);
        }
    }

    function copyText(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('✅ متن با موفقیت کپی شد!', 'success');
            }).catch(() => {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    }

    function fallbackCopy(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.opacity = '0';
        textArea.style.left = '-9999px';
        textArea.style.top = '-9999px';
        document.body.appendChild(textArea);
        textArea.select();
        
        try {
            document.execCommand('copy');
            showToast('✅ متن با موفقیت کپی شد!', 'success');
        } catch (err) {
            showToast('❌ خطا در کپی کردن متن', 'error');
            console.error('Fallback copy failed:', err);
        }
        
        document.body.removeChild(textArea);
    }

    // ============================================
    // آرشیو و تغییر وضعیت
    // ============================================

    function archiveCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        
        if (!confirm('آیا از آرشیو کردن این درس اطمینان دارید؟')) {
            return;
        }
        
        const targetBtn = event?.currentTarget;
        if (targetBtn) {
            targetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            targetBtn.style.pointerEvents = 'none';
        }
        
        fetch(`/teacher/courses/toggle-archive/${courseId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                
                const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
                if (card) {
                    card.style.transition = 'opacity 0.5s';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                    }, 500);
                }
                
                const badge = document.getElementById('archivedCountBadge');
                if (badge) {
                    const current = parseInt(badge.textContent) || 0;
                    badge.textContent = current + 1;
                }
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('خطا در ارتباط با سرور', 'error');
        })
        .finally(() => {
            if (targetBtn) {
                targetBtn.innerHTML = `
                    <i class="fas fa-archive"></i>
                    <span class="action-tooltip">آرشیو</span>
                `;
                targetBtn.style.pointerEvents = 'auto';
            }
        });
    }

    function toggleCourseStatus(courseId) {
        event.preventDefault();
        event.stopPropagation();
        
        if (!confirm('آیا از تغییر وضعیت این درس اطمینان دارید؟')) {
            return;
        }
        
        const targetBtn = event?.currentTarget;
        if (targetBtn) {
            targetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            targetBtn.style.pointerEvents = 'none';
        }
        
        fetch(`/teacher/courses/toggle-status/${courseId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                
                const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
                if (card) {
                    // تعیین وضعیت جدید بر اساس private
                    const isActive = (data.private === 0);
                    
                    // به‌روزرسانی کلاس inactive
                    if (isActive) {
                        card.classList.remove('inactive');
                    } else {
                        card.classList.add('inactive');
                    }
                    
                    // به‌روزرسانی Badge
                    const badge = card.querySelector('.course-badge');
                    if (badge) {
                        if (isActive) {
                            badge.className = 'course-badge badge-active';
                            badge.innerHTML = '<i class="fas fa-play-circle"></i> در حال برگزاری';
                        } else {
                            badge.className = 'course-badge badge-inactive';
                            badge.innerHTML = '<i class="fas fa-stop-circle"></i> خاتمه یافته';
                        }
                    }
                    
                    // به‌روزرسانی status badge زیر عنوان
                    const statusBadge = card.querySelector('.course-status-badge');
                    if (statusBadge) {
                        if (isActive) {
                            statusBadge.className = 'course-status-badge course-status-active';
                            statusBadge.innerHTML = '<i class="fas fa-circle"></i> ● در حال برگزاری';
                        } else {
                            statusBadge.className = 'course-status-badge course-status-inactive';
                            statusBadge.innerHTML = '<i class="fas fa-circle"></i> ● خاتمه یافته';
                        }
                    }
                }
                
                // به‌روزرسانی آیکون دکمه toggle
                const toggleBtns = document.querySelectorAll('.action-item[data-action="فعال/غیرفعال"]');
                toggleBtns.forEach(btn => {
                    if (btn.closest('.course-card')?.dataset?.courseId == courseId) {
                        const isActive = (data.private === 0);
                        btn.innerHTML = `
                            <i class="fas ${isActive ? 'fa-toggle-on' : 'fa-toggle-off'}"></i>
                            <span class="action-tooltip">فعال/غیرفعال</span>
                        `;
                    }
                });
                
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('خطا در ارتباط با سرور', 'error');
        })
        .finally(() => {
            if (targetBtn) {
                targetBtn.innerHTML = `
                    <i class="fas fa-toggle-on"></i>
                    <span class="action-tooltip">فعال/غیرفعال</span>
                `;
                targetBtn.style.pointerEvents = 'auto';
            }
        });
    }

    // ============================================
    // TOAST NOTIFICATION
    // ============================================
    
    function showToast(message, type = 'info') {
        var existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }
        
        var toast = document.createElement('div');
        toast.className = 'toast-notification';
        
        var colors = {
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
        
        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.4s';
            setTimeout(function() {
                toast.remove();
            }, 400);
        }, 3500);
    }

    // ============================================
    // FORM SUBMISSION (ایجاد درس)
    // ============================================
    
    document.getElementById('createCourseForm')?.addEventListener('submit', function(e) {
        var submitButton = this.querySelector('.btn-submit');
        var originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<span class="spinner"></span> در حال ارسال...';
        submitButton.disabled = true;
        
        setTimeout(function() {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }, 5000);
    });

    // ============================================
    // KEYBOARD SHORTCUTS
    // ============================================
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var createModal = document.getElementById('createCourseModal');
            if (createModal && createModal.classList.contains('active')) {
                closeModal();
            }
            var archivedModal = document.getElementById('archivedModalOverlay');
            if (archivedModal && archivedModal.classList.contains('active')) {
                closeArchivedModal();
            }
            var editModal = document.getElementById('editCourseModal');
            if (editModal && editModal.classList.contains('active')) {
                closeEditModal();
            }
        }
    });

    console.log('✅ Course management loaded successfully!');
</script>
@endsection