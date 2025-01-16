<?php if ( isset($featured_event) && $featured_event ) { ?>
  <?php if( $obj = get_post($featured_event) ) {
    $pid = $obj->ID;
    $post_type = $obj->post_type;
    $page_title = $obj->post_title;
    $pagelink = get_permalink($pid);
    $thumbnail = '';
    $short_description = get_field('event__short_description', $pid);
    // if( get_field('thumbnail_image', $pid) ) {
    //   $thumbnail = get_field('thumbnail_image', $pid);
    // }

    $featuredImage = '';

    if( get_field('full_image', $pid) ) {
      $thumbnail = get_field('full_image', $pid);
      $featuredImage = get_field('full_image', $pid);
    }

    if( $post_type=='dining' ) {
      $thumbnail = get_field('mobile-banner', $pid);
      $featuredImage = get_field('mobile-banner', $pid);
    }

    $start = get_field('start_date', $pid);
    $end = get_field('end_date', $pid);
    if(empty($end)) {
      $end = $start;
    }
    if(empty($start)) {
      $start = $end;
    }

    $start_str = ($start) ? strtotime($start) : '';
    $end_str = ($end) ? strtotime($end) : $start_str;

    $event_dates = '';
    if($start || $end) {
      if($start==$end) {
        $event_dates .= date('M j, Y', strtotime($start));
      } else {

        if($start && $end) {
          //Check if same month
          if( date('m', strtotime($start))==date('m', strtotime($end)) ) {
            $start_date_month = date('M j', strtotime($start));
            $end_date_month = date('j, Y', strtotime($end));
            $event_dates .= $start_date_month . '-' . $end_date_month;

            //Check if the same day
            if( date('d', strtotime($start))==date('d', strtotime($end)) ) {
              $event_dates = date('M j, Y', strtotime($start));
            }

          } else {
            //Different months
            $start_date_month = date('M j', strtotime($start));
            
            //Check if start month is past, 
            //then change month to current if end date is future
            if( strtotime($start) < strtotime(date('Ymd')) ) {
              $start_date_month = date('M j');
            } 

            $end_date_month = date('M j, Y', strtotime($end));
            $event_dates .= $start_date_month . '-' . $end_date_month;
          }
        }
      }
    }

  ?>

  <div class="infoBox first post-type--<?php echo $post_type ?>">
    <div class="wrap">
      <figure>
        <?php if ($thumbnail && isset($thumbnail['url'])) { ?>
          <img src="<?php echo $thumbnail['url'] ?>" alt="" />
        <?php } else { ?>
          <img src="<?php echo get_template_directory_uri(); ?>/images/image-not-available.jpg" alt="" />
        <?php } ?>
      </figure>
      <div class="text">
        <h3><?php echo $page_title; ?></h3>
        <?php if ($event_dates) { ?>
        <div class="event-date"><?php echo $event_dates ?></div>
        <?php } ?>
        <?php if ($short_description) { ?>
        <div class="short-description"><?php echo $short_description ?></div>
        <?php } ?>
        <div class="buttonwrap">
          <a href="<?php echo $pagelink; ?>" class="more-btn">See Details</a>
        </div>
      </div>
    </div>
  </div>

  <?php } ?>
<?php } ?>