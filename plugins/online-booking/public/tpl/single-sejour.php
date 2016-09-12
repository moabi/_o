<?php
/**
 * The Template for displaying animations post.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

<?php 
	$postid = get_the_ID();
	global $post;
	$ux = new online_booking_ux;
	$obp = new Online_Booking_Public('online-booking','1.0');
?>

<?php if (has_post_thumbnail( $post->ID ) ): ?>
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) ); ?>
<div id="custom-bg" style="background-image: url('<?php echo $image[0]; ?>')"></div>
<?php endif; ?>


<!-- SINGLE SEJOUR -->
<div class="pure-g inner-content">
	<div class="pure-u-1">
		<div id="primary-b" class="site-content single-animations">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<header class="entry-header">
							<h2 class="entry-title">
								<i class="fa fa-search"></i>
								<?php the_title(); ?>
							</h2>
						</header><!-- .entry-header -->


						<div class="clearfix"></div>
	<div class="pure-g">
	<!-- SLIDER -->
		<div class="pure-u-1 pure-u-md-7-12">
			<div id="activity-gallery">
			<?php echo $ux->acf_img_slider(); ?>
			</div>
		</div><!-- #activity -->
	<!-- #SLIDER -->
		<div class="pure-u-1 pure-u-md-5-12">
			<div id="single-top-information">
			<div class="box-price">
				<span class="locate-place">
				<?php echo $ux->get_place($postid); ?>
					</span>
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?>
				<?php $obp->the_sejour_btn($postid); ?>
			</div>
		</div>
		</div>
	</div>

	<div id="main-content">
		<div class="pure-g">
			<div class="pure-u-1-2">
				<div class="pack-perso">
					<i class="fs1 fa fa-info-circle" aria-hidden="true"></i>
				<?php _e('Tous nos packages sont personnalisables','online-booking'); ?>
				</div>
			</div>
			<div class="pure-u-1-2">
				<?php echo $ux->socialShare(); ?>
			</div>
		</div>

	<?php
		//retrieve days and activities
		$ux->get_sejour(); ?>

	</div>

		<div class="pure-g modify-trip">
			<div class="pure-u-1-2">
				<div class="pack-perso">
					<i class="fs1 fa fa-info-circle" aria-hidden="true"></i>
				<?php _e('Tous nos packages sont personnalisables','online-booking'); ?>
				</div>

			</div>
			<div class="pure-u-1-2">
				<?php $obp->the_sejour_btn($postid,true); ?>
			</div>
		</div>


	    <h2 class="related-title">
            <i class="fa fa-heart"></i>
            <?php $lieu_sejour =  $ux->get_place($postid,false); ?>
            <?php _e('Autres idées de package','online-booking'); ?>
        </h2>

		<?php $obp->the_sejours(8,false,$lieu_sejour,true); ?>


		</article><!-- #post -->
				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->
	</div>
</div>
<?php get_footer(); ?>