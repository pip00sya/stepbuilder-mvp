# 📜 Custom JavaScript / Кастомный JavaScript

This directory contains specialized JavaScript modules that handle interactive elements and real-time data processing on the StepBuilder platform.

---

## 📂 Modules Overview

| File | Module | Description |
| :--- | :--- | :--- |
| `ai-chatbot-handler.js` | **AI Processor** | Monitors chatbot responses and automatically updates user profiles via AJAX when the AI triggers a specific action. |
| `acf-autosave.js` | **Data Persistence** | Implements "Type-to-Save" logic for ACF forms. Ensures no data is lost during essay writing or profile building. |
| `dropdown-close-fix.js` | **UX Enhancement** | Fixes standard UI bugs in Ultimate Member menus, providing professional look-and-feel. |
| `close-button-redirect.js`| **Navigation** | Custom routing logic for the authentication flow. |

---

## 🛠️ Technical Integration

- **AJAX**: Scripts communicate with the PHP handlers defined in `custom-code/php/`.
- **Frameworks**: Pure Vanilla JS (ES6+) for maximum performance and compatibility with WordPress.
- **Trigger**: Most scripts are enqueued via specific page IDs to maintain a fast load time.

---

## 🇷🇺 Описание на русском

Здесь собраны скрипты, которые делают StepBuilder интерактивным:
- **`ai-chatbot-handler.js`**: Обработка команд от ИИ и авто-обновление профиля.
- **`acf-autosave.js`**: Автосохранение данных в формах (черновиках).
- **`dropdown-close-fix.js`**: Фикс выпадающих меню.
