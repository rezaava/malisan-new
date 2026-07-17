@extends('layout.master')

@section('title')
ملیسان | مدیریت پیام‌های انگیزشی
@endsection

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
{{-- اضافه کردن استایل Jodit --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">

<style>
    .angizesh-container {
        max-width: 1100px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .angizesh-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 30px rgba(0, 0, 0, 0.06);
        padding: 30px 35px;
    }

    .angizesh-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        border-bottom: 2px solid #f0f4f9;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .angizesh-header h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
    }

    .angizesh-header h4 i {
        color: #1e6f9f;
        margin-left: 10px;
    }

    /* ===== دکمه افزودن ===== */
    .btn-add {
        padding: 10px 24px;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 111, 159, 0.3);
        color: #fff;
    }

    /* ===== جدول ===== */
    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table thead {
        background: #f8fafc;
        border-radius: 12px;
    }

    table thead th {
        padding: 12px 16px;
        text-align: right;
        font-weight: 600;
        color: #1a2332;
        border-bottom: 2px solid #e8edf3;
        white-space: nowrap;
    }

    table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f4f9;
        color: #1a2332;
        vertical-align: middle;
    }

    table tbody tr:hover {
        background: #f8fafc;
    }

    .level-badge {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .level-1 { background: #e8f5e9; color: #2e7d32; }
    .level-2 { background: #e3f2fd; color: #1e6f9f; }
    .level-3 { background: #fff3e0; color: #e65100; }
    .level-4 { background: #fff8e1; color: #f57f17; }
    .level-5 { background: #ffebee; color: #c62828; }
    .level-6 { background: #fce4ec; color: #880e4f; }
    .level-7 { background: #f3e5f5; color: #6a1b9a; }

    .text-cell {
        max-width: 300px;
        word-wrap: break-word;
        line-height: 1.6;
    }

    .text-cell img {
        max-width: 100%;
        border-radius: 8px;
    }

    .action-btns {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 6px 14px;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }

    .btn-action-edit {
        background: #e3f2fd;
        color: #1e6f9f;
    }

    .btn-action-edit:hover {
        background: #1e6f9f;
        color: #fff;
    }

    .btn-action-delete {
        background: #ffebee;
        color: #c62828;
    }

    .btn-action-delete:hover {
        background: #f44336;
        color: #fff;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }

    .empty-state .empty-icon {
        font-size: 50px;
        color: #d0d7e2;
        display: block;
        margin-bottom: 16px;
    }

    .empty-state h5 {
        color: #1a2332;
        font-size: 16px;
        margin-bottom: 6px;
    }

    .empty-state p {
        color: #6b7a8f;
        font-size: 14px;
    }

    /* ===== مودال ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-box {
        background: #fff;
        border-radius: 20px;
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 30px;
        position: relative;
        animation: slideUp 0.3s ease;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-box::-webkit-scrollbar {
        width: 6px;
    }

    .modal-box::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .modal-box::-webkit-scrollbar-thumb {
        background: #1e6f9f;
        border-radius: 10px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 20px;
    }

    .modal-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1a2332;
    }

    .modal-header h4 i {
        color: #1e6f9f;
        margin-left: 8px;
    }

    .modal-close-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: #f0f4f9;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4a5a6e;
    }

    .modal-close-btn:hover {
        background: #e74c3c;
        color: #fff;
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 5px 0;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #1a2332;
        margin-bottom: 6px;
    }

    .form-group label .required {
        color: #e74c3c;
        margin-right: 3px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafbfc;
        color: #1a2332;
        font-family: inherit;
        resize: vertical;
    }

    .form-control:focus {
        border-color: #1e6f9f;
        outline: none;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.08);
        background: #fff;
    }

    select.form-control {
        appearance: auto;
        cursor: pointer;
    }

    /* ===== JODIT EDITOR ===== */
    .jodit-container {
        border-radius: 12px !important;
        overflow: hidden;
        border: 2px solid #e8edf3 !important;
        transition: all 0.3s ease;
    }

    .jodit-container:focus-within {
        border-color: #1e6f9f !important;
        box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.08) !important;
    }

    .jodit-container .jodit-toolbar {
        background: #f8fafc !important;
        border-bottom: 1px solid #e8edf3 !important;
        border-radius: 12px 12px 0 0 !important;
    }

    .jodit-container .jodit-workplace {
        min-height: 150px;
    }

    .jodit-container .jodit-wysiwyg {
        padding: 12px 16px !important;
        font-family: 'Vazir', Tahoma, Arial, sans-serif !important;
        font-size: 14px !important;
        direction: rtl !important;
        min-height: 150px !important;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 16px;
        border-top: 2px solid #f0f4f9;
        margin-top: 10px;
    }

    .btn-submit {
        padding: 12px 32px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: inherit;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 111, 159, 0.3);
        color: #fff;
    }

    .btn-secondary {
        background: #f0f4f9;
        color: #4a5a6e;
    }

    .btn-secondary:hover {
        background: #e3e8ef;
        transform: translateY(-2px);
    }

    /* ===== انیمیشن‌ها ===== */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .angizesh-card {
            padding: 20px 16px;
        }

        .angizesh-header {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-add {
            justify-content: center;
        }

        .modal-box {
            padding: 20px;
            width: 95%;
        }

        .action-btns {
            flex-direction: column;
        }

        .text-cell {
            max-width: 150px;
        }

        table {
            font-size: 13px;
        }

        table thead th,
        table tbody td {
            padding: 8px 10px;
        }

        .jodit-container .jodit-toolbar {
            flex-wrap: wrap !important;
        }
    }
</style>
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