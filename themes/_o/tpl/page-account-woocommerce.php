<?php
/*
 * Template Name: woocommerce account pages
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
*/
?>

<?php get_header();
global $post;
?>

<div id="page-wrapper" class="sidebar-page">


  <div class="pure-g inner-content ob-account-nav">
    <div class="pure-u-1">
      <?php
      do_action( 'woocommerce_account_navigation' );
      ?>
    </div>
  </div>

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


  <div class="pure-g inner-content">
    <div class="pure-u-1 pure-u-md-2-3">
  <?php if(!is_front_page()): ?>
    <div id="page-header">
      <?php the_title('<h1 class="page-title">','</h1>'); ?>
      <?php if ( function_exists('yoast_breadcrumb') ) {
        yoast_breadcrumb('<p id="breadcrumbs">','</p>');
      } ?>
    </div>
  <?php endif; ?>

  <div class="entry-content">

    <div class="post-content">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    </div><!-- post content -->

    <?php flexibleContent(); ?>
  </div><!-- entry content -->
  </div><!--pure block -->

    <?php get_sidebar('account'); ?>
  </div><!--inner pure -->
</div><!--page wrapper -->

</div><!-- closure ? -->
<?php get_footer(); ?>
