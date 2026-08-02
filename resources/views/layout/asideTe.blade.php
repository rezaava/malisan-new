<aside class="sidebar-right" id="sidebarRight">
    <div class="sidebar-menu">
        @php
            $userRole = auth()->user()->role ?? 'student';
            $isTeacherRoute = request()->is('teacher*');
            $isStudentRoute = request()->is('student*');
            $isAdminRoute = request()->is('admin*');
            $user = auth()->user();
        @endphp
        
        {{-- میز کار --}}
        @if($isAdminRoute)
            {{-- صفحه میز کار ادمین --}}
            <a href="{{ route('index_admin') }}" class="menu-item {{ request()->routeIs('index_admin') ? 'active-menu' : '' }}">
                <i class="fas fa-tachometer-alt"></i> <span>میز کار ادمین</span>
            </a>
        @elseif($isTeacherRoute)
            {{-- صفحه میز کار استاد --}}
            <a href="{{ route('index_teacher') }}" class="menu-item {{ request()->routeIs('index_teacher') ? 'active-menu' : '' }}">
                <i class="fas fa-tachometer-alt"></i> <span>میز کار استاد</span>
            </a>
        @else
            {{-- صفحه میز کار دانشجو --}}
            <a href="{{ route('index_student') }}" class="menu-item {{ request()->routeIs('index_student') ? 'active-menu' : '' }}">
                <i class="fas fa-tachometer-alt"></i> <span>میز کار دانشجو</span>
            </a>
        @endif
        
        {{-- درس های من --}}
        @if($isAdminRoute)
            {{-- صفحه درس‌های ادمین --}}
            <a href="{{ route('courses.Ad') }}" class="menu-item {{ request()->routeIs('courses.Ad') ? 'active-menu' : '' }}">
                <i class="fas fa-book-open"></i> <span>مدیریت درس‌ها</span>
            </a>
        @elseif($isTeacherRoute)
            {{-- صفحه درس‌های استاد --}}
            <a href="{{ route('courses') }}" class="menu-item {{ request()->routeIs('courses') ? 'active-menu' : '' }}">
                <i class="fas fa-book-open"></i> <span>درس های من</span>
            </a>
        @else
            {{-- صفحه درس‌های دانشجو --}}
            <a href="{{ route('courses.st') }}" class="menu-item {{ request()->routeIs('courses.st') ? 'active-menu' : '' }}">
                <i class="fas fa-book-open"></i> <span>درس های من</span>
            </a>
        @endif
        
        {{-- مکالمات --}}
        @if($isAdminRoute)
            {{-- صفحه مکالمات ادمین --}}
            <a href="{{ route('admin.chat.index') }}" class="menu-item {{ request()->routeIs('admin.chat.index') ? 'active-menu' : '' }}">
                <i class="fas fa-comments"></i> <span>مدیریت مکالمات</span>
            </a>
        @elseif($isTeacherRoute)
            {{-- صفحه مکالمات استاد --}}
            <a href="{{ route('teacher.chat.index') }}" class="menu-item {{ request()->routeIs('teacher.chat.index') ? 'active-menu' : '' }}">
                <i class="fas fa-comments"></i> <span>مکالمات</span>
            </a>
        @else
            {{-- صفحه مکالمات دانشجو --}}
            <a href="{{ route('student.chat.index') }}" class="menu-item {{ request()->routeIs('student.chat.index') ? 'active-menu' : '' }}">
                <i class="fas fa-comments"></i> <span>مکالمات</span>
            </a>
        @endif
        
        {{-- پیام انگیزشی (فقط ادمین) --}}
        @if ($user->hasRole('admin') && $isAdminRoute)
            <a href="{{ route('admin_angizesh') }}" class="menu-item {{ request()->routeIs('admin_angizesh') ? 'active-menu' : '' }}">
                <i class="fas fa-comments"></i> <span>پیام انگیزشی</span>
            </a>
        @endif

        <div class="menu-divider"></div>
        
        {{-- تغییر نقش --}}
        @if ($user->hasRole('teacher|admin'))
            {{-- اگر در مسیر ادمین هستیم --}}
            @if($isAdminRoute)
                <a href="{{ route('index_student') }}" class="menu-item">
                    <i class="fas fa-user-graduate"></i> <span>در نقش دانشجو</span>
                </a>
                <a href="{{ route('index_teacher') }}" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i> <span>در نقش استاد</span>
                </a>
            @elseif($isTeacherRoute)
                {{-- در مسیر استاد --}}
                <a href="{{ route('index_student') }}" class="menu-item">
                    <i class="fas fa-user-graduate"></i> <span>در نقش دانشجو</span>
                </a>
                @if($user->hasRole('admin'))
                    <a href="{{ route('index_admin') }}" class="menu-item">
                        <i class="fas fa-user-cog"></i> <span>برگشت به نقش ادمین</span>
                    </a>
                @endif
            @elseif($isStudentRoute)
                {{-- در مسیر دانشجو --}}
                <a href="{{ route('index_teacher') }}" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i> <span>در نقش استاد</span>
                </a>
                @if($user->hasRole('admin'))
                    <a href="{{ route('index_admin') }}" class="menu-item">
                        <i class="fas fa-user-cog"></i> <span>برگشت به نقش ادمین</span>
                    </a>
                @endif
            @else
                {{-- در صفحه اصلی یا سایر صفحات --}}
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