 <?php if( get_row_layout() == 'upcoming_events' ) { ?>
  <?php 
    $section_title = get_sub_field('section_title');
    $center_no_line = get_sub_field('center_no_line');
    $post_type = get_sub_field('post_type');
    $btn = get_sub_field('button');
    $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
    $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
    $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
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
      $title_center_no_line = (isset($center_no_line) && $center_no_line) ? ' title-center-no-line':'';
      if($posts) { ?>
      <div id="section-upcoming_events-<?php echo $ctr ?>" data-posttype="<?php echo $post_type ?>" class="repeatable-block section section-upcoming_events<?php echo $title_center_no_line ?>">
        <div class="wrapper">
          
          <?php if($section_title) { ?>
            <h2 class="section-title"><?php echo $section_title ?></h2>
          <?php } ?>

          <div class="flexwrap">
          <?php foreach($posts as $p) { 
            $pid = $p->ID;
            $title = $p->post_title;
            // $image = get_field('full_image', $pid);
            // if(!$image) {
            //   $image = get_field('mobile-banner', $pid);
            // }

            $image = get_field('thumbnail_image', $pid);
            $start_date = get_field('start_date', $pid);
            $pagelink = get_permalink($pid);
            $start_date_year = ($start_date ) ? date('Y', strtotime($start_date)) : '';
            if($start_date) {
              $start_date = date('l, F d', strtotime($start_date));
            }
            ?>
            <div class="infobox">
              <div class="inside">
                <?php if ($post_type!=='dining') { ?>
                  <?php if ($start_date) { ?>
                  <div class="event-date"><?php echo $start_date ?></div>
                  <?php } ?>
                <?php } ?>
                
                <?php if ($image && isset($image['url'])) { ?>
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


                <?php 
                //ADVENTURE DINING
                if ($post_type=='dining') { 
                  $schedules = get_field('schedule_days', $pid);

                  if($schedules) { ?>
                  <div class="schedule-items">
                    <?php foreach ($schedules as $s) { 
                      $times = $s['coursetime'];
                      if($times) { ?>
                      <div class="course-times">
                      <?php foreach ($times as $t) { 
                        //$time = $t['time'];
                        $course = $t['course'];
                        $product_link = $t['product_link'];
                        $xbtn = $t['extra_link'];
                        $xbtnUrl = (isset($xbtn['url']) && $xbtn['url']) ? $xbtn['url'] : '';
                        $xbtnTitle = (isset($xbtn['title']) && $xbtn['title']) ? $xbtn['title'] : '';
                        $xbtnTarget = (isset($xbtn['target']) && $xbtn['target']) ? $xbtn['target'] : '_self';
                        $shortnameDay = '';
                        if($course) { 
                          $date  = $course;
                          $dayParts = explode(',', $course);
                          if(count($dayParts) > 1) {
                            $day = $dayParts[0];
                            if( $dayName = shortenDayName($day) ) {
                              $date = $dayName . ', ' . $dayParts[1];
                            }
                          }

                          if($start_date_year) {
                            if($start_date_year!=$current_year) {
                              $date .= ' ' . $start_date_year;
                            } 
                          }

                          if($date) {
                            $date = strtoupper($date);
                            if (strpos($date, 'TH') !== false) {
                              $date = str_replace('TH','<sup style="text-transform:none">th</sup>', $date);
                            }
                            else if (strpos($date, 'ND') !== false) {
                              $date = str_replace('ND','<sup style="text-transform:none">nd</sup>', $date);
                            }
                            else if (strpos($date, 'ST') !== false) {
                              $date = str_replace('TH','<sup style="text-transform:none">st</sup>', $date);
                            }
                          }
                        ?>
                        <div class="lineItem">
                          <span class="time"><?php echo $date ?></span>
                          <?php if($xbtnUrl || $product_link) { ?>
                          <span class="links">
                            <?php if($product_link) { ?>
                            <a data-accesso-keyword="<?php echo $product_link ?>" href="javascript:void(0)">Register</a>
                            <?php } ?>

                            <?php if($xbtnUrl && $xbtnTitle) { ?>
                            <a href="<?php echo $xbtnUrl ?>" target="<?php echo $xbtnTarget ?>"><?php echo $xbtnTitle ?></a>
                            <?php } ?>
                          </span>
                          <?php } ?>
                        </div>
                        <?php } ?>
                      <?php } ?>
                      </div>
                      
                      <?php } ?>
                    <?php } ?>
                  </div>  
                  <?php } ?>
                <?php } ?>

              </div>
            </div>
          <?php } ?>
          </div>

          <?php if($btnUrl && $btnTitle) { ?>
          <div class="buttonBlock buttonMiddle">
            <a href="<?php echo $btnUrl ?>" target="<?php echo $btnTarget ?>" class="button uppercase"><?php echo $btnTitle ?></a>
          </div>
          <?php } ?>

        </div>
      </div>
      <?php } ?>
    <?php } ?>
<?php } 