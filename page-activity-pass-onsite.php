<!DOCTYPE html>
<html>
<head>

<link rel='stylesheet' id='bellaworks-style-css'  href='<?php bloginfo('template_url'); ?>/style.css' type='text/css' media='all' />
<script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.min.js?ver=3.5.1' id='jquery-js'></script>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
	body.onite {
		font-size: 24px;
		font-family: "Nunito Sans", sans-serif;
		background: #fff;
	}
	body.onite .singleActivity,
	body.onite .pass-name,
	body.onite .price .pr {
		font-size: 34px !important;
	}
	body.onite h2.stitle {
		font-size: 44px;
		font-family: "Lato", sans-serif;
	}
	.page-activity-passes .itemrow .activity-name span,
	.page-activity-passes .itemrow .button-group .price,
	.page-activity-passes .itemrow .button-group .wrap {
		background: #fff !important;
	}
	.inner.osap {
		margin-top: 50px;
	}
</style>
</head>
<body class="onite">


<?php
/**
 * Template Name: Activity Pass On Site
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
?>
<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full page-activity-passes <?php echo $has_banner ?>">

	<?php while ( have_posts() ) : the_post(); ?>
		<!-- <div class="intro-text-wrap">
			<div class="wrapper">
				<h1 class="page-title"><span><?php the_title(); ?></span></h1>
				<div class="intro-text"><?php the_content(); ?></div>
			</div>
		</div> -->
	<?php endwhile;  ?>

	<?php //get_template_part("parts/subpage-tabs"); ?>

	<?php
	$id='324';
	$all_access_title = get_field("all_access_title", $id);
	$all_access_feat_image = get_field("all_access_feat_image", $id);
	$all_access_text = get_field("all_access_text", $id);

	$single_access_title = get_field("single_access_title", $id);
	$single_access_feat_image = get_field("single_access_feat_image", $id);
	$single_access_text = get_field("single_access_text", $id);
	$class1 = ( ($all_access_title || $all_access_text) && ($single_access_title || $single_access_text) ) ? 'half':'full';
	?>

	<?php if ( ($all_access_title || $all_access_text) || ($single_access_title || $single_access_text) ) { ?>
	<section class="twoColSection full <?php //echo $class1 ?>">
		<div class="twoColInnerzz">
				
			<?php /* ALL ACCESS PASSES */ ?>
			<?php if ($all_access_title || $all_access_text) { ?>
			<div id="section1" data-section="<?php echo $all_access_title ?>" class="tcol <?php echo ($all_access_feat_image) ? 'hasphoto':'nophoto' ?>">
				<div class="inner osap">
					

					<?php
					$pass_args = array(
						'posts_per_page'	=> -1,
						'post_type'				=> 'pass',
						'post_status'			=> 'publish'
					);
					$all_passes = get_posts($pass_args);
					?>
					
					<div class="info text-center">
						<?php if ($all_access_title) { ?>
							<h2 class="stitle "><?php echo $all_access_title ?></h2>
						<?php } ?>
						<?php if ($all_access_text) { ?>
							<!-- <div class="text"><?php //echo $all_access_text ?></div> -->
						<?php } ?>
						<?php if ($all_passes) { ?>
						<div class="pass-types">
							<?php foreach ($all_passes as $p) { 
								$pid = $p->ID;
								$adult = get_field("adult_price",$pid);
								$young = get_field("young_price",$pid);
								// need ability to hide and showd
								$show = get_field("show_on_activities_page",$pid);
								// echo '<pre>';
								// print_r($show);
								// echo '</pre>';
								$buyButton = get_field("purchase_button",$pid);
								$buttonName = (isset($buyButton['title']) && $buyButton['title']) ? $buyButton['title']:'Purchase Pass';
								$buttonLink = (isset($buyButton['url']) && $buyButton['url']) ? $buyButton['url']:'';
								$buttonTarget = (isset($buyButton['target']) && $buyButton['target']) ? $buyButton['target']:'_self';

								if( $show == 'show' ) {
								?>
								<div class="type">
									<div class="pass-name"><?php echo $p->post_title ?></div>
									<div class="price">
										<?php if ($adult) { ?>
										<div class="adult-price pr">Adult &ndash; <?php echo $adult ?></div>	
										<?php } ?>
										<?php if ($young) { ?>
										<div class="young-price pr">Youth &ndash; <?php echo $young ?></div>	
										<?php } ?>

										
									</div>
								</div>
								<?php } ?>
							<?php } ?>
						</div>	
						<?php } ?>
					</div>
				</div>
			</div>
			<?php } ?>


			<?php /* SINGLE ACTIVITY PASSES */ ?>
			<?php if ($single_access_title || $single_access_text) { ?>
			<div id="section2" data-section="<?php echo $single_access_title ?>" class="tcol <?php echo ($single_access_feat_image) ? 'hasphoto':'nophoto' ?>">
				<div class="inner">
					

					<?php
					$single_activities = get_single_activity_passes_list('default'); /* see inc/func-activity-passes.php */
					?>
					<div class="info text-center">
						
						<?php if ($single_access_title) { ?>
							<h2 class="stitle"><?php echo $single_access_title ?></h2>
						<?php } ?>
						<?php if ($single_access_text) { ?>
							<!-- <div class="text"><?php echo $single_access_text ?></div> -->
						<?php } ?>

						<?php if ($single_activities) { ?>
						<div class="single-activities">
							<?php foreach ($single_activities as $e) { 
								$s_custom = ( isset($e['custom']) && $e['custom'] ) ? $e['custom'] : '';
								$s_price = ( isset($e['price']) && $e['price'] ) ? $e['price'] : '';
								$s_name = ( isset($e['name']) && $e['name'] ) ? $e['name'] : '';
								$s_id = ( isset($e['id']) && $e['id'] ) ? $e['id'] : '';
								$buy = ( isset($e['button']) && $e['button'] ) ? $e['button'] : '';
								$buy_btn = ( isset($buy['title']) && $buy['title'] ) ? $buy['title']:'Purchase';
								$buy_link = ( isset($buy['url']) && $buy['url'] ) ? $buy['url']:'';
								$buy_target = ( isset($buy['target']) && $buy['target'] ) ? $buy['target']:'_self';
								$title_slug = sanitize_title($s_name);
								$item_class = ($s_custom) ? 'other-activity':'singleActivity';
								$item_id = ($s_custom) ? "custom-" . $title_slug : 'post-activity-' . $s_id;
								?>
								<div id="<?php echo $item_id?>" class="itemrow <?php echo $item_class?>">
									<span class="activity-name"><span><?php echo $s_name ?></span></span>
									<?php if ($s_price || $buy_btn) { ?>
										<span class="button-group">
											<span class="wrap">
												<?php if ($s_price) { ?>
												<span class="price"><?php echo $s_price ?></span>	
												<?php } ?>
												<?php if ($buy_btn && $buy_link) { ?>
												<a href="<?php echo $buy_link ?>" target="<?php echo $buy_target ?>" class="btn-sm xs"><span><?php echo $buy_btn ?></span></a>
												<?php } ?>
											</span>
										</span>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
						<?php } ?>

					</div>
					
				</div>
			</div>
			<?php } ?>

		</div>
	</section>
	<?php } ?>

</div><!-- #primary -->


<script type="text/javascript">
// jQuery(document).ready(function ($) {
// 	function scroll_to_bottom_looped(duration,page_height){
// 		$('html, body').animate({ 
// 		   scrollTop: page_height},
// 		   duration,
// 		   "swing"
// 		).promise().then(function(){
// 		  scroll_to_top_looped(duration,page_height);
// 		});
// 	}
// 	function scroll_to_top_looped(duration,page_height){
// 		$('html, body').animate({ 
// 		   scrollTop: 0},
// 		   duration,
// 		   "swing"
// 		).promise().then(function(){
// 		  scroll_to_bottom_looped(duration,page_height);
// 		});
// 	}
// 	function repeat_scroller(duration,page_height,repeats,i){
// 		if( i < repeats ){
// 			$('html, body').animate({ 
// 			   scrollTop: page_height},
// 			   duration,
// 			   "swing"
// 			).promise().then(function(){
// 				$('html, body').animate({ 
// 				   scrollTop: 0},
// 				   duration,
// 				   "swing"
// 				).promise().then(function(){
// 				  i++;			 
// 				  repeat_scroller(duration,page_height,repeats,i);
// 				});
// 			});
// 		}else{
// 			return false;
// 		}
// 	}

// 	jQuery(document).ready(function ($) {	
// 		// force window to top of page
// 		$(this).scrollTop(0);
// 		// define vars
// 		let page_height = $(document).height()-$(window).height();
// 		let duration = 60000;

// 		// begin the neverending scrollage festival
// 		scroll_to_bottom_looped(duration,page_height);

// 		// or, use a set number of repeats
// 		let repeats = 3;
// 		let i = 0;
// 		// repeat_scroller(duration,page_height,repeats,i);
// 	});


//   });// END

</script>
</body>
</html>
<?php
//get_footer();
