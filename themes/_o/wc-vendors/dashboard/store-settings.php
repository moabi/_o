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

<h3>Coordonnées bancaires</h3>

<form method="post" action="" class="wcv-form wcv-formvalidator"> 

<?php WCVendors_Pro_Store_Form::form_data(); ?>

<div class="top" data-prevent-url-change="true">

	<?php //WCVendors_Pro_Store_Form::store_form_tabs( ); ?>

	<div id="payment">
		<!-- Paypal address -->
		<?php do_action( 'wcvendors_settings_before_paypal' ); ?>

		<?php //WCVendors_Pro_Store_Form::paypal_address( ); ?>

		<?php do_action( 'wcvendors_settings_after_paypal' ); ?>
	</div>



	<?php if ( $social_count != $social_total ) :  ?> 
		<div class="tabs-content" id="social">
			<?php do_action( 'wcvendors_settings_before_social' ); ?>
			<!-- Twitter -->
			<?php WCVendors_Pro_Store_Form::twitter_username( ); ?>
			<!-- Instagram -->
			<?php WCVendors_Pro_Store_Form::instagram_username( ); ?>
			<!-- Facebook -->
			<?php WCVendors_Pro_Store_Form::facebook_url( ); ?>
			<!-- Linked in -->
			<?php WCVendors_Pro_Store_Form::linkedin_url( ); ?>
			<!-- Youtube URL -->
			<?php WCVendors_Pro_Store_Form::youtube_url( ); ?>
			<!-- Pinterest URL -->
			<?php WCVendors_Pro_Store_Form::pinterest_url( ); ?>
			<!-- Google+ URL -->
			<?php WCVendors_Pro_Store_Form::googleplus_url( ); ?>
			<!-- Snapchat -->
			<?php WCVendors_Pro_Store_Form::snapchat_username( ); ?>
			<?php do_action(  'wcvendors_settings_after_social' ); ?>
		</div>
	<?php endif; ?>

	<!-- </div> -->
		<!-- Submit Button -->
		<!-- DO NOT REMOVE THE FOLLOWING TWO LINES -->
		<?php WCVendors_Pro_Store_Form::save_button( __( 'Save Changes', 'wcvendors-pro') ); ?>
</div>
	</form>
