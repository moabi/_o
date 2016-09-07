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
<footer id="colophon" class="site-footer" role="contentinfo">
  <div class="site-info">
    <div class="inner">
      
      <?php  get_sidebar('footer'); ?>


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
          <span class="footer-title">Onlyoo</span> © Copyright <?php the_time('Y'); ?> <?php get_bloginfo('name'); ?>. <?php _e('Tous droits réservés.','online-booking'); ?>
	    </div>
	   
      </div>

<?php wp_footer(); ?>

<?php
$gaCode = get_field('code_ga','option');
if($gaCode){
  echo $gaCode;
}

?>

</body>
</html>

<link rel='stylesheet' id='fa'  href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' type='text/css' media='all' />
