<?php
/**
 * Created by PhpStorm.
 * User: dfieffe
 * Date: 29/05/2015
 * Time: 14:48
 */

function scl_social_sharing_buttons($content) {
  // Show this on post and page only. Add filter is_home() for home page
  if(is_singular('post') ){

    // Get current page URL
    $shortURL = get_permalink();

    // Get current page title
    $shortTitle = get_the_title();

    // Construct sharing URL without using any script
    $twitterURL = 'https://twitter.com/intent/tweet?text='.$shortTitle.'&amp;url='.$shortURL.'&amp;via=onlyoo';
    $facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$shortURL;
    $googleURL = 'https://plus.google.com/share?url='.$shortURL;
    $linkedinURL = 'https://www.linkedin.com/shareArticle?mini=true&url='.$shortURL.'&title='.$shortTitle;

    $atts = 'aria-hidden="true" target="_blank"';
    // Add sharing button at the end of page/page content
    $content .= '<div class="scl-social">';
    $content .= '<h5>'.__('Share this article','twentyfifteen').'</h5>';
    $content .= '<a '.$atts.' data-icon="" class="scl-link scl-twitter fs1" href="'. $twitterURL .'" ></a>';
    $content .= '<a '.$atts.' data-icon="" class="scl-link scl-facebook fs1" href="'.$facebookURL.'"></a>';
    $content .= '<a '.$atts.' data-icon="" class="scl-link scl-googleplus fs1" href="'.$googleURL.'">+</a>';
    $content .= '<a '.$atts.' data-icon="" class="scl-link scl-buffer fs1" href="'.$linkedinURL.'""></a>';
    $content .= '<div class="clearfix"></div> </div>';
    return $content;
  }else{
    // if not post/page then don't include sharing button
    return $content;
  }
};
add_filter( 'the_content', 'scl_social_sharing_buttons');

