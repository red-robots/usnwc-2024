<?php  
  $infocolumns = get_field('infocolumns');
  $image_cards = get_field('image_cards');
?>

<section class="section-snapshop snapshop-wrapper">
  <div class="wrapper">
    <div class="titlediv">
      <h3>Today's Snapshot</h3>
      <div class="dateToday"><?php echo date('l, F d') ?></div>
    </div>
  </div>

  <?php if ( $infocolumns ) { ?>
  <div class="todaySnapshotInfo">
    <div class="info-wrapper">
      <div class="flexwrap">
        <?php foreach ($infocolumns as $c) { 
          $v_title = $c['title'];
          $v_text = $c['text'];
          $v_link = $c['link'];
          $nLink = ( isset($v_link['url']) && $v_link['url'] ) ? $v_link['url'] : '';
          $nTitle = ( isset($v_link['title']) && $v_link['title'] ) ? $v_link['title'] : '';
          $nTarget = ( isset($v_link['target']) && $v_link['target'] ) ? $v_link['target'] : '_self';
          $v_is_status = $c['is_status'];
          $v_status = $c['status'];
          $status_value = ( isset($v_status['value']) ) ? $v_status['value'] : '';
          $status_label = ( isset($v_status['label']) ) ? $v_status['label'] : '';
          if($v_title) { ?>
          <div class="infocol<?php echo ($v_is_status) ? ' is-status ' . $status_value:'' ?>">
            <div class="inner">
              <div class="title"><?php echo $v_title ?></div>
              <?php if ($v_is_status) { ?>
                <div class="status <?php echo $status_value ?>"></div>
                <div class="status-text"><?php echo ucwords($status_value) ?></div>
              <?php } else { ?>
                <div class="text"><?php echo $v_text ?></div>
              <?php } ?>

              <?php if ($nLink && $nTitle) { ?>
              <div class="link">
                <a href="<?php echo $nLink ?>" target="<?php echo $nTarget ?>"><?php echo $nTitle ?> <i class="fa-light fa-angle-right"></i></a>
              </div>
              <?php } ?>
            </div>
          </div>  
          <?php } ?>
        <?php } ?>
      </div>  
    </div>  
  </div>  
  <?php } ?>

</section>

<?php if ($image_cards) { ?>
<section class="section-image-cards-page">
  <div class="wrapper">
    <div class="flexwrap">
    <?php foreach ($image_cards as $card) {
      $c_image = $card['image'];
      $c_link = $card['link'];
      $cUrl = ( isset($c_link['url']) && $c_link['url'] ) ? $c_link['url'] : '';
      $cTitle = ( isset($c_link['title']) && $c_link['title'] ) ? $c_link['title'] : '';
      $cTarget = ( isset($c_link['target']) && $c_link['target'] ) ? $c_link['target'] : '_self';
      $hasLink = ($cUrl && $cTitle) ? 'hasLink':'noLink';
      if($c_image) { ?>
      <div class="flexbox">
        <figure class="<?php echo $hasLink ?>">
          <?php if ($cUrl && $cTitle) { ?>
            <a href="<?php echo $cUrl ?>" target="<?php echo $cTarget ?>" class="image-link">
              <span class="image-title"><?php echo $cTitle ?></span>
              <img src="<?php echo $c_image['url'] ?>" alt="<?php echo $c_image['title'] ?>" />
            </a>
          <?php } else { ?>
            <?php if ($cTitle) { ?>
            <span class="image-title"><?php echo $cTitle ?></span> 
            <?php } ?>
            <img src="<?php echo $c_image['url'] ?>" alt="<?php echo $c_image['title'] ?>" />
          <?php } ?>
        </figure>
      </div>
      <?php } ?>
    <?php } ?>
    </div>
  </div>
</section>
<?php } ?>