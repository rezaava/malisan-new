<aside class="sidebar-right" id="sidebarRight">
    <div class="sidebar-menu">
        {{-- میز کار --}}
        <a href="{{ route('index_student') }}" class="menu-item {{ request()->routeIs('index_student') || request()->is('student') ? 'active-menu' : '' }}">
            <i class="fas fa-tachometer-alt"></i> <span>میز کار</span>
        </a>
        
        {{-- درس های من --}}
        <a href="{{ route('courses.st') }}" class="menu-item {{ request()->routeIs('courses.st') || request()->is('student/courses*') ? 'active-menu' : '' }}">
            <i class="fas fa-book-open"></i> <span>درس های من</span>
        </a>
{{--         
        دوره های ملیسان
        <a href="{{ route('publics') }}" class="menu-item {{ request()->routeIs('publics') || request()->is('publics') ? 'active-menu' : '' }}">
            <i class="fas fa-university"></i> <span>دوره های ملیسان</span>
        </a> --}}
        
        {{-- آزمون ها --}}
        {{-- <a href="{{ route('exams') }}" class="menu-item {{ request()->routeIs('exams') || request()->is('exams') ? 'active-menu' : '' }}">
            <i class="fas fa-pen-alt"></i> <span>آزمون ها</span>
        </a>
         --}}
        {{-- نظرسنجی --}}
        {{-- <a href="{{ route('surveys') }}" class="menu-item {{ request()->routeIs('surveys') || request()->is('surveys') ? 'active-menu' : '' }}">
            <i class="fas fa-chart-simple"></i> <span>نظرسنجی</span>
        </a> --}}
        
        {{-- تولید محتوا
        <a href="{{ route('content') }}" class="menu-item {{ request()->routeIs('content') || request()->is('content') ? 'active-menu' : '' }}">
            <i class="fas fa-edit"></i> <span>تولید محتوا</span>
        </a> --}}
        
        {{-- ساخت مسابقه --}}
        {{-- <a href="{{ route('createQuiz') }}" class="menu-item {{ request()->routeIs('createQuiz') || request()->is('create-quiz') ? 'active-menu' : '' }}">
            <i class="fas fa-trophy"></i> <span>ساخت مسابقه</span>
        </a> --}}
        
        {{-- مسابقات --}}
        {{-- <a href="{{ route('quizzes') }}" class="menu-item {{ request()->routeIs('quizzes') || request()->is('quizzes') ? 'active-menu' : '' }}">
            <i class="fas fa-medal"></i> <span>مسابقات</span>
        </a> --}}
        
        {{-- مکالمات --}}
        {{-- <a href="{{ route('chat.index') }}" class="menu-item {{ request()->is('conversations*') ? 'active-menu' : '' }}">
            <i class="fas fa-comments"></i> <span>مکالمات</span>
        </a> --}}
        
        <div class="menu-divider"></div>
        
        {{-- خروج از حساب --}}
        <a href="/logout" class="menu-item">
            <i class="fas fa-sign-out-alt"></i> <span>خروج از حساب</span>
        </a>
    </div>
</aside>