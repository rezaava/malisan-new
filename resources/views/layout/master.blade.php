<!DOCTYPE html>
<html lang="fa" dir="rtl">
@include('layout.head')
<head>
    <style>
        /* ===== استایل‌های پیام‌های فلش ===== */
        .flash-messages {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: 90%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }

        .flash-message {
            pointer-events: auto;
            padding: 16px 20px;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            animation: slideUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: relative;
        }

        .flash-message.removing {
            animation: slideDown 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .flash-message i {
            font-size: 22px;
            flex-shrink: 0;
        }

        .flash-message .message-text {
            flex: 1;
            line-height: 1.6;
        }

        .flash-message .btn-close-custom {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s ease;
            color: inherit;
            padding: 0 5px;
            line-height: 1;
        }

        .flash-message .btn-close-custom:hover {
            opacity: 1;
        }

        .flash-message .progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            border-radius: 0 0 16px 16px;
            background: rgba(255, 255, 255, 0.6);
            animation: progressShrink 5s linear forwards;
            width: 100%;
        }

        /* ===== استایل‌های هر نوع پیام ===== */
        .flash-message.error {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
            color: #c0392b;
            border-color: #fcc;
        }
        .flash-message.error i {
            color: #e74c3c;
        }
        .flash-message.error .progress-bar {
            background: #e74c3c;
        }

        .flash-message.success {
            background: linear-gradient(135deg, #f0fff4 0%, #d4edda 100%);
            color: #1e7e34;
            border-color: #b8daff;
        }
        .flash-message.success i {
            color: #28a745;
        }
        .flash-message.success .progress-bar {
            background: #28a745;
        }

        .flash-message.warning {
            background: linear-gradient(135deg, #fffbf0 0%, #fff3cd 100%);
            color: #856404;
            border-color: #ffeaa7;
        }
        .flash-message.warning i {
            color: #ffc107;
        }
        .flash-message.warning .progress-bar {
            background: #ffc107;
        }

        .flash-message.info {
            background: linear-gradient(135deg, #f0f8ff 0%, #cce5ff 100%);
            color: #004085;
            border-color: #b8daff;
        }
        .flash-message.info i {
            color: #17a2b8;
        }
        .flash-message.info .progress-bar {
            background: #17a2b8;
        }

        /* ===== انیمیشن‌ها ===== */
        @keyframes slideUp {
            0% {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes slideDown {
            0% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            100% {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
        }

        @keyframes progressShrink {
            0% {
                width: 100%;
            }
            100% {
                width: 0%;
            }
        }

        /* ===== ریسپانسیو ===== */
        @media (max-width: 768px) {
            .flash-messages {
                bottom: 20px;
                width: 95%;
                max-width: 100%;
                gap: 10px;
            }
            .flash-message {
                padding: 14px 16px;
                font-size: 14px;
                border-radius: 12px;
            }
            .flash-message i {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .flash-messages {
                bottom: 15px;
                width: 96%;
            }
            .flash-message {
                padding: 12px 14px;
                font-size: 13px;
                border-radius: 10px;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    {{-- ===== پیام‌های فلش ===== --}}
    <div class="flash-messages" id="flashMessages">
        @if(session('error'))
            <div class="flash-message error" data-type="error">
                <i class="fas fa-exclamation-circle"></i>
                <span class="message-text">{{ session('error') }}</span>
                <button type="button" class="btn-close-custom" onclick="removeMessage(this)">&times;</button>
                <div class="progress-bar"></div>
            </div>
        @endif

        @if(session('success'))
            <div class="flash-message success" data-type="success">
                <i class="fas fa-check-circle"></i>
                <span class="message-text">{{ session('success') }}</span>
                <button type="button" class="btn-close-custom" onclick="removeMessage(this)">&times;</button>
                <div class="progress-bar"></div>
            </div>
        @endif

        @if(session('warning'))
            <div class="flash-message warning" data-type="warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span class="message-text">{{ session('warning') }}</span>
                <button type="button" class="btn-close-custom" onclick="removeMessage(this)">&times;</button>
                <div class="progress-bar"></div>
            </div>
        @endif

        @if(session('info'))
            <div class="flash-message info" data-type="info">
                <i class="fas fa-info-circle"></i>
                <span class="message-text">{{ session('info') }}</span>
                <button type="button" class="btn-close-custom" onclick="removeMessage(this)">&times;</button>
                <div class="progress-bar"></div>
            </div>
        @endif
    </div>

    @include('layout.header')
    <div class="header-spacer"></div>
    
    {{-- Overlay برای بستن منو --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dashboard-wrapper">
        @if (Auth::user()->hasRole('teacher|admin'))
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

    <script>
        // ===== مدیریت پیام‌های فلش =====
        document.addEventListener('DOMContentLoaded', function() {
            // حذف خودکار پیام‌ها بعد از 5 ثانیه
            const messages = document.querySelectorAll('.flash-message');
            messages.forEach(function(message) {
                setTimeout(function() {
                    removeMessage(message);
                }, 5000);
            });

            // کلیک روی پیام برای حذف (اختیاری)
            messages.forEach(function(message) {
                message.addEventListener('click', function(e) {
                    // اگر روی دکمه بستن کلیک نشده باشد
                    if (!e.target.closest('.btn-close-custom')) {
                        removeMessage(this);
                    }
                });
            });
        });

        // ===== تابع حذف پیام =====
        function removeMessage(element) {
            // اگر element یک دکمه بود، والد آن را پیدا کن
            const message = element.closest ? element.closest('.flash-message') : element;
            
            if (message) {
                // کلاس removing را اضافه کن برای انیمیشن
                message.classList.add('removing');
                
                // بعد از اتمام انیمیشن، عنصر را حذف کن
                setTimeout(function() {
                    message.remove();
                    
                    // اگر پیامی باقی نماند، کانتینر را هم حذف کن (اختیاری)
                    const container = document.getElementById('flashMessages');
                    if (container && container.children.length === 0) {
                        // می‌توانید کانتینر را مخفی کنید یا حذف کنید
                        // container.style.display = 'none';
                    }
                }, 500);
            }
        }

        // ===== تابع نمایش پیام جدید (برای استفاده در جاوااسکریپت) =====
        function showFlashMessage(type, text, duration = 5000) {
            const container = document.getElementById('flashMessages');
            if (!container) return;

            const icons = {
                error: 'fa-exclamation-circle',
                success: 'fa-check-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };

            const message = document.createElement('div');
            message.className = `flash-message ${type}`;
            message.innerHTML = `
                <i class="fas ${icons[type] || 'fa-info-circle'}"></i>
                <span class="message-text">${text}</span>
                <button type="button" class="btn-close-custom" onclick="removeMessage(this)">&times;</button>
                <div class="progress-bar"></div>
            `;

            container.appendChild(message);

            // حذف خودکار بعد از زمان مشخص
            setTimeout(function() {
                removeMessage(message);
            }, duration);
        }

        // ===== مدیریت منوی سایدبار =====
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
</body>
</html>