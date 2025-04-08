<?php
$registerLink = get_field('registrationLink');
$regTarget = get_field('registrationLinkTarget');
$has_red_tag = ($registerLink) ? true : false;
$status = get_field('registration_status');
if($status){ ?>
<div class="red-ribbon-top" style="display:none;">
  <?php if ($status=='open') { ?>
    <?php 
    $register_link = '';
    $regTarget = ( get_field('registrationLinkTarget', $post_id) ) ? true : false;
    $registerTarget = ($regTarget) ? '_blank' : '_self';
    if( $registerLink ) {
      if( is_array($registerLink) ) {
        $register_link = ( isset($registerLink['url']) && $registerLink['url'] ) ? $registerLink['url'] : '';
      } else {
        if( filter_var($registerLink, FILTER_VALIDATE_URL) ) {
          $register_link = $registerLink;
        }
      }
    }
    if ($registerLink) { ?>
      <div class="stats open teaser is-register-tag redtag"><a href="<?php echo $register_link; ?>" target="<?php echo $registerTarget ?>" class="registerBtn"><?php echo 'register' ?></a></div>
    <?php } ?>
  <?php } else if($status=='closed') { ?>
    <div class="stats closed teaser redtag"><span class="registerBtn">SOLD OUT</span></div>
  <?php } else if($status=='custom') { ?>

    <?php 
    $status_custom_message = get_field('status_custom_message', $post_id);
    if ( $status_custom_message ) { ?>
    <div class="stats custom-message teaser redtag"><span class="registerBtn"><?php echo $status_custom_message ?></span></div>
    <?php } ?>

  <?php } else if($status=='custom_button') { ?>

    <?php if( $custom_button = get_field('status_custom_button', $post_id) ) { 
      $statBtnName = (isset($custom_button['title']) && $custom_button['title']) ? $custom_button['title'] : '';
      $statBtnUrl = (isset($custom_button['url']) && $custom_button['url']) ? $custom_button['url'] : '';
      $statBtnTarget = (isset($custom_button['target']) && $custom_button['target']) ? $custom_button['target'] : '_self';
      if($statBtnName && $statBtnUrl) { ?>
      <div class="stats custom-button teaser redtag">
        <a href="<?php echo $statBtnUrl ?>" target="<?php echo $statBtnTarget ?>" class="registerBtn customButton"><?php echo $statBtnName ?></a>
      </div>
      <?php } ?>
    <?php } ?>

  <?php } ?>
</div>
<?php } ?>