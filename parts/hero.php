<?php
$postTypes = ['dining','race'];

//Using an ACF Banner (image or video option)
$postTypes2 = ['festival'];

if ( is_single() ) {
  if( in_array(get_post_type(), $postTypes) ) {
    get_template_part('parts/single-hero'); 
  } else {
    get_template_part("parts/hero-image-video"); 
  }
} else {
  get_template_part("parts/slideshow"); 
}