<aside class="sidebar-right" id="sidebarRight">
    <div class="sidebar-menu">
        @php
            $userRole = auth()->user()->role ?? 'student';
            $isTeacherRoute = request()->is('teacher*');
            $isStudentRoute = request()->is('student*');
        @endphp

        {{-- میز کار --}}
        @if($isTeacherRoute)
            <a href="{{ route('index_teacher') }}" class="menu-item {{ request()->routeIs('index_teacher') ? 'active-menu' : '' }}">
                <i class="fas fa-tachometer-alt"></i> <span>میز کار</span>
            </a>
        @else
            <a href="{{ route('index_student') }}" class="menu-item {{ request()->routeIs('index_student') ? 'active-menu' : '' }}">
                <i class="fas fa-tachometer-alt"></i> <span>میز کار</span>
            </a>
        @endif
        
        {{-- درس های من --}}
        @if($isTeacherRoute)
            <a href="{{ route('courses') }}" class="menu-item {{ request()->routeIs('courses') ? 'active-menu' : '' }}">
                <i class="fas fa-book-open"></i> <span>درس های من</span>
            </a>
        @else
            <a href="{{ route('courses.st') }}" class="menu-item {{ request()->routeIs('courses.st') ? 'active-menu' : '' }}">
                <i class="fas fa-book-open"></i> <span>درس های من</span>
            </a>
        @endif
        
        {{-- مکالمات --}}
        @if (!Auth::user()->hasRole('admin'))            
            @if($isTeacherRoute)
                <a href="{{ route('teacher.chat.index') }}" class="menu-item {{ request()->routeIs('teacher.chat.index') ? 'active-menu' : '' }}">
                    <i class="fas fa-comments"></i> <span>مکالمات</span>
                </a>
            @else
                <a href="{{ route('student.chat.index') }}" class="menu-item {{ request()->routeIs('student.chat.index') ? 'active-menu' : '' }}">
                    <i class="fas fa-comments"></i> <span>مکالمات</span>
                </a>
            @endif
        @endif
        
        @if (Auth::user()->hasRole('admin') && !$isStudentRoute)
            <a href="{{ route('admin_angizesh') }}" class="menu-item {{ request()->routeIs('admin_angizesh') ? 'active-menu' : '' }}">
                <i class="fas fa-comments"></i> <span>پیام انگیزشی</span>
            </a>
        @endif


        <div class="menu-divider"></div>
        
        @if (Auth::user()->hasRole('teacher|admin'))
            {{-- تغییر نقش --}}
            @if($isTeacherRoute)
                <a href="{{ route('index_student') }}" class="menu-item">
                    <i class="fas fa-user-graduate"></i> <span>در نقش دانشجو</span>
                </a>
            @elseif($isStudentRoute)
                <a href="{{ route('index_teacher') }}" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i> <span>بازگشت به نقش استاد</span>
                </a>
            @else
                @if($userRole == 'teacher' || $userRole == 'admin')
                    <a href="{{ route('index_student') }}" class="menu-item">
                        <i class="fas fa-user-graduate"></i> <span>در نقش دانشجو</span>
                    </a>
                @else
                    <a href="{{ route('index_teacher') }}" class="menu-item">
                        <i class="fas fa-chalkboard-teacher"></i> <span>در نقش استاد</span>
                    </a>
                @endif
            @endif
        @endif
        
        {{-- خروج از حساب --}}
        <a href="{{ route('logout') }}" class="menu-item">
            <i class="fas fa-sign-out-alt"></i> <span>خروج از حساب</span>
        </a>
    </div>
</aside>