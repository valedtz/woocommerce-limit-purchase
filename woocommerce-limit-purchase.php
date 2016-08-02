<?php

/*
Plugin Name: Woocommerce - Limit Purchase
Plugin URI: http://
Description: This plugin limits the purchase of a limit number. 
Author: Valentina Del Torre Zugna
Version: 1.0.0
Author URI: http://www.valentinadeltorrezugna.com/
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions
 * 
 * @package WooCommerce - Limit Purchase
 * @since 1.0.0
 */
if( !defined( 'VDTZ_WCPL_PLUGIN_VERSION' ) ) {
	define( 'VDTZ_WCPL_PLUGIN_VERSION', '1.0.0' ); //Plugin version number
}
if( !defined( 'VDTZ_WCPL_DIR' ) ) {
	define( 'VDTZ_WCPL_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'VDTZ_WCPL_URL' ) ) {
	define( 'VDTZ_WCPL_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'VDTZ_WCPL_PLUGIN_BASENAME' ) ) {
	define( 'VDTZ_WCPL_PLUGIN_BASENAME', basename( VDTZ_WCPL_DIR ) ); //Plugin base name
}
/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package Woocommerce - Limit Purchase
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'vdtz_wclp_install' );


/**
 * Plugin Setup on Activaction
 * 
 * Does the initial setup, set default values for the plugin options.
 * 
 * @package WooCommerce - Limit Purchase
 * @since 1.0.0
 */
function vdtz_wclp_install() {
	// do something on install
}


/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package WooCommerce - Limit Purchase
 * @since 1.0.0
 */
function vdtz_wclp_load_text_domain() {
	
	// Set language plugin dir
	$vdtz_wclp_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$vdtz_wclp_lang_dir	= apply_filters( 'vdtz_wclp_languages_directory', $vdtz_wclp_lang_dir );
	
	// Traditional WordPress plugin locale filter
	$locale	= apply_filters( 'plugin_locale',  get_locale(), 'woocommerce-limit-purchase' );
	$mofile	= sprintf( '%1$s-%2$s.mo', 'woocommerce-limit-purchase', $locale );
	
	// Setup paths to current locale file
	$mofile_local	= $vdtz_wclp_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . VDTZ_WCPL_PLUGIN_BASENAME . '/' . $mofile;
	
	if ( file_exists( $mofile_global ) ) { 
		load_textdomain( 'woocommerce-limit-purchase', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { 
		load_textdomain( 'woocommerce-limit-purchase', $mofile_local );
	} else { // Load the default language files
		load_plugin_textdomain( 'woocommerce-limit-purchase', false, $vdtz_wclp_lang_dir );
	}
}
/**
 * Plugin Path
 * 
 * Get Plugin Path
 * 
 * @package WooCommerce - Limit Purchase
 * @since 1.0.0
 */
function vdtz_wclp_plugin_path() {

  return untrailingslashit( plugin_dir_path( __FILE__ ) );
 
}

//add action to load plugin
add_action( 'plugins_loaded', 'vdtz_wclp_plugin_loaded' );

/**
 * Load Plugin
 * 
 * Handles to load plugin after dependent plugin is loaded successfully
 * 
 * @package WooCommerce - Limit Purchase
 * @since 1.0.0
 */
function vdtz_wclp_plugin_loaded() {
	
	//check Woocommerce is activated or not
	if( class_exists( 'Woocommerce' ) ) {
		
		//load text domain
		vdtz_wclp_load_text_domain();
		
		//register Deactivation Hook
		register_deactivation_hook( __FILE__, 'vdtz_wclp_unstall');
		
		/**
		 * Plugin Setup (On Deactivation)
		 * 
		 * Delete  plugin options.
		 * 
		 * @package WooCommerce - Limit Purchase
		 * @since 1.0.0
		 */
		function vdtz_wclp_unstall() {
			// do something on uninstall
		}
		
		// Display Fields if it is a single pro
		add_action( 'woocommerce_product_options_general_product_data', 'vdtz_wclp_single_product_fields' );
		// Save Fields
		add_action( 'woocommerce_process_product_meta', 'vdtz_wclp_single_product_fields_save' );
			
		/**
		* Single Product Fields
		* 
		* Add options in the general tab if is not variable
		* 
		* @package WooCommerce - Limit Purchase
		* @since 1.0.0
 		*/
		function vdtz_wclp_single_product_fields() {
			global $woocommerce, $post;
			if( function_exists('get_product') ){
				$product = get_product( $post->ID );
			
				if( !$product->is_type( 'variable' ) ){
				
				echo '<div class="options_group">';
	
				woocommerce_wp_select( 
				array( 
					'id'      => 'vdtz_wclp_select_dropdown', 
					'label'   => __( 'Purchase with limit', 'woocommerce-limit-purchase' ),
					'options' => array(
						'0'   => __( 'No', 'woocommerce-limit-purchase' ),
						'1'   => __( 'Yes', 'woocommerce-limit-purchase' )
						),
					'value'       => get_post_meta( $post->ID, 'vdtz_wclp_select_dropdown', true )
					)
				);

				woocommerce_wp_text_input(
				array(
					'id' => 'vdtz_wclp_purchased_qty',
					'label' => __( "Purchased Quantity", 'woocommerce-limit-purchase' ),
					'placeholder' => '1',
					'value'       => get_post_meta( $post->ID, 'vdtz_wclp_purchased_qty', true )
					)
					); 
					echo '</div>';
				}
			}
		}
		
		/**
		* Single Product Fields Save
		* 
		* Save options in the general tab if is not variable
		* 
		* @package WooCommerce - Limit Purchase
		* @since 1.0.0
 		*/
		function vdtz_wclp_single_product_fields_save( $post_id ){

			$vdtz_wclp_select_dropdown = $_POST['vdtz_wclp_select_dropdown'];
			if( ! empty( $vdtz_wclp_select_dropdown ) ) {
				update_post_meta( $post_id, 'vdtz_wclp_select_dropdown', esc_attr( $vdtz_wclp_select_dropdown ) );
			}
			
			$vdtz_wclp_purchased_qty = $_POST['vdtz_wclp_purchased_qty'];
			if( ! empty( $vdtz_wclp_purchased_qty ) ) {
				update_post_meta( $post_id, 'vdtz_wclp_purchased_qty', esc_attr( $vdtz_wclp_purchased_qty ) ); 
			}
		
		}
		
		
		// add variation settings
		add_action( 'woocommerce_product_after_variable_attributes', 'vdtz_wclp_variable_product_fields', 10, 3 );
		// save variation settings
		add_action( 'woocommerce_save_product_variation', 'vdtz_wclp_variable_product_fields_save', 10, 2 );
		
		
		/**
		* Variable Product Fields
		* 
		* Add options in the variation tab if is not variable
		* 
		* @package WooCommerce - Limit Purchase
		* @since 1.0.0
 		*/
		function vdtz_wclp_variable_product_fields( $loop, $variation_data, $variation ) {
			
			woocommerce_wp_select( 
			array( 
				'id'          => 'vdtz_wclp_select_dropdown_var[' . $variation->ID . ']',
				'label'   => __( 'Purchase with limit', 'woocommerce-limit-purchase' ),
				'options' => array(
						'0'   => __( 'No', 'woocommerce-limit-purchase' ),
						'1'   => __( 'Yes', 'woocommerce-limit-purchase' )
					),
				'value'       => get_post_meta( $variation->ID, 'vdtz_wclp_select_dropdown_var', true )
				)
			);
			
			woocommerce_wp_text_input(
				array(
					'id' => 'vdtz_wclp_purchased_qty_var[' . $variation->ID . ']',
					'label' => __( 'Purchased Quantity', 'woocommerce-limit-purchase' ),
					'placeholder' => '1',
					'value'       => get_post_meta( $variation->ID, 'vdtz_wclp_purchased_qty_var', true )
				)
			); 
			
		}
		
		/**
		* Variable Product Fields Save
		* 
		* Save options in the variation tab if is not variable
		* 
		* @package WooCommerce - Limit Purchase
		* @since 1.0.0
 		*/
		function vdtz_wclp_variable_product_fields_save( $post_id ) {
			
			$vdtz_wclp_select_dropdown_var = $_POST['vdtz_wclp_select_dropdown_var'][ $post_id ];
			if( ! empty( $vdtz_wclp_select_dropdown_var ) ) {
				update_post_meta( $post_id, 'vdtz_wclp_select_dropdown_var', esc_attr( $vdtz_wclp_select_dropdown_var ) );
			} 
			
			$vdtz_wclp_purchased_qty_var = $_POST['vdtz_wclp_purchased_qty_var'][ $post_id ];
			if( ! empty( $vdtz_wclp_purchased_qty_var ) ) {
				update_post_meta( $post_id, 'vdtz_wclp_purchased_qty_var', esc_attr( $vdtz_wclp_purchased_qty_var ) );
			}  
		
		}
		
		// add locate template filter
		add_filter( 'woocommerce_locate_template', 'vdtz_wclp_locate_template', 10, 3 );

		/**
		* Locate Template
		* 
		* Add plugin templates dir as new woocommerce template dir
		* 
		* @package WooCommerce - Limit Purchase
		* @since 1.0.0
 		*/
		function vdtz_wclp_locate_template( $template, $template_name, $template_path ) {
			
			global $woocommerce;
			
			$_template = $template;
			
			if ( ! $template_path ) $template_path = $woocommerce->template_url;
			
			$plugin_path  = vdtz_wclp_plugin_path() . '/templates/';
			
			$template = locate_template(
				array(
					$template_path . $template_name,
					$template_name
				)
			);
		
			// Modification: Get the template from this plugin, if it exists
			if ( ! $template && file_exists( $plugin_path . $template_name ) )
				$template = $plugin_path . $template_name;
		
			// Use default template
			if ( ! $template )
				$template = $_template;
			
			// Return what we found
			return $template;
		
		}
		
		add_filter ('woocommerce_payment_complete_order_status', 'vdtz_wclp_clean_cache', 10, 2);
		
		function vdtz_wclp_clean_cache($order_status, $order_id) { 
			$current_user = wp_get_current_user(); //$current_user->user_email, $current_user->ID
			
			$transient_name = 'vdtz_wclp' . md5( $current_user->user_email . $current_user->ID . WC_Cache_Helper::get_transient_version( 'orders' ) );
			
			delete_transient( $transient_name );
			return $order_status;
		}

		/**
		* Customer Bought Product Times
		* 
		* Different version of wc_customer_bought_product. Return how many times a customer bought a specific product.
		* 
		* @package WooCommerce - Limit Purchase
		* @since 1.0.0
 		*/
		function wc_customer_bought_product_times( $customer_email, $user_id, $product_id) {
			global $wpdb;
			
			
			$transient_name = 'vdtz_wclp' . md5( $customer_email . $user_id . WC_Cache_Helper::get_transient_version( 'orders' ) ).time();

			if ( false === ( $result = get_transient( $transient_name ) ) ) {
				$customer_data = array( $user_id );

				if ( $user_id ) {
					$user = get_user_by( 'id', $user_id );

					if ( isset( $user->user_email ) ) {
                		$customer_data[] = $user->user_email;
            		}
        		}

				if ( is_email( $customer_email ) ) {
            		$customer_data[] = $customer_email;
        		}

				$customer_data = array_map( 'esc_sql', array_filter( array_unique( $customer_data ) ) );

				if ( sizeof( $customer_data ) == 0 ) {
            		return false;
        		}

				$result = $wpdb->get_col( "
            		SELECT im.meta_value FROM {$wpdb->posts} AS p
					INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
					INNER JOIN {$wpdb->prefix}woocommerce_order_items AS i ON p.ID = i.order_id
					INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS im ON i.order_item_id = im.order_item_id
					WHERE p.post_status IN ( 'wc-completed', 'wc-processing' )
					AND pm.meta_key IN ( '_billing_email', '_customer_user' )
					AND im.meta_key IN ( '_product_id', '_variation_id' )
					AND im.meta_value != 0
					AND pm.meta_value IN ( '" . implode( "','", $customer_data ) . "' )
					GROUP BY p.ID, im.meta_value
					" );
					$result = array_map( 'absint', $result );
					
					set_transient( $transient_name, $result, DAY_IN_SECONDS * 7 );
    		}
					
    		
			$count_values = array_count_values( $result );
			
			if(isset($product_id) && $product_id > 0){				
				return $count_values[absint( $product_id )];
			}else{
				return 0;
			} 
    	}
		
		// add control if the limit is on
		add_action( 'template_redirect', 'vdtz_wclp_remove_product_from_cart' );

		/**
		* Remove Product From Cart
		* 
		* Remove product from cart or decrise quantity until Quantity Limit. 
		* 
		* @package WooCommerce - Limit Purchase
		* @since 1.0.0
 		*/
		function vdtz_wclp_remove_product_from_cart() {
			if( is_cart() || is_checkout() || is_product() ) {
				$current_user = wp_get_current_user();
				
				foreach( WC()->cart->cart_contents as $cart_id => $products ) {
					
					$ID = ( isset( $products['variation_id'] ) && $products['variation_id'] != 0 ) ? $products['variation_id'] : $products['product_id'];
						
					if(( isset( $products['variation_id'] ) && $products['variation_id'] != 0 )){
						$limit_on = get_post_meta( $ID, 'vdtz_wclp_select_dropdown_var', true );
						$limit_qty = get_post_meta( $ID, 'vdtz_wclp_purchased_qty_var', true );
					}else{
						$limit_on = get_post_meta( $ID, 'vdtz_wclp_select_dropdown', true );
						$limit_qty = get_post_meta( $ID, 'vdtz_wclp_purchased_qty', true );
					}
					$limit_qty= $limit_qty > 0 ? $limit_qty : 1;
					
					$bought = wc_customer_bought_product_times( $current_user->user_email, $current_user->ID, $ID);
					$old_quantity = $products['quantity'];

					if ( $limit_on == '1') {
						
						//modify cart quantity
							
						if($cart_id && ( $old_quantity +  $bought ) > $limit_qty ){
							
							$new_qty = $limit_qty - $bought < 1 ? 0 : $limit_qty - $bought;
							
							if( $new_qty == 0){
								
								unset( WC()->cart->cart_contents[$cart_id] );
								
								wc_add_notice( apply_filters( 'wc_add_to_cart_message', sprintf( __( "The maximum purchasable quantity allowed for \"%s\" is %s. You have already bought it, for this reason it cannot add to cart.", 'woocommerce-limit-purchase' ), $products['data']->get_title(), $limit_qty ), $ID ) );
							}else{
								
								WC()->cart->set_quantity($cart_id, $new_qty);
									
								wc_add_notice( apply_filters( 'wc_add_to_cart_message',  sprintf( __( "The maximum purchasable quantity allowed for \"%s\" is %s.  Your quantity will be modified to reach this limit", 'woocommerce-limit-purchase' ), $products['data']->get_title() , ($limit_qty - $bought)   ), $ID ) );
							}

						}

            		}

            	}
            }
        }
        
		/**
		* Check Limit 
		* 
		* Check for template if the limit is on. Return false if not, Return quantity if yes.
		* 
		* @package WooCommerce - Limit Purchase
		* @since 1.0.0
 		*/
        function vdtz_wclp_get_limit_qty(){
			global $woocommerce, $post, $product;
			
			if( function_exists('get_product') ){
				$product = get_product( $post->ID );
				
				$current_user = wp_get_current_user();
				
				
				if( !$product->is_type( 'variable' ) ){
					$limit_on = get_post_meta( $post->ID, 'vdtz_wclp_select_dropdown', true );
					$limit_qty = get_post_meta( $post->ID, 'vdtz_wclp_purchased_qty', true );
					$limit_qty = $limit_qty > 1 ? $limit_qty : 1;
					
					$bought = wc_customer_bought_product_times( $current_user->user_email, $current_user->ID, $post->ID);
					
					if ( $limit_on == '1' &&  (int)$bought >= (int)$limit_qty) {
						return 	$limit_qty;
					}
					
				}else{
					$variations = $product->get_available_variations();
					
					foreach($variations as $variation){
						
						$bought = wc_customer_bought_product_times( $current_user->user_email, $current_user->ID, $variation['variation_id']);
						
						$limit_on = get_post_meta( $variation['variation_id'], 'vdtz_wclp_select_dropdown_var', true );
						$limit_qty = get_post_meta( $variation['variation_id'], 'vdtz_wclp_purchased_qty_var', true );
						$limit_qty = $limit_qty > 1 ? $limit_qty : 1;
						$limit_qty = $limit_on ? $limit_qty : 0;
						
						if (!( $limit_on == '1' && (int)$bought >= (int)$limit_qty)) {
							$limit_qty = 0;
						}	
						
						?>
						<input type="hidden" name="limit-on[<?php echo $variation['variation_id'] ?>]" id="limit-on-<?php echo $variation['variation_id'] ?>" value="<?php echo absint( $limit_qty ); ?>" />
						<?php
					}
				}
			}
			
			return false;
	        
        }

        // add js file for front-end  
        add_action( 'wp_enqueue_scripts', 'vdtz_wclp_add_scripts' );
        
        /**
		* Add Scripts 
		* 
		* Add JS file
		* 
		* @package WooCommerce - Limit Purchase
		* @since 1.0.0
 		*/
        function vdtz_wclp_add_scripts() {
	        if(is_product()){
				wp_register_script('vdtz_wclp_script', VDTZ_WCPL_URL . 'assets/vdtz_wclp_script.js', array('jquery'),'1.1', true);
				wp_enqueue_script('vdtz_wclp_script');
			}
	    }

		
	}
}