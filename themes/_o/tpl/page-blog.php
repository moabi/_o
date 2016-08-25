<?php
/**
 * Template Name: Archive Page - no sidebar
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area index-template">
  <main id="main" class="site-main" role="main">
    <div id="page-header">
      <?php
      if( get_option('page_for_posts') ) {
        $blog_page_id = get_option('page_for_posts');
        echo '<h1 class="page-title">'.get_page($blog_page_id)->post_title.'</h1>';
      }
      ?>

      <?php if ( function_exists('yoast_breadcrumb') ) {
        yoast_breadcrumb('<p id="breadcrumbs">','</p>');
      } ?>
    </div>
    <?php if ( have_posts() ) : ?>


      <?php
      // Start the loop.
      while ( have_posts() ) : the_post();

        /*
         * Include the Post-Format-specific template for the content.
         * If you want to override this in a child theme, then include a file
         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
         */

        get_template_part( 'content', 'archive' );

        // End the loop.
      endwhile;
      // Previous/next page navigation.
      the_posts_pagination( array(
        'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
        'next_text'          => __( 'Next page', 'twentyfifteen' ),
        'before_page_number' => '',
        'screen_reader_text '=> '',
        'mid_size '          => 4
      ) );
    // If no content, include the "No posts found" template.
    else :
      get_template_part( 'content', 'none' );

    endif;
    ?>

  </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
