<?php

add_action('wp_ajax_cp_upload_rec_letter', function () {
    if (!is_user_logged_in())
        wp_send_json_error(['msg' => 'Not logged in']);
    $current_user_id = get_current_user_id();
    $hash = sanitize_text_field($_POST['username'] ?? '');
    $profile_user_id = cp_get_user_by_hash($hash);

    if (!$profile_user_id || $profile_user_id != $current_user_id) {
        wp_send_json_error(['msg' => 'Permission denied']);
    }

    if (empty($_FILES['file'])) {
        wp_send_json_error(['msg' => 'No file']);
    }

    $file = $_FILES['file'];
    $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        wp_send_json_error(['msg' => 'File type not allowed']);
    }

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    $upload = wp_handle_upload($file, ['test_form' => false]);
    if (!empty($upload['error'])) {
        wp_send_json_error(['msg' => $upload['error']]);
    }

    wp_send_json_success(['url' => $upload['url']]);
});

add_action('wp_ajax_cp_delete_rec_letter', function () {
    if (!is_user_logged_in())
        wp_send_json_error(['msg' => 'Not logged in']);
    $current_user_id = get_current_user_id();
    $hash = sanitize_text_field($_POST['username'] ?? '');
    $profile_user_id = cp_get_user_by_hash($hash);

    if (!$profile_user_id || $profile_user_id != $current_user_id) {
        wp_send_json_error(['msg' => 'Permission denied']);
    }

    $file_url = esc_url_raw($_POST['file_url'] ?? '');
    if (!$file_url)
        wp_send_json_error(['msg' => 'No file specified']);

    // Преобразуем URL в путь на сервере
    $upload_dir = wp_upload_dir();
    if (strpos($file_url, $upload_dir['baseurl']) !== 0) {
        wp_send_json_error(['msg' => 'Invalid file']);
    }
    $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $file_url);

    if (file_exists($file_path)) {
        @unlink($file_path);
    }

    wp_send_json_success();
});

add_action('wp_ajax_save_college_profile', function () {
    if (!is_user_logged_in())
        wp_send_json_error(['msg' => 'Not logged in']);

    $current_user_id = get_current_user_id();
    $hash = sanitize_text_field($_POST['username'] ?? '');
    $profile_user_id = cp_get_user_by_hash($hash);

    if (!$profile_user_id || $profile_user_id != $current_user_id) {
        wp_send_json_error(['msg' => 'Permission denied']);
    }
    $data = json_decode(stripslashes($_POST['data'] ?? ''), true);    if (!is_array($data) || empty($data)) {
        $data = [
            'colleges' => [
                'usa' => [],
                'intl' => []
            ],
            'checklist' => [
                'sat_act' => [
                    'checked' => false,
                    'sat' => '',
                    'act' => ''
                ],
                'rec_letters' => [
                    'checked' => false,
                    'detail' => ''
                ],
                'personal_statement' => [
                    'checked' => false,
                    'detail' => ''
                ],
                'ap_alevel' => [
                    'checked' => false,
                    'subjects' => []
                ],
                'major' => [
                    'checked' => false,
                    'detail' => ''
                ],
                'english_test' => [
                    'checked' => false,
                    'ielts' => '',
                    'toefl' => '',
                    'duolingo' => ''
                ]
            ],
            'extracurricular' => [],
            'gpa' => []        ];    }
    update_user_meta($current_user_id, 'college_profile_data', $data);
    wp_send_json_success();

    wp_send_json_success();
});

function cp_get_user_by_hash($hash)
{
    global $wpdb;
    $user_id = $wpdb->get_var($wpdb->prepare(
        "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'um_user_profile_url_slug_hash' AND meta_value = %s",
        $hash
    ));
    return $user_id ? intval($user_id) : false;
}

add_shortcode('college_profile', 'college_profile_shortcode');
function college_profile_shortcode()
{
    // Получаем хэш из URL
    $url = $_SERVER['REQUEST_URI'];
    if (!preg_match('/user\/([^\/]+)/', $url, $matches)) {
        return '<div class="cp-error">Profile not found.</div>';
    }
    $hash = sanitize_text_field($matches[1]);
    $profile_user_id = cp_get_user_by_hash($hash);
    if (!$profile_user_id) {
        return '<div class="cp-error">User not found.</div>';
    }

    $current_user_id = get_current_user_id();

    $is_owner = ($current_user_id == $profile_user_id);

    // Загружаем данные профиля из usermeta    $data = get_user_meta($profile_user_id, 'college_profile_data', true);    if (!is_array($data) || empty($data)) {
        $data = [
            'colleges' => [
                'usa' => [],
                'intl' => []
            ],
            'checklist' => [
                'sat_act' => [
                    'checked' => false,
                    'sat' => '',
                    'act' => ''
                ],
                'rec_letters' => [
                    'checked' => false,
                    'detail' => ''
                ],
                'personal_statement' => [
                    'checked' => false,
                    'detail' => ''
                ],
                'ap_alevel' => [
                    'checked' => false,
                    'subjects' => []
                ],
                'major' => [
                    'checked' => false,
                    'detail' => ''
                ],
                'english_test' => [
                    'checked' => false,
                    'ielts' => '',
                    'toefl' => '',
                    'duolingo' => ''
                ]
            ],
            'extracurricular' => [],
            'gpa' => []        ];
        // Сохраняем дефолт сразу, если это владелец профиля
        if ($is_owner)
            update_user_meta($profile_user_id, 'college_profile_data', $data);    }

    ob_start();
?>
    <div id="college-profile-root" class="cp-root" data-owner="<?php echo $is_owner ? '1' : '0'; ?>">
        <input type="hidden" id="cp-username" value="<?php echo esc_attr($hash); ?>">
        <div class="cp-tabs">
            <div class="cp-tab active" data-tab="college-list">College List</div>
            <div class="cp-tab" data-tab="checklist" >Checklist</div>
            <div class="cp-tab" data-tab="extracurricular">Extracurricular</div>
            <div class="cp-tab" data-tab="gpa">Transcript & GPA</div>
        </div>
        <div class="cp-tab-content" id="cp-tab-college-list">
            <div class="cp-college-lists">
                <div class="cp-college-list-block">
                    <b>USA</b>
                    <div class="cp-college-list" data-list="usa"></div>
                    <?php if ($is_owner): ?>
                        <div class="cp-add-college" data-list="usa">+ Add University</div>
                    <?php
    endif; ?>
                </div>
                <div class="cp-college-list-block">
                    <b>Outside USA</b>
                    <div class="cp-college-list" data-list="intl"></div>
                    <?php if ($is_owner): ?>
                        <div class="cp-add-college" data-list="intl">+ Add University</div>
                    <?php
    endif; ?>
                </div>
            </div>
        </div>
        <div class="cp-tab-content" id="cp-tab-checklist" style="display:none;">
<div class="cp-checklist"></div>
        </div>
        <div class="cp-tab-content" id="cp-tab-extracurricular" style="display:none;">
            <div class="cp-extracurricular-list"></div>
            <?php if ($is_owner): ?>
                <div class="cp-add-extracurricular">+ Add Activity</div>
            <?php
    endif; ?>
        </div>
        <div class="cp-tab-content" id="cp-tab-gpa" style="display:none;">
            <div class="cp-gpa-table-wrap">
                <table class="cp-gpa-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Grade</th>
                            <?php if ($is_owner): ?><th></th><?php
    endif; ?>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <?php if ($is_owner): ?>
                    <div class="cp-add-subject">+ Add Subject</div>
                <?php
    endif; ?>
            </div>
            <div class="cp-gpa-results">
                <div>GPA (4.0 scale): <span class="cp-gpa-4"></span></div>
                <div>GPA (5.0 scale): <span class="cp-gpa-5"></span></div>
            </div>
        </div>
        <input type="hidden" id="cp-initial-data" value='<?php echo esc_attr(json_encode($data)); ?>'>
    </div>
    <?php
    cp_enqueue_college_profile_assets($is_owner);
    return ob_get_clean();
}

// === 2. Стили и JS ===
function cp_enqueue_college_profile_assets($is_owner)
{
?>
    <style>
    .cp-root { max-width: 1100px; margin: 10px auto 70px auto; font-family: 'Inter', Arial, sans-serif;}
    .cp-tabs { display: flex; gap: 32px; border-bottom: 2px solid #e5eaf3; margin-bottom: 5px;}
    .cp-tab { font-size: 18px; color: #8b98b8;font-weight: 500; padding: 18px 0px 12px 0px; cursor: pointer; border-bottom: 2px solid transparent; transition: color .2s, border .2s;}
    .cp-tab.active { color: #5f4dee; border-bottom: 2px solid #5f4dee;}
    .cp-tab-content { animation: fadein .3s;}
    @keyframes fadein { from { opacity: 0; } to { opacity: 1; } }
    .cp-college-lists { display: flex; gap: 40px; }
    .cp-college-list-block { background: #fff; border-radius: 14px; padding: 10px 24px 24px 24px; flex: 1; box-shadow: 0 2px 8px rgba(108,76,244,0.02);}
    .cp-college-list-block b{
        font-size: 20px;
        padding-left: 5px;
    }
.cp-college-card-header input{ padding-left: 5px !important; font-size: 17px !important;}
    .cp-college-list { margin-bottom: 16px;}
    .cp-college-card { background: #f7f8fa; border-radius: 5px; padding: 0px 38px 0px 18px; margin-bottom: 12px; position: relative; box-shadow: 0 2px 8px rgba(108,76,244,0.02);}
    .cp-college-card input, .cp-college-card select { width: 100%; margin-bottom: 7px; padding: 7px 10px; border-radius: 5px; border: 1px solid #8b98b8; background: #fff; font-size: 1rem;}
    .cp-college-card .cp-remove-college { position: absolute; top: 12px; right: 12px; color: #f44; background: none; border: none; font-size: 1.1rem; cursor: pointer;}
    .cp-add-college, .cp-add-extracurricular, .cp-add-subject { background: #5f4dee; color: #fff; border: none; border-radius: 7px; padding: 7px 18px; font-size: 16px; cursor: pointer; margin-top: 8px;}
		.cp-add-apalevel { background: #5f4dee; color: #fff; border: none; border-radius: 7px; padding: 5px 18px; font-size: 15px; cursor: pointer; margin-top: 8px; width: 290px}
    .cp-checklist { margin-top: 18px;}
    .cp-checklist-item { display: flex; align-items: center; gap: 18px; margin-bottom: 14px;}
    .cp-checklist-checkbox { width: 18px; height: 18px; accent-color: #5f4dee;}
    .cp-checklist-detail { flex: 1; padding: 7px 10px; border-radius: 7px; border: 1px solid #8b98b8; background: #fff; font-size: 1rem;}
    .cp-checklist-toggle { background: none; border: none; font-size: 16px; color: #5f4dee; cursor: pointer;  }
    .cp-checklist-expand { margin-top: -8px; margin-left: 38px; margin-bottom: 12px; }
    .cp-checklist-detail-expand { padding: 5px 8px; border-radius: 5px; border: 1px solid #8b98b8; background: #fff; font-size: 0.9rem;}
    .cp-apalevel-list { margin-bottom: 8px; }
    .cp-apalevel-row { display: flex; gap: 10px; align-items: center; margin-bottom: 7px;}
    .cp-apalevel-row input { padding: 5px 8px; border-radius: 5px; border: 1px solid #8b98b8; background: #fff; font-size: 0.9rem;}
    .cp-remove-apalevel { color: #f44; background: none; border: none; font-size: 1.2em; cursor: pointer;}
		#cp-tab-extracurricular{padding: 5px 15px 0px 15px;}
    .cp-extracurricular-list { margin-top: 10px; margin-bottom: 14px;}
    .cp-extracurricular-card { background: #f7f8fa; border-radius: 5px; padding: 0px 38px 0px 18px; margin-bottom: 12px; position: relative; box-shadow: 0 2px 8px rgba(108,76,244,0.02);}
    .cp-extracurricular-card input, .cp-extracurricular-card textarea { width: 100%; margin-bottom: 7px; padding: 7px 10px; border-radius: 5px; border: 1px solid #8b98b8; background: #fff; font-size: 1rem;}
.cp-extracurricular-card textarea {    resize: none; /* Запретить ручное изменение размера */
    overflow-y: hidden; /* Скрыть вертикальный скролл */
    min-height: 70px; /* Минимальная высота, можно настроить */
}
    .cp-extracurricular-card .cp-remove-extracurricular { position: absolute; top: 10px; right: 15px; color: #f44; background: none; border: none; font-size: 1.1rem; cursor: pointer;}
		#cp-tab-gpa{padding: 0px 10px 0px 10px;}
		#cp-tab-checklist{padding: 0px 10px 0px 10px;}
    .cp-gpa-table-wrap { margin-top: 10px; margin-bottom: 18px;}
    .cp-gpa-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 5px; overflow: hidden;}
    .cp-gpa-table th, .cp-gpa-table td { padding: 10px 12px; border-bottom: 1px solid #8b98b8;}
    .cp-gpa-table th { background: #f7f8fa; font-weight: 600;}
		    .cp-gpa-table td { text-align: center;}
    .cp-gpa-table td:last-child { text-align: right;}
    .cp-remove-subject { color: #f44; background: none; border: none; font-size: 1.1rem; cursor: pointer;}
    .cp-gpa-results { font-size: 1.1rem; font-weight: 600; color: #5f4dee;}
    .cp-error { color: #f44; font-weight: 600; margin: 30px 0;}
    @media (max-width: 900px) {
        .cp-college-lists { flex-direction: column; gap: 20px;}
        .cp-root { padding: 10px;}
    }
    .cp-college-card-header input[type="text"] {
        font-weight: 600;
        font-size: 1.08rem;
        border: none;
        background: transparent;
        border-bottom: 1.5px solid #e5eaf3;
        border-radius: 0;
        padding: 7px 0;
        transition: border .2s;
    }
    .cp-college-card-header input[type="text"]:focus {
        border-bottom: 1.5px solid #5f4dee;
        outline: none;
        background: #f7f8fa;
    }
    .cp-college-card-header {
        background: #f7f8fa;
        border-radius: 5px 5px 0 0;
        padding: 10px 10px 10px 0;
    }
    .cp-college-card-header input[type="text"] {
        margin-bottom: 0;
    }
		@media (max-width: 600px) {
    .cp-root {
        padding: 0 2vw;
        max-width: 100vw;
        font-size: 15px;
    }
    .cp-tabs {
        gap: 8px;
        flex-wrap: wrap;
        font-size: 15px;
    }
    .cp-tab {
        font-size: 15px;
        padding: 12px 0 8px 0;
        min-width: 90px;
        text-align: center;
        flex: 1 1 40%;
        box-sizing: border-box;
    }
    .cp-college-lists {
        flex-direction: column;
        gap: 12px;
    }
    .cp-college-list-block {
        padding: 8px 6px 14px 6px;
        border-radius: 8px;
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
    }
    .cp-college-list-block b {
        font-size: 17px;
        padding-left: 2px;
    }
    .cp-college-card,
    .cp-extracurricular-card {
        padding: 0 4px 0 4px;
        font-size: 15px;
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
    }
    .cp-college-card-header,
    .cp-extracurricular-card .cp-college-card-header {
        padding: 8px 0 8px 0;
        gap: 6px;
        width: 100%;
        box-sizing: border-box;
    }
    .cp-college-card input,
    .cp-college-card select,
    .cp-extracurricular-card input,
    .cp-extracurricular-card textarea {
        font-size: 15px;
        padding: 6px 7px;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 7px;
    }
    .cp-add-college,
    .cp-add-extracurricular,
    .cp-add-subject,
    .cp-add-apalevel {
        font-size: 15px;
        padding: 10px 0;
        width: 100%;
        margin-top: 8px;
        border-radius: 6px;
        box-sizing: border-box;
        display: block;
        text-align: center;
    }
    .cp-checklist-detail,
    .cp-checklist-detail-expand {
        font-size: 15px;
        padding: 6px 7px;
        width: 100%;
        box-sizing: border-box;
    }
    .cp-apalevel-row input {
        font-size: 15px;
        width: 48vw;
        min-width: 70px;
        max-width: 100%;
        box-sizing: border-box;
    }
    .cp-apalevel-row {
        gap: 6px;
    }
    .cp-gpa-table th,
    .cp-gpa-table td {
        padding: 7px 4px;
        font-size: 15px;
    }
    .cp-gpa-table {
        font-size: 15px;
    }
    .cp-gpa-results {
        font-size: 15px;
    }
    .cp-error {
        font-size: 15px;
    }
    .cp-college-card-header input[type="text"] {
        font-size: 15px;
        padding: 6px 0;
        width: 100%;
        box-sizing: border-box;
    }
    #cp-tab-extracurricular,
    #cp-tab-gpa,
    #cp-tab-checklist {
        padding: 0 2vw;
    }
    /* Кнопки удаления и сворачивания */
    .cp-remove-college,
    .cp-remove-extracurricular,
    .cp-remove-apalevel,
    .cp-remove-subject {
        font-size: 1.3em;
        margin-left: 6px;
        margin-right: 0;
        padding: 0 6px;
        vertical-align: middle;
    }
    .cp-college-card-header button,
    .cp-extracurricular-card .cp-college-card-header button {
        min-width: 36px;
        min-height: 36px;
        padding: 0;
        margin: 0 0 0 4px;
        font-size: 1.2em;
        box-sizing: border-box;
    }
    /* Убираем горизонтальный скролл */
    html, body {
        overflow-x: hidden;
    }
}
		
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let data = {};
        try { data = JSON.parse(document.getElementById('cp-initial-data').value); } catch(e) { data = {}; }
        if (!data.colleges) data.colleges = { usa: [], intl: [] };
        if (!data.extracurricular) data.extracurricular = [];
        if (!data.gpa) data.gpa = [];
        if (!data.checklist) data.checklist = {};
        if (!data.checklist['ap_alevel']) data.checklist['ap_alevel'] = {};
        if (!data.checklist['ap_alevel']['subjects']) data.checklist['ap_alevel']['subjects'] = [];

        // Ensure SAT/ACT and English test fields exist
        if (!data.checklist['sat_act']) data.checklist['sat_act'] = {};
        if (!data.checklist['english_test']) data.checklist['english_test'] = {};

        ['usa', 'intl'].forEach(list => {
    (data.colleges[list]||[]).forEach(college => {
        college._expanded = false;
    });
});
		(data.extracurricular||[]).forEach(act => {
    act._expanded = false;
});
		const isOwner = document.getElementById('college-profile-root').dataset.owner === "1";

        // --- TABS ---
        document.querySelectorAll('.cp-tab').forEach(tab => {
            tab.onclick = function() {
                document.querySelectorAll('.cp-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.cp-tab-content').forEach(c => c.style.display = 'none');
                tab.classList.add('active');
                document.getElementById('cp-tab-' + tab.dataset.tab).style.display = '';
            };
            tab.onkeydown = function(e) {
                if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();
                    tab.click();
                }
            };
        });
		
		let autosaveTimer = null;
function autosave() {
    if (!isOwner) return;
    if (autosaveTimer) clearTimeout(autosaveTimer);
    autosaveTimer = setTimeout(() => {
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'action=save_college_profile'
                + '&username=' + encodeURIComponent(document.getElementById('cp-username').value)
                + '&data=' + encodeURIComponent(JSON.stringify(data))
        }).then(r=>r.json()).then(res=>{
            // Можно добавить визуальный индикатор "Сохранено"
        });
    }, 300); // 700 мс после последнего изменения
}
		
        // --- COLLEGE LIST ---
        function renderCollegeList() {
            ['usa','intl'].forEach(list => {
                const container = document.querySelector('.cp-college-list[data-list="'+list+'"]');
                container.innerHTML = '';
                (data.colleges[list]||[]).forEach((college, idx) => {
                    if (typeof college._expanded === 'undefined') college._expanded = true;

                    const card = document.createElement('div');
                    card.className = 'cp-college-card';

                    // --- Заголовок карточки ---
                    const header = document.createElement('div');
                    header.className = 'cp-college-card-header';
                    header.style.display = 'flex';
                    header.style.alignItems = 'center';
                    header.style.gap = '10px';
                    header.style.marginBottom = college._expanded ? '12px' : '0';

                    // Поле ввода названия
                    const nameInput = document.createElement('input');
                    nameInput.type = 'text';
                    nameInput.placeholder = 'University Name';
                    nameInput.value = college.name || '';
                    nameInput.dataset.field = 'name';
                    nameInput.style.flex = '1';
                    nameInput.readOnly = !isOwner;
                    nameInput.oninput = function() {
                        data.colleges[list][idx].name = nameInput.value;
						autosave();
                    };

                    // Кнопка сворачивания/разворачивания
                    const toggleBtn = document.createElement('button');
                    toggleBtn.type = 'button';
                    toggleBtn.innerHTML = college._expanded ? '&#9650;' : '&#9660;'; // ▲ ▼
                    toggleBtn.title = college._expanded ? 'Collapse' : 'Expand';
                    toggleBtn.style.background = 'none';
                    toggleBtn.style.border = 'none';
                    toggleBtn.style.fontSize = '1.3em';
                    toggleBtn.style.cursor = 'pointer';
                    toggleBtn.style.color = '#5f4dee';
                    toggleBtn.onclick = function(e) {
                        e.stopPropagation();
                        college._expanded = !college._expanded;
                        renderCollegeList();
                    };

                    // Кнопка удаления
                    let removeBtn = null;
                    if (isOwner) {
                        removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.innerHTML = '&#10005;';
                        removeBtn.title = 'Remove';
                        removeBtn.className = 'cp-remove-college';
                        removeBtn.style.background = 'none';
                        removeBtn.style.border = 'none';
                        removeBtn.style.fontSize = '1.2em';
                        removeBtn.style.color = '#f44';
                        removeBtn.style.marginLeft = '10px';
                        removeBtn.style.cursor = 'pointer';
                        removeBtn.onclick = function(e) {
                            e.stopPropagation();
                            data.colleges[list].splice(idx,1);
                            renderCollegeList();
							autosave();
                        };
                    }

                    header.appendChild(nameInput);
                    header.appendChild(toggleBtn);
                    if (removeBtn) header.appendChild(removeBtn);

                    card.appendChild(header);

                    // --- Содержимое карточки ---
                    if (college._expanded) {
                        const content = document.createElement('div');
                        content.className = 'cp-college-card-content';
                        content.innerHTML = `
                            <input type="text" placeholder="Application Type/Round" value="${college.round||''}" data-field="round" ${!isOwner?'readonly':''}>
                            <input type="text" placeholder="Deadline (YYYY-MM-DD)" value="${college.deadline||''}" data-field="deadline" ${!isOwner?'readonly':''}>
                            <input type="text" placeholder="Acceptance Rate (%)" value="${college.acceptance||''}" data-field="acceptance" ${!isOwner?'readonly':''}>
                            <input type="text" placeholder="Financial Aid (available)" value="${college.financial_aid||''}" data-field="financial_aid" ${!isOwner?'readonly':''}>
                            <input type="text" placeholder="Financial Aid (applied for)" value="${college.financial_aid_applied||''}" data-field="financial_aid_applied" ${!isOwner?'readonly':''}>
                            <input type="text" placeholder="Faculty/Major" value="${college.major||''}" data-field="major" ${!isOwner?'readonly':''}>
                            <input type="text" placeholder="Accepted Exams" value="${college.exams||''}" data-field="exams" ${!isOwner?'readonly':''}>
                        `;
                        if (isOwner) {
                            content.querySelectorAll('input').forEach(inp => {
                                inp.oninput = function() {
                                    data.colleges[list][idx][inp.dataset.field] = inp.value;
									autosave();
                                }
                            });
                        }
                        card.appendChild(content);
                    }

                    container.appendChild(card);
                });
            });
        }
        renderCollegeList();

        if (isOwner) {
            document.querySelectorAll('.cp-add-college').forEach(btn => {
                btn.onclick = function() {
                    const list = btn.dataset.list;
                    data.colleges[list].push({});;
                    renderCollegeList();
					autosave();
                }
            });
        }

        // --- CHECKLIST ---
        // Сворачивание/разворачивание



function renderChecklist() {
    const checklist = [
        { key: 'sat_act', label: 'SAT / ACT', expandable: true },
        { key: 'rec_letters', label: 'Recommendation Letters', expandable: true },
        { key: 'personal_statement', label: 'Personal Statement' },
        { key: 'ap_alevel', label: 'AP / A-Level', expandable: true },
        { key: 'major', label: 'Major (chosen specialty)' },
        { key: 'english_test', label: 'IELTS / TOEFL / Duolingo', expandable: true }
    ];
    const container = document.querySelector('.cp-checklist');
    container.innerHTML = '';

    checklist.forEach(item => {
        // Создаём основной div пункта с чекбоксом и кнопкой
        const div = document.createElement('div');
        div.className = 'cp-checklist-item';
        div.dataset.key = item.key;

        // Чекбокс и метка
        const label = document.createElement('label');
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'cp-checklist-checkbox';
        checkbox.dataset.key = item.key;
        checkbox.checked = !!(data.checklist[item.key] && data.checklist[item.key].checked);
        checkbox.disabled = !isOwner;
        label.appendChild(checkbox);
        label.appendChild(document.createTextNode(' ' + item.label));
        div.appendChild(label);

        // Кнопка раскрытия, если expandable
        let toggleBtn = null;
        if (item.expandable) {
            toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'cp-checklist-toggle';
            toggleBtn.innerHTML = '&#9660;';
            toggleBtn.tabIndex = 0;
            toggleBtn.setAttribute('aria-label', 'Expand/Collapse');
            div.appendChild(toggleBtn);
        }

        // Добавляем основной пункт в контейнер
        container.appendChild(div);

        // Создаём раскрывающийся блок отдельно и добавляем после пункта
        if (item.expandable) {
            const expand = document.createElement('div');
            expand.className = 'cp-checklist-expand cp-checklist-expand-' + item.key;
            expand.style.display = 'none';

            if (item.key === 'rec_letters') {
                // Контент для Recommendation Letters
                const fileWrap = document.createElement('div');
                fileWrap.style.flex = '1';

                const fileList = document.createElement('div');
                fileList.className = 'cp-rec-letters-files';
                (data.checklist.rec_letters?.files || []).forEach((file, idx) => {
                    const fileDiv = document.createElement('div');
                    fileDiv.style.display = 'flex';
                    fileDiv.style.alignItems = 'center';
                    fileDiv.style.gap = '8px';
                    fileDiv.style.marginBottom = '4px';

                    const link = document.createElement('a');
                    link.href = file.url;
                    link.target = '_blank';
                    link.textContent = file.name || ('File ' + (idx+1));
                    fileDiv.appendChild(link);

                    if (isOwner) {
                        const delBtn = document.createElement('button');
                        delBtn.type = 'button';
                        delBtn.textContent = 'Delete';
                        delBtn.style.color = '#f44';
                        delBtn.style.background = 'none';
                        delBtn.style.border = 'none';
                        delBtn.style.cursor = 'pointer';
                        delBtn.onclick = function() {
                            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                                body: 'action=cp_delete_rec_letter'
                                    + '&username=' + encodeURIComponent(document.getElementById('cp-username').value)
                                    + '&file_url=' + encodeURIComponent(file.url)
                            })
                            .then(r => r.json())
                            .then(res => {
                                if (res.success) {
                                    data.checklist.rec_letters.files.splice(idx, 1);
                                    renderRecLettersFiles();
                                    autosave();
                                } else {
                                    alert('Delete failed: ' + (res.data?.msg || 'Unknown error'));
                                }
                            })
                            .catch(() => {
                                alert('Delete failed');
                            });
                        };
                        fileDiv.appendChild(delBtn);
                    }
                    fileList.appendChild(fileDiv);
                });
                fileWrap.appendChild(fileList);

                if (isOwner) {
                    const uploadInput = document.createElement('input');
                    uploadInput.type = 'file';
                    uploadInput.multiple = true;
                    uploadInput.accept = '.pdf,.jpg,.jpeg,.png,.doc,.docx';
                    uploadInput.style.marginTop = '5px';
                    uploadInput.onchange = function() {
                        if (!uploadInput.files.length) return;
                        Array.from(uploadInput.files).forEach(file => {
                            const formData = new FormData();
                            formData.append('action', 'cp_upload_rec_letter');
                            formData.append('file', file);
                            formData.append('username', document.getElementById('cp-username').value);

                            const loadingDiv = document.createElement('div');
                            loadingDiv.textContent = 'Uploading ' + file.name + '...';
                            fileList.appendChild(loadingDiv);

                            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                                method: 'POST',
                                credentials: 'same-origin',
                                body: formData
                            })
                            .then(r => r.json())
                            .then(res => {
                                loadingDiv.remove();
                                if (res.success && res.data && res.data.url) {
                                    if (!data.checklist.rec_letters) data.checklist.rec_letters = {};
                                    if (!data.checklist.rec_letters.files) data.checklist.rec_letters.files = [];
                                    data.checklist.rec_letters.files.push({
                                        url: res.data.url,
                                        name: file.name
                                    });
                                    renderRecLettersFiles();
                                    autosave();
                                } else {
                                    alert('Upload failed: ' + (res.data?.msg || 'Unknown error'));
                                }
                            })
                            .catch(() => {
                                loadingDiv.remove();
                                alert('Upload failed');
                            });
                        });
                        uploadInput.value = '';
                    };
                    fileWrap.appendChild(uploadInput);
                }

                expand.appendChild(fileWrap);
            }
            else if (item.key === 'sat_act') {
                expand.innerHTML = `
                    <div style="display:flex;gap:10px;align-items:center;margin-bottom:7px;">
                        <label style="min-width:60px;">SAT:</label>
                        <input type="text" class="cp-checklist-detail-expand" data-key="sat_act_sat" placeholder="SAT Score" value="${data.checklist['sat_act']?.sat||''}" ${!isOwner?'readonly':''} style="width:120px; margin-left: -20px;">
                        <label style="min-width:60px;">ACT:</label>
                        <input type="text" class="cp-checklist-detail-expand" data-key="sat_act_act" placeholder="ACT Score" value="${data.checklist['sat_act']?.act||''}" ${!isOwner?'readonly':''} style="width:120px; margin-left: -20px;">
                    </div>`;
                if (isOwner) {
                    expand.querySelectorAll('input').forEach(inp => {
                        inp.oninput = function() {
                            if (!data.checklist['sat_act']) data.checklist['sat_act'] = {};
                            if (inp.dataset.key === 'sat_act_sat') {
                                data.checklist['sat_act']['sat'] = inp.value;
                            } else if (inp.dataset.key === 'sat_act_act') {
                                data.checklist['sat_act']['act'] = inp.value;
                            }
                            autosave();
                        };
                    });
                }
            }
            else if (item.key === 'ap_alevel') {
                expand.innerHTML = `
                    <div class="cp-apalevel-list"></div>
                    ${isOwner?'<div class="cp-add-apalevel">+ Add Subject</div>':''}
                `;
                if (isOwner) {
                    setTimeout(renderAPAlevel, 100);
                }
            }
            else if (item.key === 'english_test') {
                expand.innerHTML = `
                    <div style="display:flex;gap:10px;align-items:center;margin-bottom:7px;">
                        <label style="min-width:60px;">IELTS:</label>
                        <input type="text" class="cp-checklist-detail-expand" data-key="english_test_ielts" placeholder="IELTS Score" value="${data.checklist['english_test']?.ielts||''}" ${!isOwner?'readonly':''} style="width:100px; margin-left: -5px;">
                        <label style="min-width:60px;">TOEFL:</label>
                        <input type="text" class="cp-checklist-detail-expand" data-key="english_test_toefl" placeholder="TOEFL Score" value="${data.checklist['english_test']?.toefl||''}" ${!isOwner?'readonly':''} style="width:100px;">
                        <label style="min-width:90px;">Duolingo:</label>
                        <input type="text" class="cp-checklist-detail-expand" data-key="english_test_duolingo" placeholder="Duolingo Score" value="${data.checklist['english_test']?.duolingo||''}" ${!isOwner?'readonly':''} style="width:100px; margin-left: -5px;">
                    </div>`;
                if (isOwner) {
                    expand.querySelectorAll('input').forEach(inp => {
                        inp.oninput = function() {
                            if (!data.checklist['english_test']) data.checklist['english_test'] = {};
                            if (inp.dataset.key === 'english_test_ielts') {
                                data.checklist['english_test']['ielts'] = inp.value;
                            } else if (inp.dataset.key === 'english_test_toefl') {
                                data.checklist['english_test']['toefl'] = inp.value;
                            } else if (inp.dataset.key === 'english_test_duolingo') {
                                data.checklist['english_test']['duolingo'] = inp.value;
                            }
                            autosave();
                        };
                    });
                }
            }

            container.appendChild(expand);

            // Обработчик toggleBtn для показа/скрытия
            if (toggleBtn) {
                toggleBtn.onclick = function() {
                    expand.style.display = expand.style.display === 'none' ? '' : 'none';
                    toggleBtn.innerHTML = expand.style.display === 'none' ? '&#9660;' : '&#9650;';
                };
            }
        }
        else {
            // Для пунктов без expandable добавляем поле деталей внутрь div
            const detail = document.createElement('input');
            detail.type = 'text';
            detail.className = 'cp-checklist-detail';
            detail.dataset.key = item.key;
            detail.placeholder = 'Details (optional)';
            detail.value = data.checklist[item.key]?.detail || '';
            detail.readOnly = !isOwner;
            detail.style.flex = '1';
            div.appendChild(detail);

            if (isOwner) {
                detail.oninput = function() {
                    if (!data.checklist[item.key]) data.checklist[item.key] = {};
                    data.checklist[item.key].detail = this.value;
                    autosave();
                };
            }
        }

        // Обработчик изменения чекбокса
        if (isOwner) {
            checkbox.onchange = function() {
                if (!data.checklist[item.key]) data.checklist[item.key] = {};
                data.checklist[item.key].checked = this.checked;
                autosave();
            };
        }
    });
}
		renderChecklist();
			function renderRecLettersFiles() {
    const fileList = document.querySelector('.cp-rec-letters-files');
    if (!fileList) return;
    fileList.innerHTML = '';
    (data.checklist.rec_letters?.files || []).forEach((file, idx) => {
        const fileDiv = document.createElement('div');
        fileDiv.style.display = 'flex';
        fileDiv.style.alignItems = 'center';
        fileDiv.style.gap = '8px';
        fileDiv.style.marginBottom = '4px';

        const link = document.createElement('a');
        link.href = file.url;
        link.target = '_blank';
        link.textContent = file.name || ('File ' + (idx+1));
        fileDiv.appendChild(link);

        if (isOwner) {
            const delBtn = document.createElement('button');
            delBtn.type = 'button';
            delBtn.textContent = 'Delete';
            delBtn.style.color = '#f44';
            delBtn.style.background = 'none';
            delBtn.style.border = 'none';
            delBtn.style.cursor = 'pointer';
            delBtn.onclick = function() {
                // Ваш код удаления файла, после успешного удаления вызывайте renderRecLettersFiles()
            };
            fileDiv.appendChild(delBtn);
        }
        fileList.appendChild(fileDiv);
    });
}
function renderAPAlevel() {
    let list = data.checklist['ap_alevel'] && data.checklist['ap_alevel']['subjects'] ? data.checklist['ap_alevel']['subjects'] : [];
    let container = document.querySelector('.cp-apalevel-list');
    if (!container) return;
    container.innerHTML = '';
    list.forEach((row, idx) => {
        let div = document.createElement('div');
        div.className = 'cp-apalevel-row';
        div.innerHTML = `
            <input type="text" placeholder="Subject" value="${row.subject||''}" data-field="subject" ${!isOwner?'readonly':''} style="width:180px;">
            <input type="text" placeholder="Grade" value="${row.grade||''}" data-field="grade" ${!isOwner?'readonly':''} style="width:100px;">
            ${isOwner?'<button type="button" class="cp-remove-apalevel" data-idx="'+idx+'">&#10005;</button>':''}
        `;
        if (isOwner) {
            div.querySelector('input[data-field="subject"]').oninput = function() {
                data.checklist['ap_alevel']['subjects'][idx].subject = this.value;
                autosave();
            };
            div.querySelector('input[data-field="grade"]').oninput = function() {
                data.checklist['ap_alevel']['subjects'][idx].grade = this.value;
                autosave();
            };
            div.querySelector('.cp-remove-apalevel').onclick = function() {
                data.checklist['ap_alevel']['subjects'].splice(idx,1);
                renderAPAlevel();
                autosave();
            };
        }
        container.appendChild(div);
    });

    // ВАЖНО: навешиваем обработчик на кнопку после рендера!
    if (isOwner) {
        let addBtn = document.querySelector('.cp-add-apalevel');
        if (addBtn) {
            addBtn.onclick = function() {
                if (!data.checklist['ap_alevel']) data.checklist['ap_alevel'] = {};
                if (!data.checklist['ap_alevel']['subjects']) data.checklist['ap_alevel']['subjects'] = [];
                data.checklist['ap_alevel']['subjects'].push({});
                renderAPAlevel();
                autosave();
            }
        }
    }
}
		renderAPAlevel();
        if (isOwner) {
            let addBtn = document.querySelector('.cp-add-apalevel');
            if (addBtn) {
                addBtn.onclick = function() {
                    if (!data.checklist['ap_alevel']) data.checklist['ap_alevel'] = {};
                    if (!data.checklist['ap_alevel']['subjects']) data.checklist['ap_alevel']['subjects'] = [];
                    data.checklist['ap_alevel']['subjects'].push({});
					renderChecklist();
					renderAPAlevel();
					autosave();
                }
            }
        }

        // --- EXTRACURRICULAR ---
function renderExtracurricular() {
    const container = document.querySelector('.cp-extracurricular-list');
    container.innerHTML = '';
    (data.extracurricular||[]).forEach((act, idx) => {
        if (typeof act._expanded === 'undefined') act._expanded = true;

        const card = document.createElement('div');
        card.className = 'cp-extracurricular-card';

        // --- Заголовок карточки ---
        const header = document.createElement('div');
        header.className = 'cp-college-card-header'; // Можно использовать общий стиль
        header.style.display = 'flex';
        header.style.alignItems = 'center';
        header.style.gap = '10px';
        header.style.marginBottom = act._expanded ? '12px' : '0';

        // Поле ввода названия активности
        const nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.placeholder = 'Activity/Club/Project/Competition/Volunteering';
        nameInput.value = act.name || '';
        nameInput.dataset.field = 'name';
        nameInput.style.flex = '1';
        nameInput.readOnly = !isOwner;
        nameInput.oninput = function() {
            data.extracurricular[idx].name = nameInput.value;
            autosave();
        };

        // Кнопка сворачивания/разворачивания
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.innerHTML = act._expanded ? '&#9650;' : '&#9660;'; // ▲ ▼
        toggleBtn.title = act._expanded ? 'Collapse' : 'Expand';
        toggleBtn.style.background = 'none';
        toggleBtn.style.border = 'none';
        toggleBtn.style.fontSize = '1.3em';
        toggleBtn.style.cursor = 'pointer';
        toggleBtn.style.color = '#5f4dee';
        toggleBtn.onclick = function(e) {
            e.stopPropagation();
            act._expanded = !act._expanded;
            renderExtracurricular();
			setupAutoResize();
        };

        // Кнопка удаления
        let removeBtn = null;
        if (isOwner) {
            removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.innerHTML = '&#10005;';
            removeBtn.title = 'Remove';
            removeBtn.className = 'cp-remove-extracurricular';
            removeBtn.style.background = 'none';
            removeBtn.style.border = 'none';
            removeBtn.style.fontSize = '1.2em';
            removeBtn.style.color = '#f44';
            removeBtn.style.marginLeft = '10px';
            removeBtn.style.cursor = 'pointer';
            removeBtn.onclick = function(e) {
                e.stopPropagation();
                data.extracurricular.splice(idx,1);
                renderExtracurricular();
				setupAutoResize();
                autosave();
            };
        }

        header.appendChild(nameInput);
        header.appendChild(toggleBtn);
        if (removeBtn) header.appendChild(removeBtn);

        card.appendChild(header);

        // --- Содержимое карточки ---
        if (act._expanded) {
            const content = document.createElement('div');
            content.className = 'cp-extracurricular-card-content';
            content.innerHTML = `
                <textarea placeholder="Achievements/Description" data-field="desc" ${!isOwner?'readonly':''}>${act.desc||''}</textarea>
            `;
            if (isOwner) {
                content.querySelector('textarea').oninput = function() {
                    data.extracurricular[idx].desc = this.value;
                    autosave();
                }
            }
            card.appendChild(content);
        }

        container.appendChild(card);
    });
}
function autoResizeTextarea(textarea) {
    textarea.style.height = 'auto'; // Сброс высоты
    textarea.style.height = textarea.scrollHeight + 'px'; // Установка высоты по содержимому
}

function setupAutoResize() {
    document.querySelectorAll('.cp-extracurricular-card textarea').forEach(textarea => {
        autoResizeTextarea(textarea); // Установить высоту при загрузке
        textarea.addEventListener('input', function() {
            autoResizeTextarea(textarea);
        });
    });
}
renderExtracurricular();
setupAutoResize();
        if (isOwner) {
            let btn = document.querySelector('.cp-add-extracurricular');
            if (btn) btn.onclick = function() {
                data.extracurricular.push({});
                renderExtracurricular();
				setupAutoResize();
				autosave();
            }
        }

        // --- GPA ---
function updateGPAResults() {
    let grades = (data.gpa||[]).map(r => parseFloat(r.grade)).filter(x => !isNaN(x));
    let gpa4 = grades.length ? ((grades.reduce((a,b) => a + b, 0) / grades.length) - 1) : 0;
    let gpa5 = grades.length ? (grades.reduce((a,b) => a + b, 0) / grades.length) : 0;
    document.querySelector('.cp-gpa-4').textContent = gpa4 ? gpa4.toFixed(2) : '0.00';
    document.querySelector('.cp-gpa-5').textContent = gpa5 ? gpa5.toFixed(2) : '0.00';
}
function renderGPA() {
    const tbody = document.querySelector('.cp-gpa-table tbody');
    let active = document.activeElement;
    let focusInfo = null;
    if (active && (active.tagName === 'INPUT') && tbody.contains(active)) {
        focusInfo = {
            idx: active.closest('tr').rowIndex - 1,
            field: active.dataset.field,
            selectionStart: active.selectionStart,
            selectionEnd: active.selectionEnd
        };
    }

    tbody.innerHTML = '';
    (data.gpa||[]).forEach((row, idx) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" placeholder="Subject" value="${row.subject||''}" data-field="subject" ${!isOwner?'readonly':''}></td>
            <td><input type="text" class="cp-grade-input" placeholder="Grade" value="${row.grade||''}" data-field="grade" inputmode="decimal" pattern="[0-9.]*"  ${!isOwner?'readonly':''}></td>
            ${isOwner?'<td><div class="cp-remove-subject" data-idx="'+idx+'">&#10005;</div></td>':''}
        `;
        if (isOwner) {
            tr.querySelectorAll('input').forEach(inp => {
                inp.oninput = function(e) {
                    if (inp.dataset.field === 'grade') {
                        inp.value = inp.value.replace(/[^0-9.]/g, '');
                    }
                    data.gpa[idx][inp.dataset.field] = inp.value;
                    autosave();
                    updateGPAResults(); // <-- добавляем обновление GPA после каждого ввода
                }
            });
            tr.querySelector('.cp-remove-subject').onclick = function() {
                data.gpa.splice(idx,1);
                renderGPA();
                autosave();
            }
        }
        tbody.appendChild(tr);
    });

    if (focusInfo) {
        let trs = tbody.querySelectorAll('tr');
        if (trs[focusInfo.idx]) {
            let inp = trs[focusInfo.idx].querySelector('input[data-field="'+focusInfo.field+'"]');
            if (inp) {
                inp.focus();
                let len = inp.value.length;
                let selStart = Math.min(focusInfo.selectionStart, len);
                let selEnd = Math.min(focusInfo.selectionEnd, len);
                inp.setSelectionRange(selStart, selEnd);
            }
        }
    }

    updateGPAResults(); // <-- обновляем GPA после полной перерисовки таблицы
}
        renderGPA();

if (isOwner) {
    let btn = document.querySelector('.cp-add-subject');
    if (btn) {
        btn.onclick = function() {
            data.gpa.push({subject: '', grade: ''});
            renderGPA();
            autosave();
        };
    }
}


        // --- Обновление AP/A-Level при открытии ---
        document.querySelectorAll('.cp-checklist-toggle').forEach(btn=>{
            btn.addEventListener('click', function(){
                setTimeout(renderAPAlevel, 100);
            });
        });

    });
    </script>
    <?php
}
