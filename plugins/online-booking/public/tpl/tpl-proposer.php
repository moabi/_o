<?php
if ( is_user_logged_in() || current_user_can('publish_posts')  ) {
		//sanitize html
		add_filter('acf/update_value', 'wp_kses_post', 10, 1);
		//get ressources
		acf_form_head();
	}
	
?>
<?php get_header(); ?>

	<div id="primary-invite" class="content-area pure-g inner-content">
		<div id="content-b" class="site-content-invite pure-u-1 pure-u-md-2-3">
<?php
/**
 * Deregister the admin styles outputted when using acf_form
 */
add_action( 'wp_print_styles', 'tsm_deregister_admin_styles', 999 );
function tsm_deregister_admin_styles() {
	// Bail if not logged in or not able to post
	if ( ! ( is_user_logged_in() || current_user_can('publish_posts') ) ) {
		return;
	}
	wp_deregister_style( 'wp-admin' );
}
/**
 * Add ACF form for front end posting
 * @uses Advanced Custom Fields Pro
 */

	// Bail if not logged in or able to post

	while ( have_posts() ) : the_post();
	echo '<h1 class="page-title">'.get_the_title().'</h1>';
	
	if ( !(is_user_logged_in()|| current_user_can('publish_posts') ) )
	the_field('txt');
	
	if ( is_user_logged_in()|| current_user_can('publish_posts')  ) {
		the_content();

			
		$new_post = array(
			'post_id'            => 'new_post', // Create a new post
			'post_title' => true,
			'post_content' => true,
			'new_post'		=> array(
						'post_type'		=> 'product',
						'post_status'		=> 'pending'
					),
			// PUT IN YOUR OWN FIELD GROUP ID(s)
			//'field_groups'       => array(791), // Create post field group ID(s)
			'form'               => true,
			'html_before_fields' => '',
			'html_after_fields'  => '',
			'submit_value'       => __('Créer votre activité','online-booking'),
			'updated_message'    => __('Merci, nous reviendrons vers vous rapidement.','online-booking')
		);
		
		acf_form( $new_post );
	}
	endwhile;

/**
 * Back-end creation of new candidate post
 * @uses Advanced Custom Fields Pro
 */
add_filter('acf/pre_save_post' , 'tsm_do_pre_save_post' );
function tsm_do_pre_save_post( $post_id ) {
	// Bail if not logged in or not able to post
	if ( ! ( is_user_logged_in() || current_user_can('publish_posts') ) ) {
		return;
	}
	// check if this is to be a new post
	if( $post_id != 'new_post' ) {
		return $post_id;
	}
	
	//save custom taxonomies
	$acf_lieu = wp_strip_all_tags($_POST['acf[field_5661ef1d1f9f7]']);
	$acf_reservation = wp_strip_all_tags($_POST['acf']['field_5661ef54d1d4d']);
	
	// Create a new post
	$post = array(
		'post_type'     => 'product', // Your post type ( post, page, custom post type )
		'post_status'   => 'pending', // (publish, draft, private, etc.)
		'tax_input'      => array(
			'lieu' => $acf_lieu,
			'reservation_type' => $acf_reservation
			)
		);		/*
		'post_title'    => wp_strip_all_tags($_POST['acf']['field_54dfc93e35ec4']), // Post Title ACF field key
		'post_content'  => $_POST['acf']['field_54dfc94e35ec5'], // Post Content ACF field key*/

	// insert the post
	$post_id = wp_insert_post( $post );
	// Save the fields to the post
	do_action( 'acf/save_post' , $post_id );
	return $post_id;
}
/**
 * Save ACF image field to post Featured Image
 * @uses Advanced Custom Fields Pro
 */
add_action( 'acf/save_post', 'tsm_save_image_field_to_featured_image', 10 );
function tsm_save_image_field_to_featured_image( $post_id ) {
	// Bail if not logged in or not able to post
	if ( ! ( is_user_logged_in() || current_user_can('publish_posts') ) ) {
		return;
	}
	// Bail early if no ACF data
	if( empty($_POST['acf']) ) {
		return;
	}
	// ACF image field key
	$img = $_POST['acf']['field_56629ef93a621'];
	// Bail if image field is empty
	if ( empty($acf_lieu) ) {
		return;
	}
	// Add the value which is the image ID to the _thumbnail_id meta data for the current post
	add_post_meta( $post_id, '_thumbnail_id', $img );
}
// acf/update_value/name={$field_name} - filter for a specific field based on it's key



?>



		</div><!-- #content -->
		
		<div id="secondary" class="sidebar pure-u-1 pure-u-md-1-3">

      <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
      <div id="text-2" class="widget widget_text">			<div class="textwidget">Des questions ?
0811 202 101</div>
		</div><div id="text-3" class="widget widget_text">			<div class="textwidget">Du Lundi au Vendredi
De 9h00 à 18h00</div>
		</div>    </div><!-- #primary-sidebar -->
		</div></div>
		
<?php get_footer(); ?>