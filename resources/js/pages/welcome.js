// resources/js/pages/welcome.js
// Enhance accessibility with keyboard navigation for interactive card-like elements.
// Matches previous inline behavior: selects elements with role="button" or .card[href]
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('[role="button"], .card[href]');
    cards.forEach(function(card) {
        if (!card.getAttribute('tabindex')) {
            card.setAttribute('tabindex', '0');
        }

        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                // Trigger the card's click handler to preserve behavior
                card.click();
            }
        });
    });
});
