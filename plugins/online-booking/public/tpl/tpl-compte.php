<?php
/**
 * Template Name: compte
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

get_header(); ?>
	<section id="primary" class="content-area archive-reservations">
		<main id="main" class="site-main" role="main">


			<header class="page-header">
				<h1><?php _e('Mon compte', 'online-booking'); ?></h1>
			</header><!-- .page-header -->

<div id="account-wrapper">
<!-- NAVIGATION -->
<div class="pure-g" id="single-tabber">
	<?php
		$user = wp_get_current_user();
if ( in_array( 'partner', (array) $user->roles ) ) {
    //The user has the "author" role
    $tabs_cl = 'pure-u-1-4';
} else {
	$tabs_cl = 'pure-u-1-3';
}
?>
	<div class="<?php echo $tabs_cl; ?> active">
		<a href="#" class="tabsto" data-target="0">
			<div class="fs1" aria-hidden="true" data-icon=""></div>
			<?php _e('Mes devis','online-booking'); ?></a>
	</div>
	<div class="<?php echo $tabs_cl; ?>">
		<a href="#" class="tabsto" data-target="1">
			<div class="fs1" aria-hidden="true" data-icon=""></div>
			<?php _e('Mes commandes','online-booking'); ?></a></a>
	</div>
	<div class="<?php echo $tabs_cl; ?>">
		<a href="#" class="tabsto" data-target="2">
			<div class="fs1" aria-hidden="true" data-icon=""></div>
		<?php _e('Mes informations','online-booking'); ?></a></a>
	</div>
	<?php
		if ( in_array( 'partner', (array) $user->roles ) ) {
    echo '<div class="pure-u-1-4">
		<a href="#" class="tabsto" data-target="3">
			<div class="fs1" aria-hidden="true" data-icon=""></div>
			'.__("Mes activités","online-booking").'</a>
	</div>
	';
}
?>
</div>

<!-- TABS -->
<div id="tabs-single" class="slick-single">

	<div class="single-el">
		<div class="comprend">
			<?php echo online_booking_user::get_user_booking(0); ?>
		</div>
	</div>
	<div class="single-el">
		<div class="comprend">
			<?php echo online_booking_user::get_user_booking(1); ?>
			<?php echo online_booking_user::get_user_booking(2); ?>
		</div>
	</div>
	<div class="single-el">
		<div class="comprend">
			<?php echo do_shortcode('[userpro template=edit]'); ?>
		</div>
	</div>
		<?php
		if ( in_array( 'partner', (array) $user->roles ) ) { ?>
		<div class="single-el">
		<div class="comprend">
			<h2><?php _e('Mes activités', 'online-booking'); ?></h2>
			<?php online_booking_partners::get_partner_activites(); ?>
		</div>
	</div>
		
		<?php }?>
</div>

	
<script type="text/javascript">
	jQuery(function(){
		var $ = jQuery;
		$('.slick-single').slick({
		  dots: false,
		  arrows: false,
		  infinite: true,
		  speed: 500,
		   slidesToShow: 1,
		  slidesToScroll: 1,
		  draggable:false
		});
				
		$('.tabsto').on('click', function() {
			$target = $(this).attr('data-target');
			$(this).parent().addClass('active').siblings().removeClass('active');
			$('.slick-single').slick('slickGoTo',$target);
});
	})
</script>
	
</div><!-- #account-wrapper -->


		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>