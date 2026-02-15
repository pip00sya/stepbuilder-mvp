/**
 * ACF Autosave — StepBuilder
 * 
 * Automatically saves ACF (Advanced Custom Fields) form data
 * when the user leaves a field (blur) or changes a value (change).
 * No manual "Save" button click required — data is persisted via AJAX.
 * 
 * Active on: Page ID 280 (user workspace/profile page)
 * Dependencies: jQuery, ACF plugin
 * Original file: uploads/custom-css-js/304.js
 */

jQuery(document).ready(function ($) {
    // Only active on the workspace page (page_id=280)
    if (window.location.pathname.includes("/?page_id=280") || window.location.href.includes("page_id=280")) {
        $('.acf-field input, .acf-field textarea, .acf-field select').on('blur change', function () {
            let fieldName = $(this).attr('name');
            let fieldValue = $(this).val();

            $.post(ajaxurl, {
                action: 'autosave_acf_field',
                field: fieldName,
                value: fieldValue
            }, function (response) {
                console.log(response.data || response);
            });
        });
    }
});
