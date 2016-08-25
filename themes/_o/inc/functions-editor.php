<?php
/**
 * Created by PhpStorm.
 * User: dfieffe
 * Date: 18/05/2015
 * Time: 14:34
 */

/*
 * All the fn linked to the editor
 * add class to images with link
 * Shortcodes
 * Gist embed
 * map embed
 * Custom buttons in tiny mce
 * */

/**
 * Attach a class to linked images' parent anchors
 * Works for existing content
 */
function give_linked_images_class($content) {

  $classes = 'img'; // separate classes by spaces - 'img image-link'

  // check if there are already a class property assigned to the anchor
  if ( preg_match('/<a.*? class=".*?"><img/', $content) ) {
    // If there is, simply add the class
    $content = preg_replace('/(<a.*? class=".*?)(".*?><img)/', '$1 ' . $classes . '$2', $content);
  } else {
    // If there is not an existing class, create a class property
    $content = preg_replace('/(<a.*?)><img/', '$1 class="' . $classes . '" ><img', $content);
  }
  return $content;
}

add_filter('the_content','give_linked_images_class');
/**
 * Embed Gists with a URL
 *
 * Usage:
 * Paste a gist link into a blog post or page and it will be embedded eg:
 * https://gist.github.com/2926827
 *
 * If a gist has multiple files you can select one using a url in the following format:
 * https://gist.github.com/2926827?file=embed-gist.php
 *
 * Updated this code on June 14, 2014 to work with new(er) Gist URLs
 */

wp_embed_register_handler( 'gist', '/https?:\/\/gist\.github\.com\/([a-z0-9]+)(\?file=.*)?/i', 'bhww_embed_handler_gist' );

function bhww_embed_handler_gist( $matches, $attr, $url, $rawattr ) {

  $embed = sprintf(
    '<script src="https://gist.github.com/%1$s.js%2$s"></script>',
    esc_attr($matches[1]),
    esc_attr($matches[2])
  );

  return apply_filters( 'embed_gist', $embed, $matches, $attr, $url, $rawattr );

}

/*
 * shortcode for custom buttons
 */
// init process for registering our button
add_action('init', 'wpse72394_shortcode_button_init');
function wpse72394_shortcode_button_init() {

  //Abort early if the user will never see TinyMCE
  if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
    return;

  //Add a callback to regiser our tinymce plugin
  add_filter("mce_external_plugins", "wpse72394_register_tinymce_plugin");

  // Add a callback to add our button to the TinyMCE toolbar
  add_filter('mce_buttons', 'wpse72394_add_tinymce_button');
}


//This callback registers our plug-in
function wpse72394_register_tinymce_plugin($plugin_array) {
  $plugin_array['wpse72394_button'] = get_template_directory_uri() . '/js/admin/shortcodes.js';
  return $plugin_array;
}

//This callback adds our button to the toolbar
function wpse72394_add_tinymce_button($buttons) {
  //Add the button ID to the $button array
  $buttons[] = "wpse72394_button";
  return $buttons;
}

function customBtn_shortcode( $atts ) {

  // Attributes
  $btn = shortcode_atts( array(
    'link' => 'http://example.com',
    'text' => 'Découvrir',
    'background' => 'rgb(37, 160, 232)',
    'target' => '_blank',
    'icon' => 'a',
    'sub' => '',
    'align' => 'alignleft',
    'image' => '',
    'iconalign' => '90%',
  ), $atts );
  if(!empty($btn['image'])):
    $img = ' url('.$btn['image'].') '.$btn['iconalign'].' 50% no-repeat';
  else:
    $img = '';
  endif;

  $style = 'style="background:'.$btn['background']. $img.';"';
  if(!empty($btn['image']) || !empty($btn['icon'])):
    $classWithIcon = 'xtralarge';
  else:
    $classWithIcon = 'regular';
  endif;
  if (!empty($btn['icon'])):
    $aligner = ( isset($btn['iconAlign']) ? $btn['iconAlign'] : 'alignleft');
    $icon = '<span class="fs1 '.$aligner.'" aria-hidden="true" data-icon="'.$btn['icon'].'"></span>';
  else:
    $icon = '';
  endif;

    $alignerBtn = !empty($btn['align']) ? $btn['align'] : 'center';


  return '<span class="btn-reg customBtn '.$alignerBtn.' '.$classWithIcon.'"  ><a target="'.$btn['target'].'"  href="'.$btn['link'].'" '.$style.'>'.$btn['text'].' '.$icon.' <span class="sub">'.$btn['sub'].'</span></a></span>';
}
add_shortcode( 'button', 'customBtn_shortcode' );
/*
 * Register a css for the admin
 * */
function twentyfifteen_add_editor_styles() {
  add_editor_style( 'css/editor-style.css' );
}
add_action( 'admin_init', 'twentyfifteen_add_editor_styles' );

/*
 * custom wrapper in the editor
 */
function wpb_mce_buttons_2($buttons) {
  array_unshift($buttons, 'styleselect');
  return $buttons;
}
add_filter('mce_buttons_2', 'wpb_mce_buttons_2');

/*
* Callback function to filter the MCE settings
*/
function my_mce_before_init_insert_formats( $init_array ) {

// Define the style_formats array

  $style_formats = array(
    // Each array child is a format with it's own settings
    array(
      'title' => 'Orange Button',
      'block' => 'span',
      'classes' => 'btn-orange btn-reg',
      'wrapper' => true,

    ),
    array(
      'title' => 'Blue Button',
      'block' => 'span',
      'classes' => 'btn-blue btn-reg',
      'wrapper' => true,

    ),
    array(
      'title' => 'Green Button',
      'block' => 'span',
      'classes' => 'btn-green btn-reg',
      'wrapper' => true,
    ),
    array(
      'title' => 'Huge Title',
      'block' => 'div',
      'classes' => 'hugeTitle',
      'wrapper' => true,
    ),
    array(
      'title' => 'White border',
      'block' => 'span',
      'classes' => 'border-white',
      'wrapper' => true,
    ),
    array(
      'title' => 'Black border',
      'block' => 'span',
      'classes' => 'border-black',
      'wrapper' => true,
    ),
    array(
      'title' => 'Green box',
      'block' => 'div',
      'classes' => 'green-box',
      'wrapper' => true,
    ),
    array(
      'title' => 'Gray box',
      'block' => 'div',
      'classes' => 'ln-gray-box',
      'wrapper' => true,
    ),
    array(
      'title' => 'text light',
      'block' => 'span',
      'classes' => 'text5',
      'wrapper' => true,
    ),
  );
  // Insert the array, JSON ENCODED, into 'style_formats'
  $init_array['style_formats'] = json_encode( $style_formats );

  return $init_array;

}
// Attach callback to 'tiny_mce_before_init'
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );



/*Google Map shortcode*/

// Add Shortcode
function gmap_shortcode( $atts ) {
  // Attributes
  extract( shortcode_atts(
      array(
        'localisation' => 'lyra',
      ), $atts )
  );
  $args = array(
    'id' => "sc-lyra",
    'lattitude' => "43.5414097",
    'longitude' => "1.5165507000000389",
    'zoom' => 16,
    'address' => addslashes('109 rue de l\'innovation, Labége'),
    'map_text' => addslashes('Lyra Network'),
  );
  $jsMap  = gmap_obj($args);
  return $jsMap.'<div id="sc-lyra" class="gmap"></div>';
}
add_shortcode( 'googlemap', 'gmap_shortcode' );

/*
 * Embed a google map, shortcode & widget
 * @param $args array :
 * @param $map_id string The id of map and div
 * @param  $marker_latt integer map lattitude
 * @param $marker_long integer Map longitude
 * @param $zoom integer google map level of zoom 6 to 16
 * @param $map_text string text embeded in map popup
 *
 * */

function gmap_obj($args){
  /*
  $args = array(
      'id' => "map-lyra",
      'lattitude' => "43.5414097",
      'longitude' => "1.5165507000000389",
      'zoom' => 16,
      'address' => null,
      'map_text' => null,
    );*/

  $output = "<script src='https://maps.googleapis.com/maps/api/js?key=&sensor=false&extension=.js'></script>";

  $output .= "<script>
  google.maps.event.addDomListener(window, 'load', init);
  var map;
  function init() {
    var mapOptions = {
      center: new google.maps.LatLng(".$args['lattitude'].",".$args['longitude']."),
      zoom: ".$args['zoom'].",
      zoomControl: true,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL
      },
      disableDoubleClickZoom: true,
      mapTypeControl: true,
      mapTypeControlOptions: {
        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
      },
      scaleControl: true,
      scrollwheel: false,
      panControl: true,
      streetViewControl: false,
      draggable : true,
      overviewMapControl: false,
      overviewMapControlOptions: {
        opened: false
      },
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      styles: [{\"featureType\":\"all\",\"elementType\":\"all\",\"stylers\":[{\"saturation\":-100},{\"gamma\":0.5}]}]
    };
    var mapElement = document.getElementById('".$args['id']."');
    var map = new google.maps.Map(mapElement, mapOptions);
    var locations = [
      ['".$args['map_text']."', '".$args['address']."', 'undefined', 'undefined','undefined', ".$args['lattitude'].", ".$args['longitude'].", '".get_template_directory_uri()."/img/marker.png']
    ];
    for (i = 0; i < locations.length; i++) {
      if (locations[i][1] =='undefined'){ description ='';} else { description = locations[i][1];}
      if (locations[i][2] =='undefined'){ telephone ='';} else { telephone = locations[i][2];}
      if (locations[i][3] =='undefined'){ email ='';} else { email = locations[i][3];}
      if (locations[i][4] =='undefined'){ web ='';} else { web = locations[i][4];}
      if (locations[i][7] =='undefined'){ markericon ='';} else { markericon = locations[i][7];}
      marker = new google.maps.Marker({
        icon: markericon,
        position: new google.maps.LatLng(locations[i][5], locations[i][6]),
        map: map,
        title: locations[i][0],
        desc: description,
        tel: telephone,
        email: email,
        web: web
      });
      if (web.substring(0, 7) != \"http://\") {
        link = \"http://\" + web;
      } else {
        link = web;
      }
      bindInfoWindow(marker, map, locations[i][0], description, telephone, email, web, link);
    }
    function bindInfoWindow(marker, map, title, desc, telephone, email, web, link) {
      var infoWindowVisible = (function () {
        var currentlyVisible = false;
        return function (visible) {
          if (visible !== undefined) {
            currentlyVisible = visible;
          }
          return currentlyVisible;
        };
      }());
      iw = new google.maps.InfoWindow();
      google.maps.event.addListener(marker, 'click', function() {
        if (infoWindowVisible()) {
          iw.close();
          infoWindowVisible(false);
        } else {
          var html= \"<div style='color:#000;background-color:#fff;padding:5px;width:90%;'><h4>\"+title+\"</h4><p>\"+desc+\"<p><a href='mailto:\"+email+\"' >\"+email+\"<a><a href='\"+link+\"'' >\"+web+\"<a></div>\";
          iw = new google.maps.InfoWindow({content:html});
          iw.open(map,marker);
          infoWindowVisible(true);
        }
      });
      google.maps.event.addListener(iw, 'closeclick', function () {
        infoWindowVisible(false);
      });
    }
  }
</script>";

  return $output;
}
