// resources/js/pages/home.js
// Enhance .myds-card--clickable cards to behave like links (keyboard + pointer)
// Progressive enhancement: cards remain non-interactive without JS.
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.myds-card--clickable').forEach(function (card) {
        // ensure ARIA role and focusability
        if (!card.hasAttribute('role')) card.setAttribute('role', 'link');
        if (!card.hasAttribute('tabindex')) card.setAttribute('tabindex', '0');

        const href = card.dataset.href;
        if (!href) return;

        const go = function () { window.location.href = href; };

        card.addEventListener('click', function (e) {
            // allow inside anchors or buttons to behave normally
            const target = e.target.closest('a, button, [role="link"]');
            if (target && target.closest('.myds-card--clickable') === card && target.tagName.toLowerCase() !== 'a') {
                return;
            }
            go();
        });

        card.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                go();
            }
        });
    });
});
