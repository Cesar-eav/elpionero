{{-- Scripts comunes de las páginas de lectura: compartir y marcar en la barra la sección visible --}}
<script>
// Compartir: menú nativo del teléfono o, si no existe, copia el enlace
(function () {
  document.querySelectorAll('[data-compartir]').forEach(function (b) {
    var texto = b.textContent;
    b.addEventListener('click', function () {
      var url = location.href.split('#')[0];
      if (navigator.share) {
        navigator.share({ title: document.title, url: url }).catch(function () {});
      } else if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function () {
          b.textContent = 'Enlace copiado';
          setTimeout(function () { b.textContent = texto; }, 2500);
        });
      }
    });
  });
})();

// Marca en la barra la sección visible (solo los enlaces internos)
(function () {
  var nav = document.querySelector('.topnav');
  var links = document.querySelectorAll('.topnav a[href^="#"]');
  if (!nav || !('IntersectionObserver' in window)) return;
  var obs = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (!e.isIntersecting) return;
      links.forEach(function (a) {
        var on = a.getAttribute('href') === '#' + e.target.id;
        a.setAttribute('aria-current', on ? 'true' : 'false');
        if (on && nav.scrollWidth > nav.clientWidth) {
          nav.scrollLeft = a.offsetLeft - (nav.clientWidth - a.offsetWidth) / 2 - nav.offsetLeft;
        }
      });
    });
  }, { rootMargin: '-45% 0px -50% 0px' });
  links.forEach(function (a) {
    var s = document.getElementById(a.getAttribute('href').slice(1));
    if (s) obs.observe(s);
  });
})();
</script>
