# 🌍 WordPress Environment / Среда WordPress

This folder contains the standard WordPress file structure. For the purpose of technical review, we maintain a **Clean Repository** where only necessary configuration and key assets are kept.

## 📁 Key Areas
- **`wp-content/themes/blockskit/`**: The base theme used for the platform.
- **`wp-content/plugins/`**: Core functional plugins (AI Engine, ACF, Ultimate Member) that power the custom logic in `/custom-code/`.
- **`wp-config-sample.php`**: Sanitized configuration file for deployment reference.

*Note: All proprietary logic and custom scripts have been extracted to [`/custom-code/`](../custom-code/) for easier source code audit.*
