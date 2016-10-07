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
					<h2 class="page-title"><?php _e( 'Erreur 404, page introuvable...', 'twentyfifteen' ); ?></h2>
				</header><!-- .page-header -->

				<div class="page-content">
                    <div class="pure-g">
                      <div class="pure-u-1 pure-u-md-12-24">
                        <h3>Recherche sur le site :</h3>
                        <?php get_search_form( true ); ?>
                      </div>
                      <div class="pure-u-1 pure-u-md-12-24">

                      </div>
                    </div>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
