<?php

add_shortcode('custom_menu_links', function () {
    if (!is_user_logged_in())
        return '';

    $links = [
        'Coming Soon' => 'https://stepbuilder.org/content/',
        'About' => 'https://stepbuilder.org/about/',
        'Guides' => 'https://stepbuilder.org/guides/',
        'My Documents' => 'https://stepbuilder.org/documents/',
    ];

    $current_url = home_url(add_query_arg([], $_SERVER['REQUEST_URI']));

    ob_start();
    echo '<div class="custom-portfolio-menu">';
    foreach ($links as $text => $url) {
        $is_active = (rtrim($current_url, '/') === rtrim($url, '/'));
?>
        <a href="<?php echo esc_url($url); ?>" class="custom-portfolio-link<?php echo $is_active ? ' active' : ''; ?>"><?php echo esc_html($text); ?></a>
        <?php
    }
    echo '</div>';
    return ob_get_clean();
});

add_action('wp_enqueue_scripts', function () {
    wp_register_style('custom-portfolio-link-style', false);
    wp_enqueue_style('custom-portfolio-link-style');
    wp_add_inline_style('custom-portfolio-link-style', '
        .custom-portfolio-menu {
            display: flex;
            gap: 24px; /* расстояние между ссылками */
            align-items: center;
			padding-right: 35px;
        }
        .custom-portfolio-link {
            font-size: 18px;
            font-family: Arial;
            color: #333842;
            text-decoration: none;
            transition: color 0.18s, text-decoration-color 0.18s;
        }
        .custom-portfolio-link:hover{
            color: #001b61;
            text-decoration-color: #001b61;
        }
        .custom-portfolio-link.active {
            text-decoration: underline;
            text-decoration-thickness: 1px;
            text-underline-offset: 7px;
            text-decoration-color: #001b61;
        }
		        @media (max-width: 768px) {
            .custom-portfolio-menu {
                display: none !important;
            }
        }
    ');
});