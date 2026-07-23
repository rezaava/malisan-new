@extends('layout.master')

@section('title')
ملیسان | مدیریت پیام‌های انگیزشی
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
<link rel="stylesheet" href="{{ asset('css/') }}">

@endsection

@section('mohtava')
<div class="angizesh-container">
    <div class="angizesh-card">
        {{-- HEADER --}}
        <div class="angizesh-header">
            <h4>
                <i class="fas fa-heart"></i>
                مدیریت پیام‌های انگیزشی
            </h4>
            <button class="btn-add" onclick="openAddModal()">
                <i class="fas fa-plus"></i>
                افزودن پیام جدید
            </button>
        </div>

        {{-- TABLE --}}
        @if($angizeshes->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>متن پیام</th>
                            <th style="width:200px;">محدوده نمره / نوع</th>
                            <th style="width:150px;">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($angizeshes as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-cell">{!! $item->text !!}</td>
                                <td>
                                    <span class="level-badge level-{{ $item->level }}">
                                        {{ $levelLabels[$item->level] ?? 'نامشخص' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <button class="btn-action btn-action-edit" onclick="openEditModal({{ $item->id }})">
                                            <i class="fas fa-edit"></i> ویرایش
                                        </button>
                                        <button class="btn-action btn-action-delete" onclick="deleteItem({{ $item->id }})">
                                            <i class="fas fa-trash-alt"></i> حذف
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <span class="empty-icon"><i class="fas fa-inbox"></i></span>
                <h5>هیچ پیامی ثبت نشده است</h5>
                <p>با کلیک روی دکمه "افزودن پیام جدید" اولین پیام را ثبت کنید.</p>
            </div>
        @endif
    </div>
</div>

{{-- ========================================== --}}
{{-- مودال افزودن/ویرایش --}}
{{-- ========================================== --}}
<div class="modal-overlay" id="angizeshModal">
    <div class="modal-box">
        <div class="modal-header">
            <h4>
                <i class="fas fa-edit" id="modalIcon"></i>
                <span id="modalTitle">افزودن پیام جدید</span>
            </h4>
            <button class="modal-close-btn" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="angizeshForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="itemId" value="">

                <div class="form-group">
                    <label for="text">متن پیام <span class="required">*</span></label>
                    <textarea class="jodit-editor" id="textEditor" name="text" 
                              placeholder="متن پیام را وارد کنید..."></textarea>
                </div>

                <div class="form-group">
                    <label for="level">محدوده نمره / نوع <span class="required">*</span></label>
                    <select class="form-control" id="level" name="level">
                        <option value="1">نمره 20</option>
                        <option value="2">نمره 18 تا کمتر از 20</option>
                        <option value="3">نمره 15 تا کمتر از 18</option>
                        <option value="4">نمره 12 تا کمتر از 15</option>
                        <option value="5">نمره 10 تا کمتر از 12</option>
                        <option value="6">نمره زیر 10</option>
                        <option value="7">پیام ورود (نمایش در صفحه ورود)</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span id="submitText">ذخیره</span>
                    </button>
                    <button type="button" class="btn-submit btn-secondary" onclick="closeModal()">
                        <i class="fas fa-times"></i>
                        انصراف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
{{-- اضافه کردن اسکریپت Jodit --}}
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>
    let joditEditor = null;

    document.addEventListener('DOMContentLoaded', function() {
        // ==========================================
        // مقداردهی Jodit Editor
        // ==========================================
        const editorElement = document.getElementById('textEditor');
        if (editorElement) {
            joditEditor = new Jodit('#textEditor', {
                width: '100%',
                height: 250,
                allowResize: true,
                allowResizeImages: true,
                direction: 'rtl',
                language: 'fa',
                buttons: [
                    'source', '|',
                    'undo', 'redo', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'font', 'fontsize', 'brush', 'paragraph', '|',
                    'ul', 'ol', 'outdent', 'indent', '|',
                    'align', 'hr', 'table', '|',
                    'link', 'unlink',
                    {
                        name: 'uploadImage',
                        iconURL: 'https://cdn-icons-png.flaticon.com/512/1829/1829586.png',
                        tooltip: 'آپلود تصویر',
                        exec: (editor) => {
                            let input = document.createElement('input');
                            input.type = 'file';
                            input.accept = 'image/*';
                            input.onchange = () => {
                                let file = input.files[0];
                                if (!file) return;

                                let formData = new FormData();
                                formData.append('file', file);

                                fetch('{{ route("upload.image") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.files && data.files[0].url) {
                                        let img = document.createElement('img');
                                        img.src = data.files[0].url;
                                        img.style.maxWidth = '100%';
                                        editor.s.insertNode(img);
                                    } else {
                                        alert('خطا در آپلود تصویر');
                                    }
                                })
                                .catch(err => alert('Upload error: ' + err));
                            };
                            input.click();
                        }
                    },
                    {
                        name: 'uploadVideo',
                        iconURL: 'https://cdn-icons-png.flaticon.com/512/727/727245.png',
                        tooltip: 'آپلود ویدیو',
                        exec: (editor) => {
                            let input = document.createElement('input');
                            input.type = 'file';
                            input.accept = 'video/*';
                            input.onchange = () => {
                                let file = input.files[0];
                                if (!file) return;

                                let formData = new FormData();
                                formData.append('file', file);

                                fetch('{{ route("upload.video") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.files && data.files[0].url) {
                                        let wrapper = document.createElement('div');
                                        wrapper.classList.add('video-wrapper');

                                        let video = document.createElement('video');
                                        video.setAttribute('controls', '');
                                        video.src = data.files[0].url;
                                        video.style.maxWidth = '100%';

                                        wrapper.appendChild(video);
                                        editor.s.insertNode(wrapper);
                                    } else {
                                        alert('خطا در آپلود ویدیو');
                                    }
                                })
                                .catch(err => alert('Upload error: ' + err));
                            };
                            input.click();
                        }
                    },
                    '|', 'symbols', 'emoticons', '|',
                    'print', 'fullsize', 'preview'
                ],
                colors: {
                    text: ['#000000', '#ff0000', '#00ff00', '#0000ff', '#ff00ff', '#00ffff'],
                    background: ['#ffffff', '#ffff00', '#00ffff', '#ffcc99']
                },
                defaultFont: 'Vazir, Tahoma, Arial, sans-serif',
                defaultFontSize: '14px',
                fonts: ['Vazir', 'Tahoma', 'Arial', 'Courier New']
            });
        }
    });

    // ==========================================
    // متغیرها
    // ==========================================
    const modal = document.getElementById('angizeshModal');
    const form = document.getElementById('angizeshForm');
    const formMethod = document.getElementById('formMethod');
    const itemId = document.getElementById('itemId');
    const levelInput = document.getElementById('level');
    const modalTitle = document.getElementById('modalTitle');
    const modalIcon = document.getElementById('modalIcon');
    const submitText = document.getElementById('submitText');

    // ==========================================
    // توابع مودال
    // ==========================================
    function openModal() {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        form.reset();
        if (joditEditor) {
            joditEditor.value = '';
        }
        document.getElementById('submitBtn').disabled = false;
    }

    // بستن با کلیک روی پس‌زمینه
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // بستن با کلید ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // ==========================================
    // باز کردن مودال افزودن
    // ==========================================
    function openAddModal() {
        formMethod.value = 'POST';
        itemId.value = '';
        if (joditEditor) {
            joditEditor.value = '';
        }
        levelInput.value = '1';
        modalTitle.textContent = 'افزودن پیام جدید';
        modalIcon.className = 'fas fa-plus-circle';
        submitText.textContent = 'ذخیره';
        form.action = '{{ route("admin_angizesh.store") }}';
        openModal();
    }

    // ==========================================
    // باز کردن مودال ویرایش
    // ==========================================
    function openEditModal(id) {
        modalTitle.textContent = 'در حال بارگذاری...';
        openModal();

        fetch('/admin/angizesh/edit/' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = data.data;
                    formMethod.value = 'PUT';
                    itemId.value = item.id;
                    if (joditEditor) {
                        joditEditor.value = item.text;
                    }
                    levelInput.value = item.level;
                    modalTitle.textContent = 'ویرایش پیام';
                    modalIcon.className = 'fas fa-edit';
                    submitText.textContent = 'بروزرسانی';
                    form.action = '/admin/angizesh/update/' + item.id;
                } else {
                    alert('خطا در دریافت اطلاعات: ' + data.message);
                    closeModal();
                }
            })
            .catch(error => {
                alert('خطا در ارتباط با سرور');
                closeModal();
            });
    }

    // ==========================================
    // ارسال فرم (AJAX)
    // ==========================================
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ذخیره...';

        // گرفتن مقدار ادیتور
        const textValue = joditEditor ? joditEditor.value : document.getElementById('textEditor').value;
        
        const formData = new FormData(this);
        // اگر ادیتور مقدار دارد، جایگزین کن
        if (joditEditor) {
            formData.set('text', joditEditor.value);
        }

        const url = this.action;
        const method = formMethod.value;

        fetch(url, {
            method: method === 'PUT' ? 'POST' : 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                let errorMsg = 'خطا در ذخیره: ';
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMsg += errors.join(', ');
                } else {
                    errorMsg += data.message || 'مشخص نیست';
                }
                alert(errorMsg);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> <span id="submitText">' + submitText.textContent + '</span>';
            }
        })
        .catch(error => {
            alert('خطا در ارتباط با سرور');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> <span id="submitText">' + submitText.textContent + '</span>';
        });
    });

    // ==========================================
    // حذف آیتم
    // ==========================================
    function deleteItem(id) {
        if (!confirm('آیا مطمئن هستید که می‌خواهید این پیام را حذف کنید؟')) {
            return;
        }

        fetch('/admin/angizesh/destroy/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('خطا در حذف: ' + data.message);
            }
        })
        .catch(error => {
            alert('خطا در ارتباط با سرور');
        });
    }
</script>
@endsection