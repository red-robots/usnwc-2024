<?php
/**
 * Template Name: Flexible Content
 */
get_header(); 
$twoColumnItems = array();
$show_subnav = get_field('show_subnav');
?>

<div id="primary" class="content-area-full content-default repeatable-layout">
	<main id="main" class="site-main" role="main">

    <?php while ( have_posts() ) : the_post(); ?>
    <section class="main-description">
      <div class="wrapper text-center">
        <h1 class="pagetitle"><span><?php the_title() ?></span></h1>
        <?php if ( get_the_content() ) { ?>
        <?php the_content(); ?> 
        <?php } ?>
      </div>
    </section>
    <?php endwhile; ?>

    <?php if ($show_subnav) { ?>
    <div id="pageTabs" class="pageTabs2 new" style="display:none;">
      <div class="wrapper">
        <div id="tabcontent"></div>
      </div>
    </div>
    <?php } ?>
    

		<?php if( have_rows('flexibleContent') ) {  ?>
    <section class="flexible-content-wrapper">
      <?php $i=1; while( have_rows('flexibleContent') ): the_row(); ?>
        
        <?php if( get_row_layout() == 'upcoming_events' ) { ?>
          <?php 
            $section_title = get_sub_field('section_title');
            $post_type = get_sub_field('post_type');
            $btn = get_sub_field('button');
            $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
            $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
            $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';

            if($post_type) { 
              // $args = array(
              //   'posts_per_page'  => 3,
              //   'post_type'       => $post_type,
              //   'post_status'     => 'publish',
              //   'meta_query'      => array(
              //     'relation' => 'OR',
              //     array(
              //       'key' => 'start_date',
              //       'value' => date('Y-m-d'),
              //       'compare' => '>=',
              //       'type' => 'DATE'
              //     ),
              //   )
              // );
              $current_date = date('Y-m-d');
              $args = array(
                'posts_per_page'  => 3,
                'post_type'       => $post_type,
                'post_status'     => 'publish',
                'meta_query'      => array(
                  'relation' => 'OR',
                  array(
                    'key'        => 'end_date',
                    'compare'    => '>=',
                    'value'      => $current_date,
                  ),
                  array(
                      // If an end_date does not exist.
                      array(
                          // We use another, nested set of conditions, for if the end_date
                          // value is empty, OR if it is null/not set at all.
                          'relation' => 'OR',
                          array(
                              'key'        => 'end_date',
                              'compare'    => '=',
                              'value'      => '',
                          ),
                          array(
                              'key'        => 'end_date',
                              'compare'    => 'NOT EXISTS',
                          ),
                      ),
                      // AND, if the start date is upcoming.
                      array(
                          'key'        => 'start_date',
                          'compare'    => '>=',
                          'value'      => $current_date,
                      ),
                  )
                )
              );

              $posts = get_posts($args);
              if($posts) { ?>
              <div id="section-upcoming_events-<?php echo $i ?>" data-posttype="<?php echo $post_type ?>" class="repeatable-block section section-upcoming_events">
                <div class="wrapper">
                  
                  <?php if($section_title) { ?>
                    <h2 class="section-title"><?php echo $section_title ?></h2>
                  <?php } ?>

                  <div class="flexwrap">
                  <?php foreach($posts as $p) { 
                    $pid = $p->ID;
                    $title = $p->post_title;
                    $image = get_field('full_image', $pid);
                    if(!$image) {
                      $image = get_field('mobile-banner', $pid);
                    }
                    $start_date = get_field('start_date', $pid);
                    $pagelink = get_permalink($pid);
                    if($start_date) {
                      $start_date = date('l, F d', strtotime($start_date));
                    }
                    ?>
                    <div class="infobox">
                      <div class="inside">
                        <?php if ($post_type!=='dining') { ?>
                          <?php if ($start_date) { ?>
                          <div class="event-date"><?php echo $start_date ?></div>
                          <?php } ?>
                        <?php } ?>
                        
                        <?php if ($image) { ?>
                        <figure class="event-image">
                          <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['url'] ?>" />
                        </figure>
                        <?php } ?>
                        <?php if ($title) { ?>
                        <h3 class="event-title"><?php echo $title ?></h3>
                        <?php } ?>
                        <div class="button-block">
                          <a href="<?php echo $pagelink ?>" class="button-pill">See Details</a>
                        </div>


                        <?php 
                        //ADVENTURE DINING
                        if ($post_type=='dining') { 
                          $schedules = get_field('schedule_days', $pid);

                          if($schedules) { ?>
                          <div class="schedule-items">
                            <?php foreach ($schedules as $s) { 
                              $times = $s['coursetime'];
                              if($times) { ?>
                              <div class="course-times">
                              <?php foreach ($times as $t) { 
                                //$time = $t['time'];
                                $course = $t['course'];
                                $product_link = $t['product_link'];
                                $btn = $t['extra_link'];
                                $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
                                $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
                                $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
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
                                ?>
                                <div class="lineItem">
                                  <span class="time"><?php echo $date ?></span>
                                  <?php if($btn || $product_link) { ?>
                                  <span class="links">
                                    <?php if($product_link) { ?>
                                    <a data-accesso-keyword="<?php echo $product_link ?>" href="javascript:void(0)">Register</a>
                                    <?php } ?>

                                    <?php if($btnUrl && $btnTitle) { ?>
                                    <a href="<?php echo $btnUrl ?>" target="<?php echo $btnTarget ?>"><?php echo $btnTitle ?></a>
                                    <?php } ?>
                                  </span>
                                  <?php } ?>
                                </div>
                                <?php } ?>
                              <?php } ?>
                              </div>
                              
                              <?php } ?>
                            <?php } ?>
                          </div>  
                          <?php } ?>
                        <?php } ?>

                      </div>
                    </div>
                  <?php } ?>
                  </div>

                  <?php if($btnUrl && $btnTitle) { ?>
                  <div class="buttonBlock buttonMiddle">
                    <a href="<?php echo $btnUrl ?>" target="<?php echo $btnTarget ?>" class="button button-red uppercase"><?php echo $btnTitle ?></a>
                  </div>
                  <?php } ?>

                </div>
              </div>
              <?php } ?>
            <?php } ?>
        <?php } 
        else if( get_row_layout() == 'upcoming_events_groupby_months' ) { 
          $post_type = get_sub_field('post_type');
          $btn = get_sub_field('button');
          $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
          $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
          $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
          if( $post_type ) { 
            $args = array(
              'posts_per_page'  => -1,
              'post_type'       => $post_type,
              'post_status'     => 'publish',
              'meta_key' => 'start_date',
              'orderby' => 'meta_value_num',
              'order' => 'ASC',
              'meta_query'      => array(
                array(
                  'key' => 'start_date',
                  'value' => date('Y-m-d'),
                  'compare' => '>=',
                  'type' => 'DATE'
                )
              )
            );
            $posts = get_posts($args);
            $posts_by_month = array();
            if($posts) {
              foreach($posts as $p) {
                $start_date = get_field('start_date', $p->ID);
                if($start_date) {
                  $month = date('Ym', strtotime($start_date));
                  $posts_by_month[$month][] = $p; 
                }
              }
            }

            if($posts_by_month) { ?>
            <?php foreach ($posts_by_month as $m=>$items) { 
              $m = $m . '01';
              $monthName = date('F Y',strtotime($m));
              $monthSlug = strtolower( date('F-Y',strtotime($m)) );
              $post_type = $items[0]->post_type;
            ?>
            <div id="section-upcoming_events-<?php echo $m ?>" class="repeatable-block section-upcoming_events monthly-events events--<?php echo $post_type ?>">
              <div class="wrapper">
                <h2 class="section-title"><?php echo $monthName ?></h2>

                <div class="flexwrap">
                <?php foreach ($items as $row) { 
                  $pid = $row->ID;
                  $title = $row->post_title;
                  $start_date = get_field('start_date', $pid);
                  $short_description = get_field('short_description', $pid);
                  $image = get_field('mobile-banner', $pid);
                  if(!$image) {
                    $image = get_field('thumbnail_image', $pid);
                  }
                  $pagelink = get_permalink($pid);
                  if($start_date) {
                    $start_date = date('l, F d', strtotime($start_date));
                  }
                ?>
                <div class="infobox">
                  <div class="inside">
                    <?php if ($start_date) { ?>
                    <div class="event-date"><?php echo $start_date ?></div>
                    <?php } ?>
                    <?php if ($image) { ?>
                    <figure class="event-image">
                      <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['url'] ?>" />
                    </figure>
                    <?php } ?>
                    <?php if ($title) { ?>
                    <h3 class="event-title"><?php echo $title ?></h3>
                    <?php } ?>

                    <?php if ($short_description) { ?>
                    <div class="summary"><?php echo $short_description ?></div>
                    <?php } ?>
                    
                    <div class="button-block">
                      <?php if ($post_type=='film-series') { ?>
                        <a href="javascript:void(0)" class="button-pill button-popup-details" data-postid="<?php echo $pid ?>">See Details</a>
                      <?php } else { ?>
                        <a href="<?php echo $pagelink ?>" class="button-pill">See Details</a>
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

          <?php } ?>
        <?php } else if ( get_row_layout() == 'two_column_row' ) { 
          
          $text = get_sub_field('text_column'); 
          $images = get_sub_field('images'); 
          $gallery_type = get_sub_field('gallery_type'); 
          $image_position = get_sub_field('image_position'); 
          $columnClass = ( $text && $images ) ? 'half':'full';
          if($images) {
            if($gallery_type=='normal' && count($images)>3 ) {
              foreach($images as $k=>$img) {
                if($k>3) {
                  unset($images[$k]);
                }
              }
            }
          }
          $alt_class = 'odd';
          if($text || $images) { 
            if($twoColumnItems && ( end($twoColumnItems) % 2==0 ) ) {
              $alt_class = 'even';
            }
            $twoColumnItems[] = $i; 
            if($image_position) {
              $position = ($image_position==1) ? 'left':'right';
              $alt_class .= ' image-position-'.$position;
            }
            ?>
            <div id="section-two_column_row-<?php echo $i ?>" class="repeatable-block section  section-two_column_row <?php echo $alt_class ?>">
              <div class="section-inner">
                <div class="flexwrap <?php echo $columnClass ?>">
                  <?php if ($text) { ?>
                  <div class="textBlock">
                    <div class="inside"><?php echo $text ?></div>
                  </div>
                  <?php } ?>

                  <?php if ($images) { ?>
                  <div class="imageBlock <?php echo $gallery_type ?> count<?php echo count($images) ?>">
                    <div class="gallery-content">
                      <?php if ($gallery_type=='slideshow') { ?>
                      <div id="gallerySlider<?php echo $i ?>" class="swiper gallerySlider">
                        <div class="swiper-wrapper">
                          <?php foreach ($images as $img) { ?>
                          <div class="swiper-slide">
                            <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
                          </div>
                          <?php } ?>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                      </div>

                      <?php } else { ?>
                        <?php foreach ($images as $img) { ?>
                        <figure>
                          <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
                        </figure>
                        <?php } ?>
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php } 
        else if ( get_row_layout() == 'fullwidth_image' ) {  
          $image = get_sub_field('image'); 
          $text = get_sub_field('text'); 
          $add_text_overlay = get_sub_field('add_text_overlay'); 
          $hasText = ($add_text_overlay && $text) ? 'has-text-overlay':'only-image';
          if($image) { ?>
          <div id="section-fullwidth_image-<?php echo $i ?>" class="repeatable-block section  section-fullwidth_image <?php echo $hasText ?>">
            <div class="section-inner">
              <figure>
                <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>">
                <?php if ($add_text_overlay && $text) { ?>
                <div class="text-overlay">
                  <div class="text-inner">
                    <figcaption>
                      <?php echo $text ?>
                    </figcaption>
                  </div>
                </div>  
                <?php } ?>
              </figure>
            </div>
          </div>
          <?php } ?>
        <?php } 
        else if ( get_row_layout() == 'cards_bottom_text' ) {  
          $card_text_intro = get_sub_field('card_text_intro'); 
          $card_columns = get_sub_field('card_columns'); 
          $card_content = get_sub_field('card_content'); 
          ?>
          <div id="section-cards_bottom_text-<?php echo $i ?>" class="repeatable-block section  section-cards_bottom_text">
            <div class="wrapper">
              <?php if ($card_text_intro) { ?>
              <div class="content-part text-intro">
                <?php echo anti_email_spam($card_text_intro); ?>
              </div> 
              <?php } ?>

              <?php if ($card_content) { ?>
              <div class="content-part cards-content show-<?php echo $card_columns ?>">
                <div class="flexwrap">
                  <?php foreach ($card_content as $c) { 
                    $image = $c['image'];
                    $text = $c['text'];
                    if($image || $text) { ?>
                    <div class="fxcol">
                      <div class="inner">
                      <?php if ($image) { ?>
                      <figure><img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>"></figure> 
                      <?php } ?>

                      <?php if ($text) { ?>
                      <div class="textbox"><?php echo $text ?></div> 
                      <?php } ?>
                      </div>
                    </div>
                    <?php } ?>
                  <?php } ?>
                </div>
              </div> 
              <?php } ?>
            </div>
          </div>
        <?php } 
        else if ( get_row_layout() == 'pricing_cards' ) {  
          $title = get_sub_field('title'); 
          $section_text = get_sub_field('section_text'); 
          $infocards = get_sub_field('infocards'); 
          ?>
          <div id="section-pricing_cards-<?php echo $i ?>" class="repeatable-block section section-pricing_cards">
            <div class="wrapper">
              <?php if ($title) { ?>
              <div class="content-part content-title">
                <h2><?php echo $title ?></h2>
                <?php if ($section_text) { ?>
                <div class="content-part content-intro">
                  <?php echo anti_email_spam($section_text); ?>
                </div> 
                <?php } ?>
              </div> 
              <?php } ?>

              <?php if ($infocards) { ?>
              <div class="content-part infocards">
                <div class="flexwrap show-<?php echo count($infocards); ?>">
                  <?php foreach ($infocards as $info) { 
                  $image = $info['image'];
                  $title = $info['title'];
                  $details = $info['details'];
                  $priceList = $info['price_list'];
                  $buttons1 = $info['button1'];
                  $buttons2 = $info['button2'];
                  $buttons = array($buttons1, $buttons2);
                  ?>
                  <div class="fxcol">
                    <div class="inner">
                      <?php if ($image) { ?>
                      <figure><img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>"></figure> 
                      <?php } ?>

                      <?php if ($title) { ?>
                      <h3 class="title"><?php echo $title ?></h3> 
                      <?php } ?>

                      <?php if ($details) { ?>
                      <div class="details"><?php echo $details ?></div> 
                      <?php } ?>

                      <?php if ($priceList) { ?>
                      <div class="priceList">
                        <?php foreach ($priceList as $p) { 
                          $name = $p['name'];
                          $price = $p['price'];
                          if($name) { ?>
                          <div class="line-item">
                            <span class="name"><span><?php echo $name ?></span></span>
                            <span class="price"><span><?php echo $price ?></span></span>
                          </div>
                          <?php } ?>
                        <?php } ?>
                      </div> 
                      <?php } ?>

                      <?php if ($buttons && array_filter($buttons) ) { ?>
                      <div class="button-block">
                        <?php foreach ($buttons as $btn) { 
                          $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
                          $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
                          $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
                          if($btnUrl && $btnTitle) { ?>
                          <a href="<?php echo $btnUrl ?>" target="<?php echo $btnTarget ?>" class="button button-red"><?php echo $btnTitle ?></a>
                          <?php } ?>
                        <?php } ?>
                      </div> 
                      <?php } ?>

                    </div>
                  </div>
                  <?php } ?>
                </div>
              </div> 
              <?php } ?>

              
            </div>
          </div>
        <?php } ?>

      <?php $i++; endwhile; ?>
    </section>
    <?php } ?>


    <?php  
      $fieldtrip_form_title = get_field('fieldtrip_form_title');
      $fieldtrip_form = get_field('fieldtrip_form');
      if($fieldtrip_form) { ?>
      <section id="section-fieldtrip_form" data-section="FAQ" class="section  section-fieldtrip_form">
        <div class="wrapper">
          <?php if ($fieldtrip_form_title) { ?>
          <h2 class="section-title"><?php echo $fieldtrip_form_title ?></h2>
          <?php } ?>

          <?php if ($fieldtrip_form) { ?>
          <div class="section-text"><?php echo anti_email_spam($fieldtrip_form); ?></div>
          <?php } ?>
        </div>
      </section>
      <?php } ?>

    <?php  
    $faqs_post_item = get_field('faqs_post_item');
    $faqs = '';
    if($faqs_post_item) { 
      $post_id = $faqs_post_item->ID;
      $faqs = get_field('faqs', $post_id);
      $max = 3;
      if($faqs) { 
        $totalFaqs = count($faqs);
        ?>
        <section id="section-faqs" data-section="FAQ" class="section  section-content section-content--faqs full no-image">
          <div class="wrapper">
            <div class="flexwrap">
              <div class="col faqs">
                <div class="shead-icon text-center">
                  <h2 class="stitle">FAQ</h2>
                </div>

                <div class="faqsItems">
                  <?php $x=1; foreach ($faqs as $f) { 
                    $xclass = ($x > $max) ? ' hide':'';
                    $question = $f['question'];
                    $answer = $f['answer'];
                    if($x==1) {
                      $xclass .=' first active';
                    }
                    if($question) { ?>
                    <div class="faq-item animated collapsible<?php echo $xclass ?>">
                      <?php if ($question) { ?>
                      <h3 class="option-name"><?php echo $question ?> <span class="arrow"></span></h3>
                      <?php } ?>

                      <?php if ($answer) { ?>
                      <div class="option-text"><?php echo $answer ?></div>
                      <?php } ?>
                      
                    </div>
                    <?php } ?>
                  <?php $x++; } ?>
                </div>

                <?php if ($totalFaqs > $max) { ?>
                <div class="morefaqs">
                  <a href="javascript:void(0)" id="seeMoreFAQs" class="seeMoreFAQs btn-sm xs"><span>See More</span></a>
                </div>
                <?php } ?>

              </div>
            </div>
          </div>
        </section>
      <?php } ?>

    <?php } ?>

	</main><!-- #main -->
</div><!-- #primary -->

<script>
jQuery(document).ready(function($){
  if( $('.gallerySlider').length ) {
    $('.gallerySlider').each(function(){
      var sliderId = '#' + $(this).attr('id');
      var swiper = new Swiper(sliderId, {
        effect: 'fade', /* "slide", "fade", "cube", "coverflow" or "flip" */
        loop: true,
        noSwiping: true,
        simulateTouch : true,
        speed: 2000,
        autoplay: {
          delay: 2000,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        on: {
          slideChange: function (e) {
            //Do something...
          }
        }
      });
    });
  }



  var seeMoreButton = document.getElementById('seeMoreFAQs');
  if(seeMoreButton){
    seeMoreButton.addEventListener('click', function(){
      var faqs = document.querySelectorAll('.faq-item');
      if(faqs) {
        faqs.forEach(function(item,index){
          if(item.classList.contains('hide')) {
            item.classList.remove('hide');
          }
        });
      }
      seeMoreButton.style.display="none";
    });
  }

  $(document).on('click', '.faqsItems .option-name', function(){
    var parent = $(this).parents('.faq-item');
    parent.toggleClass('active fadeIn');
    $('.faqsItems .faq-item').each(function(){
      $(this).not(parent).removeClass('active');
    });
  });

  <?php 
  //Script for Subnavigation
  if ($show_subnav) { ?>
    if( $('.flexible-content-wrapper .section').length > 0 ) {
      if( $("#pageTabs").length>0 ) {
        if( $('#main h2').length > 1 ) {
          var subnav = '<div class="wrapper"><div id="tabcontent">';
          $('.section').each(function(){
            var sectionId = $(this).attr("id");
            if( $(this).find('h2').length ) {
              var name = $(this).find('h2').eq(0).text().trim();
              subnav += '<span class="mini-nav"><a href="#'+sectionId+'">'+name+'</a></span>';
            }
          });
          subnav += '</div></div>';
          $("#pageTabs").html(subnav);
          $("#pageTabs").show();
        }
      }
    } 
  <?php } ?>

});
</script>
<?php
get_footer();