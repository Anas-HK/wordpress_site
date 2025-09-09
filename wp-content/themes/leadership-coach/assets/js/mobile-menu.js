// Close mobile hamburger menu when clicking outside the menu content
(function () {
  // Known body classes that indicate the mobile menu/modal is open (cover multiple themes)
  var OPEN_BODY_CLASSES = [
    'showing-main-menu-modal',
    'showing-menu-modal',
    'menu-modal-open',
    'nav-open'
  ];

  // Try to close via the theme's own toggle first; fallback to class removal
  function closeMobileMenu() {
    var body = document.body;
    // Click an available close/toggle control if present and visible
    var toggle = document.querySelector('.close-main-nav-toggle, .header-toggle, .menu-toggle, .main-nav-toggle, .nav-toggle');
    if (toggle && (toggle.offsetParent !== null || window.getComputedStyle(toggle).display !== 'none')) {
      toggle.click();
      return;
    }
    // Fallback: remove body open classes and modal open state
    OPEN_BODY_CLASSES.forEach(function (cls) { body.classList.remove(cls); });
    var modal = document.querySelector('.main-menu-modal, .menu-modal');
    if (modal) modal.classList.remove('is-open');
  }

  function isVisible(el) {
    if (!el) return false;
    var style = window.getComputedStyle(el);
    if (style.display === 'none' || style.visibility === 'hidden' || style.opacity === '0') return false;
    // offsetParent is null for fixed-position elements; client rects catch those
    return el.offsetParent !== null || el.getClientRects().length > 0;
  }

  function isMenuOpen() {
    var body = document.body;
    if (OPEN_BODY_CLASSES.some(function (cls) { return body.classList.contains(cls); })) return true;
    // Check toggles with aria-expanded
    var toggle = document.querySelector('.close-main-nav-toggle, .header-toggle, .menu-toggle, .main-nav-toggle, .nav-toggle');
    if (toggle && (toggle.getAttribute('aria-expanded') === 'true' || toggle.classList.contains('is-active'))) return true;
    // Check visible modal/panel
    var refs = getModalAndPanel();
    if (isVisible(refs.modal) || isVisible(refs.panel)) return true;
    return false;
  }

  function getModalAndPanel() {
    var modal = document.querySelector('.main-menu-modal, .menu-modal');
    // Derive a likely inner panel to test containment against
    var panel = null;
    if (modal) {
      panel =
        modal.querySelector('.mobile-menu') ||
        modal.querySelector('.mbl-header-inner') ||
        modal.querySelector('.menu-wrapper') ||
        modal.querySelector('.modal-menu') ||
        modal;
    }
    // Fallback to common panel selectors even if modal is not found
    if (!panel) {
      panel = document.querySelector('.mobile-menu, .mbl-header-inner, .menu-wrapper, .modal-menu');
    }
  return { modal: modal, panel: panel };
  }

  function initOutsideClickToClose() {
    // Re-entrancy guard to avoid double fire (e.g., pointerdown + click)
    var lastHandled = 0;

    function handler(e) {
      var now = Date.now();
      if (now - lastHandled < 250) return; // throttle
      if (!isMenuOpen()) return;

      // Ignore right/middle mouse buttons; allow touch and pen
      if (e.pointerType === 'mouse' && typeof e.button === 'number' && e.button !== 0) return;

      // Ignore clicks on known toggles/close buttons
      if (e.target.closest('.toggle-btn, .close-main-nav-toggle, .header-toggle, .menu-toggle, .main-nav-toggle, .nav-toggle')) return;

      var refs = getModalAndPanel();
      var modal = refs.modal;
      var panel = refs.panel;
      if (!modal) return; // No modal present; nothing to do

  var clickedInsidePanel = panel ? panel.contains(e.target) : false;
  var clickedInsideModal = modal.contains(e.target);
  // Close if click is outside the inner panel (overlay area) OR completely outside modal
  if (!clickedInsidePanel || !clickedInsideModal) {
        lastHandled = now;
        closeMobileMenu();
      }
    }

    // Prefer a single pointerdown handler to avoid duplicate events; fallback to touchstart if needed
    if (window.PointerEvent) {
      document.addEventListener('pointerdown', handler, true);
    } else {
      document.addEventListener('touchstart', handler, true);
    }

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
      if (!isMenuOpen()) return;
      if (e.key === 'Escape' || e.key === 'Esc') closeMobileMenu();
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initOutsideClickToClose);
  } else {
    initOutsideClickToClose();
  }
})();
