<?php
/**
 * Created by PhpStorm.
 * User: dfieffe
 * Date: 01/06/2015
 * Time: 18:46
 */


/**
 * Adds Last tweets widget.
 */
class Last_Tweets extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'last-tweets', // Base ID
      __( 'Last Tweets', 'twentyfifteen' ), // Name
      array( 'description' => __( 'show the last Tweets', 'twentyfifteen' ), ) // Args
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
    echo $args['before_widget'];
    echo  '    <a class="twitter-timeline" href="https://twitter.com/'.$instance['title'].'" height="300" data-widget-id="604293045534756865">Tweets by @'.$instance['title'].'</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
    echo $args['after_widget'];
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Twitter Name', 'twentyfifteen' );
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Twitter Name:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
  <?php
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

    return $instance;
  }

} // class Foo_Widget

// register Foo_Widget widget
function register_Last_Tweets_widget() {
  register_widget( 'Last_Tweets' );
}
add_action( 'widgets_init', 'register_Last_Tweets_widget' );




/**
 * LAST POST WIDGETS
 *
 */
/**
 * Adds Foo_Widget widget.
 */
class Last_Posts extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'last-posts', // Base ID
      __( 'Get Last Posts', 'twentyfifteen' ), // Name
      array( 'description' => __( 'show the last Posts', 'twentyfifteen' ), ) // Args
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
    echo $args['before_widget'];
    global $post;
    $postid = get_the_ID();
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }
    $output = '<ul id="latest-post">';

    $args = array(
      'posts_per_page' => $instance['nb'],
      'post_status' => 'publish',
      'post__not_in' => array($postid),
      'post_type'   => $instance['type'],
    );
    $the_query = new WP_Query( $args );

    while ($the_query -> have_posts()) : $the_query -> the_post();
      global $post;
      $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
      $url = $thumb['0'];
      $fakeThumb = get_template_directory_uri()."/img/blank.png";
      $default_thumb = get_template_directory_uri()."/img/default-thumb.jpg";
      $postThumb = ( $url ) ? $url : $default_thumb;
      $content = get_the_content();

      $output .= '<li class="pure-g">';
      $output .= '<div class=" pure-u-1 pure-u-md-4-24">';
      $output .= '<a href="'. get_permalink().'">';
      $output .= '<img src="'.$fakeThumb.'"  data-original="'.$postThumb.'" alt="'. get_the_title().'" class="lazy" width="40" height="40" />';
      $output .=  '</a>';
      $output .= '</div>';
      $output .= '<div class="thumbnail pure-u-1 pure-u-md-20-24">';
      $output .= '<a href="'.get_permalink().'" class="sidebar-title">'. get_the_title().'</a>';
      $output .= substr(strip_tags($content), 0, 80).'...';
      $output .= '</div>';
      $output .= '</li>';

    endwhile;

    $output .= '</ul></div>';

    echo $output;
    echo $args['after_widget'];
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    $nb = ! empty( $instance['nb'] ) ? $instance['nb'] : __( '3', 'twentyfifteen' );
    $type = ! empty( $instance['type'] ) ? $instance['type'] : __( 'post', 'twentyfifteen' );
    $categories = ! empty( $instance['categories'] ) ? $instance['categories'] :'';
    $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'nb' ); ?>"><?php _e( 'Number of posts:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'nb' ); ?>" name="<?php echo $this->get_field_name( 'nb' ); ?>" type="number" value="<?php echo esc_attr( $nb ); ?>">
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Type of post :' ); ?></label>
      <?php
      $args  = array(
        'public' => true,
      );
      $post_types = get_post_types( $args, 'names','and' );
      echo '<select class="widefat"  id="'.$this->get_field_id('type').'" name="'.$this->get_field_name('type').' " type="text">';
      foreach ( $post_types as $post_type ) {
        $sel = ( $post_type == $type ) ? 'selected' : '';
        echo '<option '.$sel.' value="' . $post_type . '" >' . $post_type . '</option>';
      }
      echo '</select>';
      ?>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'categories' ); ?>"><?php _e( 'Category :' ); ?></label>
      <?php
      $args = array(
        'orderby' => 'name',
        'order' => 'ASC'
      );
      $categoriesList = get_categories($args);
      echo '<select class="widefat"  id="'.$this->get_field_id('categories').'" name="'.$this->get_field_name('categories').' " type="text">';
      foreach($categoriesList as $category) {
        $sel = ( $post_type == $categories ) ? 'selected' : '';
        echo '<option '.$sel.' value="' . get_category_link( $category->term_id ) . '">' . $category->name.' ('. $category->count . ')</option> ';

      }
      echo '</select>';
      ?>

    </p>


  <?php
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['nb'] = ( ! empty( $new_instance['nb'] ) ) ? strip_tags( $new_instance['nb'] ) : '';
    $instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
    $instance['categories'] = ( ! empty( $new_instance['categories'] ) ) ? strip_tags( $new_instance['categories'] ) : '';
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

    return $instance;
  }

}

// register Last_Posts_widget
function register_Last_Posts_widget() {
  register_widget( 'Last_Posts' );
}
add_action( 'widgets_init', 'register_Last_Posts_widget' );


/**
 * MENU widget
 *
 */

class menu_widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'menu-widget', // Base ID
      __( 'Menu widget', 'twentyfifteen' ), // Name
      array( 'description' => __( 'add a menu in the sidebar', 'twentyfifteen' ), ) // Args
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
    echo $args['before_widget'];

    if ( ! empty( $instance['title'] ) ) {
      echo '<h2 class="ln-widget">'.$instance['title'].'</h2>';
    }
    if ( ! empty( $instance['menu'] ) ) {
      wp_nav_menu( array(
        'menu_class'     => 'sidebar-nav-menu',
        'menu' => $instance['menu'],
        'menu_id'        => $instance['menu'].'sidebar',
        'menu_class'     => 'side-menu',
        'container' => false,
        'depth'         => 1,
      ) );
    }
    echo $args['after_widget'];
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    $menu = ! empty( $instance['menu'] ) ? $instance['menu'] : 'main_menu';
    $title = apply_filters('widget_title',!empty( $instance['title'] ) ? $instance['title'] : '',$instance);
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget title:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'menu' ); ?>"><?php _e( 'Please pick a menu:' ); ?></label>
      <?php
      $args  = array(
        'public' => true,
      );
      $post_types = get_post_types( $args, 'names','and' );
      $menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );

      echo '<select class="widefat"  id="'.$this->get_field_id('menu').'" name="'.$this->get_field_name('menu').' " type="text">';
      foreach ( $menus as $menuObj ) {
        $sel = ( $menuObj->name == $menu ) ? 'selected' : '';
        echo '<option '.$sel.' value="' . $menuObj->name . '" >' . $menuObj->name . '</option>';
      }
      echo '</select>';

      ?>
    </p>
  <?php
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['menu'] = ( ! empty( $new_instance['menu'] ) ) ? strip_tags( $new_instance['menu'] ) : '';
    $instance['title'] = apply_filters('widget_title',  !empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '',$instance);

    return $instance;
  }

} // class Foo_Widget

// register Foo_Widget widget
function register_menu_widget() {
  register_widget( 'menu_widget' );
}
add_action( 'widgets_init', 'register_menu_widget' );




/**
 * Adds Last tweets widget.
 */
class acf_wisywig extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'acf-wisywig', // Base ID
      __( 'Rich Text', 'twentyfifteen' ), // Name
      array( 'description' => __( 'embed rich text', 'twentyfifteen' ), ) // Args
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
    //var_dump($args);

    $title = get_field('title','widget_'.$args['widget_id']);
    $text = get_field('text','widget_'.$args['widget_id']);

    echo $args['before_widget'];
    echo '<h2 class="ln-widget">'.$title.'</h2>';
    echo '<div class="textwidget">'.$text.'</div>';
    echo $args['after_widget'];
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {

  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    return $instance;
  }

} // class Foo_Widget

// register Foo_Widget widget
function register_acf_wisywig_widget() {
  register_widget( 'acf_wisywig' );
}
add_action( 'widgets_init', 'register_acf_wisywig_widget' );




/*
 * ACF WIDGET
 * */

if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array (
    'key' => 'group_558d6dac35ee7',
    'title' => 'widget - rich text',
    'fields' => array (
      array (
        'key' => 'field_558d6dbc09d6a',
        'label' => 'title',
        'name' => 'title',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array (
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'maxlength' => '',
        'readonly' => 0,
        'disabled' => 0,
      ),
      array (
        'key' => 'field_558d6dc309d6b',
        'label' => 'text',
        'name' => 'text',
        'type' => 'wysiwyg',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array (
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'tabs' => 'all',
        'toolbar' => 'full',
        'media_upload' => 1,
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'widget',
          'operator' => '==',
          'value' => 'acf-wisywig',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
  ));

endif;
