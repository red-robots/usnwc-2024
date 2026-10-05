/* Mobile: auto-cycle the sponsor logo row (it is a swipeable scroll-snap row at 768px and below) */
(function(){
  var mobile = window.matchMedia('(max-width: 768px)');
  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
  var DELAY = 3000;

  document.querySelectorAll('.sponsor-images .flexwrap').forEach(function(row){
    var logos = row.children;
    if (logos.length < 2) return;
    var visible = true, holdUntil = 0;

    function offsetFor(logo) {
      var r = row.getBoundingClientRect(), l = logo.getBoundingClientRect();
      return row.scrollLeft + (l.left - r.left) - (r.width - l.width) / 2;
    }
    function current() {
      var best = 0, dist = Infinity;
      for (var i = 0; i < logos.length; i++) {
        var d = Math.abs(offsetFor(logos[i]) - row.scrollLeft);
        if (d < dist) { dist = d; best = i; }
      }
      return best;
    }
    function hold() { holdUntil = Date.now() + DELAY * 2; }

    row.addEventListener('touchstart', hold, {passive: true});
    row.addEventListener('touchmove', hold, {passive: true});
    row.addEventListener('touchend', hold, {passive: true});
    row.addEventListener('focusin', hold);

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function(entries){
        visible = entries[0].isIntersecting;
      }, {threshold: 0.5}).observe(row);
    }

    setInterval(function(){
      if (!mobile.matches || reduced.matches || !visible || document.hidden) return;
      if (Date.now() < holdUntil || row.scrollWidth <= row.clientWidth) return;
      var next = (current() + 1) % logos.length;
      row.scrollTo({left: offsetFor(logos[next]), behavior: 'smooth'});
    }, DELAY);
  });
})();
