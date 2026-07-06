<aside class="sidebar-right" id="sidebarRight">
    <div class="sidebar-menu">
        @php
            $user = auth()->user();
            $userRole = $user->role ?? 'student'; // یا از session استفاده کنید
            $isTeacher = ($userRole === 'teacher' || $userRole === 'admin');
            $isStudent = ($userRole === 'student');
            
            // تشخیص مسیر فعلی
            $isTeacherRoute = request()->is('teacher*') || request()->routeIs('index_teacher');
            $isStudentRoute = request()->is('student*') || request()->routeIs('index_student');
        @endphp

        {{-- میز کار --}}
        @if($isTeacher)
            <a href="{{ route('index_teacher') }}" class="menu-item {{ request()->routeIs('index_teacher') || request()->is('teacher') ? 'active-menu' : '' }}">
                <i class="fas fa-tachometer-alt"></i> <span>میز کار</span>
            </a>
        @else
            <a href="{{ route('index_student') }}" class="menu-item {{ request()->routeIs('index_student') || request()->is('student') ? 'active-menu' : '' }}">
                <i class="fas fa-tachometer-alt"></i> <span>میز کار</span>
            </a>
        @endif
        
        {{-- درس های من --}}
        @if($isTeacher)
            <a href="{{ route('courses') }}" class="menu-item {{ request()->routeIs('courses') || request()->is('teacher/courses*') ? 'active-menu' : '' }}">
                <i class="fas fa-book-open"></i> <span>درس های من</span>
            </a>
        @else
            <a href="{{ route('courses.st') }}" class="menu-item {{ request()->routeIs('courses.st') || request()->is('student/courses*') ? 'active-menu' : '' }}">
                <i class="fas fa-book-open"></i> <span>درس های من</span>
            </a>
        @endif
        
        {{-- مکالمات --}}
        <a href="{{ route('chat.index') }}" class="menu-item {{ request()->is('conversations*') ? 'active-menu' : '' }}">
            <i class="fas fa-comments"></i> <span>مکالمات</span>
        </a>
        
        <div class="menu-divider"></div>
        
        {{-- تغییر نقش بر اساس مسیر فعلی --}}
        @if($isTeacherRoute)
            {{-- در نقش استاد --}}
            <a href="{{ route('index_student') }}" class="menu-item">
                <i class="fas fa-user-graduate"></i> <span>در نقش دانشجو</span>
            </a>
        @elseif($isStudentRoute)
            {{-- در نقش دانشجو --}}
            <a href="{{ route('index_teacher') }}" class="menu-item">
                <i class="fas fa-chalkboard-teacher"></i> <span>بازگشت به نقش استاد</span>
            </a>
        @else
            {{-- در صورتی که در هیچکدام نبود، بر اساس نقش کاربر --}}
            @if($isTeacher)
                <a href="{{ route('index_student') }}" class="menu-item">
                    <i class="fas fa-user-graduate"></i> <span>در نقش دانشجو</span>
                </a>
            @else
                <a href="{{ route('index_teacher') }}" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i> <span>در نقش استاد</span>
                </a>
            @endif
        @endif
        
        {{-- خروج از حساب --}}
        <a href="/logout" class="menu-item">
            <i class="fas fa-sign-out-alt"></i> <span>خروج از حساب</span>
        </a>
    </div>
</aside>