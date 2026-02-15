<?php
/**
 * Title: Testimonial
 * Slug: blockskit/testimonial
 * Categories: all, testimonials
 * Keywords: testimonial
 */
$blockskit_images = array(
    BLOCKSKIT_URL . 'assets/images/review-avatar.png',
);
?>

<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|xx-large"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--xx-large)"><!-- wp:columns {"verticalAlignment":"top","style":{"border":{"radius":"50px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top" style="border-radius:50px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:column {"verticalAlignment":"top","width":"100%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:100%"><!-- wp:heading {"textAlign":"center","style":{"typography":{"letterSpacing":"-0.03em","lineHeight":1.1}},"className":"animated animated-fadeInUp"} -->
<h2 class="wp-block-heading has-text-align-center animated animated-fadeInUp" style="letter-spacing:-0.03em;line-height:1.1"><?php esc_html_e( 'Reviews from Clients', 'blockskit' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|medium"}}},"className":"animated animated-fadeInUp"} -->
<p class="has-text-align-center animated animated-fadeInUp" style="padding-bottom:var(--wp--preset--spacing--medium)"><?php esc_html_e( 'Elementum quia fugit cum euismod, varius hymenaeos.', 'blockskit' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|large"}}},"className":"animated animated-fadeInUp"} -->
<div class="wp-block-columns are-vertically-aligned-top animated animated-fadeInUp" style="margin-bottom:var(--wp--preset--spacing--large)"><!-- wp:column {"verticalAlignment":"top","width":"","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-small","right":"var:preset|spacing|x-large","bottom":"0","left":"var:preset|spacing|x-large"}}},"layout":{"type":"default"}} -->
<div class="wp-block-column is-vertically-aligned-top" style="padding-top:var(--wp--preset--spacing--xx-small);padding-right:var(--wp--preset--spacing--x-large);padding-bottom:0;padding-left:var(--wp--preset--spacing--x-large)"><!-- wp:image {"align":"center","id":296,"width":80,"height":80,"sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image aligncenter size-full is-resized is-style-rounded"><img src="<?php echo esc_url($blockskit_images[0]); ?>" alt="" class="wp-image-296" width="80" height="80"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|x-small"}}},"fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size" style="padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--x-small)"><?php esc_html_e( '"Architecto autem facilis consequuntur rerum nemo! Torquent non, nostrum quis vestibulum magnis? Quos praesentium netus."', 'blockskit' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e( '— Anna Wong,', 'blockskit' ); ?> <em><?php esc_html_e( 'Client', 'blockskit' ); ?></em></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":"","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-small","right":"var:preset|spacing|x-large","bottom":"0","left":"var:preset|spacing|x-large"}}},"layout":{"type":"default"}} -->
<div class="wp-block-column is-vertically-aligned-top" style="padding-top:var(--wp--preset--spacing--xx-small);padding-right:var(--wp--preset--spacing--x-large);padding-bottom:0;padding-left:var(--wp--preset--spacing--x-large)"><!-- wp:image {"align":"center","id":296,"width":80,"height":80,"sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image aligncenter size-full is-resized is-style-rounded"><img src="<?php echo esc_url($blockskit_images[0]); ?>" alt="" class="wp-image-296" width="80" height="80"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|x-small"}}},"fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size" style="padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--x-small)"><?php esc_html_e( '"Architecto autem facilis consequuntur rerum nemo! Torquent non, nostrum quis vestibulum magnis? Quos praesentium netus."', 'blockskit' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e( '— Anna Wong,', 'blockskit' ); ?> <em><?php esc_html_e( 'Client', 'blockskit' ); ?></em></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:buttons {"className":"animated animated-fadeInUp","layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons animated animated-fadeInUp"><!-- wp:button {"style":{"spacing":{"padding":{"top":"16px","right":"20px","bottom":"16px","left":"20px"}},"border":{"radius":"6px"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#" style="border-radius:6px;padding-top:16px;padding-right:20px;padding-bottom:16px;padding-left:20px">More Review</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->