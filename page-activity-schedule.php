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
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full activity-schedule <?php echo $has_banner ?>">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php 
				$custom_page_title = get_field("custom_page_title"); 
				$page_title = ($custom_page_title) ? $custom_page_title : get_the_title();
			?>
			<div class="text-centered-section intro-text-wrap">
				<div class="wrapper">
					<?php the_content(); ?>
				</div>
			</div>
		<?php endwhile;  ?>

		<div class="schedule-activities-info new-layout-v2 full">
      <div class="subhead">
        <div class="date-hours">
          <h2 class="event-date"><?php echo date('l, F j, Y'); ?></h2>
        </div>
      </div>

      <?php
        $schedules = array();  
        $terms = get_terms( [
          'taxonomy'    => 'whitewater-location',
          'post_type'   => 'activity_schedule',
          'hide_empty' => false,
        ]);
        if($terms) {
          foreach($terms as $term) {
            $slug = $term->slug;
            $data = getActivityScheduleToday($slug);
            if($data) {
              $schedules[$slug] = array(
                'location'=>$term->name,
                'schedules'=> $data
              );
            }
          }
        }

        if($schedules) { ?>
        <div class="todaySnapshotInfo">
          <ul class="location-tabs">
            <?php $i=1; foreach ($schedules as $termSlug=>$v) { 
              $is_active = ( $i==1 ) ? ' active':''; 
              $is_selected = ($i==1) ? 'true':'false'; ?>
              
            <?php $i++; } ?>
          </ul> 
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
});
</script>

<?php
get_footer();
