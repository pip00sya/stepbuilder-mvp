<?php
namespace Frontend_Admin\Woocommerce;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}



class Module {
    public function __construct() {
        
        add_action('woocommerce_loaded', array($this, 'register_custom_product_type'));
        add_filter('product_type_selector', array($this, 'add_custom_product_type'));
    }

    public function register_custom_product_type() {
        // Include the custom product type class file
        require_once plugin_dir_path(__FILE__) . 'product-types/subscription.php';
        class_exists( 'WC_Product' ) && class_exists( 'Frontend_Admin\Woocommerce\Subscription' ) && WC_Product_Factory::register_product_type( 'subscription', 'Frontend_Admin\Woocommerce\Subscription' );

    }

    public function add_custom_product_type($types) {
        $types['fea_subscription'] = __('Subscription Plan');
        return $types;
    }
}

new Module();