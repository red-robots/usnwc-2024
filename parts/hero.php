<?php
$postTypes = ['dining','race'];
if ( is_single() && in_array(get_post_type(), $postTypes) ) {
  get_template_part('parts/single-hero'); 
} else {
  get_template_part("parts/slideshow"); 
}