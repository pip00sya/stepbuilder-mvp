<?php
namespace Frontend_Admin\Actions;

use Frontend_Admin\Plugin;
use Frontend_Admin;
use Frontend_Admin\Classes\ActionBase;
use Frontend_Admin\Forms\Actions;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Checkout' ) ) :

	class Checkout extends ActionBase {
		public function __construct() {
			//add_action( 'woocommerce_product_query', [ $this, 'exclude_from_products_queries' ] );
			add_action( 'woocommerce_thankyou', array( $this, 'approve_submission' ), 10, 1);
			add_filter( 'frontend_admin/form/check_requirements', [ $this, 'check_action' ], 10, 2 );
			//add_action( 'pre_get_posts', [ $this, 'exclude_products_from_admin_list' ] );
			//add_filter( 'wp_count_posts', [ $this, 'adjust_admin_product_count' ], 10, 2 );
			add_filter( 'frontend_admin/forms/redirect_url', [ $this, 'redirect_url' ], 10, 2 );
		}

		function redirect_url( $url, $form ) {
			if ( ! empty( $form['checkouts'] ) ) {
				$checkout_items = $form['checkouts'];
			} else {
				if ( ! empty( $form['submit_actions'] ) ) {
					$actions = $form['submit_actions'];
					if ( $actions ) {
						$checkout_items = array();
						foreach ( $actions as $action ) {
							if ( 'checkout' == $action['fea_block_structure'] ) {
								$checkout_items[] = $action;
							}
						}
					}
				}
			}

			if ( ! empty( $checkout_items ) ) {
				$url = wc_get_checkout_url();
			}

			return $url;
		}

		function check_action( $requirments, $form ) {
			
			if ( ! empty( $form['checkouts'] ) ) {
				$checkout_items = $form['checkouts'];
			} else {
				if ( ! empty( $form['submit_actions'] ) ) {
					$actions = $form['submit_actions'];
					if ( $actions ) {
							$checkout_items = array();
						foreach ( $actions as $action ) {
							if ( 'checkout' == $action['fea_block_structure'] ) {
									$checkout_items[] = $action;
							}
						}
					}
				}
			}


			if ( ! empty( $checkout_items ) ) {
				$requirments[] = 'pending_payment';
			}
		

			return $requirments;
		}

		function approve_submission( $order_id ) {
			if ( ! $order_id )
				return;
		
			// Allow code execution only once 
			if( ! get_post_meta( $order_id, '_thankyou_action_done', true ) ):
		
				// Get an instance of the WC_Order object
				$order = wc_get_order( $order_id );
		
				// Get the order key
				$order_key = $order->get_order_key();
		
				// Get the order number
				$order_key = $order->get_order_number();

				$redirect = '';

				global $fea_instance;
		
				// Loop through order items
				foreach ( $order->get_items() as $item_id => $item ) :

					$quantity = $item->get_quantity();

					// Get the product ID
					$product_id = $item->get_product_id();
					
					$fea_submission = get_post_meta( $product_id, '_fea_submission', true );

					if( ! $fea_submission ) continue;

					$allowed_submits = absint( get_post_meta( $product_id, '_fea_submissions_amount', true ) );

					$form_id = get_post_meta( $product_id, '_fea_form_id', true );

					if( ! $form_id ){
						$meta_key = '_fea_remaining_submits';
					}else{
						$meta_key = $form_id . '_remaining_submits';
					}

					$previous_submits = get_user_meta( $order->get_customer_id(), $meta_key, true );

					$remaining_submits = absint($previous_submits) + absint(( $allowed_submits * $quantity )) - 1;				
	
					update_user_meta( $order->get_customer_id(), $meta_key, $remaining_submits );
					
					if( $fea_submission ) wp_delete_post( $product_id, true );

					do_action( 'frontend_admin/submission/change_status', $fea_submission, 'payment_received' );

					$redirect = get_post_meta( $product_id, '_fea_redirect_to', true );
					
				
				endforeach;
		
				// Flag the action as done (to avoid repetitions on reload for example)
				$order->update_meta_data( '_thankyou_action_done', true );
				$order->save();

				if( $redirect ){
					wp_redirect( $redirect );
					exit;
				}
			endif;
		}
		

		function adjust_admin_product_count( $counts, $type ) {
			// Only modify counts for products
			if ( $type !== 'product' ) {
				return $counts;
			}
		
			global $wpdb;
		
			// Get the counts for products excluding those with the '_fea_submission' meta key
			$query = "
				SELECT post_status, COUNT(*) AS num_posts 
				FROM {$wpdb->posts} 
				WHERE post_type = 'product' 
				AND ID NOT IN (
					SELECT post_id 
					FROM {$wpdb->postmeta} 
					WHERE meta_key = '_fea_submission'
				) 
				GROUP BY post_status
			";
		
			// Fetch results
			$results = $wpdb->get_results( $query, ARRAY_A );
		
			// Reset counts
			foreach ( $counts as $status => $count ) {
				$counts->$status = 0;
			}
		
			// Populate counts with the filtered results
			foreach ( $results as $row ) {
				$status = $row['post_status'];
				$counts->$status = $row['num_posts'];
			}
		
			return $counts;
		}
		
		public $site_domain = '';

		public function get_name() {
			return 'checkout';
		}

		public function get_label() {
			return __( 'Woo Checkout', 'acf-frontend-form-element' );
		}
		function exclude_products_from_admin_list( $query ) {
			// Only modify queries in the admin for products
			if ( $query->get( 'post_type' ) === 'product' ) {
				// Exclude products with '_fea_submission' meta key
				$meta_query = $query->get( 'meta_query' );
				if ( ! is_array( $meta_query ) ) {
					$meta_query = [];
				}
		
				$meta_query[] = array(
					'key'     => '_fea_submission',
					'compare' => 'NOT EXISTS',
				);
		
				$query->set( 'meta_query', $meta_query );
			}
		}
		public function action_options() {
			$fields = array(
				array(
					'label'         => __('Product', 'acf-frontend-form-element'),
					'instructions'  => __('What will the user receive?', 'frontend-admin'),
					'type'          => 'select',
					'allow_null'    => false,
					'name'          => 'product',
					'default_value' => 'submission',
					'key'          => 'product',
					'choices'       => array(
						'submission' => __('Form Submissions', 'acf-frontend-form-element'),
						'plan'       => __('Plan', 'acf-frontend-form-element'),
					)
				),
				array(
					'label'         => __('Price', 'acf-frontend-form-element'),
					'instructions'  => '',
					'type'          => 'text',
					'name'          => 'payment_amount',
					'dynamic_value_choices' => 1,
					'key'          => 'payment_amount',
					'default_value' => '1',
					'min'           => '0.1',
					'conditional_logic'    => array(
						array(
							'field'     => 'product',
							'operator'  => '==',
							'value'     => 'submission',
						)
					),
				),
				array(
					'label'         => __('Currency', 'acf-frontend-form-element'),
					'instructions'  => '',
					'type'          => 'select',
					'key'          => 'payment_currency',
					'name'          => 'payment_currency',
					'choices'       => feadmin_get_currencies(),
					'default_value' => get_woocommerce_currency() ?? 'USD',
					'conditional_logic'    => array(
						array(
							'field'     => 'product',
							'operator'  => '==',
							'value'     => 'submission',
						)
					),
				),
				array(
					'label'         => __('Amount of Submissions', 'acf-frontend-form-element'),
					'instructions'  => __('Number of submissions for this price', 'acf-frontend-form-element'),
					'type'          => 'text',
					'name'          => 'submissions_amount',
					'key'           => 'submissions_amount',
					'default_value' => '1',
					'dynamic_value_choices' => 1,
					'min'           => '1',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'product',
								'operator' => '==',
								'value'    => 'submission',
							),
						),
					),
				),
				array(
					'label'         => __('Redirect Afer Payment', 'acf-frontend-form-element'),
					'instructions'  => __('Where to redirect after payment', 'acf-frontend-form-element'),
					'type'          => 'text',
					'name'          => 'payment_redirect',
					'key'           => 'payment_redirect',
					'default_value' => '1',
					'dynamic_value_choices' => 1,
					'min'           => '1',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'product',
								'operator' => '==',
								'value'    => 'submission',
							),
						),
					),
				),
				array(
					'label'         => __('Limit to Current Form', 'acf-frontend-form-element'),
					'instructions'  => __('Limit the submissions to the current form only', 'acf-frontend-form-element'),
					'type'          => 'true_false',
					'name'          => 'limit_to_current_form',
					'key'           => 'limit_to_current_form',
					'default_value' => true,
				),
				array(
					'label'         => __('Plan', 'acf-frontend-form-element'),
					'instructions'  => '',
					'type'          => 'fea_plans',
					'allow_null'    => true,
					'name'          => 'plan',
					'key'          => 'plan',
					'placeholder'   => __('Choose Plan', 'acf-frontend-form-element'),
					'conditional_logic'    => array(
						array(
							array(
								'field'     => 'product',
								'operator'  => '==',
								'value'     => 'plan',
							),
						)
					),
					'add_plan'      => true,
					'edit_plans'    => true,
				),
				array(
					'label'         => __('Plan Message', 'acf-frontend-form-element'),
					'instructions'  => sprintf(__('Use %s to display the remaining submissions left for the current user.', 'acf-frontend-form-element'), '[remaining_submits]'),
					'rows'          => 3,
					'default_value' => sprintf(__('You still have %s submissions left.', 'acf-frontend-form-element'), '[remaining_submits]'),
					'type'          => 'textarea',
					'name'          => 'already_paid_message',
					'key'          => 'already_paid_message',
					'conditional_logic'    => array(
						array(
							array(
								'field'     => 'product',
								'operator'  => '==',
								'value'     => 'plan',
							),
						)
					),
				),
				array(
					'label'         => __('Payment Description', 'acf-frontend-form-element'),
					'instructions'  => __('Description of payment for the checkout page', 'acf-frontend-form-element'),
					'type'          => 'textarea',
					'dynamic_value_choices' => 1,
					'name'          => 'payment_description',
					'key'          => 'payment_description',
				),
				array(
					'label'         => __('Payment Image', 'acf-frontend-form-element'),
					'instructions'  => __('Upload an image that will display in the cart and checkout', 'acf-frontend-form-element'),
					'type'          => 'image',
					'name'          => 'product_image',
					'key'           => 'product_image',
				),
				array(
					'key'                   => 'dynamic_image',
					'label'                 => __( 'Image Field', 'acf-frontend-form-element' ),
					'name'                  => 'dynamic_image',
					'type'                  => 'text',
					'instructions'          => '',
					'required'              => 0,
					'default_value'         => '[product:featured_image]',
					'wrapper' 				=> [
						'class' => 'post-slug-field'
					],
					'placeholder'           => '',
					'prepend'               => '',
					'append'                => '',
					'maxlength'             => '',
				),
				
			);
			//$fields = apply_filters( 'frontend_admin/action_settings/type=' . $this->get_name(), $fields );

			return $fields;
		}

		public function register_settings_section( $widget ) {
			$site_domain = feadmin_get_site_domain();

			$repeater = new \Elementor\Repeater();

			$tab = apply_filters( 'frontend_admin/elementor/form_widget/control_tab', Controls_Manager::TAB_CONTENT, $widget );
			$condition = apply_filters( 
				'frontend_admin/elementor/form_widget/conditional_logic',
				[ 'more_actions' => $this->get_name() ],
				$widget
			);

			$widget->start_controls_section(
				'section_checkout',
				array(
					'label'     => $this->get_label(),
					'tab'       => $tab,
					'condition' => $condition,
				)
			);

		

			$fields = array(				
				array(
					'label'       => __( 'Action Name', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => __( 'Woo Checkout', 'acf-frontend-form-element' ),
					'default'     => __( 'Woo Checkout', 'acf-frontend-form-element' ),
					'label_block' => true,
					'key'          => 'action_name',
					'name'          => 'action_name',
					'description' => __( 'Give this action an identifier', 'acf-frontend-form-element' ),
					'render_type' => 'none',
				),
				array(
					'label'         => __('Product', 'acf-frontend-form-element'),
					'type'          => 'select',
					'name'          => 'product',
					'key'          => 'product',
					'options'       => array(
						'submission' => __('Form Submission', 'acf-frontend-form-element'),
						'plan'       => __('Plan', 'acf-frontend-form-element'),
					),
					'description'   => __('What will the user receive?', 'frontend-admin'),
				),
				array(
					'label'         => __('Plan', 'acf-frontend-form-element'),
					'type'          => 'text',
					'name'          => 'plan',
					'condition'     => [
						'product' => 'plan',
					],
					'placeholder'   => __('Choose Plan', 'acf-frontend-form-element'),
				),
				array(
					'label'         => __('Plan Message', 'acf-frontend-form-element'),
					'type'          => 'textarea',
					'name'          => 'already_paid_message',
					'default'       => sprintf(__('You still have %s submissions left.', 'acf-frontend-form-element'), '[remaining_submits]'),
					'description'   => sprintf(__('Use %s to display the remaining submissions left for the current user.', 'acf-frontend-form-element'), '[remaining_submits]'),
					'condition'     => [
						'product' => 'plan',
					],
				),
				array(
					'label'         => __('Payment Description', 'acf-frontend-form-element'),
					'type'          => 'textarea',
					'dynamic'     => array(
						'active' => true,
					),
					'name'          => 'payment_description',
					'description'   => __('Description of payment for the checkout page. You can use field values like so: [post:field_name]', 'acf-frontend-form-element'),
				),
				array(
					'label'         => __('Payment Image', 'acf-frontend-form-element'),
					'instructions'  => __('Upload an image that will display in the cart and checkout', 'acf-frontend-form-element'),
					'type'          => 'media',
					'name'          => 'product_image',
					'dynamic'     => array(
						'active' => true,
					),
				),
				array(
					'label'         => __('Amount to Charge', 'acf-frontend-form-element'),
					'type'          => 'text',
					'name'          => 'payment_amount',
					'default'       => 1,
					'min'           => 0.1,
					'condition'     => [
						'product' => 'submission',
					],
				),
				array(
					'label'         => __('Currency', 'acf-frontend-form-element'),
					'type'          => 'select',
					'name'          => 'payment_currency',
					'options'       => feadmin_get_currencies(),
					'default'       => 'USD',
					'condition'     => [
						'product' => 'submission',
					],
				),
				array(
					'label'         => __('Amount of Submissions', 'acf-frontend-form-element'),
					'type'          => 'text',
					'name'          => 'submissions_amount',
					'default'       => '1',
					'min'           => 1,
					'description'   => __('Number of submissions for this price', 'acf-frontend-form-element'),
					'condition'     => [
						'product' => 'submission',
					],
				),
				array(
					'label'         => __('Redirect Afer Payment', 'acf-frontend-form-element'),
					'type'          => 'text',
					'name'          => 'payment_redirect',
					'description'   => __('Where to redirect after payment', 'acf-frontend-form-element'),
					'condition'     => [
						'product' => 'submission',
					],
				),
				array(
					'label'         => __('Limit to Current Form', 'acf-frontend-form-element'),
					'type'          => 'switcher',
					'name'          => 'limit_to_current_form',
					'default'       => true,
					'description'   => __('Limit the submissions to the current form only', 'acf-frontend-form-element'),
					'condition'     => [
						'product' => 'submission',
					],
				),
			);
	
			// Add fields as Elementor controls
			foreach ($fields as $field) {
				$args = $field;
	
				if (!empty($field['options'])) {
					$args['options'] = $field['options'];
				}
	
				if (!empty($field['placeholder'])) {
					$args['placeholder'] = $field['placeholder'];
				}
	
				if (!empty($field['min'])) {
					$args['min'] = $field['min'];
				}

				$args['render_type'] = 'none';
	
				$repeater->add_control($field['name'], $args);
			}


			$widget->add_control(
				'checkouts_to_send',
				array(
					'label'       => __( 'Woo Checkout', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'title_field' => '{{{ action_name }}}',
					'render_type' => 'none',
				)
			);

			$widget->end_controls_section();
		}

		public function run( $form ) {
			if( ! empty( $form['approval'] ) ) return;
			if ( ! empty( $form['checkouts'] ) ) {
				$checkout_items = $form['checkouts'];
			} else {
				if ( empty( $checkout_items ) && ! empty( $form['submit_actions'] ) ) {
					$actions = $form['submit_actions'];
					if ( $actions ) {
						   $checkout_items = array();
						foreach ( $actions as $action ) {
							if ( $action['fea_block_structure'] == 'checkout' ) {
								   $checkout_items[] = $action;
							}
						}
					}
				}
			}

			if ( empty( $checkout_items ) ) {
				return;
			}

			foreach ( $checkout_items as $checkout ) {
				$form_id = $checkout['limit_to_current_form'] ? $form['id'] : null;

				if( ! $form_id ){
					$meta_key = '_fea_remaining_submits';
				}else{
					$meta_key = $form_id . '_remaining_submits';
				}

				$user_id = get_current_user_id();
				$previous_submits = get_user_meta( $user_id, $meta_key, true );

				if( $user_id && $previous_submits > 0 ){
					$previous_submits--;
					update_user_meta( $user_id, $meta_key, $previous_submits );
					continue;
				}

				$product = $checkout['product'];
				$amount = $checkout['payment_amount'];
				$currency = $checkout['payment_currency'];
				$description = $checkout['payment_description'];
				

				$submission = $form['submission'];
				$this->check_prerequisites();

					// Add submission product to cart
					$product_id = $this->find_submission_product( $submission, $checkout, $form );
					if( $product_id ) WC()->cart->add_to_cart($product_id, 1, '', '');


			}

		}

		function check_prerequisites() {
    
			// var_dump(WC()->cart);
			
			if ( defined( 'WC_ABSPATH' ) ) {
				// WC 3.6+ - Cart and other frontend functions are not included for REST requests.
				include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
				include_once WC_ABSPATH . 'includes/wc-notice-functions.php';
						include_once WC_ABSPATH . 'includes/wc-template-hooks.php';
			}
		
			if ( null === WC()->session ) {
				$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
		
				WC()->session = new $session_class();
				WC()->session->init();
			}
		
			if ( null === WC()->customer ) {
				WC()->customer = new WC_Customer( get_current_user_id(), true );
			}
		
			if ( null === WC()->cart ) {
				WC()->cart = new WC_Cart();
		
				WC()->cart->get_cart();
			}
		}

		function exclude_from_products_queries( $query ) {
			$meta_query = $query->get( 'meta_query' );

			// Exclude products with '_fea_submission' meta key
			$meta_query[] = array(
				'key'     => '_fea_submission',
				'compare' => 'NOT EXISTS',
			);
			$query->set( 'meta_query', $meta_query );
		}
		public function find_submission_product( $submission, $checkout, $form = null ) {
			// Try to find the product with the given '_fea_submission' meta
			$args = array(
				'post_type'   => 'product',
				'posts_per_page' => 1,
				'meta_query'  => array(
					array(
						'key'   => '_fea_submission',
						'value' => $submission,
					),
				),
			);
		
			$posts = get_posts( $args );
		
			if ( $posts ) {
				return $posts[0]->ID;
			} else {
				if( $checkout['limit_to_current_form'] ){
					global $fea_form, $fea_instance;
					
					$submission_item = $fea_instance->submissions_handler->get_submission( $submission );
					$form_id = $submission_item->form;
				}

				$price = absint( $fea_instance->dynamic_values->get_dynamic_values( $checkout['payment_amount'], $form ) );
				$submissions = absint( $fea_instance->dynamic_values->get_dynamic_values( $checkout['submissions_amount'], $form ) );
				$description = sanitize_text_field( $fea_instance->dynamic_values->get_dynamic_values( $checkout['payment_description'], $form ) );

				$redirect = $checkout['payment_redirect'] ?? '';

				if( ! $redirect ){
					$redirect = $fea_instance->form_submit->get_redirect_url( $form );
				}

				// Create a new product
				$product = new \WC_Product();

				$item = $checkout['product_image'] ?? 'submission';

				if( 'plan' == $item ){
					$plan = fea_instance()->plans_handler->get_plan( $checkout['plan'] );
					if( ! $plan ){
						return new WP_Error( 'rest_cannot_add_to_cart', esc_html__( 'Could not find plan.', 'acf-frontend-form-element' ), array( 'status' => 500 ) );
					}

					$product->update_meta_data( '_fea_plan', $checkout['plan'] );
					$product->set_price( $plan->pricing );
					$product->set_name( $plan->title );


				}else{

					$product->set_name( $description );
					$product->set_regular_price( $price );
					$product->set_price( $price );	
				}
				$checkout_image = $checkout['product_image']['id'] ?? $checkout['product_image'] ?? 0; 
				
				$product->set_image_id( $checkout_image );
				$product->set_stock_status( 'instock' );
				$product->set_virtual(true);
				$product->set_manage_stock( false );
				$product->update_meta_data( '_fea_submission', $submission );
				$product->update_meta_data( '_fea_submissions_amount', $submissions );
				$product->update_meta_data( '_fea_redirect_to', $redirect);
				
				$product->update_meta_data( '_fea_form_id', $form_id ?? 0 );				
				$product->set_catalog_visibility( 'hidden' );
		
				$product_id = $product->save();
		
				if ( is_wp_error( $product_id ) ) {
					return new WP_Error( 'rest_cannot_add_to_cart', esc_html__( 'Could not create the product.', 'acf-frontend-form-element' ), array( 'status' => 500 ) );
				}
		
				return $product_id;
			}
		}
		

	}
	fea_instance()->remote_actions['checkout'] = new Checkout();

endif;
