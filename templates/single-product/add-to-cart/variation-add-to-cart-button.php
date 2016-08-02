<?php
/**
 * Single variation cart button
 *
 * @see 	http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<?php echo vdtz_wclp_get_limit_qty(); ?>

<div class="woocommerce-variation-add-to-cart variations_button default">
	<?php if ( ! $product->is_sold_individually() ) : ?>
		<?php woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); ?>
	<?php endif; ?>
	<button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->id ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->id ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
<div class="woocommerce-variation-add-to-cart variations_button limiton">
	 	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
	 	<button type="submit" class="single_add_to_cart_button2 button alt" disabled="disabled"><?php _e( 'Product already bought', 'woocommerce-limit-purchase' ); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</div>
