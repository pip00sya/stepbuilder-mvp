# 📂 Custom PHP Snippets / Кастомные PHP сниппеты

This directory contains key functional logic extracted from the WordPress "Code Snippets" (or functions.php replacement). These snippets power the core non-standard functionality of StepBuilder.

---

## 📄 Snippets Overview

| File | Shortcode / Hook | Description |
|------|------------------|-------------|
| `college_profile.php` | `[college_profile]` | **The Workspace Core**. Multi-tab interface (College List, Checklist, Extracurricular, GPA). Handles AJAX saving of user application data. |
| `documents.php` | `[documents]` | **Editor & AI Assistant**. A massive module (3000+ lines) for portfolio/essay editing, document management, and deep AI integration for writing assistance. |
| `college_list_guide.php` | `[college_list_guide]` | Guided interface for building a college list with built-in AI chat support. |
| `custom_profile_menu.php` | `[custom_profile_menu]` | Avatar-based interactive profile menu for the header. |
| `custom_menu_links.php` | `[custom_menu_links]` | Contextual navigation bar for the documents and account areas. |
| `stepbuilder_cofounders.php`| `[stepbuilder_cofounders]`| Interactive co-founders section with bios and Shuffle logic. |
| `coming_soon.php` | `[stepbuilder_coming_soon]`| Modern landing page describing StepBuilder mission and future features. |
| `redirect.php` | `template_redirect` | Security layer: Redirects guests from `/account/`, `/user/`, and `/documents/` to the login page. |
| `dobavka.php` | `um_after_form` | Integration hook for Nextend Social Login inside Ultimate Member forms. |

---

## 🛠️ Usage Note

All snippets are designed to be included in a WordPress environment. 
- Most are triggered via **Shortcodes** (`[]`) on specific pages.
- Some contain **AJAX handlers** (`wp_ajax_...`) which the JS files in `/custom-code/js/` communicate with.
- **AI Integration**: Files like `documents.php` and `college_list_guide.php` use the `OPENAI_API_KEY` constant (defined in `wp-config.php`).

---

## 🇷🇺 Описание на русском

В этой папке собрана бизнес-логика проекта, которая обычно «спрятана» в базе данных плагинов:
- **`college_profile.php`**: Ядро личного кабинета (Колледж-лист, Чек-листы, GPA).
- **`documents.php`**: Самый большой модуль — редактор эссе и портфолио с интеграцией ИИ.
- **`redirect.php`**: Защита закрытых страниц от неавторизованных пользователей.
- **`custom_profile_menu.php`**: Кастомное меню профиля с аватаром.
