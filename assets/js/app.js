/**
 * app.js – Kamen Riders
 * Vanilla JS | No external dependencies
 */

(function () {
  'use strict';

  /* ──────────────────────────────────────────────────────
     1. TRACKING CONFIG (populated by PHP in index.php head)
  ────────────────────────────────────────────────────── */
  const T = window.__T__ || {};

  /* ──────────────────────────────────────────────────────
     2. UTM / CLICK ID CAPTURE  (sessionStorage)
  ────────────────────────────────────────────────────── */
  const UTM_KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term',
    'fbclid', 'gclid', 'wbraid', 'gbraid'];
  const params = new URLSearchParams(window.location.search);

  UTM_KEYS.forEach(function (k) {
    const v = params.get(k);
    if (v) sessionStorage.setItem(k, v);
  });

  if (document.referrer) {
    try {
      if (new URL(document.referrer).hostname !== location.hostname) {
        sessionStorage.setItem('referrer', document.referrer);
      }
    } catch (_) { }
  }

  // Fill hidden form fields
  function fillHidden() {
    UTM_KEYS.forEach(function (k) {
      var el = document.getElementById('f_' + k);
      if (el) el.value = sessionStorage.getItem(k) || '';
    });
    var ref = document.getElementById('f_referrer');
    if (ref) ref.value = sessionStorage.getItem('referrer') || document.referrer || '';
  }

  /* ──────────────────────────────────────────────────────
     3. PIXEL / ANALYTICS HELPERS
  ────────────────────────────────────────────────────── */
  function loadScript(src, cb) {
    var s = document.createElement('script');
    s.src = src; s.async = true;
    if (cb) s.onload = cb;
    document.head.appendChild(s);
  }

  function initGA4() {
    if (!T.ga4) return;
    loadScript('https://www.googletagmanager.com/gtag/js?id=' + T.ga4, function () {
      window.dataLayer = window.dataLayer || [];
      window.gtag = function () { window.dataLayer.push(arguments); };
      gtag('js', new Date());
      gtag('config', T.ga4, { anonymize_ip: true });
      gtag('event', 'page_view');
    });
  }

  function initGAds() {
    if (!T.gadsAw) return;
    window.dataLayer = window.dataLayer || [];
    window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
    loadScript('https://www.googletagmanager.com/gtag/js?id=' + T.gadsAw);
    gtag('js', new Date());
    gtag('config', T.gadsAw);
  }

  function initMeta() {
    if (!T.meta) return;
    !function (f, b, e, v, n, t, s) {
      if (f.fbq) return; n = f.fbq = function () {
        n.callMethod ?
        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
      n.queue = []; t = b.createElement(e); t.async = !0;
      t.src = v; s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
    window.fbq('init', T.meta);
    window.fbq('track', 'PageView');
  }

  function fireGA4(name, props) {
    try { if (typeof gtag === 'function') gtag('event', name, props || {}); } catch (_) { }
  }

  function fireMeta(event, props) {
    try { if (typeof fbq === 'function') fbq('track', event, props || {}); } catch (_) { }
  }

  function fireGAdsConversion() {
    if (!T.gadsAw || !T.gadsLabel) return;
    try { gtag('event', 'conversion', { send_to: T.gadsAw + '/' + T.gadsLabel }); } catch (_) { }
  }

  // ViewContent on scroll past hero
  var vcFired = false;
  function fireViewContent() {
    if (vcFired) return; vcFired = true;
    fireGA4('view_item', { item_name: 'Jersey Kamen Rider Ichigo & Black Edisi 1', value: 225000, currency: 'IDR' });
    fireMeta('ViewContent', { content_name: 'Jersey Kamen Rider', value: 225000, currency: 'IDR' });
  }

  /* ──────────────────────────────────────────────────────
     4. COUNTDOWN TIMER
  ────────────────────────────────────────────────────── */
  function initCountdown() {
    var deadline = T.promoDeadline; // ISO string, Asia/Jakarta
    if (!deadline) return;

    var endTime = new Date(deadline).getTime();
    var cdH = document.getElementById('cd-h');
    var cdM = document.getElementById('cd-m');
    var cdS = document.getElementById('cd-s');
    var cdBox = document.getElementById('countdown-wrap');
    var endBox = document.getElementById('promo-ended');
    var orderCta = document.getElementById('btnOrder');
    var formSubmit = document.getElementById('btnSubmit');

    if (!cdH || !cdM || !cdS) return;

    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function tick() {
      var now = Date.now();
      var diff = endTime - now;

      if (diff <= 0) {
        cdH.textContent = '00'; cdM.textContent = '00'; cdS.textContent = '00';
        if (cdBox) cdBox.style.display = 'none';
        if (endBox) endBox.style.display = 'block';
        if (orderCta) orderCta.setAttribute('disabled', 'disabled');
        if (formSubmit) formSubmit.setAttribute('disabled', 'disabled');
        return;
      }

      var totalSec = Math.floor(diff / 1000);
      var h = Math.floor(totalSec / 3600);
      var m = Math.floor((totalSec % 3600) / 60);
      var s = totalSec % 60;

      cdH.textContent = pad(h);
      cdM.textContent = pad(m);
      cdS.textContent = pad(s);

      setTimeout(tick, 1000);
    }
    tick();
  }

  /* ──────────────────────────────────────────────────────
     5. INSTAGRAM LAZY EMBED (IntersectionObserver)
  ────────────────────────────────────────────────────── */
  var igScriptLoaded = false;

  function loadIGScript() {
    if (igScriptLoaded) {
      if (window.instgrm) window.instgrm.Embeds.process();
      return;
    }
    igScriptLoaded = true;
    loadScript('https://www.instagram.com/embed.js', function () {
      if (window.instgrm) window.instgrm.Embeds.process();
    });
  }

  function initIGEmbeds() {
    var placeholders = document.querySelectorAll('.ig-placeholder[data-url]');
    if (!placeholders.length) return;

    var io = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        obs.unobserve(entry.target);

        var wrap = entry.target;
        var url = wrap.getAttribute('data-url');

        // Remove skeleton content
        wrap.innerHTML = '';
        wrap.classList.add('loaded');

        // Build blockquote for IG embed
        var bq = document.createElement('blockquote');
        bq.className = 'instagram-media';
        bq.setAttribute('data-instgrm-permalink', url);
        bq.setAttribute('data-instgrm-version', '14');
        bq.style.cssText = 'background:#1a1a1a;border:1px solid #2a2a2a;border-radius:6px;width:100%;min-width:unset!important;';

        // Fallback link inside blockquote
        var inner = document.createElement('div');
        inner.style.cssText = 'padding:12px;text-align:center';
        var link = document.createElement('a');
        link.href = url;
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
        link.className = 'ig-fallback-link';
        link.textContent = 'Lihat Post Instagram →';
        inner.appendChild(link);
        bq.appendChild(inner);
        wrap.appendChild(bq);

        loadIGScript();
      });
    }, { rootMargin: '200px 0px', threshold: 0 });

    placeholders.forEach(function (el) { io.observe(el); });
  }

  /* ──────────────────────────────────────────────────────
     6. SMOOTH SCROLL
  ────────────────────────────────────────────────────── */
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function (a) {
      a.addEventListener('click', function (e) {
        var href = a.getAttribute('href');
        if (!href || href === '#') return;
        var target = document.querySelector(href);
        if (!target) return;
        e.preventDefault();
        var topbar = document.querySelector('.topbar');
        var offset = topbar ? topbar.offsetHeight + 12 : 12;
        window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
      });
    });
  }

  /* ──────────────────────────────────────────────────────
     7. CLICK TRACKING
  ────────────────────────────────────────────────────── */
  function initClickTracking() {
    // InitiateCheckout on "Pesan Sekarang" CTA
    document.querySelectorAll('[data-track="initiate_checkout"]').forEach(function (el) {
      el.addEventListener('click', function () {
        fireGA4('begin_checkout', { value: 225000, currency: 'IDR' });
        fireMeta('InitiateCheckout', { value: 225000, currency: 'IDR' });
      });
    });

    // WhatsApp Contact
    document.querySelectorAll('[data-track="wa_contact"]').forEach(function (el) {
      el.addEventListener('click', function () {
        fireGA4('contact', { method: 'whatsapp' });
        fireMeta('Contact');
      });
    });

    // ViewContent after hero visible
    var heroSection = document.getElementById('home');
    if (heroSection && 'IntersectionObserver' in window) {
      var vcObs = new IntersectionObserver(function (entries) {
        if (entries[0].isIntersecting) { fireViewContent(); vcObs.disconnect(); }
      }, { threshold: 0.3 });
      vcObs.observe(heroSection);
    }
  }

  /* ──────────────────────────────────────────────────────
     8. FORM ENHANCE (client-side validation)
  ────────────────────────────────────────────────────── */
  function initForm() {
    var form = document.getElementById('orderForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      var errBox = document.getElementById('formError');
      var errors = [];
      var name = form.querySelector('[name="name"]');
      var phone = form.querySelector('[name="phone"]');
      var addr = form.querySelector('[name="address"]');
      var design = form.querySelector('[name="design"]');
      var size = form.querySelector('[name="size"]');
      var agree = form.querySelector('[name="agree_dp"]');

      if (!name || !name.value.trim()) errors.push('Nama wajib diisi.');
      if (!phone || !phone.value.trim()) errors.push('Nomor WhatsApp wajib diisi.');
      if (!addr || !addr.value.trim()) errors.push('Alamat wajib diisi.');
      if (!design || !design.value) errors.push('Pilih desain jersey.');
      if (!size || !size.value) errors.push('Pilih ukuran.');
      if (agree && !agree.checked) errors.push('Setujui syarat DP terlebih dahulu.');

      if (errors.length) {
        e.preventDefault();
        if (errBox) {
          errBox.style.display = 'block';
          errBox.textContent = errors.join(' ');
          errBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return;
      }

      // Fire Lead tracking
      fireGA4('generate_lead', { value: 100000, currency: 'IDR' });
      fireMeta('Lead', { value: 100000, currency: 'IDR' });
    });
  }

  /* ──────────────────────────────────────────────────────
     9. STICKY WA LINK
  ────────────────────────────────────────────────────── */
  function initWA() {
    var msg = 'Halo Admin Ozverligsportwear, saya tertarik dengan Jersey Kamen Rider Ichigo & Black Edisi 1. Boleh info lebih lanjut?';
    var url = 'https://wa.me/6281617260666?text=' + encodeURIComponent(msg);
    document.querySelectorAll('[data-wa]').forEach(function (el) {
      el.href = url;
    });
  }

  /* ──────────────────────────────────────────────────────
     10. INIT
  ────────────────────────────────────────────────────── */
  function init() {
    fillHidden();
    initCountdown();
    initIGEmbeds();
    initSmoothScroll();
    initClickTracking();
    initForm();
    initWA();
    // Defer pixel inits to not block render
    setTimeout(function () {
      initGA4();
      initGAds();
      initMeta();
    }, 800);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();