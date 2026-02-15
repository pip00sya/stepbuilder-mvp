<?php
/**
 * Title: Template Product Search Results
 * Slug: blockskit/template-product-search-results
 * Categories: template
 * Inserter: false
 */
?>

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|x-large","padding":{"bottom":"var:preset|spacing|x-large"}}},"layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group" style="padding-bottom:var(--wp--preset--spacing--x-large)"><!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:var(--wp--preset--spacing--xx-large)"><!-- wp:woocommerce/breadcrumbs {"align":""} /-->

<!-- wp:query-title {"type":"search","textAlign":"left","showPrefix":false} /--></div>
<!-- /wp:group -->

<!-- wp:woocommerce/store-notices {"align":""} /-->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:woocommerce/product-results-count /-->

<!-- wp:woocommerce/catalog-sorting /--></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":4,"query":{"perPage":9,"pages":0,"offset":0,"postType":"product","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":true,"__woocommerceAttributes":[],"__woocommerceStockStatus":["instock","outofstock","onbackorder"]},"displayLayout":{"type":"flex","columns":3},"namespace":"woocommerce/product-query"} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"products-block-post-template","__woocommerceNamespace":"woocommerce/product-query/product-template"} -->
<!-- wp:woocommerce/product-image {"isDescendentOfQueryLoop":true} /-->

<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"fontSize":"medium","__woocommerceNamespace":"woocommerce/product-query/product-title"} /-->

<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"center","style":{"spacing":{"margin":{"bottom":"1rem"}}}} /-->

<!-- wp:woocommerce/product-button {"isDescendentOfQueryLoop":true,"textAlign":"center","style":{"spacing":{"margin":{"bottom":"1rem"}}}} /-->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p><?php esc_html_e( 'No products were found matching your selection.', 'blockskit' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:search {"showLabel":false,"placeholder":"Search products…","query":{"post_type":"product"}} /-->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->