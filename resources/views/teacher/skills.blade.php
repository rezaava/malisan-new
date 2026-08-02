@extends('layout.master')

@section('title')
ملیسان | صفحه اصلی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-courses.css')}}">
@endsection

@section('mohtava')
<div class="content-header">
    <button class="courses-btn">
        <i class="fas fa-book"></i>
        <span>مهارت‌ها</span>
    </button>
    <!-- Create Course Button -->
    <button class="btn-create-course m-0" onclick="openCreateModal()">
        <i class="fas fa-plus"></i>
        ایجاد مهارت جدید
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
            // is_ended: 1 = خاتمه یافته، 0 = در حال برگزاری
            $isEnded = ($cours->is_ended == 1);
            $isDore = ($cours->is_dore == 1);
            $isPrivate = ($cours->private == 1);
        @endphp
        <div class="course-card {{ $isEnded ? 'ended' : '' }}" 
             data-course-id="{{ $cours->id }}"
             data-is-dore="{{ $isDore ? '1' : '0' }}"
             data-is-private="{{ $isPrivate ? '1' : '0' }}"
             data-is-ended="{{ $isEnded ? '1' : '0' }}">
            <a href="{{ route('view.coure',$cours->id)}}" class="course-link">
                <div class="course-image">
                    <img src="{{ asset('/files/icons/' . $cours->header . '.jpg') }}" alt="{{ $cours->name }}">
                    <div class="course-badge {{ $isEnded ? 'badge-ended' : 'badge-active' }}">
                        <i class="fas {{ $isEnded ? 'fa-stop-circle' : 'fa-play-circle' }}"></i>
                        {{ $isEnded ? 'خاتمه یافته' : 'در حال برگزاری' }}
                    </div>
                </div>
                <div class="course-info">
                    <h3 class="course-title">{{ $cours->name }}</h3>
                    <p class="course-code">کد: {{ $cours->code }}</p>
                    
                    <!-- نمایش وضعیت برگزاری -->
                    <span class="course-status-badge {{ $isEnded ? 'course-status-inactive' : 'course-status-active' }}">
                        <i class="fas fa-circle"></i>
                        {{ $isEnded ? '● خاتمه یافته' : '● در حال برگزاری' }}
                    </span>
                    
                    <!-- نشانگر دوره‌ای بودن مهارت -->
                    @if($isDore)
                        <span class="dore-badge" id="doreBadge-{{ $cours->id }}">
                            <i class="fas fa-calendar-check"></i> دوره‌ای
                        </span>
                    @endif
                    
                    <!-- نشانگر عمومی/خصوصی بودن مهارت -->
                    <span class="private-badge {{ $isPrivate ? 'private' : 'public' }}" id="privateBadge-{{ $cours->id }}">
                        <i class="fas {{ $isPrivate ? 'fa-lock' : 'fa-globe-asia' }}"></i>
                        {{ $isPrivate ? 'خصوصی' : 'عمومی' }}
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
                <a href="{{ route('courses.setting',$cours->id) }}" class="action-item action-btn settings-btn">
                    <i class="fas fa-cog"></i>
                </a>
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
                <!-- دکمه تغییر وضعیت دوره‌ای/غیردوره‌ای -->
                <div class="action-item" 
                     data-action="دوره‌ای" 
                     onclick="event.preventDefault(); event.stopPropagation(); toggleDore({{ $cours->id }})">
                    <i class="fas {{ $isDore ? 'fa-calendar-check' : 'fa-calendar-times' }}"></i>
                    <span class="action-tooltip">{{ $isDore ? 'غیردوره‌ای' : 'دوره‌ای' }}</span>
                </div>
                <!-- دکمه تغییر وضعیت خاتمه یافته/در حال برگزاری (is_ended) -->
                <div class="action-item" data-action="خاتمه/فعال" onclick="event.preventDefault(); event.stopPropagation(); toggleEnded({{ $cours->id }})">
                    <i class="fas {{ $isEnded ? 'fa-play' : 'fa-stop' }}"></i>
                    <span class="action-tooltip">{{ $isEnded ? 'فعال کردن' : 'خاتمه دادن' }}</span>
                </div>
                <!-- دکمه تغییر وضعیت عمومی/خصوصی (private) -->
                <div class="action-item" 
                     data-action="عمومی/خصوصی" 
                     onclick="event.preventDefault(); event.stopPropagation(); togglePrivate({{ $cours->id }})">
                    <i class="fas {{ $isPrivate ? 'fa-lock' : 'fa-globe-asia' }}"></i>
                    <span class="action-tooltip">{{ $isPrivate ? 'عمومی کردن' : 'خصوصی کردن' }}</span>
                </div>
                <div class="action-item" data-action="حذف" onclick="event.preventDefault(); event.stopPropagation(); deleteCourse({{ $cours->id }})">
                    <i class="fas fa-trash-alt"></i>
                    <span class="action-tooltip">حذف</span>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <p>شما هیچ مهارتی ندارید</p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- مودال‌ها -->
<!-- ========================================== -->

<!-- مودال ایجاد مهارت -->
<div class="modal-overlay" id="createCourseModal">
    <div class="modal-container">
        <div class="modal-header" style="background: linear-gradient(135deg, #1e6f9f, #155a82);">
            <h3 id="modalTitle" style="color:#fff;">ایجاد مهارت جدید</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="createCourseForm" action="{{ route('skill.store') }}" method="POST">
            @csrf
            <input type="hidden" name="copy" id="copyCourseId" value="">
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">عنوان مهارت <span class="required">*</span></label>
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
                    <span id="submitButtonText">ایجاد مهارت</span>
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

<!-- مودال ویرایش مهارت -->
<div class="modal-overlay" id="editCourseModal">
    <div class="modal-container" style="max-width: 550px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #ff9800, #e65100);">
            <h3 style="color:#fff;"><i class="fas fa-edit"></i> ویرایش مهارت</h3>
            <button class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editCourseForm">
            @csrf
            <input type="hidden" name="course_id" id="editCourseId" value="">
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_name">عنوان مهارت <span class="required">*</span></label>
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
            modalTitle.textContent = 'کپی مهارت';
            submitButtonText.textContent = 'کپی مهارت';
            document.getElementById('copyCourseId').value = copyId;
            
            showToast('در حال بارگذاری اطلاعات مهارت...', 'info');
            
            fetch(`/teacher/skill/copy/${copyId}`)
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
                    showToast('اطلاعات مهارت بارگذاری شد.', 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('خطا در بارگذاری اطلاعات مهارت', 'error');
                    modal.classList.add('active');
                });
        } else {
            modalTitle.textContent = 'ایجاد مهارت جدید';
            submitButtonText.textContent = 'ایجاد مهارت';
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

        fetch('/teacher/skill/archived')
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
        fetch('/teacher/skills/archived')
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
        
        fetch('/teacher/skills/toggle-archive/' + courseId, {
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
    // ویرایش مهارت - نسخه Ajax
    // ============================================

    function editCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        
        showToast('در حال دریافت اطلاعات مهارت...', 'info');
        
        fetch(`/teacher/skills/${courseId}/edit-data`, {
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
                    throw new Error(data.message || 'خطا در دریافت اطلاعات مهارت');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('edit_name').value = data.course.name;
                document.getElementById('edit_majazi').value = data.course.majazi || '';
                document.getElementById('editCourseId').value = courseId;
                
                const modal = document.getElementById('editCourseModal');
                modal.classList.add('active');
                showToast('اطلاعات مهارت بارگذاری شد', 'success');
            } else {
                showToast(data.message || 'خطا در دریافت اطلاعات مهارت', 'error');
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
        
        const name = document.getElementById('edit_name').value.trim();
        if (!name) {
            showToast('لطفاً عنوان مهارت را وارد کنید', 'error');
            return;
        }
        
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> در حال ذخیره...';
        submitBtn.disabled = true;
        
        fetch(`/teacher/skills/${courseId}`, {
            method: 'POST',
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
                    throw new Error(data.message || 'خطا در ویرایش مهارت');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message || 'مهارت با موفقیت ویرایش شد', 'success');
                updateCourseCard(courseId, data.course);
                closeEditModal();
            } else {
                if (data.errors) {
                    let errorMessages = '';
                    for (let field in data.errors) {
                        errorMessages += data.errors[field].join('\n') + '\n';
                    }
                    showToast(errorMessages || data.message, 'error');
                } else {
                    showToast(data.message || 'خطا در ویرایش مهارت', 'error');
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
    // تابع به‌روزرسانی کارت مهارت
    // ============================================

    function updateCourseCard(courseId, courseData) {
        const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
        if (!card) return;
        
        card.classList.add('updating');
        
        const title = card.querySelector('.course-title');
        if (title) title.textContent = courseData.name;
        
        const virtualContainer = card.querySelector('.virtual-class-container');
        const virtualLink = card.querySelector('.virtual-class-link');
        
        if (courseData.majazi) {
            const baseUrl = 'https://testnn.malisan.ir/teacher/';
            const cleanUrl = courseData.majazi.replace(baseUrl, '');
            
            if (virtualLink) {
                virtualLink.href = 'https://' + cleanUrl;
                if (virtualContainer) virtualContainer.style.display = 'block';
            } else {
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
            if (virtualContainer) {
                virtualContainer.style.display = 'none';
            }
        }
        
        if (courseData.is_dore !== undefined) {
            updateDoreStatus(courseId, courseData.is_dore);
        }
        
        if (courseData.private !== undefined) {
            updatePrivateStatus(courseId, courseData.private);
        }
        
        if (courseData.is_ended !== undefined) {
            updateEndedStatus(courseId, courseData.is_ended);
        }
        
        setTimeout(() => {
            card.classList.remove('updating');
        }, 1000);
        
        showToast('✅ مهارت با موفقیت به‌روزرسانی شد', 'success');
    }

    // ============================================
    // تغییر وضعیت دوره‌ای/غیردوره‌ای (is_dore)
    // ============================================

    function toggleDore(courseId) {
        event.preventDefault();
        event.stopPropagation();
        
        const targetBtn = event?.currentTarget;
        if (targetBtn) {
            targetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            targetBtn.style.pointerEvents = 'none';
        }
        
        fetch(`/teacher/skills/toggle-dore/${courseId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'خطا در تغییر وضعیت');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                updateDoreStatus(courseId, data.is_dore);
            } else {
                showToast(data.message || 'خطا در تغییر وضعیت', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'خطا در ارتباط با سرور', 'error');
        })
        .finally(() => {
            if (targetBtn) {
                const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
                const isDore = card?.dataset?.isDore === '1';
                targetBtn.innerHTML = `
                    <i class="fas ${isDore ? 'fa-calendar-check' : 'fa-calendar-times'}"></i>
                    <span class="action-tooltip">${isDore ? 'غیردوره‌ای' : 'دوره‌ای'}</span>
                `;
                targetBtn.style.pointerEvents = 'auto';
            }
        });
    }

    function updateDoreStatus(courseId, isDore) {
        const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
        if (!card) return;
        
        card.dataset.isDore = isDore ? '1' : '0';
        
        const doreBtn = card.querySelector('.action-item[data-action="دوره‌ای"]');
        if (doreBtn) {
            doreBtn.innerHTML = `
                <i class="fas ${isDore ? 'fa-calendar-check' : 'fa-calendar-times'}"></i>
                <span class="action-tooltip">${isDore ? 'غیردوره‌ای' : 'دوره‌ای'}</span>
            `;
        }
        
        let doreBadge = card.querySelector('.dore-badge');
        const infoDiv = card.querySelector('.course-info');
        
        if (isDore) {
            if (!doreBadge) {
                doreBadge = document.createElement('span');
                doreBadge.className = 'dore-badge';
                doreBadge.id = `doreBadge-${courseId}`;
                doreBadge.innerHTML = '<i class="fas fa-calendar-check"></i> دوره‌ای';
                if (infoDiv) {
                    const statusBadge = infoDiv.querySelector('.course-status-badge');
                    if (statusBadge) {
                        statusBadge.after(doreBadge);
                    } else {
                        infoDiv.appendChild(doreBadge);
                    }
                }
            } else {
                doreBadge.style.display = 'inline-block';
            }
            if (doreBadge) {
                doreBadge.classList.add('updating');
                setTimeout(() => {
                    doreBadge.classList.remove('updating');
                }, 500);
            }
        } else {
            if (doreBadge) {
                doreBadge.style.display = 'none';
            }
        }
    }

    // ============================================
    // تغییر وضعیت عمومی/خصوصی (private) با toggleVisibility
    // ============================================

    function togglePrivate(courseId) {
        event.preventDefault();
        event.stopPropagation();
        
        const targetBtn = event?.currentTarget;
        if (targetBtn) {
            targetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            targetBtn.style.pointerEvents = 'none';
        }
        
        const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
        const currentValue = card?.dataset?.isPrivate === '1' ? 0 : 1;
        
        fetch(`/teacher/skills/toggle-visibility/${courseId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                field: 'private',
                value: currentValue
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'خطا در تغییر وضعیت');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                updatePrivateStatus(courseId, data.value);
            } else {
                showToast(data.message || 'خطا در تغییر وضعیت', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'خطا در ارتباط با سرور', 'error');
        })
        .finally(() => {
            if (targetBtn) {
                const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
                const isPrivate = card?.dataset?.isPrivate === '1';
                targetBtn.innerHTML = `
                    <i class="fas ${isPrivate ? 'fa-lock' : 'fa-globe-asia'}"></i>
                    <span class="action-tooltip">${isPrivate ? 'عمومی کردن' : 'خصوصی کردن'}</span>
                `;
                targetBtn.style.pointerEvents = 'auto';
            }
        });
    }

    function updatePrivateStatus(courseId, isPrivate) {
        const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
        if (!card) return;
        
        card.dataset.isPrivate = isPrivate ? '1' : '0';
        
        const privateBtn = card.querySelector('.action-item[data-action="عمومی/خصوصی"]');
        if (privateBtn) {
            privateBtn.innerHTML = `
                <i class="fas ${isPrivate ? 'fa-lock' : 'fa-globe-asia'}"></i>
                <span class="action-tooltip">${isPrivate ? 'عمومی کردن' : 'خصوصی کردن'}</span>
            `;
        }
        
        let privateBadge = card.querySelector('.private-badge');
        
        if (privateBadge) {
            if (isPrivate) {
                privateBadge.className = 'private-badge private';
                privateBadge.innerHTML = '<i class="fas fa-lock"></i> خصوصی';
            } else {
                privateBadge.className = 'private-badge public';
                privateBadge.innerHTML = '<i class="fas fa-globe-asia"></i> عمومی';
            }
            privateBadge.classList.add('updating');
            setTimeout(() => {
                privateBadge.classList.remove('updating');
            }, 500);
        }
    }

    // ============================================
    // تغییر وضعیت خاتمه یافته/در حال برگزاری (is_ended) با toggleVisibility
    // ============================================

    function toggleEnded(courseId) {
        event.preventDefault();
        event.stopPropagation();
        
        if (!confirm('آیا از تغییر وضعیت این مهارت اطمینان دارید؟')) {
            return;
        }
        
        const targetBtn = event?.currentTarget;
        if (targetBtn) {
            targetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            targetBtn.style.pointerEvents = 'none';
        }
        
        const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
        const currentValue = card?.dataset?.isEnded === '1' ? 0 : 1;
        
        fetch(`/teacher/skills/toggle-visibility/${courseId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                field: 'is_ended',
                value: currentValue
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'خطا در تغییر وضعیت');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                updateEndedStatus(courseId, data.value);
            } else {
                showToast(data.message || 'خطا در تغییر وضعیت', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'خطا در ارتباط با سرور', 'error');
        })
        .finally(() => {
            if (targetBtn) {
                const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
                const isEnded = card?.dataset?.isEnded === '1';
                targetBtn.innerHTML = `
                    <i class="fas ${isEnded ? 'fa-play' : 'fa-stop'}"></i>
                    <span class="action-tooltip">${isEnded ? 'فعال کردن' : 'خاتمه دادن'}</span>
                `;
                targetBtn.style.pointerEvents = 'auto';
            }
        });
    }

    function updateEndedStatus(courseId, isEnded) {
        const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
        if (!card) return;
        
        card.dataset.isEnded = isEnded ? '1' : '0';
        
        // به‌روزرسانی کلاس ended
        if (isEnded) {
            card.classList.add('ended');
        } else {
            card.classList.remove('ended');
        }
        
        // به‌روزرسانی دکمه
        const endedBtn = card.querySelector('.action-item[data-action="خاتمه/فعال"]');
        if (endedBtn) {
            endedBtn.innerHTML = `
                <i class="fas ${isEnded ? 'fa-play' : 'fa-stop'}"></i>
                <span class="action-tooltip">${isEnded ? 'فعال کردن' : 'خاتمه دادن'}</span>
            `;
        }
        
        // به‌روزرسانی badge روی تصویر
        const badge = card.querySelector('.course-badge');
        if (badge) {
            if (isEnded) {
                badge.className = 'course-badge badge-ended';
                badge.innerHTML = '<i class="fas fa-stop-circle"></i> خاتمه یافته';
            } else {
                badge.className = 'course-badge badge-active';
                badge.innerHTML = '<i class="fas fa-play-circle"></i> در حال برگزاری';
            }
        }
        
        // به‌روزرسانی status badge زیر عنوان
        const statusBadge = card.querySelector('.course-status-badge');
        if (statusBadge) {
            if (isEnded) {
                statusBadge.className = 'course-status-badge course-status-inactive';
                statusBadge.innerHTML = '<i class="fas fa-circle"></i> ● خاتمه یافته';
            } else {
                statusBadge.className = 'course-status-badge course-status-active';
                statusBadge.innerHTML = '<i class="fas fa-circle"></i> ● در حال برگزاری';
            }
        }
        
        card.classList.add('updating');
        setTimeout(() => {
            card.classList.remove('updating');
        }, 500);
    }

    // ============================================
    // حذف مهارت
    // ============================================

    function deleteCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'آیا از حذف این مهارت اطمینان دارید؟',
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
            if (confirm('آیا از حذف این مهارت اطمینان دارید؟ این اقدام غیرقابل بازگشت است!')) {
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
        
        fetch(`/teacher/skills/${courseId}`, {
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
                    throw new Error(data.message || 'خطا در حذف مهارت');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message || 'مهارت با موفقیت حذف شد', 'success');
                
                if (card) {
                    card.style.transition = 'all 0.5s ease';
                    card.style.transform = 'scale(0.8)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                    }, 500);
                }
            } else {
                showToast(data.message || 'خطا در حذف مهارت', 'error');
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
            showToast('خطا: اطلاعات مهارت پیدا نشد', 'error');
            return;
        }
        
        const courseName = card.querySelector('.course-title')?.textContent || '';
        const courseCode = card.querySelector('.course-code')?.textContent?.replace('کد: ', '') || '';
        
        const message = `دانشجوی عزیز، برای دسترسی به مهارت ${courseName} ابتدا از طریق سایت WWW.MALISAN.IR در سامانه آموزشی ملیسان با هویت واقعی ثبت نام کنید، سپس با استفاده از شناسه ${courseCode} در مهارت ذکر شده عضو شوید.`;
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'اشتراک گذاری مهارت',
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
    // آرشیو کردن مهارت
    // ============================================

    function archiveCourse(courseId) {
        event.preventDefault();
        event.stopPropagation();
        
        if (!confirm('آیا از آرشیو کردن این مهارت اطمینان دارید؟')) {
            return;
        }
        
        const targetBtn = event?.currentTarget;
        if (targetBtn) {
            targetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            targetBtn.style.pointerEvents = 'none';
        }
        
        fetch(`/teacher/skills/toggle-archive/${courseId}`, {
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
    // FORM SUBMISSION (ایجاد مهارت)
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