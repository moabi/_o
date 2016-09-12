<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width">
    <link rel="shortcut icon" href="<?php echo get_bloginfo('url'); ?>/favicon.ico"/>
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <?php wp_head(); ?>
</head>

<body id="<?php    if (is_front_page()) { echo "home";} else { echo (is_page()) ? get_query_var('name') :( (is_category()) ? "category"  : ((is_archive()) ? "archive" : "single")); } ; ?>" <?php body_class(); ?>>

<div id="site-wrapper">
    <div id="masthead" class="site-header" role="banner">
        <div class="site-branding">

            <div id="header-logo">
              <h1 class="screen-reader-text">Onlyoo - Créez vos évênements</h1>
                <?php the_header_logo(); ?>

            </div>
            <?php if (has_nav_menu('primary')) : ?>
                <a href="#mob-site-navigation" id="menuToggle" class="full-target">Menu
                    <i class="fs1 fa fa-bars" aria-hidden="true"></i>
                </a>
                <?php
                // Primary navigation menu.
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id' => 'top-menu',
                    'menu_class' => 'nav-menu sf-menu',
                    'container_id' => 'site-navigation',
                    'container_class' => 'full-static',
                ));

                // Primary navigation menu. MOBILE
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id' => 'top-mob-menu',
                    'menu_class' => 'mob-nav-menu sf-mob-menu',
                    'container_id' => 'mob-site-navigation',
                    'container_class' => 'full-menu',
                ));

            endif; ?>
        </div><!-- .site-branding -->
    </div><!-- .site-header -->
