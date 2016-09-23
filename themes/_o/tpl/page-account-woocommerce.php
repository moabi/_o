<?php
/*
 * Template Name: woocommerce account pages
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
*/
?>

<?php
get_header();
global $post;
if(is_user_logged_in()){
  $page_width = 'pure-u-1 pure-u-md-18-24';
} else {
  $page_width = 'pure-u-1';
}

$is_vendor = ( current_user_can('vendor') || current_user_can('pending_vendor'));
$sidebar_type = $is_vendor ? 'vendor-account' : 'account';

$classFep = new online_booking_fep();
?>

<div id="page-wrapper" class="page-account-woocommerce-php">
  <div class="ob-account-nav">
      <?php
      if( current_user_can('vendor') || current_user_can('pending_vendor') ){
        echo '<a href="#" class="js-toggle-dashboard-menu mobile-only"><i class="fa fa-bars"></i>MENU</a>';
        //echo do_shortcode('[wcv_pro_dashboard_nav]');
        //do_action( 'woocommerce_account_navigation' );
        wp_nav_menu(array(
            'theme_location'    => 'vendor',
            'menu_class'        => 'menu black pure-menu-list',
            'container_class'   => 'wcv-navigation pure-menu pure-menu-horizontal',
            'walker'            => new pure_walker_nav_menu
        ));
      } else {
        do_action( 'woocommerce_account_navigation' );
      }
      ?>
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

<div class="inner-content">
  <div class="pure-g">
    <div class="<?php echo $page_width; ?>">
      <div class="site-content-invite">
  <?php if(!is_page(array('messagerie'))): ?>
    <div id="page-header">
      <?php the_title('<h2 class="page-title">','</h2>'); ?>
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
    </div>
    <?php
    get_sidebar( $sidebar_type );
    ?>
  </div><!--inner pure -->
</div><!--page wrapper -->
</div>
</div><!-- closure ? -->
<?php get_footer(); ?>
