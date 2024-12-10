<?php
//$postTypes = ['dining','race'];
$postTypes = ['dining'];

//Using an ACF Banner (image or video option)
$postTypes2 = ['festival','race'];

if ( is_single() ) {
  if( in_array(get_post_type(), $postTypes) ) {

    $enable_video_banner = get_field('enable_video_banner');
    $videoURL = get_field('video_url');
    if( $enable_video_banner && $videoURL ) {
      include( locate_template('parts/single-hero-video.php') );
    } else {
      get_template_part('parts/single-hero'); 
    }

    
  } else {
    get_template_part("parts/hero-image-video"); 
  }
} else {
  $visibility = get_field('banner_visibility');
  $is_visible = ($visibility=='hide') ? false : true;
  if($is_visible) {
    get_template_part("parts/slideshow"); 
  }
}