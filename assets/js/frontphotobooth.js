/**
 * Front Photobooth - Tracking & Interactions (Playful Theme)
 */

document.addEventListener('DOMContentLoaded', () => {
    // ---- 1. Fade-in on Scroll ----
    const scrollElements = document.querySelectorAll('.js-scroll');

    const elementInView = (el, dividend = 1) => {
        const elementTop = el.getBoundingClientRect().top;
        return (elementTop <= (window.innerHeight || document.documentElement.clientHeight) / dividend);
    };

    const displayScrollElement = (element) => {
        element.classList.add('is-visible');
    };

    const handleScrollAnimation = () => {
        scrollElements.forEach((el) => {
            if (elementInView(el, 1.15)) {
                displayScrollElement(el);
            }
        });
    };

    // Initial check
    setTimeout(() => handleScrollAnimation(), 100);
    window.addEventListener('scroll', () => {
        handleScrollAnimation();
    }, {
        passive: true
    });

    // ---- 2. Navbar Scroll Effect ----
    const mainNav = document.getElementById('mainNav');
    if (mainNav) {
        const threshold = 24;
        let isScrolled = window.scrollY > threshold;
        let ticking = false;

        const applyNavbarState = () => {
            mainNav.classList.toggle('is-scrolled', isScrolled);
            ticking = false;
        };

        const onScrollNav = () => {
            const nextState = window.scrollY > threshold;
            if (nextState === isScrolled) {
                return;
            }
            isScrolled = nextState;
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(applyNavbarState);
            }
        };

        applyNavbarState();
        window.addEventListener('scroll', onScrollNav, { passive: true });
    }


    // ---- 3. Flash Effect Interaction ----
    const flashElement = document.createElement('div');
    flashElement.className = 'screen-flash';
    document.body.appendChild(flashElement);

    const triggerFlash = () => {
        flashElement.classList.add('active');
        setTimeout(() => {
            flashElement.classList.remove('active');
        }, 100);
    };

    // ---- 4. Tracking System ----
    let sessionId = sessionStorage.getItem('fp_session_id');
    if (!sessionId) {
        sessionId = crypto.randomUUID ? crypto.randomUUID() : 'sess_' + Math.random().toString(36).substr(2, 9) + Date.now();
        sessionStorage.setItem('fp_session_id', sessionId);
    }

    const urlParams = new URLSearchParams(window.location.search);
    const utms = {
        utm_source: urlParams.get('utm_source'),
        utm_medium: urlParams.get('utm_medium'),
        utm_campaign: urlParams.get('utm_campaign')
    };

    const sendEvent = (eventType, eventValue = 0) => {
        fetch('track.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                session_id: sessionId,
                event_type: eventType,
                event_value: eventValue,
                page_url: window.location.href,
                ...utms
            }),
            keepalive: true
        }).catch(err => console.error('Tracking Error:', err));
    };

    sendEvent('view');

    document.querySelectorAll('[data-track]').forEach(el => {
        el.addEventListener('click', (e) => {
            const trackName = el.getAttribute('data-track');
            sendEvent(trackName);
            if (trackName.includes('cta')) triggerFlash(); // Flash on main CTA clicks
        });
    });

    let scrollMarks = { 25: false, 50: false, 75: false, 90: false };
    window.addEventListener('scroll', () => {
        const h = document.documentElement;
        const b = document.body;
        const st = 'scrollTop';
        const sh = 'scrollHeight';

        const percent = Math.round((h[st] || b[st]) / ((h[sh] || b[sh]) - h.clientHeight) * 100);

        Object.keys(scrollMarks).forEach(mark => {
            if (percent >= parseInt(mark) && !scrollMarks[mark]) {
                scrollMarks[mark] = true;
                sendEvent('scroll_depth', parseInt(mark));
            }
        });
    }, { passive: true });

    let secondsSpent = 0;
    const heartbeat = setInterval(() => {
        secondsSpent += 15;
        if (secondsSpent <= 300) {
            sendEvent('time_spent', secondsSpent);
        } else {
            clearInterval(heartbeat);
        }
    }, 15000);
});
