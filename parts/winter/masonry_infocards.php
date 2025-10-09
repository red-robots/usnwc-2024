<?php if( get_row_layout() == 'masonry_infocards' ) { ?>
  <?php  
  $section_title = get_sub_field('section_title');
  $infocards = get_sub_field('infocards');
  $bottom_information = get_sub_field('bottom_information');
  // $section_text = get_sub_field('details');
  //$buttons = get_sub_field('buttons');
  if($section_title || $infocards || $bottom_information) { ?>
  <section id="<?php echo get_row_layout() ?>_<?php echo $ctr ?>" class="repeatable repeatable_<?php echo get_row_layout() ?>">
    <div class="wrapper textCenter">
      <?php if ($section_title) { ?>
      <div class="page--title--wrapper">
        <h2 class="h2"><?php echo $section_title ?></h2>
      </div>
      <?php } ?>

      <?php if ($infocards) { ?>
      <div class="infocards">
        <?php foreach ($infocards as $info) { 
          $cards_per_row = $info['cards_per_row'];
          if($cards_per_row) { ?>
          <div class="cards-row count-<?php echo count($cards_per_row); ?>">
            <?php foreach ($cards_per_row as $card) { 
              $image = $card['image'];
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
              <div class="card-item" style="width:<?php echo $colwidth ?>%">
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
              </div>
              <?php } ?>
            <?php } ?>
          </div>
          <?php } ?>
        <?php } ?>
      </div>
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