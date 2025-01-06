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
      <?php $ctr=1; while( have_rows('flexibleContent') ): the_row(); ?>
        
       <?php include( locate_template('parts-flexible/weddings/upcoming_events.php') ); ?>
       <?php include( locate_template('parts-flexible/weddings/upcoming_events_groupby_months.php') ); ?>
       <?php include( locate_template('parts-flexible/weddings/two_column_row.php') ); ?>
       <?php include( locate_template('parts-flexible/weddings/fullwidth_image.php') ); ?>
       <?php include( locate_template('parts-flexible/weddings/cards_bottom_text.php') ); ?>
       <?php include( locate_template('parts-flexible/weddings/pricing_cards.php') ); ?>
       <?php include( locate_template('parts-flexible/fullwidth_red_bgcolor.php') ); ?>

      <?php $ctr++; endwhile; ?>
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
    $faqs_show = get_field('faqs_visibility');
    $faqs = '';
    if(!$faqs_show) {
      $faqs_post_item = '';
    }
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