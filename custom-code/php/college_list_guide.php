<?php

// 1. AJAX обработчик для OpenAI
add_action('wp_ajax_clg_ask_ai', 'clg_ask_ai');
add_action('wp_ajax_nopriv_clg_ask_ai', 'clg_ask_ai');
function clg_ask_ai()
{
    // ВАЖНО: замените на свой ключ!
    $api_key = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : '';

    $prompt = sanitize_text_field($_POST['prompt'] ?? '');

    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ],
        'body' => json_encode([
            'model' => 'gpt-3.5-turbo', // или gpt-4, если есть доступ
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant for college admissions.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 300,
            'temperature' => 0.7,
        ]),
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        wp_send_json(['error' => 'Request failed']);
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['choices'][0]['message']['content'])) {
        wp_send_json(['answer' => $body['choices'][0]['message']['content']]);
    }
    else {
        wp_send_json(['error' => 'No answer from AI']);
    }
}

// 2. Шорткод с версткой и JS
function college_list_guide_shortcode_en()
{
    ob_start();
?>
    <style>
    .clg-guide-layout {
        position: relative;
        width: 100%;
        min-height: 100vh;
		margin-top: 0 !important;
    }
    .clg-guide-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0px 0 30px 0;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        color: #333842;
        line-height: 1.7;
        background: #fff;
        font-weight: 500;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(59,130,246,0.04);
        position: relative;
        z-index: 2;
    }
    .clg-guide-sidebar {
        position: absolute;
        left: 0;
        top: 50px;
        width: 350px;
        margin-left: 40px;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        color: #333842;
        font-weight: 500;
        height: fit-content;
        z-index: 3;
    }
@media (max-width: 1200px) {
    .clg-guide-sidebar {
        position: static !important;
        margin: 0 auto 24px auto !important;
        width: 100% !important;
        max-width: 400px !important;
        display: block !important;
        padding: 0 12px !important;
        box-sizing: border-box;
    }
    .clg-guide-container {
        margin: 0 auto !important;
        padding: 10px 12px !important;
        box-sizing: border-box;
    }
    .clg-guide-layout {
        display: block !important;
        padding: 0 12px;
    }
}
    @media (max-width: 900px) {
        .clg-guide-sidebar { padding: 12px 6px; }
        .clg-guide-container { padding: 10px 2px; }
    }
    /* ... остальной ваш CSS ... */
    .clg-guide-sidebar-menu {
        list-style: none;
        margin: 0; padding: 0;
    }
    .clg-guide-sidebar-menu li {
        margin-bottom: 12px;
    }
    .clg-guide-sidebar-link {
        color: #3d67e3;
        text-decoration: none;
        font-weight: 500;
        font-size: 20px;
        cursor: pointer;
        display: block;
        padding: 8px 0;
        border: none;
        background: none;
        outline: none;
    }
    .clg-guide-sidebar-link.active,
    .clg-guide-sidebar-link:hover {
        color: #1e40af;
    }
    .clg-guide-ai-sidebar-btn {
        width: 90%;
        background: #3d67e3;
        color: #fff;
        border: none;
        border-radius: 7px;
        padding: 12px 0;
        font-size: 1.09em;
        font-weight: 700;
        margin-top: 18px;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(59,130,246,0.10);
    }
    .clg-guide-ai-sidebar-btn:hover {
        background: #1e40af;
    }
    .clg-guide-ai-sidebar-chat {
        margin-top: 18px;
        display: flex;
        flex-direction: column;
        min-height: 320px;
        max-height: 420px;
        animation: clg-fadein 0.35s;
        overflow: hidden;
        position: relative;
    }
    @keyframes clg-fadein {
        from { opacity: 0; transform: translateY(20px);}
        to { opacity: 1; transform: translateY(0);}
    }
    .clg-guide-ai-sidebar-chat-header {
        color: #3d67e3;
        padding: 12px 16px;
        font-size: 1.08em;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
		border-bottom: 1px solid #e5e7eb;
		
    }
    .clg-guide-ai-sidebar-chat-close {
        background: none;
        border: none;
        color: #f87171;
        font-size: 1.3em;
        cursor: pointer;
        margin-left: 10px;
        transition: color 0.2s;
    }
    .clg-guide-ai-sidebar-chat-close:hover { color: #fc4e4e;}
    .clg-guide-ai-sidebar-chat-body {
        flex: 1 1 auto;
        padding: 12px 0px 8px 0px;
        overflow-y: auto;
        font-size: 1em;
    }
    .clg-guide-ai-chat-history {
        max-height: 160px;
        overflow-y: auto;
        margin-bottom: 10px;
    }
    .clg-guide-ai-chat-message {
        margin-bottom: 8px;
        padding: 7px 12px;
        border-radius: 7px;
        background: #f0fbff;
        color: #333842;
        font-size: 0.98em;
        word-break: break-word;
    }
    .clg-guide-ai-chat-message.user {
        background: #dbeafe;
        color: #1e40af;
        text-align: right;
    }
    .clg-guide-ai-sidebar-chat-footer {
        padding: 8px 12px 12px 12px;
        border-top: 1px solid #e5e7eb;
    }
    .clg-guide-ai-chat-form {
        display: flex;
        gap: 6px;
    }
    .clg-guide-ai-chat-input {
        flex: 1 1 auto;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 7px 10px;
        font-size: 1em;
        outline: none;
        transition: border 0.2s;
    }
    .clg-guide-ai-chat-input:focus {
        border: 1.5px solid #3d67e3;
    }
    .clg-guide-ai-chat-send {
        background: #3d67e3;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 7px 14px;
        font-size: 1em;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .clg-guide-ai-chat-send:hover {
        background: #1e40af;
    }
    .clg-guide-step {
        margin-bottom: 36px;
        padding: 0 0 18px 0;
        background: transparent;
        position: relative;
    }
    .clg-guide-step:last-child {
        border-bottom: none;
    }
    .clg-guide-step-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #5d58f5;
    }
    .clg-guide-step.step1 ul, .clg-guide-step.step2 ul, .clg-guide-step.step3 ul{
        line-height: 2.5;
    }
    .clg-guide-step.step1::before {
        content: '';
        display: block;
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 7px;
        border-radius: 10px;
        background: rgba(59,130,246,0.13);
    }
    .clg-guide-step.step1 {
        background: #f8fbff;
        padding:18px 32px;
    }
    .clg-guide-step.step2::before {
        content: '';
        display: block;
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 7px;
        border-radius: 10px;
        background: rgba(34,197,94,0.13);
    }
    .clg-guide-step.step2 {
        background: #f9fefb;
        padding:18px 32px;
    }
    .clg-guide-step.step3::before {
        content: '';
        display: block;
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 7px;
        border-radius: 10px;
        background: rgba(251,146,60,0.13);
    }
    .clg-guide-step.step3 {
        background: #fffdfa;
        padding:18px 32px;
    }
    .clg-guide-terms-row {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 12px;
    }
    .clg-guide-term-card {
        background: #f5fbff;
        border-radius: 14px;
        padding: 18px 20px;
        min-width: 220px;
        flex: 1 1 220px;
        box-shadow: 0 2px 12px rgba(59,130,246,0.06);
        margin-bottom: 0px;
        border: none;
    }
    .clg-guide-term-card .clg-guide-term-title {
        font-weight: 600;
        color: #5d58f5;
        margin-bottom: 7px;
        font-size: 1.09em;
    }
    .clg-guide-term-card ul {
        margin: 0 0 0 18px;
        padding: 0;
        font-size: 0.98em;
    }
    .clg-guide-step.step5::before {
        content: '';
        display: block;
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 7px;
        border-radius: 7px;
        background: rgba(56,189,248,0.13);
    }
    .clg-guide-step.step5 {
        background: #f7fbfd;
        border-radius: 10px;
        padding:18px 32px;;
        box-shadow: 0 2px 12px rgba(56,189,248,0.04);
    }
    .clg-guide-platform-list {
        margin: 0 0 0 18px;
        padding: 0;
    }
    .clg-guide-platform-list li {
        margin-bottom: 8px;
        font-size: 1.04em;
    }
    .clg-guide-platform-badge {
        background: #e8f3fb;
        color: #5d58f5;
        border-radius: 5px;
        padding: 5px 13px;
        font-size: 1.04em;
        margin-right: 8px;
        font-weight: 500;
    }
    .clg-guide-categories {
        display: flex;
        gap: 18px;
        margin-top: 12px;
        flex-wrap: wrap;
        justify-content: flex-start;
    }
    .clg-guide-cat {
        background: #f1f5f9;
        border-radius: 8px;
        padding: 10px 18px;
        font-weight: 500;
        color: #334155;
    }
    .clg-guide-final {
        background: #e0f2fe;
        border-radius: 10px;
        padding: 18px 20px;
        margin-top: 30px;
        font-weight: 500;
        color: #0369a1;
        font-size: 1.08em;
    }
    .clg-guide-badge {
        background: #e8f3fb;
        color: #5d58f5;
        border-radius: 5px;
        padding: 5px 13px;
        font-size: 0.95em;
        margin-right: 8px;
        font-weight: 500;
    }
    .clg-guide-badge.green {
        background: #eafaf1;
        color: #22a06b;
    }
    .clg-guide-badge.orange {
        background: #fff7ed;
        color: #e07c1c;
    }
    @media (max-width: 900px) {
        .clg-guide-terms-row { flex-direction: column; }
        .clg-guide-categories { flex-direction: column; gap: 10px; }
    }
    @media (max-width: 600px) {
        .clg-guide-step { padding-left: 10px !important; }
    }
		@media (max-width: 700px) {
    .clg-guide-container {
        max-width: 100% !important;
        padding: 10px 10px !important;
        font-size: 1em !important;
    }
    .clg-guide-step {
        padding: 12px 16px !important;
        margin-bottom: 24px !important;
    }
    .clg-guide-step-title {
        font-size: 1.15rem !important;
        gap: 6px !important;
    }
    .clg-guide-sidebar-menu li {
        margin-bottom: 10px !important;
    }
    .clg-guide-sidebar-link {
        font-size: 18px !important;
        padding: 6px 0 !important;
    }
    .clg-guide-ai-sidebar-btn {
        font-size: 1em !important;
        padding: 10px 0 !important;
        border-radius: 6px !important;
        box-shadow: 0 1px 6px rgba(59,130,246,0.1) !important;
    }
    .clg-guide-ai-sidebar-btn:hover {
        background: #1e40af !important;
    }
    /* Чат AI на всю ширину */
    #clgGuideAiSidebarChat {
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        max-width: 100vw !important;
        height: 50vh !important;
        max-height: 50vh !important;
        background: #fff !important;
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.1) !important;
        z-index: 9999 !important;
        display: none;
        flex-direction: column;
        padding: 0 !important;
        overflow: hidden;
    }
    .clg-guide-ai-sidebar-chat {
        min-height: auto !important;
        max-height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        height: 100% !important;
    }
    .clg-guide-ai-sidebar-chat-header {
        padding: 10px 16px !important;
        font-size: 1.1em !important;
        border-bottom: 1px solid #e5e7eb !important;
    }
    .clg-guide-ai-sidebar-chat-body {
        flex: 1 1 auto !important;
        padding: 8px 12px !important;
        overflow-y: auto !important;
        font-size: 0.95em !important;
    }
    .clg-guide-ai-chat-history {
        max-height: none !important;
        overflow-y: auto !important;
        margin-bottom: 8px !important;
    }
    .clg-guide-ai-sidebar-chat-footer {
        padding: 8px 12px !important;
        border-top: 1px solid #e5e7eb !important;
    }
    .clg-guide-ai-chat-input {
        font-size: 1em !important;
        padding: 8px 10px !important;
    }
    .clg-guide-ai-chat-send {
        padding: 8px 14px !important;
        font-size: 1em !important;
    }
}

/* Очень маленькие экраны (до 400px) */
@media (max-width: 400px) {
    .clg-guide-step-title {
        font-size: 1.05rem !important;
    }
    .clg-guide-ai-chat-input {
        font-size: 0.95em !important;
    }
    .clg-guide-ai-chat-send {
        font-size: 0.95em !important;
        padding: 6px 10px !important;
    }
}
    </style>
    <script>
    window.clgGuideAjax = {
        url: '<?php echo admin_url('admin-ajax.php'); ?>'
    };
    </script>
    <div class="clg-guide-layout">
        <nav class="clg-guide-sidebar">
            <ul class="clg-guide-sidebar-menu">
                <li>
                    <a href="#" class="clg-guide-sidebar-link active">How to Build Your College List</a>
                </li>
            </ul>
            <button class="clg-guide-ai-sidebar-btn" id="clgGuideAiSidebarBtn">AI Assistant
            </button>
            <div id="clgGuideAiSidebarChat" style="display:none;">
                <div class="clg-guide-ai-sidebar-chat">
                    <div class="clg-guide-ai-sidebar-chat-header">
                        <span>AI Assistant</span>
                        <button class="clg-guide-ai-sidebar-chat-close" id="clgGuideAiSidebarCloseBtn" title="Close">&times;</button>
                    </div>
                    <div class="clg-guide-ai-sidebar-chat-body">
                        <div class="clg-guide-ai-chat-history" id="clgGuideAiSidebarChatHistory">
                            <div class="clg-guide-ai-chat-message">Hi! I am your AI assistant. Ask me anything about college admissions, scholarships, or building your college list.</div>
                        </div>
                    </div>
                    <div class="clg-guide-ai-sidebar-chat-footer">
                        <form class="clg-guide-ai-chat-form" id="clgGuideAiSidebarChatForm" autocomplete="off">
                            <input type="text" class="clg-guide-ai-chat-input" id="clgGuideAiSidebarChatInput" placeholder="Type your question..." required />
                            <button type="submit" class="clg-guide-ai-chat-send">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <div class="clg-guide-container">
            <h2>Complete Guide: How to Build Your College List</h2>
            <!-- ...весь ваш HTML-контент без изменений... -->
            <div class="clg-guide-step step1">
                <div class="clg-guide-step-title">🔹 1. Define Your Goals</div>
                <ul>
                    <li><span class="clg-guide-badge">Major</span> (Intended Major): e.g., Economics, Business, Finance, Data Science.</li>
                    <li><span class="clg-guide-badge">Region</span> USA, Asia (Korea, China, Hong Kong, UAE, Qatar), Europe.</li>
                    <li><span class="clg-guide-badge">Financial Aid</span> Do you need a full-ride, full-tuition, or partial scholarship?</li>
                    <li><span class="clg-guide-badge">Priorities</span> Prestige, research, career connections, campus atmosphere.</li>
                </ul>
            </div>
            <div class="clg-guide-step step2">
                <div class="clg-guide-step-title">🔹 2. Where to Search for Universities</div>
                <ul>
                    <li><span class="clg-guide-badge green">Official Websites</span> Main source for programs, requirements, deadlines, and financial aid.</li>
                    <li><span class="clg-guide-badge green">Niche.com</span> Campus atmosphere, student reviews, rankings, and average stats (SAT, GPA).</li>
                    <li><span class="clg-guide-badge green">Common App Explore</span> Convenient catalog of US colleges.</li>
                    <li><span class="clg-guide-badge green">BigFuture</span> Search by criteria (location, major, cost).</li>
                    <li><span class="clg-guide-badge green">QS/US News</span> Compare program strength in Economics, Business, etc.</li>
                </ul>
            </div>
            <div class="clg-guide-step step3">
                <div class="clg-guide-step-title">🔹 3. What to Check on University Websites</div>
                <ul>
                    <li><span class="clg-guide-badge orange">Programs / Majors</span> Find your subject (e.g., Economics).</li>
                    <li><span class="clg-guide-badge orange">Course Description</span> Required and elective courses.</li>
                    <li><span class="clg-guide-badge orange">Admissions</span> SAT, IELTS, GPA requirements.</li>
                    <li><span class="clg-guide-badge orange">Financial Aid</span> Scholarship information.</li>
                </ul>
            </div>
            <div class="clg-guide-step">
                <div class="clg-guide-step-title">🔹 4. Key Terms</div>
                <div class="clg-guide-terms-row">
                    <div class="clg-guide-term-card">
                        <div class="clg-guide-term-title">🎯 Acceptance Rate</div>
                        <ul>
                            <li>Percentage of admitted students out of all applicants.</li>
                            <li>Example: 5% — super competitive (Harvard, Princeton). 30–40% — more accessible.</li>
                        </ul>
                    </div>
                    <div class="clg-guide-term-card">
                        <div class="clg-guide-term-title">💰 Types of Financial Aid</div>
                        <ul>
                            <li><b>Need-based Aid:</b> Based on your family's financial situation.</li>
                            <li><b>Need-blind:</b> Admission decision does not depend on your financial need.</li>
                            <li><b>Need-aware:</b> University considers how much aid you request.</li>
                            <li><b>Merit-based Aid:</b> Scholarships for academic, athletic, or leadership achievements.</li>
                            <li><b>Full-ride / Full-tuition:</b> Full coverage (sometimes includes room and board).</li>
                        </ul>
                    </div>
                    <div class="clg-guide-term-card">
                        <div class="clg-guide-term-title">⏳ Application Rounds</div>
                        <ul>
                            <li><b>ED (Early Decision):</b> Apply early (November), binding if admitted.</li>
                            <li><b>EA (Early Action):</b> Also early, but non-binding.</li>
                            <li><b>RD (Regular Decision):</b> Main round (January–February).</li>
                            <li><b>Rolling Admissions:</b> Applications accepted until spots are filled.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="clg-guide-step step5">
                <div class="clg-guide-step-title">🔹 5. Store Everything on Our Platform</div>
                <ul class="clg-guide-platform-list">
                    <li><span class="clg-guide-platform-badge">No Spreadsheets</span> No need to use Google Sheets or Excel manually.</li>
                    <li><span class="clg-guide-platform-badge">University Cards</span> Each university has a card where you can save:
                        <ul>
                            <li><span class="clg-guide-platform-badge">Acceptance Rate</span></li>
                            <li><span class="clg-guide-platform-badge">Financial Aid Type</span></li>
                            <li><span class="clg-guide-platform-badge">Deadlines (ED/EA/RD)</span></li>
                            <li><span class="clg-guide-platform-badge">Majors & Programs</span></li>
                            <li><span class="clg-guide-platform-badge">Pros & Cons</span></li>
                        </ul>
                    </li>
                    <li><span class="clg-guide-platform-badge">Easy Comparison</span> You get a single, convenient list of universities, easy to filter and compare.</li>
                </ul>
            </div>
            <div class="clg-guide-step">
                <div class="clg-guide-step-title">🔹 6. Categorize Your Universities</div>
                <div class="clg-guide-categories">
                    <div class="clg-guide-cat"><b>Reach</b> — very competitive (Harvard, Princeton, Yale)</div>
                    <div class="clg-guide-cat"><b>Match</b> — good chances (NYUAD, Colby, Grinnell)</div>
                    <div class="clg-guide-cat"><b>Safety</b> — reliable options (PolyU HK, Yonsei UIC, Nazarbayev University)</div>
                </div>
            </div>
            <div class="clg-guide-final">
                ✅ Summary:<br>
                1. Define your goals.<br>
                2. Find universities (Niche + official websites).<br>
                3. Explore programs and requirements.<br>
                4. Understand key terms (acceptance rate, financial aid, ED/RD).<br>
                5. Save all info <b>on our platform</b> so nothing gets lost.<br>
                6. Balance your list: Reach / Match / Safety.
            </div>
        </div>
    </div>
    <script>
    (function(){
        var btn = document.getElementById('clgGuideAiSidebarBtn');
        var chat = document.getElementById('clgGuideAiSidebarChat');
        var closeBtn = document.getElementById('clgGuideAiSidebarCloseBtn');
        var chatForm = document.getElementById('clgGuideAiSidebarChatForm');
        var chatInput = document.getElementById('clgGuideAiSidebarChatInput');
        var chatHistory = document.getElementById('clgGuideAiSidebarChatHistory');

        btn.addEventListener('click', function() {
            btn.style.display = 'none';
            chat.style.display = 'block';
            setTimeout(function(){ chatInput.focus(); }, 300);
        });
        closeBtn.addEventListener('click', function() {
            chat.style.display = 'none';
            btn.style.display = 'flex';
        });

        // AI chat with AJAX
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var userMsg = chatInput.value.trim();
            if (!userMsg) return;
            // Add user message
            var userDiv = document.createElement('div');
            userDiv.className = 'clg-guide-ai-chat-message user';
            userDiv.textContent = userMsg;
            chatHistory.appendChild(userDiv);
            chatInput.value = '';
            chatHistory.scrollTop = chatHistory.scrollHeight;

            // Add loading message
            var aiDiv = document.createElement('div');
            aiDiv.className = 'clg-guide-ai-chat-message';
            aiDiv.textContent = 'Thinking...';
            chatHistory.appendChild(aiDiv);
            chatHistory.scrollTop = chatHistory.scrollHeight;

            // AJAX to backend
            fetch(window.clgGuideAjax.url, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=clg_ask_ai&prompt=' + encodeURIComponent(userMsg)
            })
            .then(r => r.json())
            .then(data => {
                if (data.answer) {
                    aiDiv.textContent = data.answer;
                } else {
                    aiDiv.textContent = 'Sorry, AI error: ' + (data.error || 'Unknown');
                }
                chatHistory.scrollTop = chatHistory.scrollHeight;
            })
            .catch(() => {
                aiDiv.textContent = 'Sorry, network error.';
                chatHistory.scrollTop = chatHistory.scrollHeight;
            });
        });
    })();
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('college_list_guide', 'college_list_guide_shortcode_en');