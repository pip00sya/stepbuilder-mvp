<?php

add_action('init', function () {
    if (!session_id()) {
        session_start();
    }
});

add_action('template_redirect', function () {
    if (is_user_logged_in())
        return;

    $protected_paths = array(
        '/account/',
        '/user/',
        '/documents/',
    );

    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    foreach ($protected_paths as $path) {
        if (stripos($current_path, $path) === 0) {
            // Не редиректим, если уже на странице логина
            if ($current_path === '/login/')
                return;

            // Сохраняем URL в сессии
            $_SESSION['stepbuilder_redirect_after_login'] = home_url($current_path);
            wp_redirect(home_url('/login/'));
            exit;
        }
    }
});

add_action('wp_login', function ($user_login, $user) {
    if (!session_id()) {
        session_start();
    }
    if (!empty($_SESSION['stepbuilder_redirect_after_login'])) {
        $redirect_url = $_SESSION['stepbuilder_redirect_after_login'];
        unset($_SESSION['stepbuilder_redirect_after_login']);
        wp_redirect($redirect_url);
        exit;
    }
}, 10, 2);
