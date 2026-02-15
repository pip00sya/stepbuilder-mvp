# 🏗️ Project Structure / Структура проекта — StepBuilder

This document describes the organization of the StepBuilder repository. We use a **Clean WordPress Repository** approach, keeping only custom logic and configurations to ensure technical clarity for the jury.

---

## 📂 Root Directory

| Item | Description | Описание |
|------|-------------|----------|
| `README.md` | Main project documentation and live links. | Главная документация проекта и ссылки. |
| `STRUCTURE.md` | This file — repository map. | Этот файл — карта репозитория. |
| `.gitignore` | Excludes WP Core, bulk media, and market plugins. | Исключает ядро WP, медиа и стандартные плагины. |
| `custom-code/` | **[CRITICAL]** All manually written JS/CSS/PHP logic. | **[ВАЖНО]** Весь написанный вручную код. |
| `database/` | Place your database export (SQL/XML) here. | Разместите здесь дамп базы данных (SQL/XML). |
| `screenshots/` | Interface screenshots for visual review. | Скриншоты интерфейса для визуальной оценки. |

---

## 📝 Custom Code (`/custom-code`)

We have extracted all core logic from the "Simple Custom CSS and JS" plugin into these folders for easy source review:

- **`js/`**:
  - `ai-chatbot-handler.js`: AI chatbot response processing & AJAX user profile updates.
  - `acf-autosave.js`: Real-time data persistence for application forms.
  - `dropdown-close-fix.js`: UI/UX fix for navigation menus.
  - `close-button-redirect.js`: Custom navigation logic for auth pages.
- **`css/`**:
  - `main-ui-overrides.css`: Global styling and themes.
  - `acf-forms.css`: Professional styling for document submission forms.
  - `buttons.css`: Brand color schemes and interactive states.
  - `auth-forms-v1...v4.css`: Multiple iterations of login/register UI evolution.
- **`php/`**:
  - `college_profile.php`: The Workspace core logic.
  - `documents.php`: Huge module for Doc Editing & AI Assistant.
  - `redirect.php`: Access control and security logic.
  - ...and other functional shortcodes.
- **`php/`**:
  - `college_profile.php`: The Workspace core logic.
  - `documents.php`: Huge module for Doc Editing & AI Assistant.
  - `redirect.php`: Access control and security logic.
  - ...and other functional shortcodes.

---

## 💾 Content & Templates (Database) / Контент и Шаблоны

Unlike custom code, your **pages and edited templates** (edited via Elementor or Site Editor) are stored in the WordPress database. 

- **To see the full site**: The jury needs to import the file from the `/database` folder.
- **Visual Review**: Check the `/screenshots` folder for a quick look at the designed pages.

---

## 🌍 WordPress Site (`/public_html`)

Standard WordPress installation with key custom areas:

```
public_html/
├── wp-config-sample.php     # Sanitized config (security first!)
└── wp-content/
    ├── themes/
    │   └── blockskit/       # Core block-based theme
    ├── plugins/             # Core functional plugins (AI Engine, etc.)
    └── uploads/             # Managed assets
```
