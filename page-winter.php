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


<div id="infocardsModal" class="modal customModal fade" aria-modal="false" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close modal-close-button" data-dismiss="modal" aria-label="Dismiss pop-up"><span class="sr-only">Dismiss pop-up</span></button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>


<?php get_footer(); ?>
<script src="https://player.vimeo.com/api/player.js"></script>
<script>
  var iframe = document.querySelector('.iframe-vimeo');
  if(iframe) {
    var player = new Vimeo.Player(iframe);
    player.setVolume(0);
  }
  jQuery(document).ready(function($){
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

    if(  $('#infocardsModal.modal').length ) {
      $('#infocardsModal.modal').insertAfter('.site-footer')
    }

    if( $('.infocards .card-item a[href="#popup-details"]').length ) {
      $('.infocards .card-item a[href="#popup-details"]').each(function(){
        let href = $(this).attr('href');
        $(this).addClass('popup-details-button');
        $(this).attr({
          'href':'javascript:void(0)',
          'role':'button'
        });
      });
    }


    $(document).on('click','.infocards .popup-details-button', function(e){
      e.preventDefault();
      var parent = $(this).parents('.card-item');
      if( parent.find('.popup-details-container').length ) {
        $('#infocardsModal.modal .modal-body').html("");
        let detailsContainer = parent.find('.popup-details-container');
        let details = detailsContainer.find('.popupDetails');
        detailsContainer.addClass('show-popup');
        details.appendTo('#infocardsModal.modal .modal-body');
        $('#infocardsModal.modal').addClass('show');
        $('body').addClass('modal-open');
        $('#infocardsModal.modal').attr('aria-modal','true');
      }
    });

    
    $(document).on('click','.close.modal-close-button', function(e){
      e.preventDefault();
      closeWinterModal();
    });

    function closeWinterModal() {
      $('#infocardsModal.modal').addClass('closed');
      setTimeout(function(){
        $('#infocardsModal.modal').removeClass('show closed');
        $('#infocardsModal.modal').attr('aria-modal','false');
        $('body').removeClass('modal-open');
      },300);
      setTimeout(function(){
        let modalContent = $('#infocardsModal.modal .modal-body').html();
        $('.popup-details-container.show-popup').html(modalContent);
        $('.popup-details-container').removeClass('show-popup');
      },350);
    }

    $(document).on('click','#infocardsModal', function(e){
      var container = $("#infocardsModal .modal-content");
      if(!container.is(e.target) && container.has(e.target).length === 0){
        closeWinterModal();
      }
    });
  });
</script>
