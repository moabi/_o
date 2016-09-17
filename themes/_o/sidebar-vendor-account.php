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
<div id="secondary" class="sidebar sidebar-vendor sidebar-account">
	<?php if ( is_active_sidebar( 'sidebar-vendor-account' ) ) : ?>
	      <?php dynamic_sidebar( 'sidebar-vendor-account' ); ?>
	  <?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-vendor' ) ) : ?>
		<div id="second-sidebar" class="sidebar sidebar-vendor sidebar-account">
			<!--    <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">-->
			<?php dynamic_sidebar( 'sidebar-vendor' ); ?>
			<!--    </div> #primary-sidebar -->
		</div>
	<?php endif; ?>
</div>
</div>

