<?php
/**
 * Title: Footer Minimal Center Menu
 * Slug: blockskit/footer-minimal-center-menu
 * Categories: all, footer
 */
?>

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|medium","padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|large"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--x-large);padding-bottom:var(--wp--preset--spacing--large)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|xx-small"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:site-logo {"width":36} /-->

<!-- wp:site-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium"} /--></div>
<!-- /wp:group -->

<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|medium"}}} -->
<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Home', 'blockskit' ); ?>","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"<?php esc_html_e( 'About', 'blockskit' ); ?>","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Blog', 'blockskit' ); ?>","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Contact', 'blockskit' ); ?>","url":"#","kind":"custom","isTopLevelLink":true} /-->
<!-- /wp:navigation -->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:social-links {"iconColor":"heading","iconColorValue":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dcolor\u002d\u002dheading)","openInNewTab":true,"size":"has-small-icon-size","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|x-small","left":"var:preset|spacing|x-small"}}},"className":"is-style-logos-only"} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"youtube"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|xx-small"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
<p class="has-text-align-center has-x-small-font-size"><?php echo sprintf( __( '© %d ·', 'blockskit' ), date_i18n("Y")); ?></p>
<!-- /wp:paragraph -->

<!-- wp:site-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"400"}},"fontSize":"x-small"} /-->

<!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
<p class="has-text-align-center has-x-small-font-size"><?php esc_html_e( '· All rights reserved', 'blockskit' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->