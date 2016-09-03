<?php
/**
 * Created by PhpStorm.
 * User: dfieffe
 * Date: 29/05/2015
 * Time: 13:43
 */
?>

<div id="secondary" class="sidebar pure-u-1 pure-u-md-1-3">

  <?php if ( is_active_sidebar( 'right_sidebar' ) ) : ?>
    <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
      <?php dynamic_sidebar( 'right_sidebar' ); ?>
    </div><!-- #primary-sidebar -->
  <?php endif; ?>

</div>