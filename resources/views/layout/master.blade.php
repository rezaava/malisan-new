<!DOCTYPE html>
<html lang="fa" dir="rtl">
@include('layout.head')
<body>
    @include('layout.header')
    <div class="header-spacer"></div>
    
    {{-- Overlay برای بستن منو --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dashboard-wrapper">
        @if (Auth::user()->hasRole('teacher'))
            @include('layout.asideTe')
        @elseif (Auth::user()->hasRole('student'))
            @include('layout.asideSt')
        @endif
        <div class="main-content">
            <div class="empty-content">
                @yield('mohtava')
            </div>
            @include('layout.footer')
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebarRight');
        const overlay = document.getElementById('sidebarOverlay');

        if (toggleBtn && sidebar && overlay) {
            // باز کردن منو
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
            });

            // بستن با کلیک روی overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            });

            // بستن با کلیک روی آیتم منو (در موبایل)
            document.querySelectorAll('.menu-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        sidebar.classList.remove('open');
                        overlay.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                });
            });

            // بستن با دکمه Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });

            // بستن با تغییر اندازه صفحه به دسکتاپ
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        }
    });
</script>
@yield('js')
</html>