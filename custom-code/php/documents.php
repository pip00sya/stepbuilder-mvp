<?php
add_action('init', function () {
    add_rewrite_rule('^documents/([^/]+)/?$', 'index.php?pagename=documents&doc_id=$matches[1]', 'top');
});
add_filter('query_vars', function ($vars) {
    $vars[] = 'doc_id';
    return $vars;
});

function generate_doc_id()
{
    return rtrim(strtr(base64_encode(random_bytes(16)), '+/', '-_'), '=');
}

/* -------------------- ПОРТФОЛИО -------------------- */
add_action('wp_ajax_nopriv_get_saved_portfolio', function () {
    $key = sanitize_text_field($_POST['portfolio_key']);
    $all_portfolios = get_option('all_portfolios', []);
    $owner_id = $all_portfolios[$key]['owner_id'] ?? 0;
    $is_public = $all_portfolios[$key]['is_public'] ?? false;

    if ($is_public) {
        $all = get_user_meta($owner_id, 'saved_portfolios', true);
        $all = is_array($all) ? $all : [];
        if (isset($all[$key])) {
            wp_send_json_success(['content' => $all[$key]]);
        }
        else {
            wp_send_json_error('Not found');
        }
    }
    else {
        wp_send_json_error('Not allowed');
    }
});

add_action('wp_ajax_get_saved_portfolio', function () {
    $user_id = get_current_user_id();
    $key = sanitize_text_field($_POST['portfolio_key']);
    $all_portfolios = get_option('all_portfolios', []);
    $owner_id = $all_portfolios[$key]['owner_id'] ?? 0;
    $is_public = $all_portfolios[$key]['is_public'] ?? false;

    if ($owner_id === $user_id || $is_public) {
        $all = get_user_meta($owner_id, 'saved_portfolios', true);
        $all = is_array($all) ? $all : [];
        if (isset($all[$key])) {
            wp_send_json_success(['content' => $all[$key]]);
        }
        else {
            wp_send_json_error('Not found');
        }
    }
    else {
        wp_send_json_error('Not allowed');
    }
});

add_action('wp_ajax_save_portfolio', function () {
    $user_id = get_current_user_id();
    $key = sanitize_text_field($_POST['portfolio_key']);
    $content = wp_kses_post($_POST['content']);
    $all = get_user_meta($user_id, 'saved_portfolios', true);
    $all = is_array($all) ? $all : [];
    $all[$key] = $content;
    update_user_meta($user_id, 'saved_portfolios', $all);

    $all_portfolios = get_option('all_portfolios', []);
    if (!isset($all_portfolios[$key])) {
        $all_portfolios[$key] = [
            'owner_id' => $user_id,
            'is_public' => false,
        ];
    }
    update_option('all_portfolios', $all_portfolios);

    wp_send_json_success();
});

add_action('wp_ajax_get_saved_portfolios', function () {
    $user_id = get_current_user_id();
    $all = get_user_meta($user_id, 'saved_portfolios', true);
    $all = is_array($all) ? $all : [];
    wp_send_json_success(['portfolios' => $all]);
});

add_action('wp_ajax_delete_portfolio', function () {
    $user_id = get_current_user_id();
    $key = sanitize_text_field($_POST['portfolio_key']);
    $all = get_user_meta($user_id, 'saved_portfolios', true);
    $all = is_array($all) ? $all : [];
    if (isset($all[$key])) {
        unset($all[$key]);
        update_user_meta($user_id, 'saved_portfolios', $all);
        wp_send_json_success();
    }
    else {
        wp_send_json_error('Not found');
    }
});

add_action('wp_ajax_make_portfolio_public', function () {
    $user_id = get_current_user_id();
    $key = sanitize_text_field($_POST['portfolio_key']);
    $all = get_option('all_portfolios', []);
    if (isset($all[$key]) && $all[$key]['owner_id'] == $user_id) {
        $all[$key]['is_public'] = true;
        update_option('all_portfolios', $all);
        wp_send_json_success();
    }
    else {
        wp_send_json_error('Not found or not allowed');
    }
});

/* -------------------- МОТИВАЦИОННЫЕ ЭССЕ -------------------- */
add_action('wp_ajax_nopriv_get_saved_essay', function () {
    $key = sanitize_text_field($_POST['essay_key']);
    $all_essays = get_option('all_essays', []);
    $owner_id = $all_essays[$key]['owner_id'] ?? 0;
    $is_public = $all_essays[$key]['is_public'] ?? false;

    if ($is_public) {
        $all = get_user_meta($owner_id, 'saved_essays', true);
        $all = is_array($all) ? $all : [];
        if (isset($all[$key])) {
            wp_send_json_success(['content' => $all[$key]]);
        }
        else {
            wp_send_json_error('Not found');
        }
    }
    else {
        wp_send_json_error('Not allowed');
    }
});

add_action('wp_ajax_get_saved_essay', function () {
    $user_id = get_current_user_id();
    $key = sanitize_text_field($_POST['essay_key']);
    $all_essays = get_option('all_essays', []);
    $owner_id = $all_essays[$key]['owner_id'] ?? 0;
    $is_public = $all_essays[$key]['is_public'] ?? false;

    if ($owner_id === $user_id || $is_public) {
        $all = get_user_meta($owner_id, 'saved_essays', true);
        $all = is_array($all) ? $all : [];
        if (isset($all[$key])) {
            wp_send_json_success(['content' => $all[$key]]);
        }
        else {
            wp_send_json_error('Not found');
        }
    }
    else {
        wp_send_json_error('Not allowed');
    }
});

add_action('wp_ajax_save_essay', function () {
    $user_id = get_current_user_id();
    $key = sanitize_text_field($_POST['essay_key']);
    $content = wp_kses_post($_POST['content']);
    $all = get_user_meta($user_id, 'saved_essays', true);
    $all = is_array($all) ? $all : [];
    $all[$key] = $content;
    update_user_meta($user_id, 'saved_essays', $all);

    $all_essays = get_option('all_essays', []);
    if (!isset($all_essays[$key])) {
        $all_essays[$key] = [
            'owner_id' => $user_id,
            'is_public' => false,
        ];
    }
    update_option('all_essays', $all_essays);

    wp_send_json_success();
});

add_action('wp_ajax_get_saved_essays', function () {
    $user_id = get_current_user_id();
    $all = get_user_meta($user_id, 'saved_essays', true);
    $all = is_array($all) ? $all : [];
    wp_send_json_success(['essays' => $all]);
});

add_action('wp_ajax_delete_essay', function () {
    $user_id = get_current_user_id();
    $key = sanitize_text_field($_POST['essay_key']);
    $all = get_user_meta($user_id, 'saved_essays', true);
    $all = is_array($all) ? $all : [];
    if (isset($all[$key])) {
        unset($all[$key]);
        update_user_meta($user_id, 'saved_essays', $all);
        wp_send_json_success();
    }
    else {
        wp_send_json_error('Not found');
    }
});

add_action('wp_ajax_make_essay_public', function () {
    $user_id = get_current_user_id();
    $key = sanitize_text_field($_POST['essay_key']);
    $all = get_option('all_essays', []);
    if (isset($all[$key]) && $all[$key]['owner_id'] == $user_id) {
        $all[$key]['is_public'] = true;
        update_option('all_essays', $all);
        wp_send_json_success();
    }
    else {
        wp_send_json_error('Not found or not allowed');
    }
});

/* -------------------- AI -------------------- */
add_action('wp_ajax_ai_assistant_gpt', 'ai_assistant_gpt_handler');
add_action('wp_ajax_nopriv_ai_assistant_gpt', 'ai_assistant_gpt_handler');
function ai_assistant_gpt_handler()
{
    $prompt = sanitize_text_field($_POST['prompt'] ?? '');
    $context = sanitize_textarea_field($_POST['context'] ?? '');
    $section_type = sanitize_text_field($_POST['section_type'] ?? '');

    $full_prompt = $prompt;
    if ($context)
        $full_prompt .= "\n\nКонтекст:\n" . $context;

    $api_key = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : '';
    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'Ты помощник для составления резюме и мотивационных эссе, отвечай кратко и по делу.'],
                ['role' => 'user', 'content' => $full_prompt],
            ],
            'max_tokens' => 400,
            'temperature' => 0.7,
        ]),
        'timeout' => 30,
    ]);
    if (is_wp_error($response)) {
        wp_send_json_error('Ошибка соединения');
    }
    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (!empty($body['choices'][0]['message']['content'])) {
        wp_send_json_success($body['choices'][0]['message']['content']);
    }
    else {
        wp_send_json_error('Нет ответа от AI');
    }
}

/* -------------------- SHORTCODE -------------------- */
add_shortcode('documents', function ($atts) {
    $doc_id = get_query_var('doc_id');

    // Определяем тип документа (portfolio или essay)
    $isEssay = false;
    if ($doc_id) {
        $all_essays = get_option('all_essays', []);
        if (isset($all_essays[$doc_id])) {
            $isEssay = true;
        }
    }

    ob_start();
?>
<style>
    .portfolio-editor-container {
        display: flex;
        font-family: 'Rubik', Arial, sans-serif;
        background: #fff;
        padding: 0;
        margin: 0;
    }
    .portfolio-sidebar h3 {
        margin: 18px 0 8px 0;
        font-size: 1.1em;
        color: #222;
    }
    .portfolio-sidebar button, .portfolio-sidebar select, .portfolio-sidebar input[type="color"] {
        margin: 6px 0;
        width: 100%;
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ddd;
        background: #f6f6f6;
        font-size: 1em;
		cursor: pointer;
    }
    .portfolio-sidebar .color-pick {
        display: flex;
        gap: 6px;
        margin-bottom: 10px;
		cursor: pointer;
    }
    .portfolio-sidebar .color-pick input[type="color"] {
        width: 32px; height: 32px; padding: 0; border: none;
    }
	.portfolio-sidebar input[type="range"] {
		cursor: pointer;
	}
.portfolio-main {
    flex: 1;
    height: 100vh;
    overflow: auto; /* Прокручивается только эта область */
    background: #f4f6fa;
    padding: 32px 0;
}
    .portfolio-resume {
        background: #fff;
		width: calc(210mm - (15mm * 2));
        margin: 0 auto;
		border-width: 1px;
		border-color: #b5b5b5;
		padding: 17mm 15mm;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        height: calc(293mm - (17mm * 2));
        transition: all 0.3s;
    	transition: zoom 0.2s ease;
}
    }
    .editable {
        cursor: pointer;
        border-bottom: 1px dashed #bbb;
        transition: all 0.5s;
    }
    .editable:focus {
        outline: none;
        background: #eaf6ff;
		padding: 5px;
    }
    .resume-section {
        margin-bottom: 25px;
    }
    .resume-section-title {
        font-weight: bold;
        font-size: 1.2em;
        border-bottom: 2px solid #222;
        margin-bottom: 10px;
		padding-left: 3px;
		padding-right: 3px;
    }
    .resume-columns {
        display: flex;
        gap: 50px;
    }
    .resume-col {
        flex: 1;
    }
    .resume-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #e0e0e0;
        margin: 11px auto 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        color: #bbb;
    }
    .toolbar {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
    }
    .toolbar button {
        background: #f0f0f0;
        border: none;
        border-radius: 4px;
        padding: 6px 10px;
        cursor: pointer;
        font-size: 1em;
    }
    .ai-assistant {
        background: #eaf6ff;
        border: 1px solid #b3e0ff;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 16px;
    }
    .template-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .template-item {
        width: 48%;
        border: 2px solid #eee;
        border-radius: 6px;
        cursor: pointer;
        padding: 8px;
        background: #fafafa;
        transition: border 0.2s;
    }
    .template-item.selected {
        border: 2px solid #0078d4;
        background: #eaf6ff;
    }
	.zoom{
    position: fixed;
    right: 20px;
    bottom: 20px;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 8px 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 8px;
    z-index: 9999;
    user-select: none;
    width: 260px;
    font-family: 'Rubik', Arial, sans-serif;
	}
	.template-list {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    justify-content: space-between;
}
.template-item {
    width: 48%;
    border: 2px solid #eee;
    border-radius: 8px;
    cursor: pointer;
    padding: 8px;
    background: #fafafa;
    transition: border 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px #f4f6fa;
}
.template-item.selected {
    border: 2px solid #0078d4;
    background: #eaf6ff;
    box-shadow: 0 4px 16px #cce6ff;
}
.template-item img {
    width: 100%;
    border-radius: 6px;
    display: block;
}
	.resume-section {
    position: relative;
}
.section-menu-btn {
    position: absolute;
    top: 14px;
    right: 8px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 20px;
    color: #bbb;
    opacity: 0;
    transition: opacity 0.2s;
    z-index: 2;
}
.resume-section:hover .section-menu-btn,
.resume-section.editing .section-menu-btn {
    opacity: 1;
}
.section-toolbar {
    position: absolute;
    top: 14px;
    right: 8px;
    background: #fff;
    border: 1px solid #e5eaf3;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    display: flex;
    flex-direction: column;
    gap: 2px;
    z-index: 10;
    min-width: 120px;
    padding: 4px 0;
}
.section-toolbar button {
    background: none;
    border: none;
    text-align: left;
    padding: 7px 16px;
    font-size: 15px;
    color: #28314f;
    cursor: pointer;
    transition: background 0.15s;
    border-radius: 0;
}
.section-toolbar button:hover {
    background: #f4f6fa;
}
	.editable-block {
    position: relative;
    padding-left: 5px;
	padding-right: 5px;
}
.editable-block-menu-btn {
    position: absolute;
    top: 2px;
	right: -10px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    color: #888;
    opacity: 0;
    transition: opacity 0.2s;
    user-select: none;
}
.editable-block:hover .editable-block-menu-btn {
    opacity: 1;
}
.editable-block-toolbar {
    position: absolute;
    top: 24px;
    right: 4px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 1000;
    display: flex;
    flex-direction: column;
    min-width: 140px;
}
.editable-block-toolbar button {
    padding: 6px 12px;
    border: none;
    background: none;
    cursor: pointer;
    text-align: left;
    font-size: 14px;
    color: #28314f;
    user-select: none;
}
.editable-block-toolbar button:hover {
    background: #f4f6fa;
}
.editable-block-toolbar button.delete {
    color: #c00;
}
.ai-popover {
    background: linear-gradient(120deg, #f8fbff 0%, #eaf6ff 100%);
    border: none;
    box-shadow: 0 8px 32px rgba(51,102,204,0.18), 0 1.5px 8px #b3e0ff;
    border-radius: 16px;
    min-width: 260px;
    max-width: 320px;
    padding: 22px 22px 14px 22px;
    font-family: 'Inter', 'Rubik', Arial, sans-serif;
    color: #222;
    transition: box-shadow 0.25s, transform 0.25s;
    animation: ai-popover-in 0.35s cubic-bezier(.4,2,.6,1);
}
.ai-popover h4 {
    margin: 0 0 8px 0;
    font-size: 1.1em;
    color: #0078d4;
}
.ai-popover .ai-input-row {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
}
.ai-popover input[type="text"] {
    flex: 1;
    padding: 7px 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 1em;
}

.ai-popover .ai-quick-prompts {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
    flex-wrap: wrap;
}
.ai-popover .ai-quick-prompts button {
    background: #eaf6ff;
    color: #0078d4;
    border: 1px solid #b3e0ff;
    font-size: 0.97em;
    padding: 5px 10px;
}
.ai-popover .ai-answer {
    background: #f4f6fa;
    border-radius: 6px;
    padding: 8px 10px;
    min-height: 30px;
    font-size: 0.98em;
    color: #28314f;
    margin-top: 6px;
    white-space: pre-line;
}
.ai-popover .ai-close-btn {
    position: absolute;
    top: 7px;
    right: 10px;
    background: none;
    border: none;
    font-size: 18px;
    color: #bbb;
    cursor: pointer;
}
	.ai-section-btn {
    position: absolute;
    top: 54px; /* под тремя точками */
    font-size: 18px;
    cursor: pointer;
    z-index: 1002;
    opacity: 0;
    transition: opacity 0.2s;
}
	.resume-section:hover .ai-section-btn {
    opacity: 1;
    pointer-events: auto;
}
	.section-menu-btn {
    position: absolute;
    top: 20px;
	right: -27px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 20px;
    color: #bbb;
    opacity: 0;
    transition: opacity 0.2s;
    z-index: 2;
}
	.ai-section-btn {
    transition: transform 0.3s cubic-bezier(.4,2,.6,1), opacity 0.2s;
}
.ai-section-btn.ai-animating {
    transform: scale(0.7) rotate(-15deg);
    opacity: 0;
}
.ai-popover {
    animation: ai-popover-in 0.35s cubic-bezier(.4,2,.6,1);
}
.ai-popover {
    background: linear-gradient(120deg, #f8fbff 0%, #eaf6ff 100%);
    border: none;
    box-shadow: 0 8px 32px rgba(51,102,204,0.18), 0 1.5px 8px #b3e0ff;
    border-radius: 16px;
    min-width: 260px;
    max-width: 320px;
    padding: 22px 22px 14px 22px;
    font-family: 'Inter', 'Rubik', Arial, sans-serif;
    color: #222;
    transition: box-shadow 0.25s, transform 0.25s;
    animation: ai-popover-in 0.35s cubic-bezier(.4,2,.6,1);
}
@keyframes ai-popover-in {
    0% { opacity: 0; transform: scale(0.85) translateX(30px);}
    100% { opacity: 1; transform: scale(1) translateX(0);}
}
.ai-popover h4 {
    margin: 0 0 10px 0;
    font-size: 1.08em;
    color: #0078d4;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.ai-popover .ai-input-row {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
}
.ai-popover input[type="text"] {
    flex: 1;
    padding: 7px 10px;
    border-radius: 8px;
    border: 1px solid #e5eaf3;
    font-size: 1em;
    background: #f7fafd;
    outline: none;
    transition: border 0.2s;
}
.ai-popover .ai-answer {
    background: #f4f6fa;
    border-radius: 8px;
    padding: 10px 12px;
    min-height: 30px;
    font-size: 0.98em;
    color: #28314f;
    margin-top: 6px;
    white-space: pre-line;
    box-shadow: 0 1px 4px #eaf6ff;
}
.ai-popover .ai-close-btn {
    position: absolute;
    top: 7px;
    right: 10px;
    background: none;
    border: none;
    font-size: 18px;
    color: #bbb;
    cursor: pointer;
    transition: color 0.2s;
}
.ai-popover .ai-close-btn:hover {
    color: #0078d4;
}
	.ai-popover .ai-input-row textarea.ai-prompt-input {
    width: 100%;
    font-size: 1em;
    border-radius: 8px;
    border: 1px solid #e5eaf3;
    background: #f7fafd;
    padding: 7px 10px;
    outline: none;
    transition: border 0.2s;
    min-height: 36px;
    max-height: 120px;
    resize: none;
    box-sizing: border-box;
}
.ai-popover {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(40,49,79,0.13);
    min-width: 260px;
    max-width: 290px;
    padding: 0 0 10px 0;
    font-family: 'Inter', Arial, sans-serif;
    color: #222;
    border: 1.5px solid #e5eaf3;
    transition: box-shadow 0.25s, transform 0.25s;
    animation: ai-popover-in 0.25s cubic-bezier(.4,2,.6,1);
}
@keyframes ai-popover-in {
    0% { opacity: 0; transform: scale(0.95);}
    100% { opacity: 1; transform: scale(1);}
}
.ai-popover-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 18px 8px 14px;
    border-bottom: 1px solid #f0f1f4;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1px;
    color: #28314f;
    position: relative;
}
.ai-popover-dot {
    width: 8px; height: 8px;
    background: #6c63ff;
    border-radius: 50%;
    display: inline-block;
}
.ai-popover-title {
    font-size: 13px;
    font-weight: 700;
    color: #28314f;
    letter-spacing: 1px;
    margin-left: 2px;
}
.ai-close-btn {
    position: absolute;
    right: 10px;
    top: 7px;
    background: none;
    border: none;
    font-size: 18px;
    color: #bbb;
    cursor: pointer;
    transition: color 0.2s;
}
.ai-close-btn:hover {
    color: #6c63ff;
}
.ai-popover-btns {
    display: flex;
    flex-direction: column;
    gap: 7px;
    padding: 14px 14px 0 14px;
}
.ai-suggestion-btn {
    border: none;;
    color: #6c63ff;
    font-weight: 600;
    text-align: left;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background 0.18s, color 0.18s;
	    background: #fff;
    border-radius: 8px;
    padding: 7px 5px;
    font-size: 15px;
}
.ai-suggestion-btn:hover, .ai-suggestion-btn:focus {
    background: #eaf0ff;
    color: #6c63ff;
}
.ai-popover-or {
    text-align: center;
    color: #bfc8e2;
    font-size: 13px;
    margin: 10px 0 7px 0;
    position: relative;
}
.ai-popover-or:before, .ai-popover-or:after {
    content: '';
    display: inline-block;
    width: 38%;
    height: 1px;
    background: #f0f1f4;
    vertical-align: middle;
    margin: 0 6px;
}
.ai-popover-input-row {
    padding: 0 14px;
}
.ai-prompt-input {
    width: 100%;
    font-size: 15px;
    border-radius: 7px;
    border: 1.5px solid #bfc8e2;
    background: #fff;
    padding: 10px 12px;
    outline: none;
    transition: border 0.18s;
    margin-bottom: 0;
    box-sizing: border-box;
    min-height: 38px;
    max-height: 120px;
    resize: none;
    overflow-y: auto;
}
.ai-prompt-input:focus {
    border-color: #6c63ff;
}
.ai-answer {
    background: #f7f8fa;
    border-radius: 7px;
    padding: 10px 12px;
    min-height: 28px;
    font-size: 14px;
    color: #28314f;
    margin: 10px 14px 0 14px;
    white-space: pre-line;
    box-shadow: 0 1px 4px #f4f6fa;
}
	.modern-sidebar {
  width: 230px;
  background: #fbfcfe;
  border-right: 1.5px solid #e5eaf3;
  padding: 20px 35px;
  display: flex;
  flex-direction: column;
  gap: 18px;
  font-family: 'Inter', 'Rubik', Arial, sans-serif;
  box-shadow: 2px 0 16px rgba(51,102,204,0.03);
}

.sidebar-section {
  margin-bottom: 10px;
}

.sidebar-title {
  font-size: 17px;
  font-weight: 700;
  color: #5f4dee;
  margin-bottom: 8px;
  letter-spacing: 1px;
  display: flex;
  align-items: center;
  gap: 7px;
}

.sidebar-row {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
}

.sidebar-row label {
  font-size: 16px;
  color: #bfc8e2;
  width: 22px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sidebar-row select,
.sidebar-row input[type="range"],
.sidebar-row input[type="color"] {
  border-radius: 6px;
  border: 1px solid #e5eaf3;
  background: #fff;
  padding: 5px 8px;
  font-size: 1em;
  outline: none;
  transition: border 0.2s;
}

.sidebar-row input[type="color"] {
  width: 28px;
  height: 28px;
  padding: 0;
  border: none;
  background: none;
}

.sidebar-actions {
  display: flex;
  gap: 10px;
  margin-top: 6px;
}

.sidebar-actions button {
  background: #fff;
  border: 1.5px solid #e5eaf3;
  border-radius: 7px;
  padding: 7px 10px;
  font-size: 16px;
  color: #5f4dee;
  cursor: pointer;
  transition: background 0.18s, color 0.18s, border 0.18s;
}

.sidebar-actions button:hover {
  background: #f2f3fa;
  color: #3d2fc7;
  border-color: #5f4dee;
}

@media (max-width: 700px) {
  .modern-sidebar {
    width: 100vw;
    height: auto;
    flex-direction: row;
    overflow-x: auto;
    border-right: none;
    border-bottom: 1.5px solid #e5eaf3;
    box-shadow: none;
    padding: 10px 2vw;
    gap: 12px;
  }
  .sidebar-section {
    margin-bottom: 0;
    margin-right: 18px;
  }
}
@media (max-width: 500px) {
  .modern-sidebar {
    padding: 6px 1vw;
    gap: 8px;
  }
  .sidebar-title {
    font-size: 13px;
  }
  .sidebar-row select,
  .sidebar-row input[type="range"],
  .sidebar-row input[type="color"] {
    font-size: 0.95em;
    padding: 3px 4px;
  }
}
@media (max-width: 700px) {
  .portfolio-main {
    padding: 12px 0;
  }
  .portfolio-resume {
    width: 98vw !important;
    min-width: unset;
    padding: 10vw 2vw !important;
    height: auto !important;
  }
}
@media (max-width: 500px) {
  .portfolio-resume {
    padding: 4vw 1vw !important;
    font-size: 12px !important;
  }
}
	@media (max-width: 500px) {
  .portfolio-sidebar button,
  .portfolio-sidebar select,
  .portfolio-sidebar input[type="color"] {
    font-size: 0.95em;
    padding: 6px;
  }
  .zoom {
    width: 98vw;
    right: 1vw;
    bottom: 10px;
    padding: 4px 6px;
    font-size: 0.95em;
  }
}
</style>
<?php    if ($doc_id) {
        // --- РЕДАКТОР ---
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
<div class="portfolio-editor-container" id="portfolioEditorApp">
    <div class="modern-sidebar">
      <div class="sidebar-section">
        <div class="sidebar-title"><i class="fa fa-layer-group"></i> Templates</div>
        <div class="sidebar-templates" id="templateList"></div>
      </div>
      <div class="sidebar-section">
        <div class="sidebar-title"><i class="fa fa-paint-brush"></i> Design</div>
        <div class="sidebar-row">
          <label><i class="fa fa-font"></i></label>
          <select id="fontSelect">
            <option value="Rubik">Rubik</option>
            <option value="Arial">Arial</option>
            <option value="Roboto">Roboto</option>
            <option value="Georgia">Georgia</option>
          </select>
        </div>
        <div class="sidebar-row">
          <label><i class="fa fa-text-height"></i></label>
          <input type="range" id="fontSizeRange" min="10" max="16" value="13">
        </div>
        <div class="sidebar-row">
          <label><i class="fa fa-palette"></i></label>
          <input type="color" id="mainColor" value="#0078d4">
          <input type="color" id="accentColor" value="#222222">
        </div>
        <div class="sidebar-row">
          <label><i class="fa fa-columns"></i></label>
          <select id="columnSelect">
            <option value="2">2</option>
            <option value="1">1</option>
            <option value="3">3</option>
          </select>
        </div>
      </div>
      <div class="sidebar-section">
        <div class="sidebar-title"><i class="fa fa-cogs"></i> Actions</div>
        <div class="sidebar-actions">
          <button onclick="portfolioUndo()" title="Undo"><i class="fa fa-undo"></i></button>
          <button onclick="portfolioRedo()" title="Redo"><i class="fa fa-redo"></i></button>
          <button onclick="portfolioDownload()" title="Download PDF"><i class="fa fa-download"></i></button>
          <button onclick="portfolioShare()" title="Share"><i class="fa fa-share-alt"></i></button>
        </div>
      </div>
    </div>
    <div class="portfolio-main">
        <div id="loadingIndicator" style="text-align:center; padding: 40px; font-size: 1.2em; color: #555;">
            Loading...
        </div>
        <div class="portfolio-resume" id="resumeArea" contenteditable="false" style="display:none;"></div>

        <label class="zoom" style="display:flex; align-items:center; gap:6px; user-select:none; cursor:pointer;">
            <i class="fa-solid fa-magnifying-glass" style="font-size:16px; color:#555;"></i>
            <input type="range" id="zoomRange" min="25" max="190" value="120" style="flex:1;">
            <input type="number" id="zoomInput" min="25" max="190" value="125" style="width:50px; text-align:right; font-size:1em; border:1px solid #ccc; border-radius:4px; padding:2px 4px;">%
        </label>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
/* ============================================================
   НАСТРОЙКИ ДОКУМЕНТА
   ============================================================ */
var DOCUMENT_ID   = "<?php echo esc_js($doc_id); ?>";
var DOCUMENT_TYPE = "<?php echo isset($isEssay) && $isEssay ? 'essay' : 'portfolio'; ?>";
var isEssay       = (DOCUMENT_TYPE === 'essay');

/* ============================================================
   ТЕМПЛЕЙТЫ
   ============================================================ */
const portfolioTemplates = [
    {
        name: "Custom",
        columns: 2,
        font: "Rubik",
        fontSize: 13,
        mainColor: "#0078d4",
        accentColor: "#222222",
        preview: "http://stepbuilder.org/wp-content/uploads/2025/09/Custom.png",
        sections: [
            {type: "header", data: {name: "YOUR NAME", contacts: "Phone | Email | LinkedIn/Portfolio | Location", avatar: ""}},
            {type: "awards", data: {title: "AWARDS", items: ["Award 1", "Award 2"]}},
            {type: "projects", data: {title: "Projects", items: [{ title: "Project 1", desc: "Description 1" }, { title: "Project 2", desc: "Description 2" },]}},
            {type: "summary", data: {title: "SUMMARY", text: "Description"}},
            {type: "achievements", data: {title: "Achievements", items: [{ title: "Achievement 1", desc: "Description 1" }, { title: "Achievement 2", desc: "Description 2" }]}},
            {type: "skills", data: {title: "SKILLS", items: ["Your Skill"]}},
            {
                type: "education",
                data: {
                    title: "EDUCATION",
                    items: [
                        { degree: "Bachelor of Science", school: "MIT", major: "Computer Science", gpa: "4.0", date: "2018–2022" }
                    ]
                }
            }
        ]
    }
];

// Шаблон для ЭССЕ
const essayTemplates = [
    {
        name: "Motivation Essay",
        font: "Rubik",
        fontSize: 13,
        mainColor: "#0078d4",
        accentColor: "#222222",
        preview: "https://dummyimage.com/400x200/e5eaf3/5f4dee&text=Essay",
        sections: [
            {
                type: "essay",
                data: {
                    title: "My Motivation",
                    body: "Here goes your motivation letter text. Tell about your background, achievements, why you want to study or work at this place, and what value you will bring."
                }
            }
        ]
    }
];

/* ============================================================
   СОСТОЯНИЕ / ИСТОРИЯ (Portfolio)
   ============================================================ */
let portfolioHistory = [];
let portfolioHistoryIndex = -1;
let portfolioState = {
    template: 0,
    font: "Rubik",
    fontSize: 13,
    mainColor: "#0078d4",
    accentColor: "#222222",
    columns: 2,
    sections: JSON.parse(JSON.stringify(portfolioTemplates[0].sections))
};

/* ============================================================
   СОСТОЯНИЕ / ИСТОРИЯ (Essay)
   ============================================================ */
let essayState = {
    template: 0,
    font: "Rubik",
    fontSize: 13,
    mainColor: "#0078d4",
    accentColor: "#222222",
    sections: JSON.parse(JSON.stringify(essayTemplates[0].sections))
};
let essayHistory = [];
let essayHistoryIndex = -1;

/* ============================================================
   РЕНДЕР ОДНОЙ СЕКЦИИ (Portfolio)
   ============================================================ */
function renderSection(section, idx, colIdx = 0) {
    const div = document.createElement('div');
    div.className = 'resume-section';

    function getTitle(def) {
        if (section.data && typeof section.data === 'object') {
            if ('title' in section.data) return section.data.title;
            if (Array.isArray(section.data) && section.data[0] && section.data[0].title) return section.data[0].title;
        }
        return def;
    }

    // Вспомогательная функция для блока с меню
    function renderEditableBlock(htmlContent, sectionIdx, itemIdx) {
        return `
            <div class="editable-block" data-section="${sectionIdx}" data-index="${itemIdx}">
                <button class="editable-block-menu-btn" title="Actions">&#8942;</button>
                ${htmlContent}
            </div>
        `;
    }

    if (section.type === 'header') {
        div.innerHTML = `
            <div class="resume-section-title editable" contenteditable="true" data-section="${idx}" data-field="name">${section.data.name}</div>
            <div class="editable" contenteditable="true" data-section="${idx}" data-field="contacts" style="font-size:0.95em;color:#888">${section.data.contacts}</div>
            <div class="resume-avatar"><i class="fa-solid fa-user"></i></div>
        `;
    } else {
        const title = getTitle(section.type.toUpperCase());
        let contentHTML = '';

        if (section.type === 'achievements' && Array.isArray(section.data.items)) {
            contentHTML = section.data.items.map((item, i) => renderEditableBlock(`
                <b class="editable" contenteditable="true" data-section="${idx}" data-field="title" data-index="${i}">${item.title || ''}</b>
                <div class="editable" contenteditable="true" data-section="${idx}" data-field="desc" data-index="${i}" style="font-size:0.95em;color:#555">${item.desc || ''}</div>
            `, idx, i)).join('');
        }
        else if (section.type === 'awards' && Array.isArray(section.data.items)) {
            contentHTML = section.data.items.map((item, i) => renderEditableBlock(`
                <div class="editable" contenteditable="true" data-section="${idx}" data-field="items" data-index="${i}">${item}</div>
            `, idx, i)).join('');
        }
        else if (section.type === 'education' && Array.isArray(section.data.items)) {
            contentHTML = section.data.items.map((item, i) => renderEditableBlock(`
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <b class="editable" contenteditable="true" data-section="${idx}" data-field="degree" data-index="${i}">${item.degree || ''}</b>
                    <span class="editable" contenteditable="true" data-section="${idx}" data-field="date" data-index="${i}" style="color:#888;white-space:nowrap;font-size:0.97em;">${item.date || ''}</span>
                </div>
                <div class="editable" contenteditable="true" data-section="${idx}" data-field="school" data-index="${i}" style="font-weight:500;">${item.school || ''}</div>
                <div>
                    <span class="editable" contenteditable="true" data-section="${idx}" data-field="major" data-index="${i}" style="font-size:0.97em;color:#555;">${item.major || ''}</span>
                    <span style="color:#555;">${item.major && item.gpa ? ' | ' : ''}</span>
                    <span class="editable" contenteditable="true" data-section="${idx}" data-field="gpa" data-index="${i}" style="font-size:0.97em;color:#555;">${item.gpa ? 'GPA: ' + item.gpa : ''}</span>
                </div>
            `, idx, i)).join('');
        }
        else if (section.type === 'custom' && Array.isArray(section.data.items)) {
            contentHTML = section.data.items.map((item, i) => renderEditableBlock(`
                <b class="editable" contenteditable="true" data-section="${idx}" data-field="title" data-index="${i}">${item.title || ''}</b>
                <div class="editable" contenteditable="true" data-section="${idx}" data-field="desc" data-index="${i}" style="font-size:0.95em;color:#555">${item.desc || ''}</div>
            `, idx, i)).join('');
        }
        else if (section.type === 'skills' && Array.isArray(section.data.items)) {
            contentHTML = renderEditableBlock(`
                <div class="editable" contenteditable="true" data-section="${idx}" data-field="items">${section.data.items.join(', ')}</div>
            `, idx, 0);
        }
        else if (section.type === 'summary') {
            contentHTML = renderEditableBlock(`
                <div class="editable" contenteditable="true" data-section="${idx}" data-field="text">${section.data.text || section.data}</div>
            `, idx, 0);
        }
        else if (section.type === 'projects' && section.data && Array.isArray(section.data.items)) {
            contentHTML = section.data.items.map((item, i) => renderEditableBlock(`
                <b class="editable" contenteditable="true" data-section="${idx}" data-field="title" data-index="${i}">${item.title || ''}</b>
                <div class="editable" contenteditable="true" data-section="${idx}" data-field="desc" data-index="${i}" style="font-size:0.95em;color:#555">${item.desc || ''}</div>
            `, idx, i)).join('');
        }
        else if (typeof section.data === 'string' || typeof section.data === 'number') {
            contentHTML = renderEditableBlock(`
                <div class="editable" contenteditable="true" data-section="${idx}" data-field="data">${section.data}</div>
            `, idx, 0);
        }
        else {
            contentHTML = `<pre>${JSON.stringify(section.data, null, 2)}</pre>`;
        }

        div.innerHTML = `
            <div class="resume-section-title editable" contenteditable="true" data-section="${idx}" data-field="title">${title}</div>
            ${contentHTML}
        `;
    }

    // Три точки меню секции
    const menuBtn = document.createElement('button');
    menuBtn.className = 'section-menu-btn';
    menuBtn.innerHTML = '<i class="fa fa-ellipsis-v"></i>';
    menuBtn.title = 'Section actions';
    menuBtn.onclick = function(e) {
        e.stopPropagation();
        showSectionToolbar(div, idx);
    };
    div.appendChild(menuBtn);

    if (section.type !== 'header') {
        showSectionAIBtn(div, idx, colIdx);
    }

    div.addEventListener('focusin', () => div.classList.add('editing'));
    div.addEventListener('focusout', () => setTimeout(() => div.classList.remove('editing'), 200));

    return div;
}

/* ============================================================
   РАСПРЕДЕЛЕНИЕ ПО КОЛОНКАМ (Portfolio)
   ============================================================ */
function splitSectionsByColumns(sections, columnsCount) {
    let columns = Array.from({length: columnsCount}, () => []);
    let perCol = Math.ceil(sections.length / columnsCount);
    for (let i = 0; i < sections.length; i++) {
        let colIdx = Math.floor(i / perCol);
        if (colIdx >= columnsCount) colIdx = columnsCount - 1;
        columns[colIdx].push(sections[i]);
    }
    return columns;
}

/* ============================================================
   РЕНДЕР РЕЗЮМЕ (Portfolio)
   ============================================================ */
function renderResume() {
    const area = document.getElementById('resumeArea');
    area.style.fontFamily = portfolioState.font;
    area.style.fontSize = portfolioState.fontSize + "px";
    area.style.color = portfolioState.accentColor;
    area.style.borderColor = portfolioState.mainColor;
    area.innerHTML = '';

    const columnsArr = splitSectionsByColumns(
        portfolioState.sections.map((section, idx) => ({section, idx})),
        portfolioState.columns
    );
    const columnsDiv = document.createElement('div');
    columnsDiv.className = 'resume-columns';
    columnsArr.forEach((col, colIdx) => {
        const colDiv = document.createElement('div');
        colDiv.className = 'resume-col';
        col.forEach(({section, idx}) => colDiv.appendChild(renderSection(section, idx, colIdx)));
        columnsDiv.appendChild(colDiv);
    });
    area.appendChild(columnsDiv);
}

/* ============================================================
   РЕНДЕР ЭССЕ (Essay)
   ============================================================ */
function renderEssay() {
    const area = document.getElementById('resumeArea');
    area.style.fontFamily = essayState.font;
    area.style.fontSize = essayState.fontSize + "px";
    area.style.color = essayState.accentColor;
    area.style.borderColor = essayState.mainColor;
    area.innerHTML = '';

    const sec = essayState.sections[0];
    const container = document.createElement('div');
    container.className = 'resume-section';
    container.innerHTML = `
        <div class="resume-section-title editable" contenteditable="true" data-field="title">${sec.data.title}</div>
        <div class="editable essay-body" contenteditable="true" data-field="body" style="white-space:pre-wrap; line-height:1.5;">${sec.data.body}</div>
    `;
    area.appendChild(container);
}

/* ============================================================
   СЧИТЫВАЕМ ДОКУМЕНТ С СЕРВЕРА
   ============================================================ */
document.addEventListener('DOMContentLoaded', function() {
    if (!DOCUMENT_ID) return;

    const finishLoading = () => {
        document.getElementById('loadingIndicator').style.display = 'none';
        document.getElementById('resumeArea').style.display = 'block';
    };

    if (isEssay) {
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'get_saved_essay',
                essay_key: DOCUMENT_ID
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data && data.data.content) {
                try {
                    essayState = JSON.parse(data.data.content);
                    saveEssayHistory();
                    renderEssay();
                    autoSaveEssay();
                } catch(e) {
                    document.getElementById('resumeArea').innerHTML = data.data.content;
                }
                finishLoading();
            } else {
                document.getElementById('resumeArea').innerHTML = '<div style="color:#e53e3e;font-size:1.2em;text-align:center;margin-top:100px;">Document not found</div>';
                finishLoading();
            }
        });
    } else {
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'get_saved_portfolio',
                portfolio_key: DOCUMENT_ID
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data && data.data.content) {
                try {
                    portfolioState = JSON.parse(data.data.content);
                    renderTemplateList();
                    saveHistory();
                    renderResume();
                    autoSave();
                } catch(e) {
                    document.getElementById('resumeArea').innerHTML = data.data.content;
                }
                finishLoading();
            } else if (data.data === undefined && data.message === 'Not allowed') {
                document.getElementById('resumeArea').innerHTML = '<div style="color:#e53e3e;font-size:1.2em;text-align:center;margin-top:100px;">Access denied</div>';
                document.querySelector('.portfolio-sidebar') && (document.querySelector('.portfolio-sidebar').style.display = 'none');
                finishLoading();
            } else {
                document.getElementById('resumeArea').innerHTML = '<div style="color:#e53e3e;font-size:1.2em;text-align:center;margin-top:100px;">Document not found</div>';
                document.querySelector('.portfolio-sidebar') && (document.querySelector('.portfolio-sidebar').style.display = 'none');
                finishLoading();
            }
        });
    }
});

/* ============================================================
   АВТОСОХРАНЕНИЕ
   ============================================================ */
let saveTimeout = null;
function autoSave() {
    if (isEssay) { autoSaveEssay(); return; }
    if (saveTimeout) clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        const content = JSON.stringify(portfolioState);
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'save_portfolio',
                portfolio_key: DOCUMENT_ID,
                content: content
            })
        });
    }, 1000);
}
function autoSaveEssay() {
    if (!isEssay) return;
    if (saveTimeout) clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        const content = JSON.stringify(essayState);
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'save_essay',
                essay_key: DOCUMENT_ID,
                content: content
            })
        });
    }, 1000);
}

/* ============================================================
   ИСТОРИЯ (UNDO/REDO)
   ============================================================ */
function saveHistory() {
    if (isEssay) { saveEssayHistory(); return; }
    portfolioHistory = portfolioHistory.slice(0, portfolioHistoryIndex + 1);
    portfolioHistory.push(JSON.stringify(portfolioState));
    portfolioHistoryIndex++;
}
function portfolioUndo() {
    if (isEssay) { essayUndo(); return; }
    if (portfolioHistoryIndex > 0) {
        portfolioHistoryIndex--;
        portfolioState = JSON.parse(portfolioHistory[portfolioHistoryIndex]);
        renderResume();
    }
}
function portfolioRedo() {
    if (isEssay) { essayRedo(); return; }
    if (portfolioHistoryIndex < portfolioHistory.length - 1) {
        portfolioHistoryIndex++;
        portfolioState = JSON.parse(portfolioHistory[portfolioHistoryIndex]);
        renderResume();
    }
}

// Essay history
function saveEssayHistory() {
    essayHistory = essayHistory.slice(0, essayHistoryIndex + 1);
    essayHistory.push(JSON.stringify(essayState));
    essayHistoryIndex++;
}
function essayUndo() {
    if (essayHistoryIndex > 0) {
        essayHistoryIndex--;
        essayState = JSON.parse(essayHistory[essayHistoryIndex]);
        renderEssay();
    }
}
function essayRedo() {
    if (essayHistoryIndex < essayHistory.length - 1) {
        essayHistoryIndex++;
        essayState = JSON.parse(essayHistory[essayHistoryIndex]);
        renderEssay();
    }
}

/* ============================================================
   ТУЛБАРЫ И AI КНОПКИ СЕКЦИЙ (Portfolio)
   ============================================================ */
function showSectionToolbar(sectionDiv, idx) {
    document.querySelectorAll('.section-toolbar').forEach(tb => tb.remove());

    const toolbar = document.createElement('div');
    toolbar.className = 'section-toolbar';
    toolbar.innerHTML = `
        <button onclick="moveSectionUp(${idx})"><i class="fa fa-arrow-up"></i> Move up</button>
        <button onclick="moveSectionDown(${idx})"><i class="fa fa-arrow-down"></i> Move down</button>
        <button onclick="duplicateSection(${idx})"><i class="fa fa-copy"></i> Duplicate</button>
        <button onclick="deleteSection(${idx})"><i class="fa fa-trash"></i> Delete</button>
        <button onclick="addSectionAfter(${idx})"><i class="fa fa-plus"></i> Add below</button>
    `;
    sectionDiv.appendChild(toolbar);
    setTimeout(() => {
        document.addEventListener('mousedown', closeToolbar, { once: true });
    }, 10);

    function closeToolbar(e) {
        if (!toolbar.contains(e.target)) toolbar.remove();
    }
}
function showSectionAIBtn(sectionDiv, idx, colIdx = 0) {
    sectionDiv.querySelectorAll('.ai-section-btn').forEach(b => b.remove());
    const aiBtn = document.createElement('div');
    aiBtn.innerHTML = `<img src="http://stepbuilder.org/wp-content/uploads/2025/12/aicon.png" alt="AI Assistant" style="width:32px;height:32px;display:block;">`;
    aiBtn.title = 'AI Assistant';
    aiBtn.className = 'ai-section-btn';
    if (portfolioState.columns > 1 && colIdx === 0) {
        aiBtn.style.left = '-30px';
        aiBtn.style.right = 'auto';
    } else {
        aiBtn.style.right = '-30px';
        aiBtn.style.left = 'auto';
    }
    aiBtn.style.top = '54px';
    aiBtn.style.position = 'absolute';
    aiBtn.style.cursor = 'pointer';

    aiBtn.onclick = function(e) {
        e.stopPropagation();
        aiBtn.classList.add('ai-animating');
        setTimeout(() => {
            showAIPopover(sectionDiv, idx, aiBtn);
            aiBtn.classList.remove('ai-animating');
        }, 250);
    };
    sectionDiv.appendChild(aiBtn);
}
function showAIPopover(sectionDiv, idx, anchorBtn) {
    document.querySelectorAll('.ai-popover').forEach(p => p.remove());

    const popover = document.createElement('div');
    popover.className = 'ai-popover';
    popover.innerHTML = `
        <div class="ai-popover-header">
            <span class="ai-popover-dot"></span>
            <span class="ai-popover-title">AI ASSISTANT</span>
            <button class="ai-close-btn" title="Close">&times;</button>
        </div>
        <div class="ai-popover-btns">
            <button class="ai-suggestion-btn" data-prompt="Improve writing">
                <i class="fa fa-magic"></i> Improve Writing
            </button>
            <button class="ai-suggestion-btn" data-prompt="Generate suggestions">
                <i class="fa fa-magic"></i> Generate Suggestions
            </button>
        </div>
        <div class="ai-popover-or">or</div>
        <div class="ai-popover-input-row">
            <textarea class="ai-prompt-input" placeholder="Enter a custom request" rows="1" autocomplete="off"></textarea>
        </div>
    `;
    document.body.appendChild(popover);

    const sectionRect = sectionDiv.getBoundingClientRect();
    const popoverWidth = 260;
    let left = sectionRect.left - popoverWidth - 16;
    let top = sectionRect.top + sectionRect.height / 2 - 90;
    if (left < 10) left = sectionRect.right + 16;
    if (top < 10) top = 10;
    popover.style.position = 'absolute';
    popover.style.top = (window.scrollY + top) + 'px';
    popover.style.left = (window.scrollX + left) + 'px';
    popover.style.zIndex = 10010;
    popover.style.opacity = 0;
    popover.style.transform = 'scale(0.95)';
    setTimeout(() => {
        popover.style.opacity = 1;
        popover.style.transform = 'scale(1)';
    }, 10);
    popover.dataset.initialLeft = left;
    popover.querySelector('.ai-close-btn').onclick = () => popover.remove();

    function getSectionContext(section) {
        if (section.type === 'summary' && section.data.text) return section.data.text;
        if (section.type === 'achievements' && section.data.items)
            return section.data.items.map(i => i.title + ': ' + i.desc).join('\n');
        if (section.type === 'skills' && section.data.items)
            return section.data.items.join(', ');
        if (section.type === 'projects' && section.data.items)
            return section.data.items.map(i => i.title + ': ' + i.desc).join('\n');
        if (section.type === 'education' && section.data.items)
            return section.data.items.map(i => `${i.degree} at ${i.school} (${i.date})\nMajor: ${i.major}\nGPA: ${i.gpa}`).join('\n\n');
        if (typeof section.data === 'string') return section.data;
        return JSON.stringify(section.data, null, 2);
    }
    function getSectionTitle(section) {
        const titles = { summary: "Summary", achievements: "Achievements", skills: "Skills", projects: "Projects", education: "Education", awards: "Awards", custom: "Custom Section", header: "Header" };
        return titles[section.type] || section.type.charAt(0).toUpperCase() + section.type.slice(1);
    }
    function showAIAnswerUI() {
        popover.querySelector('.ai-popover-btns').style.display = 'none';
        popover.querySelector('.ai-popover-or').style.display = 'none';
        popover.querySelector('.ai-popover-input-row').style.display = 'none';
        popover.style.transition = 'max-width 0.3s ease, min-width 0.3s ease, left 0.3s ease';
        popover.style.minWidth = '420px';
        popover.style.maxWidth = '420px';
        let initialLeft = parseFloat(popover.dataset.initialLeft) || 0;
        let newLeft = initialLeft - 160;
        popover.style.left = (window.scrollX + newLeft) + 'px';
        let answerBlock = popover.querySelector('.ai-answer');
        if (!answerBlock) {
            answerBlock = document.createElement('div');
            answerBlock.className = 'ai-answer';
            popover.appendChild(answerBlock);
        }
        answerBlock.innerHTML = `<div class="ai-answer-text" style="margin-top:10px;color:#28314f;font-size:1em;"></div>`;
    }
    function sendPrompt(prompt, context) {
        let answerText = popover.querySelector('.ai-answer-text');
        if (answerText) answerText.textContent = 'Generating...';
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'ai_assistant_gpt',
                prompt: prompt,
                context: context,
                section_type: portfolioState.sections[idx].type
            })
        })
        .then(res => res.json())
        .then(data => {
            let answerText = popover.querySelector('.ai-answer-text');
            if (answerText) {
                answerText.textContent = data.success && data.data ? data.data : 'Error: ' + (data.data || 'No response');
            }
        })
        .catch(() => {
            let answerText = popover.querySelector('.ai-answer-text');
            if (answerText) answerText.textContent = 'Connection error';
        });
    }

    popover.querySelectorAll('.ai-suggestion-btn').forEach(btn => {
        btn.onclick = function () {
            const section = portfolioState.sections[idx];
            const context = getSectionContext(section);
            let prompt = '';
            if (this.dataset.prompt === 'Improve writing') {
                prompt = `You are a professional career consultant... Improve writing for university applications. Section: ${getSectionTitle(section)}. Text:\n\n${context}`;
            } else {
                prompt = `Suggest 2-3 ways to improve this section "${getSectionTitle(section)}" for university applications:\n\n${context}`;
            }
            showAIAnswerUI();
            sendPrompt(prompt, context);
        };
    });
    const textarea = popover.querySelector('.ai-prompt-input');
    textarea.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            const section = portfolioState.sections[idx];
            const context = getSectionContext(section);
            const prompt = textarea.value.trim();
            if (!prompt) return;
            showAIAnswerUI();
            sendPrompt(prompt, context);
        }
    });

    // drag popover
    const header = popover.querySelector('.ai-popover-header');
    let isDragging = false;
    let dragStartX = 0, dragStartY = 0, popoverStartLeft = 0, popoverStartTop = 0;
    header.style.cursor = 'move';
    header.addEventListener('mousedown', (e) => {
        e.preventDefault();
        isDragging = true;
        dragStartX = e.clientX;
        dragStartY = e.clientY;
        popoverStartLeft = parseFloat(popover.style.left) || 0;
        popoverStartTop = parseFloat(popover.style.top) || 0;
        document.body.style.userSelect = 'none';
    });
    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        const dx = e.clientX - dragStartX;
        const dy = e.clientY - dragStartY;
        popover.style.left = (popoverStartLeft + dx) + 'px';
        popover.style.top = (popoverStartTop + dy) + 'px';
    });
    document.addEventListener('mouseup', () => {
        if (isDragging) {
            isDragging = false;
            document.body.style.userSelect = '';
        }
    });
}

/* ============================================================
   ДВИЖЕНИЕ / ДУБЛИ / УДАЛЕНИЕ СЕКЦИЙ (Portfolio)
   ============================================================ */
function moveSectionUp(idx) {
    if (idx > 0) {
        [portfolioState.sections[idx-1], portfolioState.sections[idx]] = [portfolioState.sections[idx], portfolioState.sections[idx-1]];
        saveHistory(); renderResume(); autoSave();
    }
}
function moveSectionDown(idx) {
    if (idx < portfolioState.sections.length - 1) {
        [portfolioState.sections[idx+1], portfolioState.sections[idx]] = [portfolioState.sections[idx], portfolioState.sections[idx+1]];
        saveHistory(); renderResume(); autoSave();
    }
}
function duplicateSection(idx) {
    const copy = JSON.parse(JSON.stringify(portfolioState.sections[idx]));
    portfolioState.sections.splice(idx+1, 0, copy);
    saveHistory(); renderResume(); autoSave();
}
function deleteSection(idx) {
    if (portfolioState.sections.length > 1) {
        portfolioState.sections.splice(idx, 1);
        saveHistory(); renderResume(); autoSave();
    } else {
        alert('At least one section required');
    }
}
function addSectionAfter(idx) {
    portfolioState.sections.splice(idx+1, 0, {
        type: "custom",
        data: { title: "Custom Section", items: [ {title: "Custom Title", desc: "Custom Description"} ] }
    });
    saveHistory(); renderResume(); autoSave();
}
window.moveSectionUp = moveSectionUp;
window.moveSectionDown = moveSectionDown;
window.duplicateSection = duplicateSection;
window.deleteSection = deleteSection;
window.addSectionAfter = addSectionAfter;

/* ============================================================
   ДВИЖЕНИЕ / ДУБЛИ / УДАЛЕНИЕ ЭЛЕМЕНТОВ (Portfolio)
   ============================================================ */
function moveItemUp(sectionIdx, itemIdx) {
    const section = portfolioState.sections[sectionIdx];
    if (!section) return;
    if (itemIdx > 0 && Array.isArray(section.data.items)) {
        const items = section.data.items;
        [items[itemIdx - 1], items[itemIdx]] = [items[itemIdx], items[itemIdx - 1]];
        saveHistory(); renderResume(); autoSave();
    }
}
function moveItemDown(sectionIdx, itemIdx) {
    const section = portfolioState.sections[sectionIdx];
    if (!section) return;
    if (Array.isArray(section.data.items)) {
        const items = section.data.items;
        if (itemIdx < items.length - 1) {
            [items[itemIdx + 1], items[itemIdx]] = [items[itemIdx], items[itemIdx + 1]];
            saveHistory(); renderResume(); autoSave();
        }
    }
}
function duplicateItem(sectionIdx, itemIdx) {
    const section = portfolioState.sections[sectionIdx];
    if (!section) return;
    if (Array.isArray(section.data.items)) {
        let copy;
        if (section.type === 'education') {
            copy = {...section.data.items[itemIdx]};
        } else if (typeof section.data.items[0] === 'string' || section.type === 'skills' || section.type === 'awards') {
            copy = section.data.items[itemIdx];
        } else {
            copy = {...section.data.items[itemIdx]};
        }
        section.data.items.splice(itemIdx + 1, 0, copy);
        saveHistory(); renderResume(); autoSave();
    }
}
function deleteItem(sectionIdx, itemIdx) {
    const section = portfolioState.sections[sectionIdx];
    if (!section) return;
    if (Array.isArray(section.data.items)) {
        if (section.data.items.length > 1) {
            section.data.items.splice(itemIdx, 1);
            saveHistory(); renderResume(); autoSave();
        } else {
            alert('At least one item required');
        }
    }
}
function addItemAfter(sectionIdx, itemIdx) {
    const section = portfolioState.sections[sectionIdx];
    if (!section) return;
    if (Array.isArray(section.data.items)) {
        if (section.type === 'education') {
            section.data.items.splice(itemIdx + 1, 0, { degree: "Degree", school: "School", major: "Major", gpa: "gpa", date: "Date" });
        } else if (typeof section.data.items[0] === 'string' || section.type === 'skills' || section.type === 'awards') {
            section.data.items.splice(itemIdx + 1, 0, "New");
        } else {
            section.data.items.splice(itemIdx + 1, 0, {title: "New Title", desc: "New Description"});
        }
        saveHistory(); renderResume(); autoSave();
    }
}
window.moveItemUp = moveItemUp;
window.moveItemDown = moveItemDown;
window.duplicateItem = duplicateItem;
window.deleteItem = deleteItem;
window.addItemAfter = addItemAfter;

/* ============================================================
   ОБРАБОТКА ВВОДА (Portfolio)
   ============================================================ */
document.addEventListener('input', function(e) {
    if (isEssay) return; // эссе обрабатываем ниже
    if (e.target.classList.contains('editable')) {
        const idx = +e.target.dataset.section;
        const field = e.target.dataset.field;
        const val = e.target.innerText || e.target.textContent;
        const arrIndex = e.target.dataset.index !== undefined ? +e.target.dataset.index : undefined;

        let section = portfolioState.sections[idx];
        if (!section) return;

        if (arrIndex !== undefined) {
            if (section.type === 'awards' && field === 'items' && Array.isArray(section.data.items)) {
                section.data.items[arrIndex] = val;
            }
            else if (Array.isArray(section.data.items)) {
                if (section.data.items[arrIndex]) {
                    section.data.items[arrIndex][field] = val;
                }
            } else if (Array.isArray(section.data)) {
                if (section.data[arrIndex]) section.data[arrIndex][field] = val;
            }
        } else if (field === "items") {
            if (Array.isArray(section.data.items)) {
                section.data.items = val.split(/\n|,|<br>/).map(s => s.trim()).filter(Boolean);
            } else if (Array.isArray(section.data)) {
                if (section.data[0] && Array.isArray(section.data[0].items)) {
                    section.data[0].items = val.split(/\n|,|<br>/).map(s => s.trim()).filter(Boolean);
                }
            }
        } else if (field === "title") {
            if (section.type === 'custom' || section.type === 'awards') {
                if (typeof section.data === 'object' && section.data !== null) section.data.title = val;
            } else {
                if (typeof section.data === "object" && section.data !== null) section.data[field] = val;
            }
        } else if (field === "data") {
            section.data = val;
        } else {
            if (typeof section.data === "object" && section.data !== null) section.data[field] = val;
        }

        saveHistory();
        autoSave();
    }
});

/* ============================================================
   ОБРАБОТКА ВВОДА (Essay)
   ============================================================ */
document.addEventListener('input', function(e) {
    if (!isEssay) return;
    if (e.target.classList.contains('editable')) {
        const field = e.target.dataset.field;
        const val = e.target.innerText || e.target.textContent;
        if (field === 'title') {
            essayState.sections[0].data.title = val;
        } else if (field === 'body') {
            essayState.sections[0].data.body = val;
        }
        saveEssayHistory();
        autoSaveEssay();
    }
});

/* ============================================================
   КЛИК ПО МЕНЮ БЛОКОВ (Portfolio)
   ============================================================ */
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('editable-block-menu-btn')) {
        e.stopPropagation();
        document.querySelectorAll('.editable-block-toolbar').forEach(tb => tb.remove());
        const btn = e.target;
        const block = btn.closest('.editable-block');
        const sectionIdx = +block.dataset.section;
        const itemIdx = +block.dataset.index;
        const toolbar = document.createElement('div');
        toolbar.className = 'editable-block-toolbar';
        toolbar.innerHTML = `
            <button title="Move Up">&#8593; Move Up</button>
            <button title="Move Down">&#8595; Move Down</button>
            <button title="Duplicate">&#128203; Duplicate</button>
            <button class="delete" title="Delete">&#128465; Delete</button>
            <button style="font-weight:bold;" title="Add Below">&#43; Add Below</button>
        `;
        block.appendChild(toolbar);
        const buttons = toolbar.querySelectorAll('button');
        buttons[0].onclick = () => { moveItemUp(sectionIdx, itemIdx); toolbar.remove(); };
        buttons[1].onclick = () => { moveItemDown(sectionIdx, itemIdx); toolbar.remove(); };
        buttons[2].onclick = () => { duplicateItem(sectionIdx, itemIdx); toolbar.remove(); };
        buttons[3].onclick = () => { deleteItem(sectionIdx, itemIdx); toolbar.remove(); };
        buttons[4].onclick = () => { addItemAfter(sectionIdx, itemIdx); toolbar.remove(); };
    } else {
        document.querySelectorAll('.editable-block-toolbar').forEach(tb => tb.remove());
    }
});

/* ============================================================
   ТЕМПЛЕЙТЫ (Portfolio)
   ============================================================ */
function renderTemplateList() {
    const list = document.getElementById('templateList');
    if (!list) return;
    list.innerHTML = '';
    portfolioTemplates.forEach((tpl, idx) => {
        const div = document.createElement('div');
        div.className = 'template-item' + (portfolioState.template === idx ? ' selected' : '');
        div.innerHTML = `
            <img src="${tpl.preview}" alt="${tpl.name}" style="width:100%;border-radius:6px;box-shadow:0 2px 8px #eee;">
            <div style="text-align:center;font-weight:600;margin-top:6px;">${tpl.name}</div>
        `;
        div.onclick = () => {
            portfolioState.template = idx;
            const oldSections = portfolioState.sections;
            const newSections = JSON.parse(JSON.stringify(portfolioTemplates[idx].sections));
            portfolioState.sections = mergeSections(oldSections, newSections);
            portfolioState.font = tpl.font;
            portfolioState.fontSize = tpl.fontSize;
            portfolioState.mainColor = tpl.mainColor;
            portfolioState.accentColor = tpl.accentColor;
            portfolioState.columns = tpl.columns;
            saveHistory(); renderTemplateList(); renderResume(); autoSave();
        };
        list.appendChild(div);
    });
}
function mergeSections(oldSections, newTemplateSections) {
    const result = [];
    newTemplateSections.forEach(newSec => {
        const oldSec = oldSections.find(s => s.type === newSec.type);
        if (oldSec) {
            let merged = {...newSec, data: oldSec.data};
            result.push(merged);
        } else {
            result.push(newSec);
        }
    });
    return result;
}

/* ============================================================
   ЗАГРУЗКА/САЙДБАР ДЛЯ ДИЗАЙНА
   ============================================================ */
document.getElementById('fontSelect').onchange = function() {
    if (isEssay) { essayState.font = this.value; saveEssayHistory(); renderEssay(); autoSaveEssay(); return; }
    portfolioState.font = this.value; saveHistory(); renderResume(); autoSave();
};
document.getElementById('fontSizeRange').oninput = function() {
    if (isEssay) { essayState.fontSize = +this.value; saveEssayHistory(); renderEssay(); autoSaveEssay(); return; }
    portfolioState.fontSize = +this.value; saveHistory(); renderResume(); autoSave();
};
document.getElementById('mainColor').oninput = function() {
    if (isEssay) { essayState.mainColor = this.value; saveEssayHistory(); renderEssay(); autoSaveEssay(); return; }
    portfolioState.mainColor = this.value; saveHistory(); renderResume(); autoSave();
};
document.getElementById('accentColor').oninput = function() {
    if (isEssay) { essayState.accentColor = this.value; saveEssayHistory(); renderEssay(); autoSaveEssay(); return; }
    portfolioState.accentColor = this.value; saveHistory(); renderResume(); autoSave();
};
document.getElementById('columnSelect').onchange = function() {
    if (isEssay) { this.value = 1; return; }
    portfolioState.columns = +this.value; saveHistory(); renderResume(); autoSave();
};

/* ============================================================
   ЗУМ
   ============================================================ */
const zoomRange = document.getElementById('zoomRange');
const zoomInput = document.getElementById('zoomInput');
const resumeArea = document.getElementById('resumeArea');
function setZoom(value) {
    let val = Math.min(Math.max(value, 25), 190);
    zoomRange.value = val;
    zoomInput.value = val;
    resumeArea.style.zoom = val + '%';
}
zoomRange.addEventListener('input', () => { setZoom(zoomRange.value); });
zoomInput.addEventListener('change', () => { setZoom(zoomInput.value); });

/* ============================================================
   DOWNLOAD / SHARE
   ============================================================ */
function portfolioDownload() {
    // для эссе — свой рендер
    if (isEssay) {
        const resumeArea = document.getElementById('resumeArea');
        if (!resumeArea) return;
        const prevZoom = resumeArea.style.zoom;
        resumeArea.style.zoom = '100%';
        setTimeout(() => {
            html2canvas(resumeArea, {backgroundColor: '#fff', scale: 2}).then(canvas => {
                resumeArea.style.zoom = prevZoom;
                const imgData = canvas.toDataURL('image/png');
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'pt', 'a4');
                const pageWidth = pdf.internal.pageSize.getWidth();
                const imgWidth = pageWidth;
                const imgHeight = canvas.height * imgWidth / canvas.width;
                pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                let filename = "essay.pdf";
                try {
                    const title = essayState.sections[0].data.title || "Essay";
                    filename = title.replace(/[^\w\d]+/g, "_") + ".pdf";
                } catch(e) {}
                pdf.save(filename);
            });
        }, 100);
        return;
    }
    // портфолио
    const prevZoom = resumeArea.style.zoom;
    resumeArea.style.zoom = '100%';
    setTimeout(() => {
        html2canvas(resumeArea, {backgroundColor: '#fff', scale: 2}).then(canvas => {
            resumeArea.style.zoom = prevZoom;
            const imgData = canvas.toDataURL('image/png');
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'pt', 'a4');
            const pageWidth = pdf.internal.pageSize.getWidth();
            const imgWidth = pageWidth;
            const imgHeight = canvas.height * imgWidth / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
            let filename = "resume.pdf";
            try {
                const header = portfolioState.sections.find(s => s.type === 'header');
                if (header && header.data && header.data.name) {
                    filename = header.data.name.replace(/[^\w\d]+/g, "_") + ".pdf";
                }
            } catch(e) {}
            pdf.save(filename);
        });
    }, 100);
}
function portfolioShare() {
    if (isEssay) {
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'make_essay_public',
                essay_key: DOCUMENT_ID
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                prompt('Share this link:', window.location.origin + '/documents/' + DOCUMENT_ID);
            } else {
                alert('Error making document public');
            }
        });
        return;
    }
    fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
            action: 'make_portfolio_public',
            portfolio_key: DOCUMENT_ID
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            prompt('Share this link:', window.location.origin + '/documents/' + DOCUMENT_ID);
        } else {
            alert('Error making document public');
        }
    });
}

/* ============================================================
   AI ПО ВЫДЕЛЕНИЮ ТЕКСТА (ESSAY + Portfolio)
   ============================================================ */
function showSelectionAI(text, x, y, context, sectionType='essay') {
    document.querySelectorAll('.ai-popover').forEach(p => p.remove());
    if (!text || !text.trim()) return;

    const pop = document.createElement('div');
    pop.className = 'ai-popover';
    pop.innerHTML = `
        <div class="ai-popover-header">
            <span class="ai-popover-dot"></span>
            <span class="ai-popover-title">AI ASSISTANT</span>
            <button class="ai-close-btn" title="Close">&times;</button>
        </div>
        <div class="ai-popover-btns">
            <button class="ai-suggestion-btn" data-prompt="Improve writing">
                <i class="fa fa-magic"></i> Improve Writing
            </button>
            <button class="ai-suggestion-btn" data-prompt="Generate suggestions">
                <i class="fa fa-magic"></i> Generate Suggestions
            </button>
        </div>
        <div class="ai-popover-or">or</div>
        <div class="ai-popover-input-row">
            <textarea class="ai-prompt-input" placeholder="Enter a custom request" rows="1" autocomplete="off"></textarea>
        </div>
    `;
    document.body.appendChild(pop);
    pop.style.position = 'absolute';
    pop.style.left = (window.scrollX + x + 10) + 'px';
    pop.style.top = (window.scrollY + y + 10) + 'px';
    pop.style.zIndex = 10010;
    pop.querySelector('.ai-close-btn').onclick = () => pop.remove();

    function showAIAnswerUI() {
        pop.querySelector('.ai-popover-btns').style.display = 'none';
        pop.querySelector('.ai-popover-or').style.display = 'none';
        pop.querySelector('.ai-popover-input-row').style.display = 'none';
        let answer = document.createElement('div');
        answer.className = 'ai-answer';
        answer.innerHTML = `<div class="ai-answer-text" style="margin-top:10px;color:#28314f;font-size:1em;">Generating...</div>`;
        pop.appendChild(answer);
    }
    function sendPrompt(prompt, contextText) {
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'ai_assistant_gpt',
                prompt: prompt,
                context: contextText,
                section_type: sectionType
            })
        })
        .then(res => res.json())
        .then(data => {
            let answerText = pop.querySelector('.ai-answer-text');
            if (answerText) {
                answerText.textContent = data.success && data.data ? data.data : 'Error: ' + (data.data || 'No response');
            }
        })
        .catch(() => {
            let answerText = pop.querySelector('.ai-answer-text');
            if (answerText) answerText.textContent = 'Connection error';
        });
    }

    pop.querySelectorAll('.ai-suggestion-btn').forEach(btn => {
        btn.onclick = function() {
            let prompt = '';
            if (this.dataset.prompt === 'Improve writing') {
                prompt = `Improve the following selected text: "${text}". Make it more powerful and clear.`;
            } else {
                prompt = `Give 2-3 short suggestions to improve this text: "${text}".`;
            }
            showAIAnswerUI();
            sendPrompt(prompt, context || text);
        };
    });
    const textarea = pop.querySelector('.ai-prompt-input');
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            const prompt = textarea.value.trim();
            if (!prompt) return;
            showAIAnswerUI();
            sendPrompt(prompt, context || text);
        }
    });
}
// вешаем на mouseup
document.addEventListener('mouseup', function(e) {
    const sel = window.getSelection();
    if (!sel || sel.isCollapsed) return;
    const selectedText = sel.toString();
    if (!selectedText.trim()) return;
    const range = sel.getRangeAt(0);
    const rect = range.getBoundingClientRect();
    const ctx = isEssay ? (essayState.sections[0].data.body || '') : (resumeArea.innerText || '');
    const sectionType = isEssay ? 'essay' : 'portfolio';
    showSelectionAI(selectedText, rect.right, rect.bottom, ctx, sectionType);
});

/* ============================================================
   ИНИЦИАЛИЗАЦИЯ
   ============================================================ */
window.onload = function initEditor() {
    setZoom(125);
    if (isEssay) {
        document.getElementById('fontSelect').value = essayState.font;
        document.getElementById('fontSizeRange').value = essayState.fontSize;
        document.getElementById('mainColor').value = essayState.mainColor;
        document.getElementById('accentColor').value = essayState.accentColor;
        document.getElementById('columnSelect').value = 1;
        saveEssayHistory();
        renderEssay();
        autoSaveEssay();
    } else {
        renderTemplateList();
        saveHistory();
        renderResume();
        autoSave();
    }
};
</script>
<?php    }
    else {        // --- ДАШБОРД ---
?>
<?php if (!is_user_logged_in()) { ?>
    <p style="font-family: Inter, sans-serif; color: #e53e3e;">Please log in to view your documents.</p>
<?php
        }
        else { ?>
<style>
    .portfolio-dashboard-root {
        font-family: 'Inter', Arial, sans-serif;
        background: #f7f9fc;
        min-height: 100vh;
        padding-bottom: 20vh;
        margin-bottom: 0px;
    }
    .portfolio-dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0 auto 24px auto;
        max-width: 1200px;
        padding: 32px 24px 0 24px;
    }
    .portfolio-dashboard-title {
        font-size: 32px;
        font-weight: 700;
        color: #28314f;
    }
    .portfolio-dashboard-create-btn {
        background: #5f4dee;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        padding: 12px 28px;
        cursor: pointer;
        transition: background 0.2s;
        margin-left: 12px;
    }
    .portfolio-dashboard-create-btn:hover {
        background: #3d2fc7;
    }
    .portfolio-dashboard-tabs {
        display: flex;
        gap: 32px;
        border-bottom: 2px solid #e5eaf3;
        max-width: 1200px;
        margin: 0 auto 0 auto;
        padding: 0 24px;
    }
    .portfolio-dashboard-tab {
        font-size: 18px;
        font-weight: 500;
        color: #8b98b8;
        padding: 18px 0 12px 0;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: color 0.2s, border-bottom 0.2s;
    }
    .portfolio-dashboard-tab.active {
        color: #5f4dee;
        border-bottom: 2px solid #5f4dee;
    }
    .portfolio-dashboard-controls {
        display: flex;
        align-items: center;
        gap: 18px;
        max-width: 1200px;
        margin: 24px auto 0 auto;
        padding: 0 24px;
    }
    .portfolio-dashboard-search {
        flex: 1;
        background: #fff;
        border: 1px solid #e5eaf3;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 16px;
        outline: none;
        transition: border 0.2s;
    }
    .portfolio-dashboard-view-switch {
        display: flex;
        gap: 8px;
    }
    .portfolio-dashboard-view-btn {
        background: #fff;
        border: 1px solid #e5eaf3;
        border-radius: 6px;
        padding: 7px 10px;
        cursor: pointer;
        font-size: 18px;
        color: #8b98b8;
        transition: border 0.2s, color 0.2s;
    }
    .portfolio-dashboard-view-btn.active {
        border: 1.5px solid #5f4dee;
        color: #5f4dee;
        background: #f2f3fa;
    }
    .portfolio-dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
        gap: 32px;
        max-width: 1200px;
        margin: 32px auto 0 auto;
        padding: 0 24px 32px 24px;
    }
    .portfolio-dashboard-list {
        display: flex;
        flex-direction: column;
        gap: 24px;
        max-width: 900px;
        margin: 32px auto 0 auto;
        padding: 0 24px 32px 24px;
    }
    .portfolio-dashboard-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(51,102,204,0.07);
        border: 1px solid #e5eaf3;
        display: flex;
        gap: 28px;
        padding: 28px 32px;
        align-items: flex-start;
        position: relative;
        min-height: 220px;
        transition: box-shadow 0.2s;
    }
    .portfolio-dashboard-card:hover {
        box-shadow: 0 8px 32px rgba(51,102,204,0.13);
    }
    .portfolio-dashboard-preview {
        width: 140px;
        min-width: 160px;
        height: 210px;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5eaf3;
        position: relative;
    }
    .portfolio-dashboard-preview canvas {
        width: 100% !important;
        height: 100% !important;
        display: block;
    }
    .portfolio-dashboard-preview-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #bfc8e2;
        font-size: 38px;
        font-weight: 700;
        height: 100%;
        width: 100%;
        letter-spacing: 1px;
    }
    .portfolio-dashboard-card-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .portfolio-dashboard-card-type {
        font-size: 14px;
        font-weight: 600;
        color: #5f4dee;
        margin-bottom: 2px;
        letter-spacing: 0.5px;
    }
    .portfolio-dashboard-card-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .portfolio-dashboard-card-title {
        font-size: 20px;
        font-weight: 700;
        color: #28314f;
        margin-right: 10px;
    }
    .portfolio-dashboard-card-label-btn {
        background: #f5f6fa;
        color: #8b98b8;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        padding: 4px 12px;
        cursor: pointer;
        margin-right: 8px;
        transition: background 0.2s;
    }
    .portfolio-dashboard-card-label-btn:hover {
        background: #e5eaf3;
    }
    .portfolio-dashboard-card-star {
        color: #e4c441;
        font-size: 22px;
        cursor: pointer;
        margin-left: 8px;
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    .portfolio-dashboard-card-star.active {
        opacity: 1;
    }
    .portfolio-dashboard-card-edit-btn {
        background: none;
        border: none;
        color: #8b98b8;
        font-size: 18px;
        cursor: pointer;
        margin-left: 8px;
        transition: color 0.2s;
    }
    .portfolio-dashboard-card-edit-btn:hover {
        color: #5f4dee;
    }
    .portfolio-dashboard-card-meta {
        font-size: 14px;
        color: #8b98b8;
        margin-bottom: 8px;
    }
    .portfolio-dashboard-card-actions {
        display: flex;
        gap: 18px;
        margin-top: 10px;
        flex-wrap: wrap;
    }
    .portfolio-dashboard-card-action-btn {
        background: none;
        border: none;
        color: #5f4dee;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s;
        padding: 0;
    }
    .portfolio-dashboard-card-action-btn.delete {
        color: #e74c3c;
    }
    .portfolio-dashboard-card-action-btn.delete:hover {
        color: #c0392b;
    }
    .portfolio-dashboard-card-action-btn:hover {
        color: #3d2fc7;
    }
    .portfolio-dashboard-card-footer {
        font-size: 13px;
        color: #bfc8e2;
        margin-top: 18px;
        display: flex;
        align-items: center;
        gap: 18px;
    }
    .portfolio-dashboard-card-footer .fa-clock {
        margin-right: 4px;
    }
    .portfolio-dashboard-card-footer .fa-history {
        margin-left: 8px;
        cursor: pointer;
        color: #8b98b8;
        transition: color 0.2s;
    }
    .portfolio-dashboard-card-footer .fa-history:hover {
        color: #5f4dee;
    }
    .portfolio-dashboard-add-card {
        background: #eafaf2;
        border: 2px dashed #3ecf8e;
        border-radius: 16px;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s, border 0.2s;
        padding: 32px 0;
        gap: 12px;
    }
    .portfolio-dashboard-add-card:hover {
        background: #d4f5e3;
        border: 2px solid #3ecf8e;
    }
    .portfolio-dashboard-add-card .fa-plus {
        font-size: 38px;
        color: #3ecf8e;
    }
    .portfolio-dashboard-add-card-label {
        font-size: 18px;
        color: #3ecf8e;
        font-weight: 600;
    }
    /* Responsive */
    @media (max-width: 900px) {
        .portfolio-dashboard-header, .portfolio-dashboard-tabs, .portfolio-dashboard-controls, .portfolio-dashboard-grid, .portfolio-dashboard-list {
            max-width: 100vw;
            padding-left: 4vw;
            padding-right: 4vw;
        }
        .portfolio-dashboard-card {
            padding: 18px 18px;
        }
    }

	@media (max-width: 700px) {
  .portfolio-dashboard-grid {
    grid-template-columns: 1fr !important;
    gap: 18px;
    padding: 0 4vw 18px 4vw;
  }
  .portfolio-dashboard-header,
  .portfolio-dashboard-tabs,
  .portfolio-dashboard-controls {
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
  }
		.portfolio-dashboard-create-btn{
			display:none;
		}
}
@media (max-width: 500px) {
  .portfolio-dashboard-card {
    flex-direction: column;
    gap: 10px;
    padding: 10px 14px 6px 14px;
    min-height: 120px;
  }
	.portfolio-dashboard-header {

		margin: 0 auto 0px auto;}
  .portfolio-dashboard-preview {
    width: 100%;
    min-width: unset;
    height: 120px;
  }
  .portfolio-dashboard-title {
    font-size: 22px;
	  margin-top: -15px;
	  margin-left: auto;
	  margin-right: auto;
  }
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
<div class="portfolio-dashboard-root">
    <div class="portfolio-dashboard-header">
        <div class="portfolio-dashboard-title">My Documents</div>
        <div class="portfolio-dashboard-create-btn" id="portfolio-create-btn"><i class="fa fa-plus"></i> Create New</div>
    </div>
    <div class="portfolio-dashboard-tabs">
        <div class="portfolio-dashboard-tab active" data-tab="all">All documents</div>
        <div class="portfolio-dashboard-tab" data-tab="dportfolio">Portfolio (<span id="dportfolio-count">0</span>)</div>
        <div class="portfolio-dashboard-tab" data-tab="dessay">Motivation Essays (<span id="dessay-count">0</span>)</div>
    </div>
    <div class="portfolio-dashboard-controls">
        <input type="text" class="portfolio-dashboard-search" id="portfolio-search" placeholder="Search"/>
        <div class="portfolio-dashboard-view-switch">
            <button class="portfolio-dashboard-view-btn active" id="portfolio-view-grid" title="Grid view"><i class="fa fa-th-large"></i></button>
            <button class="portfolio-dashboard-view-btn" id="portfolio-view-list" title="List view"><i class="fa fa-list"></i></button>
        </div>
    </div>
    <div id="portfolio-dashboard-cards" class="portfolio-dashboard-grid"></div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://unpkg.com/html-docx-js/dist/html-docx.js"></script>
<script>
(function(){
    /* Берём ваши функции renderResumeHTML и т.п. — не изменяем */

    // Типы документов
    function getTypeFromKey(key, mapTypes) {
        return mapTypes[key] || 'dportfolio';
    }
    function getTypeName(type) {
        if (type === 'dessay') return 'Motivation Essay';
        return 'Portfolio';
    }
    function getTypeIcon(type) {
        if (type === 'dessay') return '<i class="fa fa-file-alt"></i>';
        return '<i class="fa fa-envelope"></i>';
    }

    let documents = {};     // key -> content
    let docTypes = {};      // key -> 'dportfolio' | 'dessay'
    let meta = {};
    let currentTab = 'all';
    let currentView = 'grid';
    let searchValue = '';
    const cardsContainer = document.getElementById('portfolio-dashboard-cards');
    const searchInput = document.getElementById('portfolio-search');
    const tabEls = document.querySelectorAll('.portfolio-dashboard-tab');
    const viewGridBtn = document.getElementById('portfolio-view-grid');
    const viewListBtn = document.getElementById('portfolio-view-list');
    const createBtn = document.getElementById('portfolio-create-btn');
    const dportfolioCount = document.getElementById('dportfolio-count');
    const dessayCount = document.getElementById('dessay-count');

    function loadDocuments() {
        Promise.all([
            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({action: 'get_saved_portfolios'})
            }).then(r=>r.json()),
            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({action: 'get_saved_essays'})
            }).then(r=>r.json())
        ]).then(([pRes, eRes]) => {
            documents = {};
            docTypes = {};
            meta = {};
            // портфолио
            if (pRes.success) {
                Object.keys(pRes.data.portfolios).forEach((key, i) => {
                    let value = pRes.data.portfolios[key];
                    documents[key] = value;
                    docTypes[key] = 'dportfolio';
                    let name = 'Portfolio #' + (i+1);
                    try {
                        let obj = JSON.parse(value);
                        let header = obj.sections.find(s => s.type === 'header');
                        if (header && header.data && header.data.name) name = header.data.name;
                    } catch(e){}
                    meta[key] = {name, edited: new Date().toLocaleString(), created: new Date().toLocaleString()};
                });
            }
            // эссе
            if (eRes.success) {
                Object.keys(eRes.data.essays).forEach((key, i) => {
                    let value = eRes.data.essays[key];
                    documents[key] = value;
                    docTypes[key] = 'dessay';
                    let name = 'Motivation Essay #' + (i+1);
                    try {
                        let obj = JSON.parse(value);
                        if (obj.sections && obj.sections[0] && obj.sections[0].data.title) {
                            name = obj.sections[0].data.title;
                        }
                    } catch(e){}
                    meta[key] = {name, edited: new Date().toLocaleString(), created: new Date().toLocaleString()};
                });
            }
            renderCards();
        });
    }

    function renderCards() {
        let keys = Object.keys(documents);
        if (currentTab === 'dportfolio') keys = keys.filter(k => docTypes[k] === 'dportfolio');
        if (currentTab === 'dessay') keys = keys.filter(k => docTypes[k] === 'dessay');

        if (searchValue.trim()) {
            keys = keys.filter(key => (meta[key]?.name || '').toLowerCase().includes(searchValue.toLowerCase()));
        }
        dportfolioCount.textContent = keys.filter(k => docTypes[k]==='dportfolio').length;
        dessayCount.textContent = keys.filter(k => docTypes[k]==='dessay').length;

        cardsContainer.className = currentView === 'grid' ? 'portfolio-dashboard-grid' : 'portfolio-dashboard-list';
        cardsContainer.innerHTML = '';
        cardsContainer.appendChild(createAddCard());
        keys.forEach(key => {
            cardsContainer.appendChild(createCard(key, documents[key], meta[key], docTypes[key]));
        });
    }

    function renderResumeHTML(portfolio) {
        /* ваша старая функция рендера портфолио */
        function splitSectionsByColumns(sections, columnsCount) {
            let columns = Array.from({length: columnsCount}, () => []);
            let perCol = Math.ceil(sections.length / columnsCount);
            for (let i = 0; i < sections.length; i++) {
                let colIdx = Math.floor(i / perCol);
                if (colIdx >= columnsCount) colIdx = columnsCount - 1;
                columns[colIdx].push(sections[i]);
            }
            return columns;
        }
        function renderSectionHTML(section, idx, portfolio) {
            function getTitle(def) {
                if (section.data && typeof section.data === 'object') {
                    if ('title' in section.data) return section.data.title;
                    if (Array.isArray(section.data) && section.data[0] && section.data[0].title) return section.data[0].title;
                }
                return def;
            }
            let contentHTML = '';
            if (section.type === 'header') {
                contentHTML = `
                    <div class="resume-section-title" style="font-weight:bold; font-size:1.2em; border-bottom:2px solid #222; margin-bottom:10px; letter-spacing:1px;">
                        ${section.data.name || ''}
                    </div>
                    <div style="font-size:0.95em; color:#888;">
                        ${section.data.contacts || ''}
                    </div>
                    <div class="resume-avatar"><i class="fa-solid fa-user"></i></div>
                `;
            } else if (section.type === 'education' && Array.isArray(section.data.items)) {
                contentHTML = section.data.items.map(ed => `
                    <div style="margin-bottom:18px;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                            <div><b>${ed.degree || ''}</b></div>
                            <div style="color:#888;white-space:nowrap;font-size:0.97em;">${ed.date || ''}</div>
                        </div>
                        <div style="font-weight:500;">${ed.school || ''}</div>
                        <div style="font-size:0.97em;color:#555;">${ed.major ? ed.major : ''}${ed.major && ed.gpa ? ' | ' : ''}${ed.gpa ? 'GPA: ' + ed.gpa : ''}</div>
                    </div>
                `).join('');
            } else if (Array.isArray(section.data?.items) && typeof section.data.items[0] === 'object') {
                contentHTML = section.data.items.map(item => `
                    <div>
                        <b>${item.title || ''}</b>
                        <div style="font-size:0.95em; color:#555;">${item.desc || ''}</div>
                    </div>
                `).join('');
            } else if (Array.isArray(section.data?.items) && typeof section.data.items[0] === 'string') {
                contentHTML = section.data.items.map(item => `<div>${item}</div>`).join('');
            } else if (section.type === 'summary') {
                contentHTML = section.data.text || section.data || '';
            } else if (typeof section.data === 'string' || typeof section.data === 'number') {
                contentHTML = section.data;
            } else {
                contentHTML = '';
            }

            if (section.type !== 'header') {
                const title = getTitle(section.type.toUpperCase());
                return `
                    <div class="resume-section">
                        <div class="resume-section-title">${title}</div>
                        <div class="resume-section-content">${contentHTML}</div>
                    </div>
                `;
            } else {
                return `<div class="resume-section">${contentHTML}</div>`;
            }
        }
        let html = `<div class="portfolio-resume" style="font-family:${portfolio.font};font-size:${portfolio.fontSize}px;color:${portfolio.accentColor};border-color:${portfolio.mainColor};">`;
        const columnsArr = splitSectionsByColumns(
            (portfolio.sections || []).map((section, idx) => renderSectionHTML(section, idx, portfolio)),
            portfolio.columns
        );
        html += `<div class="resume-columns">`;
        columnsArr.forEach(col => {
            html += `<div class="resume-col">` + col.join('') + `</div>`;
        });
        html += `</div></div>`;
        return html;
    }
    function renderEssayHTML(essay) {
        let sec = (essay.sections && essay.sections[0]) ? essay.sections[0] : {data:{title:'',body:''}};
        return `
        <div class="portfolio-resume" style="font-family:${essay.font};font-size:${essay.fontSize}px;color:${essay.accentColor};border-color:${essay.mainColor};">
            <div class="resume-section">
                <div class="resume-section-title">${sec.data.title || ''}</div>
                <div style="white-space:pre-wrap; line-height:1.5;">${sec.data.body || ''}</div>
            </div>
        </div>`;
    }

    function createCard(key, value, metaData, type) {
        const card = document.createElement('div');
        card.className = 'portfolio-dashboard-card';
        const preview = document.createElement('div');
        preview.className = 'portfolio-dashboard-preview';

        let htmlForPreview = value;
        try {
            const obj = JSON.parse(value);
            if (type === 'dessay') htmlForPreview = renderEssayHTML(obj);
            else htmlForPreview = renderResumeHTML(obj);
        } catch(e) {
            htmlForPreview = value;
        }
        preview.innerHTML = '<div class="portfolio-dashboard-preview-empty"></div>';
        setTimeout(() => renderPreview(preview, htmlForPreview), 50);

        const main = document.createElement('div');
        main.className = 'portfolio-dashboard-card-main';
        const typeName = getTypeName(type);
        const typeIcon = getTypeIcon(type);
        const typeEl = document.createElement('div');
        typeEl.className = 'portfolio-dashboard-card-type';
        typeEl.innerHTML = typeIcon + ' ' + typeName;
        const titleRow = document.createElement('div');
        titleRow.className = 'portfolio-dashboard-card-title-row';
        const title = document.createElement('div');
        title.className = 'portfolio-dashboard-card-title';
        title.textContent = metaData?.name || 'Name';
        const editBtn = document.createElement('button');
        editBtn.className = 'portfolio-dashboard-card-edit-btn';
        editBtn.innerHTML = '<i class="fa fa-pen"></i>';
        editBtn.onclick = function(e) {
            e.stopPropagation();
            if (title.querySelector('input')) return;
            const input = document.createElement('input');
            input.type = 'text';
            input.value = meta[key]?.name || '';
            input.style.fontSize = '18px';
            input.style.fontWeight = '700';
            input.style.width = '180px';
            input.style.marginRight = '10px';
            input.style.outline = 'none';
            input.style.border = 'none';
            input.onkeydown = function(ev) {
                if (ev.key === 'Enter') finishEdit();
                if (ev.key === 'Escape') cancelEdit();
            };
            input.onblur = finishEdit;
            title.innerHTML = '';
            title.appendChild(input);
            input.focus();
            function finishEdit() {
                const newName = input.value.trim();
                if (newName) {
                    meta[key].name = newName;
                    title.textContent = newName;
                } else {
                    title.textContent = meta[key]?.name || '';
                }
            }
            function cancelEdit() {
                title.textContent = meta[key]?.name || '';
            }
        };
        titleRow.appendChild(title);
        titleRow.appendChild(editBtn);
        const metaEl = document.createElement('div');
        metaEl.className = 'portfolio-dashboard-card-meta';
        metaEl.textContent = 'Edited ' + (metaData?.edited || 'recently');
        const actions = document.createElement('div');
        actions.className = 'portfolio-dashboard-card-actions';
        const btnEdit = document.createElement('button');
        btnEdit.className = 'portfolio-dashboard-card-action-btn';
        btnEdit.innerHTML = '<i class="fa fa-pen"></i> Edit';
        btnEdit.onclick = function(e) {
            e.stopPropagation();
            window.location.href = '/documents/' + key;
        };
        const btnDup = document.createElement('button');
        btnDup.className = 'portfolio-dashboard-card-action-btn';
        btnDup.innerHTML = '<i class="fa fa-copy"></i> Duplicate';
        btnDup.onclick = function(e) {
            e.stopPropagation();
            duplicateDocument(key, type);
        };
        const btnDownload = document.createElement('button');
        btnDownload.className = 'portfolio-dashboard-card-action-btn';
        btnDownload.innerHTML = '<i class="fa fa-download"></i> Download';
        btnDownload.onclick = function(e) {
            e.stopPropagation();
            downloadDocument(key, value, type);
        };
        const btnDelete = document.createElement('button');
        btnDelete.className = 'portfolio-dashboard-card-action-btn delete';
        btnDelete.innerHTML = '<i class="fa fa-trash"></i> Delete';
        btnDelete.onclick = function(e) {
            e.stopPropagation();
            deleteDocument(key, type);
        };
        actions.appendChild(btnEdit);
        actions.appendChild(btnDup);
        actions.appendChild(btnDownload);
        actions.appendChild(btnDelete);
        const footer = document.createElement('div');
        footer.className = 'portfolio-dashboard-card-footer';
        footer.innerHTML = '';
        main.appendChild(typeEl);
        main.appendChild(titleRow);
        main.appendChild(metaEl);
        main.appendChild(actions);
        main.appendChild(footer);
        card.appendChild(preview);
        preview.onclick = function(e) {
            e.stopPropagation();
            showPreviewModal(key, value, type);
        };
        card.appendChild(main);
        return card;
    }

    function createAddCard() {
        const card = document.createElement('div');
        card.className = 'portfolio-dashboard-add-card';
        card.innerHTML = `<i class="fa fa-plus"></i>
            <div class="portfolio-dashboard-add-card-label">New Document</div>
            <div style="font-size:14px;color:#8b98b8;">Choose: Portfolio or Essay</div>`;
        card.onclick = function() {
            const choice = confirm('OK — create Portfolio, Cancel — create Motivation Essay');
            if (choice) createPortfolio();
            else createEssay();
        };
        return card;
    }

    function renderPreview(container, html) {
        container.innerHTML = '';
        const temp = document.createElement('div');
        temp.style.width = '220mm';
        temp.style.height = '285mm';
        temp.style.overflow = 'hidden';
        temp.innerHTML = html;
        temp.style.position = 'absolute';
        temp.style.left = '-9999px';
        temp.style.top = '-9999px';
        document.body.appendChild(temp);
        html2canvas(temp, {backgroundColor: '#fff', scale: 0.25}).then(canvas => {
            container.appendChild(canvas);
            document.body.removeChild(temp);
        }).catch(() => {
            container.innerHTML = '<div class="portfolio-dashboard-preview-empty"></div>';
            document.body.removeChild(temp);
        });
    }

    function downloadDocument(key, value, type) {
        let html = value;
        try {
            if (type === 'dessay') html = renderEssayHTML(JSON.parse(value));
            else html = renderResumeHTML(JSON.parse(value));
        } catch(e){ html = value; }

        const temp = document.createElement('div');
        temp.style.width = '210mm';
        temp.style.minHeight = '297mm';
        temp.style.background = '#fff';
        temp.style.padding = '17mm 15mm';
        temp.style.position = 'absolute';
        temp.style.left = '-9999px';
        temp.style.top = '-9999px';
        temp.innerHTML = html;
        document.body.appendChild(temp);
        html2canvas(temp, {backgroundColor: '#fff', scale: 2}).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'pt', 'a4');
            const pageWidth = pdf.internal.pageSize.getWidth();
            const imgWidth = pageWidth;
            const imgHeight = canvas.height * imgWidth / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
            pdf.save((meta[key]?.name || key) + '.pdf');
            document.body.removeChild(temp);
        }).catch(() => {
            alert('Error generating PDF');
            document.body.removeChild(temp);
        });
    }

    function duplicateDocument(key, type) {
        const newKey = key.replace(/\d+$/, '') + (Math.floor(Math.random()*100000));
        const value = documents[key];
        const actionName = (type === 'dessay') ? 'save_essay' : 'save_portfolio';
        const bodyKey = (type === 'dessay') ? 'essay_key' : 'portfolio_key';
        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({action: actionName, [bodyKey]: newKey, content: value})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                documents[newKey] = value;
                docTypes[newKey] = type;
                meta[newKey] = {...meta[key], name: meta[key].name + ' (Copy)'};
                renderCards();
            } else {
                alert('Error duplicating');
            }
        });
    }

    function deleteDocument(key, type) {
        const actionName = (type === 'dessay') ? 'delete_essay' : 'delete_portfolio';
        const bodyKey = (type === 'dessay') ? 'essay_key' : 'portfolio_key';
        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({action: actionName, [bodyKey]: key})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                delete documents[key];
                delete docTypes[key];
                renderCards();
            } else {
                alert('Error deleting');
            }
        });
    }

    window.portfolioTemplates = <?php echo json_encode([]); ?>; // не используем здесь

    function generateDocId() {
        return btoa(Array.from(crypto.getRandomValues(new Uint8Array(16)), b => String.fromCharCode(b)).join(''))
            .replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
    }

    function createPortfolio() {
        let templateIdx = 0;
        const template = {
            template: 0,
            font: "Rubik",
            fontSize: 16,
            mainColor: "#0078d4",
            accentColor: "#222222",
            columns: 2,
            sections: [
                {type: "header", data: {name: "YOUR NAME", contacts: "Phone | Email | LinkedIn/Portfolio | Location", avatar: ""}},
                {type: "awards", data: {title: "AWARDS", items: ["Award 1", "Award 2"]}},
                {type: "projects", data: {title: "Projects", items: [{ title: "Project 1", desc: "Description 1" }, { title: "Project 2", desc: "Description 2" }]}},
                {type: "summary", data: {title: "SUMMARY", text: "Description"}},
                {type: "achievements", data: {title: "Achievements", items: [{ title: "Achievement 1", desc: "Description 1" }, { title: "Achievement 2", desc: "Description 2" }]}},
                {type: "skills", data: {title: "SKILLS", items: ["Your Skill"]}},
                {type: "education", data: {title: "EDUCATION", items: [{degree: "Bachelor of Science", school: "MIT", major: "Computer Science", gpa: "4.0", date: "2018–2022"}]}}
            ]
        };
        const content = JSON.stringify(template);
        const key = generateDocId();
        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({action: 'save_portfolio', portfolio_key: key, content: content})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) window.location.href = '/documents/' + key;
            else alert('Error creating portfolio');
        });
    }

    function createEssay() {
        const content = JSON.stringify({
            template: 0,
            font: "Rubik",
            fontSize: 13,
            mainColor: "#0078d4",
            accentColor: "#222222",
            sections: [
                {type: "essay", data: {title: "My Motivation", body: "Here goes your motivation letter text."}}
            ]
        });
        const key = generateDocId();
        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({action: 'save_essay', essay_key: key, content: content})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) window.location.href = '/documents/' + key;
            else alert('Error creating essay');
        });
    }

    function showPreviewModal(key, value, type) {
        const modal = document.createElement('div');
        modal.style.position = 'fixed';
        modal.style.left = '0'; modal.style.top = '0';
        modal.style.width = '100vw'; modal.style.height = '100vh';
        modal.style.background = 'rgba(40,49,79,0.15)';
        modal.style.zIndex = '9999';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        const box = document.createElement('div');
        box.style.background = '#fff';
        box.style.borderRadius = '16px';
        box.style.padding = '32px';
        box.style.maxWidth = '90vw';
        box.style.maxHeight = '90vh';
        box.style.overflow = 'auto';
        try {
            if (type === 'dessay') box.innerHTML = renderEssayHTML(JSON.parse(value));
            else box.innerHTML = renderResumeHTML(JSON.parse(value));
        } catch(e) { box.innerHTML = value; }
        modal.appendChild(box);
        modal.onclick = function(e) {
            if (e.target === modal) document.body.removeChild(modal);
        };
        document.body.appendChild(modal);
    }

    // Tabs
    tabEls.forEach(tab => {
        tab.onclick = function() {
            tabEls.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            currentTab = tab.dataset.tab;
            renderCards();
        };
    });
    // View switch
    viewGridBtn.onclick = function() {
        currentView = 'grid';
        viewGridBtn.classList.add('active');
        viewListBtn.classList.remove('active');
        renderCards();
    };
    viewListBtn.onclick = function() {
        currentView = 'list';
        viewListBtn.classList.add('active');
        viewGridBtn.classList.remove('active');
        renderCards();
    };
    // Search
    searchInput.oninput = function() {
        searchValue = searchInput.value;
        renderCards();
    };
    // Create new
    createBtn.onclick = function() {
        const choice = confirm('OK — create Portfolio, Cancel — create Motivation Essay');
        if (choice) createPortfolio(); else createEssay();
    };
    // INIT
    loadDocuments();
})();
</script>
<?php
        }?>
<?php    }    return ob_get_clean();
});