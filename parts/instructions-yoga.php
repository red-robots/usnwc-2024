<?php 
$placeholder = THEMEURI . 'images/rectangle.png';
$portrait = THEMEURI . 'images/portrait.png';
$square = THEMEURI . 'images/square.png';
$options[] = array('Price',get_field("price"));
//$options[] = array('Ages',get_field("ages"));
$options[] = array('Duration',get_field("duration"));
//$options[] = array('Ratio',get_field("ratio"));
$count_options = count($options);
?>

<section class="section-price-ages full">
	<div class="flexwrap fourCols count-options-<?php echo $count_options?>">
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
?>
<?php if ($registerLink) { ?>
<section id="section-registration" class="section-content section-full-button">
	<a href="<?php echo $registerLink ?>" target="<?php echo $regiserTarget ?>" class="red-button-full stitle"><span><?php echo $registerBtn ?></span></a>
</section>
<?php } ?>


<?php if($program = get_field("program_text")) { ?>
<section id="program-info" class="section-content text-center">
	<div class="wrapper narrow">
		<?php echo $program ?>
	</div>
</section>
<?php } ?>


<?php 
$important_note = get_field("important_note"); 
$schedule_items = get_field("schedule_items"); 
$sch_anchor = get_field("schedule_anchor");
$sch_anchor_sani = sanitize_title_with_dashes($sch_anchor);
$schedule_title = get_field("schedule_title");
?>
<?php if ($schedule_items) { ?>
<section id="section-<?php echo strtolower($sch_anchor_sani); ?>" data-section="<?php echo $sch_anchor; ?>" class="section-schedule section-content">
	<div class="wrapper">
		<div class="shead-icon text-center">
			<div class="icon"><span class="ci-menu"></span></div>
			<h2 class="stitle"><?php if($schedule_title){ echo $schedule_title; }else{echo 'SCHEDULE';} ?></h2>
		</div>
		<div class="schedules-list">
			<ul class="items">
				<?php foreach ($schedule_items as $s) { ?>
					<li class="item">
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
							<div class="event"><?php echo $s['event'] ?></div>
						<?php } ?>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>	
</section>
<?php } ?>

<?php if ($important_note) { ?>
<section id="schedule-bottomtext" class="section-content gray">
	<div class="wrapper narrow text-center"><?php echo $important_note ?></div>	
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

<?php 
$instruction_title = get_field("instruction_title");
$instruction_text = get_field("instruction_text");
$instruction_anchor = get_field("instruction_anchor");
$instruction_anchor_sani = sanitize_title_with_dashes($instruction_anchor);
if( $instruction_text ) { ?>
<section id="instruction-<?php echo strtolower($instruction_anchor_sani); ?>"  data-section="<?php echo $instruction_anchor ?>" class="section-content instruction-team">
	<div class="wrapper narrow text-center">
		<div class="shead-icon text-center">
			<div class="icon"><span class="ci-team-hat"></span></div>
			<?php if ($instruction_title) { ?>
			<h2 class="stitle"><?php echo $instruction_title ?></h2>
			<?php } ?>
		</div>
		
		<div class="text-wrap">
			<?php echo $instruction_text ?>
		</div>
	</div>
</section>
<?php } ?>


<?php 
$photo = get_field("instructor_photo");
$instructor_title = get_field("instructor_title");
$instructor_text = get_field("instructor_text");
$sclass = ($photo && $instructor_text) ? 'half':'full';
if( $photo ||  $instructor_text ) { ?>
<section id="about-instructor" class="section-content <?php echo $sclass ?>">
	<div class="flexwrap">
		<?php if ($photo) { ?>
		<div class="photo">
			<div class="image" style="background-image:url('<?php echo $photo['url'] ?>')">
				<img src="<?php echo $square ?>" alt="" aria-hidden="true" class="helper">
			</div>
		</div>	
		<?php } ?>
		<?php if ($instructor_text) { ?>
		<div class="text">
			<div class="wrap">
				<?php if ($instructor_title) { ?>
					<h2 class="stitle"><?php echo $instructor_title ?></h2>
				<?php } ?>
				<div class="info"><?php echo $instructor_text ?></div>
			</div>
		</div>	
		<?php } ?>
	</div>
</section>
<?php } ?>


<?php 
/*
	Second Instructor

*/
$photo_two = get_field("instructor_photo_two");
$instructor_title_two = get_field("instructor_title_two");
$instructor_text_two = get_field("instructor_text_two");
$sclass = ($photo_two && $instructor_text_two) ? 'half':'full';
if( $photo_two ||  $instructor_text_two ) { ?>
<section id="about-instructor" class="section-content <?php echo $sclass ?>">
	<div class="flexwrap">
		<?php if ($instructor_text_two) { ?>
		<div class="text">
			<div class="wrap">
				<?php if ($instructor_title) { ?>
					<h2 class="stitle textalign-right"><?php echo $instructor_title_two ?></h2>
				<?php } ?>
				<div class="info textalign-right"><?php echo $instructor_text_two ?></div>
			</div>
		</div>	
		<?php } ?>
		<?php if ($photo_two) { ?>
		<div class="photo">
			<div class="image" style="background-image:url('<?php echo $photo_two['url'] ?>')">
				<img src="<?php echo $square ?>" alt="" aria-hidden="true" class="helper">
			</div>
		</div>	
		<?php } ?>
	</div>
</section>
<?php } ?>


