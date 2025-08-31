// Minimal admin JS helpers
document.addEventListener('DOMContentLoaded', function(){
  // Simple collapse for navbar toggler
  document.querySelectorAll('.navbar-toggler').forEach(function(btn){
    btn.addEventListener('click', function(){
      var target = document.querySelector(btn.dataset.bsTarget || '#navMain');
      if(!target) return;
      target.classList.toggle('show');
      var expanded = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', String(!expanded));
    });
  });

  // Initialise shared MYDS behaviours if available (idempotent)
  try {
    if (window.MYDS && typeof window.MYDS.initActionButtons === 'function') {
      window.MYDS.initActionButtons(document);
    }
    if (window.MYDS && typeof window.MYDS.wireClipboard === 'function') {
      window.MYDS.wireClipboard(document);
    }
    if (window.MYDS && typeof window.MYDS.wirePasswordToggle === 'function') {
      window.MYDS.wirePasswordToggle(document);
    }
  } catch (e) {
    // Non-fatal; keep progressive enhancement tolerant
    // eslint-disable-next-line no-console
    console.warn('MYDS bootstrapping warning', e && e.message ? e.message : e);
  }
});
