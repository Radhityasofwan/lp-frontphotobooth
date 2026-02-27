(function () {
  'use strict';

  // ---------- UTM capture ----------
  const params = new URLSearchParams(window.location.search);
  const utmKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];

  function setInput(id, val) {
    const el = document.getElementById(id);
    if (el) el.value = val || '';
  }

  // store UTMs in sessionStorage for navigation without query
  utmKeys.forEach(k => {
    const v = params.get(k);
    if (v) sessionStorage.setItem(k, v);
  });

  utmKeys.forEach(k => setInput(k, sessionStorage.getItem(k) || params.get(k) || ''));

  // ---------- WhatsApp links ----------
  const waNumber = '6281617260666';
  function makeWaLink(text) {
    return 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(text);
  }

  const waText = [
    'Halo admin, saya ingin tanya Jersey Kamen Rider (Edisi 1).',
    '',
    'Nama:',
    'Desain:',
    'Size:',
    'Jumlah:',
    '',
    'Sumber: ' + (sessionStorage.getItem('utm_source') || '-'),
    'Campaign: ' + (sessionStorage.getItem('utm_campaign') || '-')
  ].join('\n');

  const waLink = document.getElementById('waLink');
  const waSticky = document.getElementById('waSticky');
  if (waLink) waLink.href = makeWaLink(waText);
  if (waSticky) waSticky.href = makeWaLink(waText);

  // ---------- Tracking bootstrap ----------
  const T = (window.__TRACKING__ || {});
  const hasGA4 = !!(T.ga4 && T.ga4.trim());
  const hasGAds = !!(T.gadsAw && T.gadsAw.trim());
  const hasMeta = !!(T.metaPixel && T.metaPixel.trim());

  // GA4
  if (hasGA4) {
    const s = document.createElement('script');
    s.async = true;
    s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(T.ga4);
    document.head.appendChild(s);

    window.dataLayer = window.dataLayer || [];
    window.gtag = function(){ window.dataLayer.push(arguments); };
    window.gtag('js', new Date());
    window.gtag('config', T.ga4, {
      anonymize_ip: true
    });
  }

  // Google Ads base tag (optional but recommended if you use conversion)
  if (hasGAds) {
    const s2 = document.createElement('script');
    s2.async = true;
    s2.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(T.gadsAw);
    document.head.appendChild(s2);

    window.dataLayer = window.dataLayer || [];
    window.gtag = window.gtag || function(){ window.dataLayer.push(arguments); };
    window.gtag('js', new Date());
    window.gtag('config', T.gadsAw);
  }

  // Meta Pixel
  if (hasMeta) {
    !function(f,b,e,v,n,t,s){
      if(f.fbq)return; n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n; n.push=n; n.loaded=!0; n.version='2.0';
      n.queue=[]; t=b.createElement(e); t.async=!0;
      t.src=v; s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)
    }(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');
    window.fbq('init', T.metaPixel);
    window.fbq('track', 'PageView');
    window.fbq('track', 'ViewContent');
  }

  // ---------- Event helper ----------
  function track(name, extra) {
    extra = extra || {};
    // GA4 custom event
    if (window.gtag && hasGA4) {
      window.gtag('event', name, extra);
    }
    // Meta standard/custom event
    if (window.fbq && hasMeta) {
      if (name === 'form_submit') window.fbq('track', 'Lead');
      if (name === 'wa_click') window.fbq('track', 'Contact');
      // also custom
      window.fbq('trackCustom', name, extra);
    }
  }

  // Track clicks by data-track attribute
  document.addEventListener('click', function (e) {
    const a = e.target.closest('[data-track]');
    if (!a) return;

    const ev = a.getAttribute('data-track');
    track(ev, {
      utm_source: sessionStorage.getItem('utm_source') || '',
      utm_campaign: sessionStorage.getItem('utm_campaign') || ''
    });

    // Google Ads conversion on WhatsApp click (optional)
    if (window.gtag && hasGAds && ev && ev.indexOf('wa') >= 0 && T.gadsLabel) {
      window.gtag('event', 'conversion', {
        'send_to': T.gadsAw + '/' + T.gadsLabel
      });
    }
  });

  // Track form view
  track('page_ready', {
    utm_source: sessionStorage.getItem('utm_source') || '',
    utm_campaign: sessionStorage.getItem('utm_campaign') || ''
  });

})();