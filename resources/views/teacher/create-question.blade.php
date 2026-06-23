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

        @if(session('success'))
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                <ul style="margin: 0; padding-right: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="question-form" action="{{ route('question.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="question-text">متن سوال</label>
                <textarea id="question-text" name="question" class="form-textarea" rows="3" placeholder="متن سوال را وارد کنید...">{{ old('question') }}</textarea>
            </div>

            <div class="options-grid">
                <div class="form-group option-item" data-option="0">
                    <label for="option-0">
                        گزینه ۱ 
                        <span class="badge-correct" style="display: none;">✓ صحیح</span>
                    </label>
                    <div class="option-input-wrapper">
                        <input type="text" id="option-0" name="options[]" class="form-input" placeholder="متن گزینه ۱ را وارد کنید" value="{{ old('options.0') }}">
                        <button type="button" class="set-correct-btn" data-option="0" title="تنظیم به عنوان پاسخ صحیح">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group option-item" data-option="1">
                    <label for="option-1">
                        گزینه ۲
                        <span class="badge-correct" style="display: none;">✓ صحیح</span>
                    </label>
                    <div class="option-input-wrapper">
                        <input type="text" id="option-1" name="options[]" class="form-input" placeholder="متن گزینه ۲ را وارد کنید" value="{{ old('options.1') }}">
                        <button type="button" class="set-correct-btn" data-option="1" title="تنظیم به عنوان پاسخ صحیح">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group option-item" data-option="2">
                    <label for="option-2">
                        گزینه ۳
                        <span class="badge-correct" style="display: none;">✓ صحیح</span>
                    </label>
                    <div class="option-input-wrapper">
                        <input type="text" id="option-2" name="options[]" class="form-input" placeholder="متن گزینه ۳ را وارد کنید" value="{{ old('options.2') }}">
                        <button type="button" class="set-correct-btn" data-option="2" title="تنظیم به عنوان پاسخ صحیح">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group option-item" data-option="3">
                    <label for="option-3">
                        گزینه ۴
                        <span class="badge-correct" style="display: none;">✓ صحیح</span>
                    </label>
                    <div class="option-input-wrapper">
                        <input type="text" id="option-3" name="options[]" class="form-input" placeholder="متن گزینه ۴ را وارد کنید" value="{{ old('options.3') }}">
                        <button type="button" class="set-correct-btn" data-option="3" title="تنظیم به عنوان پاسخ صحیح">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </div>
                </div>
            </div>

            <input type="hidden" name="correct_answer" id="correct_answer" value="{{ old('correct_answer', 0) }}">

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

<style>
    .option-item {
        transition: all 0.3s ease;
        padding: 10px;
        border-radius: 8px;
        border: 2px solid transparent;
    }
    
    .option-item.is-correct {
        background: #e8f5e9;
        border-color: #4caf50;
    }
    
    .option-item.is-correct .badge-correct {
        display: inline-block !important;
    }
    
    .option-item .badge-correct {
        display: none;
        background: #4caf50;
        color: white;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        margin-right: 5px;
    }
    
    .option-input-wrapper {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .option-input-wrapper .form-input {
        flex: 1;
    }
    
    .set-correct-btn {
        background: none;
        border: 2px solid #ddd;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        color: #999;
        flex-shrink: 0;
        font-size: 18px;
    }
    
    .set-correct-btn:hover {
        border-color: #4caf50;
        color: #4caf50;
        transform: scale(1.1);
    }
    
    .set-correct-btn.is-correct-btn {
        border-color: #4caf50;
        background: #4caf50;
        color: white;
    }
    
    .set-correct-btn.is-correct-btn:hover {
        background: #388e3c;
        border-color: #388e3c;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const optionItems = document.querySelectorAll('.option-item');
        const correctAnswerInput = document.getElementById('correct_answer');
        
        function clearCorrectStyles() {
            optionItems.forEach(item => {
                item.classList.remove('is-correct');
                const btn = item.querySelector('.set-correct-btn');
                if (btn) {
                    btn.classList.remove('is-correct-btn');
                }
            });
        }
        
        function setCorrectOption(optionIndex) {
            clearCorrectStyles();
            
            const selectedItem = document.querySelector(`.option-item[data-option="${optionIndex}"]`);
            if (selectedItem) {
                selectedItem.classList.add('is-correct');
                const btn = selectedItem.querySelector('.set-correct-btn');
                if (btn) {
                    btn.classList.add('is-correct-btn');
                }
                correctAnswerInput.value = optionIndex;
            }
        }
        
        document.querySelectorAll('.set-correct-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const optionIndex = this.getAttribute('data-option');
                setCorrectOption(parseInt(optionIndex));
            });
        });
        
        const oldCorrect = correctAnswerInput.value;
        if (oldCorrect) {
            setCorrectOption(parseInt(oldCorrect));
        } else {
            setCorrectOption(0);
        }
    });
</script>
@endsection