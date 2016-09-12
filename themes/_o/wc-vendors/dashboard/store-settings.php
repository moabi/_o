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

	<?php //WCVendors_Pro_Store_Form::store_form_tabs( ); ?>

	<!-- Store Settings Form -->
	
	<div class="tabs-content" id="store">

		<!-- Store Name -->
		<?php WCVendors_Pro_Store_Form::store_name( $store_name ); ?>

		<?php do_action( 'wcvendors_settings_after_shop_name' ); ?>

		<!-- Store Description -->
		<?php WCVendors_Pro_Store_Form::store_description( $store_description ); ?>	
		
		<?php do_action( 'wcvendors_settings_after_shop_description' ); ?>
		<br />

		<!-- Seller Info -->
		<?php WCVendors_Pro_Store_Form::seller_info( ); ?>	
		
		
		<?php do_action( 'wcvendors_settings_after_seller_info' ); ?>

		<br />

		<!-- Company URL -->
		<?php do_action( 'wcvendors_settings_before_company_url' ); ?>
		<?php WCVendors_Pro_Store_Form::company_url( ); ?>
		<?php do_action(  'wcvendors_settings_after_company_url' ); ?>



		<!-- Store Phone -->
		<?php do_action( 'wcvendors_settings_before_store_phone' ); ?>
		<?php WCVendors_Pro_Store_Form::store_phone( ); ?>
		<?php do_action(  'wcvendors_settings_after_store_phone' ); ?>

		<!-- Store Address -->
		<?php do_action( 'wcvendors_settings_before_address' ); ?>
		<?php WCVendors_Pro_Store_Form::store_address_country( ); ?>
		<?php WCVendors_Pro_Store_Form::store_address1( ); ?>
		<?php WCVendors_Pro_Store_Form::store_address2( ); ?>
		<?php WCVendors_Pro_Store_Form::store_address_city( ); ?>
		<?php WCVendors_Pro_Store_Form::store_address_state( ); ?>
		<?php WCVendors_Pro_Store_Form::store_address_postcode( ); ?>
		<?php do_action(  'wcvendors_settings_after_address' ); ?>

		<!-- Store Vacation Mode -->
		<?php do_action( 'wcvendors_settings_before_vacation_mode' ); ?>
		<?php WCVendors_Pro_Store_Form::vacation_mode( ); ?>
		<?php do_action(  'wcvendors_settings_after_vacation_mode' ); ?>


	</div>

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
