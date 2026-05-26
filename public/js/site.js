// ===== Herbal Roots storefront JS =====
document.addEventListener('DOMContentLoaded', () => {
  // Scroll reveal
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(el => io.observe(el));

  // Sticky header shadow
  const header = document.querySelector('header.site');
  if (header) window.addEventListener('scroll', () => header.classList.toggle('scrolled', window.scrollY > 20));

  // Mobile menu
  const toggle = document.querySelector('.menu-toggle');
  if (toggle) toggle.addEventListener('click', () => document.querySelector('.nav-links').classList.toggle('show'));

  // Cart drawer open/close
  const drawer = document.getElementById('cartDrawer');
  const overlay = document.getElementById('drawerOverlay');
  const openCart = () => { loadDrawer(); drawer.classList.add('open'); overlay.classList.add('open'); };
  const closeCart = () => { drawer.classList.remove('open'); overlay.classList.remove('open'); };
  document.querySelectorAll('[data-open-cart]').forEach(b => b.addEventListener('click', e => { e.preventDefault(); openCart(); }));
  if (overlay) overlay.addEventListener('click', closeCart);
  document.querySelectorAll('[data-close-cart]').forEach(b => b.addEventListener('click', closeCart));

  // Quantity steppers
  document.querySelectorAll('.qty').forEach(q => {
    const input = q.querySelector('input');
    q.querySelector('[data-dec]')?.addEventListener('click', () => { input.value = Math.max(1, parseInt(input.value || 1) - 1); input.dispatchEvent(new Event('change')); });
    q.querySelector('[data-inc]')?.addEventListener('click', () => { input.value = parseInt(input.value || 1) + 1; input.dispatchEvent(new Event('change')); });
  });

  // AJAX add to cart
  document.querySelectorAll('form.ajax-add').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = form.querySelector('button[type=submit]');
      const label = btn.innerHTML;
      btn.disabled = true; btn.innerHTML = 'Adding…';
      try {
        const res = await fetch(form.action, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': window.CSRF, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: new FormData(form),
        });
        const data = await res.json();
        updateCount(data.count);
        toast(data.message || 'Added to cart');
        openCart();
      } catch (err) { toast('Something went wrong'); }
      finally { btn.disabled = false; btn.innerHTML = label; }
    });
  });
});

function updateCount(n) {
  document.querySelectorAll('.cart-badge').forEach(b => { b.textContent = n; b.style.display = n > 0 ? 'grid' : 'none'; });
}

async function loadDrawer() {
  const body = document.getElementById('drawerBody');
  const foot = document.getElementById('drawerFoot');
  body.innerHTML = '<p class="muted" style="text-align:center;padding:2rem">Loading…</p>';
  const res = await fetch('/cart?partial=1', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
  const html = await res.text();
  const doc = new DOMParser().parseFromString(html, 'text/html');
  const lines = doc.getElementById('drawerLines');
  const f = doc.getElementById('drawerFootInner');
  body.innerHTML = lines ? lines.innerHTML : '<div class="empty-cart"><div class="e">🛒</div><p>Your cart is empty</p></div>';
  foot.innerHTML = f ? f.innerHTML : '';
  bindDrawerRemove();
}

function bindDrawerRemove() {
  document.querySelectorAll('#drawerBody [data-remove]').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.dataset.remove;
      await fetch(`/cart/remove/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': window.CSRF } });
      const c = await (await fetch('/cart/count')).json();
      updateCount(c.count);
      loadDrawer();
    });
  });
}

let toastTimer;
function toast(msg) {
  let t = document.getElementById('toast');
  t.innerHTML = '<span>✅</span>' + msg;
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 2800);
}
