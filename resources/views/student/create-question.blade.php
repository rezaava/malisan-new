@extends('layout.master')

@section('title')
ملیسان | طرح سوال
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/style-create-question.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">

<style>
.modal-overlay {
    overflow: hidden;
}

.modal-box {
    display: flex;
    flex-direction: column;
    max-height: 90vh;
    overflow: hidden;
}

.modal-header {
    flex: 0 0 auto;
    position: sticky;
    top: 0;
    z-index: 20;
    background: #fff;
    border-bottom: 1px solid #eee;
}

.modal-body {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
}

.modal-body::-webkit-scrollbar {
    width: 7px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f5f5f5;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #c7c7c7;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #999;
}

.question-type-wrapper {
    margin-bottom: 20px;
}

.question-type-title {
    display: block;
    font-weight: 600;
    margin-bottom: 10px;
}

.question-type-options {
    display: flex;
    align-items: center;
    gap: 25px;
    flex-wrap: wrap;
}

.question-type-option {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    font-size: 14px;
    user-select: none;
}

.question-type-option input[type="radio"] {
    width: 17px;
    height: 17px;
    cursor: pointer;
    accent-color: #1e6f9f;
}

.question-type-option span {
    cursor: pointer;
}

.short-answer-note {
    display: none;
    margin-bottom: 10px;
    padding: 10px 14px;
    border-radius: 8px;
    background: #f5f8ff;
    border: 1px solid #dce6f7;
    color: #345;
    font-size: 13px;
}

.short-answer-note.active {
    display: block;
}

.short-answer-note i {
    margin-left: 5px;
}

.option-item.hidden-option {
    display: none !important;
}

@media (max-width: 768px) {
    .modal-box {
        width: 95%;
        max-height: 90vh;
    }

    .modal-header {
        min-height: 58px;
    }

    .modal-body {
        max-height: calc(90vh - 58px);
    }

    .question-type-options {
        gap: 15px;
    }
}
</style>
@endsection

@section('mohtava')
<div class="question-container">
    <div class="question-card">
        <div class="question-header">
            <div class="header-left">
                <div class="info-badges">
                    <h4>
                        طرح سوال
                        <button type="button" class="help-icon" onclick="openSettingModal()" title="راهنمای طرح سوال">
                            <i class="fas fa-question"></i>
                        </button>
                    </h4>

                    <div class="info-badge course-badge">
                        <span class="badge-icon"><i class="fas fa-book-open"></i></span>
                        <span class="badge-label">درس:</span>
                        <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
                    </div>

                    <div class="info-badge session-badge">
                        <span class="badge-icon"><i class="fas fa-hashtag"></i></span>
                        <span class="badge-label">جلسه:</span>
                        <span class="badge-value">{{ $session->number }}</span>
                    </div>

                    <div class="info-badge topic-badge">
                        <span class="badge-icon"><i class="fas fa-tag"></i></span>
                        <span class="badge-label">موضوع:</span>
                        <span class="badge-value">{{ $session->name }}</span>
                    </div>
                </div>
            </div>

            <div class="header-actions">
                <button type="button" class="header-btn header-btn-secondary" onclick="openFriendsQuestionsModal()">
                    <i class="fas fa-users"></i>
                    سوالات دوستان
                </button>

                <button type="button" class="header-btn header-btn-primary" onclick="openMyQuestionsModal()">
                    <i class="fas fa-user"></i>
                    سوالات من
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="question-form" action="{{ route('student.question.store', $session->id) }}" method="POST">
            @csrf

            <div class="form-group question-type-wrapper">
                <label class="question-type-title">
                    نوع سوال
                    <span style="color:red;">*</span>
                </label>

                <div class="question-type-options">
                    <label class="question-type-option">
                        <input type="radio" name="type" value="0" {{ old('type', '0') == '0' ? 'checked' : '' }}>
                        <span>چهار گزینه‌ای</span>
                    </label>

                    <label class="question-type-option">
                        <input type="radio" name="type" value="1" {{ old('type') == '1' ? 'checked' : '' }}>
                        <span>پاسخ کوتاه</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="questionEditor">
                    متن سوال
                    <span style="color:red;">*</span>
                </label>

                <textarea class="jodit-editor" id="questionEditor" name="question" placeholder="متن سوال را وارد کنید...">{{ old('question') }}</textarea>
            </div>

            <div class="short-answer-note" id="shortAnswerNote">
                <i class="fas fa-info-circle"></i>
                پاسخ را حداکثر در سه کلمه وارد کنید.
            </div>

            <div class="options-grid">
                @for($i = 0; $i < 4; $i++)
                    <div class="form-group option-item" data-option="{{ $i }}">
                        <label for="option-{{ $i }}">
                            <span class="option-label-text">گزینه {{ $i + 1 }}</span>
                            <span class="badge-correct">✓</span>
                            <span class="correct-label">(گزینه صحیح)</span>
                        </label>

                        <div class="option-input-wrapper">
                            <input
                                type="text"
                                id="option-{{ $i }}"
                                name="options[]"
                                class="form-input"
                                placeholder="متن گزینه {{ $i + 1 }} را وارد کنید"
                                value="{{ old('options.'.$i) }}"
                            >

                            <button type="button" class="set-correct-btn" data-option="{{ $i }}" title="تنظیم به عنوان پاسخ صحیح">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </div>
                    </div>
                @endfor
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

<div class="modal-overlay" id="friendsQuestionsModal">
    <div class="modal-box">
        <div class="modal-header">
            <h4>
                <i class="fas fa-users" style="color:#6c5ce7;"></i>
                سوالات دوستان
            </h4>

            <button type="button" class="modal-close-btn" onclick="closeModal('friendsQuestionsModal')" aria-label="بستن">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body" id="friendsQuestionsModalBody">
            <div class="loading-container">
                <i class="fas fa-spinner fa-spin fa-2x loading-spinner"></i>
                <p class="loading-text">در حال بارگذاری سوالات دوستان...</p>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="myQuestionsModal">
    <div class="modal-box">
        <div class="modal-header">
            <h4>
                <i class="fas fa-user" style="color:#1e6f9f;"></i>
                سوالات من در این جلسه
            </h4>

            <button type="button" class="modal-close-btn" onclick="closeModal('myQuestionsModal')" aria-label="بستن">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body" id="myQuestionsModalBody">
            <div class="loading-container">
                <i class="fas fa-spinner fa-spin fa-2x loading-spinner"></i>
                <p class="loading-text">در حال بارگذاری سوالات من...</p>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="settingModal">
    <div class="modal-box">
        <div class="modal-header">
            <h4>
                <i class="fas fa-info-circle" style="color:#ff9800;"></i>
                راهنمای طرح سوال
            </h4>

            <button type="button" class="modal-close-btn" onclick="closeModal('settingModal')" aria-label="بستن">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="setting-description">
                {!! $settingDescription !!}
            </div>

            @if($settingScore > 0)
                <div class="setting-score-box">
                    <i class="fas fa-star setting-score-icon"></i>
                    <strong>امتیاز این فعالیت:</strong>
                    {{ $settingScore }} امتیاز
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.jodit-editor').forEach(function (element) {
        const editorId = element.id || 'editor-' + Math.random().toString(36).substr(2, 9);

        if (!element.id) {
            element.id = editorId;
        }

        new Jodit('#' + editorId, {
            width: '100%',
            height: 200,
            allowResize: true,
            allowResizeImages: true,
            direction: 'rtl',
            language: 'fa',
            buttons: [
                'source', '|', 'undo', 'redo', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'font', 'fontsize', 'brush', 'paragraph', '|',
                'ul', 'ol', 'outdent', 'indent', '|',
                'align', 'hr', 'table', '|',
                'link', 'unlink',
                {
                    name: 'uploadImage',
                    iconURL: 'https://cdn-icons-png.flaticon.com/512/1829/1829586.png',
                    tooltip: 'آپلود تصویر',
                    exec: (editor) => {
                        let input = document.createElement('input');
                        input.type = 'file';
                        input.accept = 'image/*';

                        input.onchange = () => {
                            let file = input.files[0];
                            if (!file) return;

                            let formData = new FormData();
                            formData.append('file', file);

                            fetch('{{ route("upload.image") }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.files && data.files[0] && data.files[0].url) {
                                    let img = document.createElement('img');
                                    img.src = data.files[0].url;
                                    img.style.maxWidth = '100%';
                                    editor.s.insertNode(img);
                                } else {
                                    alert('خطا در آپلود تصویر');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert('خطا در آپلود تصویر');
                            });
                        };

                        input.click();
                    }
                },
                {
                    name: 'uploadVideo',
                    iconURL: 'https://cdn-icons-png.flaticon.com/512/727/727245.png',
                    tooltip: 'آپلود ویدیو',
                    exec: (editor) => {
                        let input = document.createElement('input');
                        input.type = 'file';
                        input.accept = 'video/*';

                        input.onchange = () => {
                            let file = input.files[0];
                            if (!file) return;

                            let formData = new FormData();
                            formData.append('file', file);

                            fetch('{{ route("upload.video") }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.files && data.files[0] && data.files[0].url) {
                                    let wrapper = document.createElement('div');
                                    wrapper.classList.add('video-wrapper');

                                    let video = document.createElement('video');
                                    video.setAttribute('controls', '');
                                    video.src = data.files[0].url;
                                    video.style.maxWidth = '100%';

                                    wrapper.appendChild(video);
                                    editor.s.insertNode(wrapper);
                                } else {
                                    alert('خطا در آپلود ویدیو');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert('خطا در آپلود ویدیو');
                            });
                        };

                        input.click();
                    }
                },
                '|', 'symbols', 'emoticons', '|', 'print', 'fullsize', 'preview'
            ],
            colors: {
                text: ['#000000', '#ff0000', '#00ff00', '#0000ff', '#ff00ff', '#00ffff'],
                background: ['#ffffff', '#ffff00', '#00ffff', '#ffcc99']
            },
            defaultFont: 'Vazir, Tahoma, Arial, sans-serif',
            defaultFontSize: '14px',
            fonts: ['Vazir', 'Tahoma', 'Arial', 'Courier New']
        });
    });

    const typeInputs = document.querySelectorAll('input[name="type"]');
    const optionItems = document.querySelectorAll('.option-item');
    const correctAnswerInput = document.getElementById('correct_answer');
    const shortAnswerNote = document.getElementById('shortAnswerNote');

    function clearCorrectStyles() {
        optionItems.forEach(function (item) {
            item.classList.remove('is-correct');

            const btn = item.querySelector('.set-correct-btn');

            if (btn) {
                btn.classList.remove('is-correct-btn');
            }
        });
    }

    function setCorrectOption(optionIndex) {
        clearCorrectStyles();

        const selectedItem = document.querySelector('.option-item[data-option="' + optionIndex + '"]');

        if (selectedItem) {
            selectedItem.classList.add('is-correct');

            const btn = selectedItem.querySelector('.set-correct-btn');

            if (btn) {
                btn.classList.add('is-correct-btn');
            }

            correctAnswerInput.value = optionIndex;
        }
    }

    function updateQuestionType(type) {
        if (type === '1') {
            optionItems.forEach(function (item, index) {
                const label = item.querySelector('.option-label-text');
                const input = item.querySelector('.form-input');
                const button = item.querySelector('.set-correct-btn');
                const badgeCorrect = item.querySelector('.badge-correct');
                const correctLabel = item.querySelector('.correct-label');

                if (index === 0) {
                    item.classList.remove('hidden-option');
                    label.textContent = 'پاسخ (حداکثر سه کلمه)';
                    input.placeholder = 'پاسخ را وارد کنید (حداکثر سه کلمه)';

                    if (button) {
                        button.style.display = 'none';
                    }

                    if (badgeCorrect) {
                        badgeCorrect.style.display = 'none';
                    }

                    if (correctLabel) {
                        correctLabel.style.display = 'none';
                    }
                } else {
                    item.classList.add('hidden-option');
                    input.value = '';

                    if (button) {
                        button.style.display = 'none';
                    }
                }
            });

            correctAnswerInput.value = '';
            clearCorrectStyles();
            shortAnswerNote.classList.add('active');
        } else {
            optionItems.forEach(function (item, index) {
                const label = item.querySelector('.option-label-text');
                const input = item.querySelector('.form-input');
                const button = item.querySelector('.set-correct-btn');
                const badgeCorrect = item.querySelector('.badge-correct');
                const correctLabel = item.querySelector('.correct-label');

                item.classList.remove('hidden-option');

                label.textContent = 'گزینه ' + (index + 1);
                input.placeholder = 'متن گزینه ' + (index + 1) + ' را وارد کنید';

                if (button) {
                    button.style.display = '';
                }

                if (badgeCorrect) {
                    badgeCorrect.style.display = '';
                }

                if (correctLabel) {
                    correctLabel.style.display = '';
                }
            });

            shortAnswerNote.classList.remove('active');

            const correctValue = correctAnswerInput.value === '' ? 0 : parseInt(correctAnswerInput.value);

            setCorrectOption(correctValue);
        }
    }

    document.querySelectorAll('.set-correct-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            const type = document.querySelector('input[name="type"]:checked')?.value || '0';

            if (type === '1') {
                return;
            }

            setCorrectOption(parseInt(this.getAttribute('data-option')));
        });
    });

    typeInputs.forEach(function (radio) {
        radio.addEventListener('change', function () {
            updateQuestionType(this.value);
        });
    });

    const initialType = document.querySelector('input[name="type"]:checked')?.value || '0';
    updateQuestionType(initialType);
});

function openModal(modalId) {
    const modal = document.getElementById(modalId);

    if (!modal) return;

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);

    if (!modal) return;

    modal.classList.remove('active');
    document.body.style.overflow = '';
}

document.querySelectorAll('.modal-overlay').forEach(function (modal) {
    modal.addEventListener('click', function (e) {
        if (e.target === this) {
            this.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(function (modal) {
            modal.classList.remove('active');
        });

        document.body.style.overflow = '';
    }
});

function openFriendsQuestionsModal() {
    openModal('friendsQuestionsModal');

    const sessionId = '{{ $session->id }}';
    const body = document.getElementById('friendsQuestionsModalBody');

    body.innerHTML = `
        <div class="loading-container">
            <i class="fas fa-spinner fa-spin fa-2x loading-spinner"></i>
            <p class="loading-text">در حال بارگذاری سوالات دوستان...</p>
        </div>
    `;

    fetch('/student/questions/list/' + sessionId)
        .then(response => response.json())
        .then(data => {
            if (!data.success || !data.questions || data.questions.length === 0) {
                body.innerHTML = `
                    <div class="empty-state">
                        <span class="empty-icon"><i class="fas fa-inbox"></i></span>
                        <h5>تاکنون سوالی برای این جلسه طرح نشده است</h5>
                        <p>شما می‌توانید اولین سوال را طرح کنید.</p>
                    </div>
                `;
                return;
            }

            const userId = {{ Auth::id() }};
            const questions = data.questions.filter(q => q.user_id != userId);

            if (questions.length === 0) {
                body.innerHTML = `
                    <div class="empty-state">
                        <span class="empty-icon"><i class="fas fa-inbox"></i></span>
                        <h5>هنوز دوستانت سوالی طرح نکرده‌اند</h5>
                        <p>اولین نفری باش که سوال خود را طراحی می‌کند!</p>
                    </div>
                `;
                return;
            }

            let html = '';

            questions.forEach(function (q, index) {
                let questionHtml = `
                    <div class="question-list-item">
                        <div class="q-text">
                            <strong>سوال ${index + 1}:</strong>
                            ${q.question}
                        </div>
                `;

                if (parseInt(q.type) === 1) {
                    questionHtml += `
                        <div class="q-options">
                            <span class="correct">
                                پاسخ: ${q.answer || ''}
                            </span>
                        </div>

                        <div class="q-meta">
                            <span>نوع سوال: پاسخ کوتاه</span>
                            <span>تاریخ: ${q.date || ''}</span>
                        </div>
                    `;
                } else {
                    const options = [q.answer1, q.answer2, q.answer3, q.answer4];

                    questionHtml += `
                        <div class="q-options">
                            ${options.map(function (opt, i) {
                                return `
                                    <span class="${q.answer == (i + 1) ? 'correct' : ''}">
                                        ${i + 1}. ${opt}
                                        ${q.answer == (i + 1) ? '✓' : ''}
                                    </span>
                                `;
                            }).join('')}
                        </div>

                        <div class="q-meta">
                            <span>نوع سوال: چهارگزینه‌ای</span>
                            <span>تاریخ: ${q.date || ''}</span>
                        </div>
                    `;
                }

                questionHtml += `</div>`;
                html += questionHtml;
            });

            body.innerHTML = html;
        })
        .catch(function (error) {
            console.error(error);

            body.innerHTML = `
                <div class="empty-state">
                    <span class="empty-icon">
                        <i class="fas fa-exclamation-triangle" style="color:#f44336;"></i>
                    </span>
                    <h5>خطا در بارگذاری سوالات</h5>
                    <p>مشکلی در ارتباط با سرور رخ داده است. لطفاً مجدداً تلاش کنید.</p>
                </div>
            `;
        });
}

function openMyQuestionsModal() {
    openModal('myQuestionsModal');

    const sessionId = '{{ $session->id }}';
    const body = document.getElementById('myQuestionsModalBody');

    body.innerHTML = `
        <div class="loading-container">
            <i class="fas fa-spinner fa-spin fa-2x loading-spinner"></i>
            <p class="loading-text">در حال بارگذاری سوالات من...</p>
        </div>
    `;

    fetch('/student/questions/list/' + sessionId)
        .then(response => response.json())
        .then(data => {
            if (!data.success || !data.questions || data.questions.length === 0) {
                body.innerHTML = `
                    <div class="empty-state">
                        <span class="empty-icon"><i class="fas fa-inbox"></i></span>
                        <h5>تاکنون سوالی برای این جلسه طرح نشده است</h5>
                        <p>شما می‌توانید اولین سوال را طرح کنید.</p>
                    </div>
                `;
                return;
            }

            const userId = {{ Auth::id() }};
            const questions = data.questions.filter(q => q.user_id == userId);

            if (questions.length === 0) {
                body.innerHTML = `
                    <div class="empty-state">
                        <span class="empty-icon"><i class="fas fa-inbox"></i></span>
                        <h5>شما هنوز سوالی برای این جلسه طرح نکرده‌اید</h5>
                        <p>اولین سوال خود را طراحی کنید!</p>
                    </div>
                `;
                return;
            }

            let html = '';

            questions.forEach(function (q, index) {
                const statusBadge = getStatusBadge(q.status);

                let questionHtml = `
                    <div class="question-list-item">
                        <div class="q-text">
                            <strong>سوال ${index + 1}:</strong>
                            ${q.question}
                        </div>
                `;

                if (parseInt(q.type) === 1) {
                    questionHtml += `
                        <div class="q-options">
                            <span class="correct">
                                پاسخ: ${q.answer || ''}
                            </span>
                        </div>

                        <div class="q-meta">
                            <span>طراح: ${q.user_name || 'نامشخص'}</span>
                            <span>${statusBadge}</span>
                            <span>نوع سوال: پاسخ کوتاه</span>
                            <span>تاریخ: ${q.date || ''}</span>
                        </div>
                    `;
                } else {
                    const options = [q.answer1, q.answer2, q.answer3, q.answer4];

                    questionHtml += `
                        <div class="q-options">
                            ${options.map(function (opt, i) {
                                return `
                                    <span class="${q.answer == (i + 1) ? 'correct' : ''}">
                                        ${i + 1}. ${opt}
                                        ${q.answer == (i + 1) ? '✓' : ''}
                                    </span>
                                `;
                            }).join('')}
                        </div>

                        <div class="q-meta">
                            <span>طراح: ${q.user_name || 'نامشخص'}</span>
                            <span>${statusBadge}</span>
                            <span>نوع سوال: چهارگزینه‌ای</span>
                            <span>تاریخ: ${q.date || ''}</span>
                        </div>
                    `;
                }

                questionHtml += `</div>`;
                html += questionHtml;
            });

            body.innerHTML = html;
        })
        .catch(function (error) {
            console.error(error);

            body.innerHTML = `
                <div class="empty-state">
                    <span class="empty-icon">
                        <i class="fas fa-exclamation-triangle" style="color:#f44336;"></i>
                    </span>
                    <h5>خطا در بارگذاری سوالات</h5>
                    <p>مشکلی در ارتباط با سرور رخ داده است. لطفاً مجدداً تلاش کنید.</p>
                </div>
            `;
        });
}

function openSettingModal() {
    openModal('settingModal');
}

function getStatusBadge(status) {
    if (status === null || status === undefined) {
        return `<span class="badge badge-pending">در انتظار تایید</span>`;
    }

    if (status === 0) {
        return `<span class="badge badge-returned">برگشت خورده</span>`;
    }

    const statusMap = {
        1: { text: 'عالی', class: 'badge-excellent' },
        2: { text: 'خوب', class: 'badge-good' },
        3: { text: 'متوسط', class: 'badge-medium' },
        4: { text: 'بد', class: 'badge-bad' }
    };

    const s = statusMap[status];

    if (s) {
        return `<span class="badge ${s.class}">${s.text}</span>`;
    }

    return `<span class="badge badge-pending">نامشخص</span>`;
}
</script>
@endsection