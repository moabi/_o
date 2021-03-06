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
	$post_id = get_the_ID();
	$user_id = get_current_user_id();
	global $post;
	$is_author = ($user_id == $post->post_author)? true : false;
	$ux = new online_booking_ux;
	$obp = new Online_Booking_Public('online-booking','1.0');
	$obs = new Online_Booking_Sejour('online-booking','1.0');
?>

<?php if (has_post_thumbnail( $post_id ) ): ?>
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ) ); ?>
<div id="custom-bg" style="background-image: url('<?php echo $image[0]; ?>')"></div>
<?php endif; ?>


<!-- SINGLE SEJOUR -->
<div class="pure-g inner-content">
	<div class="pure-u-1">
		<div id="primary-b" class="site-content single-animations single-sejour-php">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php echo $postid; ?>" <?php post_class(); ?>>

						<header class="entry-header">
							<h1 class="entry-title">
								<i class="fa fa-search"></i>
								<?php the_title(); ?>
								<?php
								if($is_author){
									echo '<a target="_blank" href="'.$obs->get_sejour_modify_uri().'"> (Modifier)</a>';
								}

									
								?>
							</h1>
						</header><!-- .entry-header -->


						<div class="clearfix"></div>
	<div class="pure-g">
	<!-- SLIDER -->
		<div class="pure-u-1 pure-u-md-7-12">
			<div id="activity-gallery">
			<?php echo $obs->get_sejour_slider($post_id); ?>
			</div>
		</div><!-- #activity -->
	<!-- #SLIDER -->
		<div class="pure-u-1 pure-u-md-5-12">
			<div id="single-top-information">
			<div class="box-price">
				<div class="pure-g">
					<div class="pure-u-7-24">
						<div class="author">
							<?php
							$author_id = get_the_author_meta('ID');
							echo $ux->get_custom_avatar($author_id,64,'avatar photo');
							echo get_the_author_meta('display_name'); ?>
						</div>
					</div>
					<div class="pure-u-17-24">
						<div class="pure-g">
							<div class="pure-u-2-5 info-block">
						<span class="locate-place">
					<?php
					echo  '<i class="fa fa-clock-o" aria-hidden="true"></i>'.__('Durée :', 'online-booking').'<br />';

					echo '<strong>'.$obs->get_sejour_duration($post_id).'</strong>';  ?>
				</span>
							</div>
							<div class="pure-u-3-5 info-block">
								<a href="#map-sejour" class="js-smooth-scroll">
									<i class="fa fa-map-marker" aria-hidden="true"></i> Lieu
								</a>
							</div>
						</div>
						<div class="pure-g">
							<div class="pure-u-3-5 info-block">
						<span class="locate-place">
					<?php
					echo  '<i class="fa fa-tag" aria-hidden="true"></i>'.__('Tarif à partir de :', 'online-booking').'<br />';

					echo '<strong>'.$obs->get_sejour_price($post_id).'€*/'.__('pers.','online-booking').'</strong>';
					//echo '<strong>'.get_field('budget_min').'€*/'.__('pers.','online-booking').'</strong>';  ?>
				</span>
							</div>
							<div class="pure-u-2-5 info-block">
						<span class="users-place">
							<?php
							echo  '<i class="fa fa-users" aria-hidden="true"></i>';
							if(get_field('min-personnes')){
								echo __('A partir de :<br />', 'online-booking');
								echo '<strong>'.get_field('min-personnes').' '.__('pers.','online-booking').'</strong>';
							}
							echo __('Jusqu\'à :<br />', 'online-booking');
							echo '<strong>'.get_field('personnes').' '.__('pers.','online-booking').'</strong>';


							?>

						</span>
							</div>
						</div>
					</div>
				</div>

				<?php
				$content = get_the_content();
				$br = (strlen($content) > 250) ? '[...]' : '';
				echo substr($content,0,250).$br;
				?>
				<?php 
				$obs->the_sejour_btn($post_id);

				?>
				
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
		echo $ux->get_sejour();
		echo $obs->get_sejour_map($post_id);
	?>
      
      <div class="pure-g modify-trip">
        <div class="pure-u-1-2">
            <div class="pack-perso">
                <i class="fs1 fa fa-info-circle" aria-hidden="true"></i>
            <?php _e('Tous nos packages sont personnalisables','online-booking'); ?>
            </div>

        </div>
        <div class="pure-u-1-2">
            <?php $obs->the_sejour_btn($post_id,true); ?>
        </div>
    </div>
      
      
	</div>


		</article><!-- #post -->
				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->
	</div>
</div>

<div class="newsletter-insolite">
  <?php  the_field('newsletter_single_product', 'options'); ?>
</div>
<?php get_footer(); ?>