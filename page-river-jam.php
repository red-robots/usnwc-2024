<?php
/**
 * Template Name: River Jam
 */

get_header(); 
$blank_image = THEMEURI . "images/square.png";
$square = THEMEURI . "images/square.png";
$rectangle = THEMEURI . "images/rectangle-lg.png";

?>


<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full riverjam">
	<?php while ( have_posts() ) : the_post(); ?>
		<section class="text-centered-section">
			<div class="wrapper text-center">
				<div class="page-header">
					<h1 class="page-title"><?php the_title(); ?></h1>
				</div>
				<?php if ( get_the_content() ) { ?>
				<div class="text"><?php the_content(); ?></div>
				<?php } ?>
			</div>
		</section>
	<?php endwhile;  ?>

	<?php get_template_part("parts/subpage-tabs"); ?>

	<?php /* UPCOMING */ ?>
	<?php if( $upcoming = get_upcoming_bands() ) { ?>
	<section id="upcoming" data-section="Upcoming" class="section-content">
		<div class="redDiv text-center">
			<h2 class="stitle">Upcoming</h2>
		</div>
		<div class="upcoming-posts">
			<div class="flexwrap">
				<?php 
				$noInclude = array();
				
				
				foreach ($upcoming as $row) { 
					$pid = $row->ID;
					$noInclude[] = $pid;
					// echo $pid;
					$title = $row->post_title;
					$start_date = ( isset($row->start_date) && $row->start_date ) ? $row->start_date : '';
          $end_date = get_field('end_date', $pid);

          $startD = ($start_date) ? date('m-d-Y', strtotime($start_date)) : '';
          $endD = ($end_date) ? date('m-d-Y', strtotime($end_date)) : '';
          $event_dates = array($startD, $endD);
          $event_dates_display='';
          if( $datesArr = array_filter($event_dates) ) {
            $event_dates_display = implode(', ', $datesArr);
          }

					// comment out the line below to hide schedule
					$start_day = ($start_date) ? date('l',strtotime($start_date)) : '';
					$image = get_field("thumbnail_image",$pid);
					$helper = THEMEURI . "images/rectangle-lg.png";
					$has_image = ($image) ? 'has-image':'no-image';
					$style = ($image) ? ' style="background-image:url('.$image['url'].')"':'';
					$start_date_format = '';
					if($start_date) {
						$c_month = date('M',strtotime($start_date));
						//$period = ($c_month!='MAY') ? '. ':'';
            $period = ' ';
						$start_date_format = date('F',strtotime($start_date)) . $period . date('j',strtotime($start_date));
					}
					$short_description = get_field("short_description",$pid);
					$is_festival = get_field("is_festival",$pid);
					// $schedule = get_field("schedule_repeater",$pid);
					// $schedule_title = get_field("schedule_title",$pid);

					$schedules = array();
					$schedule_title = get_field("rj_heading","option");	
					$showRelated = get_field("show_related","option");
					$scheduleItems = get_field("rj_schedules","option");
					$xstartDay = ($start_day) ? strtolower( preg_replace('/\s+/', '', $start_day) ) : '';
					if($scheduleItems) {
						foreach($scheduleItems as $e) {
							// echo '<pre>';
							// print_r($e);
							// echo '</pre>';
							if( $e['show_related'] == 'show' ) {
								$e_day = $e['day'];
								$e_schedule = $e['schedule'];
								$e_day = ($e_day) ? strtolower( preg_replace('/\s+/', '', $e_day) ) : '';
								if($e_day && ($e_day==$xstartDay) ) {
									$schedules = $e_schedule;
								}
							}
						}
					}

          $event_date = array($start_day, $start_date_format);
          $event_date_format = '';
          if( array_filter($event_date) ) {
            $event_date_format = implode(",", $event_date);
          }
          $pagelink = get_permalink($pid);
				?>
				<div data-postid='<?php echo $pid ?>' data-startdate="<?php echo $start_date ?>" data-enddate="<?php echo $end_date ?>" data-title="<?php echo $start_day ?>" class="entry <?php echo $has_image ?>">
					<div class="inside">
						<div class="titlediv">
              <p class="event-date"><?php echo $event_date_format ?></p>
						</div>
						<div class="photo <?php echo $has_image ?>"<?php echo $style ?>>
							<img src="<?php echo $helper ?>" alt="" aria-hidden="true" class="helper">
						</div>
            <h3 class="title"><?php echo $title ?></h3>
            <div class="button">
              <a href="javascript:void(0)" data-url="<?php echo $pagelink ?>" data-action="ajaxGetPageData" data-id="<?php echo $pid ?>" class="btn-sm xs popup-details-btn popdata button-pill"><span>See Details</span></a>
            </div>
						<?php if ($short_description) { ?>
						<div class="description text-center js-blocks">
							<div class="text"><?php echo $short_description ?></div>
						</div>
						<?php } ?>

						<?php /* SCHEDULE */ 
								// echo '<pre>';
								// print_r($showRelated);
								// echo '</pre>';

						?>
						<?php if ( $schedules && $is_festival !== 'yes' ) { ?>
						<div class="schedule schedules-list">
							
							<ul class="items">
							<?php $ctr=1; foreach ($schedules as $s) { 
								$time = $s['time'];
								if($time) {
									$altText = ( isset($s['alt_text']) && $s['alt_text'] ) ? $s['alt_text']:'';
									$is_pop_up = ( isset($s['popup_info'][0]) && $s['popup_info'][0]=='yes' ) ? true : false;
									$act = ( isset($s['program']) && $s['program'] ) ? $s['program']:'';
									$activityName = '';
									$pageLink = '';
									$pid = '';
									if($act) {
										$pid = $act->ID;
										$activityName = $act->post_title;
										$pageLink = get_permalink($id);
										$altText = ($altText) ? '('.$altText.')' : '';
									}
									?>
									<li class="item timerow-<?php echo $ctr?><?php echo ($is_pop_up) ? ' has-link':'' ?>">
										<div class="event">
											<?php if ($is_pop_up) { ?>
												<?php if ($act) { ?>
                          <span class="namewrap">
                            <span class="actname"><b><?php echo $activityName ?></b></span>  
  													<a href="#" data-url="<?php echo $pageLink ?>" data-action="ajaxGetPageData" data-id="<?php echo $pid ?>" class="actnamelink popdata">Learn More</a>	
                          </span>
												<?php } ?>
											<?php } else { ?>
                        <span class="namewrap">
												  <span class="actname"><b><?php echo $activityName ?></b></span>	
                        </span>
											<?php } ?>

											<?php if ($altText) { ?>
                        <span class="namewrap">
											   <span class="alttext actname"><b><?php echo $altText ?></b></span>
                        </span>
											<?php } ?>
										</div>
                    <div class="time"><span><?php echo $time ?></span></div>
									</li>
								<?php $ctr++; } ?>
							<?php } ?>
							</ul>
						</div>
						<?php } ?>

					</div>
				</div>	
				<?php } ?>
			</div>
		</div>
	</section>

  <?php  
    $sched = get_field('full_schedule_link');
    $s_url = (isset($sched['url']) && $sched['url'] ) ? $sched['url'] : '';
    $s_title = (isset($sched['title']) && $sched['title'] ) ? $sched['title'] : '';
    $s_target = (isset($sched['target']) && $sched['target'] ) ? $sched['target'] : '_blank';
    ?>
    <?php if ($s_url && $s_title) { ?>
    <div class="fullwidth-wrapper">
      <div class="buttondiv align-middle-button">
        <a href="<?php echo $s_url ?>" target="<?php echo $s_target ?>" class="button-rounded"><?php echo $s_title ?></a>
      </div>
    </div>
    <?php } ?>
  <?php } ?>

	<?php /* UPCOMING BANDS BY DATE */ ?>
	<?php 
	// get_template_part("parts/filter-river-jam"); 
	// include this way to pass variables. sending post id's from above to not include on the next query
	//include(locate_template('parts/filter-river-jam.php'));
	?>


	<?php  
	/* PROGRAMS */
	// $args = array(
	// 	'post_type'				=> 'jam-programs',
	// 	'posts_per_page'	=> -1,
	// 	'post_status'			=> 'publish'
	// );
	// $programs = new WP_Query($args);
	//if( $programs->have_posts() ) { 
	$programs = get_field("rj_programming","option");
	// echo '<pre>';
	// print_r($programs);
	// echo '</pre>';
	if($programs) { ?>	
	<section id="riverjam-programs" data-section="Programs" class="section-content menu-sections">
		<div class="wrapper">
			<div class="shead-icon text-center">
				<h2 class="stitle">PROGRAMS</h2>
			</div>
		</div>
		<div class="columns-2 text-and-images">
			<?php $i=1; foreach($programs as $p) {
				$xid = $p->ID;
				$slides = get_field("featured_images",$xid);
				$boxClass = ( $slides ) ? 'half':'full'; 
				$colClass = ($i % 2) ? ' odd':' even';
				//$excerpt = ( get_the_content($xid) ) ? shortenText( strip_tags(get_the_content($xid)),300,' ','...' ) : '';
				//$programText = ($p->post_content) ? shortenText( strip_tags($p->post_content),250,' ','...' ) : '';
				$programText = ($p->post_content) ? strip_shortcodes($p->post_content) : '';
				$programText = ($programText) ? shortenText( strip_tags($programText),280,' ','...' ) : '';
				$title = $p->post_title;
				$pagelink = get_permalink($xid);
				$helper = THEMEURI . 'images/rectangle-narrow.png';
				$excerpt = get_field("short_description",$xid);
				$program_description = '';
				if($excerpt) {
					$program_description = $excerpt;
				} else {
					$program_description = $programText;
				}
				?>
				<div id="section<?php echo $i?>" class="mscol <?php echo $boxClass.$colClass ?>">
					<div class="textcol">
						<div class="inside">
							<div class="info">
								<h3 class="mstitle"><?php echo $title; ?></h3>
								<?php if ($program_description) { ?>
								<div class="textwrap"><?php echo $program_description; ?></div>
								<div class="buttondiv">
									<a href="#" data-url="<?php echo $pagelink; ?>" data-action="ajaxGetPageData" data-id="<?php echo $xid ?>" class="btn-sm xs popdata"><span>See Details</span></a>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>

					<?php if ( $slides ) { ?>
					<div class="gallerycol">
						<div class="flexslider">
							<ul class="slides">
								<?php foreach ($slides as $s) { ?>
									<li class="slide-item" style="background-image:url('<?php echo $s['url']?>')">
										<img src="<?php echo $helper ?>" alt="" aria-hidden="true" class="placeholder">
										<img src="<?php echo $s['url'] ?>" alt="<?php echo $s['title'] ?>" class="actual-image" />
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>	
					<?php } ?>
				</div>
			<?php  $i++; } ?>
		</div>
	</section>
	<?php } ?>

	<?php
	/* FAQs */
	$customFAQTitle = 'FAQ';
	$customFAQClass = 'custom-class-faq graybg';
	include( locate_template('parts/content-faqs.php') );
	include( locate_template('inc/faqs.php') );
	?>

</div><!-- #primary -->

<?php include( locate_template('parts/popup-river-jam.php') ); ?>
<script type="text/javascript">
jQuery(document).ready(function($){
	// $('#gallery').flexslider({
 //    animation: "slide"
 //  });

  $(document).on('facetwp-refresh', function() {
    var start = $('input.flatpickr-alt[placeholder="Start Date"]').val();
    var end = $('input.flatpickr-alt[placeholder="End Date"]').val();
    var pageURL = '<?php echo get_permalink();?>?' + FWP.build_query_string();
    if(start || end) {
	    $("#upcoming-bands-by-date").load(pageURL + " #entries-result",function(){
	    	$("#loaderDiv").show();
	    	setTimeout(function(){
	    		$("#loaderDiv").hide();
	    	},500);
	    });
	  }
 	});

 	// $(document).on('click','#resetFilter',function(e) {
  //   e.preventDefault();
  //   var pageURL = $(this).attr("href");
  //   $("#upcoming-bands-by-date").load(pageURL + " #entries-result",function(){
  //   	history.pushState('',document.title,pageURL);
  //   });
 	// });	
});
</script>
<?php
include( locate_template('inc/pagetabs-script.php') );
get_footer();
?>

