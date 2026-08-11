<header class="top-header">
    <div class="logo-area">
        <a href="/" class="logo-link">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="logo-text">ملیسان | Malisan</div>
        </a>
        {{-- دکمه همبرگری --}}
        <button class="navbar-toggler" id="sidebarToggle" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <div class="top-actions">
        {{-- ===== دراپ‌داون پروفایل ===== --}}
        <div class="dropdown profile-dropdown">
            <button class="dropdown-toggle top-icon-btn" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset(Auth::user()->image) }}" alt="پروفایل" class="profile-img">
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2" aria-labelledby="profileDropdown">
                {{-- اطلاعات کاربری --}}
                <li>
                    <a class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2" 
                       href="{{ Auth::user()->hasRole('student') ? route('student.profile') : route('teacher.profile') }}">
                        <i class="fas fa-user-circle fs-5 text-primary"></i>
                        <span>اطلاعات کاربری</span>
                    </a>
                </li>
                {{-- ویرا کوین --}}
                <li>
                    <a class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2" href="#">
                        <i class="fas fa-coins fs-5 text-warning"></i>
                        <span>ویرا کوین</span>
                    </a>
                </li>
                <li><hr class="dropdown-divider my-2"></li>
                {{-- خروج --}}
                <li>
                    <a class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2 text-danger" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt fs-5"></i>
                        <span>خروج از حساب کاربری</span>
                    </a>
                </li>
            </ul>
        </div>

        {{-- دکمه پیام‌ها --}}
        <a href="/messages" class="top-icon-btn" title="پیام‌ها">
            <i class="far fa-envelope"></i>
        </a>
        {{-- دکمه سوالات متداول --}}
        <a href="/faq" class="top-icon-btn" title="سوالات متداول">
            <i class="far fa-question-circle"></i>
        </a>
    </div>
</header>

{{-- استایل‌های سفارشی --}}
<style>
    /* بهبود ظاهر منو */
    .profile-dropdown .dropdown-menu {
        min-width: 200px;
        background: #ffffff;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12);
        animation: slideDown 0.25s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .profile-dropdown .dropdown-item {
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.2s ease;
        color: #2d3748;
    }

    .profile-dropdown .dropdown-item:hover {
        background-color: #f7fafc;
        color: #1a202c;
        transform: translateX(4px);
    }

    .profile-dropdown .dropdown-item i {
        width: 20px;
        text-align: center;
    }

    .profile-dropdown .dropdown-item.text-danger:hover {
        background-color: #fff5f5;
        color: #c53030;
    }

    /* بهبود تصویر پروفایل */
    .profile-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #e2e8f0;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .dropdown-toggle.show .profile-img {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25);
    }

    /* دکمه‌های کناری */
    .top-icon-btn {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: transparent;
        border: none;
        color: #4a5568;
        transition: background 0.2s, color 0.2s;
        font-size: 1.2rem;
        text-decoration: none;
    }

    .top-icon-btn:hover {
        background: #edf2f7;
        color: #2d3748;
    }

    /* ریسپانسیو */
    @media (max-width: 576px) {
        .profile-dropdown .dropdown-menu {
            min-width: 180px;
            right: -10px !important;
        }
    }
</style>