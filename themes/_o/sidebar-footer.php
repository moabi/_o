<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 */

if ( ! is_active_sidebar( 'sidebar-footer' ) ) {
	return;
}
?>

<div class="widget-area sidebar-footer pure-g" role="complementary">
	<?php dynamic_sidebar( 'sidebar-footer' ); ?>
</div><!-- #secondary -->
