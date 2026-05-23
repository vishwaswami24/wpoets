(function () {
  'use strict';

  function apiList() {
    return fetch('api/list.php', { method: 'GET' }).then((r) => r.json());
  }

  function setActiveTab(index) {
    const tabButtons = document.querySelectorAll('[data-tab-index]');
    tabButtons.forEach((btn) => {
      const isActive = Number(btn.getAttribute('data-tab-index')) === index;
      btn.classList.toggle('active', isActive);
      btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });
  }

  function setActiveSlideWithinCarousel($carousel, targetIdx) {
    // Bootstrap carousel uses index via data-slide-to
    const slides = $carousel.find('.carousel-item');
    if (!slides.length) return;
    const safeIdx = Math.max(0, Math.min(targetIdx, slides.length - 1));
    $carousel.carousel(safeIdx);
  }

  function init() {
    const tabPaneByIndex = new Map();

    apiList().then((data) => {
      const items = data.items || [];

      // Desktop tabs
      const tabsWrap = document.getElementById('web-tabs');
      const panesWrap = document.getElementById('web-panes');

      // Mobile accordion
      const accordionWrap = document.getElementById('mobile-accordion');

      // Build UI from DB
      if (tabsWrap) tabsWrap.innerHTML = '';
      if (panesWrap) panesWrap.innerHTML = '';
      if (accordionWrap) accordionWrap.innerHTML = '';

      items.forEach((item, idx) => {
        // Columns:
        // Column 1 tab/accordion label controls Column 2 carousel slide.
        // Column 2 background carousel uses the current item's web_image.

        // Desktop tab
        const tabId = `tab-${item.id}`;
        const tabBtn = document.createElement('button');
        tabBtn.type = 'button';
        tabBtn.className = 'nav-link' + (idx === 0 ? ' active' : '');
        tabBtn.setAttribute('data-tab-index', String(idx));
        tabBtn.setAttribute('aria-controls', tabId);
        tabBtn.setAttribute('role', 'tab');
        tabBtn.textContent = item.title;
        tabBtn.addEventListener('click', () => {
          // Change carousel control by index (single carousel reused)
          setActiveTab(idx);
          const $carousel = window.jQuery ? window.jQuery('#web-carousel') : null;
          if ($carousel && $carousel.length) {
            setActiveSlideWithinCarousel($carousel, idx);
          }
          // Also reflect in mobile
          if (window.jQuery && window.jQuery('#mobile-carousel').length) {
            window.jQuery('#mobile-carousel').carousel(idx);
          }
        });
        if (tabsWrap) tabsWrap.appendChild(tabBtn);

        // Desktop carousel item: use each item web_image as background for column 2
        const webUrl = item.web_image_path;
        const mobileUrl = item.mobile_bg_image_path || item.web_image_path;

        if (panesWrap) {
          const itemEl = document.createElement('div');
          const active = idx === 0 ? 'active' : '';
          itemEl.className = `carousel-item ${active}`;
          itemEl.setAttribute('data-index', String(idx));

          itemEl.innerHTML = `
            <div class="carousel-bg" style="background-image:url('${webUrl}')"></div>
          `;

          // Also create mobile carousel slides (background-image) to match column 3
          const mobilePanes = document.getElementById('mobile-panes');
          if (mobilePanes) {
            const mobileItem = document.createElement('div');
            mobileItem.className = `carousel-item ${idx === 0 ? 'active' : ''}`;
            mobileItem.innerHTML = `<div class="mobile-bg" style="background-image:url('${mobileUrl}')"></div>`;
            mobilePanes.appendChild(mobileItem);
          }

          tabPaneByIndex.set(idx, itemEl);
          panesWrap.appendChild(itemEl);
        }

        // Mobile accordion
        const accItem = document.createElement('div');
        accItem.className = 'accordion-item';
        const headerId = `acc-h-${item.id}`;
        const collapseId = `acc-c-${item.id}`;
        accItem.innerHTML = `
          <h2 class="accordion-header" id="${headerId}">
            <button class="accordion-button ${idx === 0 ? '' : 'collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="${idx === 0 ? 'true' : 'false'}" aria-controls="${collapseId}" data-mobile-select="${idx}">
              ${item.title}
            </button>
          </h2>
          <div id="${collapseId}" class="accordion-collapse collapse ${idx === 0 ? 'show' : ''}" data-bs-parent="#mobile-accordion">
            <div class="accordion-body p-0">
              <div style="height:220px;background-image:url('${mobileUrl}');background-size:cover;background-position:center;border-radius:0 0 8px 8px;"></div>
            </div>
          </div>
        `;

        accordionWrap && accordionWrap.appendChild(accItem);
      });

      // Sync mobile carousel when accordion tab is opened
      accordionWrap && accordionWrap.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-mobile-select]');
        if (!btn) return;
        const idx = Number(btn.getAttribute('data-mobile-select'));
        if (Number.isNaN(idx)) return;
        setActiveTab(idx);
        if (window.jQuery) {
          const $mob = window.jQuery('#mobile-carousel');
          if ($mob.length) setActiveSlideWithinCarousel($mob, idx);
        }
      });

      // Initialize both carousels (Bootstrap)
      if (window.jQuery) {
        window.jQuery('#web-carousel').carousel(0);
        window.jQuery('#mobile-carousel').carousel(0);
      }
    });
  }

  document.addEventListener('DOMContentLoaded', init);
})();

