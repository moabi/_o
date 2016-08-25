<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header();

$obp = new Online_Booking_Public('online-booking','1.0');
?>

			<div id="desc-ar">
			<header class="page-header inner-content">
				<h1><div class="fs1" aria-hidden="true" data-icon=""></div><?php _e('Découvrez nos séjours clef en main', 'online-booking'); ?></h1>
				<?php the_content(); ?>
			</header><!-- .page-header -->
			
			</div>
			
	<section id="primary" class="content-area archive-reservations">
		<main id="main" class="site-main" role="main">
			<?php $obp->the_sejours(20,false,false,true); ?>
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
