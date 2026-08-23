@extends('layout.master')

@section('title')
ویرا کوین | مدیریت
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
                    <i class="fas fa-coins me-2"></i>
                    مدیریت ویرا کوین
                </h3>
                <small>مدیریت و ثبت تراکنش‌های ویرا کوین</small>
            </div>
            <div class="mt-2 mt-md-0">
                <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                    <i class="fas fa-database me-1"></i>
                    کل کوین‌ها: {{ $coins->count() }}
                </span>
            </div>
        </div>
    </div>


    {{-- فرم اضافه کردن --}}
    <div class="form-card">
        <div class="form-title">
            <i class="fas fa-plus-circle"></i>
            <span>افزودن ویرا کوین جدید</span>
        </div>
        
        <form action="{{ route('admin.coin.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-md-5">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag me-1"></i>
                        نام کوین
                    </label>
                    <input type="text" name="name" id="name" class="form-control" 
                           placeholder="مثال: بیت‌کوین" required>
                </div>
                
                <div class="col-md-5">
                    <label for="value" class="form-label">
                        <i class="fas fa-dollar-sign me-1"></i>
                        مقدار
                    </label>
                    <input type="number" name="value" id="value" class="form-control" 
                           placeholder="مثال: 45000" step="any" required>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save me-2"></i>
                        ثبت کوین
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- جدول نمایش کوین‌ها --}}
    <div class="table-card">
        <div class="table-header">
            <h5>
                <i class="fas fa-list me-2 text-primary"></i>
                لیست ویرا کوین‌ها
            </h5>
            <span class="badge-count">
                <i class="fas fa-coins me-1"></i>
                {{ $coins->count() }} مورد
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="8%">#</th>
                        <th width="28%">نام کوین</th>
                        <th width="25%">مقدار</th>
                        <th width="27%">تاریخ ثبت</th>
                        <th width="12%" class="text-center">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coins as $coin)
                        <tr>
                            {{-- ستون ID --}}
                            <td>
                                <span class="badge-id">#{{ $coin->id }}</span>
                            </td>
                            
                            {{-- ستون نام --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-coin text-warning"></i>
                                    <span class="fw-semibold">{{ $coin->name }}</span>
                                </div>
                            </td>
                            
                            {{-- ستون مقدار --}}
                            <td>
                                <span class="badge-coin">
                                    <i class="fas fa-coins"></i>
                                    {{ number_format($coin->value) }}
                                </span>
                            </td>
                            
                            {{-- ستون تاریخ با VertA --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="far fa-calendar-alt text-muted"></i>
                                    <div class="date-verta">
                                        <span class="date-main">
                                            {{ verta($coin->timestamp)->format('Y/m/d') }}
                                        </span>
                                        <span class="date-time">
                                            <i class="far fa-clock me-1"></i>
                                            {{ verta($coin->timestamp)->format('H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- ستون عملیات --}}
                            <td class="text-center">
                                <form action="{{ route('admin.coin.destroy', $coin->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('آیا از حذف این کوین مطمئن هستید؟')"
                                      class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-sm-danger">
                                        <i class="fas fa-trash-alt"></i>
                                        حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-coins"></i>
                                    <p>هیچ کوینی ثبت نشده است!</p>
                                    <small class="text-muted">با استفاده از فرم بالا اولین کوین را ثبت کنید</small>
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