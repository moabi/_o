<?php
/**
 * The default template for displaying blog content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post pure-u-1 pure-u-md-1-2'); ?>>

  <div class="padd">
  <div class="pure-g">
    <div class="clearfix"></div>

  <div class="thumbnail pure-u-1 pure-u-md-10-24">
    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
      <?php if ( has_post_thumbnail() ): ?>
        <?php the_post_thumbnail('news'); ?>
      <?php else: ?>
        <?php echo '<img src="'.get_template_directory_uri().'/img/default-thumb.png" alt="'.get_the_title().'" class="default-thumb" />'; ?>
      <?php endif; ?>
    </a>
  </div>
  <div class="pure-u-1 pure-u-md-14-24 the-entry">

    <header class="entry-header clearfix">
      <?php
      if ( is_single() ) :
        the_title( '<h1 class="entry-title">', '</h1>' );
      else :
        the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
      endif;
      ?>
    </header><!-- .entry-header -->
    <div class="entry-content">
    <?php
    if ( is_single() ) :
    /* translators: %s: Name of current post */
    the_content( sprintf(
      __( 'Continue reading %s', 'twentyfifteen' ),
      the_title( '<span class="screen-reader-text">', '</span>', false )
    ) );
    else:
      $exc = get_the_excerpt();
      echo '<p>'.substr($exc, 0, 200).'</p>'; 
      echo '<div class="clearfix"></div>';
      echo '<a href="'.get_permalink().'" title="'.get_the_title().'" class="readmore btn btn-reg"><i class="fa fa-zoom"></i>'. __('Voir','twentyfifteen').' </a>';
    endif;
    ?>
    </div>
  </div><!-- .entry-content -->
  </div>

  </div>
</article><!-- #post-## -->
