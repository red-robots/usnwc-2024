<?php if( get_row_layout() == 'schedule_dash_listings' ) { 
  $section_title = get_sub_field('section_title');
  $section_title_color = get_sub_field('section_title_color');
  $info_1 = get_sub_field('info_1');
  $info_2 = get_sub_field('info_2');
  $schedule_listing = get_sub_field('schedule_listing');
  if( $section_title || $schedule_listing ) { ?>
  <div id="schedule-dash-listings-<?php echo $ctr ?>" class="schedule_dash_listings_section">
    <div class="wrapper">
      <?php if ( $section_title ) { ?>
      <div class="titleDiv">
        <h2 class="stitle" style="color:<?php echo $section_title_color ?>"><?php echo $section_title ?></h2>
        <?php if ( $info_1 ) { ?>
        <div class="info1"><?php echo $info_1 ?></div>
        <?php } ?>
        <?php if ( $info_2 ) { ?>
        <div class="info2"><?php echo $info_2 ?></div>
        <?php } ?>
      </div>
      <?php } ?>


      <?php if ( $schedule_listing ) { ?>
      <div class="schedule-listing-dashes">
        <?php foreach ($schedule_listing as $s) { 
          $name1 = $s['name'];
          $name2 = $s['name_normal'];
          $description = $s['description'];
          $time = $s['time'];

          $vbtnName = (isset($name1['title']) && $name1['title']) ? $name1['title'] : '';
          $vbtnLink = (isset($name1['url']) && $name1['url']) ? $name1['url'] : '';
          $vbtnTarget = (isset($name1['target']) && $name1['target']) ? $name1['target'] : '_self';
          ?>
          <div class="info">
            <div class="flexwrap">
              <?php if ($name1 || $name2) { ?>
              <span class="name">
                <?php if ($vbtnName && $vbtnLink) { ?>
                <a href="<?php echo $vbtnLink ?>" target="<?php echo $vbtnTarget ?>"><?php echo $vbtnName ?></a>
                <?php } ?>
                <?php if ($name2) { ?>
                <span><?php echo $name2 ?></span>
                <?php } ?>
              </span>
              <?php } ?>

              <?php if ($time) { ?>
              <span class="time"><?php echo $time ?></span>
              <?php } ?>
            </div>
            <?php if ($description) { ?>
            <div class="desc"><?php echo $description ?></div>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
  </div>
  <?php } ?>

<?php } ?>