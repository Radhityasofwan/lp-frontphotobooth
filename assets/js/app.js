/**
 * Minimal JS (UTM & Countdown)
 */
(function () {
  // Capture UTMs
  const params = new URLSearchParams(location.search);
  const fields = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid', 'wbraid', 'gbraid'];

  fields.forEach(f => {
    if (params.has(f)) sessionStorage.setItem(f, params.get(f));
  });
  if (document.referrer && !sessionStorage.getItem('referrer')) {
    sessionStorage.setItem('referrer', document.referrer);
  }

  // Populate hidden form on dom load
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('orderForm');
    if (form) {
      fields.forEach(f => {
        let val = sessionStorage.getItem(f);
        let inp = document.getElementById('f_' + f);
        if (inp && val) inp.value = val;
      });
      let ref = sessionStorage.getItem('referrer');
      if (ref && document.getElementById('f_referrer')) {
        document.getElementById('f_referrer').value = ref;
      }
    }

    // Countdown Timer logic
    const cdWrap = document.getElementById('promo-cd');
    const endedWrap = document.getElementById('promo-ended');
    if (!cdWrap || typeof PROMO_DEADLINE === 'undefined') return;

    const end = new Date(PROMO_DEADLINE).getTime();

    function tick() {
      const now = new Date().getTime();
      const diff = end - now;

      if (diff <= 0) {
        cdWrap.style.display = 'none';
        if (endedWrap) endedWrap.style.display = 'block';
        return;
      }

      const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const s = Math.floor((diff % (1000 * 60)) / 1000);

      const eh = document.getElementById('cd-h');
      const em = document.getElementById('cd-m');
      const es = document.getElementById('cd-s');

      if (eh) eh.innerText = h.toString().padStart(2, '0');
      if (em) em.innerText = m.toString().padStart(2, '0');
      if (es) es.innerText = s.toString().padStart(2, '0');

      setTimeout(tick, 1000);
    }
    tick();
  });
})();