<?php
$page_title = 'Snap & Smile';
require_once __DIR__ . '/header.php';
?>

<!-- Minimal Header / Wave Divider -->
<section class="bg-orange-gradient position-relative pb-5" style="padding-top: 5rem;">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-10 text-center text-white">
        <h1 class="display-2 fw-bold mb-2 text-shadow-sm font-monospace">SNAP & SMILE!</h1>
        <p class="lead fw-medium opacity-75">Cobain experiencenya langsung. Siap-siap, gaya paling gokil yang bakal
            di-capture!</p>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">

            <div class="col-md-6 col-lg-5 position-relative js-scroll">

                <i class="ph-fill ph-sparkle text-white position-absolute float-anim z-10"
                    style="font-size: 3rem; top: -30px; right: -20px; opacity: 0.8;"></i>

                <!-- Fake Photobooth UI -->
                <div class="bg-dark p-4 rounded-4 shadow-lg text-center" style="border: 8px solid #FFF;">

                    <div class="bg-black rounded-3 d-flex align-items-center justify-content-center position-relative overflow-hidden mb-4"
                        style="height: 350px;">
                        <i class="ph-duotone ph-user ph-icon-xl text-secondary"></i>

                        <!-- Photobooth Overlay Frame Mockup -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 border border-4 border-white opacity-25"
                            style="pointer-events:none;"></div>
                        <div class="position-absolute bottom-0 start-0 w-100 p-2 text-white bg-dark bg-opacity-50 font-monospace small"
                            style="letter-spacing:1px;">
                            <i class="ph-fill ph-camera text-danger blink"></i> REC
                        </div>

                        <!-- Countdown Number inside Camera Mockup -->
                        <div id="countdown-display"
                            class="position-absolute top-50 start-50 translate-middle display-1 fw-bold text-white d-none"
                            style="text-shadow: 0 4px 20px rgba(0,0,0,0.5);">3</div>
                    </div>

                    <button id="btn-snap"
                        class="btn btn-playful w-100 py-3 rounded-pill shadow-lg border-2 border-white">
                        <i class="ph-bold ph-camera"></i> Siap Pose!
                    </button>

                </div>
            </div>

        </div>
    </div>

</section>

<!-- Additional Interactive JS logic specific to this page -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btnSnap = document.getElementById('btn-snap');
        const countdownDisplay = document.getElementById('countdown-display');

        // Existing flash logic from global JS
        const flashElement = document.querySelector('.screen-flash');
        const triggerFlash = () => {
            if (flashElement) {
                flashElement.classList.add('active');
                setTimeout(() => flashElement.classList.remove('active'), 150);
                setTimeout(() => {
                    flashElement.classList.add('active');
                    setTimeout(() => flashElement.classList.remove('active'), 100);
                }, 300); // double flash
            }
        };

        btnSnap.addEventListener('click', (e) => {
            btnSnap.disabled = true;
            btnSnap.innerHTML = '<i class="ph-bold ph-spinner-gap ph-spin"></i> Wait...';
            countdownDisplay.classList.remove('d-none');

            let counter = 3;
            countdownDisplay.innerText = counter;

            const countdownInterval = setInterval(() => {
                counter--;
                if (counter > 0) {
                    countdownDisplay.innerText = counter;
                } else {
                    clearInterval(countdownInterval);
                    countdownDisplay.innerText = "SMILE!";
                    setTimeout(() => {
                        triggerFlash();
                        countdownDisplay.classList.add('d-none');
                        btnSnap.disabled = false;
                        btnSnap.innerHTML = '<i class="ph-bold ph-check-circle"></i> Mantap! Booking Aslinya Sekarng?';
                        btnSnap.classList.remove('btn-playful');
                        btnSnap.classList.add('btn-light', 'text-orange');

                        // Track fake snap
                        if (typeof sendEvent === 'function') {
                            sendEvent('fake_snap_interaction');
                        }
                    }, 500);
                }
            }, 1000);
        });
    });
</script>

<?php require_once __DIR__ . '/footer.php'; ?>