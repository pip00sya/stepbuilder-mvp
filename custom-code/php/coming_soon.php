<?php

function stepbuilder_coming_soon_shortcode()
{
    ob_start();
?>
    <section class="sb-container">
        <!-- Hero Section -->
        <div class="sb-hero">
            <h1>StepBuilder 2.0 <span class="sb-badge">Coming Soon</span></h1>
            <p class="sb-lead">Your Intellectual Guide to University Admissions.</p>
            <p class="sb-sublead">A unified platform for students, mentors, and schools. Manage your entire application process in one place.</p>
        </div>

        <!-- Mission Section -->
        <div class="sb-section">
            <h2 class="sb-title">Our Mission</h2>
            <div class="sb-grid-2">
                <div class="sb-card">
                    <h3>For Students</h3>
                    <ul class="sb-list">
                        <li><strong>Personal Profile:</strong> Securely store your GPA, CSS profile, transcripts, and exam scores (SAT, IELTS, etc.).</li>
                        <li><strong>College List:</strong> Smart university table (Common App & international options) with deadlines and requirements.</li>
                        <li><strong>Documents:</strong> Collect and organize Recommendation Letters, Confirmation Letters, and other files.</li>
                        <li><strong>Essays:</strong> A dedicated tab for drafts, notes, and ideas.</li>
                    </ul>
                </div>
                <div class="sb-card">
                    <h3>For Mentors & Schools</h3>
                    <ul class="sb-list">
                        <li><strong>Monitoring:</strong> Full real-time access to student progress.</li>
                        <li><strong>Feedback:</strong> Edit essays and leave notes directly on the platform.</li>
                        <li><strong>Workspace:</strong> Create pre-made <span class="highlight">College Lists</span> and assignments for students (perfect for educational centers).</li>
                        <li><strong>Dashboard:</strong> Manage multiple students within a single interface.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Problem & Solution -->
        <div class="sb-section sb-bg-light">
            <h2 class="sb-title">Problem & Solution</h2>
            <div class="sb-grid-2">
                <div class="sb-feature">
                    <div class="sb-icon">✕</div>
                    <h4>The Problem</h4>
                    <p>Using scattered Google Docs, Excel sheets, and messengers. Information gets lost in chats, personal messages mix with work, and Notion's interface can be difficult for beginners.</p>
                </div>
                <div class="sb-feature">
                    <div class="sb-icon">✓</div>
                    <h4>The StepBuilder Solution</h4>
                    <p>A specialized, structured platform. Communication with your mentor happens in the context of the task (essay or document), not in a general chat. All deadlines and files are at your fingertips.</p>
                </div>
            </div>
        </div>

        <!-- Interface Showcase (Images) -->
        <div class="sb-section">
            <h2 class="sb-title">Platform Interface</h2>
            <p class="sb-center-text">Intuitive, powerful, and flexible design.</p>
            
            <div class="sb-showcase">
                <!-- Image 1 -->
                <div class="sb-showcase-item">
                    <div class="sb-image-placeholder">
                        <img src="http://stepbuilder.org/wp-content/uploads/2026/02/university_workspace.png" alt="University Workspace" />
                        <span class="sb-img-note">Replace with Screenshot 1</span>
                    </div>
                    <div class="sb-showcase-desc">
                        <h4>Personal University Workspace</h4>
                        <p>Example: Harvard Workspace. On the right is your working area with document tables, status tracking (Ready/Missing), and statistics. Use ready-made templates for quick setup for any university.</p>
                    </div>
                </div>

                <!-- Image 2 -->
                <div class="sb-showcase-item">
                    <div class="sb-image-placeholder">
                        <img src="http://stepbuilder.org/wp-content/uploads/2026/02/application.png" alt="Application Tracker" />
                        <span class="sb-img-note">Replace with Screenshot 2</span>
                    </div>
                    <div class="sb-showcase-desc">
                        <h4>Application Tracker</h4>
                        <p>Your master list of universities. Clear view of application status (In Progress, Submitted), deadlines, and quick access to requirements.</p>
                    </div>
                </div>

                <!-- Image 3 -->
                <div class="sb-showcase-item">
                    <div class="sb-image-placeholder">
                        <img src="http://stepbuilder.org/wp-content/uploads/2026/02/analytics.png" alt="Progress Overview" />
                        <span class="sb-img-note">Replace with Screenshot 3</span>
                    </div>
                    <div class="sb-showcase-desc">
                        <h4>Analytics & Progress</h4>
                        <p>Visualize your chances. Compare your metrics (SAT/ACT, GPA) with average admitted student data. Track financial aid and cost estimates.</p>
                    </div>
                </div>

                <!-- Image 4 -->
                <div class="sb-showcase-item">
                    <div class="sb-image-placeholder">
                        <img src="http://stepbuilder.org/wp-content/uploads/2026/02/essay.png" alt="Essay Editor" />
                        <span class="sb-img-note">Replace with Screenshot 4</span>
                    </div>
                    <div class="sb-showcase-desc">
                        <h4>Essay Editor with Mentor</h4>
                        <p>Collaborative writing workspace. Mentor comments, draft versions, and built-in prompts for inspiration.</p>
                    </div>
                </div>

                <!-- Image 5 -->
                <div class="sb-showcase-item">
                    <div class="sb-image-placeholder">
                        <img src="http://stepbuilder.org/wp-content/uploads/2026/02/essay.png" alt="Portfolio & Resume" />
                        <span class="sb-img-note">Replace with Screenshot 5</span>
                    </div>
                    <div class="sb-showcase-desc">
                        <h4>Portfolio & Resume</h4>
                        <p>Resume builder and Activities section. Automatic formatting of achievements for the Common App.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Future Plans -->
        <div class="sb-section sb-bg-dark">
            <h2 class="sb-title" style="color: #fff;">Future Plans</h2>
            <div class="sb-grid-3">
                <div class="sb-plan-card">
                    <div class="sb-plan-icon">🔍</div>
                    <h3>Smart University Search</h3>
                    <p>Search engine with detailed information (similar to NICHE). Universities can manage and fill their own profiles.</p>
                </div>
                <div class="sb-plan-card">
                    <div class="sb-plan-icon">🤝</div>
                    <h3>Mentor Marketplace</h3>
                    <p>Platform to find qualified university admissions mentors from around the world.</p>
                </div>
                <div class="sb-plan-card">
                    <div class="sb-plan-icon">🏆</div>
                    <h3>Contests & Grants</h3>
                    <p>Publish research papers and portfolios. Opportunity to get noticed by professors and earn recommendation letters.</p>
                </div>
            </div>
        </div>

        <div class="sb-footer">
            <p>&copy; <?php echo date("Y"); ?> StepBuilder. All rights reserved.</p>
        </div>
    </section>

    <style>
        /* StepBuilder Premium Styles */
        :root {
            --sb-primary: #2563eb;       /* Rich Blue */
            --sb-secondary: #1e40af;     /* Darker Blue */
            --sb-accent: #3b82f6;        /* Bright Blue */
            --sb-bg: #f8fafc;            /* Light Gray Background */
            --sb-text: #1e293b;          /* Dark Slate Text */
            --sb-text-light: #64748b;    /* Muted Text */
            --sb-card-bg: #ffffff;
            --sb-success: #10b981;
            --sb-border-radius: 16px;
            --sb-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --sb-font: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .sb-container {
            font-family: var(--sb-font);
            color: var(--sb-text);
            background-color: var(--sb-bg);
            line-height: 1.6;
            max-width: 1200px;
            margin: 0 auto;
            border-radius: var(--sb-border-radius);
            overflow: hidden;
            box-shadow: var(--sb-shadow);
        }

        .sb-hero {
            background: linear-gradient(135deg, var(--sb-primary), var(--sb-secondary));
            color: white;
            padding: 80px 40px;
            text-align: center;
        }

        .sb-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-weight: 800;
            color: white;
            letter-spacing: -1px;
        }

        .sb-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 1rem;
            vertical-align: middle;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sb-lead {
            font-size: 1.5rem;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .sb-sublead {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        .sb-section {
            padding: 60px 40px;
        }

        .sb-bg-light {
            background-color: white;
        }

        .sb-bg-dark {
            background-color: #0f172a;
            color: white;
        }

        .sb-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 50px;
            color: var(--sb-text);
            font-weight: 700;
        }

        .sb-bg-dark .sb-title {
            color: white;
        }

        .sb-center-text {
            text-align: center;
            color: var(--sb-text-light);
            margin-bottom: 40px;
            font-size: 1.2rem;
        }

        .sb-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .sb-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .sb-card, .sb-feature, .sb-plan-card {
            background: var(--sb-card-bg);
            padding: 30px;
            border-radius: var(--sb-border-radius);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .sb-card:hover, .sb-feature:hover, .sb-plan-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--sb-shadow);
        }

        .sb-plan-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sb-card h3, .sb-feature h4, .sb-plan-card h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--sb-primary);
        }
        
        .sb-plan-card h3 {
            color: white;
        }

        .sb-list {
            list-style: none;
            padding: 0;
        }

        .sb-list li {
            margin-bottom: 15px;
            padding-left: 25px;
            position: relative;
        }

        .sb-list li::before {
            content: "•";
            color: var(--sb-success);
            font-weight: bold;
            font-size: 1.5em;
            position: absolute;
            left: 0;
            top: -5px;
        }

        .sb-icon, .sb-plan-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            display: inline-block;
            background: #eff6ff;
            padding: 15px;
            border-radius: 12px;
            color: var(--sb-primary);
        }

        .sb-plan-icon {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sb-showcase {
            display: flex;
            flex-direction: column;
            gap: 60px;
        }

        .sb-showcase-item {
            display: grid;
            grid-template-columns: 3fr 2fr;
            gap: 40px;
            align-items: center;
        }

        /* Alternate layout for showcase items */
        .sb-showcase-item:nth-child(even) {
            direction: rtl; /* Switch order visually */
        }
        
        .sb-showcase-item:nth-child(even) .sb-showcase-desc {
            direction: ltr; /* Reset text direction */
        }

        .sb-image-placeholder {
            background-color: #e2e8f0;
            border-radius: var(--sb-border-radius);
            overflow: hidden;
            box-shadow: var(--sb-shadow);
            position: relative;
            /* aspect-ratio: 16/9; Removed to allow full height */
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #cbd5e1;
        }

        .sb-image-placeholder img {
            width: 100%;
            height: auto; /* Allow natural height */
            /* object-fit: cover; Removed to stop cropping */
            display: block;
        }
        
        /* Fallback if image helps verify placeholder */
        .sb-img-note {
            display: none; /* Hide note as images are now populated */
            position: absolute;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.9rem;
            pointer-events: none;
        }

        .sb-showcase-desc h4 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: var(--sb-secondary);
        }

        .sb-showcase-desc p {
            font-size: 1.1rem;
            color: var(--sb-text-light);
        }

        .sb-footer {
            text-align: center;
            padding: 40px;
            background-color: #f1f5f9;
            color: var(--sb-text-light);
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .sb-grid-2, .sb-grid-3, .sb-showcase-item {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .sb-showcase-item:nth-child(even) {
                direction: ltr;
            }

            .sb-hero h1 {
                font-size: 2.5rem;
            }
            
            .sb-section {
                padding: 40px 20px;
            }
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('stepbuilder_coming_soon', 'stepbuilder_coming_soon_shortcode');