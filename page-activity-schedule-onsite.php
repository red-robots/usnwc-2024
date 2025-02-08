<!DOCTYPE html>
<html>
<head>

<link rel='stylesheet' id='bellaworks-style-css'  href='<?php bloginfo('template_url'); ?>/style.css' type='text/css' media='all' />
<script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.min.js?ver=3.5.1' id='jquery-js'></script>
<style type="text/css">
	#primary.activity-schedule-onsite {
		margin-top: 0;
		border: 0;
	}
	#primary.activity-schedule-onsite .wrapper {
		max-width: 100%;
		font-size: 36px;
		font-weight: bold;
	}
	#primary.activity-schedule-onsite .wrapper h3 {
		font-weight: bold;
	}
	#primary.activity-schedule-onsite .intro-text-wrap {
		padding-top: 0;
		padding-bottom: 0;
	}
	#primary.activity-schedule-onsite .status-legend {
		padding-top: 10px;
	}
	#primary.activity-schedule-onsite .date-hours {
		margin-top: 0;
	}
	#primary.activity-schedule-onsite .activities {
		/*display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		justify-content: space-around;*/
	}
	#primary.activity-schedule-onsite {

	}
	#primary.activity-schedule-onsite .activity-info {
		/*flex-basis: 45%;*/
		margin-bottom: 50px;
		max-width: 96%;
	}
	#primary.activity-schedule-onsite span.redclosed {
		width: auto;
		padding: 2px 10px;
		background-color: #BA0D30;
		color: #fff;
	}
	.daily-content, .daily-container {
		width: 100%;
		float: left;
		background-color: #fff;
	}
	.daily-container {
		/*padding: 5px;*/
		height: 4000px;
	}
	.schedule-activities-info.new-layout .activities .activity-info ul.list .cell.cell-open:before, .schedule-activities-info.new-layout .activities .activity-info ul.list .cell.cell-closed:before {
		width: 20px;
		height: 20px;
		left: -10px;
		top: 15px;
	}
	.schedule-activities-info.new-layout .activities .activity-info h3.type {
		font-size: 42px;
	}
	.activity-info ul.list li {
		/*  font-family: "Lato", sans-serif; */
		font-family: "Nunito Sans", sans-serif;
		font-weight: 700;
	}
	.activity-info ul.list li .cell {
		font-weight: 700;
	}
	.schedule-activities-info.new-layout .activities .activity-info ul.list .cell.name .cellTxt span.ct {
		color: #000;
	}
	.schedule-activities-info.new-layout .status-legend span {
		font-family: "Lato", sans-serif;
		font-weight: 500;
		font-size: 30px;
		color: #000;
	}
	.schedule-activities-info.new-layout .subhead .event-date {
		font-family: "Lato", sans-serif;
		font-size: 28px;
    	font-weight: 600;
	}
	.schedule-activities-info.new-layout .subhead .pass-hours {
		font-family: "Lato", sans-serif;
    	font-size: 18px;
		font-weight: 400;
	}
	.schedule-activities-info.new-layout .subhead .note {
		font-family: "Lato", sans-serif;
		font-size: 16px;
		font-weight: 400;
	}
	.schedule-activities-info.new-layout .activities .activity-info ul.list .cell.name .cellTxt {
		top: 16px;
	}
	.schedule-activities-info.new-layout .activities .activity-info ul.list .cell.time .cellTxt {
		bottom: -16px;
	}
	.schedule-activities-info.new-layout .activities .activity-info ul.list .cell.cell-open:before,
	.schedule-activities-info.new-layout .activities .activity-info ul.list .cell.cell-closed:before {
		top: 28px;
	}
</style>
</head>
<body>


<?php
/**
 * Template Name: Activity Schedule On Site
 */

//get_header(); 
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
	
global $table_prefix, $wpdb;
$postype = 'activity_schedule';
$dateNow = date('Y-m-d  h:i:s A');
$dateNowMod = date('Ymd', strtotime($dateNow) - 60 * 60 * 5);
$query = "SELECT p.ID, p.post_title, m.meta_value AS event_date FROM ".$table_prefix."posts p, ".$table_prefix."postmeta m 
          WHERE p.ID=m.post_id AND m.meta_key='eventDateSchedule' AND m.meta_value='".$dateNowMod."' 
		  AND p.post_status='publish' AND p.post_type='".$postype."'";
$result = $wpdb->get_results($query);
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full activity-schedule-onsite <?php echo $has_banner ?>">
	<div class="daily-container">
		<div class="daily-content">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php 
				$custom_page_title = get_field("custom_page_title"); 
				$page_title = ($custom_page_title) ? $custom_page_title : get_the_title();
			?>
		<?php endwhile;  ?>

		<div class="schedule-activities-info new-layout full">
			<?php
			//$dateToday = date('l, F jS, Y'); /* example result: SATURDAY, OCTOBER 17TH, 2020 */
			$dateToday = date('l, F j'); /* example resul: SATURDAY, OCTOBER 17 */
			//$post = get_current_activity_schedule($postype);
			if($result) { 
				$post = $result[0];
				$postID = $post->ID;
				$post_title = $post->post_title;
				//$pass_hours = get_field("pass_hours",$postID);
				$pass_hours = '';
				$note = get_field("note",$postID);
				//$scheduled_activities = get_field("scheduled_activities",$postID);
				$passHoursFrom = get_field('pass_hours_from', $postID);
				$passHoursTo = get_field('pass_hours_to', $postID);
				$passHoursArr = array($passHoursFrom,$passHoursTo);
				if( array_filter($passHoursArr) ) {
					$passHoursArr = array_filter($passHoursArr);
					$passHours = implode(' — ', $passHoursArr);
					$pass_hours = strtoupper($passHours);
				}
				$scheduled_activities = '';
				$schedules_column1 = get_field('schedules', $postID);
				$schedules_column2 = get_field('schedules_2', $postID);
				if( $schedules_column1 || $schedules_column2 ) {
					$scheduled_activities = array_merge($schedules_column1, $schedules_column2);
				}
				
				?>
				
				<div class="subhead">
					<div class="date-hours">
						<h2 class="event-date"><?php echo $dateToday ?></h2>
						<?php if ($pass_hours) { ?>
						<div class="pass-hours"><span class="ph">Pass Hours:</span> <?php echo $pass_hours ?></div>	
						<?php } ?>
					</div>

					<?php if ($note) { ?>
					<div class="note"><?php echo $note ?></div>	
					<?php } ?>
				</div>

				<?php //get_template_part("parts/subpage-tabs"); ?>

				<div class="entries full">
					<div class="status-legend">
						<div class="wrapper">
							<span class="open">Activity Open</span>
							<span class="closed">Activity Closed</span>
						</div>
					</div>
					<div class="wrapper">
					<?php if ($scheduled_activities) { ?>
						<div class="activities">
						<?php $i=1; foreach ($scheduled_activities as $a) { 
							$group_name = $a['group_name'];
							$items = $a['items'];
							?>
							<div id="activity<?php echo $i?>" class="activity-info info">
								<?php if($group_name) { ?>
								<h3 class="type"><?php echo $group_name; ?></h3>
								<?php } ?>
								<?php if($items) { ?>
								<ul class="list">
									<?php foreach ($items as $e) {  
									$status = $e['status'];
									$name = $e['name'];
									$start_time = $e['start_time'];
									$end_time = $e['end_time'];
									?>
									<li class="data" data-status="<?php echo $status?>">
										<div class="cell name cell-<?php echo $status?>">
										  <span class="cellTxt"><span class="ct <?php echo $status?>"><?php echo $name ?></span></span>
										</div>
										<div class="cell time">
											<span class="cellTxt">
												<?php if( $status == 'open' ) { ?>
													<?php if($start_time || $end_time) { ?>
														<?php if($start_time) { ?>
														<span class="time-start"><?php echo $start_time?></span>
														<?php } ?>
														<?php if($start_time && $end_time) { ?>
														<span class="dashed">–</span>
														<?php } ?>
														<?php if($end_time) { ?>
														<span class="time-end"><?php echo $end_time?></span>
														<?php } ?>
													<?php } ?>
												<?php } else { ?>
													<span class="redclosed">CLOSED</span>
												<?php } ?>
											</span>
										</div>
									</li>
									<?php } ?>
								</ul>
								<?php } ?>
							</div>
						<?php $i++; } ?>
						</div>
					<?php } ?>
					<span id="timer"></span> s
					</div>	
				</div>

			<?php } else { ?>
				
				<div class="subhead">
					<div class="date-hours">
						<h2 class="event-date"><?php echo $dateToday ?></h2>
						<div class="pass-hours">NO SCHEDULED ACTIVITY TODAY</div>	
					</div>
				</div>
			
			<?php } ?>

		</div>
</div>
</div>
</div><!-- #primary -->


<script type="text/javascript">
  jQuery(document).ready(function ($) {

    run_scroller();

    function scroll_to_bottom_looped(duration,page_height){
      $('html, body').animate({ 
         scrollTop: page_height},
         duration,
         "swing"
      ).promise().then(function(){
        scroll_to_top_looped(duration,page_height);
      });
    }
    function scroll_to_top_looped(duration,page_height){
      $('html, body').animate({ 
         scrollTop: 0},
         duration,
         "swing"
      ).promise().then(function(){
        scroll_to_bottom_looped(duration,page_height);
      });
    }
    function repeat_scroller(duration,page_height,repeats,i){
      if( i < repeats ){
        $('html, body').animate({ 
           scrollTop: page_height},
           duration,
           "swing"
        ).promise().then(function(){
          $('html, body').animate({ 
             scrollTop: 0},
             duration,
             "swing"
          ).promise().then(function(){
            i++;       
            repeat_scroller(duration,page_height,repeats,i);
          });
        });
      }else{
        return false;
      }
    }
        
    function run_scroller() {
      // force window to top of page
      $(this).scrollTop(0);
      // define vars
      let page_height = $(document).height()-$(window).height();
      let duration = 60000;

      // begin the neverending scrollage festival
      scroll_to_bottom_looped(duration,page_height);

      // or, use a set number of repeats
      let repeats = 3;
      let i = 0;
      // repeat_scroller(duration,page_height,repeats,i);
    }

  });
</script>
</body>
</html>
<?php
//get_footer();
