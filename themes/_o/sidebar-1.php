<?php
/**
 * Created by PhpStorm.
 * User: dfieffe
 * Date: 29/05/2015
 * Time: 13:43
 */
?>
<?php
global $wp_query;
$page_id = $wp_query->post->ID;

?>

<div id="secondary" class="sidebar pure-u-1 pure-u-md-1-3 sidebar-b">

  <?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
    <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
      <?php dynamic_sidebar( 'sidebar-2' ); ?>
    </div><!-- #primary-sidebar -->
  <?php endif; ?>

</div>