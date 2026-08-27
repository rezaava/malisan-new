@extends('layout.master')

@section('title')
    ویرا کوین
@endsection

@section('head')
<style>
    .vc-card {
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        padding: 25px 20px;
        max-width: 650px;
        margin: 30px auto;
        background: #fff;
    }

    .vc-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 25px;
    }

    .vc-balance {
        font-size: 15px;
        color: #333;
    }

    .vc-balance b {
        font-weight: bold;
    }

    .vc-logo-box {
        text-align: left;
    }

    .vc-logo-title {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-start;
        font-weight: bold;
        font-size: 16px;
        color: #111;
    }

    .vc-logo-circle {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #f5a623;
        display: inline-block;
    }

    .vc-logo-sub {
        font-size: 12px;
        color: #555;
        margin-top: 4px;
    }

    .vc-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .vc-btn {
        flex: 1 1 0;
        min-width: 120px;
        border: 1px solid #f5a623;
        color: #333;
        background: #fff;
        border-radius: 6px;
        padding: 12px 8px;
        text-align: center;
        font-size: 14px;
        text-decoration: none;
        transition: 0.2s;
    }

    .vc-btn:hover {
        background: #fff8ec;
        color: #333;
        text-decoration: none;
    }
</style>
@endsection

@section('mohtava')
<div class="vc-card">
    <div class="vc-header">
        <div class="vc-balance">
            <div class="vc-logo-title">
                <span class="vc-logo-circle"></span>
                ویرا کوین
            </div>
            <div class="vc-logo-sub">هر ویرا کوین معادل ۱۰،۰۰۰ ریال</div>
        </div>
        <div class="vc-logo-box">
            موجودی: <b>[ ۱۰۰۰ ]</b> ویرا کوین
        </div>
    </div>

    <div class="vc-actions">
        <a href="#" class="vc-btn">ماموریت ها</a>
        <a href="#" class="vc-btn">تاریخچه</a>
        <a href="#" class="vc-btn">جایزه روزانه</a>
        <a href="#" class="vc-btn">افزایش موجودی</a>
    </div>
</div>
@endsection

@section('js')

@endsection