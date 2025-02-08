<?php  
$secondary_links = get_field('secondary_links', 'option');
$snapshotLink = get_field('snapshot_link', 'option');
$branches = get_field('whitewaterLocations', 'option'); ?>
<div class="right">
  <?php if ($secondary_links) { ?>
    <?php foreach ($secondary_links as $e) { 
      $s = $e['link'];
      $s_name = ( isset($s['title']) ) ? $s['title'] : '';
      $s_link = ( isset($s['url']) ) ? $s['url'] : '';
      $s_target = ( isset($s['target']) ) ? $s['target'] : '_self';
      if($s_name && $s_link) { ?>
      <div class="nav-item"><a href="<?php echo $s_link ?>" class="navlink" target="<?php echo $s_target ?>"><?php echo $s_name ?></a></div>
      <?php } ?>
    <?php } ?>
  <?php } ?>
   <div class="nav-item nav-item-today">
      <button  class="navlink navlink-today today">Today</button>
      <?php if ($branches) { ?>
      <div class="today-dropdown-container today--dropdown">
        <div class="today-inner">
          <div class="flexwrap">
            <div class="flexcol fx-locations">
              <ul class="branches">
              <?php $m=1; foreach ($branches as $b) { 
                $cs = $b['location'];
                $tax = $b['locations_taxonomy'];
                $name = ( isset($tax->name) && $tax->name ) ? $tax->name : '';
                $slug = ( isset($tax->slug) && $tax->slug ) ? $tax->slug : '';
                $city = ( isset($cs->name) && $cs->name ) ? $cs->name : '';
                $is_active = ($m==1) ? ' active':'';
                $is_expanded = ($m==1) ? 'true':'false';
                if($name) { ?>
                <li>
                  <a href="javascript:void(0)" class="branchName<?php echo $is_active ?>" role="button" aria-expanded="<?php echo $is_expanded ?>" data-location="<?php echo $slug ?>">
                    <span class="name"><?php echo $name ?></span>
                    <?php if($city) { ?>
                    <span class="city"><?php echo $city ?></span>
                    <?php } ?>
                  </a>  
                </li>
                <?php } ?>
              <?php $m++; } ?>
              </ul>
            </div>
            <div class="flexcol fx-info">
              <ul class="location-details">
                <?php $n=1; foreach ($branches as $b) { 
                  $is_active = ($n==1) ? ' active':'';
                  $status = $b['trail_status_branch'];
                  $custom_status = $b['trail_status_custom_message'];
                  $trail_status = '';
                  if($status=='open') {
                    $trail_status = 'Trails Open';
                  }
                  else if($status=='closed') {
                    $trail_status = 'Trails Closed';
                  }
                  else if($status=='custom') {
                    if( $custom_status ) {
                      $trail_status = $custom_status;
                    }
                  }
                  $tax = $b['locations_taxonomy'];
                  $loc = (isset($b['location'])) ? $b['location'] : '';
                  $today_items = $b['today_items'];
                  $name = ( isset($tax->name) && $tax->name ) ? $tax->name : '';
                  $slug = ( isset($tax->slug) && $tax->slug ) ? $tax->slug : '';
                  $city = ( isset($loc->name) && $loc->name ) ? $loc->name : '';
                  $mobile_display = ($n==1) ? ' style="display:block"' : ' style="display:none"';
                  $mobile_open = ($n==1) ? ' open':'';
                ?>
                <li class="info <?php echo $status ?><?php echo $is_active ?>" data-location="<?php echo $slug ?>">
                  <button class="mobile-info-toggle" data-slug="<?php echo $slug ?>" aria-expanded="<?php echo ($n==1) ? 'true':'false' ?>" aria-controls="location--<?php echo $slug ?>">
                    <span class="name"><?php echo $name ?></span>
                    <?php if($city) { ?>
                    <span class="city"><?php echo $city ?></span>
                    <?php } ?>
                  </button>
                  <div class="inner-info<?php echo $mobile_open ?>" id="location--<?php echo $slug ?>" <?php echo $mobile_display ?>>
                    <div class="info-trail-status">
                      <div class="tlabel">Trail Status</div>
                      <div class="tVal"><?php echo $trail_status ?></div>
                    </div>
                    
                    <?php if ($today_items) { ?>
                    <div class="info-today-schedule<?php echo $is_active ?>">
                      <ul class="items">
                      <?php foreach ($today_items as $e) { 
                        $title = $e['title'];
                        $hours_shortcode = $e['hours_shortcode'];
                        $small_text = $e['small_text'];
                        $shortcode = '';
                        if($hours_shortcode) {
                          if ( (strpos($hours_shortcode, '[') !== false) && strpos($hours_shortcode, ']') !== false ) {
                            if( do_shortcode($hours_shortcode) ) {
                              $shortcode = do_shortcode($hours_shortcode);
                            }
                          }
                        }

                        $btn = $e['details_link'];
                        $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
                        $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
                        $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';

                        if($title) { ?>
                        <li>
                          <div class="tlabel"><?php echo $title ?></div>
                          <div class="tVal">
                            <?php if ($shortcode) { ?>
                            <div class="text"><?php echo $shortcode ?></div>
                            <?php } ?>
                            <?php if ($small_text) { ?>
                            <div class="extra"><?php echo $small_text ?></div>
                            <?php } ?>
                            <?php if ($btnName && $btnLink) { ?>
                            <div class="info-link"><a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>"><?php echo $btnName ?></a></div>
                            <?php } ?>
                          </div>
                        </li>
                        <?php } ?>
                      <?php } ?>
                      </ul> 
                    </div>  
                    <?php } ?>
                  </div>
                </li>
                <?php $n++; } ?>  
              </ul>

              <?php  
              $ss_btnName = (isset($snapshotLink['title']) && $snapshotLink['title']) ? $snapshotLink['title'] : '';
              $ss_btnLink = (isset($snapshotLink['url']) && $snapshotLink['url']) ? $snapshotLink['url'] : '';
              $ss_btnTarget = (isset($snapshotLink['target']) && $snapshotLink['target']) ? $snapshotLink['target'] : '_self';
              if ($ss_btnName && $ss_btnLink) { ?>
              <div class="snapshot-button">
                <a href="<?php echo $ss_btnLink ?>" target="<?php echo $ss_btnTarget ?>" class="snapshotBtn"><?php echo $ss_btnName ?></a>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
    </div> 
</div>