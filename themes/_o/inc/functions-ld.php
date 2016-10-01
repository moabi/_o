<?php
/*
 * Main function
 *
 * */


/*
 * Include editor - all editor related functions
 * Include acf    - create acf fields
 * Include blocks - acf fields logic
 * Include widgets - Last tweets - Last_Posts - menu
 * Include blog - scl_social_sharing_buttons
 * */
include_once 'functions-editor.php';
include_once 'functions-acf.php';
include_once 'functions-blocks.php';
include_once 'functions-widgets.php';
include_once 'functions-blog.php';

/*
 * Images sizes
 * Register Scripts
 * Custom post types
 * Breadcrumbs
 * Flexible structure
 * Logo && header
 */


/*
 * Create images sizes
 * */
add_image_size('thumb', 350, 350, TRUE);
add_image_size('logo', 153, 999, FALSE);
add_image_size('square', 280, 280, TRUE);
add_image_size('news', 390, 240, TRUE);
add_image_size('ref', 9999, 80, FALSE);
add_image_size('full-size', '1600', 99999, TRUE);

/*
 * ENQUEUE SCRIPTS
 * if we don't use REVOLUTION PLUGIN, no need to load jQuery in the head
 * */
if (!is_admin()) {
  add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
}

/*
 * load Jquery with CDN
 * */
function my_jquery_enqueue() {
	/*
  wp_deregister_script('jquery');
  wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', FALSE, NULL, false);
  wp_enqueue_script('jquery');
	*/
}

/*
 * Load scripts css & js
 * */
function loadAssets() {
  //needed for slick slider to work with IE8
  wp_enqueue_script('migrate', '//code.jquery.com/jquery-migrate-1.2.1.js', FALSE, '1.0', TRUE);
  wp_script_add_data('migrate', 'conditional', 'lt IE 9');
  // enqueue scripts
  wp_enqueue_script('plugins', get_bloginfo('template_directory') . '/js/plugins.js', array('jquery'), '1.0', TRUE);
  wp_enqueue_script('custom', get_bloginfo('template_directory') . '/js/custom.js', array(
    'jquery',
    'plugins'
  ), '1.0', TRUE);
  wp_enqueue_style('font', '//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Raleway:300,500,800');
}

add_action('wp_enqueue_scripts', 'loadAssets');

//Load contact form 7 if needed only
//add_filter( 'wpcf7_load_js', '__return_false' );
//add_filter( 'wpcf7_load_css', '__return_false' );

//remove wordpress Junk
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
//remove wpml css syles when WPML plugin is used for i18n
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', TRUE);


/*
 * Register Custom Post Type
 * AJAX CONTENT POPUP_TYPE
 * used to call in popup with ajax
 * not publicly available
 * */
function popup_post_type() {

  $labels = array(
    'name' => _x('popup contents', 'Post Type General Name', 'twentyfifteen'),
    'singular_name' => _x('popup content', 'Post Type Singular Name', 'twentyfifteen'),
    'menu_name' => __('Popup Blocks', 'twentyfifteen'),
    'name_admin_bar' => __('PopupType', 'twentyfifteen'),
    'parent_item_colon' => __('Parent Popup:', 'twentyfifteen'),
    'all_items' => __('All Popups', 'twentyfifteen'),
    'add_new_item' => __('Add New Popup', 'twentyfifteen'),
    'add_new' => __('Add New', 'twentyfifteen'),
    'new_item' => __('New Popup', 'twentyfifteen'),
    'edit_item' => __('Edit Popup', 'twentyfifteen'),
    'update_item' => __('Update Popup', 'twentyfifteen'),
    'view_item' => __('View Popup', 'twentyfifteen'),
    'search_items' => __('Search Popup', 'twentyfifteen'),
    'not_found' => __('Not found', 'twentyfifteen'),
    'not_found_in_trash' => __('Not found in Trash', 'twentyfifteen'),
  );
  $args = array(
    'label' => __('popup_type', 'twentyfifteen'),
    'description' => __('popup for ajax content - not a public post', 'twentyfifteen'),
    'labels' => $labels,
    'supports' => array('title', 'editor',),
    'taxonomies' => array('category'),
    'hierarchical' => FALSE,
    'public' => TRUE,
    'show_ui' => TRUE,
    'show_in_menu' => TRUE,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-lock',
    'show_in_admin_bar' => TRUE,
    'show_in_nav_menus' => TRUE,
    'can_export' => TRUE,
    'has_archive' => FALSE,
    'exclude_from_search' => TRUE,
    'publicly_queryable' => TRUE,
    'rewrite' => FALSE,
    'capability_type' => 'page',
  );
  register_post_type('popup_type', $args);

}

// Hook into the 'init' action
//add_action('init', 'popup_post_type', 0);


/*
 * Register Custom Taxonomy
 * for block type - custom post type
 * created to make it easy to re-arrange if too many custom post "block type"
 * */
function page_type() {

  $labels = array(
    'name' => _x('pages type', 'Taxonomy General Name', 'twentyfifteen'),
    'singular_name' => _x('page type', 'Taxonomy Singular Name', 'twentyfifteen'),
    'menu_name' => __('Page type', 'twentyfifteen'),
    'all_items' => __('All pages type', 'twentyfifteen'),
    'parent_item' => __('Parent page type', 'twentyfifteen'),
    'parent_item_colon' => __('Parent page type:', 'twentyfifteen'),
    'new_item_name' => __('New page type Name', 'twentyfifteen'),
    'add_new_item' => __('Add New page type', 'twentyfifteen'),
    'edit_item' => __('Edit page type', 'twentyfifteen'),
    'update_item' => __('Update page type', 'twentyfifteen'),
    'view_item' => __('View page type', 'twentyfifteen'),
    'separate_items_with_commas' => __('Separate items with commas', 'twentyfifteen'),
    'add_or_remove_items' => __('Add or remove pages type', 'twentyfifteen'),
    'choose_from_most_used' => __('Choose from the most used', 'twentyfifteen'),
    'popular_items' => __('Popular pages type', 'twentyfifteen'),
    'search_items' => __('Search pages type', 'twentyfifteen'),
    'not_found' => __('Not Found', 'twentyfifteen'),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => TRUE,
    'public' => TRUE,
    'show_ui' => TRUE,
    'show_admin_column' => TRUE,
    'show_in_nav_menus' => TRUE,
    'show_tagcloud' => TRUE,
  );
  register_taxonomy('page_type', array('block_type'), $args);

}

// Hook into the 'init' action
//add_action('init', 'page_type', 0);


/**
 *CUSTOM MENU FOR ONE PAGE
 */
function onePageMenu() {
  while (have_posts()) : the_post();
    if (have_rows('type')):
      while (have_rows('type')) : the_row();
        if (get_row_layout() == 'common_block'):
          flex_onePageMenu();
        endif;
      endwhile;
    endif;
  endwhile;
}

/*
 * ACF
 */
if (function_exists('acf_add_options_page')) {

  acf_add_options_page(array(
    'page_title' => 'Settings',
    'menu_slug' => 'lyra-settings',
    'redirect' => FALSE
  ));


}
/*
*  Create a simple sub options page called 'Footer'
*/


/*
*  Create an advanced sub page called 'Footer' that sits under the General options menu
*/

if (function_exists('acf_add_options_sub_page')) {
  acf_add_options_sub_page(array(
    'title' => 'Help',
    'parent' => 'lyra-settings'
  ));
}


/**
 * provide flexible content with ACF PRO
 * @param $is_common : if is a common block we use title ID else an integer
 */
function flexibleContent($is_common = false) {
  if (have_rows('type')):
    $i = 0;

    while (have_rows('type')) : the_row();
      /* BLOCKS FIELD - REPEATER TYPE*/
      if (get_row_layout() == 'revolution_slider'):
        /* revolution slider
         * @param $i integer : used to define ID on the page
         * @param $is_common : used to define a unique ID for common blocks with common blocks title
        */
        flex_before( $i, $is_common );
        flex_revolution();
        flex_after();
      elseif (get_row_layout() == 'blocks_field'):
        flex_before( $i, $is_common );
        flex_blocks_field();
        flex_after();
      /* SLICK CAROUSSSEL*/
      elseif (get_row_layout() == 'carousel'):
        flex_before( $i, $is_common );
        flex_carousel();
        flex_after();
      /* BLOCKS FIELD IMAGES with internal linking - REPEATER TYPE*/
      elseif (get_row_layout() == 'blocks_images'):
        flex_before( $i, $is_common );
        flex_block_image();
        flex_after();
      /* NEWS BLOCKS  - REPEATER TYPE*/
      elseif (get_row_layout() == 'news'):
        flex_before( $i, $is_common );
        flex_news();
        flex_after();
      /* PRICING TABLE BLOCKS  - REPEATER TYPE*/
      elseif (get_row_layout() == 'pricing_tables'):
        flex_before( $i, $is_common );
        flex_pricing_tables();
        flex_after();
      /* WITNESS BLOCKS  - REPEATER TYPE*/
      elseif (get_row_layout() == 'witness'):
        flex_before( $i, $is_common );
        flex_witness();
        flex_after();
      /* SOCIAL LINKS  - REPEATER TYPE*/
      elseif (get_row_layout() == 'multicolumns'):
        flex_before( $i, $is_common );
        flex_multicolumns();
        flex_after();
      /* REF BLOCKS  - REPEATER TYPE*/
      elseif (get_row_layout() == 'social'):
        flex_before( $i, $is_common );
        flex_social();
        flex_after();
      /* REF BLOCKS  - REPEATER TYPE*/
      elseif (get_row_layout() == 'references'):
        flex_before( $i, $is_common );
        flex_references();
        flex_after();
      elseif (get_row_layout() == 'latest_post'):
        flex_before( $i, $is_common );
        flex_latest_post();
        flex_after();
      elseif (get_row_layout() == 'common_block'):
        flex_commonBlocks();
      endif;
      $i++;
    endwhile;

  /* NO FLEXIBLE ? DISPLAY CONTENT*/
  else :
    //do nothing here
  endif;
}

/**
 * @param $is_global for the footer - if false set for block
 */


function the_header_logo() {
  $header_logo = get_field('logo', 'options');
  if (!empty($header_logo)):
    $logoUrl = $header_logo['url'];
    $width = $header_logo['width'];
    $height = $header_logo['height'];

    $logo = '<img src="' . $logoUrl . '" width="' . $width . '" height="' . $height . '"  alt="' . get_bloginfo('name') . '"/>';
  else:
    $logo = '<img src="' . get_template_directory_uri() . '/img/logo-header.png" width="80" height="59"  alt="' . get_bloginfo('name') . '"/>';
  endif;
  echo '<a href="' . esc_url(home_url('/')) . '" rel="home">' . $logo . '</a>';
}

function the_logo() {
  $footer_logo = get_field('logo_footer', 'options');
  if (isset($footer_logo) || !empty($footer_logo)):
    $logo_footer = $footer_logo['url'];
    $height = $footer_logo['height'];
    $width = $footer_logo['width'];
  else:
    $logo_footer = get_template_directory_uri() . '/img/logo-white-200.png';
  endif;
  echo '<div id="logoFooter"><img src="' . $logo_footer . '" width="' . $width . '" height="' . $height . '" alt="' . get_bloginfo('name') . '"/></div>';
}

if (!function_exists('the_mentions')):
  function the_mentions() {
    $output = '<a target="_blank" href="http://www.lyra-network.com/mentions-legales/">' . __("Legal", "twentyfifteen") . '</a>';
    echo $output;
  }
endif;
function Truncate($string, $length, $stopanywhere = FALSE) {
  //truncates a string to a certain char length, stopping on a word if not specified otherwise.
  if (strlen($string) > $length) {
    //limit hit!
    $string = substr($string, 0, ($length - 3));
    if ($stopanywhere) {
      //stop anywhere
      $string .= '...';
    }
    else {
      //stop on a word.
      $string = substr($string, 0, strrpos($string, ' ')) . '...';
    }
  }
  return $string;
}

function the_twitter_user_timeline($username, $height) {
  $output = '    <a class="twitter-timeline" href="https://twitter.com/_PayZen" height="' . $height . '" data-widget-id="604293045534756865">Tweets by @' . $username . '</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';

  echo $output;
}

function the_latest_post($count, $type, $exclude) {
  $output = '<ul id="latest-post">';

  $args = array(
    'showpost' => $count,
    'post_status' => 'publish',
    'post__not_in' => array($exclude),
    'post_type' => $type
  );
  $the_query = new WP_Query($args);

  while ($the_query->have_posts()) : $the_query->the_post();
    global $post;
    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
    $url = $thumb['0'];
    $fakeThumb = get_template_directory_uri() . "/img/blank.png";
    $default_thumb = get_template_directory_uri() . "/img/default-thumb.jpg";
    $postThumb = ($url) ? $url : $default_thumb;
    $content = get_the_content();

    $output .= '<li class="pure-g">';
    $output .= '<div class=" pure-u-1 pure-u-md-4-24">';
    $output .= '<a href="' . get_permalink() . '">';
    $output .= '<img src="' . $fakeThumb . '"  data-original="' . $postThumb . '" alt="' . get_the_title() . '" class="lazy" width="40" height="40" />';
    $output .= '</a>';
    $output .= '</div>';
    $output .= '<div class="thumbnail pure-u-1 pure-u-md-20-24">';
    $output .= '<a href="' . get_permalink() . '" class="sidebar-title">' . get_the_title() . '</a>';
    $output .= substr(strip_tags($content), 0, 80) . '...';
    $output .= '</div>';
    $output .= '</li>';

  endwhile;

  $output .= '</ul>';

  echo $output;
}

/**
 * Register our sidebars and widgetized areas.
 *
 */
if (!function_exists('right_sidebar')) {

// Register Sidebar
  function right_sidebar() {
    $args = array(
      'id' => 'right_sidebar',
      'name' => __('Main sidebar', 'twentyfifteen'),
      'description' => __('will be used in pages with sidebar & blog or archives', 'twentyfifteen'),
      'class' => 'right-s',
      'before_title' => '<h2 class="widget">',
      'after_title' => '</h2>',
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
    );
    $args_c = array(
      'name'          => __('Footer sidebar', 'twentyfifteen'),
      'id'            => 'sidebar-footer',
      'description'   => __('will be used in template pages with sidebar & blog or archives', 'twentyfifteen'),
      'class'         => 'footer-sidebar',
      'before_title'  => '<h2 class="widget">',
      'after_title'   => '</h2>',
      'before_widget' => '<div id="%1$s" class="widget %2$s pure-u-1 pure-u-md-12-24 pure-u-lg-6-24">',
      'after_widget'  => '</div>',
    );
    register_sidebar($args);
    register_sidebar($args_c);
  }

// Hook into the 'widgets_init' action
add_action('widgets_init', 'right_sidebar');

}

if(!function_exists('custom_sidebars')){

  function custom_sidebars() {
    $args_b = array(
      'name' => __('Sidebar %d', 'twentyfifteen'),
      'description' => __('will be used in template pages with sidebar & blog or archives', 'twentyfifteen'),
      'class' => 'pagesidebar-s-%d',
      'before_title' => '<h2 class="widget">',
      'after_title' => '</h2>',
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
    );

    register_sidebars(2,$args_b);
  }
  // Hook into the 'widgets_init' action
  add_action('widgets_init', 'custom_sidebars');
}
