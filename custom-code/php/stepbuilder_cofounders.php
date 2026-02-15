<?php

function stepbuilder_cofounders_shortcode()
{
    $cofounders = [
        [
            'name' => 'Aimukatov Nurkhan',
            'role' => 'Founder, Coder & Architect',
            'photo' => 'http://stepbuilder.org/wp-content/uploads/2025/09/nn.jpeg',
            'about' => 'Nurkhan played a key role in turning StepBuilder from an idea into a real product. As <strong>the main coder</strong>, he was responsible for <strong>StepBuilder’s structure and functionality</strong>, making sure everything worked smoothly.
Using his <strong>programming skills</strong> and knowledge of <strong>AI engineering</strong>, he also created <strong>the document storage and editor interface</strong>, giving students the ability not only to create and organize portfolios, certificates, and other files in one place, but also to edit and update their information directly on the platform.',
        ],
        [
            'name' => 'Zhanbyrshy Dias',
            'role' => 'Founder, Vision & Coder',
            'photo' => 'http://stepbuilder.org/wp-content/uploads/2025/09/dd.jpeg',
            'about' => 'Dias was the one who first imagined StepBuilder. While preparing for his own applications, he realized how messy and time-consuming it was to collect certificates, store documents, and keep a clear college list.
He turned that problem into a solution by designing <strong>StepBuilder’s concept and profile interface</strong>. Dias envisioned StepBuilder not only as a portfolio builder but also as <strong>a place for college lists and student information storage</strong>. His creativity and design direction gave the project its identity.',
        ],
        [
            'name' => 'Kanat Adilkhan',
            'role' => 'Founder, AI Innovator & Tester',
            'photo' => 'http://stepbuilder.org/wp-content/uploads/2025/09/WhatsApp-Image-2025-09-25-at-21.57.37.jpeg',
            'about' => 'Adilkhan brought not only a fresh perspective, but also experience in <strong>AI engineering</strong>.
He recognized the growing need for <strong>artificial intelligence in education</strong> and proposed how StepBuilder could leverage it to help students build stronger portfolios. Today, Adilkhan ensures that the project stays relevant and forward-looking — from shaping its <strong>AI vision</strong> to securing grants that support StepBuilder’s growth. Alongside this, he tested the platform to make sure it was <strong>functional, user-friendly, and ready for real students</strong>.',
        ],
    ];
    shuffle($cofounders);

    ob_start();
?>
    <style>
    .sb-cofounders-flex-wrap {
        max-width: 1200px;
        margin: 5px auto 0 auto;
        padding: 0 10px;
    }
    .sb-cofounders-title {
        font-size: 40px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #333842;
        text-align: center;
    }
    .sb-cofounders-desc {
        font-size: 19px;
        color: #333842;
        margin-bottom: 36px;
        text-align: left;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
		font-weight:500;
    }
.sb-cofounders-flex-slider {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    gap: 40px;
    margin-bottom: 40px;
    position: relative;
    min-height: 340px;
    flex-wrap: wrap;
    transition: gap 0.3s;
}
    .sb-cofounder-card {
        background: #fdfdfd;
        border-radius: 10px;
        box-shadow: 0 2px 18px rgba(0,0,0,0.08);
        width: 270px;
        min-width: 180px;
        padding: 24px 18px 18px 18px;
        text-align: center;
        cursor: pointer;
transition: 
        transform 0.35s cubic-bezier(.4,0,.2,1),
        box-shadow 0.25s,
        opacity 0.25s,
        z-index 0.25s,
        order 0.35s cubic-bezier(.4,0,.2,1); /* добавлено! */
        position: relative;
        opacity: 1;
        z-index: 1;
        filter: blur(0);
    }
    .sb-cofounder-card .sb-cofounder-photo {
        width: 140px;
        height: 170px;
        border-radius: 8px;
        object-fit: cover;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        transition: box-shadow 0.2s;
    }
		.sb-cofounder-card:hover .sb-cofounder-photo,
.sb-cofounder-photo {
    transition: 
        transform 0.3s cubic-bezier(.4,0,.2,1),
}
		
		.sb-cofounder-card:focus .sb-cofounder-photo,
.sb-cofounder-card.sb-cofounder-card--active .sb-cofounder-photo,
.sb-cofounder-card.active .sb-cofounder-photo {
    transform: scale(1.13);
}

    .sb-cofounder-name {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 2px;
        color: #1a1a1a;
    }
    .sb-cofounder-role {
        font-size: 1.05rem;
        color: #6b7280;
        margin-bottom: 0;
    }
    .sb-cofounder-arrow {
        display: block;
        margin: 0 auto;
        margin-top: 10px;
        width: 22px;
        height: 22px;
        fill: #b0b7c3;
        transition: transform 0.3s;
    }
    .sb-cofounder-more {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.45s cubic-bezier(.4,0,.2,1), padding 0.3s;
        font-size: 1.05rem;
        color: #444;
        margin-top: 14px;
        text-align: left;
        padding: 0 10px;
    }
    .sb-cofounder-card.active .sb-cofounder-more {
        max-height: 1000px;
    }
    .sb-cofounder-card.active .sb-cofounder-arrow {
        transform: rotate(180deg);
    }
    /* Hover/focus effect */
    .sb-cofounder-card.sb-cofounder-card--inactive {
        opacity: 0.6;
        transform: scale(0.92);
        z-index: 1;
        filter: blur(0.5px);
    }
    .sb-cofounder-card.sb-cofounder-card--active {
        z-index: 10;
        opacity: 1;
		transform: scale(1.1);
        filter: blur(0);
        box-shadow: 0 16px 48px rgba(0,0,0,0.18);
    }
.sb-cofounder-card.active {
    width: 420px;
    max-width: 90vw;
    transition: width 0.35s cubic-bezier(.4,0,.2,1), box-shadow 0.25s;
}
		
    /* Responsive */
    @media (max-width: 900px) {
        .sb-cofounders-flex-slider {
            flex-wrap: wrap;
            gap: 18px;
        }
        .sb-cofounder-card, .sb-cofounder-card.sb-cofounder-card--active {
            width: 95vw;
            min-width: 0;
        }
        .sb-cofounder-card .sb-cofounder-photo {
            width: 100%;
            height: 140px;
        }
    }
@media (max-width: 600px) {
    .sb-cofounders-flex-slider {
        flex-direction: column;
        gap: 14px;
        align-items: stretch;
        min-height: unset;
    }
    .sb-cofounder-card,
    .sb-cofounder-card.sb-cofounder-card--active {
        width: 100%;
        min-width: 0;
        max-width: 100vw;
        padding: 12px 4vw 10px 4vw;
        box-sizing: border-box;
    }
    .sb-cofounder-card .sb-cofounder-photo {
        width: 100%;
        height: 90vw;
        max-height: 180px;
        min-height: 90px;
        margin-bottom: 10px;
    }
    .sb-cofounder-name {
        font-size: 1.05rem;
    }
    .sb-cofounder-role {
        font-size: 0.98rem;
    }
    .sb-cofounder-more {
        font-size: 0.98rem;
        padding: 0 2vw;
    }
    .sb-cofounder-card.active {
        width: 100%;
        max-width: 100vw;
    }
}
    </style>
<div class="container">
    <div class="sb-cofounders-flex-wrap">
        <div class="sb-cofounders-title">Meet the Co-Founders of StepBuilder</div>
        <div class="sb-cofounders-desc">
            <strong>StepBuilder</strong> was created by <strong>three students</strong> who shared one vision: to make building and managing <strong>a portfolio easier</strong> for everyone. Each co-founder brought unique skills to the project, and together they transformed a simple idea into a real platform.
        </div>
        <div class="sb-cofounders-flex-slider">
            <?php foreach ($cofounders as $i => $cofounder): ?>
                <div class="sb-cofounder-card" tabindex="0" data-index="<?php echo $i; ?>">
                    <img src="<?php echo esc_url($cofounder['photo']); ?>" alt="<?php echo esc_attr($cofounder['name']); ?>" class="sb-cofounder-photo" />
                    <div class="sb-cofounder-name"><?php echo esc_html($cofounder['name']); ?></div>
                    <div class="sb-cofounder-role"><?php echo esc_html($cofounder['role']); ?></div>
                    <svg class="sb-cofounder-arrow" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
                    <div class="sb-cofounder-more">
                        <?php echo wp_kses_post(nl2br($cofounder['about'])); ?>
                    </div>
                </div>
            <?php
    endforeach; ?>
        </div>
        <div class="sb-cofounders-history">
            <div class="sb-cofounders-title">How the Idea Came Into View</div>
<div class="sb-cofounders-desc">
    The idea for <strong>StepBuilder</strong> started in a simple but eye-opening way. I was sitting with my college counselor, discussing my application, when he sent me an Excel table. I had to fill in all my personal information and build a college list inside it. The process felt complicated, messy, and not user-friendly at all — and I realized it wasn’t just me. More than half of the students at <strong>QSS</strong> faced the same frustration.
    <br><br>
    That’s when I began asking myself: <strong>“How can this be made easier and more understandable?”</strong>. Together with <strong>Adilkhan</strong> and <strong>Nurkhan</strong>, we conducted a survey across several schools. The results confirmed our suspicion — most students struggled with the same problem. That gave us the push to take action.
    <br><br>
    We decided to build <strong>a website that could store all student information</strong> in one place. Soon, we realized we could go even further: by using the stored information, we could <strong>automatically generate portfolios</strong> with completed templates.
    <br><br>
    The problem lay in building the website from a technical point of view, but <strong>Nurkhan</strong> managed to overcome it and built <strong>the first working prototype</strong>. He developed the system that allowed students to create, organize, and edit portfolios, turning our concept into a functional product.
    <br><br>
    When we tested our MVP, <strong>Adilkhan</strong> noticed another challenge: many students didn’t know how to describe their activities professionally. To solve this, we implemented <strong>AI prompts</strong> that could suggest improvements and rewrite content in a polished way.
    <br><br>
    <strong>The Evolution: StepBuilder 2.0</strong>
    <br><br>
    However, we realized that the application process was still "fragmented"—spread across Excel, Google Docs, and messy messengers. This sparked the creation of <strong>StepBuilder 2.0</strong>. We decided to transform the site into a <strong>unified digital workspace</strong> where students and mentors can collaborate in real-time.
    <br><br>
    We introduced a <strong>Personal Dashboard</strong> where students can manage everything from CSS Profiles to transcripts. The <strong>University section</strong> evolved into a flexible workspace: you can now pick a university like Harvard, use a <strong>ready-made template</strong> for its requirements, or build your own tracking tables. 
    <br><br>
    Most importantly, we added a <strong>Mentor Side</strong>. Counselors can now view a student’s progress, leave notes on essay drafts, and manage tasks directly on the platform. No more cluttered chats or lost files—just a clear, structured path to success.
    <br><br>
    Today, StepBuilder continues to grow because we believe students <strong>shouldn’t have to pay just to organize their knowledge and opportunities</strong>. Our vision for the future includes a global mentorship marketplace and a space for research competitions. Step by step, we’re making the application process less stressful and more effective for every student worldwide.
            </div>
        </div>
    </div>
	</div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.sb-cofounders-flex-slider');
    let cards = Array.from(container.querySelectorAll('.sb-cofounder-card'));

    function resetCards(exceptCard = null) {
        cards.forEach(card => {
            if (card !== exceptCard) {
                card.classList.remove('sb-cofounder-card--active', 'sb-cofounder-card--inactive', 'active');
                card.querySelector('.sb-cofounder-more').style.maxHeight = null;
            }
        });
    }

    function highlightCard(card) {
        // Только визуальное выделение, не раскрывать
        cards.forEach(c => {
            if (c !== card && !c.classList.contains('active')) c.classList.add('sb-cofounder-card--inactive');
        });
        if (!card.classList.contains('active')) card.classList.add('sb-cofounder-card--active');
    }

    function centerCard(card) {
        const idx = cards.indexOf(card);
        if (idx === -1) return;
        const centerIdx = Math.floor(cards.length / 2);
        if (idx === centerIdx) return;
        const [selectedCard] = cards.splice(idx, 1);
        cards.splice(centerIdx, 0, selectedCard);
        container.innerHTML = '';
        cards.forEach(c => container.appendChild(c));
    }

    function activateCard(card) {
        resetCards(card);
        cards.forEach(c => {
            if (c !== card) c.classList.add('sb-cofounder-card--inactive');
        });
        card.classList.add('sb-cofounder-card--active', 'active');
        var more = card.querySelector('.sb-cofounder-more');
        more.style.maxHeight = more.scrollHeight + "px";
    }

    function deactivateCard(card) {
        card.classList.remove('sb-cofounder-card--active', 'active');
        card.querySelector('.sb-cofounder-more').style.maxHeight = null;
        cards.forEach(c => c.classList.remove('sb-cofounder-card--inactive'));
    }

cards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        highlightCard(card);
    });
    card.addEventListener('focus', function() {
        highlightCard(card);
    });
    card.addEventListener('mouseleave', function() {
        // Не закрываем карточку!
        cards.forEach(c => {
            if (!c.classList.contains('active')) c.classList.remove('sb-cofounder-card--active', 'sb-cofounder-card--inactive');
        });
    });
    card.addEventListener('blur', function() {
        // Не закрываем карточку!
        cards.forEach(c => {
            if (!c.classList.contains('active')) c.classList.remove('sb-cofounder-card--active', 'sb-cofounder-card--inactive');
        });
    });
card.addEventListener('click', function(e) {
    e.stopPropagation();
    if (card.classList.contains('active')) {
        deactivateCard(card);
        return;
    }
    // Только на десктопе центрируем карточку
    if (window.innerWidth > 600) {
        centerCard(card);
        // Обновляем список карточек после перестановки
        cards = Array.from(container.querySelectorAll('.sb-cofounder-card'));
    }
    activateCard(card);
});
    card.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            card.click();
        }
    });
});

    // Клик вне карточек — закрывает открытую карточку
    document.addEventListener('click', function(e) {
        // Проверяем, был ли клик внутри одной из карточек
        if (!e.target.closest('.sb-cofounder-card')) {
            // Закрываем все карточки
            cards.forEach(card => {
                if (card.classList.contains('active')) {
                    deactivateCard(card);
                }
            });
        }
    });
});
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('stepbuilder_cofounders', 'stepbuilder_cofounders_shortcode');