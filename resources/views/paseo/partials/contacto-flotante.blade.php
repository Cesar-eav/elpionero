{{-- Botón flotante + formulario "Únete / pide información": el envío llega por Telegram --}}
<style>
.cf-fab {
  position: fixed; z-index: 50; right: max(16px, env(safe-area-inset-right, 0px)); bottom: max(16px, env(safe-area-inset-bottom, 0px));
  display: inline-flex; align-items: center; gap: 8px; padding: 12px 18px; border: 0; border-radius: 999px; cursor: pointer;
  font: 700 1rem var(--f-body); background: var(--yellow); color: var(--on-yellow); box-shadow: 0 6px 20px rgba(4, 20, 38, .35);
}
.cf-fab:hover { filter: brightness(.95); }
.cf-dlg {
  width: min(480px, calc(100% - 24px)); max-height: calc(100dvh - 24px); overflow: auto; padding: 0;
  border: 1px solid var(--line); border-radius: 14px; background: var(--card-solid); color: var(--ink);
}
.cf-dlg::backdrop { background: rgba(4, 20, 38, .6); }
.cf-dlg form { padding: clamp(18px, 4vw, 26px); display: grid; gap: 14px; }
.cf-dlg h2 { font: 800 1.5rem/1.1 var(--f-display); margin: 0; }
.cf-dlg .cf-sub { margin: 0; color: var(--ink-2); font-size: .96rem; }
.cf-dlg label, .cf-dlg legend { font-weight: 600; font-size: .95rem; }
.cf-dlg fieldset { border: 0; padding: 0; margin: 0; display: grid; gap: 6px; }
.cf-dlg .cf-opt { display: flex; align-items: center; gap: 8px; font-weight: 400; }
.cf-dlg input[type=text], .cf-dlg input[type=email], .cf-dlg input[type=tel], .cf-dlg textarea {
  width: 100%; margin-top: 4px; padding: 10px 12px; font: inherit; color: var(--ink);
  background: var(--card-solid); border: 1px solid var(--line); border-radius: 8px;
}
.cf-dlg .cf-hint { font-size: .86rem; color: var(--ink-2); margin: 0; }
.cf-dlg .cf-err { color: var(--rust); font-size: .88rem; margin: 2px 0 0; }
.cf-dlg .cf-hp { position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden; }
.cf-dlg .cf-row { display: flex; gap: 10px; flex-wrap: wrap; }
.cf-dlg .cf-send, .cf-dlg .cf-cancel { padding: 10px 18px; border-radius: 10px; font: 700 1rem var(--f-body); cursor: pointer; }
.cf-dlg .cf-send { border: 0; background: var(--btn-bg); color: var(--btn-fg); }
.cf-dlg .cf-send[disabled] { opacity: .6; cursor: wait; }
.cf-dlg .cf-cancel { border: 1px solid var(--line); background: none; color: var(--ink); }
.cf-dlg .cf-ok { padding: clamp(24px, 5vw, 36px); text-align: center; display: grid; gap: 14px; }
</style>

<button type="button" class="cf-fab" id="cf-abrir" aria-haspopup="dialog">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 5h16v11H9l-5 4z" stroke-linejoin="round"/></svg>
  Únete / Pide información
</button>

<dialog class="cf-dlg" id="cf-dialogo" aria-labelledby="cf-titulo">
  <form id="cf-form" method="post" action="{{ route('paseo.contacto') }}" novalidate>
    @csrf
    <h2 id="cf-titulo">Únete o pide información</h2>
    <p class="cf-sub">Cuéntanos cómo contactarte y te escribimos.</p>

    <fieldset>
      <legend>¿Qué necesitas?</legend>
      <label class="cf-opt"><input type="radio" name="motivo" value="unirme" checked> Quiero unirme</label>
      <label class="cf-opt"><input type="radio" name="motivo" value="informacion"> Quiero más información</label>
    </fieldset>

    <div>
      <label for="cf-nombre">Nombre</label>
      <input type="text" id="cf-nombre" name="nombre" maxlength="100" autocomplete="name" required>
      <p class="cf-err" data-err="nombre" hidden></p>
    </div>

    <div>
      <label for="cf-whatsapp">WhatsApp</label>
      <input type="tel" id="cf-whatsapp" name="whatsapp" maxlength="30" autocomplete="tel" placeholder="+56 9 1234 5678">
      <p class="cf-err" data-err="whatsapp" hidden></p>
    </div>

    <div>
      <label for="cf-correo">Correo</label>
      <input type="email" id="cf-correo" name="correo" maxlength="150" autocomplete="email">
      <p class="cf-err" data-err="correo" hidden></p>
    </div>
    <p class="cf-hint">Indica al menos uno: WhatsApp o correo.</p>

    <div>
      <label for="cf-mensaje">Mensaje (opcional)</label>
      <textarea id="cf-mensaje" name="mensaje" rows="3" maxlength="1000"></textarea>
    </div>

    <div class="cf-hp" aria-hidden="true"><label>No llenar <input type="text" name="web" tabindex="-1" autocomplete="off"></label></div>

    <p class="cf-err" id="cf-error" role="alert" hidden></p>
    <div class="cf-row">
      <button type="submit" class="cf-send">Enviar</button>
      <button type="button" class="cf-cancel" id="cf-cerrar">Cancelar</button>
    </div>
  </form>

  <div class="cf-ok" id="cf-listo" hidden>
    <h2>¡Gracias!</h2>
    <p>Recibimos tu mensaje y te contactaremos pronto.</p>
    <div><button type="button" class="cf-send" id="cf-listo-cerrar">Cerrar</button></div>
  </div>
</dialog>

<script>
(function () {
  var dlg = document.getElementById('cf-dialogo');
  var form = document.getElementById('cf-form');
  var listo = document.getElementById('cf-listo');
  var errGen = document.getElementById('cf-error');
  var enviar = form.querySelector('.cf-send');
  if (!dlg || typeof dlg.showModal !== 'function') return;

  function abrir() {
    form.hidden = false; listo.hidden = true; limpiar();
    dlg.showModal();
    document.getElementById('cf-nombre').focus();
  }
  function cerrar() { dlg.close(); }
  function limpiar() {
    errGen.hidden = true;
    form.querySelectorAll('[data-err]').forEach(function (p) { p.hidden = true; p.textContent = ''; });
  }
  function mostrar(campo, msg) {
    var p = form.querySelector('[data-err="' + campo + '"]');
    if (p) { p.textContent = msg; p.hidden = false; } else { errGen.textContent = msg; errGen.hidden = false; }
  }

  document.querySelectorAll('#cf-abrir, [data-abrir-contacto]').forEach(function (b) { b.addEventListener('click', abrir); });
  document.getElementById('cf-cerrar').addEventListener('click', cerrar);
  document.getElementById('cf-listo-cerrar').addEventListener('click', cerrar);
  dlg.addEventListener('click', function (e) { if (e.target === dlg) cerrar(); });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    limpiar();
    var f = new FormData(form);
    if (!f.get('nombre').trim()) { mostrar('nombre', 'Escribe tu nombre.'); return; }
    if (!f.get('whatsapp').trim() && !f.get('correo').trim()) { mostrar('correo', 'Indica un WhatsApp o un correo.'); return; }
    enviar.disabled = true;
    fetch(form.action, { method: 'POST', body: f, headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function (r) { return r.json().catch(function () { return {}; }).then(function (j) { return { s: r.status, j: j }; }); })
      .then(function (r) {
        if (r.s >= 200 && r.s < 300) { form.reset(); form.hidden = true; listo.hidden = false; return; }
        if (r.s === 422 && r.j.errors) {
          Object.keys(r.j.errors).forEach(function (k) { mostrar(k, r.j.errors[k][0]); });
        } else if (r.s === 429) {
          mostrar('', 'Demasiados intentos. Espera unos minutos e inténtalo de nuevo.');
        } else {
          mostrar('', r.j.message || 'No pudimos enviar tu mensaje. Inténtalo de nuevo.');
        }
      })
      .catch(function () { mostrar('', 'Sin conexión. Revisa tu internet e inténtalo de nuevo.'); })
      .finally(function () { enviar.disabled = false; });
  });
})();
</script>
