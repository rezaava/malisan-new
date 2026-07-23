@extends('layout.master')

@section('title')
ملیسان | خودآزمایی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-self-test.css')}}">
@endsection

@section('mohtava')
<div class="self-test-container">
    <div class="self-test-card">
        <div class="self-test-header">
            <div>
                <h4 style="margin:0;font-size:18px;font-weight:700;color:#1a2332;">
                    <i class="fas fa-brain" style="color:#1e6f9f;"></i>
                    خودآزمایی
                </h4>
                <div class="question-number">
                    سوال <span id="currentNum">{{ $num ?? 1 }}</span> از <span id="totalQ">{{ $q_num ?? 10 }}</span>
                </div>
            </div>
            <div class="progress">
                <i class="fas fa-check-circle" style="color:#4caf50;"></i>
                <span id="answeredCount">{{ $num - 1 }}</span> پاسخ داده شده
            </div>
        </div>

        <div class="feedback-box" id="feedbackBox">
            @if(session('feedback'))
                {{ session('feedback') }}
            @endif
        </div>

        <form id="selfTestForm" method="POST" action="{{ route('student.selfTest.next') }}">
            @csrf
            <input type="hidden" name="answer_id" value="{{ $newAnswer->id ?? $answer->id }}">            

            <div class="question-text" id="questionText">
                {{ $question->question ?? 'سوال یافت نشد' }}
            </div>

            <div class="options-list" id="optionsList">
                @php
                    $options = [
                        ['label' => 'الف', 'value' => $question->answer1, 'index' => 0],
                        ['label' => 'ب', 'value' => $question->answer2, 'index' => 1],
                        ['label' => 'ج', 'value' => $question->answer3, 'index' => 2],
                        ['label' => 'د', 'value' => $question->answer4, 'index' => 3],
                    ];
                    shuffle($options);
                @endphp

                @foreach($options as $option)
                    <div class="option-item" data-value="{{ $option['index'] }}" onclick="selectOption(this)">
                        <div class="option-radio"></div>
                        <span class="option-text">{{ $option['value'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit-answer" id="submitBtn">
                    <i class="fas fa-check"></i>
                    ثبت پاسخ
                </button>
                <button type="button" class="btn-report-issue" onclick="openReportModal()">
                    <i class="fas fa-flag"></i>
                    گزارش ایراد سوال
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL REPORT ISSUE ===== --}}
<div class="modal-overlay" id="reportModal">
    <div class="modal-container">
        <div class="modal-header">
            <h4><i class="fas fa-flag" style="color:#f44336;"></i> گزارش ایراد سوال</h4>
            <button class="modal-close" onclick="closeReportModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p style="color:#4a5a6e;font-size:14px;line-height:1.8;margin-bottom:16px;">
                <i class="fas fa-info-circle" style="color:#1e6f9f;"></i>
                لطفاً ایراد موجود در صورت سوال یا گزینه‌های آن را به طور دقیق شرح دهید تا نسبت به بررسی و رفع آن اقدام شود.
            </p>
            <form id="reportForm">
                @csrf
                <input type="hidden" name="question_id" id="reportQuestionId" value="{{ $question->id ?? 0 }}">
                
                <div class="form-group">
                    <label>توضیحات ایراد <span class="required">*</span></label>
                    <textarea name="description" id="reportDescription" 
                              placeholder="مشکل را به طور دقیق توضیح دهید..."
                              required></textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span> / 1000
                    </div>
                </div>
                
                <div class="form-actions-modal">
                    <button type="button" class="btn-cancel" onclick="closeReportModal()">
                        <i class="fas fa-times"></i> انصراف
                    </button>
                    <button type="submit" class="btn-submit-report" id="submitReportBtn">
                        <i class="fas fa-paper-plane"></i> ارسال گزارش
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let selectedOption = null;

    function selectOption(element) {
        if (document.getElementById('submitBtn').disabled) return;
        
        document.querySelectorAll('.option-item').forEach(item => {
            item.classList.remove('selected');
        });
        element.classList.add('selected');
        selectedOption = element;
        document.getElementById('submitBtn').disabled = false;
    }
    document.getElementById('selfTestForm').addEventListener('submit', function(e) {
        if (!selectedOption) {
            e.preventDefault();
            showFeedback('لطفاً یک گزینه را انتخاب کنید', 'warning');
            return;
        }
        
        document.getElementById('submitBtn').disabled = true;
        document.querySelectorAll('.option-item').forEach(item => {
            item.style.pointerEvents = 'none';
        });
        
        // ارسال اندیس گزینه (عدد 0, 1, 2, 3)
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'answer';
        hiddenInput.value = selectedOption.dataset.value; // اینجا عدد 0,1,2,3 است
        this.appendChild(hiddenInput);
    });
    // جلوگیری از ارسال فرم بدون انتخاب گزینه
    document.getElementById('selfTestForm').addEventListener('submit', function(e) {
        if (!selectedOption) {
            e.preventDefault();
            showFeedback('لطفاً یک گزینه را انتخاب کنید', 'warning');
            return;
        }
        
        // غیرفعال کردن دکمه و المان‌ها
        document.getElementById('submitBtn').disabled = true;
        document.querySelectorAll('.option-item').forEach(item => {
            item.style.pointerEvents = 'none';
        });
        
        // اضافه کردن مقدار انتخاب شده به فرم
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'answer';
        hiddenInput.value = selectedOption.dataset.value;
        this.appendChild(hiddenInput);
    });

    function showFeedback(message, type) {
        const box = document.getElementById('feedbackBox');
        box.textContent = message;
        box.className = 'feedback-box show ' + type;
        
        setTimeout(() => {
            box.classList.remove('show');
        }, 3000);
    }

    // اگر پیام قبلی وجود داشت نمایش بده
    @if(session('feedback'))
        document.addEventListener('DOMContentLoaded', function() {
            const box = document.getElementById('feedbackBox');
            box.className = 'feedback-box show';
        });
    @endif
    // ===== Report Modal =====
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
        const modal = document.getElementById('reportModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // ===== Close modal on overlay click =====
    document.getElementById('reportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReportModal();
        }
    });

    // ===== Close modal on Escape =====
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReportModal();
        }
    });

    // ===== Character counter =====
    document.getElementById('reportDescription').addEventListener('input', function() {
        const count = this.value.length;
        const charCount = document.getElementById('charCount');
        charCount.textContent = count;
        
        if (count > 1000) {
            charCount.classList.add('error');
        } else {
            charCount.classList.remove('error');
        }
    });

    // ===== Submit Report =====
    document.getElementById('reportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitReportBtn');
        const description = document.getElementById('reportDescription').value.trim();
        const questionId = document.getElementById('reportQuestionId').value;
        
        if (!description) {
            showToast('لطفاً توضیحات را وارد کنید', 'error');
            document.getElementById('reportDescription').focus();
            return;
        }
        
        if (description.length < 10) {
            showToast('توضیحات باید حداقل ۱۰ کاراکتر باشد', 'error');
            document.getElementById('reportDescription').focus();
            return;
        }
        
        if (description.length > 1000) {
            showToast('توضیحات نباید بیشتر از ۱۰۰۰ کاراکتر باشد', 'error');
            document.getElementById('reportDescription').focus();
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> در حال ارسال...';
        
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
                let errorMsg = data.message || 'خطا در ثبت گزارش';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('\n');
                }
                showToast('❌ ' + errorMsg, 'error');
            }
        })
        .catch(error => {
            showToast('❌ خطا در ارتباط با سرور. لطفاً دوباره تلاش کنید.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> ارسال گزارش';
        });
    });

    // ===== Toast Message =====
    function showToast(message, type = 'success') {
        const oldToast = document.querySelector('.toast-message');
        if (oldToast) {
            oldToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = 'toast-message ' + type;
        toast.innerHTML = `
            <span>${message}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                toast.style.transition = 'all 0.4s ease';
                setTimeout(() => toast.remove(), 400);
            }
        }, 5000);
    }
</script>
@endsection