# 🚀 StepBuilder — AI-Powered College Admissions Workspace

> **MVP Is Live:** [https://stepbuilder.org/](https://stepbuilder.org/)
>
> ![StepBuilder Preview](./screenshots/stepbuilder.org.png)

StepBuilder is an all-in-one platform designed to simplify the complex journey of college admissions. By combining structured workflows with advanced AI assistance, we empower students to build perfect college lists, manage their application milestones, and craft compelling essays.

---

## 🌟 Key Features

- **🎯 AI College List Assistant**: A guided interface to help students find "Reach", "Match", and "Safety" schools based on their profile.
- **✍️ AI Essay Editor**: A massive collaboration module for document editing with deep AI integration for brainstorming and refining essays.
- **📋 Management Workspace**: Centralized dashboard for tracking extracurriculars, GPA, and application checklists.
- **🔒 Security & UX**: Custom-built redirection layers and responsive interface variations for a premium user experience.

---

## 🏗️ Repository Organization (Clean WP Approach)

To ensure technical clarity for the jury, this repository follows a **Clean WordPress** structure. We have separated the core platform logic from the standard WordPress installation:

| Folder | Purpose |
| :--- | :--- |
| **`custom-code/`** | **[CORE LOGIC]** All manually written JS, CSS, and PHP snippets. This is where the real "magic" happens. |
| **`STRUCTURE.md`** | A technical map of the entire repository for source code review. |
| **`public_html/`** | Standard WordPress environment setup (Themes, Plugins config). |
| **`database/`** | Exported data and structure instructions. |
| **`screenshots/`** | Visual walkthrough of the interface. |

---

## 🛠️ Tech Stack

- **Core**: WordPress (Headless Logic)
- **Frontend**: Custom JS (ES6+), Vanilla CSS (Modern UI/UX Overrides)
- **Backend**: PHP (Custom AJAX Handlers, Security Hooks)
- **AI**: OpenAI API Integration (GPT-3.5/GPT-4)
- **Database**: MySQL (WP Schema)

---

## 👨‍💻 How to Review

1. **Review Custom Logic**: Navigate to [`custom-code/`](./custom-code/) to see the proprietary code developed for StepBuilder.
2. **Explore the Live Site**: Visit [stepbuilder.org](https://stepbuilder.org/) to see the platform in action.
3. **Check Architecture**: Read [`STRUCTURE.md`](./STRUCTURE.md) for a detailed breakdown of how components interact.

---

## 🇷🇺 Описание на русском

**StepBuilder** — это инновационная платформа для автоматизации процесса поступления в зарубежные вузы. Мы используем ИИ, чтобы помочь студентам структурировать свои достижения и писать выдающиеся эссе.

- **Живой проект**: [stepbuilder.org](https://stepbuilder.org/)
- **Особенности**: Платформа объединяет в себе управление документами, ИИ-ассистента и интерактивные чеклисты.
- **Технологии**: WordPress в качестве ядра, кастомный стек JS/PHP для AI-интеграции и уникального интерфейса.
