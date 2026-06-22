document.addEventListener('DOMContentLoaded', function () {
    var splide = new Splide('.splide', {
        fixedWidth: 330,
        gap: 5,
        direction: 'rtl',
        pagination: false,
        breakpoints: {
            500: { gap: 20 },
            768: { gap: 40 },
        },
    });

    // Bind custom buttons
    splide.on('mounted', function () {
        document.querySelector('.prev-btn').addEventListener('click', function () {
            splide.go('<');
        });
        document.querySelector('.next-btn').addEventListener('click', function () {
            splide.go('>');
        });
    });

    splide.mount();

    // --- تنظیمات اسلایدر تخفیفات روز (دومی) ---
    // استفاده از ID برای جدا کردن تنظیمات
    var daySplide = new Splide('#day-slider', {
        fixedWidth: 330,
        gap: 5,
        direction: 'rtl',
        pagination: false,
        breakpoints: {
            768: { fixedWidth: 400 },
        },
    });


    daySplide.mount();

    // --- اسکریپت تایمر شمارش معکوس ---
    let timeInSeconds = 5 * 60 * 60 + 42 * 60 + 15; // 05:42:15

    const hoursEl = document.getElementById('hours');
    const minutesEl = document.getElementById('minutes');
    const secondsEl = document.getElementById('seconds');

    function updateTimer() {
        const hours = Math.floor(timeInSeconds / 3600);
        const minutes = Math.floor((timeInSeconds % 3600) / 60);
        const seconds = timeInSeconds % 60;

        hoursEl.textContent = hours.toString().padStart(2, '0');
        minutesEl.textContent = minutes.toString().padStart(2, '0');
        secondsEl.textContent = seconds.toString().padStart(2, '0');

        if (timeInSeconds > 0) {
            timeInSeconds--;
        } else {
            // ریست کردن تایمر وقتی تمام شد
            timeInSeconds = 5 * 60 * 60;
        }
    }

    setInterval(updateTimer, 1000);
    updateTimer(); // اجرای فوری برای جلوگیری از تاخیر
});