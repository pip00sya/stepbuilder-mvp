<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function($){
    const chatContainer = document.querySelector('.mwai-conversation');
    if (!chatContainer) return;

    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === 1) { // элемент
                    // Ищем текст ответа ИИ внутри mwai-reply.mwai-ai
                    if(node.classList.contains('mwai-reply') && node.classList.contains('mwai-ai')){
                        const textSpan = node.querySelector('.mwai-text span');
                        if(!textSpan) return;
                        const text = textSpan.textContent.trim();
                        try {
                            const cmd = JSON.parse(text);
                            if(cmd.action === 'update_field' && cmd.field && cmd.value){
                                $.post(aiEngineAjax.ajaxurl, {
                                    action: 'ai_update_user_field',
                                    field: cmd.field,
                                    value: cmd.value
                                }).done(function(response){
                                    if(response.success){
                                        alert(`Поле "${cmd.field}" обновлено ассистентом.`);
                                    } else {
                                        alert('Ошибка обновления поля: ' + response.data);
                                    }
                                });
                            }
                        } catch(e){
                            // Ответ не JSON — игнорируем
                        }
                    }
                }
            });
        });
    });

    observer.observe(chatContainer, {childList: true});
});</script>
<!-- end Simple Custom CSS and JS -->
