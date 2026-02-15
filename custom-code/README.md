# Custom Code / Кастомный код — StepBuilder

This directory contains all **custom-written code** (CSS & JavaScript snippets) used on the StepBuilder platform. These snippets were created via the "Simple Custom CSS and JS" WordPress plugin and are extracted here for clarity and review.

---

- **[php/](./php/)**: **[CORE BUSINESS LOGIC]** WordPress hooks, shortcodes, AI handlers, and security logic.
- **[js/](./js/)**: **[INTERACTIVE LOGIC]** AI chatbot processing, form autosave, and UI fixes.
- **[css/](./css/)**: **[VISUAL DESIGN]** Global overrides, branding, and responsive layouts.

---

## 🐘 PHP Logic (`/php`)

| File | Shortcode / Hook | Description |
| :--- | :--- | :--- |
| `documents.php` | `[documents]` | **Editor & AI Assistant**. Massive module for essay editing and deep AI integration. |
| `college_profile.php`| `[college_profile]`| **Workspace Core**. Multi-tab dashboard for tracking applications. |
| `college_list_guide.php`| `[college_list_guide]`| Interactive guide for building college lists with AI chat. |
| `redirect.php` | `template_redirect` | **Security Layer**. Access control for private user areas. |
| `custom_profile_menu.php`| `[custom_profile_menu]`| Avatar-based interactive profile menu for the header. |
| `custom_menu_links.php`| `[custom_menu_links]`| Dynamic navigation links for user account pages. |
| `stepbuilder_cofounders.php`| `[stepbuilder_cofounders]`| Interactive section for team bios with shuffle logic. |
| `coming_soon.php` | `[stepbuilder_coming_soon]`| Mission-focused landing page for upcoming features. |
| `dobavka.php` | `um_after_form` | Social login integration for Ultimate Member forms. |

## 📜 JavaScript (`/js`)

| File | Description |
| :--- | :--- |
| `ai-chatbot-handler.js` | Monitors AI responses and updates user profile fields via AJAX. |
| `acf-autosave.js` | Real-time "Type-to-Save" data persistence for application forms. |
| `dropdown-close-fix.js` | Professional UX fix for closing menus on click-outside/Escape. |
| `close-button-redirect.js`| Custom navigation logic for authentication pages. |

## 🎨 CSS Styles (`/css`)

| File | Context | Description |
| :--- | :--- | :--- |
| `main-ui-overrides.css` | **Global** | Core branding, glassmorphism, Inter typography, and grid systems. |
| `acf-forms.css` | **Editor** | Professional styling for document submission and editing forms. |
| `buttons.css` | **Branding** | Brand color schemes and interactive states for all buttons. |
| `auth-forms-v1..v4.css`| **Auth** | Iterative evolution of login/register UI design. |
| `login-page-81.css` | **Landing** | Page-specific layout optimizations for the entry point. |

---

## 🇷🇺 Описание на русском

Папка `custom-code` является сердцем StepBuilder. Здесь хранится весь уникальный код проекта:
- **`php/`**: Бизнес-логика, шорткоды и серверная интеграция с OpenAI.
- **`js/`**: Интерактивные функции, автосохранение и клиентская часть AI-ассистента.
- **`css/`**: Визуальный стиль, современные UI-эффекты и полная адаптивность.
