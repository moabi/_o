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

$obs = new Online_Booking_Sejour('online-booking','1.0');
?>

	<section id="primary" class="content-area archive-reservations">
		<main id="main" class="site-main" role="main">
			<?php the_content(); ?>
			<?php $obs->the_sejours(20,true,false,false); ?>
			<div class="clearfix"></div>
		</main><!-- .site-main -->
	</section><!-- .content-area -->
    
<div class="newsletter-insolite">
  <?php  the_field('newsletter_single_product', 'options'); ?>
</div>

<?php get_footer(); ?>
