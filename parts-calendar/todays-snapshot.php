<?php  
  $wwlocations = get_field('whitewaterLocations','option');
?>

<section class="snapshop-wrapper">
  <div class="wrapper">
    <div class="titlediv">
      <h3>Today's Snapshot</h3>
      <div class="dateToday"><?php echo date('l, F d') ?></div>
    </div>
  </div>


  <?php if ( $wwlocations ) { ?>
  <div class="todaySnapshotInfo">
      <ul class="location-tabs">
      <?php $i=1; foreach ($wwlocations as $w) { 
        $name = $w['name'];
        $location = $w['location'];
        $infocolumns = $w['infocolumns'];
        $is_active = ($i==1) ? ' active':'';
        $is_selected = ($i==1) ? 'true':'false';
        if($name) { ?>
          <li class="tab<?php echo $is_active ?>">
            <button role="tab" aria-selected="<?php echo $is_selected ?>" aria-controls="tabpanel-<?php echo sanitize_title($name) ?>" id="tab-<?php echo sanitize_title($name) ?>">
              <span class="wname"><?php echo $name ?></span>
              <?php if ($location) { ?>
              <span class="wloc"><?php echo $location ?></span>
              <?php } ?>
            </button>
          </li>
        <?php } ?>
      <?php $i++; } ?>
      </ul> 

      <?php $j=1; foreach ($wwlocations as $w) { 
        $name = $w['name'];
        $location = $w['location'];
        $infocolumns = $w['infocolumns'];
        $is_active = ($j==1) ? ' active':'';
        $is_selected = ($j==1) ? 'true':'false';
        $is_display = ($j==1) ? 'flex':'none';
        if($name) { ?>
          <?php if ($infocolumns) { ?>
          <button class="mobile-tab-heading<?php echo $is_active ?>" aria-expanded="<?php echo $is_selected ?>" aria-controls="tabpanel-<?php echo sanitize_title($name) ?>">
            <span class="wname"><?php echo $name ?></span>
            <?php if ($location) { ?>
            <span class="wloc"><?php echo $location ?></span>
            <?php } ?>
          </button>

          <div style="display:<?php echo $is_display ?>" class="info-wrapper<?php echo $is_active ?>" role="tabpanel" aria-labelledby="tab-<?php echo sanitize_title($name) ?>" id="tabpanel-<?php echo sanitize_title($name) ?>">
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
                    <div class="status <?php echo $status_value ?>"><?php echo $status_label ?></div>
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
          <?php } ?>
       <?php $j++; } ?>
      <?php } ?>
  </div>  
  <?php } ?>

</section>