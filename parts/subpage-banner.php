<?php
$wrs_banner_type = get_field('wrs_banner_type');
// videos
$wrs_mobile_video = get_field('wrs_mobile_video');
$wrs_video_banner = get_field('wrs_video_banner');
// images
$heroImage = get_field("full_image");
$mobile_image = get_field('mobile_image');
$mobile_image_fest = get_field('mobile-banner');
$status = get_field('registration_status');
$registerLink = get_field('registrationLink');
$regTarget = get_field('registrationLinkTarget');
$status_custom_message = get_field('status_custom_message');
if( is_single('pond-hockey') ){
	$registerButton = 'Youth Tournament Registration';
} else {
	$registerButton = 'Register';
}

$registerTarget = ( isset($regTarget[0]) && $regTarget[0]=='yes' ) ? '_blank':'_self';
$has_red_tag = false;
$excludePostTypes = exclude_post_types_banner();
if ( is_singular( get_post_type() ) && in_array(get_post_type(),$excludePostTypes) ) {
	$show_slide = false;
	if($status) {
		$has_red_tag = true;
	}
}
// echo '<pre>';
// print_r($mobile_image);
// echo '</pre>';
$eventstatus = get_field("eventstatus");
$eventStatus = ($eventstatus) ? strtoupper($eventstatus) : '';
$heroImageText = get_field("full_image_text");
$single_post_hero = '';

$fieldtrip_banner = get_field("fieldtrip_featured_image");
if($fieldtrip_banner) {
	$heroImage = $fieldtrip_banner;
}

// if (empty($heroImageText)) {
// 	if ($eventStatus) {
// 		echo '<div class="film-event-status"><div class="wrapper">'.$eventStatus.'</div></div>';
// 	}
// }
if ($eventStatus && $eventstatus!='upcoming') {
	echo '<div class="film-event-status"><div class="wrapper">'.$eventStatus.'</div></div>';
}


if( $wrs_banner_type == 'video' ) { 
	ob_start(); ?>
	<?php if( $wrs_mobile_video || $wrs_video_banner ) { ?>
		<div id="banner" class="subpageBanner">
			<div class="slides-wrapper static-banner">
				<ul class="slides">
					<li class="slideItem type-video">
						<div class="iframe-wrapper <?php echo ($wrs_mobile_video || $row['mobile_image'])?'yes-mobile':'no-mobile';?>">
							<video class="desktop" autoPlay loop muted playsinline  poster="<?php echo $placeThumb['url']; ?>">
								<source src="<?php echo $wrs_video_banner;?>" type="video/mp4">
							</video>
							<video class="mobile" autoPlay loop muted playsinline poster="<?php echo $placeThumb['url']; ?>">
								<source src="<?php echo $wrs_mobile_video;?>" type="video/mp4">
							</video>
						</div>
					</li>
				</ul>
			</div>
		</div>
	<?php } 
	$single_post_video = ob_get_contents();
	ob_end_clean();
	?>

<?php } else {

	ob_start(); 
	if($heroImage) { ?>
	<div id="banner" class="subpageBanner">
		<div class="slides-wrapper static-banner">
			<ul class="slides">
				<li class="slideItem type-image">
					<div class="image-wrapper yes-mobile" style="background-image: url('<?php echo $heroImage['url']?>');">
						<img class="desktop " src="<?php echo $heroImage['url']?>" alt="<?php echo $heroImage['title']?>">
						<?php if( $mobile_image ){ ?>
							<img class="mobile " src="<?php echo $mobile_image['url']?>" alt="<?php echo $mobile_image['title']?>">
						<?php } ?>
						<?php if( $mobile_image_fest ){ ?>
							<img class="mobile " src="<?php echo $mobile_image_fest['url']?>" alt="<?php echo $mobile_image_fest['title']?>">
						<?php } ?>
					</div>
					<?php if ($heroImageText) { ?>
					<div class="slideCaption"><div class="text"><?php echo $heroImageText ?></div></div>
					<?php } ?>
				</li>
			</ul>
		</div>
	</div>
	<?php } 
	$single_post_hero = ob_get_contents();
	ob_end_clean();
	?>
<?php } ?>

<?php

if($has_red_tag) { ?>
<div class="hero-wrapper hero-register-button<?php echo ($eventStatus) ? ' has-event-status':''; ?>">
<?php if($status){ ?>

	<?php if( $wrs_banner_type == 'video' ) { 
		echo $single_post_video; 
	} else {
		echo $single_post_hero;
	} ?>

	<?php if ($status=='open') { ?>
		<?php if ($registerButton && $registerLink) { ?>
			<div class="stats open"><a href="<?php echo $registerLink ?>" target="<?php echo $registerTarget ?>" class="registerBtn"><?php echo $registerButton ?></a></div>
		<?php } ?>
	<?php } else if($status=='closed') { ?>
		<!-- <div class="stats closed">SOLD OUT</div> -->
		<?php //if( !is_singular('build-your-own-boat-competition') ) { ?>
			<div class="stats closed"><a href="" class="registerBtn">SOLD OUT</a></div>
		<?php //} ?>
	<?php } else if($status=='custom') { ?>

		<?php if ($status_custom_message) { ?>
		<!-- <div class="stats closed"><?php echo $status_custom_message ?></div> -->
		<div class="stats closed"><a href="" class="registerBtn"><?php echo $status_custom_message ?></a></div>
		<?php } ?>

	<?php } ?>
<?php } ?>
</div>

<?php } else {
	echo $single_post_hero;
} ?>