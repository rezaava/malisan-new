@extends('layout.master')
@section('title')
    ملیسان | مدیریت درس
@endsection
@section('head')
    <link rel="stylesheet" href="{{ asset('css/badge.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-course.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .collapsible-body{display:none;}
        .collapsible-body.open{display:block;}
        .expand-icon.rotated{transform:rotate(180deg);}
        .session-content-header .info-badges{flex-wrap:wrap;gap:8px;}
        .student-activities-section{background:#f0f7ff;border-radius:12px;padding:16px 20px;margin-bottom:16px;border-right:4px solid #1e6f9f;}
        .student-activities-section .activity-header{display:flex;align-items:center;justify-content:space-between;font-weight:600;color:#1e6f9f;margin-bottom:0;}
        .student-activities-section .activity-header .header-left{display:flex;align-items:center;gap:10px;}
        .student-activities-section .activity-header .header-left i{font-size:18px;}
        .student-activities-section .activity-icons{display:flex;gap:16px;align-items:center;}
        .student-activities-section .activity-icons a{color:#1a2332;font-size:18px;transition:all .2s ease;display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,.6);text-decoration:none;}
        .student-activities-section .activity-icons a:hover{background:#fff;color:#1e6f9f;transform:translateY(-2px);box-shadow:0 4px 12px rgba(30,111,159,.15);}
        .student-activities-section .activity-icons a i{font-size:18px;}
        .student-activities-section .activity-icons a .fa-question-circle{color:#ff9800;}
        .student-activities-section .activity-icons a .fa-file-alt{color:#4caf50;}
        .student-activities-section .activity-icons a .fa-list-ul{color:#2196f3;}
        .student-activities-section .activity-icons a:hover .fa-question-circle{color:#e65100;}
        .student-activities-section .activity-icons a:hover .fa-file-alt{color:#2e7d32;}
        .student-activities-section .activity-icons a:hover .fa-list-ul{color:#0d47a1;}
        .student-activities-section .activity-icons a.disabled,
        .student-activities-section .activity-icons a.hidden-btn{display:none !important;}
        .session-aparat-container{margin-bottom:16px;}
        .aparat-video-wrapper{position:relative;width:100%;padding-top:56.25%;overflow:hidden;border-radius:10px;background:#000;}
        .aparat-video-wrapper iframe{position:absolute;top:0;right:0;width:100%;height:100%;border:0;}
        .session-action-buttons{display:none;}
        @media(max-width:768px){
            .student-activities-section .activity-header{flex-direction:column;gap:12px;}
            .student-activities-section .activity-icons{justify-content:center;}
        }
    </style>
@endsection
@section('mohtava')
<div class="course-detail-container">
    <div class="course-actions-bar">
        <div class="info-badge course-badge">
            <span class="badge-icon"><i class="fas fa-book-open"></i></span>
            <span class="badge-label">درس:</span>
            <span class="badge-value">{{ $course->name ?? 'عنوان درس' }}</span>
        </div>
    </div>
    <div class="course-chips">
        @if($course->quiz == 1)
            <a href="{{ route('student.selfTest.start', $course->id) }}" class="chip-item">
                <i class="fas fa-star"></i>
                خودآزمایی
            </a>
        @endif
        @if($course->faaliat == 1)
            <a href="{{ route('student.my.activities', $course->id) }}" class="chip-item">
                <i class="fas fa-database"></i>
                فعالیت های من
            </a>
        @endif
        @if($course->pishraft == 1)
            <a href="{{ route('student.progress', $course->id) }}" class="chip-item">
                <i class="fas fa-chart-line"></i>
                پیشرفت درسی
            </a>
        @endif
        @if(isset($course->davari) && $course->davari == 1)
            <a href="{{ route('student.judgment.index', ['course_id' => $course->id]) }}" class="chip-item davari">
                <i class="fas fa-users-cog"></i>
                داوری دوستان
            </a>
        @endif
    </div>
    <div class="sessions-section">
        <div class="sessions-sidebar">
            <div class="sessions-header">
                <h5>جلسه های ارائه شده</h5>
            </div>
            <div class="sessions-list">
                @forelse($sessions as $session)
                    <a href="javascript:void(0);"
                       class="session-item {{ $loop->first ? 'active' : '' }}"
                       data-session="{{ $session->id }}"
                       data-can-question="{{ isset($session['can_question']) && $session['can_question'] ? 'true' : 'false' }}"
                       data-can-homework="{{ isset($session['can_homework']) && $session['can_homework'] ? 'true' : 'false' }}"
                       data-can-report="{{ isset($session['can_report']) && $session['can_report'] ? 'true' : 'false' }}"
                       data-pdf="{{ $session->file ?? '' }}"
                       data-link="{{ e($session->link ?? '') }}"
                       data-title="{{ e($session->name ?? '') }}"
                       data-number="جلسه {{ $session->number ?? '' }}"
                       data-description="{{ e($session->text ?? '') }}"
                       data-lessonplan="{{ e($session->lesson_plan ?? '') }}"
                       data-majazi="{{ e($session->majazi ?? '') }}"
                       data-aparat="{{ e($session->aparat ?? '') }}"
                       onclick="changeSession(this)">
                        <span class="session-check">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span class="session-title">{{ $session->name }}</span>
                        <small class="session-number">(جلسه {{ $session->number }})</small>
                    </a>
                @empty
                    <div class="alert alert-warning m-3">
                        <i class="fas fa-info-circle"></i>
                        این دوره هنوز جلسه‌ای ندارد
                    </div>
                @endforelse
            </div>
        </div>
        <div class="session-content">
            <div class="session-content-header">
                <div class="session-title-display">
                    <div class="info-badges">
                        <div class="info-badge session-badge">
                            <span class="badge-icon"><i class="fas fa-hashtag"></i></span>
                            <span class="badge-label">جلسه:</span>
                            <span class="badge-value" id="sessionNumberDisplay">
                                @if($sessions->isNotEmpty())
                                    {{ $sessions->first()->number }}
                                @else
                                    <span class="empty">-</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-badge topic-badge">
                            <span class="badge-icon"><i class="fas fa-tag"></i></span>
                            <span class="badge-label">موضوع:</span>
                            <span class="badge-value" id="sessionNameDisplay">
                                @if($sessions->isNotEmpty())
                                    {{ $sessions->first()->name }}
                                @else
                                    <span class="empty">هیچ جلسه‌ای انتخاب نشده است</span>
                                @endif
                            </span>
                        </div>
                        <a href="#"
                           id="majaziSessionBtn"
                           class="info-badge majazi-badge"
                           target="_blank"
                           rel="noopener noreferrer"
                           style="display:{{ $sessions->isNotEmpty() && !empty($sessions->first()->majazi) ? 'inline-flex' : 'none' }};text-decoration:none;cursor:pointer;">
                            <span class="badge-icon"><i class="fas fa-video"></i></span>
                            <span class="badge-value">کلاس مجازی</span>
                        </a>
                    </div>
                </div>
                <div class="session-action-buttons"></div>
            </div>
            <div class="student-activities-section">
                <div class="activity-header">
                    <div class="header-left">
                        <i class="fas fa-users"></i>
                        <span>
                            فعالیت‌های دانشجو در
                            <span id="activitySessionLabel">
                                @if($sessions->isNotEmpty())
                                    جلسه {{ $sessions->first()->number }}
                                @else
                                    جلسه جاری
                                @endif
                            </span>
                        </span>
                    </div>
                    <div class="activity-icons">
                        <a href="#" id="questionStudentBtn" title="ثبت سوال">
                            <i class="fas fa-question-circle"></i>
                        </a>
                        <a href="#" id="homeworkStudentBtn" title="ارسال تکلیف">
                            <i class="fas fa-file-alt"></i>
                        </a>
                        <a href="#" id="reportStudentBtn" title="ارسال گزارش">
                            <i class="fas fa-list-ul"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="session-description">
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <i class="fas fa-tasks"></i>
                        طرح درس
                        <i class="fas fa-chevron-down expand-icon"></i>
                    </div>
                    <div class="collapsible-body" id="sessionLessonPlan">
                        @if($sessions->isNotEmpty() && $sessions->first()->lesson_plan)
                            {!! $sessions->first()->lesson_plan !!}
                        @else
                            <p class="text-muted">هیچ طرح درسی برای این جلسه ثبت نشده است</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="session-text-section">
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <i class="fas fa-book"></i>
                        محتوای درس
                        <div style="margin-right:auto;">
                            <i class="fas fa-chevron-down expand-icon"></i>
                        </div>
                    </div>
                    <div class="collapsible-body" id="sessionText">
                        @if($sessions->isNotEmpty() && $sessions->first()->text)
                            {!! $sessions->first()->text !!}
                        @else
                            <p class="text-muted">هیچ محتوایی برای این جلسه ثبت نشده است</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="session-aparat-container">
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <i class="fas fa-play-circle" style="color:#e53935;"></i>
                        پیوست درس
                        <i class="fas fa-chevron-down expand-icon"></i>
                    </div>
                    <div class="collapsible-body" id="sessionAparat">
                        @if($sessions->isNotEmpty() && !empty($sessions->first()->aparat))
                            <div class="aparat-video-wrapper">
                                <iframe id="aparatIframe"
                                        src="{{ $sessions->first()->aparat }}"
                                        frameborder="0"
                                        allowfullscreen="true"
                                        webkitallowfullscreen="true"
                                        mozallowfullscreen="true"
                                        scrolling="no"
                                        allow="encrypted-media *;"></iframe>
                            </div>
                        @else
                            <p class="text-muted">هیچ فیلم آپاراتی برای این جلسه ثبت نشده است</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="session-pdf-container">
                <div class="collapsible-section">
                    <div class="collapsible-header p-0 px-2">
                        <div class="pdf-toolbar d-flex justify-content-between align-items-center w-100">
                            <div class="w-100">
                                <span class="px-2">پیوست (جزوه، کتاب، پاورپوینت و...)</span>
                                <a href="#"
                                   id="pdfOpenBtn"
                                   class="pdf-open-btn"
                                   title="باز کردن فایل پیوست در صفحه جدید"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   style="float:left;display:none;">
                                    <i class="fas fa-file-pdf" style="color:black"></i>
                                </a>
                                <a href="#"
                                   id="linkOpenBtn"
                                   class="pdf-open-btn"
                                   title="باز کردن لینک محتوای جلسه در صفحه جدید"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   style="float:left;display:none;">
                                    <i class="fas fa-link" style="color:#1e6f9f"></i>
                                </a>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down expand-icon"></i>
                    </div>
                    <div class="collapsible-body">
                        <div class="pdf-viewer" id="attachmentViewer">
                            @if($sessions->isNotEmpty() && $sessions->first()->file)
                                <object id="pdfViewer"
                                        data="/files/session{{ $sessions->first()->file }}"
                                        type="application/pdf"
                                        width="100%"
                                        height="550px">
                                    <object width="100%" height="550" data="/files/session{{ $sessions->first()->file }}"></object>
                                </object>
                            @elseif($sessions->isNotEmpty() && $sessions->first()->link)
                                <div class="text-center p-5">
                                    <i class="fas fa-link fa-3x mb-3" style="color:#1e6f9f;"></i>
                                    <p class="mb-3">محتوای این جلسه به صورت لینک ارائه شده است.</p>
                                    <a href="{{ $sessions->first()->link }}"
                                       class="btn btn-primary"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        <i class="fas fa-external-link-alt"></i>
                                        باز کردن محتوای جلسه
                                    </a>
                                </div>
                            @else
                                <div class="text-center p-5">
                                    <i class="fas fa-paperclip fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">هیچ فایل یا لینکی برای این جلسه ثبت نشده است</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
let currentSessionId='{{ $sessions->first()->id ?? "" }}';
let currentPdfUrl='{{ $sessions->first()->file ?? "" }}';
let currentSessionTitle=@json($sessions->first()->name ?? '');
let currentSessionNumber='جلسه {{ $sessions->first()->number ?? "" }}';
let currentDescription=@json($sessions->first()->text ?? '');
let currentMajaziUrl=@json($sessions->first()->majazi ?? '');
let currentAparatUrl=@json($sessions->first()->aparat ?? '');
let currentLessonPlan=@json($sessions->first()->lesson_plan ?? '');
let currentLinkUrl=@json($sessions->first()->link ?? '');
function changeSession(element){
    if(!element)return;
    const sessionId=element.dataset.session||'';
    const pdfUrl=element.dataset.pdf||'';
    const linkUrl=element.dataset.link||'';
    const title=element.dataset.title||'';
    const number=element.dataset.number||'';
    const description=element.dataset.description||'';
    const lessonPlan=element.dataset.lessonplan||'';
    const majaziUrl=element.dataset.majazi||'';
    const aparatUrl=element.dataset.aparat||'';
    document.querySelectorAll('.session-item').forEach(item=>item.classList.remove('active'));
    element.classList.add('active');
    currentSessionId=sessionId;
    currentPdfUrl=pdfUrl;
    currentSessionTitle=title;
    currentSessionNumber=number;
    currentDescription=description;
    currentLessonPlan=lessonPlan;
    currentMajaziUrl=majaziUrl;
    currentAparatUrl=aparatUrl;
    currentLinkUrl=linkUrl;
    const numberDisplay=document.getElementById('sessionNumberDisplay');
    if(numberDisplay){
        const numberMatch=number.match(/\d+/);
        numberDisplay.textContent=numberMatch?numberMatch[0]:'-';
    }
    const activitySessionLabel=document.getElementById('activitySessionLabel');
    if(activitySessionLabel){
        const numberMatch=number.match(/\d+/);
        activitySessionLabel.textContent=numberMatch?'جلسه '+numberMatch[0]:'جلسه جاری';
    }
    const nameDisplay=document.getElementById('sessionNameDisplay');
    if(nameDisplay)nameDisplay.textContent=title||'هیچ جلسه‌ای انتخاب نشده است';
    const majaziBtn=document.getElementById('majaziSessionBtn');
    if(majaziBtn){
        const rawMajaziUrl=(majaziUrl||'').trim();
        if(rawMajaziUrl&&rawMajaziUrl!=='null'){
            let finalMajaziUrl=rawMajaziUrl;
            try{
                if(!finalMajaziUrl.startsWith('http://')&&!finalMajaziUrl.startsWith('https://'))finalMajaziUrl='https://'+finalMajaziUrl;
                majaziBtn.href=new URL(finalMajaziUrl).href;
                majaziBtn.style.display='inline-flex';
            }catch(error){
                majaziBtn.removeAttribute('href');
                majaziBtn.style.display='none';
            }
        }else{
            majaziBtn.removeAttribute('href');
            majaziBtn.style.display='none';
        }
    }
    const sessionLessonPlan=document.getElementById('sessionLessonPlan');
    if(sessionLessonPlan){
        if(lessonPlan&&lessonPlan.trim()&&lessonPlan.trim()!=='null'){
            sessionLessonPlan.innerHTML=lessonPlan;
        }else{
            sessionLessonPlan.innerHTML='<p class="text-muted">هیچ طرح درسی برای این جلسه ثبت نشده است</p>';
        }
    }
    const sessionText=document.getElementById('sessionText');
    if(sessionText){
        if(description&&description.trim()&&description.trim()!=='null'){
            sessionText.innerHTML=description;
        }else{
            sessionText.innerHTML='<p class="text-muted">هیچ محتوایی برای این جلسه ثبت نشده است</p>';
        }
    }
    const sessionAparat=document.getElementById('sessionAparat');
    if(sessionAparat){
        let rawAparatUrl=(aparatUrl||'').trim();
        if(rawAparatUrl&&rawAparatUrl!=='null'){
            const iframeMatch=rawAparatUrl.match(/<iframe[^>]+src=["']([^"']+)["']/i);
            if(iframeMatch)rawAparatUrl=iframeMatch[1];
            if(!rawAparatUrl.startsWith('http://')&&!rawAparatUrl.startsWith('https://'))rawAparatUrl='https://'+rawAparatUrl;
            sessionAparat.innerHTML='<div class="aparat-video-wrapper"><iframe id="aparatIframe" src="'+rawAparatUrl+'" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" scrolling="no" allow="encrypted-media *;"></iframe></div>';
        }else{
            sessionAparat.innerHTML='<p class="text-muted">هیچ فیلم آپاراتی برای این جلسه ثبت نشده است</p>';
        }
    }
    let fullPdfUrl='';
    if(pdfUrl){
        fullPdfUrl=pdfUrl.startsWith('http://')||pdfUrl.startsWith('https://')?pdfUrl:'/files/session'+pdfUrl;
    }
    let fullLinkUrl=(linkUrl||'').trim();
    if(fullLinkUrl&&!fullLinkUrl.startsWith('http://')&&!fullLinkUrl.startsWith('https://'))fullLinkUrl='https://'+fullLinkUrl;
    const attachmentViewer=document.getElementById('attachmentViewer');
    const pdfOpenBtn=document.getElementById('pdfOpenBtn');
    const linkOpenBtn=document.getElementById('linkOpenBtn');
    if(pdfOpenBtn){
        pdfOpenBtn.removeAttribute('href');
        pdfOpenBtn.style.display='none';
    }
    if(linkOpenBtn){
        linkOpenBtn.removeAttribute('href');
        linkOpenBtn.style.display='none';
    }
    if(attachmentViewer){
        if(fullPdfUrl){
            attachmentViewer.innerHTML='<object id="pdfViewer" data="'+fullPdfUrl+'" type="application/pdf" width="100%" height="550px"><object width="100%" height="550" data="'+fullPdfUrl+'"></object></object>';
            if(pdfOpenBtn){
                pdfOpenBtn.href=fullPdfUrl;
                pdfOpenBtn.style.display='inline-flex';
            }
        }else if(fullLinkUrl){
            attachmentViewer.innerHTML='<div class="text-center p-5"><i class="fas fa-link fa-3x mb-3" style="color:#1e6f9f;"></i><p class="mb-3">محتوای این جلسه به صورت لینک ارائه شده است.</p><a href="'+fullLinkUrl+'" class="btn btn-primary" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt"></i> باز کردن محتوای جلسه</a></div>';
            if(linkOpenBtn){
                linkOpenBtn.href=fullLinkUrl;
                linkOpenBtn.style.display='inline-flex';
            }
        }else{
            attachmentViewer.innerHTML='<div class="text-center p-5"><i class="fas fa-paperclip fa-3x text-muted mb-3"></i><p class="text-muted">هیچ فایل یا لینکی برای این جلسه ثبت نشده است</p></div>';
        }
    }
    const canQuestion=element.dataset.canQuestion==='true';
    const canHomework=element.dataset.canHomework==='true';
    const canReport=element.dataset.canReport==='true';
    const questionStudentBtn=document.getElementById('questionStudentBtn');
    if(questionStudentBtn){
        if(canQuestion){
            questionStudentBtn.href='/student/questions/create/'+sessionId;
            questionStudentBtn.style.display='inline-flex';
            questionStudentBtn.style.opacity='1';
            questionStudentBtn.style.pointerEvents='auto';
            questionStudentBtn.classList.remove('disabled','hidden-btn');
        }else{
            questionStudentBtn.removeAttribute('href');
            questionStudentBtn.style.display='none';
            questionStudentBtn.classList.add('disabled','hidden-btn');
        }
    }
    const homeworkStudentBtn=document.getElementById('homeworkStudentBtn');
    if(homeworkStudentBtn){
        if(canHomework){
            homeworkStudentBtn.href='/student/exercise/show/'+sessionId;
            homeworkStudentBtn.style.display='inline-flex';
            homeworkStudentBtn.style.opacity='1';
            homeworkStudentBtn.style.pointerEvents='auto';
            homeworkStudentBtn.classList.remove('disabled','hidden-btn');
        }else{
            homeworkStudentBtn.removeAttribute('href');
            homeworkStudentBtn.style.display='none';
            homeworkStudentBtn.classList.add('disabled','hidden-btn');
        }
    }
    const reportStudentBtn=document.getElementById('reportStudentBtn');
    if(reportStudentBtn){
        if(canReport){
            reportStudentBtn.href='/student/discussion/create/'+sessionId;
            reportStudentBtn.style.display='inline-flex';
            reportStudentBtn.style.opacity='1';
            reportStudentBtn.style.pointerEvents='auto';
            reportStudentBtn.classList.remove('disabled','hidden-btn');
        }else{
            reportStudentBtn.removeAttribute('href');
            reportStudentBtn.style.display='none';
            reportStudentBtn.classList.add('disabled','hidden-btn');
        }
    }
}
document.addEventListener('DOMContentLoaded',function(){
    document.querySelectorAll('.collapsible-header').forEach(function(header){
        header.addEventListener('click',function(){
            const body=this.nextElementSibling;
            const icon=this.querySelector('.expand-icon');
            if(!body)return;
            body.classList.toggle('open');
            if(icon)icon.classList.toggle('rotated');
        });
    });
    const firstSession=document.querySelector('.session-item.active');
    if(firstSession)changeSession(firstSession);
});
</script>
@endsection