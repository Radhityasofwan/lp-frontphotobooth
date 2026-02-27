document.addEventListener('DOMContentLoaded', () => {
  // 1. UTM and Click ID Tracking
  const urlParams = new URLSearchParams(window.location.search);
  const trackingKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid'];

  trackingKeys.forEach(key => {
    let value = urlParams.get(key);
    if (value) {
      sessionStorage.setItem(key, value);
    } else {
      value = sessionStorage.getItem(key) || '';
    }

    // Fill hidden form fields if they exist
    const input = document.getElementById(key);
    if (input) {
      input.value = value;
    }
  });

  // Capture referrer if it's external, or load from session
  if (document.referrer && new URL(document.referrer).hostname !== window.location.hostname) {
    sessionStorage.setItem('referrer', document.referrer);
  }

  // 2. Smooth Scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (href !== '#') {
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          const topbarHeight = document.querySelector('.topbar')?.offsetHeight || 0;
          const targetPosition = target.getBoundingClientRect().top + window.scrollY - topbarHeight - 20;
          window.scrollTo({ top: targetPosition, behavior: 'smooth' });
        }
      }
    });
  });

  // 3. Pixel & Analytics Event Tracking Helper
  const trackEvent = (eventName, params = {}) => {
    try {
      if (typeof gtag === 'function') {
        gtag('event', eventName, params);
      }
      if (typeof fbq === 'function') {
        // Map GA4 events to FB Pixel standard events where appropriate
        let fbEvent = 'Track';
        if (eventName === 'Lead') fbEvent = 'Lead';
        if (eventName === 'Purchase') fbEvent = 'Purchase';
        if (eventName === 'Begin_Checkout') fbEvent = 'InitiateCheckout';

        fbq('track', fbEvent, params);
      }
    } catch (error) {
      console.warn('Tracking issue:', error);
    }
  };

  // Track button clicks
  document.querySelectorAll('[data-track]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const trackName = btn.getAttribute('data-track');
      trackEvent('click_button', { element: trackName });
    });
  });

  // Track form submission
  const orderForm = document.getElementById('orderForm');
  if (orderForm) {
    orderForm.addEventListener('submit', () => {
      trackEvent('Lead', { event_category: 'Order_Form', value: 100000, currency: 'IDR' });
    });
  }

  // 4. Set Sticky WhatsApp Link
  const waLink = document.getElementById('waSticky');
  if (waLink) {
    // Base WA link can be configured to add dynamic message
    waLink.href = `https://wa.me/6281617260666?text=Halo%20Admin%20Ozverligsportwear,%20saya%20tertarik%20pesan%20Jersey%20Kamen%20Rider`;
  }
});