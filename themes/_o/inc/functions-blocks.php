<?php
/**
 * Created by PhpStorm.
 * User: dfieffe
 * Date: 06/05/2015
 * Time: 10:15
 */
/**
 * output the acf image)
 * @param obj : get acf field
 * @param string : additionnal classes
 * @param bolean : apply lazy loading of this image
 */
function get_acf_image($img,$size,$additionnalClass,$lazyload){
  $alt = $img['alt'];
  $title = $img['title'];
  $img_title = ($alt == '') ? $title : $alt;
  $imgClass = ($additionnalClass) ? $additionnalClass : 'acf-img ';
  $fakeThumb = get_template_directory_uri()."/img/blank.png";
  if(!$size  && !exist($img['sizes'][$size])):
    $thumb = $img['url' ];
    $width = $img['width' ];
    $height = $img['height' ];
  else:
    $thumb = $img['sizes'][ $size ];
    $width = $img['sizes'][ $size . '-width' ];
    $height = $img['sizes'][ $size . '-height' ];
  endif;
  if($lazyload):
    $output = '<img class="lazy '.$imgClass.'" data-original="'.$thumb.'" src="'.$fakeThumb.'" alt="'.$img_title.'" width="'.$width.'" height="'.$height.'"  />';
  else:
    $output = '<img class="'.$imgClass.'" src="'.$thumb.'" alt="'.$img_title.'" width="'.$width.'" height="'.$height.'" '.$imgClass.' />';
  endif;
  return  $output;

}


function flex_commonBlocks(){
  wp_reset_postdata();
  if(get_sub_field('blocks_ID')):
    $argsCommon = array(
      'post_type' => 'block_type',
      'post__in' => get_sub_field('blocks_ID'),
      'orderby' => 'post__in',
      'posts_per_page' => -1,
    );
    $block_query = new WP_Query( $argsCommon );
    // The Loop
    if ( $block_query->have_posts() ) {
      echo '<div class="common-wrapper op-content">';
      while ( $block_query->have_posts() ) {
        $block_query->the_post();
        $postObj = get_post();
        $background_color = get_field('background_color');
        $text_color = get_field('text_color');
        $imgfull = get_field('full_background');
        if($imgfull):
          $fullsize = 'full-size';
          $thumb = $imgfull['sizes'][ $fullsize ];
          $fakeThumb = get_template_directory_uri()."/img/blank.png";
        endif;
        $acfpadding = get_field('padding');
        $hide_title = get_field('hide_title');
        $acfpaddingValue = (!empty($acfpadding) ? 'padding:'.$acfpadding .'px 0;' : '');
        $bg_color = ( !empty($background_color) ? $background_color : '');

        if(!empty($imgfull)):
          $fullbg = 'data-original="'.$thumb.'"';
          $fullbgClass = ' parallax-window lazy';
          $custom_bg = 'background: '.$bg_color.' url('.$fakeThumb.') 50% 50% no-repeat fixed';
        elseif(!empty($background_color) && empty($imgfull)):
          $fullbg = "";
          $fullbgClass = '';
          $custom_bg = 'background: '.$bg_color.';';
        else:
          $fullbg = "";
          $fullbgClass = '';
          $custom_bg = '';
        endif;

        if(!empty($text_color)):
          $txtcolor = 'color:'.$text_color.';';
          $colorClass = 'colorChanged';
        else:
          $txtcolor = "";
          $colorClass = 'colorNatural';
        endif;
        $postNameHash = ($postObj->post_name != '') ? $postObj->post_name : 'warningNOID' ;
        echo '<div id="'.$postNameHash.'"  class="post post-onepage '.$colorClass.' '.$fullbgClass.' " style="'.$txtcolor.' '.$acfpaddingValue.' '.$custom_bg.'" '.$fullbg.'>';

        echo '<div class="inner-content">';
        echo '<div>';
        if(!$hide_title):
          echo '<h2 class="entry-title">' . get_the_title().'</h2>';
        endif;
        the_content();
        flexibleContent($postNameHash);
        echo "</div>";
        echo "</div>";
        echo '<div class="clearfix"></div></div>';
      }
      echo '<div class="clearfix"></div></div>';
    } else {
      // no posts found
    }
    wp_reset_postdata();
  endif;

}




/**
 * Flex_before is made for unique Blocks (in page with the flexible ACF)
 */
function flex_before($row = null,$is_common){
  if( have_rows('graphism') ):
    while ( have_rows('graphism') ) : the_row();
      /* BLOCKS FIELD - REPEATER TYPE*/
      if( get_row_layout() == 'graphiccomposition' ):
        $background_color = get_sub_field('bg');
        $text_color = get_sub_field('text_color');
        $imgfull = get_sub_field('full_background');
        if($imgfull):
          $fullsize = 'full-size';
          $thumb = $imgfull['sizes'][ $fullsize ];
          $fakeThumb = get_template_directory_uri()."/img/blank.png";
        endif;

        $acfheight = get_sub_field('height');
        $acfpadding = get_sub_field('padding');
        $blockTitle = get_sub_field('title');
        $bg_color = ( !empty($background_color) ? $background_color : '');
      endif;
    endwhile;
  /* NO FLEXIBLE */
  else :
    //do nothing here
  endif;

  $acfpaddingValue = (!empty($acfpadding) ? 'padding:'.$acfpadding .'px 0;' : '');
  $title = (isset($blockTitle) && !empty($blockTitle) ) ? '<div class="block-title"><h2 class="ln-block-h2">'.$blockTitle.'</h2><hr /></div>' : '';

  if(!empty($imgfull)):
    $fullbg = 'data-original="'.$thumb.'"';
    $fullbgClass = ' parallax-window lazy';
    $custom_bg = 'background: '.$bg_color.' url('.$fakeThumb.') 50% 50% no-repeat fixed';
  elseif(!empty($background_color) && empty($imgfull)):
    $fullbg = "";
    $fullbgClass = '';
    $custom_bg = 'background: '.$bg_color.';';
  else:
    $fullbg = "";
    $fullbgClass = '';
    $custom_bg = '';
  endif;

  if(!empty($text_color)):
    $txtcolor = 'color:'.$text_color.';';
    $colorClass = 'colorChanged';
  else:
    $txtcolor = "";
    $colorClass = 'colorNatural';
  endif;
  //if is common we use the post slug to make it unique
  $id_prefix = (!empty($is_common)) ? $is_common : 's-';


  echo '<div id="'.$id_prefix.$row.'" class="post post-onepage '.$colorClass.' '.$fullbgClass.'" style="'.$txtcolor.' '.$acfpaddingValue.' '.$custom_bg.'" '. $fullbg.'>';
  echo $title;
}

/**
 *
 */
function flex_after(){
  echo '<div class="clearfix"></div></div>';
}

/**
 *SIMPLE BLOCKS
 */
function flex_blocks_field() {
  $type = get_sub_field('type');
  if (have_rows('blocks')):
    //$the_row = acf_get_row();
    //$row_count = count($the_row['value']);
    $getAcfLoop = acf_get_loop('active');
    $the_row = (isset($getAcfLoop)) ? count($getAcfLoop['value']) : '1';
    $row_count = (isset($the_row)) ? $the_row : '1';
    $rowValue = (24 / $row_count );
    $inViewFx = ($type == "logos" || $type == "team") ? 'js-masonry inner-content': 'blocks inView pure-g';
    if($type == "logos"):
      $isotopeAtt = "data-masonry-options='{\"columnWidth\": 153, \"itemSelector\": \".isotope-logos\", \"isFitWidth\": true }'";
    elseif($type == "team"):
      $isotopeAtt = "data-masonry-options='{\"columnWidth\": 225, \"itemSelector\": \".team-logos\", \"isFitWidth\": true }'";
    else:
      $isotopeAtt = "";
    endif;

    $i = 0;
    echo '<div class="'. $inViewFx.' " '.$isotopeAtt.'>';
    // loop through the rows of data
    while (have_rows('blocks')) : the_row();
      $i++;
      // display a sub field value
      $ratio = get_sub_field('ratio');
      $rowRatio = ( $ratio ) ? $ratio : $rowValue;
      $img = get_sub_field('image');
      $icon = get_sub_field('icon');
      $title = ( get_sub_field('titre') ) ? '<h2 class="entry-title">'.get_sub_field('titre').'</h2>' : '';
      $text = ( get_sub_field('texte') ) ? '<div class="block-content">'.get_sub_field('texte').'</div>' : '';
      $wrapper = ($type == 'leftIcon') ? '<div class="leftIconwrapper">' : '<div class="leftIconNormal">';
      $step = ($type == 'steps') ? '<div class="stepper-wrapper"><span></span><div class="stepper">'.$i.'</div><span class="ls"></span></div>': '';
      //take care of images
      if($type == "logos" && $img):
        $image = get_acf_image($img, 'logo',false,true);
      elseif($type == "team" && $img):
        $image = get_acf_image($img, 'full-size', 'team',true);
      elseif($img):
        $image = get_acf_image($img, 'full-size', false,true);
      endif;
      //take care of wrapper class
      if($type == "logos" && $img):
        $gridClass = "isotope-logos";
      elseif($type == "team" && $img):
        $gridClass = "team-logos";
      else:
        $gridClass = 'pure-u-1 pure-u-md-'.$rowRatio.'-24';
      endif;

      echo '<div class="block '.$gridClass.' col-nb-'.$i.' "><div class="l-box">';
      echo $step;
      if ($img):
        echo $image;
      endif;
      if ($icon && !$img):
        echo '<div class="icon-style"><i class="fs1 ' . $icon . '" aria-hidden="true" ></i></div>';
      endif;
      if($type != "logos"):
        echo $wrapper;
        echo $title;
        echo $text;
        echo '</div>';
      endif;
      echo '</div></div>';

    endwhile;
    echo '</div>';
  endif;
}

/* SLICK CAROUSSSEL*/
function flex_carousel() {
  $type = get_sub_field('carousel_type');
  if (have_rows('carousel_blocks')):
    $the_row = acf_get_row();
    $row_count = count($the_row['value']);

    echo '<div class="' . $type . ' slick block-' . $row_count . '">';
    // loop through the rows of data
    while (have_rows('carousel_blocks')) : the_row();
      // display a sub field value
      $content = get_sub_field('carousel_block');

      echo '<div class="slick_block">';
      echo $content;
      echo '</div>';

    endwhile;
    echo '</div>';
  endif;
}



/**
 * BLOCKS FIELD IMAGES with internal linking
 * REPEATER TYPE
 * TODO: get rid of acf_get_row
 */
function flex_block_image() {
  $type = get_sub_field('type');
  //FILTERING
  $filtering = get_sub_field('filtering');
  if ($filtering == TRUE):
    $categories = get_sub_field('categories');
    $words = explode(',', $categories);
    $filteringClassContent = " filteringContent ";
    $wordslength = count($words);
    echo '<div class="filtering">';
    echo '<button href="#"  data-target="*" class="filter current">'.__('All','twentyfifteen').'</button>';
    for ($x = 0; $x < $wordslength; $x++) {
      echo '<button data-filter=".' . preg_replace("/[^a-zA-Z]+/", "", $words[$x]) . '" href="#" class="filter ">' . $words[$x] . '</button>';
    }
    echo "</div>";
  else:
    $filteringClassContent = "";
  endif;
  if (have_rows('block')):
    $the_row = acf_get_row();
    $row_count = count($the_row['value']);
    echo '<div class="' . $type . $filteringClassContent . ' inView blocks_images block-' . $row_count . ' '.$type.'">';
    // loop through the rows of data
    while (have_rows('block')) : the_row();
      // display a sub field value
      $img = get_sub_field('image');
      $linktype = get_sub_field('link_type');
      $linkbtntxt = get_sub_field('link_text');
      $linkClass = ($linktype == "popup") ? 'ajax-popup-link '.$linktype :  'noajax-link '.$linktype ;
      $linkTarget = ($linktype == "external") ? '_blank' : '_self';

      if($img):
        $image = get_acf_image($img, 'square',false,true);
      else:
        $image = '';
      endif;
      $content = get_sub_field('titre');
      $cat = get_sub_field('category');
      if($linktype == 'popup'):
        $link = get_sub_field('link');
      elseif($linktype == "external"):
        $link = get_sub_field('external');
      else:
        $link = get_sub_field('internal');
      endif;

      if (!empty($link)) {
        $linkStart = (!empty($link) ? '<a target="'.$linkTarget.'" href="' . $link . '" class="'.$linkClass.'">' : '');
      }
      else {
        $linkStart = ($link ? '<a  target="'.$linkTarget.'" href="' . $link . '" class="'.$linkClass.'">' : '');
      }

      if (!empty($link) && !empty($linkbtntxt) ) {
        $linkCTA = (!empty($link) ? '<a  target="' . $linkTarget . '" href="' . $link . '" class="fs1 btnlink ' . $linkClass . '" aria-hidden="true">' . $linkbtntxt . '</a>' : '');
      } else {
        $linkCTA = "";
      }
      $linkEnd = !empty($link) ? '</a>' : '';
      $filteringClass = ($cat ? preg_replace("/[^a-zA-Z]+/", "", $cat) : '') . '';

      $title = get_sub_field('title');
      if($type == 'title-above' && !empty($title)):
        $titleabove = '<h2>'.$linkStart.$title.$linkEnd.'</h2>';
        $titlebelow = "";
      elseif(!empty($title)):
        $titleabove = "";
        $titlebelow = '<h2>'.$linkStart.$title.$linkEnd.'</h2>';
      else:
        $titleabove = "";
        $titlebelow = "";
      endif;


      echo '<div class="blocks_image ' . $filteringClass . '">';
      echo $titleabove;
      echo $linkStart . '<div class="box-overlay"></div>'.$image . $linkEnd;
      echo $titlebelow;
      echo '<div class="content-box"><div class="content-text">' . $content . '</div> ' . $linkCTA . '</div>';
      echo '</div>';

    endwhile;
    echo '<div class="clearfix"></div></div>';
  endif;
}

function flex_news() {
  $newstype = get_sub_field('newstype');
  $newsTypeClass = ($newstype == "leftnav") ? 'tabs-content' : 'nonav';
  if (have_rows('news_type')):
    $the_row = acf_get_row();
    $row_count = count($the_row['value']);
    //var_dump($the_row);
    if ($newstype == "leftnav") {
      echo '<div class="responsiveTabs">';
      $i = 0;
      echo '<ul class=" blocks_newsNav resp-tabs-list ' . $newstype . '">';
      while (have_rows('news_type')) : the_row();
        $i++;
        // display a sub field value
        $img = get_sub_field('image');
        if($img):
          $image = get_acf_image($img, 'news',false, false);
        else:
          $image = '';
        endif;
        $title = get_sub_field('titre');
        $text = get_sub_field('texte');
        $activeClass = ($i == 1) ? 'active' : '';
        echo '<li class="block_news block ' . $activeClass . '"><a href="#"  data-tab="#tab'.$the_row['field']['ID'].'-'. $i . '">';
        echo $image;
        echo '<div class="rightnav_news"><h2>' . $title . '</h2>';
        echo '<div class="news_content">' . Truncate($text, 70);
        echo '</div></div><div class="clearfix"></div></a></li>';

      endwhile;
      echo '</ul>';

    }
    echo '<div class=" blocks_news blocks block-' . $row_count . ' ' . $newstype . ' ' . $newsTypeClass . '">';
    if ($newstype == "leftnav") {
      echo '<i class="fs1 closer fa fa-times" aria-hidden="true"></i>';
    }
    // loop through the rows of data
    $i = 0;
    while (have_rows('news_type')) : the_row();
      // display a sub field value
      $i++;
      $img = get_sub_field('image');
      if($img):
        $image = get_acf_image($img, 'news',false,false);
      endif;
      $title = get_sub_field('titre');
      $text = get_sub_field('texte');
      $link = get_sub_field('link');
      $linkStart = ($link && $newstype != "leftnav" ? '<a href="' . $link . '" class="ajax-popup-link">' : '');
      $linkEnd = ($link && $newstype != "leftnav" ? '</a>' : '');
      $activeClass = ($i == 1 && $newstype == "leftnav") ? 'active' : '';
      $zommIcon = ($newstype != "leftnav") ? '<i class="fs1 fa fa-search zoomer" aria-hidden="true"></i>' : '';
      echo '<div class="block_news block ' . $activeClass . '" id="tab'.$the_row['field']['ID'].'-'. $i . '">';
      echo $linkStart . ' ' . $zommIcon;
      echo $image . $linkEnd;
      echo '<h2 class="ln-news-title">' . $linkStart . ' ' . $title . ' ' . $linkEnd . '</h2>';
      echo '<div class="news_content">' . $text;
      if ($link && $newstype != "leftnav"):
        echo $linkStart . '[...]' . $linkEnd;
      endif;
      echo '</div></div>';

    endwhile;
    echo '</div>';
    if ($newstype == "leftnav") {
      echo '</div>';
    }
  endif;
}


function flex_pricing_tables(){
  if( have_rows('pricing_table') ):
    $the_row = acf_get_row();
    $row_count = count($the_row['value']);
    echo '<div class="blocks_pricing_table blocks block-'.$row_count.'" >';
    // loop through the rows of data
    while ( have_rows('pricing_table') ) : the_row();

      $formula = get_sub_field('formula');
      $price = get_sub_field('price');
      $color = get_sub_field('global_color');
      $button = get_sub_field('button');
      $link = get_sub_field('call_to_action');
      $linkStart = ($link ? '<a href="'.$link.'" class="normal-link">' : '');
      $linkEnd = ($link ? '</a>' : '');

      echo '<div class="block_pricing_table block">';
      echo '<div class="tablehead" style="background: '.$color.'">'
        .$formula.'<span>
'.$price.'</span></div>';
      if( have_rows('row') ):
        echo '<ul>';
        while( have_rows('row') ): the_row();
          echo '<li>'.get_sub_field('row_content').'</li>';
        endwhile;
        echo '</ul>';
      endif;
      echo '<div class="call_content" style="background: '.$color.'">';
      if($link):
        echo $linkStart.' '.$button.' '.$linkEnd;
      endif;
      echo '</div></div>';

    endwhile;
    echo '</div>';
  endif;
}

function flex_witness(){
  if( have_rows('witnesses') ):
    $the_row = acf_get_row();
    $row_count = count($the_row['value']);
    echo '<div class="slick-witness block-'.$row_count.'">';
    // loop through the rows of data
    while ( have_rows('witnesses') ) : the_row();
      // display a sub field value
      $img = get_sub_field('image');
      if($img):
        $image = get_acf_image($img, 'ref',false,false);
      endif;
      $title = get_sub_field('name');
      $text = get_sub_field('text');
      $link = get_sub_field('link');
      $linkStart = ($link ? '<a target="_blank" href="'.$link.'" class="outside-link">' : '');
      $linkEnd = ($link ? '</a>' : '');

      echo '<div class="block_witness"><div class="witness-wrapper"><div class="witness-image">';
      echo $linkStart.$image.$linkEnd;
      echo '</div><div class="witness_content">'.$text;
      echo '<span class="personn">— '.$title.'</span>';
      echo '<i class="fs1 quote fa-quote-left" aria-hidden="true"></i></div></div></div>';

    endwhile;
    echo '</div>';
  endif;
}

function flex_social(){
  if (get_sub_field('facebook') || get_sub_field('twitter') || get_sub_field('googleplus') || get_sub_field('linkedin')):
    $type = get_sub_field('type');
    echo '<ul id="blocksocial" class="'.$type.'">';
    if (get_sub_field('facebook')):
      echo '<li class="glyph">
                            <a class="fs1 fa-facebook-official" target="_blank" href="' . get_sub_field("facebook") . '" aria-hidden="true"></a>
                        </li>';
    endif;
    if (get_sub_field('twitter')):
      echo '<li class="glyph">
                          <a class="fs1 fa fa-twitter-square" target="_blank"  href="' . get_sub_field("twitter") . '" aria-hidden="true" ></a>
                      </li>';
    endif;
    if (get_sub_field('googleplus')):
      echo '<li class="glyph">
                          <a class="fs1 fa fa-google-plus" target="_blank" href="' . get_sub_field("googleplus") . '"  aria-hidden="true"></a>
                      </li>';
    endif;
    if (get_sub_field('linkedin')):
      echo '<li class="glyph">
                          <a class="fs1 fa fa-linkedin-square" target="_blank" href="' . get_sub_field("linkedin") . '" aria-hidden="true"></a>
                      </li>';
    endif;
    echo '</ul>';
  endif;
}

function flex_latest_post(){
  $format = get_sub_field('format');
  $all_categories = get_sub_field('all_categories');


  $list_type = array("abovethumb", "belowthumb", "imageonly");
  if(in_array($format, $list_type)):
    $with_thumb = true;
    $thumbSize = "square";
    $classThumb = " with280";
    $default_thumb = get_template_directory_uri()."/img/default-thumb-280.jpg";
  else:
    $with_thumb = false;
  endif;
  $count = get_sub_field('count');
  $output = '<div class="flex-latest-post '.$format.$classThumb.' nb-'.$count.'">';

  $type = get_sub_field('type');
  if( $all_categories == true):
    $argues = array(
      'order'   => 'DESC',
      'posts_per_page' => $count - 1,
      'post_status' => 'publish',
      'post_type'   => 'post',
      'nopaging '   => true,
    );
  else:
    $argues = array(
      'posts_per_page' => $count,
      'post_status' => 'publish',
      'post_type'   => 'post',
      'category__in' => array($type),
    );

  endif;

  $the_last_post = new WP_Query( $argues );

  while ($the_last_post -> have_posts()) : $the_last_post -> the_post();
    global $post;
    if($with_thumb == true):
      $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $thumbSize );
      $url = $thumb['0'];
      $width = $thumb['1'];
      $height = $thumb['2'];
      $fakeThumb = get_template_directory_uri()."/img/blank.png";
      $postThumb = ( !empty($url) ) ? $url : $default_thumb;
      $thumbnail = '<img src="'.$fakeThumb.'"  data-original="'.$postThumb.'" alt="'. get_the_title().'" class="lazy" width="'.$width.'" height="'.$height.'" />';
      $thumbnail_output = '<div class=" lp-thumb"><a href="'. get_permalink().'">'.$thumbnail.'</a></div>';
    else:
      $thumbnail_output = "";
    endif;
    if($format == 'abovethumb'):
      $title_above = '<a href="'.get_permalink().'" class="lp-title">'. get_the_title().'</a>';
      $title_below = "";
    else:
      $title_above = "";
      $title_below = '<a href="'.get_permalink().'" class="lp-title">'. get_the_title().'</a>';
    endif;
    $read_more = '<a href="'.get_permalink().'" class="btn lp-more">'.__('Read more','twentyfifteen').'</a>';
    if($format == 'imageonly'):
      $content = $read_more;
    else:
      $get_content = get_the_content();
      $content = substr(strip_tags($get_content), 0, 80).'...'.$read_more;
    endif;

    $output .= '<div class="inView block-lp">';
    $output .= $title_above;
    $output .= $thumbnail_output;
    $output .= '<div class="lp-content">';
    $output .= $title_below;
    $output .= $content;
    $output .= '</div>';
    $output .= '</div>';

  endwhile;
  wp_reset_postdata();
  $output .= '</div>';
  echo $output;
}

function flex_references(){
  $type = get_sub_field('type');
  $image_size_choice = get_sub_field('image');
  $image_size = ($image_size_choice == 'small') ? 'ref' : 'large';
  if( have_rows('ref') ):
    $getAcfLoop = acf_get_loop('active');
    $the_row = (isset($getAcfLoop)) ? count($getAcfLoop['value']) : '1';
    $row_count = (isset($the_row)) ? $the_row : '1';
    //$the_row = acf_get_row();
    //$row_count = count($the_row['value']);

    if($type == "carousel"):
      echo '<div class="acf-carousel-type"><div class="acf-ref-'.$type.' slick-multi block-'.$row_count.' ">';
    else:
    	//var_dump($type);
      echo '<div class="inner-content"><div class=" pure-g ref-grid acf-ref-'.$type.'">';
    endif;
    // loop through the rows of data
    while ( have_rows('ref') ) : the_row();
      // display a sub field value
      $img = get_sub_field('logo');
      $image = get_acf_image($img, $image_size, false, false);
      $link = get_sub_field('link');
      $linkStart = ($link ? '<a target="_blank" href="'.$link.'" class="outside-link">' : '');
      $linkEnd = ($link ? '</a>' : '');
      if($type != "carousel"):
        $iso_class = "pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4 ln-reference-item";

      else:
        $iso_class = "slick_block";
      endif;
      if($type == "grid_description"):
        $img_desc = !empty($img['description']) ? '<span class="ref-desc">'.$img['description'].'</span>': '';
        $img_title = !empty($img['title']) ? '<h4 class="ref-desc">'.$img['title'].'</h4>': '';
      endif;

      echo '<div class="'.$iso_class.'">';
      echo $linkStart. $image .$linkEnd;
      if($type == "grid_description"):
        echo $img_title. $img_desc;
      endif;
      echo '</div>';

    endwhile;

    echo '</div></div>';
  endif;
}

function flex_revolution(){
  $slider = get_sub_field('revshortcode');
  if($slider !== ""):
    echo do_shortcode($slider);
  endif;
}

function flex_multicolumns(){
  if( have_rows('column') ):
    $getAcfLoop = acf_get_loop('active');
    $the_row = (isset($getAcfLoop)) ? count($getAcfLoop['value']) : '1';
    $row_count = (isset($the_row)) ? $the_row : '1';
    //$the_row = acf_get_row();
    //$row_count = count($the_row['value']);
    $rowValue = (24 / $row_count );
    echo '<div class="multiColumn block-'.$row_count.' pure-g '.$rowValue.'">';
    // loop through the rows of data
    $rownb = 0;
    while ( have_rows('column') ) : the_row();
      // display a sub field value
      $text = get_sub_field('text');
      $ratio = get_sub_field('ratio');
      $rowRatio = ( $ratio ) ? $ratio : $rowValue;
      echo '<div class="col_block pure-u-1 pure-u-md-'.$rowRatio.'-24 col-nb-'.$rownb.'">';
      echo '<div class="l-box">'.$text.'</div>';
      echo '</div>';
      $rownb++;
    endwhile;
    echo '</div>';
  endif;
}



function socialLinks(){

  $tag = 'option';
  $ulClass = 'social-links';
  $type  = get_field('type',$tag);

  if(get_field('facebook', $tag) || get_field('twitter', $tag) || get_field('googleplus', $tag) || get_field('linkedin', $tag) || get_field('youtube', $tag)):
    echo '<ul id="'.$ulClass.'" class="'.$type.'">';
    if(get_field('facebook', $tag)):
      echo '<li class="glyph">
          <a class="fs1 fa-facebook-official" target="_blank" href="'.get_field("facebook", $tag) .'" aria-hidden="true"></a>
      </li>';
    endif;
    if(get_field('twitter', $tag)):
      echo '<li class="glyph">
            <a class="fs1 fa fa-twitter-square" target="_blank" href="'.get_field("twitter", $tag) .'"  aria-hidden="true"></a>
        </li>';
    endif;
    if(get_field('youtube', $tag)):
      echo '<li class="glyph">
            <a class="fs1 fa fa-youtube" target="_blank" href="'.get_field("youtube", $tag) .'"  aria-hidden="true" ></a>
        </li>';
    endif;
    if(get_field('googleplus', $tag)):
      echo '<li class="glyph">
            <a class="fs1 fa fa-google-plus" target="_blank" href="'.get_field("googleplus", $tag).'"  aria-hidden="true"></a>
        </li>';
    endif;
    if(get_field('linkedin', $tag)):
      echo '<li class="glyph">
            <a class="fs1 fa fa-linkedin-square" target="_blank" href="'.get_field("linkedin", $tag).'" aria-hidden="true"></a>
        </li>';
    endif;
    echo '</ul>';
  endif;

}


/*
 * One page menu
 * provide a menu based on acf choice with orders and hash links
 */
function flex_onePageMenu(){
  if(get_sub_field('blocks_ID')):
    $argsMenu = array(
      'post_type' => 'block_type',
      'post__in' => get_sub_field('blocks_ID'),
      'orderby' => 'post__in',
      'posts_per_page' => -1,
    );
    $Menu_query = new WP_Query( $argsMenu );
// The Loop
    if ( $Menu_query->have_posts() ) {
      echo '<ul id="top-menu">';
      while ( $Menu_query->have_posts() ) {
        $Menu_query->the_post();
        $in_menu = get_field('show_in_menu');
        //var_dump(get_post());
        $postObj = get_post();
        if($in_menu):
          echo '<li><a href="#/'.$postObj->post_name.'"> ' . get_the_title() .
            '</a></li>';
        endif;
      }
      echo '</ul>';
    } else {
      echo 'please add some common blocks';
    }
    wp_reset_postdata();
  endif;
}



function slidersContent(){
  $images = get_field('slider_full');
  if( $images ): ?>
    <ul>
      <?php foreach( $images as $image ): ?>
        <li>
          <a href="<?php echo $image['url']; ?>">
            <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
          </a>
          <p><?php echo $image['caption']; ?></p>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif;
}