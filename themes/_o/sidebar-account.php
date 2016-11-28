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
<div class="pure-u-1 pure-u-md-6-24">
<div id="secondary" class="sidebar sidebar-account">
  <?php if ( is_active_sidebar( 'sidebar-account' ) ) : ?>

      <?php dynamic_sidebar( 'sidebar-account' ); ?>

  <?php endif; ?>

</div>
</div>