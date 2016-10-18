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
							<h1 class="entry-title">
								<i class="fa fa-search"></i>
								<?php the_title(); ?>
							</h1>
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
				<div class="pure-g">
					<div class="pure-u-6-24">
						<div class="author">
							<?php
							$author_id = get_the_author_meta('ID');
							echo get_avatar($author_id,64);
							echo get_the_author_meta('display_name'); ?>
						</div>
					</div>
					<div class="pure-u-18-24">
						<div class="pure-g">
							<div class="pure-u-1-2">
						<span class="locate-place">
					<?php
					echo  '<i class="fa fa-clock-o" aria-hidden="true"></i>'.__('Durée :', 'online-booking').'<br />';

					echo '<strong>3h</strong>';  ?>
				</span>
							</div>
							<div class="pure-u-1-2">
						<?php echo $ux->get_place($postid,true,true); ?>

							</div>
						</div>
						<div class="pure-g">
							<div class="pure-u-1-2">
						<span class="locate-place">
					<?php
					echo  '<i class="fa fa-tag" aria-hidden="true"></i>'.__('Tarif à partir de :', 'online-booking').'<br />';

					echo '<strong>'.get_field('budget_min').'€*/'.__('pers.','online-booking').'</strong>';  ?>
				</span>
							</div>
							<div class="pure-u-1-2">
						<span class="users-place">
							<?php
							echo  '<i class="fa fa-users" aria-hidden="true"></i>'.__('Jusqu\'à :<br />', 'online-booking');
							echo '<strong>'.get_field('personnes').' '.__('pers.','online-booking').'</strong>'; ?>
						</span>
							</div>
						</div>
					</div>
				</div>





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


	    <h1 class="related-title">
            <i class="fa fa-heart"></i>
            <?php $lieu_sejour =  $ux->get_place($postid,false); ?>
            <?php _e('Autres idées de package','online-booking'); ?>
        </h1>

		<?php $obp->the_sejours(8,false,$lieu_sejour,true); ?>


		</article><!-- #post -->
				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->
	</div>
</div>
<?php get_footer(); ?>