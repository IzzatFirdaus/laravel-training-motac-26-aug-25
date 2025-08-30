// resources/js/pages/home.js
// Enhances clickable cards to behave like accessible links:
// - Adds role="link" and tabindex="0" if absent
// - Ensures keyboard (Enter/Space) triggers navigation
// - Does not override interactions for inner interactive controls

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.myds-card--clickable, [data-href]').forEach(function (card) {
        if (!card.hasAttribute('role')) card.setAttribute('role', 'link');
        if (!card.hasAttribute('tabindex')) card.setAttribute('tabindex', '0');

        const href = card.dataset.href || card.getAttribute('href') || card.getAttribute('data-href');
        if (!href) return;

        function go() {
            // Respect target attribute if set
            const target = card.getAttribute('data-target') || null;
            if (target === '_blank') window.open(href, '_blank', 'noopener');
            else window.location.href = href;
        }

        card.addEventListener('click', function (e) {
            const interactive = e.target.closest('a, button, input, select, textarea, [role="button"], [role="link"]');
            if (interactive && card.contains(interactive)) return;
            go();
        });

        card.addEventListener('keydown', function (e) {
            const interactive = e.target.closest('a, button, input, select, textarea, [role="button"], [role="link"]');
            if (interactive && card.contains(interactive)) return;
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                go();
            }
        });
    });
});
