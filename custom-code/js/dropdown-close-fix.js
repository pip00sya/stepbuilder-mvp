/**
 * Dropdown Close Fix — StepBuilder
 * 
 * Fixes Ultimate Member dropdown menus that don't close
 * when the user clicks outside of them. Also adds Escape key support.
 * 
 * Dependencies: None (vanilla JS)
 * Original file: uploads/custom-css-js/687.js
 */

(function () {
    // Selectors for toggle buttons that open dropdown menus
    const TOGGLE_SELECTORS = [
        '.um-dropdown-toggle',
        '[data-um-dropdown-toggle]',
        '[aria-haspopup="menu"]'
    ];

    const isToggle = (el) => TOGGLE_SELECTORS.some(sel => el.closest(sel));

    const closeAllDropdowns = () => {
        document.querySelectorAll('.um-dropdown').forEach(dd => {
            dd.classList.remove('active', 'open', 'is-open', 'show');
            dd.style.display = 'none';
            dd.setAttribute('aria-hidden', 'true');
        });
    };

    // Close dropdowns on click outside (capture phase)
    document.addEventListener('click', function (e) {
        if (e.target.closest('.um-dropdown')) return;  // inside menu
        if (isToggle(e.target)) return;                // toggle button
        closeAllDropdowns();
    }, true);

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAllDropdowns();
    });
})();
