// Minimal admin JS helpers
document.addEventListener('DOMContentLoaded', function(){
  // Simple collapse for navbar toggler
  document.querySelectorAll('.navbar-toggler').forEach(function(btn){
    btn.addEventListener('click', function(){
      var target = document.querySelector(btn.dataset.bsTarget || '#navMain');
      if(!target) return;
      target.classList.toggle('show');
    });
  });
});
