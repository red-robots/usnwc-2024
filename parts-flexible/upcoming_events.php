<?php if( get_row_layout() == 'upcoming_events' ) { ?>

  <?php 
  $section_title = get_sub_field('section_title');
  $center_no_line = get_sub_field('center_no_line');
  $title_center = get_sub_field('section_title_align_center');
  $show_start_date = get_sub_field('show_start_date');
  $post_type = get_sub_field('post_type');

  // $btn = get_sub_field('button');
  // $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
  // $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
  // $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
  $current_date = date('Y-m-d');
  $current_year = date('Y');
  if($post_type) { 
    include( locate_template('parts-flexible/events/upcoming.php') );
  } ?>

<?php } ?>