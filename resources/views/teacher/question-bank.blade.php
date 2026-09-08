@extends('layout.master')

@section('title')
ملیسان | بانک سوالات
@endsection

@section('head')

<link rel="stylesheet" href="{{ asset('css/style-question-bank.css') }}">
<link rel="stylesheet" href="{{ asset('css/badge.css') }}">
<style>
.stat-filter{cursor:pointer;transition:all .3s ease;position:relative}
.stat-filter:hover{transform:translateY(-3px);box-shadow:0 8px 25px rgba(0,0,0,.08)}
.stat-filter.active-filter{border-color:#1a2332!important;border-width:2px!important;background:#f0f4f9!important;box-shadow:0 4px 15px rgba(26,35,50,.1)}
.stat-filter.active-filter::after{content:'✓';position:absolute;top:5px;right:10px;color:#1a2332;font-weight:bold;font-size:14px}
.stat-filter .stat-number{transition:color .3s ease}
.stat-filter:hover .stat-number{color:#1a2332!important}
.stat-card.excellent .stat-number{color:#2e7d32}
.stat-card.good .stat-number{color:#1565c0}
.stat-card.medium .stat-number{color:#e65100}
.stat-card.bad .stat-number{color:#c62828}
.stat-card.pending .stat-number{color:#6a1b9a}
.stat-card.starred .stat-number{color:#f9a825}
.stat-card.teacher-questions .stat-number{color:#00838f}
.stat-card.test-questions .stat-number{color:#1565c0}
.stat-card.short-questions .stat-number{color:#7b1fa2}
.level-badge.teacher-level{background:#6a1b9a;color:#fff}
.question-type-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:600}
.question-type-badge.test{background:#e3f2fd;color:#1565c0}
.question-type-badge.short{background:#f3e5f5;color:#7b1fa2}
.short-answer-box{background:#f8f5ff;border:1px solid #e1bee7;border-radius:10px;padding:12px 15px}
.short-answer-box .answer-title{font-size:12px;color:#7b1fa2;font-weight:600;margin-bottom:5px}
.short-answer-box .answer-text{font-size:14px;font-weight:600;color:#333}
@keyframes countUp{from{opacity:0;transform:scale(.8)}to{opacity:1;transform:scale(1)}}
.stat-number.animated{animation:countUp .5s ease}
</style>
@endsection

@section('mohtava')

<div class="bank-container">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3 flex-wrap gap-2">
        <div class="info-badge course-badge">
            <span class="badge-icon"><i class="fas fa-book-open"></i></span>
            <span class="badge-label">بانک سوالات در درس:</span>
            <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
        </div>
        <div>@include('layout.backbtn')</div>
    </div>


<div class="row g-3 mb-4">
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-card bg-white rounded-3 p-3 text-center border h-100 stat-filter active-filter" data-filter="all" data-label="کل سوالات">
            <div class="stat-number fs-3 fw-bold text-dark">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-secondary small">کل سوالات</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-card test-questions bg-white rounded-3 p-3 text-center border h-100 stat-filter" data-filter="type_0" data-label="تستی">
            <div class="stat-number fs-3 fw-bold">{{ cou }}</div>
            <div class="text-secondary small">تستی</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-card short-questions bg-white rounded-3 p-3 text-center border h-100 stat-filter" data-filter="type_1" data-label="پاسخ کوتاه">
            <div class="stat-number fs-3 fw-bold">{{ $stats['short_questions'] ?? 0 }}</div>
            <div class="text-secondary small">پاسخ کوتاه</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-card excellent bg-white rounded-3 p-3 text-center border h-100 stat-filter" data-filter="1" data-label="عالی">
            <div class="stat-number fs-3 fw-bold">{{ $stats['excellent'] ?? 0 }}</div>
            <div class="text-secondary small">عالی</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-card good bg-white rounded-3 p-3 text-center border h-100 stat-filter" data-filter="2" data-label="خوب">
            <div class="stat-number fs-3 fw-bold">{{ $stats['good'] ?? 0 }}</div>
            <div class="text-secondary small">خوب</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-card medium bg-white rounded-3 p-3 text-center border h-100 stat-filter" data-filter="3" data-label="متوسط">
            <div class="stat-number fs-3 fw-bold">{{ $stats['medium'] ?? 0 }}</div>
            <div class="text-secondary small">متوسط</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-card bad bg-white rounded-3 p-3 text-center border h-100 stat-filter" data-filter="4" data-label="ضعیف">
            <div class="stat-number fs-3 fw-bold">{{ $stats['bad'] ?? 0 }}</div>
            <div class="text-secondary small">ضعیف</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-card pending bg-white rounded-3 p-3 text-center border h-100 stat-filter" data-filter="null" data-label="در انتظار تایید">
            <div class="stat-number fs-3 fw-bold">{{ $stats['pending'] ?? 0 }}</div>
            <div class="text-secondary small">در انتظار تایید</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-card starred bg-white rounded-3 p-3 text-center border h-100 stat-filter" data-filter="starred" data-label="ستاره دار">
            <div class="stat-number fs-3 fw-bold">{{ $stats['starred'] ?? 0 }}</div>
            <div class="text-secondary small">ستاره دار</div>
        </div>
    </div>
    <div class="col-6 col-md-3 col-lg">
        <div class="stat-card teacher-questions bg-white rounded-3 p-3 text-center border h-100 stat-filter" data-filter="5" data-label="سوال استاد">
            <div class="stat-number fs-3 fw-bold">{{ $stats['teacher_questions'] ?? 0 }}</div>
            <div class="text-secondary small">سوال استاد</div>
        </div>
    </div>
</div>

<div class="d-flex gap-3 mb-4 flex-wrap align-items-center">
    <div class="search-box flex-grow-1" style="min-width:200px;">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="searchInput" class="form-control rounded-3 border-2" placeholder="جستجوی سوال..." style="border-color:#e8edf3;background:#fafbfc;">
    </div>

    <select class="filter-select form-select rounded-3 border-2" id="filterLevel" style="border-color:#e8edf3;background:#fafbfc;min-width:150px;width:auto;">
        <option value="all">همه سوالات</option>
        <option value="type_0">تستی</option>
        <option value="type_1">پاسخ کوتاه</option>
        <option value="1">عالی</option>
        <option value="2">خوب</option>
        <option value="3">متوسط</option>
        <option value="4">ضعیف</option>
        <option value="5">👨‍🏫 سوال استاد</option>
        <option value="null">در انتظار تایید</option>
        <option value="starred">⭐ ستاره دار</option>
    </select>

    <button class="btn btn-sm rounded-3" id="toggleAllBtn" style="background:#e8edf3;color:#1a2332;border:none;">
        <i class="fas fa-eye"></i>
        نمایش / عدم نمایش جزئیات سوال
    </button>
</div>

<div class="d-flex flex-column gap-3" id="questionsList">
    @forelse($questions ?? [] as $question)
        <div class="question-card bg-white rounded-4 border"
             data-level="{{ $question->status ?? 'null' }}"
             data-type="{{ (int)($question->type ?? 0) }}"
             data-starred="{{ $question->star ?? 0 }}"
             data-question-id="{{ $question->id }}"
             style="border-color:#e8edf3;">

            <div class="question-header p-3 bg-light d-flex justify-content-between align-items-start flex-wrap gap-2" onclick="toggleQuestion(this)" style="background:#f8fafc;cursor:pointer;">
                <div class="question-text fw-semibold text-dark flex-grow-1" style="font-size:15px;">
                    <button class="star-btn {{ $question->star == 1 ? 'active' : '' }}"
                            onclick="event.stopPropagation();toggleStar({{ $question->id }})">
                        <i class="fas fa-star"></i>
                    </button>
                    {!! $question->question !!}
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size:12px;color:#6b7a8f;">
                    <span class="text-primary fw-semibold">
                        <i class="fas fa-user"></i>
                        {{ $question->designer_name ?? 'نامشخص' }}
                    </span>

                    <span class="question-type-badge {{ (int)$question->type === 1 ? 'short' : 'test' }}">
                        <i class="fas {{ (int)$question->type === 1 ? 'fa-align-left' : 'fa-list-ol' }}"></i>
                        {{ (int)$question->type === 1 ? 'پاسخ کوتاه' : 'تستی' }}
                    </span>

                    <span class="level-badge {{
                        $question->status == 1 ? 'excellent' :
                        ($question->status == 2 ? 'good' :
                        ($question->status == 3 ? 'medium' :
                        ($question->status == 4 ? 'bad' :
                        ($question->status == 5 ? 'teacher-level' : 'pending'))))
                    }} {{ $question->teacher_change == 1 ? 'teacher-changed' : '' }}">
                        {{ $question->level_text ?? 'نامشخص' }}
                        @if($question->teacher_change == 1)
                            <i class="fas fa-check-circle" style="font-size:10px;margin-right:4px;"></i>
                        @endif
                    </span>

                    <i class="fas fa-chevron-down question-chevron" style="color:#6b7a8f;font-size:12px;transition:transform .3s;"></i>
                </div>
            </div>

            <div class="question-body p-3">
                @if((int)$question->type === 1)
                    <div class="short-answer-box mb-3">
                        <div class="answer-title">
                            <i class="fas fa-comment-dots"></i>
                            پاسخ کوتاه
                        </div>
                        <div class="answer-text">
                            {{ $question->answer ?? 'پاسخی ثبت نشده است.' }}
                        </div>
                    </div>
                @else
                    <div class="options-grid row g-2 mb-3">
                        @php
                            $options = [
                                ['label' => 'الف', 'value' => $question->answer1, 'index' => 1],
                                ['label' => 'ب', 'value' => $question->answer2, 'index' => 2],
                                ['label' => 'ج', 'value' => $question->answer3, 'index' => 3],
                                ['label' => 'د', 'value' => $question->answer4, 'index' => 4],
                            ];
                        @endphp

                        @foreach($options as $option)
                            <div class="col-12 col-md-6">
                                <div class="option-item p-2 rounded-2 bg-light d-flex align-items-center gap-2 {{ $option['index'] == $question->answer ? 'correct' : '' }}" style="background:#f8fafc;">
                                    <span class="option-label fw-semibold">{{ $option['label'] }}.</span>
                                    <span class="option-text">{{ $option['value'] }}</span>
                                    @if($option['index'] == $question->answer)
                                        <span class="correct-badge ms-auto">
                                            <i class="fas fa-check-circle"></i>
                                            پاسخ صحیح
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(isset($question->nazars) && $question->nazars->count() > 0)
                    <div class="nazars-section pt-2 mt-2" style="border-top:1px solid #f0f4f9;">
                        <div class="nazar-title fw-semibold text-dark mb-2" style="font-size:13px;">نظرات داوران:</div>
                        @foreach($question->nazars as $nazar)
                            <div class="nazar-item {{ $nazar->user_id == Auth::id() ? 'my-judgment' : '' }}">
                                <span class="nazar-user">
                                    {{ $nazar->user->name ?? 'ناشناس' }}
                                    {{ $nazar->user->family ?? '' }}
                                </span>
                                <span class="nazar-score score-{{ $nazar->score_class }}">
                                    {{ $nazar->score_label }}
                                </span>
                                @if($nazar->comment1)
                                    <span style="color:#6b7a8f;font-size:12px;">({{ $nazar->comment1 }})</span>
                                @endif
                                @if($nazar->user_id == Auth::id())
                                    <span style="color:#1e6f9f;font-size:11px;font-weight:600;">(شما)</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="question-actions d-flex gap-2 mt-3 pt-3" style="border-top:1px solid #f0f4f9;">
                    <button class="btn btn-edit btn-sm rounded-2 fw-semibold d-inline-flex align-items-center gap-1"
                            onclick="event.stopPropagation();openEditModal({{ $question->id }})">
                        <i class="fas fa-edit"></i>
                        ویرایش
                    </button>

                    <a href="/teacher/questions/{{ $question->id }}"
                       class="btn btn-delete btn-sm rounded-2 fw-semibold d-inline-flex align-items-center gap-1"
                       onclick="event.stopPropagation();return confirm('آیا از حذف این سوال اطمینان دارید؟')"
                       style="border:none;">
                        <i class="fas fa-trash"></i>
                        حذف
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-question-circle"></i>
            <p class="mb-0">هیچ سوالی در این درس وجود ندارد.</p>
        </div>
    @endforelse
</div>


</div>

<div class="edit-modal-overlay" id="editModalOverlay">
    <div class="edit-modal">
        <div class="edit-modal-header">
            <h5>
                <i class="fas fa-edit text-primary"></i>
                ویرایش سوال
            </h5>
            <button class="edit-modal-close" onclick="closeEditModal()">&times;</button>
        </div>


    <div class="edit-modal-body">
        <form id="editQuestionForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_question_id" name="question_id">

            <div class="edit-form-group">
                <label for="edit_question">
                    متن سوال
                    <span class="required">*</span>
                </label>
                <textarea class="form-control" id="edit_question" name="question" rows="3" required></textarea>
                <div class="invalid-feedback" id="edit_question_error">لطفاً متن سوال را وارد کنید.</div>
            </div>

            <div class="edit-form-group">
                <label>گزینه‌ها <span class="required">*</span></label>

                <div class="edit-options-grid">
                    <div class="edit-option-item">
                        <span class="option-label">الف</span>
                        <input type="text" class="form-control" id="edit_answer1" name="answer1">
                        <input type="radio" name="answer" value="1" id="edit_radio_1" class="correct-radio" title="انتخاب به عنوان پاسخ صحیح">
                    </div>

                    <div class="edit-option-item">
                        <span class="option-label">ب</span>
                        <input type="text" class="form-control" id="edit_answer2" name="answer2">
                        <input type="radio" name="answer" value="2" id="edit_radio_2" class="correct-radio" title="انتخاب به عنوان پاسخ صحیح">
                    </div>

                    <div class="edit-option-item">
                        <span class="option-label">ج</span>
                        <input type="text" class="form-control" id="edit_answer3" name="answer3">
                        <input type="radio" name="answer" value="3" id="edit_radio_3" class="correct-radio" title="انتخاب به عنوان پاسخ صحیح">
                    </div>

                    <div class="edit-option-item">
                        <span class="option-label">د</span>
                        <input type="text" class="form-control" id="edit_answer4" name="answer4">
                        <input type="radio" name="answer" value="4" id="edit_radio_4" class="correct-radio" title="انتخاب به عنوان پاسخ صحیح">
                    </div>
                </div>

                <div class="invalid-feedback" id="edit_answer_error">لطفاً پاسخ صحیح را انتخاب کنید.</div>
            </div>

            <div class="edit-form-group">
                <label for="edit_type">
                    نوع سوال
                    <span class="required">*</span>
                </label>

                <select class="form-select" id="edit_type" name="type">
                    <option value="0">تستی</option>
                    <option value="1">پاسخ کوتاه</option>
                </select>
            </div>

            <div class="edit-form-group" style="background:#f3e5f5;padding:16px;border-radius:12px;border:2px solid #ce93d8;">
                <label for="edit_status" style="color:#6a1b9a;">
                    <i class="fas fa-gavel"></i>
                    تغییر امتیاز / نتیجه داوری
                </label>

                <select class="form-select" id="edit_status" name="status" style="border-color:#ce93d8;">
                    <option value="">بدون تغییر</option>
                    <option value="1">⭐ عالی</option>
                    <option value="2">✅ خوب</option>
                    <option value="3">📊 متوسط</option>
                    <option value="4">⚠️ ضعیف</option>
                    <option value="5">👨‍🏫 سوال استاد</option>
                </select>

                <small style="color:#6a1b9a;font-size:12px;display:block;margin-top:6px;">
                    <i class="fas fa-info-circle"></i>
                    با تغییر امتیاز، رنگ برچسب سوال بنفش می‌شود تا مشخص شود توسط استاد تغییر یافته است.
                </small>
            </div>
        </form>
    </div>

    <div class="edit-modal-footer">
        <button class="btn btn-cancel" onclick="closeEditModal()">انصراف</button>
        <button class="btn btn-save" id="editSaveBtn" onclick="saveQuestionEdit()">
            <i class="fas fa-save"></i>
            ذخیره تغییرات
        </button>
    </div>
</div>


</div>
@endsection

@section('js')

<script>
document.addEventListener('DOMContentLoaded',function(){
    var activeFilter=document.querySelector('.stat-filter.active-filter');
    if(!activeFilter){
        var firstFilter=document.querySelector('.stat-filter[data-filter="all"]');
        if(firstFilter) firstFilter.classList.add('active-filter');
    }

    document.querySelectorAll('.stat-filter').forEach(function(stat){
        stat.addEventListener('click',function(){
            document.querySelectorAll('.stat-filter').forEach(function(el){
                el.classList.remove('active-filter');
            });
            this.classList.add('active-filter');
            var filterValue=this.getAttribute('data-filter');
            var select=document.getElementById('filterLevel');
            if(select) select.value=filterValue;
            applyFilters();
            var number=this.querySelector('.stat-number');
            if(number){
                number.classList.remove('animated');
                setTimeout(function(){number.classList.add('animated')},10);
            }
        });
    });

    var select=document.getElementById('filterLevel');
    if(select){
        select.addEventListener('change',function(){
            var value=this.value;
            document.querySelectorAll('.stat-filter').forEach(function(el){
                el.classList.remove('active-filter');
                if(el.getAttribute('data-filter')===value){
                    el.classList.add('active-filter');
                }
            });
            applyFilters();
        });
    }

    var searchInput=document.getElementById('searchInput');
    if(searchInput){
        searchInput.addEventListener('input',function(){
            applyFilters();
        });
    }

    var toggleAllBtn=document.getElementById('toggleAllBtn');
    if(toggleAllBtn){
        var allOpen=false;

        toggleAllBtn.addEventListener('click',function(){
            var bodies=document.querySelectorAll('.question-body');
            var icons=document.querySelectorAll('.question-chevron');

            allOpen=!allOpen;

            bodies.forEach(function(body,index){
                if(allOpen){
                    body.classList.add('open');
                    if(icons[index]) icons[index].style.transform='rotate(180deg)';
                }else{
                    body.classList.remove('open');
                    if(icons[index]) icons[index].style.transform='rotate(0deg)';
                }
            });

            toggleAllBtn.innerHTML='<i class="fas '+(allOpen?'fa-eye-slash':'fa-eye')+'"></i> نمایش / عدم نمایش جزئیات سوال';
        });
    }

    var overlay=document.getElementById('editModalOverlay');
    if(overlay){
        overlay.addEventListener('click',function(e){
            if(e.target===this) closeEditModal();
        });
        document.addEventListener('keydown',function(e){
            if(e.key==='Escape'&&overlay.classList.contains('active')) closeEditModal();
        });
    }

    var editType=document.getElementById('edit_type');
    if(editType){
        editType.addEventListener('change',function(){
            updateEditTypeFields(this.value);
        });
    }

    applyFilters();
});

function toggleQuestion(header){
    var body=header.nextElementSibling;
    var icon=header.querySelector('.question-chevron');

    if(body.classList.contains('open')){
        body.classList.remove('open');
        if(icon) icon.style.transform='rotate(0deg)';
    }else{
        body.classList.add('open');
        if(icon) icon.style.transform='rotate(180deg)';
    }
}

function toggleStar(questionId){
    var btn=document.querySelector('.star-btn[onclick*="toggleStar('+questionId+')"]');
    if(!btn) return;

    var originalHtml=btn.innerHTML;
    btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled=true;

    fetch('/teacher/courses/bank/star/'+questionId,{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        }
    })
    .then(function(response){return response.json()})
    .then(function(data){
        if(data.success){
            var card=btn.closest('.question-card');

            if(data.star===1){
                btn.classList.add('active');
                if(card) card.setAttribute('data-starred','1');
            }else{
                btn.classList.remove('active');
                if(card) card.setAttribute('data-starred','0');
            }

            showToast('وضعیت ستاره تغییر کرد','success');
            applyFilters();
        }else{
            showToast(data.message||'خطا در تغییر وضعیت ستاره','error');
        }
    })
    .catch(function(error){
        console.error(error);
        showToast('خطا در ارتباط با سرور','error');
    })
    .finally(function(){
        btn.innerHTML=originalHtml;
        btn.disabled=false;
    });
}

function openEditModal(questionId){
    var overlay=document.getElementById('editModalOverlay');
    var saveBtn=document.getElementById('editSaveBtn');

    saveBtn.disabled=true;
    saveBtn.innerHTML='<span class="edit-loading"></span> در حال بارگذاری...';

    fetch('/teacher/courses/bank/edit/'+questionId,{
        method:'GET',
        headers:{'X-Requested-With':'XMLHttpRequest'}
    })
    .then(function(response){
        if(!response.ok) throw new Error('خطا در دریافت اطلاعات سوال');
        return response.json();
    })
    .then(function(data){
        if(data.success){
            var q=data.question;

            document.getElementById('edit_question_id').value=q.id;
            document.getElementById('edit_question').value=q.question;
            document.getElementById('edit_answer1').value=q.answer1||'';
            document.getElementById('edit_answer2').value=q.answer2||'';
            document.getElementById('edit_answer3').value=q.answer3||'';
            document.getElementById('edit_answer4').value=q.answer4||'';
            document.getElementById('edit_type').value=q.type!==undefined&&q.type!==null?q.type:0;

            document.querySelectorAll('input[name="answer"]').forEach(function(radio){
                radio.checked=false;
            });

            if(parseInt(q.type)===1){
                document.getElementById('edit_answer1').value=q.answer||'';
                updateEditTypeFields('1');
            }else{
                var radio=document.getElementById('edit_radio_'+q.answer);
                if(radio) radio.checked=true;
                updateEditTypeFields('0');
            }

            var statusSelect=document.getElementById('edit_status');
            statusSelect.value=q.status!==null&&q.status!==undefined?q.status:'';

            clearErrors();
            overlay.classList.add('active');
            document.body.style.overflow='hidden';
        }else{
            showToast(data.message||'خطا در دریافت اطلاعات سوال','error');
        }
    })
    .catch(function(error){
        console.error(error);
        showToast('خطا در ارتباط با سرور','error');
    })
    .finally(function(){
        saveBtn.disabled=false;
        saveBtn.innerHTML='<i class="fas fa-save"></i> ذخیره تغییرات';
    });
}

function updateEditTypeFields(type){
    var optionItems=document.querySelectorAll('.edit-option-item');
    var answerError=document.getElementById('edit_answer_error');

    if(type==='1'){
        optionItems.forEach(function(item,index){
            if(index===0){
                item.style.display='grid';
                item.querySelector('.option-label').textContent='پاسخ';
            }else{
                item.style.display='none';
            }

            var radio=item.querySelector('.correct-radio');
            if(radio) radio.checked=false;
        });

        if(answerError) answerError.classList.remove('show');
    }else{
        optionItems.forEach(function(item,index){
            item.style.display='';
            item.querySelector('.option-label').textContent=['الف','ب','ج','د'][index];
        });
    }
}

function closeEditModal(){
    var overlay=document.getElementById('editModalOverlay');
    overlay.classList.remove('active');
    document.body.style.overflow='';
    clearErrors();
}

function clearErrors(){
    document.querySelectorAll('.edit-form-group .form-control,.edit-form-group .form-select').forEach(function(el){
        el.classList.remove('is-invalid');
    });

    document.querySelectorAll('.invalid-feedback').forEach(function(el){
        el.classList.remove('show');
    });
}

function showErrors(errors){
    for(var field in errors){
        var input=document.getElementById('edit_'+field);

        if(input){
            input.classList.add('is-invalid');

            var feedback=document.getElementById('edit_'+field+'_error');

            if(feedback){
                feedback.textContent=errors[field][0];
                feedback.classList.add('show');
            }
        }
    }
}

function saveQuestionEdit(){
    var form=document.getElementById('editQuestionForm');
    var saveBtn=document.getElementById('editSaveBtn');
    var questionId=document.getElementById('edit_question_id').value;
    var type=document.getElementById('edit_type').value;

    var isValid=true;
    clearErrors();

    var question=document.getElementById('edit_question');

    if(!question.value.trim()){
        question.classList.add('is-invalid');
        document.getElementById('edit_question_error').textContent='لطفاً متن سوال را وارد کنید.';
        document.getElementById('edit_question_error').classList.add('show');
        isValid=false;
    }

    if(type==='0'){
        var answerSelected=false;

        for(var i=1;i<=4;i++){
            var radio=document.getElementById('edit_radio_'+i);

            if(radio&&radio.checked){
                answerSelected=true;
                break;
            }
        }

        if(!answerSelected){
            document.getElementById('edit_answer_error').classList.add('show');
            isValid=false;
        }
    }

    if(!isValid) return;

    saveBtn.disabled=true;
    saveBtn.innerHTML='<span class="edit-loading"></span> در حال ذخیره...';

    var formData=new FormData(form);

    if(type==='1'){
        formData.set('answer',document.getElementById('edit_answer1').value);
        formData.set('answer1','');
        formData.set('answer2','');
        formData.set('answer3','');
        formData.set('answer4','');
    }

    fetch('/teacher/courses/bank/update/'+questionId,{
        method:'POST',
        headers:{
            'X-CSRF-TOKEN':'{{ csrf_token() }}',
            'X-Requested-With':'XMLHttpRequest'
        },
        body:formData
    })
    .then(function(response){return response.json()})
    .then(function(data){
        if(data.success){
            showToast('سوال با موفقیت ویرایش شد.','success');
            closeEditModal();
            updateQuestionCard(data.question);
        }else{
            if(data.errors){
                showErrors(data.errors);
            }else{
                showToast(data.message||'خطا در ویرایش سوال','error');
            }
        }
    })
    .catch(function(error){
        console.error(error);
        showToast('خطا در ارتباط با سرور','error');
    })
    .finally(function(){
        saveBtn.disabled=false;
        saveBtn.innerHTML='<i class="fas fa-save"></i> ذخیره تغییرات';
    });
}

function updateQuestionCard(question){
    var card=document.querySelector('.question-card[data-question-id="'+question.id+'"]');
    if(!card) return;

    var textEl=card.querySelector('.question-text');

    if(textEl){
        var starBtn=textEl.querySelector('.star-btn');
        textEl.innerHTML='';

        if(starBtn){
            textEl.appendChild(starBtn);
            textEl.appendChild(document.createTextNode(' '));
        }

        var temp=document.createElement('div');
        temp.innerHTML=question.question||'';
        while(temp.firstChild){
            textEl.appendChild(temp.firstChild);
        }
    }

    card.setAttribute('data-type',question.type!==null&&question.type!==undefined?question.type:0);
    card.setAttribute('data-level',question.status!==null&&question.status!==undefined?question.status:'null');

    var typeBadge=card.querySelector('.question-type-badge');

    if(typeBadge){
        var isShort=parseInt(question.type)===1;
        typeBadge.className='question-type-badge '+(isShort?'short':'test');
        typeBadge.innerHTML='<i class="fas '+(isShort?'fa-align-left':'fa-list-ol')+'"></i> '+(isShort?'پاسخ کوتاه':'تستی');
    }

    var levelBadge=card.querySelector('.level-badge');

    if(levelBadge){
        levelBadge.textContent=getLevelText(question.status);
        levelBadge.className='level-badge '+getLevelClass(question.status);

        if(question.teacher_change==1){
            levelBadge.classList.add('teacher-changed');

            var checkIcon=document.createElement('i');
            checkIcon.className='fas fa-check-circle';
            checkIcon.style.cssText='font-size:10px;margin-right:4px;';
            levelBadge.prepend(checkIcon);
        }
    }

    var body=card.querySelector('.question-body');

    if(body){
        var existingDetails=body.querySelector('.short-answer-box,.options-grid');

        if(existingDetails) existingDetails.remove();

        var actions=body.querySelector('.question-actions');

        if(parseInt(question.type)===1){
            var answerBox=document.createElement('div');
            answerBox.className='short-answer-box mb-3';
            answerBox.innerHTML='<div class="answer-title"><i class="fas fa-comment-dots"></i> پاسخ کوتاه</div><div class="answer-text"></div>';
            answerBox.querySelector('.answer-text').textContent=question.answer||'پاسخی ثبت نشده است.';

            if(actions) body.insertBefore(answerBox,actions);
            else body.appendChild(answerBox);
        }else{
            var optionsContainer=document.createElement('div');
            optionsContainer.className='options-grid row g-2 mb-3';

            var options=[
                {label:'الف',value:question.answer1||'',index:1},
                {label:'ب',value:question.answer2||'',index:2},
                {label:'ج',value:question.answer3||'',index:3},
                {label:'د',value:question.answer4||'',index:4}
            ];

            options.forEach(function(opt){
                var col=document.createElement('div');
                col.className='col-12 col-md-6';

                var item=document.createElement('div');
                item.className='option-item p-2 rounded-2 bg-light d-flex align-items-center gap-2';
                item.style.background='#f8fafc';

                if(opt.index==question.answer) item.classList.add('correct');

                var label=document.createElement('span');
                label.className='option-label fw-semibold';
                label.textContent=opt.label+'.';

                var text=document.createElement('span');
                text.className='option-text';
                text.textContent=opt.value;

                item.appendChild(label);
                item.appendChild(text);

                if(opt.index==question.answer){
                    var badge=document.createElement('span');
                    badge.className='correct-badge ms-auto';
                    badge.innerHTML='<i class="fas fa-check-circle"></i> پاسخ صحیح';
                    item.appendChild(badge);
                }

                col.appendChild(item);
                optionsContainer.appendChild(col);
            });

            if(actions) body.insertBefore(optionsContainer,actions);
            else body.appendChild(optionsContainer);
        }
    }

    applyFilters();
}

function getLevelText(status){
    if(status==1) return 'عالی';
    if(status==2) return 'خوب';
    if(status==3) return 'متوسط';
    if(status==4) return 'ضعیف';
    if(status==5) return 'سوال استاد';
    return 'در انتظار تایید';
}

function getLevelClass(status){
    if(status==1) return 'excellent';
    if(status==2) return 'good';
    if(status==3) return 'medium';
    if(status==4) return 'bad';
    if(status==5) return 'teacher-level';
    return 'pending';
}

function applyFilters(){
    var searchInput=document.getElementById('searchInput');
    var filterLevel=document.getElementById('filterLevel');
    var cards=document.querySelectorAll('.question-card');

    if(!searchInput||!filterLevel) return;

    var searchText=searchInput.value.toLowerCase().trim();
    var filterValue=filterLevel.value;
    var visibleCount=0;

    cards.forEach(function(card){
        var show=true;

        if(searchText){
            var text=card.querySelector('.question-text');

            if(!text||!text.textContent.toLowerCase().includes(searchText)){
                show=false;
            }
        }

        if(show&&filterValue!=='all'){
            if(filterValue==='starred'){
                if(card.getAttribute('data-starred')!=='1') show=false;
            }else if(filterValue==='type_0'){
                if(card.getAttribute('data-type')!=='0') show=false;
            }else if(filterValue==='type_1'){
                if(card.getAttribute('data-type')!=='1') show=false;
            }else{
                if(card.getAttribute('data-level')!==filterValue) show=false;
            }
        }

        card.style.display=show?'':'none';

        if(show) visibleCount++;
    });

    var questionsList=document.getElementById('questionsList');
    var existingEmpty=questionsList.querySelector('.filter-empty-state');

    if(visibleCount===0&&cards.length>0){
        if(!existingEmpty){
            var empty=document.createElement('div');
            empty.className='empty-state filter-empty-state';
            empty.innerHTML='<i class="fas fa-filter"></i><p class="mb-0">هیچ سوالی با این فیلتر پیدا نشد.</p>';
            questionsList.appendChild(empty);
        }
    }else if(existingEmpty){
        existingEmpty.remove();
    }
}

function showToast(message,type){
    var existingToast=document.querySelector('.toast-notification');
    if(existingToast) existingToast.remove();

    var colors={
        success:'#4CAF50',
        error:'#f44336',
        info:'#2196F3',
        warning:'#FF9800'
    };

    var toast=document.createElement('div');
    toast.className='toast-notification';

    toast.style.cssText='position:fixed;bottom:30px;left:50%;transform:translateX(-50%);background:'+(colors[type]||colors.info)+';color:white;padding:14px 28px;border-radius:12px;font-size:14px;font-weight:500;z-index:100000;box-shadow:0 8px 24px rgba(0,0,0,.2);animation:slideUp .4s ease;direction:rtl;max-width:90%;text-align:center;';

    toast.textContent=message;
    document.body.appendChild(toast);

    setTimeout(function(){
        toast.style.opacity='0';
        toast.style.transition='opacity .4s';

        setTimeout(function(){
            toast.remove();
        },400);
    },3500);
}
</script>

@endsection
