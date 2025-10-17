<?php    
    $current__post_id = get_the_ID();
    $args = array(
      'posts_per_page'  => 3,
      'post_type'       => $post_type,
      'post_status'     => 'publish',
      'meta_key' => 'start_date',
      'orderby' => 'meta_value_num',
      'order' => 'ASC',
      'post__not_in'=>array($current__post_id),
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
    $title_center_no_line = (isset($center_no_line) && $center_no_line) ? ' title-center-no-line':'';
    if($posts) { 
      $data_section = ($section_title) ? 'data-section="'.$section_title.'"':'';
    ?>
    <div id="section-upcoming_events-<?php echo $ctr ?>" data-posttype="<?php echo $post_type ?>" <?php echo $data_section ?> class="repeatable-block section section-upcoming_events upcoming_events_section fullwidth-float-left<?php echo $title_center_no_line ?>">
      <div class="wrapper">
        
        <?php if($section_title) { ?>
          <h2 class="section-title<?php echo ($title_center) ? ' text-center':'' ?>"><?php echo $section_title ?></h2>
        <?php } ?>

        <div class="flexwrap">
        <?php foreach($posts as $p) { 
          $pid = $p->ID;
          $title = $p->post_title;
          $image = get_field('full_image', $pid);
          // if(!$image) {
          //   $image = get_field('mobile-banner', $pid);
          // }
          $start_date = get_field('start_date', $pid);
          $pagelink = get_permalink($pid);
          $start_date_year = ($start_date ) ? date('Y', strtotime($start_date)) : '';
          if($start_date) {
            $start_date = date('l, F d', strtotime($start_date));
          }

          if( get_field('thumbnail_image', $pid) ) {
            $image = get_field('thumbnail_image', $pid);
          }

          //Try... (Adventure Dining)
          if(!$image) {
            $image = get_field('post_image_full', $pid);
          }
          ?>

          <div class="infobox">
            <div class="inside">
              
              <?php if ( get_post_type() == 'festival' ) { 
                  $images_repeater = get_field('flexslider_banner', $pid);
                  if($images_repeater) {
                    $img = $images_repeater[0];
                    if(!$image) {
                      $image = ( isset($img['image']) ) ? $img['image'] : ''; 
                    }
                  }
                ?>

                <a href="<?php echo $pagelink ?>" class="eventLink">
                  <?php if ($show_start_date && $start_date) { ?>
                  <div class="start-date"><?php echo $start_date ?></div>
                  <?php } ?>
                  <?php if ($image && isset($image['url'])) { ?>
                  <figure class="event-image THIS--">
                    <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['url'] ?>" />
                  </figure>
                  <?php } else { ?>
                  <figure class="event-image no-image">
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/image-not-available.jpg" alt="" />
                  </figure>
                  <?php } ?>
                  <?php if ($title) { ?>
                  <h3 class="event-title"><?php echo $title ?></h3>
                  <?php } ?>
                </a>

              <?php } else { ?>

                <?php if ($show_start_date && $start_date) { ?>
                <div class="start-date"><?php echo $start_date ?></div>
                <?php } ?>
                <?php if ( $image && isset($image['url']) ) { ?>
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

              <?php } ?>
            </div>
          </div>
        <?php } ?>
        </div>

      </div>
    </div>
    <?php } ?>
    