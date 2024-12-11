<?php
  $args = array(
    'posts_per_page'  => -1,
    'post_type'       => $post_type,
    'post_status'     => 'publish',
    'meta_key' => 'start_date',
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'meta_query'      => array(
      array(
        'key' => 'start_date',
        'value' => date('Y-m-d'),
        'compare' => '>=',
        'type' => 'DATE'
      )
    )
  );
  $posts = get_posts($args);
  $posts_by_month = array();
  if($posts) {
    foreach($posts as $p) {
      $start_date = get_field('start_date', $p->ID);
      if($start_date) {
        $month = date('Ym', strtotime($start_date));
        $posts_by_month[$month][] = $p; 
      }
    }
  }

  if($posts_by_month) { ?>
  <?php foreach ($posts_by_month as $m=>$items) { 
    $m = $m . '01';
    $monthName = date('F Y',strtotime($m));
    $monthSlug = strtolower( date('F-Y',strtotime($m)) );
    $post_type = $items[0]->post_type;
  ?>
  <div id="section-upcoming_events-<?php echo $m ?>" class="repeatable-block section-upcoming_events monthly-events events--<?php echo $post_type ?>">
    <div class="wrapper">
      <h2 class="section-title"><?php echo $monthName ?></h2>

      <div class="flexwrap">
      <?php foreach ($items as $row) { 
        $pid = $row->ID;
        $title = $row->post_title;
        $start_date = get_field('start_date', $pid);
        $short_description = get_field('short_description', $pid);
        $image = get_field('mobile-banner', $pid);
        if(!$image) {
          $image = get_field('thumbnail_image', $pid);
        }
        $pagelink = get_permalink($pid);
        if($start_date) {
          $start_date = date('l, F d', strtotime($start_date));
        }
      ?>
      <div class="infobox">
        <div class="inside">
          <?php if ($start_date) { ?>
          <div class="event-date"><?php echo $start_date ?></div>
          <?php } ?>
          <?php if ($image) { ?>
          <figure class="event-image">
            <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['url'] ?>" />
          </figure>
          <?php } else { ?>
          <figure class="no-event-image"><span>Photo Not Available</span></figure>
          <?php } ?>

          <?php if ($title) { ?>
          <h3 class="event-title"><?php echo $title ?></h3>
          <?php } ?>

          <?php if ($short_description) { ?>
          <div class="summary"><?php echo $short_description ?></div>
          <?php } ?>
          
          <div class="button-block">
            <?php if ($post_type=='film-series') { ?>
              <a href="javascript:void(0)" class="button-pill button-popup-details" data-postid="<?php echo $pid ?>">See Details</a>
            <?php } else { ?>
              <a href="<?php echo $pagelink ?>" class="button-pill">See Details</a>
            <?php } ?>
          </div>
        </div>
      </div>
      <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>
  
<?php } ?>
