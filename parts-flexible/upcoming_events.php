<?php if( get_row_layout() == 'upcoming_events' ) { ?>

  <?php 
  $section_title = get_sub_field('section_title');
  $post_type = get_sub_field('post_type');

  // $btn = get_sub_field('button');
  // $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
  // $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
  // $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
  $current_date = date('Y-m-d');
  $current_year = date('Y');
  if($post_type) { 
    $args = array(
      'posts_per_page'  => 3,
      'post_type'       => $post_type,
      'post_status'     => 'publish',
      'meta_key' => 'start_date',
      'orderby' => 'meta_value_num',
      'order' => 'ASC',
      'meta_query'      => array(
        'relation' => 'OR',
        array(
          'key' => 'start_date',
          'value' => $current_date,
          'compare' => '>=',
          'type' => 'DATE'
        ),
        array(
          'key' => 'end_date',
          'value' => $current_date,
          'compare' => '>=',
          'type' => 'DATE'
        ),
      )
    );


    $posts = get_posts($args);
    if($posts) { 
      $data_section = ($section_title) ? 'data-section="'.$section_title.'"':'';
    ?>
    <div id="section-upcoming_events-<?php echo $i ?>" data-posttype="<?php echo $post_type ?>" <?php echo $data_section ?> class="repeatable-block section section-upcoming_events upcoming_events_section fullwidth-float-left">
      <div class="wrapper">
        
        <?php if($section_title) { ?>
          <h2 class="section-title"><?php echo $section_title ?></h2>
        <?php } ?>

        <div class="flexwrap">
        <?php foreach($posts as $p) { 
          $pid = $p->ID;
          $title = $p->post_title;
          $image = get_field('full_image', $pid);
          if(!$image) {
            $image = get_field('mobile-banner', $pid);
          }
          $start_date = get_field('start_date', $pid);
          $pagelink = get_permalink($pid);
          $start_date_year = ($start_date ) ? date('Y', strtotime($start_date)) : '';
          if($start_date) {
            $start_date = date('l, F d', strtotime($start_date));
          }
          ?>
          <div class="infobox">
            <div class="inside">
              
              <?php if ($image) { ?>
              <figure class="event-image">
                <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['url'] ?>" />
              </figure>
              <?php } ?>
              <?php if ($title) { ?>
              <h3 class="event-title"><?php echo $title ?></h3>
              <?php } ?>
              <div class="button-block">
                <a href="<?php echo $pagelink ?>" class="button-pill">See Details</a>
              </div>

            </div>
          </div>
        <?php } ?>
        </div>

      </div>
    </div>
    <?php } ?>
  <?php } ?>

<?php } ?>