// Post-action redirect helper for user store/update confirmation screens
document.addEventListener('DOMContentLoaded', () => {
  const targetUrl = document.body?.dataset?.redirectUrl || null;
  const ms = parseInt(document.body?.dataset?.redirectDelay || '1200', 10);
  const urlMeta = document.getElementById('users-redirect-url');
  const delayMeta = document.getElementById('users-redirect-delay');

  const finalUrl = targetUrl || (urlMeta ? urlMeta.content : null);
  const finalDelay = Number.isFinite(ms) ? ms : (delayMeta ? parseInt(delayMeta.content || '1200', 10) : 1200);

  if (!finalUrl) return;
  setTimeout(() => { window.location.assign(finalUrl); }, finalDelay);
});
