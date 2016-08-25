<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'We could not find this page...', 'twentyfifteen' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'twentyfifteen' ); ?></p>
                    <div class="pure-g">
                      <div class="pure-u-1 pure-u-md-12-24">
                        <h3><?php _e( 'Please search from here', 'twentyfifteen' ); ?></h3>
                        <?php get_search_form( true ); ?>
                      </div>
                      <div class="pure-u-1 pure-u-md-12-24">
                        <h3><?php _e( 'Quick link to our pages :', 'twentyfifteen' ); ?></h3>
                        <ul>
                          <?php wp_list_pages('title_li='); ?>
                        </ul>
                      </div>
                    </div>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
