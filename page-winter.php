<?php
/*
 * Template Name: Winter Page
 */
get_header();
?>


<div id="primary" class="content-area-full content-about">
  <main id="main" class="site-main" role="main">
    <?php while ( have_posts() ) : the_post(); ?>
    <?php endwhile; ?>

    <?php /* FLEXIBLE CONTENT */ ?>
    <?php if( have_rows('flexible_content_about') ) { ?>
      <?php $ctr=1; while( have_rows('flexible_content_about') ): the_row(); ?>
        
        <?php include( locate_template('parts-flexible/winter/text_content_center.php') ); ?>
        <?php include( locate_template('parts-flexible/winter/fullwidth_video.php') ); ?>
        <?php include( locate_template('parts-flexible/winter/fullwidth_video_alt.php') ); ?>
        <?php include( locate_template('parts-flexible/winter/image_and_text_two_column.php') ); ?>
        <?php include( locate_template('parts-flexible/winter/image_cards_columns.php') ); ?>
        <?php include( locate_template('parts-flexible/winter/masonry_infocards.php') ); ?>
        <?php include( locate_template('parts-flexible/winter/carousel_section.php') ); ?>
        <?php include( locate_template('parts-flexible/winter/faqs_accordion_section.php') ); ?>
      
      <?php $ctr++; endwhile;  ?>
    <?php  } ?>

  </main>
</div>


<?php get_footer(); ?>
<script src="https://player.vimeo.com/api/player.js"></script>
<script>
  var iframe = document.querySelector('.iframe-vimeo');
  if(iframe) {
    var player = new Vimeo.Player(iframe);
    player.setVolume(0);
  }
  if ( $('.winterGallerySlider').length ) {
    $('.winterGallerySlider').each(function(){
      var SliderId = '#'+ $(this).attr('id');
      var winterSwiper = new Swiper (SliderId, {
        speed: 500,
        loop: true,
        effect: 'fade',
        slidesPerView: 1,
        autoplay: {
          delay: 5000,
        },
        navigation: {
          nextEl: SliderId + ' .swiper-button-next',
          prevEl: SliderId + ' .swiper-button-prev',
        },
        pagination: {
          el: SliderId +  ' .swiper-pagination', // Target the pagination container
          clickable: true, // Allow clicking on dots to navigate
        },
      });
    });
  }
</script>
