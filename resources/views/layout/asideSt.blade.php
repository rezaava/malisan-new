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

        {{-- درس های من --}}
        <a href="{{ route('skill.st') }}" class="menu-item {{ request()->routeIs('skill.st') || request()->is('student/skill*') ? 'active-menu' : '' }}">
            <i class="fas fa-book-open"></i> <span>مهارت های من</span>
        </a>

        <div class="menu-divider"></div>
        
        {{-- خروج از حساب --}}
        <a href="/logout" class="menu-item">
            <i class="fas fa-sign-out-alt"></i> <span>خروج از حساب</span>
        </a>
    </div>
</aside>