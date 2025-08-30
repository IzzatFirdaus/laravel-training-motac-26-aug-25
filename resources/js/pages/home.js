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
            // If clicking on interactive child (links, buttons, inputs), let it handle the event
            const interactive = e.target.closest('a, button, input, select, textarea, [role="button"], [role="link"]');
            if (interactive && card.contains(interactive)) {
                return; // don't hijack inner controls
            }
            go();
        });

        card.addEventListener('keydown', function (e) {
            // Ignore key events when focus is on inner interactive elements
            const interactive = e.target.closest('a, button, input, select, textarea, [role="button"], [role="link"]');
            if (interactive && card.contains(interactive)) {
                return;
            }
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                go();
            }
        });
    });
});
