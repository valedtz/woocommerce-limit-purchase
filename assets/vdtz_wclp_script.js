/**
 * Woocommece - Limit Product
 * Version: 1.0.0
 */
jQuery(document).ready(function() {
	
	jQuery('.woocommerce-variation-add-to-cart.limiton').hide();
	
	var base = jQuery('.woocommerce-variation-add-to-cart').html();
	jQuery( ".variations_form" ).on( "change", function () {
    // Fires whenever variation selects are changed
	    
	    var value = jQuery(this).find( 'input[name=variation_id]' ).val();
	    if( value != "0" && value != "" ){
		    var limit = jQuery( '#limit-on-'+value ).val();
		    if(limit > 0){
		    	jQuery('.woocommerce-variation-add-to-cart.default:visible').hide();
		    	jQuery('.woocommerce-variation-add-to-cart.limiton:hidden').show();
		    }else{
	    		jQuery('.woocommerce-variation-add-to-cart.default:hidden').show();
				jQuery('.woocommerce-variation-add-to-cart.limiton:visible').hide();
			}
	    }else{
	    	jQuery('.woocommerce-variation-add-to-cart.default:hidden').show();
			jQuery('.woocommerce-variation-add-to-cart.limiton:visible').hide();
		}
    
} );

/*
jQuery( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
    jQuery('.woocommerce-variation-add-to-cart.limiton').hide();
	
	var base = jQuery('.woocommerce-variation-add-to-cart').html();
	jQuery( ".variations_form" ).on( "change", function () {
    // Fires whenever variation selects are changed
	    
	    var value = jQuery(this).find( 'input[name=variation_id]' ).val();
	    if( value != "0" && value != "" ){
		    var limit = jQuery( '#limit-on-'+value ).val();
		    if(limit > 0){
		    	jQuery('.woocommerce-variation-add-to-cart.default:visible').hide();
		    	jQuery('.woocommerce-variation-add-to-cart.limiton:hidden').show();
		    }else{
	    		jQuery('.woocommerce-variation-add-to-cart.default:hidden').show();
				jQuery('.woocommerce-variation-add-to-cart.limiton:visible').hide();
			}
	    }else{
	    	jQuery('.woocommerce-variation-add-to-cart.default:hidden').show();
			jQuery('.woocommerce-variation-add-to-cart.limiton:visible').hide();
		}
} );	
*/
});