<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

<div id="footer-newsletter">
	<div class="inner">
		 <div class="pure-u-1" id="newsletter-form">
		    <?php echo do_shortcode('[contact-form-7 id="1089" title="Newsletter"]'); ?>
	    </div>
	</div>
</div>
	   
<footer id="colophon" class="site-footer" role="contentinfo">
  <div class="site-info">
    <div class="inner">
	    
      <div class="cols">
        <div class="col-4 find-us">
          <?php the_field('nous_trouver','option'); ?>
          <?php wp_nav_menu( array( 'theme_location' => 'savoir' ) ); ?>
        </div>
        <div class="col-4 our-services">
          <?php the_field('texte_footer','option'); ?>
          <?php wp_nav_menu( array( 'theme_location' => 'trouver' ) ); ?>
        </div>

        <div class="col-4 last">
          <?php the_logo(); ?>
          <?php socialLinks(); ?>
        </div>
      </div>



      <div class="bl-1">
        <?php the_field('footer','options'); ?>
      </div>
      <div class="bl-1">
      </div>
    </div>
    <div class="clearfix"></div>
  </div><!-- .site-info -->
  <div class="clearfix"></div>
    
</footer><!-- .site-footer -->

</div><!-- site-wrapper -->
    <div class="infos"> 
	    <div class="inner">
	    	 © <?php the_time('Y'); ?> Tous droits réservés
	    </div>
	   
      </div>

<?php wp_footer(); ?>

<?php
$gaCode = get_field('code_ga','option');
$prodUrl = get_field('url_de_production','option');
//only echo analytics if if the good server
$rootUrl= "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
if($rootUrl == $prodUrl){
  echo $gaCode;
} else {
  echo '<div id="testWebsite">TEST</div>';
}
?>
<!--[if lt IE 9]>
<script type='text/javascript' src='//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.js?ver=4.1.2'></script>
<script type='text/javascript' src='//cdn.jsdelivr.net/respond/1.4.2/respond.min.js?ver=4.1.2'></script>
<![endif]-->

<link rel='stylesheet' id='fa'  href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' type='text/css' media='all' />

</body>
</html>
