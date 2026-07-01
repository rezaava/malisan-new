@extends('layout.master')

@section('title')
ملیسان | خودآزمایی
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-self-test.css')}}">
<style>
    .self-test-container {
        max-width: 800px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .self-test-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        padding: 30px;
    }

    .self-test-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f4f9;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .self-test-header .progress {
        font-weight: 600;
        color: #1e6f9f;
        font-size: 15px;
    }

    .question-number {
        font-size: 14px;
        color: #6b7a8f;
        margin-bottom: 12px;
    }

    .question-number span {
        font-weight: 700;
        color: #1a2332;
    }

    .question-text {
        font-size: 18px;
        font-weight: 600;
        color: #1a2332;
        margin-bottom: 24px;
        line-height: 1.8;
        padding: 16px 20px;
        background: #f8fafc;
        border-radius: 12px;
        border-right: 4px solid #1e6f9f;
    }

    .options-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 24px;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        border: 2px solid #e8edf3;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafbfc;
    }

    .option-item:hover {
        border-color: #1e6f9f;
        background: #f0f7fe;
    }

    .option-item.selected {
        border-color: #1e6f9f;
        background: #e3f2fd;
    }

    .option-item.correct {
        border-color: #4caf50;
        background: #e8f5e9;
    }

    .option-item.wrong {
        border-color: #f44336;
        background: #ffebee;
    }

    .option-item .option-radio {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #d0d7e2;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .option-item.selected .option-radio {
        border-color: #1e6f9f;
        background: #1e6f9f;
    }

    .option-item.selected .option-radio::after {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #fff;
    }

    .option-item .option-label {
        font-weight: 600;
        color: #1a2332;
        flex-shrink: 0;
        width: 24px;
    }

    .option-item .option-text {
        flex: 1;
        font-size: 15px;
        color: #1a2332;
    }

    .form-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        padding-top: 20px;
        border-top: 2px solid #f0f4f9;
        flex-wrap: wrap;
    }

    .btn-submit-answer {
        padding: 12px 48px;
        background: linear-gradient(135deg, #1e6f9f, #155a82);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit-answer:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(30, 111, 159, 0.3);
    }

    .feedback-box {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: none;
        font-weight: 600;
        text-align: center;
    }

    .feedback-box.show {
        display: block;
    }

    .feedback-box.correct {
        background: #e8f5e9;
        border: 1px solid #4caf50;
        color: #2e7d32;
    }

    .feedback-box.wrong {
        background: #ffebee;
        border: 1px solid #f44336;
        color: #c62828;
    }

    .feedback-box.warning {
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
    }

    @media (max-width: 768px) {
        .self-test-card {
            padding: 16px;
        }
        .question-text {
            font-size: 16px;
            padding: 12px 16px;
        }
        .form-actions {
            flex-direction: column;
        }
        .btn-submit-answer {
            width: 100%;
            justify-content: center;
        }
    }
    /* ===== BUTTON REPORT ===== */
.btn-report-issue {
    padding: 12px 24px;
    background: transparent;
    color: #f44336;
    border: 2px solid #f44336;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-report-issue:hover {
    background: #f44336;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(244, 67, 54, 0.3);
}

/* ===== MODAL ===== */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    z-index: 99999;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: fadeIn 0.3s ease;
}

.modal-overlay.active {
    display: flex;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.modal-container {
    background: #fff;
    border-radius: 24px;
    width: 100%;
    max-width: 550px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.2);
    animation: slideUp 0.3s ease;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 28px;
    border-bottom: 2px solid #f0f4f9;
    position: sticky;
    top: 0;
    background: #fff;
    border-radius: 24px 24px 0 0;
    z-index: 10;
}

.modal-header h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #1a2332;
}

.modal-header h4 i {
    margin-left: 8px;
}

.modal-close {
    background: #f0f4f9;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4a5a6e;
    font-size: 18px;
}

.modal-close:hover {
    background: #ffebee;
    color: #c62828;
    transform: rotate(90deg);
}

.modal-body {
    padding: 24px 28px 30px;
}

.modal-body .form-group {
    margin-bottom: 18px;
}

.modal-body .form-group label {
    display: block;
    font-weight: 600;
    font-size: 14px;
    color: #1a2332;
    margin-bottom: 6px;
}

.modal-body .form-group label .required {
    color: #f44336;
    margin-right: 3px;
}

.modal-body .form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e8edf3;
    border-radius: 12px;
    font-size: 14px;
    font-family: inherit;
    background: #fafbfc;
    transition: all 0.3s ease;
    min-height: 120px;
    resize: vertical;
}

.modal-body .form-group textarea:focus {
    border-color: #1e6f9f;
    outline: none;
    box-shadow: 0 0 0 4px rgba(30, 111, 159, 0.1);
    background: #fff;
}

.modal-body .char-counter {
    text-align: left;
    font-size: 12px;
    color: #6b7a8f;
    margin-top: 4px;
}

.modal-body .char-counter .error {
    color: #f44336;
}

.form-actions-modal {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 8px;
}

.btn-cancel {
    padding: 10px 28px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    border: 2px solid #e8edf3;
    background: transparent;
    color: #4a5a6e;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-cancel:hover {
    background: #f0f4f9;
}

.btn-submit-report {
    padding: 10px 32px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #f44336, #c62828);
    color: #fff;
}

.btn-submit-report:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(244, 67, 54, 0.3);
}

.btn-submit-report:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.loading-spinner {
    display: inline-block;
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid #fff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.toast-message {
    position: fixed;
    bottom: 30px;
    right: 30px;
    padding: 14px 24px;
    border-radius: 12px;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    z-index: 999999;
    animation: slideUp 0.4s ease;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    max-width: 400px;
}

.toast-message.success {
    background: linear-gradient(135deg, #4caf50, #388e3c);
}

.toast-message.error {
    background: linear-gradient(135deg, #f44336, #c62828);
}

.toast-message .toast-close {
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
    margin-left: 12px;
    font-size: 16px;
    opacity: 0.7;
    transition: all 0.2s;
}

.toast-message .toast-close:hover {
    opacity: 1;
}

@media (max-width: 768px) {
    .modal-container {
        max-height: 95vh;
        border-radius: 20px;
    }
    .modal-header {
        padding: 16px 18px;
    }
    .modal-body {
        padding: 18px 16px 24px;
    }
    .form-actions-modal {
        flex-direction: column;
    }
    .btn-cancel,
    .btn-submit-report {
        width: 100%;
        justify-content: center;
    }
    .toast-message {
        bottom: 15px;
        right: 15px;
        left: 15px;
        max-width: none;
    }
}
</style>
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
            </div>
        </form>
    </div>
</div>

{{-- ===== دکمه گزارش ایراد ===== --}}
<div class="form-actions">
    <button type="button" class="btn-submit-answer" id="submitBtn">
        <i class="fas fa-check"></i>
        ثبت پاسخ
    </button>
    
    {{-- دکمه گزارش ایراد سوال --}}
    <button type="button" class="btn-report-issue" onclick="openReportModal()">
        <i class="fas fa-flag"></i>
        گزارش ایراد سوال
    </button>
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