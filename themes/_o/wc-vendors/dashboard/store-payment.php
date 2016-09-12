<?php
/**
 * The template for displaying the store settings form
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/
 *
 * @package    WCVendors_Pro
 * @version    1.3.3
 */

$settings_social 		= (array) WC_Vendors::$pv_options->get_option( 'hide_settings_social' );
$social_total 		= count( $settings_social );
$social_count = 0;
foreach ( $settings_social as $value) { if ( 1 == $value ) $social_count +=1;  }

?>

<h3><?php _e( 'Settings', 'wcvendors-pro' ); ?></h3>

<form method="post" action="" class="wcv-form wcv-formvalidator">

	<?php WCVendors_Pro_Store_Form::form_data(); ?>

	<div class="wcv-tabs top" data-prevent-url-change="true">

		<div class="tabs-content" id="payment">
			<!-- Paypal address -->
			<?php do_action( 'wcvendors_settings_before_paypal' ); ?>

			<?php WCVendors_Pro_Store_Form::paypal_address( ); ?>

			<?php do_action( 'wcvendors_settings_after_paypal' ); ?>
		</div>
		<!-- </div> -->
		<!-- Submit Button -->
		<!-- DO NOT REMOVE THE FOLLOWING TWO LINES -->
		<?php WCVendors_Pro_Store_Form::save_button( __( 'Save Changes', 'wcvendors-pro') ); ?>
	</div>
</form>
