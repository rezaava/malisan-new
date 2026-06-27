@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-courses.css')}}">
@endsection

@section('mohtava')
<div class="content-header">
    <button class="archive-btn" id="archiveBtn">
        <i class="fas fa-archive"></i>
        <span>آرشیوها</span>
        <span class="archived-count" id="archivedCountBadge">0</span>
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

<div class="courses-grid" id="coursesGrid">
    @forelse ($courses as $cours)
        <div class="course-card" data-course-id="{{ $cours->id }}">
            <a href="{{ route('view.coure',$cours->id)}}" class="course-link">
                <div class="course-image">
                    <img src="{{ asset('images/course-default.jpg') }}" alt="{{ $cours->name }}">
                    <div class="course-badge" style="background: {{ $cours->private == 1 ? 'rgba(76, 175, 80, 0.9)' : 'rgba(0, 0, 0, 0.7)' }};">
                        @if ($cours->private == 1)
                            خصوصی
                        @else
                            عمومی
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
                    <i class="fas {{ $cours->private == 1 ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
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

<!-- ==========================================
     مودال ایجاد درس
     ========================================== -->
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

<!-- ==========================================
     مودال نمایش دوره‌های آرشیو شده
     ========================================== -->
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
    // مودال آرشیو - با EventListener
    // ============================================
    
    // باز کردن مودال با EventListener
    document.addEventListener('DOMContentLoaded', function() {
        // دکمه آرشیو
        var archiveBtn = document.getElementById('archiveBtn');
        if (archiveBtn) {
            archiveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openArchivedModal();
            });
        }

        // بستن مودال با کلیک روی پس‌زمینه
        var modal = document.getElementById('archivedModalOverlay');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeArchivedModal();
                }
            });
        }

        // بارگذاری تعداد آرشیوها
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

                // بروزرسانی تعداد Badge
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
        
        // پیدا کردن دکمه‌ها و غیرفعال کردن
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
        
        if (!confirm('آیا از آرشیو کردن این درس اطمینان دارید؟')) {
            return;
        }
        
        // نمایش لودینگ
        const targetBtn = event?.currentTarget;
        if (targetBtn) {
            targetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            targetBtn.style.pointerEvents = 'none';
        }
        
        fetch(`/teacher/courses/toggle-archive/${courseId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                
                // حذف کارت از صفحه
                const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
                if (card) {
                    card.style.transition = 'opacity 0.5s';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                    }, 500);
                }
                
                // بروزرسانی تعداد Badge آرشیو
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
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                
                const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
                if (card) {
                    const badge = card.querySelector('.course-badge');
                    if (badge) {
                        if (data.private === 1) {
                            badge.textContent = 'خصوصی';
                            badge.style.background = 'rgba(76, 175, 80, 0.9)';
                        } else {
                            badge.textContent = 'عمومی';
                            badge.style.background = 'rgba(0, 0, 0, 0.7)';
                        }
                    }
                }
                
                const toggleBtns = document.querySelectorAll('.action-item[data-action="فعال/غیرفعال"]');
                toggleBtns.forEach(btn => {
                    if (btn.closest('.course-card')?.dataset?.courseId == courseId) {
                        btn.innerHTML = `
                            <i class="fas ${data.private === 1 ? 'fa-toggle-on' : 'fa-toggle-off'}"></i>
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
    // FORM SUBMISSION
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
        }
    });

    console.log('✅ Course management loaded successfully!');
</script>
@endsection