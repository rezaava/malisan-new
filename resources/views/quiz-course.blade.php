@extends('layout.master')

@section('title')
ملیسان | آزمون درس
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-quiz-course.css')}}">
@endsection

@section('mohtava')
<div class="quiz-container">
    <div class="quiz-progress">
        <span class="quiz-counter">1/10</span>
        <span class="quiz-status"><i class="fas fa-check-circle"></i> پاسخ داده شده</span>
    </div>

    <div class="quiz-card">
        <div class="quiz-question">
            <p class="quiz-question-text">
                کدام مورد جزو اقدامات بیولوژیک برای کنترل فرسایش بادی نیست؟
            </p>
        </div>

        <form class="quiz-form" id="quiz-form" data-correct="2">
            <div class="options-grid">
                <label class="option-item">
                    <input type="radio" name="answer" value="1">
                    <span class="custom_answer_wrapper" data-value="1">قلمه‌زدن</span>
                </label>
                <label class="option-item">
                    <input type="radio" name="answer" value="2">
                    <span class="custom_answer_wrapper" data-value="2">مالچ نفتی</span>
                </label>
                <label class="option-item">
                    <input type="radio" name="answer" value="3">
                    <span class="custom_answer_wrapper" data-value="3">بذرکاری</span>
                </label>
                <label class="option-item">
                    <input type="radio" name="answer" value="4">
                    <span class="custom_answer_wrapper" data-value="4">نهال‌کاری</span>
                </label>
            </div>

            <div class="quiz-actions">
                <button type="button" class="submit-quiz-btn" id="submit-btn">ثبت پاسخ</button>
            </div>
        </form>

        <div class="designer-note">
            طراح سوال: عارفه عرفانی منش
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.querySelectorAll('.custom_answer_wrapper').forEach(function(item) {
        item.addEventListener('click', function(e) {
            var parentLabel = this.closest('.option-item');
            var radioInput = parentLabel.querySelector('input[type="radio"]');

            document.querySelectorAll('.custom_answer_wrapper').forEach(function(el) {
                el.classList.remove('answer-selected');
            });

            document.querySelectorAll('.option-item input[type="radio"]').forEach(function(el) {
                el.checked = false;
            });

            this.classList.add('answer-selected');
            radioInput.checked = true;
        });
    });

    document.getElementById('submit-btn').addEventListener('click', function() {
        var form = document.getElementById('quiz-form');
        var correct = form.getAttribute('data-correct');
        var selected = document.querySelector('input[name="answer"]:checked');

        if (!selected) {
            alert('لطفاً یکی از گزینه‌ها را انتخاب کنید.');
            return;
        }

        var userValue = selected.value;

        document.querySelectorAll('.custom_answer_wrapper').forEach(function(span) {
            var val = span.getAttribute('data-value');
            span.classList.remove('answer-selected', 'answer-correct', 'answer-wrong');

            if (val === correct) {
                span.classList.add('answer-correct');
            } else if (val === userValue) {
                span.classList.add('answer-wrong');
            }
        });

        this.style.display = 'none';

        setTimeout(function() {
            document.getElementById('quiz-form').submit();
        }, 3000);
    });
</script>
@endsection