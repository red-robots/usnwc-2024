<?php  
  $wwlocations = get_field('whitewaterLocations','option');
  $branchName = ( isset($snapshot_branch) && $snapshot_branch ) ? $snapshot_branch : '';
  $branchNameSlug = ( isset($branchName->slug) && $branchName->slug ) ? $branchName->slug : '';
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
      <?php if ( empty($branchNameSlug) ) { ?>
        <ul class="location-tabs">
        <?php $i=1; foreach ($wwlocations as $w) { 
          $loc = $w['locations_taxonomy'];
          $name = (isset($loc->name) && $loc->name) ? $loc->name : '';
          //$name = $w['name'];
          $locInfo = $w['location'];
          $location = (isset($locInfo->name) && $locInfo->name) ? $locInfo->name : '';

          //$location = ( isset($loc->name) ) ? $loc->name : '';
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
      <?php } ?>

      <?php 

        
        if($branchNameSlug) {
          foreach ($wwlocations as $k=>$w) {
            $loc = $w['locations_taxonomy'];
            $slug = (isset($loc->slug) && $loc->slug) ? $loc->slug : '';
            if($branchNameSlug!=$slug) {
              unset($wwlocations[$k]);
            }
          }
        }

        $popupInfoArrs = array();
        foreach ($wwlocations as $k=>$w) {
          $info_popup = $w['info_popup'];
          $infocolumns = $w['infocolumns'];
          if($infocolumns) {
            foreach($infocolumns as $c) {
              $v_title = $c['title'];
              $v_link = $c['link'];
              $nLink = ( isset($v_link['url']) && $v_link['url'] ) ? $v_link['url'] : '';
              

              if($v_title && $nLink) {
                if (strpos($nLink, '#popup-') !== false) {
                  $key = str_replace('#','', $nLink);
                  $popupInfoArrs[$key] = $v_title ;
                }
              }
            }
          }
        }

        $j=1; foreach ($wwlocations as $w) { 
        $loc = $w['locations_taxonomy'];
        $info_popup = $w['info_popup'];
        $name = (isset($loc->name) && $loc->name) ? $loc->name : '';
        //$name = $w['name'];
        //$location = $w['location'];
        $locInfo = $w['location'];
        $location = (isset($locInfo->name) && $locInfo->name) ? $locInfo->name : '';
        $infocolumns = $w['infocolumns'];
        $is_active = ($j==1) ? ' active':'';
        $is_selected = ($j==1) ? 'true':'false';
        $is_display = ($j==1) ? 'flex':'none';

        $is_tabbing = ( $branchNameSlug ) ? false : true;
        if($name) { ?>
          <?php if ($infocolumns) { ?>

            <?php if ( empty($branchNameSlug) ) { ?>
            <button class="mobile-tab-heading<?php echo $is_active ?>" aria-expanded="<?php echo $is_selected ?>" aria-controls="tabpanel-<?php echo sanitize_title($name) ?>">
              <span class="wname"><?php echo $name ?></span>
              <?php if ($location) { ?>
              <span class="wloc"><?php echo $location ?></span>
              <?php } ?>
            </button>
            <?php } ?>

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
              if($status_label && strtolower($status_label)=='close') {
                $status_label = 'Closed';
              }
              if($v_title) { ?>
              <div class="infocol<?php echo ($v_is_status) ? ' is-status ' . $status_value:'' ?>">
                <div class="inner">
                  <div class="title"><?php echo $v_title ?></div>
                  <?php if ($v_is_status) { ?>
                    <div class="status <?php echo $status_value ?>"><?php echo $status_label ?></div>
                  <?php } else { ?>

                    <?php if ($v_text) { ?>
                      <?php if (strpos($v_text, '[get_hours') !== false) { ?>
                        <?php if ( $hours = do_shortcode($v_text) ) { ?>
                        <div class="text hours--info">
                          <?php 
                          $hours = strtolower($hours);
                          $hours = preg_replace('/\s+/', ' ', $hours);
                          if ( strpos($hours, ':00') !== false ) {
                            $hours = str_replace(':00','',$hours);
                          }
                          if ( strpos($hours, 'hrgen-hours') !== false ) {
                            $hours = str_replace('<span class="hrgen-hours">','',$hours);
                            $hours = str_replace('</span>','',$hours);
                            $hours = preg_replace('/\s+/','',$hours);
                          }
                          $hours = ($hours) ? strtoupper($hours) : '';
                          echo $hours; 
                          ?>
                        </div>
                        <?php } ?>
                      <?php } else { ?>
                        <div class="text"><?php echo $v_text ?></div>
                      <?php } ?>
                    <?php } ?>
                    
                  <?php } ?>

                  <?php if ($nLink && $nTitle) { ?>
                  <div class="link">
                    <?php  
                    // if ( (strpos($nLink, '#') !== false) && ( (strpos($v_title_slug, 'hours') !== false) ) ) {
                    if ( (strpos($nLink, '#') !== false) && ( (strpos($nLink, '-hours') !== false) ) ) {
                      //Get Activity Schedule
                      $slug = str_replace('#','', trim($nLink));
                      $slug = str_replace('-hours','', $slug);
                      $location_slug = $slug;
                      //$data = getActivityScheduleToday($slug); 
                      $post_limit = 30; /* 30 days */
                      $array_key = 0;
                      //$start_from_date = date('Ymd', strtotime('-1 day')); /* yesterday */
                      $start_from_date = date('Ymd'); /* today's date */
                      $entries = getUpcomingEventsCustom($post_limit, $array_key, $start_from_date);
                      ?>
                      <?php if($entries) { ?>
                        <a href="javascript:void(0)" data-schedule="<?php echo $slug ?>" class="popupSchedule"><?php echo $nTitle ?> <i class="fa-light fa-angle-right"></i></a>

                        <!-- ACTIVITY SCHEDULE -->
                        <?php include( locate_template('parts-calendar/activity-schedule-popup.php') ); ?>
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

          <?php if ($info_popup) { ?>
            <?php foreach ($info_popup as $info) { 
              $info_text = $info['info_modal'];
              $info_id = ($info['popup_id']) ? preg_replace('/\s+/', '', trim($info['popup_id'])) : '';
              if($info_text && $info_id) { 
                $key = str_replace('#','', $info_id);
                $popup_title = ( isset($popupInfoArrs[$key]) && $popupInfoArrs[$key] ) ? $popupInfoArrs[$key] : '';
              ?>
              <div class="info-popup" data-todaypopinfo="<?php echo $info_id ?>" style="display:none;">
                <div class="info-popup-content">
                  <?php if ($popup_title) { ?>
                  <div class="info-modal-title"><h2><?php echo $popup_title ?></h2></div>
                  <?php } ?>
                  <div class="info-text"><?php echo anti_email_spam($info_text) ?></div>
                </div>
              </div> 
              <?php } ?>
            <?php } ?>
          <?php } ?>

          <?php } ?>
       <?php $j++; } ?>
      <?php } ?>
  </div>  
  <?php } ?>

</section>