/**
 * AI Chatbot Handler — StepBuilder
 * 
 * Monitors AI Engine (MWAI) chatbot responses in real-time.
 * When the AI returns a JSON command like:
 *   { "action": "update_field", "field": "user_gpa", "value": "3.8" }
 * 
 * ...the script automatically sends an AJAX request to update
 * the user's profile field without page reload.
 * 
 * Dependencies: jQuery, AI Engine plugin (mwai)
 * Original file: uploads/custom-css-js/489.js
 */

jQuery(document).ready(function ($) {
    const chatContainer = document.querySelector('.mwai-conversation');
    if (!chatContainer) return;

    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === 1) {
                    // Look for AI reply elements
                    if (node.classList.contains('mwai-reply') && node.classList.contains('mwai-ai')) {
                        const textSpan = node.querySelector('.mwai-text span');
                        if (!textSpan) return;

                        const text = textSpan.textContent.trim();
                        try {
                            const cmd = JSON.parse(text);
                            if (cmd.action === 'update_field' && cmd.field && cmd.value) {
                                $.post(aiEngineAjax.ajaxurl, {
                                    action: 'ai_update_user_field',
                                    field: cmd.field,
                                    value: cmd.value
                                }).done(function (response) {
                                    if (response.success) {
                                        alert(`Field "${cmd.field}" updated by assistant.`);
                                    } else {
                                        alert('Error updating field: ' + response.data);
                                    }
                                });
                            }
                        } catch (e) {
                            // Response is not JSON — ignore (normal text reply)
                        }
                    }
                }
            });
        });
    });

    observer.observe(chatContainer, { childList: true });
});
