<?php if( get_row_layout() == 'upcoming_events_groupby_months' ) { 
  $post_type = get_sub_field('post_type');
  $btn = get_sub_field('button');
  $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
  $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
  $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self'; 

  if( $post_type ) { 
    if($post_type=='film-series') {
      include( locate_template('parts-flexible/events/previous-events.php') );
    }
    include( locate_template('parts-flexible/events/upcoming-by-month.php') );
  }

}

