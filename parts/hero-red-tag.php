<?php
$postid = get_the_ID();
$status = get_field('registration_status');
$registerLink = get_field('registrationLink');
$regTarget = get_field('registrationLinkTarget');
$status_custom_message = get_field('status_custom_message');
$registerButton = 'Register';
$registerTarget = ( isset($regTarget[0]) && $regTarget[0]=='yes' ) ? '_blank':'_self'; 
?>
<div class="hero-wrapper hero-register-button" data-tag-status="<?php echo $status ?>">
<?php if($status){ ?>
	<?php get_template_part("parts/subpage-banner"); ?>
	<?php if ($status=='open') { ?>
		<?php if ($registerButton && $registerLink) { ?>
			<div class="stats open"><a href="<?php echo $registerLink ?>" target="<?php echo $registerTarget ?>" class="registerBtn"><?php echo $registerButton ?></a></div>
		<?php } ?>
	<?php } else if($status=='closed') { ?>
		<div class="stats closed">SOLD OUT</div>
	<?php } else if($status=='custom') { ?>

		<?php if ($status_custom_message) { ?>
		<div class="stats closed"><?php echo $status_custom_message ?></div>
		<?php } ?>

	<?php } else if($status=='custom_button') {  ?>

    <?php if( $custom_button = get_field('status_custom_button', $post_id) ) { 
      $statBtnName = (isset($custom_button['title']) && $custom_button['title']) ? $custom_button['title'] : '';
      $statBtnUrl = (isset($custom_button['url']) && $custom_button['url']) ? $custom_button['url'] : '';
      $statBtnTarget = (isset($custom_button['target']) && $custom_button['target']) ? $custom_button['target'] : '_self';
      if($statBtnName && $statBtnUrl) { ?>
      <div class="stats custom-button teaser">
        <a href="<?php echo $statBtnUrl ?>" target="<?php echo $statBtnTarget ?>" class="registerBtn customButton"><?php echo $statBtnName ?></a>
      </div>
      <?php } ?>
    <?php } ?>

  <?php } ?>

<?php } ?>
</div>