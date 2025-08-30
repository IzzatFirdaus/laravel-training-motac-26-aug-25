// resources/js/pages/welcome.js
// Accessibility helpers for "welcome" or demo pages:
// - Enhances card-like interactive elements to be keyboard accessible
// - Does not modify behaviour if cards are not present

document.addEventListener('DOMContentLoaded', function () {
    const selectors = ['[role="button"]', '.card[href]', '.myds-card--clickable', '[data-href]'];
    const elements = document.querySelectorAll(selectors.join(','));
    elements.forEach(function (card) {
        if (!card.hasAttribute('tabindex')) card.setAttribute('tabindex', '0');
        // Keyboard triggers click to preserve existing handlers
        card.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                card.click();
            }
        });
    });
});
