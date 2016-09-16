<?php
/**
 * The template for displaying the Product edit form  
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/
 *
 * @package    WCVendors_Pro
 * @version    1.3.2
 */
/**
 *   DO NOT EDIT ANY OF THE LINES BELOW UNLESS YOU KNOW WHAT YOU'RE DOING 
 *   
*/

$title = 	( is_numeric( $object_id ) ) ? __('Save Changes', 'wcvendors-pro') : __('Add Product', 'wcvendors-pro'); 
$product = 	( is_numeric( $object_id ) ) ? wc_get_product( $object_id ) : null;

// Get basic information for the product 
$product_title     			= ( isset($product) && null !== $product ) ? $product->post->post_title    : ''; 
$product_description        = ( isset($product) && null !== $product ) ? $product->post->post_content  : ''; 
$product_short_description  = ( isset($product) && null !== $product ) ? $product->post->post_excerpt  : ''; 
$post_status				= ( isset($product) && null !== $product ) ? $product->post->post_status   : ''; 

$classWcVendors = new online_booking_wcvendors('online-booking','v1');
global $post;
/**
 *  Ok, You can edit the template below but be careful!
*/
?>

<h2>Ajouter une activité</h2>

<!-- Product Edit Form -->
<form method="post" action="" id="wcv-product-edit" class="wcv-form wcv-formvalidator">

	<!-- Basic Product Details -->
	<div class="wcv-product-basic wcv-product"> 
		<!-- Product Title -->
		<?php WCVendors_Pro_Product_Form::title( $object_id, $product_title ); ?>


		<?php
		echo $classWcVendors->reglages_edit_product_form($object_id);
		?>
		<div class="show_if_simple show_if_external">
			<div class="pure-g">
				<div class="pure-u-1-2">
					<?php WCVendors_Pro_Product_Form::prices( $object_id ); ?>
				</div>
				<div class="pure-u-1-2">
					<?php
					//REMOVE IF WORKING
					//echo $classWcVendors->sold_indiv_edit_product_form($post->ID); ?>
					<?php
					woocommerce_wp_checkbox( array( 'id' => '_sold_individually', 'wrapper_class' => 'show_if_simple show_if_variable', 'label' => __( 'Prestation unique', 'woocommerce' ), 'description' => __( 'Le tarif est 
					forfaitaire, il est appliqué au groupe', 'woocommerce' )
					) );
					do_action( 'woocommerce_product_options_sold_individually' );
					?>
				</div>
			</div>
			<!-- Price and Sale Price -->

		</div>


	</div>

	<div class="all-100"> 
    	<!-- Media uploader -->
		<div class="wcv-product-media">
			<?php WCVendors_Pro_Form_helper::product_media_uploader( $object_id ); ?>
		</div>
	</div>

	
	<div class="all-100 hidden">
		<!-- Product Type -->
		<div class="wcv-product-type"> 
			<?php WCVendors_Pro_Product_Form::product_type( $object_id ); ?>
		</div>
	</div>

	<div class="all-100">
		<div class="wcv-tabs top" data-prevent-url-change="true">

			<ul class="tabs-nav" style="padding:0; margin:0;">

					<li>
						<a class="tabs-tab" href="#general">
							<i class="fa fa-sun-o" aria-hidden="true"></i> Description
						</a>
					</li>
				<li>
					<a class="tabs-tab" href="#wcv-acf-infos">
						<i class="fa fa fa-info-circle" aria-hidden="true"></i> Infos pratiques
					</a>
				</li>
				<li>
					<a class="tabs-tab js-show-gmap acf-cat" href="#acf-cat">
						<i class="fa fa fa-map-marker" aria-hidden="true"></i> Lieu
					</a>
				</li>
				<li>
					<a class="tabs-tab" href="#wcv-acf-reglages">
						<i class="fa fa-cog" aria-hidden="true"></i> Réglages
					</a>
				</li>

			</ul>

			<?php do_action( 'wcv_before_general_tab', $object_id ); ?>

			<!-- General Product Options -->
			<div class="wcv-product-general tabs-content" id="general">
				<strong>Description de votre activité</strong> <em>(Indiquez un texte accrocheur qui incitera les
					organisateurs à choisir votre activité)</em>
				<!-- Product Description -->
				<?php WCVendors_Pro_Product_Form::description( $object_id, $product_description );  ?>
				<!-- Product Short Description -->
				<?php WCVendors_Pro_Product_Form::short_description( $object_id, $product_short_description );  ?>

				<!-- Product Categories -->
				<?php //WCVendors_Pro_Product_Form::categories( $object_id, true ); ?>
				<!-- Product Tags -->
				<?php //WCVendors_Pro_Product_Form::tags( $object_id, true ); ?>

				<div class="hide_if_grouped">
					<!-- SKU  -->
					<?php //WCVendors_Pro_Product_Form::sku( $object_id ); ?>
					<!-- Private listing  -->
					<?php //WCVendors_Pro_Product_Form::private_listing( $object_id ); ?>
				</div>


				<div class="options_group show_if_external">
					<?php //WCVendors_Pro_Product_Form::external_url( $object_id ); ?>
					<?php //WCVendors_Pro_Product_Form::button_text( $object_id ); ?>
				</div>



				<div class="show_if_simple show_if_external show_if_variable"> 
					<!-- Tax -->
					<?php //WCVendors_Pro_Product_Form::tax( $object_id ); ?>
				</div>

				<div class="show_if_downloadable" id="files_download">
					<!-- Downloadable files -->
					<?php //WCVendors_Pro_Product_Form::download_files( $object_id ); ?>
					<!-- Download Limit -->
					<?php //WCVendors_Pro_Product_Form::download_limit( $object_id ); ?>
					<!-- Download Expiry -->
					<?php //WCVendors_Pro_Product_Form::download_expiry( $object_id ); ?>
					<!-- Download Type -->
					<?php //WCVendors_Pro_Product_Form::download_type( $object_id ); ?>
				</div>
			</div>

			<?php do_action( 'wcv_after_general_tab', $object_id ); ?>


			<?php do_action( 'wcv_before_linked_tab', $object_id ); ?>

			<!-- Upsells and grouping -->
			<div class="wcv-product-upsells tabs-content" id="linked_product"> 

				<?php WCVendors_Pro_Product_Form::up_sells( $object_id ); ?>
				
				<?php WCVendors_Pro_Product_Form::crosssells( $object_id ); ?>

				<div class="hide_if_grouped hide_if_external">

					<?php WCVendors_Pro_Product_Form::grouped_products( $object_id, $product ); ?>

				</div>
			</div>

			<?php do_action( 'wcv_after_linked_tab', $object_id ); ?>

			<?php WCVendors_Pro_Product_Form::form_data( $object_id, $post_status ); ?>
			<div class="btn-add-activity-wrapper">
				<div class="pure-g">
					<div class="pure-u-1-2">
						<?php WCVendors_Pro_Product_Form::draft_button( __('Save Draft','wcvendors-pro') ); ?>
					</div>
					<div class="pure-u-1-2">
						<?php WCVendors_Pro_Product_Form::save_button( 'Ajouter cette activité' ); ?>
					</div>
				</div>
			</div>



			</div>
		</div>
</form>