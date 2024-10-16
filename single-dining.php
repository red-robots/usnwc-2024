<?php
get_header(); 
?>
<div id="primary" class="content-area-full content-default single-dining-post">
  <main id="main" data-postid="post-<?php the_ID(); ?>" class="site-main" role="main">
    <?php while ( have_posts() ) : the_post(); ?>
      <?php if( get_the_content() ) { ?>
      <div class="intro-text-wrap">
        <div class="wrapper">
          <h1 class="page-title"><span><?php the_title(); ?></span></h1>
          <div class="intro-text"><?php the_content(); ?></div>
        </div>
      </div>
      <?php } ?>

      <?php 
      $schedules = get_field('schedule_days');
      $subheading = get_field('subheading');
      if($schedules) { ?>

        <?php foreach ($schedules as $s) { 
        $day_image = $s['day_image'];
        $times = $s['coursetime']; 
        $sectionClass = ($day_image && $times) ? 'half':'full';
        ?>
        <div id="schedules"  class="repeatable-block  section--schedules section-two_column_row">
          <div class="section-inner section-upcoming_events">
            <div class="flexwrap <?php echo $sectionClass ?>">

                <?php if($day_image) { ?>
                <div class="imageBlock">
                  <img src="<?php echo $day_image['url'] ?>" alt="<?php echo $day_image['title'] ?>" />    
                </div>
                <?php } ?>

                <?php if($times) { ?>
                <div class="textBlock schedule-items">
                  <div class="inside">
                    <?php if ($subheading) { ?>
                    <h2 class="subhead"><?php echo $subheading ?></h2> 
                    <?php } ?>
                    <div class="course-times">
                      <?php foreach ($times as $t) { 
                        //$time = $t['time'];
                        $course = $t['course'];
                        $product_link = $t['product_link'];
                        $xbtn = $t['extra_link'];
                        $xbtnUrl = (isset($xbtn['url']) && $xbtn['url']) ? $xbtn['url'] : '';
                        $xbtnTitle = (isset($xbtn['title']) && $xbtn['title']) ? $xbtn['title'] : '';
                        $xbtnTarget = (isset($xbtn['target']) && $xbtn['target']) ? $xbtn['target'] : '_self';
                        $shortnameDay = '';
                        if($course) { 
                          $date  = $course;
                          $dayParts = explode(',', $course);
                          if(count($dayParts) > 1) {
                            $day = $dayParts[0];
                            if( $dayName = shortenDayName($day) ) {
                              $date = $dayName . ', ' . $dayParts[1];
                            }
                          }

                          if($date) {
                            $date = strtoupper($date);
                            if (strpos($date, 'TH') !== false) {
                              $date = str_replace('TH','<sup style="text-transform:none">th</sup>', $date);
                            }
                            else if (strpos($date, 'ND') !== false) {
                              $date = str_replace('ND','<sup style="text-transform:none">nd</sup>', $date);
                            }
                            else if (strpos($date, 'ST') !== false) {
                              $date = str_replace('TH','<sup style="text-transform:none">st</sup>', $date);
                            }
                          }
                        ?>
                        <div class="lineItem">
                          <span class="time"><?php echo $date ?></span>
                          <?php if($xbtnUrl || $product_link) { ?>
                          <span class="links">
                            <?php if($product_link) { ?>
                            <a data-accesso-keyword="<?php echo $product_link ?>" href="javascript:void(0)">Register</a>
                            <?php } ?>

                            <?php if($xbtnUrl && $xbtnTitle) { ?>
                            <a href="<?php echo $xbtnUrl ?>" target="<?php echo $xbtnTarget ?>"><?php echo $xbtnTitle ?></a>
                            <?php } ?>
                          </span>
                          <?php } ?>
                        </div>
                        <?php } ?>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <?php } ?>

              


            </div>
          </div>
        </div>
        <?php } ?>

      <?php } ?>

      <?php  
      $event_title = get_field('event_title');
      if($event_title) { ?>
      <div id="event-details" data-section="<?php echo $event_title ?>" class="event-details--section">
        <div class="wrapper">
          <h2><?php echo $event_title ?></h2>
        </div>
        

        <?php  
        /* EVENT DETAILS */
        $details = get_field("event_details");
        $event_button = get_field("event_button");
        $button = ( isset($event_button['button_type']) && $event_button['button_type'] ) ? $event_button['button_type']:'';
        $buttonName = '';
        $buttonLink = '';
        $buttonTarget = '';
        if($button && $button=='pdf') {
          $buttonPDF = $event_button['button_pdf'];
          if($buttonPDF['pdf_button_name'] && $buttonPDF['pdf_button_link']) {
            $buttonName = $buttonPDF['pdf_button_name'];
            $buttonLink = $buttonPDF['pdf_button_link']['url'];
            $buttonTarget = '_blank';
          }
        } else if($button && $button=='pagelink') {
          $buttonPage = $event_button['button_pagelink'];
          $buttonName = $buttonPage['title'];
          $buttonLink = $buttonPage['url'];
          $buttonTarget = ( isset($buttonPage['target']) && $buttonPage['target'] ) ? $buttonPage['target']:'_self';
        }
        $cta_buttons = get_field("event_cta_buttons");
        if($details) { ?>
        <div id="section-event-details" data-section="<?php echo $event_title ?>" class="section-content dining-event-details single-dining-page">
          <div class="wrapper">
            <div class="details text-center">
              <?php foreach ($details as $d) { 
                $title = $d['title'];
                $text = $d['description'];
                ?>
                <div class="info">
                  <?php if ($title) { ?>
                    <h3 class="infoTitle"><?php echo $title ?></h3>
                  <?php } ?>
                  <?php if ($text) { ?>
                    <div class="infoText"><?php echo $text ?></div>
                  <?php } ?>
                </div>
              <?php } ?>
              
            </div>
          </div>
        </div>
        <?php } ?>  

      </div>
      <?php } ?>

    <?php endwhile; ?>
  </main>
</div>
<?php
get_footer();