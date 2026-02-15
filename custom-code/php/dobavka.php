<?php

add_action('um_after_form', function ($args) {
    // Только для формы входац
    if ($args['mode'] === 'login') {
        echo do_shortcode('[nextend_social_login]');
    }
    // Только для формы регистрации
    if ($args['mode'] === 'register') {
        echo do_shortcode('[nextend_social_login]');
    }
});