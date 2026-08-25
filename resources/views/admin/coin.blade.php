@extends('layout.master')

@section('title')
ویرا کوین | مدیریت فعالیت‌ها
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/admin/coin.css')}}">
@endsection

@section('mohtava')
<div class="container-fluid px-4">
    
    {{-- Header --}}
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3>
                    <i class="fas fa-tasks me-2"></i>
                    مدیریت و ثبت فعالیت‌های جایزه‌دار
                </h3>
                <small>مدیریت و ثبت فعالیت‌های جایزه‌دار</small>
            </div>
            <div class="mt-2 mt-md-0">
                <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                    <i class="fas fa-database me-1"></i>
                    تعداد فعالیت‌ها: {{ $coins->count() }}
                </span>
            </div>
        </div>
    </div>

    {{-- فرم اضافه کردن --}}
    <div class="form-card">
        <div class="form-title">
            <i class="fas fa-plus-circle"></i>
            <span>افزودن فعالیت جدید</span>
        </div>
        
        <form action="{{ route('admin.coin.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-md-5">
                    <label for="title" class="form-label">
                        <i class="fas fa-tag me-1"></i>
                        عنوان فعالیت
                    </label>
                    <input type="text" name="title" id="title" class="form-control" 
                           placeholder="مثال: ارسال ۱۰ نظر" required>
                </div>
                
                <div class="col-md-5">
                    <label for="coin_value" class="form-label">
                        <i class="fas fa-coins me-1"></i>
                        مقدار ویراکوین
                    </label>
                    <input type="number" name="coin_value" id="coin_value" class="form-control" 
                           placeholder="مثال: ۱۰۰" step="any" required>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save me-2"></i>
                        ثبت فعالیت
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- جدول نمایش فعالیت‌ها --}}
    <div class="table-card">
        <div class="table-header">
            <h5>
                <i class="fas fa-list me-2 text-primary"></i>
                لیست فعالیت‌ها
            </h5>
            <span class="badge-count">
                <i class="fas fa-tasks me-1"></i>
                {{ $coins->count() }} مورد
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="8%">ردیف</th>
                        <th width="30%">عنوان فعالیت</th>
                        <th width="20%">مقدار ویراکوین</th>
                        <th width="20%">وضعیت</th>
                        <th width="22%" class="text-center">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coins as $index => $coin)
                        <tr>
                            {{-- ستون ردیف --}}
                            <td>
                                <span class="badge-id">{{ $loop->iteration }}</span>
                            </td>
                            
                            {{-- ستون عنوان --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-tasks text-primary"></i>
                                    <span class="fw-semibold">{{ $coin->name }}</span>
                                </div>
                            </td>
                            
                            {{-- ستون مقدار ویراکوین --}}
                            <td>
                                <span class="badge-coin">
                                    <i class="fas fa-coins"></i>
                                    {{ number_format($coin->value) }}
                                </span>
                            </td>
                            
                            {{-- ستون وضعیت --}}
                            <td>
                                @if($coin->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        فعال
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i>
                                        غیرفعال
                                    </span>
                                @endif
                            </td>
                            
                            {{-- ستون عملیات --}}
                            <td class="text-center">
                                {{-- دکمه ویرایش (فقط مقدار ویراکوین) --}}
                                <button type="button" class="btn-sm-edit" data-bs-toggle="modal" 
                                        data-bs-target="#editModal{{ $coin->id }}">
                                    <i class="fas fa-edit"></i>
                                    ویرایش
                                </button>

                                {{-- دکمه فعال/غیرفعال --}}
                                <form action="{{ route('admin.coin.toggle', $coin->id) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-sm-toggle {{ $coin->is_active ? 'btn-danger' : 'btn-success' }}">
                                        <i class="fas {{ $coin->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                        {{ $coin->is_active ? 'غیرفعال' : 'فعال' }}
                                    </button>
                                </form>

                                {{-- مودال ویرایش --}}
                                <div class="modal fade" id="editModal{{ $coin->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-edit me-2"></i>
                                                    ویرایش مقدار ویراکوین
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.coin.update', $coin->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="coin_value_{{ $coin->id }}" class="form-label">
                                                            <i class="fas fa-coins me-1"></i>
                                                            مقدار ویراکوین
                                                        </label>
                                                        <input type="number" name="coin_value" 
                                                               id="coin_value_{{ $coin->id }}" 
                                                               class="form-control" 
                                                               value="{{ $coin->value }}" 
                                                               step="any" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1"></i>
                                                        انصراف
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save me-1"></i>
                                                        ذخیره تغییرات
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-tasks"></i>
                                    <p>هیچ فعالیتی ثبت نشده است!</p>
                                    <small class="text-muted">با استفاده از فرم بالا اولین فعالیت را ثبت کنید</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection