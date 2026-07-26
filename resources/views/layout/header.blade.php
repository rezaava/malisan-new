<header class="top-header">
    <div class="logo-area">
        <a href="/" class="logo-link">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="logo-text">ملیسان | Malisan</div>
        </a>
        {{-- ===== دکمه همبرگری ===== --}}
        <button class="navbar-toggler" id="sidebarToggle" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <div class="top-actions">
        @if (Auth::user()->hasRole('student'))
        <a href="{{ route('student.profile') }}" class="top-icon-btn" title="پیام‌ها">
            <img src="{{asset(Auth::user()->image)}}" alt="پروفایل" class="profile-img">
        </a>
        @else
        <a href="{{ route('teacher.profile') }}" class="top-icon-btn" title="پیام‌ها">
            <img src="{{asset(Auth::user()->image)}}" alt="پروفایل" class="profile-img">
        </a>
        @endif
        <a href="/messages" class="top-icon-btn" title="پیام‌ها">
            <i class="far fa-envelope"></i>
        </a>
        <a href="/faq" class="top-icon-btn" title="سوالات متداول">
            <i class="far fa-question-circle"></i>
        </a>
    </div>
</header>