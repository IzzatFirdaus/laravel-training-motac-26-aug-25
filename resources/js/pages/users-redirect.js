// Post-action redirect helper for user store/update confirmation screens
document.addEventListener('DOMContentLoaded', () => {
  // Idempotent: avoid scheduling multiple redirects if script runs more than once
  if (document.body && document.body.dataset.mydsRedirectScheduled === '1') return;

  const targetUrl = document.body?.dataset?.redirectUrl || null;
  const ms = parseInt(document.body?.dataset?.redirectDelay || '1200', 10);
  const urlMeta = document.getElementById('users-redirect-url');
  const delayMeta = document.getElementById('users-redirect-delay');

  const finalUrl = targetUrl || (urlMeta ? urlMeta.content : null);
  const finalDelay = Number.isFinite(ms) ? ms : (delayMeta ? parseInt(delayMeta.content || '1200', 10) : 1200);

  if (!finalUrl) return;
  if (document.body) document.body.dataset.mydsRedirectScheduled = '1';
  setTimeout(() => { window.location.assign(finalUrl); }, finalDelay);
});
