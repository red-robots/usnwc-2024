<?php
/**
 * Template Name: Activity Schedule
 */

get_header(); 
$dateToday = date('l, F m, Y');
$blank_image = THEMEURI . "images/square.png";
$square = THEMEURI . "images/square.png";
$flexslider = get_field( "flexslider_banner" );
$slidesCount = ($flexslider) ? count($flexslider) : 0;
$slideImages = array();
if($flexslider) {
	foreach($flexslider as $r) {
		if( isset($r['image']['url']) ) {
			$slideImages[] = $r['image']['url'];
		}
	}
}
$has_banner = ($slideImages) ? 'has-banner':'no-banner';

$customDate = ( isset($_GET['date']) && $_GET['date'] ) ? $_GET['date'] : '';
$date_title = ($customDate) ?  date('l, F j, Y', strtotime($customDate)) : date('l, F j, Y');
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full activity-schedule <?php echo $has_banner ?>">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php 
				$custom_page_title = get_field("custom_page_title"); 
				$page_title = ($custom_page_title) ? $custom_page_title : get_the_title();
			?>
			<div id="schedule--info" class="text-centered-section intro-text-wrap">
				<div class="wrapper">
					<?php the_content(); ?>
				</div>
			</div>
		<?php endwhile;  ?>

		<div class="schedule-activities-info new-layout-v2 full">
      <div class="subhead">
        <div class="date-hours">
          <h2 class="event-date"><?php //echo date('l, F j, Y'); ?><?php echo $date_title ?></h2>
        </div>
      </div>

      <?php 
      $wwlocations = get_field('whitewaterLocations','option');
      if ( $wwlocations ) { ?>
      <div class="todaySnapshotInfo">
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

        <?php 
        $j=1; foreach ($wwlocations as $w) { 
          $loc = $w['locations_taxonomy'];
          $name = (isset($loc->name) && $loc->name) ? $loc->name : '';
          $slug = (isset($loc->slug) && $loc->slug) ? $loc->slug : '';
          $locInfo = $w['location'];
          $location = (isset($locInfo->name) && $locInfo->name) ? $locInfo->name : '';
          $is_active = ($j==1) ? ' active':'';
          $is_selected = ($j==1) ? 'true':'false';
          $is_display = ($j==1) ? 'flex':'none';
          //$data = getActivityScheduleToday($slug); 
          $data = getActivityScheduleToday($slug, $customDate); 
          if($data) { ?>

          <button class="mobile-tab-heading<?php echo $is_active ?>" aria-expanded="<?php echo $is_selected ?>" aria-controls="tabpanel-<?php echo $slug; ?>">
            <span class="wname"><?php echo $name ?></span>
            <?php if ($location) { ?>
            <span class="wloc"><?php echo $location ?></span>
            <?php } ?>
          </button>

          <div class="info-wrapper<?php echo $is_active ?>" role="tabpanel" aria-labelledby="tab-<?php echo sanitize_title($name) ?>" id="tabpanel-<?php echo sanitize_title($name) ?>">
            <!-- ACTIVITY SCHEDULE -->
            <div data-schedule-single="<?php echo $slug ?>" class="activity-schedule-modal schedule-single">
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
                  <?php //echo $today_date ?>
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
          </div>

         <?php $j++; } ?>
        <?php } ?>
    </div>  
  <?php } ?>
	</div>


</div><!-- #primary -->


<script type="text/javascript">
jQuery(document).ready(function($){
	if( $(".activities h3.type").length > 0 ) {
		$(".activities h3.type").each(function(){
			var text = $(this).text().replace(/\s+/g,' ').trim();
			var wrap = $(this).parents(".activity-info");
			var parentId = wrap.attr("id");
			var tab = '<span class="mini-nav"><a href="#'+parentId+'">'+text+'</a></span>';
			$("#tabcontent").append(tab);
		});
		$("#pageTabs").show().addClass("show-tabs");
	}

  if( typeof params.date!='undefined' || params.date!=null ) {
    var scrollTarget = $('#schedule--info');
    $('html, body').animate({
      scrollTop: scrollTarget.offset().top - 150
    }, 500, function() {
      if ( scrollTarget.is(":focus") ) {
        return false;
      } else {
        scrollTarget.attr('tabindex','-1');
      };
    });
  }

});
</script>

<?php
get_footer();
