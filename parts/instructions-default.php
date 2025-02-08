<?php 
$placeholder = THEMEURI . 'images/rectangle.png';
$options[] = array('Price',get_field("price"));
$options[] = array('Ages',get_field("ages"));
$options[] = array('Duration',get_field("duration"));
$options[] = array('Ratio',get_field("ratio"));
?>

<section class="section-price-ages full">
	<div class="flexwrap fourCols">
		<?php foreach ($options as $opt) { 
		$class = sanitize_title($opt[0]); ?>
		<div class="info <?php echo $class ?>">
			<div class="wrap">
				<div class="label"><?php echo $opt[0] ?></div>
				<div class="val"><?php echo $opt[1] ?></div>
			</div>
		</div>
		<?php } ?>
	</div>
</section>
<?php
	$register = get_field("registration_link");
	$registerBtn = ( isset($register['title']) && $register['title'] ) ? $register['title'] : 'Register';
	$registerLink = ( isset($register['url']) && $register['url'] ) ? $register['url'] : '';
	$regiserTarget = ( isset($register['target']) && $register['target'] ) ? $register['target'] : '_self';

	$passport = get_field('passport_btn');
	$passLabel = get_field('passport_label');
	$idArray = array('40','41','42','43','53','54','55','56','57','58','154','152','153','412');
	if( $passport == 'all' ) {
		$pp = 'data-accesso-launch';
	} elseif(in_array($passport, $idArray )) {
		$pp = 'data-accesso-package="'.$passport.'"';
	} else {
		$pp = 'data-accesso-keyword="'.$passport.'"';
	}
?>
<?php if($passport) { ?>
	<!-- <section id="section-registration" class="section-content section-full-button">
		<a <?php if($passport){echo $pp;} ?> href="#" target="<?php echo $buttonTarget ?>" class="red-button-full stitle"><span><?php echo $registerBtn ?></span></a>
	</section> -->
<?php } else { ?>
	<?php if ($registerLink) { ?>
	<section id="section-registration" class="section-content section-full-button">
		<a href="<?php echo $registerLink ?>" target="<?php echo $regiserTarget ?>" class="red-button-full stitle"><span><?php echo $registerBtn ?></span></a>
	</section>
	<?php } ?>
<?php } ?>

<?php $schedule_items = get_field("schedule_items"); 
		$sch_anchor = get_field("schedule_anchor");
		$sch_anchor_sani = sanitize_title_with_dashes($sch_anchor);
		$schedule_title = get_field("schedule_title");
 if ($schedule_items) { ?>
<section id="section-<?php echo strtolower($sch_anchor_sani); ?>" data-section="<?php echo $sch_anchor; ?>" class="section-schedule section-content">
	<div class="wrapper">
		<div class="shead-icon text-center">
			<div class="icon"><span class="ci-menu"></span></div>
			<h2 class="stitle">
				<?php if($schedule_title){ echo $schedule_title; }else{echo 'SCHEDULE';} ?>
			</h2>
		</div>
		<div class="schedules-list">
			<ul class="items">
				<?php foreach ($schedule_items as $s) { 
					$has_full_info = ($s['dates'] && $s['time']) ? 'has-dash':'no-dash';
					?>
					<li class="item <?php echo $has_full_info ?>">
						<?php if ($s['dates'] && $s['time']) { ?>
							<div class="dates"><span><?php echo $s['dates'] ?></span></div>
							<div class="time"><?php echo $s['time'] ?></div>
							<div class="event"><?php echo $s['event'] ?></div>
						<?php } else { ?>
							<?php if ( empty($s['time']) && $s['dates'] ) { ?>
							<div class="time"><?php echo $s['dates'] ?></div>
							<?php } else if ( empty($s['dates']) && $s['time'] ) { ?>
							<div class="time"><?php echo $s['time'] ?></div>
							<?php } ?>
							<?php if ($s['event']) { ?>
								<div class="event"><?php echo $s['event'] ?></div>
							<?php } ?>
						<?php } ?>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>	
</section>
<?php } ?>

<?php $gal_anchor = get_field("gallery_anchor");
$gal_anchor_sani = sanitize_title_with_dashes($gal_anchor);
if( $galleries = get_field("gallery") ) { ?>
<section id="section-<?php echo strtolower($gal_anchor_sani); ?>" data-section="<?php echo $gal_anchor; ?>" class="section-content">
	<div id="carousel-images">
		<div class="loop owl-carousel owl-theme">
		<?php foreach ($galleries as $g) { ?>
			<div class="item">
				<div class="image" style="background-image:url('<?php echo $g['url']?>')">
					<img src="<?php echo $placeholder ?>" alt="" aria-hidden="true" />
				</div>
			</div>
		<?php } ?>
		</div>
	</div>
</section>
<?php } ?>


<?php $info_anchor = get_field("info_anchor");
$info_anchor_sani = sanitize_title_with_dashes($info_anchor);
$info_title = get_field("info_title");
$info_extra_copy = get_field("info_extra_copy");
if( $information = get_field("information_content") ) { ?>
<section id="section-<?php echo strtolower($info_anchor_sani); ?>" data-section="<?php echo $info_anchor; ?>" class="section-content gray information-content">
	<div class="wrapper">
		<div class="shead-icon text-center">
			<div class="icon"><span class="ci-info"></span></div>
			<h2 class="stitle">
				<?php if($info_title){ echo $info_title; }else{ echo 'Information'; } ?>
			</h2>
		</div>
		<?php if( $info_extra_copy ) { ?>
			<div class="text-wrap text-center extrapad">
				<?php echo $info_extra_copy; ?>
			</div>
		<?php } ?>
		<div class="text-wrap text-center">
			<?php foreach ($information as $e) { ?>
			<div class="text">
				<?php if ($e['title']) { ?>
					<div class="t1"><?php echo $e['title'] ?></div>
				<?php } ?>
				<?php if ($e['text']) { ?>
					<div class="t2"><?php echo $e['text'] ?></div>
				<?php } ?>
			</div>	
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>




