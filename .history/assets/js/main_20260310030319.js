document.addEventListener('DOMContentLoaded', () => {

    /**
     * Scroll Animation
     * Adds 'is-visible' class to elements with 'js-scroll' when they enter the viewport.
     */
    const scrollElements = document.querySelectorAll('.js-scroll');

    const elementInView = (el, dividend = 1) => {
        const elementTop = el.getBoundingClientRect().top;
        return (
            elementTop <= (window.innerHeight || document.documentElement.clientHeight) / dividend
        );
    };

    const handleScrollAnimation = () => {
        scrollElements.forEach((el) => {
            if (elementInView(el, 1.25)) {
                el.classList.add('is-visible');
            }
        });
    }

    window.addEventListener('scroll', () => {
        handleScrollAnimation();
    });

    // Trigger on load
    handleScrollAnimation();

});