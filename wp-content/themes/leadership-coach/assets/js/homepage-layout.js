(function(){
  'use strict';

  function pickRandomTwo(arr){
    if(arr.length <= 2) return arr;
    const copy = arr.slice();
    const selected = [];
    for(let i=0;i<2;i++){
      const idx = Math.floor(Math.random() * copy.length);
      selected.push(copy[idx]);
      copy.splice(idx,1);
    }
    return selected;
  }

  function updateEmbracedPlaceholders(){
    const placeholders = Array.from(document.querySelectorAll('.embraced-left .embraced-image-placeholder'));
    if(placeholders.length === 0) return;

    if(window.innerWidth <= 750){
      // Hide all by default on compact, then show two random ones
      placeholders.forEach(el => el.classList.add('hide-mobile-compact'));
      pickRandomTwo(placeholders).forEach(el => el.classList.remove('hide-mobile-compact'));
    } else {
      // Show all on wider screens
      placeholders.forEach(el => el.classList.remove('hide-mobile-compact'));
    }
  }

  function onReady(fn){
    if(document.readyState === 'loading'){
      document.addEventListener('DOMContentLoaded', fn);
    } else { fn(); }
  }

  onReady(updateEmbracedPlaceholders);
  window.addEventListener('resize', function(){
    // Throttle resize updates
    clearTimeout(window.__embracedResizeTimer);
    window.__embracedResizeTimer = setTimeout(updateEmbracedPlaceholders, 120);
  });
})();

