<?php  
  $wwlocations = get_field('whitewaterLocations','option');
?>

<section id="todays-snapshot" class="snapshop-wrapper">
  <div class="wrapper">
    <div class="titlediv">
      <h3>Today's Snapshot</h3>
      <div class="dateToday"><?php echo date('l, F d') ?></div>
    </div>
  </div>


  <?php if ( $wwlocations ) { ?>
  <div class="todaySnapshotInfo">
      <ul class="location-tabs">
      <?php $i=1; foreach ($wwlocations as $w) { 
        $loc = $w['locations_taxonomy'];
        $name = (isset($loc->name) && $loc->name) ? $loc->name : '';
        //$name = $w['name'];
        $location = $w['location'];
        $infocolumns = $w['infocolumns'];
        $is_active = ($i==1) ? ' active':'';
        $is_selected = ($i==1) ? 'true':'false';
        if($name) { ?>
          <li class="tab<?php echo $is_active ?>">
            <button role="tab" aria-selected="<?php echo $is_selected ?>" aria-controls="tabpanel-<?php echo sanitize_title($name) ?>" id="tab-<?php echo sanitize_title($name) ?>">
              <span class="wname"><?php echo $name ?></span>
              <?php if ($location) { ?>
              <span class="wloc"><?php echo $location ?></span>
              <?php } ?>
            </button>
          </li>
        <?php } ?>
      <?php $i++; } ?>
      </ul> 

      <?php $j=1; foreach ($wwlocations as $w) { 
        $loc = $w['locations_taxonomy'];
        $name = (isset($loc->name) && $loc->name) ? $loc->name : '';
        //$name = $w['name'];
        $location = $w['location'];
        $infocolumns = $w['infocolumns'];
        $is_active = ($j==1) ? ' active':'';
        $is_selected = ($j==1) ? 'true':'false';
        $is_display = ($j==1) ? 'flex':'none';
        if($name) { ?>
          <?php if ($infocolumns) { ?>
          <button class="mobile-tab-heading<?php echo $is_active ?>" aria-expanded="<?php echo $is_selected ?>" aria-controls="tabpanel-<?php echo sanitize_title($name) ?>">
            <span class="wname"><?php echo $name ?></span>
            <?php if ($location) { ?>
            <span class="wloc"><?php echo $location ?></span>
            <?php } ?>
          </button>

          <div style="display:<?php echo $is_display ?>" class="info-wrapper<?php echo $is_active ?>" role="tabpanel" aria-labelledby="tab-<?php echo sanitize_title($name) ?>" id="tabpanel-<?php echo sanitize_title($name) ?>">
            <div class="flexwrap">
            <?php foreach ($infocolumns as $c) { 
              $v_title = $c['title'];
              $v_title_slug = ($v_title) ? sanitize_title($v_title) : '';
              $v_text = $c['text'];
              $v_link = $c['link'];
              $nLink = ( isset($v_link['url']) && $v_link['url'] ) ? $v_link['url'] : '';
              $nTitle = ( isset($v_link['title']) && $v_link['title'] ) ? $v_link['title'] : '';
              $nTarget = ( isset($v_link['target']) && $v_link['target'] ) ? $v_link['target'] : '_self';
              $v_is_status = $c['is_status'];
              $v_status = $c['status'];
              $status_value = ( isset($v_status['value']) ) ? $v_status['value'] : '';
              $status_label = ( isset($v_status['label']) ) ? $v_status['label'] : '';
              if($v_title) { ?>
              <div class="infocol<?php echo ($v_is_status) ? ' is-status ' . $status_value:'' ?>">
                <div class="inner">
                  <div class="title"><?php echo $v_title ?></div>
                  <?php if ($v_is_status) { ?>
                    <div class="status <?php echo $status_value ?>"><?php echo $status_label ?></div>
                  <?php } else { ?>
                    <div class="text"><?php echo $v_text ?></div>
                  <?php } ?>

                  <?php if ($nLink && $nTitle) { ?>
                  <div class="link">
                    <?php  
                    // if ( (strpos($nLink, '#') !== false) && ( (strpos($v_title_slug, 'hours') !== false) ) ) {

                    if ( (strpos($nLink, '#') !== false) && ( (strpos($nLink, '-hours') !== false) ) ) {
                      //Get Activity Schedule
                      $slug = str_replace('#','', trim($nLink));
                      $slug = str_replace('-hours','', $slug);
                      $data = getActivityScheduleToday($slug); ?>
                      <?php if($data) { ?>
                        <a href="javascript:void(0)" class="popupSchedule"><?php echo $nTitle ?> <i class="fa-light fa-angle-right"></i></a>

                        <!-- ACTIVITY SCHEDULE -->
                        <div class="activity-schedule-modal">
                          <?php  
                          $pid = $data->ID;
                          $schedule = get_field('eventDateSchedule', $pid);
                          $phFrom = get_field('pass_hours_from', $pid);
                          $phTo = get_field('pass_hours_to', $pid);
                          $note = get_field('note', $pid);
                          $pass = array($phFrom, $phTo);
                          $pass_hours = ( $pass && array_filter($pass) ) ? array_filter($pass):'';
                          if( $pass_hours ) {
                            if( count($pass_hours) == 1 ) {
                              $pass_hours = $pass_hours[0];
                            } else {
                              $pass_hours = implode(' - ', $pass_hours);
                            }
                          }
                          $today_date = date('l, F d');
                          $schedules1  = get_field('schedules', $pid);
                          $schedules2  = get_field('schedules_2', $pid);
                          $column_class = ($schedules1 && $schedules2) ? 'half':'full';
                          ?>
                          <div class="modal-title">
                            <h2>Activity Schedule</h2>
                            <p class="hours-info">
                              <?php echo $today_date ?>
                              <?php if ( $pass_hours ) { ?>
                              <span class="pass-hours">
                                Pass Hours: <span><?php echo strtoupper($pass_hours) ?></span>
                              </span>
                              <?php } ?>
                            </p>
                          </div>
                          <div class="modal-body">
                            <?php if ($note) { ?>
                             <div class="note">
                                <div class="inner">
                                  <?php echo $note ?>
                                </div>
                             </div> 
                            <?php } ?>
                            <div class="legend">
                              <span class="open">Activity Open</span>
                              <span class="closed">Activity Closed</span>
                            </div>

                            <?php if($schedules1 || $schedules2 ) { ?>
                            <div class="schedule-container <?php echo $column_class ?>">
                              
                              <?php if ($schedules1) { ?>
                              <div class="fxcol column1">
                                <?php foreach ($schedules1 as $s) { 
                                  $name = $s['group_name'];
                                  $activities = $s['items'];
                                  if($name || $activities) { ?>
                                  <div class="items">
                                    <?php if ($name) { ?>
                                    <div class="activity-name"><?php echo $name ?></div>
                                    <?php } ?>

                                    <?php if ($activities) { ?>
                                    <div class="activities">
                                      <?php foreach ($activities as $a) { 
                                        $i_name = $a['name'];
                                        $i_start_time = $a['start_time'];
                                        $i_end_time = $a['end_time'];
                                        $i_status = $a['status'];
                                        $timeArr = array($i_start_time, $i_end_time);
                                        $time = '';
                                        if($timeArr && array_filter($timeArr)) {
                                          $arrs = array_filter($timeArr);
                                          if( count($arrs) == 1 ) {
                                            $time = $timeArr[0];
                                          } else {
                                            $time = implode(' - ', $arrs);
                                          }
                                        }
                                        if($i_name) { ?>
                                        <div class="line-item">
                                          <span class="stat <?php echo $i_status ?>"></span>
                                          <span class="name"><?php echo $i_name ?></span>
                                          <?php if ($time) { ?>
                                          <span class="time"><?php echo strtoupper($time) ?></span>
                                          <?php } ?>
                                        </div>
                                        <?php } ?>
                                      <?php } ?>
                                    </div>
                                    <?php } ?>
                                  </div>

                                  <?php } ?>
                                <?php } ?>
                              </div>
                              <?php } ?>

                              <?php if ($schedules2) { ?>
                              <div class="fxcol column2">
                                <?php foreach ($schedules2 as $s) { 
                                  $name = $s['group_name'];
                                  $activities = $s['items'];
                                  if($name || $activities) { ?>
                                  <div class="items">
                                    <?php if ($name) { ?>
                                    <div class="activity-name"><?php echo $name ?></div>
                                    <?php } ?>

                                    <?php if ($activities) { ?>
                                    <div class="activities">
                                      <?php foreach ($activities as $a) { 
                                        $i_name = $a['name'];
                                        $i_start_time = $a['start_time'];
                                        $i_end_time = $a['end_time'];
                                        $i_status = $a['status'];
                                        $timeArr = array($i_start_time, $i_end_time);
                                        $time = '';
                                        if($timeArr && array_filter($timeArr)) {
                                          $arrs = array_filter($timeArr);
                                          if( count($arrs) == 1 ) {
                                            $time = $timeArr[0];
                                          } else {
                                            $time = implode(' - ', $arrs);
                                          }
                                        }
                                        if($i_name) { ?>
                                        <div class="line-item">
                                          <span class="stat <?php echo $i_status ?>"></span>
                                          <span class="name"><?php echo $i_name ?></span>
                                          <?php if ($time) { ?>
                                          <span class="time"><?php echo strtoupper($time) ?></span>
                                          <?php } ?>
                                        </div>
                                        <?php } ?>
                                      <?php } ?>
                                    </div>
                                    <?php } ?>
                                  </div>

                                  <?php } ?>
                                <?php } ?>
                              </div>
                              <?php } ?>

                            </div>      
                            <?php } ?>

                          </div>
                        </div>

                      <?php } ?>
                    <?php } else { ?>
                      <a href="<?php echo $nLink ?>" target="<?php echo $nTarget ?>"><?php echo $nTitle ?> <i class="fa-light fa-angle-right"></i></a>
                    <?php } ?>
                  </div>

                  <?php } ?>
                </div>
              </div>  
              <?php } ?>
            <?php } ?>
            </div>
          </div>
          <?php } ?>
       <?php $j++; } ?>
      <?php } ?>
  </div>  
  <?php } ?>

</section>