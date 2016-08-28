<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>
<div id="single-post">

    <?php
    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full-size' );
    $url = $thumb['0'];
    ?>
    <?php if($url): ?>
<div class="pure-g inner-content">
  <div id="full-size-thumb">
  <?php
  $fakeThumb = get_template_directory_uri()."/img/blank.png";
  $default_thumb = get_template_directory_uri()."/img/default-full.jpg";
  ?>
    <img src="<?php echo $fakeThumb; ?>"  data-original="<?php echo $url; ?>" alt="<?php the_title(); ?>" class="lazy"  />
    </div>
</div>
    <?php endif; ?>

<div class="pure-g inner-content">
<div id="primary" class="singlepost pure-u-1 pure-u-md-2-3">
		<?php
		while ( have_posts() ) : the_post();
        ?>
          <article class="post single-template">

              <header class="entry-header">

                <?php
                if(!get_field('hide_the_title')):

                the_title( '<h1 class="entry-title">', '</h1>' );
                  endif;
                ?>
                <?php if ( function_exists('yoast_breadcrumb') ) {
                  yoast_breadcrumb('<p id="breadcrumbs">','</p>');
                } ?>
              </header><!-- .entry-header -->

              <div class="entry-content">
                <?php
                /* translators: %s: Name of current post */
                the_content( sprintf(
                  __( 'Continue reading %s', 'twentyfifteen' ),
                  the_title( '<span class="screen-reader-text">', '</span>', false )
                ) );
                ?>
                </div><!-- .entry-content -->


          </article><!-- #post-## -->


<?php endwhile; ?>

<?php get_sidebar(); ?>
</div>
</div>
</div><!--  #single post -->


<?php get_footer(); ?>
