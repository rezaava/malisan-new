@extends('layout.master')

@section('title')
ملیسان | خودآزمایی دانشجویان
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-self-tests-list.css')}}">
@endsection

@section('mohtava')
<div class="self-tests-container">
    <div class="self-tests-header">
        <div class="header-left">
            <a href="/dashboard" class="back-btn">
                <i class="fas fa-arrow-right"></i>
                بازگشت
            </a>
        </div>
        <div class="header-center">
            <h4 class="self-tests-title"><i class="fas fa-brain"></i> خودآزمایی دانشجویان</h4>
            <p class="self-tests-subtitle">لیست تمام خودآزمایی‌های ثبت شده توسط دانشجویان</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="self-tests-table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>شناسه</th>
                    <th>نمره</th>
                    <th>مشاهده</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>ST-001</td>
                    <td>۱۸.۵۰</td>
                    <td>
                        <a href="/self-tests/1" class="view-btn">
                            <i class="fas fa-eye"></i>
                            مشاهده
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>ST-002</td>
                    <td>۱۶.۰۰</td>
                    <td>
                        <a href="/self-tests/2" class="view-btn">
                            <i class="fas fa-eye"></i>
                            مشاهده
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>ST-003</td>
                    <td>۱۹.۰۰</td>
                    <td>
                        <a href="/self-tests/3" class="view-btn">
                            <i class="fas fa-eye"></i>
                            مشاهده
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>ST-004</td>
                    <td>۱۴.۵۰</td>
                    <td>
                        <a href="/self-tests/4" class="view-btn">
                            <i class="fas fa-eye"></i>
                            مشاهده
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>ST-005</td>
                    <td>۱۷.۰۰</td>
                    <td>
                        <a href="/self-tests/5" class="view-btn">
                            <i class="fas fa-eye"></i>
                            مشاهده
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>ST-006</td>
                    <td>۱۵.۵۰</td>
                    <td>
                        <a href="/self-tests/6" class="view-btn">
                            <i class="fas fa-eye"></i>
                            مشاهده
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>ST-007</td>
                    <td>۲۰.۰۰</td>
                    <td>
                        <a href="/self-tests/7" class="view-btn">
                            <i class="fas fa-eye"></i>
                            مشاهده
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>ST-008</td>
                    <td>۱۲.۰۰</td>
                    <td>
                        <a href="/self-tests/8" class="view-btn">
                            <i class="fas fa-eye"></i>
                            مشاهده
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection