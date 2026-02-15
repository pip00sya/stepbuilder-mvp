/**
 * Close Button Redirect — StepBuilder
 * 
 * Adds click handler to the "×" close buttons on login/register pages
 * (page IDs 227, 228). Clicking redirects the user to the home page.
 * 
 * Dependencies: None (vanilla JS)
 * Original file: uploads/custom-css-js/621.js
 */

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(
        '.page-id-227 .wp-block-group.is-horizontal.is-content-justification-right, ' +
        '.page-id-228 .wp-block-group.is-horizontal.is-content-justification-right'
    ).forEach(function (el) {
        el.addEventListener('click', function () {
            window.location.href = '/';
        });
    });
});
