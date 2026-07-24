{{-- فایل: resources/views/student/self-test.blade.php --}}
@extends('layout.master')

@section('title')
خودآزمایی
@endsection

@section('head')
<style>
    .self-test-wrapper {
        max-width: 650px;
        margin: 30px auto;
        padding: 25px 30px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        font-family: 'Tahoma', sans-serif;
    }

    .test-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 14px;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 22px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .test-header .title {
        font-weight: 700;
        color: #1a2332;
        font-size: 16px;
    }

    .test-header .progress {
        color: #666;
        font-size: 14px;
    }

    .test-header .progress i {
        color: #4caf50;
        margin-left: 5px;
    }

    .feedback-box {
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 18px;
        border: 1px solid;
        display: none;
        font-weight: 500;
        font-size: 15px;
    }

    .feedback-box.correct {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
        display: block;
    }

    .feedback-box.incorrect {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
        display: block;
    }

    .question-text {
        font-size: 18px;
        margin-bottom: 20px;
        padding: 15px 18px;
        background: #fafafa;
        border-radius: 8px;
        border-right: 4px solid #1e6f9f;
        line-height: 1.8;
        min-height: 55px;
    }

    .option-item {
        padding: 12px 18px;
        margin: 6px 0;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        cursor: pointer;
        background: #fff;
        transition: all 0.2s ease;
        font-size: 15px;
    }

    .option-item:hover {
        background: #f5f7fa;
        border-color: #b8c9e0;
    }

    .option-item.selected {
        background: #e3f2fd !important;
        border-color: #1e6f9f !important;
    }

    .option-item.correct-answer {
        background: #d4edda !important;
        border-color: #28a745 !important;
    }

    .option-item.wrong-answer {
        background: #f8d7da !important;
        border-color: #dc3545 !important;
    }

    .option-item.disabled {
        pointer-events: none;
        opacity: 0.85;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .btn-primary {
        padding: 10px 28px;
        background: #1e6f9f;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background: #155a82;
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-outline {
        padding: 10px 20px;
        background: #f5f5f5;
        border: 1px solid #d0d0d0;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #333;
    }

    .btn-outline:hover {
        background: #ececec;
        border-color: #b0b0b0;
    }

    .error-box {
        margin-top: 15px;
        padding: 10px 15px;
        border-radius: 8px;
        display: none;
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
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
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-container {
        background: #fff;
        border-radius: 12px;
        max-width: 500px;
        width: 100%;
        padding: 25px 28px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        animation: modalFadeIn 0.3s ease;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .modal-header h4 {
        margin: 0;
        font-size: 18px;
        color: #1a2332;
    }

    .modal-header h4 i {
        color: #f44336;
        margin-left: 8px;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #888;
        transition: color 0.2s;
        line-height: 1;
    }

    .modal-close:hover {
        color: #333;
    }

    .modal-body p {
        color: #4a5a6e;
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 16px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        font-size: 14px;
        color: #333;
    }

    .form-group label .required {
        color: #dc3545;
    }

    .form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        resize: vertical;
        min-height: 100px;
        transition: border-color 0.2s;
        font-family: inherit;
        box-sizing: border-box;
    }

    .form-group textarea:focus {
        outline: none;
        border-color: #1e6f9f;
        box-shadow: 0 0 0 3px rgba(30,111,159,0.12);
    }

    .char-counter {
        text-align: left;
        font-size: 12px;
        color: #888;
        margin-top: 4px;
    }

    .char-counter.error {
        color: #dc3545;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .modal-actions .btn-cancel {
        padding: 8px 20px;
        background: #f1f1f1;
        border: 1px solid #ccc;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.2s;
        font-size: 14px;
    }

    .modal-actions .btn-cancel:hover {
        background: #e0e0e0;
    }

    .modal-actions .btn-submit {
        padding: 8px 24px;
        background: #1e6f9f;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
    }

    .modal-actions .btn-submit:hover {
        background: #155a82;
    }

    .modal-actions .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #fff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Toast */
    .toast-message {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        padding: 12px 24px;
        border-radius: 8px;
        background: #333;
        color: #fff;
        font-size: 14px;
        z-index: 99999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.4s ease;
        white-space: nowrap;
    }

    .toast-message.success {
        background: #28a745;
    }

    .toast-message.error {
        background: #dc3545;
    }

    .toast-close {
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
        font-size: 20px;
        opacity: 0.8;
        transition: opacity 0.2s;
        line-height: 1;
    }

    .toast-close:hover {
        opacity: 1;
    }

    .prev-question-box {
        background: #f8f9fa;
        padding: 15px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .prev-question-box .label {
        font-size: 14px;
        color: #888;
        margin-bottom: 6px;
    }

    .prev-question-box .qtext {
        font-size: 16px;
        margin-bottom: 12px;
    }

    hr.divider {
        margin: 20px 0;
        border: 0;
        border-top: 1px dashed #ddd;
    }
</style>
@endsection

@section('mohtava')

<div class="self-test-wrapper">

    {{-- هدر --}}
    <div class="test-header">
        <span class="title">سوال <span id="currentNum">{{ $num ?? 1 }}</span> از <span id="totalQ">{{ $q_num ?? 10 }}</span></span>
        <span class="progress">
            <i class="fas fa-check-circle"></i>
            <span id="answeredCount">{{ ($num ?? 1) - 1 }}</span> پاسخ داده شده
        </span>
    </div>

    {{-- باکس فیدبک (فقط پیام) --}}
    <div id="feedbackBox" class="feedback-box"></div>

    {{-- نمایش سوال قبلی برای حالت غیر AJAX (رفرش) --}}
    @if($showQuiz == 1 && isset($previousQuestion) && $previousQuestion && !request()->ajax())
        <div class="prev-question-box">
            <div class="label">سوال قبلی:</div>
            <div class="qtext">{{ $previousQuestion->question }}</div>
            <div>
                @php
                    $shuffledPrev = $shuffledOptionsPrev ?? [];
                @endphp
                @foreach($shuffledPrev as $index => $opt)
                    @php
                        $isUserAnswer = (isset($userAnswerIndex) && $userAnswerIndex == $index);
                        $isCorrectAnswer = (isset($correctIndexPrev) && $correctIndexPrev == $index);
                        $class = '';
                        if ($isCorrectAnswer) {
                            $class = 'correct-answer';
                        } elseif ($isUserAnswer && !$isCorrectAnswer) {
                            $class = 'wrong-answer';
                        }
                    @endphp
                    <div class="option-item {{ $class }} disabled">{{ $opt['value'] }}</div>
                @endforeach
            </div>
        </div>
        <hr class="divider">
    @endif

    {{-- سوال فعلی --}}
    <div id="questionContainer">
        <div class="question-text" id="questionText">
            {{ $question->question ?? 'سوال یافت نشد' }}
        </div>

        <div id="optionsList">
            @php
                if (isset($shuffledOptions) && count($shuffledOptions) > 0) {
                    $options = $shuffledOptions;
                } else {
                    $options = [
                        ['value' => $question->answer1 ?? ''],
                        ['value' => $question->answer2 ?? ''],
                        ['value' => $question->answer3 ?? ''],
                        ['value' => $question->answer4 ?? ''],
                    ];
                }
            @endphp

            @foreach($options as $index => $opt)
                <div class="option-item" data-value="{{ $index }}" onclick="selectOption(this)">
                    {{ $opt['value'] }}
                </div>
            @endforeach
        </div>
    </div>

    {{-- دکمه‌ها --}}
    <div class="form-actions">
        <button id="submitBtn" class="btn-primary">
            <i class="fas fa-check"></i> ثبت پاسخ
        </button>
        <button type="button" class="btn-outline" onclick="openReportModal()">
            <i class="fas fa-flag"></i> گزارش ایراد
        </button>
    </div>

    {{-- باکس خطا --}}
    <div id="errorBox" class="error-box"></div>

    {{-- هیدن‌ها --}}
    <input type="hidden" id="answerId" value="{{ $newAnswer->id ?? $answer->id }}">
    <input type="hidden" id="showQuiz" value="{{ $showQuiz ?? 0 }}">
    <input type="hidden" id="isFirstQuestion" value="{{ $isFirstQuestion ?? false }}">
</div>

{{-- مودال گزارش ایراد --}}
<div class="modal-overlay" id="reportModal">
    <div class="modal-container">
        <div class="modal-header">
            <h4><i class="fas fa-flag"></i> گزارش ایراد سوال</h4>
            <button class="modal-close" onclick="closeReportModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p><i class="fas fa-info-circle" style="color:#1e6f9f;"></i> لطفاً ایراد موجود در صورت سوال یا گزینه‌های آن را به طور دقیق شرح دهید تا نسبت به بررسی و رفع آن اقدام شود.</p>
            <form id="reportForm">
                @csrf
                <input type="hidden" name="question_id" id="reportQuestionId" value="{{ $question->id ?? 0 }}">
                <div class="form-group">
                    <label>توضیحات ایراد <span class="required">*</span></label>
                    <textarea name="description" id="reportDescription" placeholder="مشکل را به طور دقیق توضیح دهید..." required></textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span> / 1000
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeReportModal()">انصراف</button>
                    <button type="submit" class="btn-submit" id="submitReportBtn">
                        <i class="fas fa-paper-plane"></i> ارسال گزارش
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // =============================================
    // منطق اصلی خودآزمایی (دقیقاً مانند قبل)
    // =============================================
    let selectedOption = null;
    let isLoading = false;
    const showQuiz = parseInt(document.getElementById('showQuiz').value);

    // انتخاب گزینه با onclick
    function selectOption(element) {
        if (isLoading) return;
        document.querySelectorAll('.option-item').forEach(item => {
            item.classList.remove('selected');
        });
        element.classList.add('selected');
        selectedOption = element;
        document.getElementById('submitBtn').disabled = false;
    }

    // ثبت پاسخ
    document.getElementById('submitBtn').addEventListener('click', function(e) {
        e.preventDefault();
        if (isLoading) return;

        if (!selectedOption) {
            showError('لطفاً یک گزینه را انتخاب کنید.');
            return;
        }

        isLoading = true;
        const answerId = document.getElementById('answerId').value;
        const selectedValue = selectedOption.dataset.value;

        this.disabled = true;
        document.querySelectorAll('.option-item').forEach(el => el.style.pointerEvents = 'none');

        fetch('{{ route('student.selfTest.next') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                answer_id: answerId,
                answer: selectedValue
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                showError(data.message || 'خطا در ارسال پاسخ');
                resetAfterError();
                return;
            }

            if (data.finished) {
                window.location.href = '{{ route("student.selfTest.results", ["quiz_id" => ":quiz_id"]) }}'
                    .replace(':quiz_id', data.quiz_id);
                return;
            }

            // نمایش پاسخ قبلی اگر show_quiz == 1
            if (showQuiz === 1 && data.previous && data.previous.question) {
                showPreviousQuestion(data.previous);
            }

            const next = data.next;
            setTimeout(function() {
                updateQuestion(next);
                resetAfterSuccess();
            }, showQuiz === 1 ? 3000 : 0);
        })
        .catch(function() {
            showError('خطا در ارتباط با سرور.');
            resetAfterError();
        });
    });

    // نمایش سوال قبلی در همان باکس
    function showPreviousQuestion(prev) {
        const fb = document.getElementById('feedbackBox');
        fb.className = 'feedback-box ' + (prev.is_correct ? 'correct' : 'incorrect');
        fb.textContent = (prev.is_correct ? '✅' : '❌') + ' ' + (prev.is_correct ? 'پاسخ شما صحیح بود!' : 'پاسخ شما صحیح نبود.');

        document.getElementById('questionText').textContent = prev.question;

        const container = document.getElementById('optionsList');
        container.innerHTML = '';
        prev.options.forEach(function(opt, index) {
            const div = document.createElement('div');
            div.className = 'option-item disabled';
            if (index === prev.correct_index) {
                div.classList.add('correct-answer');
            } else if (index === prev.user_answer_index && index !== prev.correct_index) {
                div.classList.add('wrong-answer');
            }
            div.textContent = opt.value;
            container.appendChild(div);
        });

        document.getElementById('submitBtn').disabled = true;
    }

    // بارگذاری سوال بعدی
    function updateQuestion(next) {
        document.getElementById('questionText').textContent = next.question_text;

        const container = document.getElementById('optionsList');
        container.innerHTML = '';
        next.options.forEach(function(opt, index) {
            const div = document.createElement('div');
            div.className = 'option-item';
            div.dataset.value = index;
            div.textContent = opt.value;
            div.setAttribute('onclick', 'selectOption(this)');
            container.appendChild(div);
        });

        document.getElementById('currentNum').textContent = next.num;
        document.getElementById('answeredCount').textContent = next.num - 1;
        document.getElementById('answerId').value = next.answer_id;
        document.getElementById('feedbackBox').className = 'feedback-box';
        document.getElementById('feedbackBox').textContent = '';
        document.getElementById('submitBtn').disabled = false;
    }

    // کمک‌ها
    function showError(msg) {
        const box = document.getElementById('errorBox');
        box.textContent = '⚠️ ' + msg;
        box.style.display = 'block';
        setTimeout(() => { box.style.display = 'none'; }, 4000);
    }

    function resetAfterError() {
        isLoading = false;
        document.getElementById('submitBtn').disabled = false;
        document.querySelectorAll('.option-item').forEach(el => el.style.pointerEvents = '');
    }

    function resetAfterSuccess() {
        isLoading = false;
        document.getElementById('submitBtn').disabled = false;
        document.querySelectorAll('.option-item').forEach(el => el.style.pointerEvents = '');
        selectedOption = null;
    }

    // =============================================
    // مودال گزارش ایراد (دقیقاً مانند قبل)
    // =============================================
    function openReportModal() {
        const modal = document.getElementById('reportModal');
        const questionId = document.getElementById('reportQuestionId').value;
        if (!questionId || questionId == 0) {
            showToast('خطا در شناسایی سوال', 'error');
            return;
        }
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        document.getElementById('reportDescription').focus();
    }

    function closeReportModal() {
        document.getElementById('reportModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('reportModal').addEventListener('click', function(e) {
        if (e.target === this) closeReportModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeReportModal();
    });

    document.getElementById('reportDescription').addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('charCount').textContent = count;
        if (count > 1000) {
            document.getElementById('charCount').classList.add('error');
        } else {
            document.getElementById('charCount').classList.remove('error');
        }
    });

    document.getElementById('reportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('submitReportBtn');
        const description = document.getElementById('reportDescription').value.trim();
        const questionId = document.getElementById('reportQuestionId').value;

        if (!description || description.length < 10) {
            showToast('لطفاً توضیحات را حداقل ۱۰ کاراکتر وارد کنید', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> در حال ارسال...';

        fetch('{{ route("question.report.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                question_id: questionId,
                description: description
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('✅ گزارش شما با موفقیت ثبت شد.', 'success');
                closeReportModal();
                document.getElementById('reportDescription').value = '';
                document.getElementById('charCount').textContent = '0';
            } else {
                showToast('❌ ' + (data.message || 'خطا در ثبت گزارش'), 'error');
            }
        })
        .catch(() => showToast('❌ خطا در ارتباط با سرور.', 'error'))
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> ارسال گزارش';
        });
    });

    // =============================================
    // Toast
    // =============================================
    function showToast(message, type) {
        const old = document.querySelector('.toast-message');
        if (old) old.remove();

        const toast = document.createElement('div');
        toast.className = 'toast-message ' + type;
        toast.innerHTML = `
            <span>${message}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                setTimeout(() => toast.remove(), 400);
            }
        }, 5000);
    }
</script>

@endsection