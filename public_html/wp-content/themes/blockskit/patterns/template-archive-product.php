<?php
/**
 * Title: Template Archive Product
 * Slug: blockskit/template-archive-product
 * Categories: template
 * Inserter: false
 */
?>

<!-- wp:group {"tagName":"main","style":{"spacing":{"blockGap":"var:preset|spacing|x-large","padding":{"bottom":"var:preset|spacing|x-large"}}},"layout":{"type":"constrained"}} -->
<main class="wp-block-group" style="padding-bottom:var(--wp--preset--spacing--x-large)"><!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:var(--wp--preset--spacing--xx-large)"><!-- wp:columns {"className":"is-not-stacked-on-mobile"} -->
<div class="wp-block-columns is-not-stacked-on-mobile"><!-- wp:column {"width":"18%"} -->
<div class="wp-block-column" style="flex-basis:18%"></div>
<!-- /wp:column -->

<!-- wp:column {"width":"66.666%"} -->
<div class="wp-block-column" style="flex-basis:66.666%"><!-- wp:query-title {"type":"archive","textAlign":"center","showPrefix":false,"align":"wide"} /-->

<!-- wp:woocommerce/breadcrumbs {"align":"","className":"bt-woo-breadcrumb bt-woo-breadcrumb-center"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"18%"} -->
<div class="wp-block-column" style="flex-basis:18%"></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|70","right":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--30)"><!-- wp:woocommerce/store-notices /-->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:woocommerce/product-results-count /-->

<!-- wp:woocommerce/catalog-sorting /--></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":6,"query":{"perPage":9,"pages":0,"offset":0,"postType":"product","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":true,"__woocommerceAttributes":[],"__woocommerceStockStatus":["instock","outofstock","onbackorder"]},"displayLayout":{"type":"flex","columns":3},"namespace":"woocommerce/product-query"} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"products-block-post-template","__woocommerceNamespace":"woocommerce/product-query/product-template"} -->
<!-- wp:woocommerce/product-image {"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} /-->

<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"style":{"spacing":{"margin":{"bottom":"0.75rem","top":"0"}}},"fontSize":"medium","__woocommerceNamespace":"woocommerce/product-query/product-title"} /-->

<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"center"} /-->

<!-- wp:woocommerce/product-button {"isDescendentOfQueryLoop":true,"textAlign":"center"} /-->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results."} -->
<p></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></main>
<!-- /wp:group -->