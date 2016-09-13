<?php
/**
 * The template for displaying the store settings form
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/
 *
 * @package    WCVendors_Pro
 * @version    1.3.3
 */
global $woocommerce;

$vendor_id = get_current_user_id();

$store_name = get_user_meta( $vendor_id, 'pv_shop_name', true );
$store_description = get_user_meta( $vendor_id, 'pv_shop_description', true );
$shipping_disabled			= WCVendors_Pro::get_option( 'shipping_management_cap' );
$shipping_methods 			= $woocommerce->shipping->load_shipping_methods();
$shipping_method_enabled	= ( array_key_exists( 'wcv_pro_vendor_shipping', $shipping_methods ) && $shipping_methods['wcv_pro_vendor_shipping']->enabled == 'yes' ) ? true : 0;

$settings_social 		= (array) WC_Vendors::$pv_options->get_option( 'hide_settings_social' );
$social_total 		= count( $settings_social );
$social_count = 0;
foreach ( $settings_social as $value) { if ( 1 == $value ) $social_count +=1;  }

?>

<form method="post" action="" class="wcv-form wcv-formvalidator">

	<?php WCVendors_Pro_Store_Form::form_data(); ?>

	<div class="wcv-tabs top" data-prevent-url-change="true">

		<div class="tabs-content" id="payment">
			<!-- Paypal address -->
			<?php //do_action( 'wcvendors_settings_before_paypal' ); ?>

			<?php WCVendors_Pro_Store_Form::paypal_address( ); ?>

			<?php //do_action( 'wcvendors_settings_after_paypal' ); ?>
		</div>
		<!-- </div> -->
		<!-- Submit Button -->
		<!-- DO NOT REMOVE THE FOLLOWING TWO LINES -->
		<?php WCVendors_Pro_Store_Form::save_button( __( 'Save Changes', 'wcvendors-pro') ); ?>
	</div>
</form>
