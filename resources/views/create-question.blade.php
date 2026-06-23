@extends('layout.master')

@section('title')
ملیسان | طرح سوال
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-create-question.css')}}">
@endsection

@section('mohtava')
<div class="question-container">
    <div class="question-card">
        <div class="question-header">
            <h4><i class="fas fa-question-circle"></i> طرح سوال</h4>
            <p>سوال خود را با دقت وارد کنید و گزینه صحیح را مشخص نمایید</p>
        </div>

        <form class="question-form">
            <div class="form-group">
                <label for="question-text">متن سوال</label>
                <textarea id="question-text" class="form-textarea" rows="3" placeholder="متن سوال را وارد کنید..."></textarea>
            </div>

            <div class="options-grid">
                <div class="form-group">
                    <label for="correct-answer">گزینه صحیح <span class="badge-correct">✓</span></label>
                    <input type="text" id="correct-answer" class="form-input correct-input" placeholder="متن گزینه صحیح را وارد کنید">
                </div>
                <div class="form-group">
                    <label for="wrong-answer-1">گزینه غلط ۱</label>
                    <input type="text" id="wrong-answer-1" class="form-input wrong-input" placeholder="متن گزینه غلط را وارد کنید">
                </div>
                <div class="form-group">
                    <label for="wrong-answer-2">گزینه غلط ۲</label>
                    <input type="text" id="wrong-answer-2" class="form-input wrong-input" placeholder="متن گزینه غلط را وارد کنید">
                </div>
                <div class="form-group">
                    <label for="wrong-answer-3">گزینه غلط ۳</label>
                    <input type="text" id="wrong-answer-3" class="form-input wrong-input" placeholder="متن گزینه غلط را وارد کنید">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-save"></i>
                    ثبت سوال
                </button>
                <button type="reset" class="reset-btn">
                    <i class="fas fa-undo"></i>
                    لغو
                </button>
            </div>
        </form>
    </div>
</div>
@endsection