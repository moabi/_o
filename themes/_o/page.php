<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header();
global $post;
?>
<div id="page-wrapper" class="tpl-page">

  <?php if(!is_front_page()): ?>
  <div id="page-header">
    <?php the_title('<h1 class="page-title">','</h1>'); ?>
    <?php if ( function_exists('yoast_breadcrumb') ) {
      yoast_breadcrumb('<p id="breadcrumbs">','</p>');
    } ?>
  </div>
  <?php endif; ?>

  <div class="entry-content default-page">

    <div class="post-content">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    </div>

    <?php flexibleContent(); ?>
  </div>
</div>
<div class="clearfix"></div>

<?php get_footer(); ?>
