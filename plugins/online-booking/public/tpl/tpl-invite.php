<?php
/**
 * Template Name: booking-invite
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<?php
	 // Show only posts and media related to logged in author
	 /*
   add_action('pre_get_posts', 'query_set_only_author' );
   function query_set_only_author( $wp_query ) {
     global $current_user;
     if( is_admin() && !current_user_can('edit_others_posts') ) {
        $wp_query->set( 'author', $current_user->ID );
        add_filter('views_edit-post', 'fix_post_counts');
        add_filter('views_upload', 'fix_media_counts');
     }
   }
*/
?>



<?php acf_form_head(); ?>
	
<?php
/**
 * Deregister the admin styles outputted when using acf_form
 */
add_action( 'wp_print_styles', 'tsm_deregister_admin_styles', 999 );
function tsm_deregister_admin_styles() {
	// Bail if not logged in or not able to post
	//
	if ( !is_user_logged_in() ) {
		return;
	}
	wp_deregister_style( 'wp-admin' );
}

?>
<?php get_header(); ?>

	<div id="primary-invite" class="content-area pure-g">
		<div id="content-b" class="site-content-invite pure-u-1 pure-u-md-2-3">

			
<?php 
				
	if (  is_user_logged_in()) {
	
		while ( have_posts() ) : the_post();
	
			acf_form(array(
				'post_id'		=> 'new_post',
				'post_title' => true,
				'post_content' => true,
				'new_post'		=> array(
					'post_type'		=> 'product',
					'post_status'		=> 'pending'
				),
				'updated_message'    => 'Merci pour votre contribution,nous reviendrons vers vous rapidement pour valider votre activité',
				'submit_value'		=> 'Proposez votre event'
			)); 
			
		endwhile;
	
	} else {
		
		echo __('Merci de vous connecter pour ajouter votre activité','online-booking');
	}
				
?>



		</div><!-- #content -->
		
		<div id="secondary" class="sidebar pure-u-1 pure-u-md-1-3">

      <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
      <div id="text-2" class="widget widget_text">			<div class="textwidget">Des questions ?
0811 202 101</div>
		</div><div id="text-3" class="widget widget_text">			<div class="textwidget">Du Lundi au Vendredi
De 9h00 à 18h00</div>
		</div>    </div><!-- #primary-sidebar -->
  
</div>

	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>