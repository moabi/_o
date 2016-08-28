<?php
/*
 * Template Name: full-width
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
*/
?>

<?php get_header(); ?>

<div id="page-wrapper" class="full-width-page">

	<?php if ( has_post_thumbnail() ): ?>
		<div class="pure-g inner-content">
			<div id="full-size-thumb">
				<?php
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full-size' );
				$url = $thumb['0'];
				$fakeThumb = get_template_directory_uri()."/img/blank.png";
				$default_thumb = get_template_directory_uri()."/img/default-full.jpg";
				$postThumb = ( $url ) ? $url : $default_thumb;
				// the_post_thumbnail('full-size');
				?>
				<img src="<?php echo $fakeThumb; ?>"  data-original="<?php echo $postThumb; ?>" alt="<?php the_title(); ?>" class="lazy"  />
			</div>
		</div>
	<?php endif; ?>

	<div class="full-width-wrapper">
			<div class="entry-content">

				<div class="post-content">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php the_content(); ?>
					<?php endwhile; ?>
				</div><!-- post content -->
				<?php flexibleContent(); ?>
			</div><!-- entry content -->
	</div><!--inner full-width -->
</div><!--page wrapper -->

<?php get_footer(); ?>
