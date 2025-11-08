<?php if( get_row_layout() == 'masonry_infocards' ) { ?>
  <?php  
  $html_anchor = get_sub_field('html_anchor');
  $section_title = get_sub_field('section_title');
  $infocards = get_sub_field('infocards');
  $infocards_carousel = get_sub_field('infocards_carousel_mobile');
  $infocards_carousel_mobile = ($infocards_carousel) ? ' convert-to-carousel' : '';
  $bottom_information = get_sub_field('bottom_information');
  // $section_text = get_sub_field('details');
  //$buttons = get_sub_field('buttons');
  if($section_title || $infocards || $bottom_information) { ?>
  <section id="<?php echo get_row_layout() ?>_<?php echo $ctr ?>" class="repeatable repeatable_<?php echo get_row_layout() ?>">
    <?php if ($html_anchor) { ?>
    <div id="<?php echo $html_anchor ?>" class="html-anchor"></div>
    <?php } ?>
    <div class="wrapper textCenter">
      <?php if ($section_title) { ?>
      <div class="page--title--wrapper">
        <h2 class="h2"><?php echo $section_title ?></h2>
      </div>
      <?php } ?>

      <?php if ($infocards) { ?>
        <div class="infocards<?php echo $infocards_carousel_mobile ?>">
          <?php foreach ($infocards as $info) { 
            $cards_per_row = $info['cards_per_row'];
            if($cards_per_row) { ?>
            <div class="cards-row count-<?php echo count($cards_per_row); ?>">
              <?php foreach ($cards_per_row as $card) { 
                $image = $card['image'];
                $has_popup_details = $card['has_popup_details'];
                $full_details = $card['full_details'];
                $has_popup = ($has_popup_details && $full_details) ? ' has-popup' : '';
                $colwidth = ($card['image_width']) ? $card['image_width'] : '100';
                $text = $card['text'];
                //$stripped_text = ($text) ? $text : ''; 
                if($image) { 
                  $image_id = $image['ID'];
                  $image_pagelink = get_field('image_website', $image_id);
                  $link_target = get_field('link_target', $image_id);
                  $openLink = '';
                  $closeLink = '';
                  if($image_pagelink) {
                    $target = ($link_target) ? '_blank':'_self';
                    $openLink = '<a class="imageLink" href="'.$image_pagelink.'" target="'.$target.'">';
                    $closeLink = '</a>';
                    $text = ($text) ? strip_tags($text, '<h1><h2><h3><h4><p><b><strong><i><em>') : ''; 
                  }
                ?>
                <div class="card-item<?php echo $has_popup ?>" style="width:<?php echo $colwidth ?>%">
                  <figure>
                    <?php echo $openLink ?>
                    <div class="imagediv">
                      <img src="<?php echo $image['url'] ?>" alt="" />
                    </div>
                    <?php if ($text) { ?>
                    <figcaption>
                      <div class="text">
                        <?php echo anti_email_spam($text); ?>
                      </div>
                    </figcaption>  
                    <?php } ?>
                    <?php echo $closeLink ?>
                  </figure>

                  <?php if ($has_popup_details && $full_details) { ?>
                  <div class="popup-details-container" tabindex="-1" aria-hidden="true" style="display:none">
                    <!-- <div class="popupDetails"><?php //echo anti_email_spam($full_details); ?></div> -->
                    <?php $encodedDetails = htmlentities($full_details, ENT_QUOTES, 'UTF-8'); ?>
                    <textarea class="popupDetailsTextBox" style="display:none;"><?php echo $encodedDetails; ?></textarea>
                  </div>
                  <?php } ?>
                </div>
                <?php } ?>
              <?php } ?>
            </div>
            <?php } ?>
          <?php } ?>
        </div>
        
        <?php if ($infocards_carousel) { ?>
        <div class="infoCardsCarousel">
          <div id="infoCardsCarouselSlider_<?php echo $ctr ?>" class="infoCardsCarouselSlider owl-carousel owl-theme"></div>
          <button class="infocard-nav infocard-prev-btn"><span class="sr-only">Previous</span><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
          <button class="infocard-nav infocard-next-btn"><span class="sr-only">Next</span><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
        </div>
        <?php } ?>
      <?php } ?>



      <?php if ($bottom_information) { ?>
      <div class="bottom-information">
        <?php echo anti_email_spam($bottom_information); ?>
      </div>
      <?php } ?>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>