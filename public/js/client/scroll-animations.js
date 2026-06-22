// Scroll Animations
window.addEventListener('DOMContentLoaded', function () {
    const sections = document.querySelectorAll('.animate-section');

    function checkSections() {
        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (sectionTop < windowHeight * 0.75) {
                section.classList.add('visible');
            }
        });
    }
    window.addEventListener('scroll', checkSections);
    window.addEventListener('load', checkSections);
});