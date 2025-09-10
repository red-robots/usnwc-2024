<?php if( isset($data->ID) ) {
  $has_future_schedule = queryActivitySchedulePosts(5); ?>
  <div data-schedule="<?php echo $slug ?>" data-pid="<?php echo $data->ID ?>" class="activity-schedule-modal today-activity-schedule" style="display:none;">
    <?php  
    $pid = $data->ID;
    $schedule = get_field('eventDateSchedule', $pid);
    $schedule_date = ($schedule) ? str_replace('-','',$schedule) : '';
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
    if( isset($is_navigate) && $is_navigate ) {
      if($schedule_date) {
        $today_date = date('l, F d', strtotime($schedule_date));
      }
    }
    ?>
    <div class="modal-title">
      <div class="modal-title-inner">
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
      <?php if ($has_future_schedule) { ?>
      <button class="scheduleNav previous-schedule hide" data-for="<?php echo $slug ?>" data-action="previous" data-index="4"><span class="sr-only">Previous Schedule</span></button>
      <button class="scheduleNav next-schedule" data-for="<?php echo $slug ?>" data-action="next" data-index="1"><span class="sr-only">Next Schedule</span></button>
      <?php } ?>
    </div>
    <div class="modal-body">
      <div class="modal-body-inner" data-event-date="<?php echo $schedule ?>" data-pid="<?php echo $data->ID ?>">
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
                    $custom_time_text = ( isset($a['custom_text']) ) ? $a['custom_text'] : '';
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
                      <?php if ($custom_time_text) { ?>
                        <span class="time"><?php echo $custom_time_text ?></span>
                      <?php } else { ?>
                        <?php if ($time) { ?>
                        <span class="time"><?php echo strtoupper($time) ?></span>
                        <?php } ?>
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
                    $custom_time_text = ( isset($a['custom_text']) ) ? $a['custom_text'] : '';
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
                      <?php if ($custom_time_text) { ?>
                        <span class="time"><?php echo $custom_time_text ?></span>
                      <?php } else { ?>
                        <?php if ($time) { ?>
                        <span class="time"><?php echo strtoupper($time) ?></span>
                        <?php } ?>
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
  </div>
<?php } ?>