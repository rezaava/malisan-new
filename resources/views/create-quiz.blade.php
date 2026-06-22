@extends('layout.master')

@section('title')
ملیسان | ساخت مسابقه
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-create-quiz.css')}}">
@endsection

@section('mohtava')
<div class="quiz-header">
    <h2>ساخت مسابقه</h2>
    <p>اطلاعات مسابقه را به دقت وارد کنید</p>
</div>

<div class="quiz-form">
    <div class="form-row">
        <div class="form-group">
            <label for="title">title</label>
            <input type="text" id="title" class="form-input" placeholder="عنوان مسابقه را وارد کنید">
        </div>
        <div class="form-group">
            <label for="title-hint">title-hint</label>
            <input type="text" id="title-hint" class="form-input" placeholder="راهنمای عنوان را وارد کنید">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="keyword-hint">keyword-hint</label>
            <input type="text" id="keyword-hint" class="form-input" placeholder="راهنمای کلمه کلیدی را وارد کنید">
        </div>
        <div class="form-group">
            <label for="sponsor-hint">sponsor-hint</label>
            <input type="text" id="sponsor-hint" class="form-input" placeholder="راهنمای اسپانسر را وارد کنید">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="title_min">title_min</label>
            <input type="number" id="title_min" class="form-input" placeholder="حداقل تعداد عنوان">
        </div>
        <div class="form-group">
            <label for="abs_min">abs_min</label>
            <input type="number" id="abs_min" class="form-input" placeholder="حداقل تعداد توضیحات">
        </div>
        <div class="form-group">
            <label for="key_min">key_min</label>
            <input type="number" id="key_min" class="form-input" placeholder="حداقل کلمات کلیدی">
        </div>
        <div class="form-group">
            <label for="keyword_min">keyword_min</label>
            <input type="number" id="keyword_min" class="form-input" placeholder="حداقل کلمه کلیدی">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="title_max">title_max</label>
            <input type="number" id="title_max" class="form-input" placeholder="حداکثر تعداد عنوان">
        </div>
        <div class="form-group">
            <label for="abs_max">abs_max</label>
            <input type="number" id="abs_max" class="form-input" placeholder="حداکثر تعداد توضیحات">
        </div>
        <div class="form-group">
            <label for="key_max">key_max</label>
            <input type="number" id="key_max" class="form-input" placeholder="حداکثر کلمات کلیدی">
        </div>
        <div class="form-group">
            <label for="keyword_max">keyword_max</label>
            <input type="number" id="keyword_max" class="form-input" placeholder="حداکثر کلمه کلیدی">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group full-width">
            <label for="file_max">file_max</label>
            <input type="text" id="file_max" class="form-input" placeholder="حداکثر حجم فایل (مثلاً ۵MB)">
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="submit-btn">
            <i class="fas fa-save"></i>
            ثبت اطلاعات
        </button>
    </div>
</div>
@endsection