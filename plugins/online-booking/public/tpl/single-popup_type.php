<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

//get_header(); ?>

		<?php
		while ( have_posts() ) : the_post();
        ?>
        <?php $ratio = get_field('ratio'); ?>
          <article class="mfp-with-anim hidden-content <?php echo $ratio; ?>">

            <div class="left-block">
              <header class="entry-header">
                <?php
                if(!get_field('hide_the_title')):
                the_title( '<h1 class="entry-title">', '</h1>' );
                  endif;
                ?>
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
            </div>
            <div class="right-block">
              <?php the_field('right_block'); ?>
            </div>


          </article><!-- #post-## -->


<?php endwhile;
		?>
<?php //get_footer(); ?>
