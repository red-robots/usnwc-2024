 <?php if( get_row_layout() == 'two_column_layout_text_image' ) { ?>
  <?php  
  $section_title = get_sub_field('title');
  $section_text = get_sub_field('details');
  $section_buttons = get_sub_field('buttons');
  $featured_image = get_sub_field('featured_image');
  $image_position = get_sub_field('image_position');
  $column_class = ( ($section_title || $section_text) &&  $featured_image ) ? 'half' : 'full';
  
  if( ( ($section_title || $section_text) || $featured_image ) ) { ?>
  <section class="section section--<?php echo get_row_layout() ?> <?php echo $column_class ?>">
    <div class="content-inner">
      <div class="flexwrap">
        <?php if ($section_title || $section_text) { ?>
        <div class="flexcol textCol">
          <?php if ($section_title) { ?>
          <h2 class="section-title"><?php echo $section_title ?></h2> 
          <?php } ?>
          <?php if ($section_text) { ?>
          <div class="section-text"><?php echo anti_email_spam($section_text) ?></div> 
          <?php } ?>

          <?php if ($section_buttons) { 
            $button_count = count($section_buttons);
            $count_odd_even = ( $button_count % 2==0 ) ? ' count-is-even':' count-is-odd';
            if($button_count>1) {
              $count_odd_even .= ' multiple';
            }
            ?>
          <div class="section-buttons count-<?php echo $button_count ?><?php echo $count_odd_even ?>">
            <?php $bCtr=1; foreach ($section_buttons as $b) { 
              $btn = $b['button'];
              $button_style = $b['button_style'];
              $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
              $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
              $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
              //$btn_class = ( $bCtr % 3==0 ) ? ' thirds' : '';
              if($btnName && $btnLink) { ?>
                <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="button-traditional <?php echo $button_style ?>"><span><?php echo $btnName ?></span></a>
              <?php $bCtr++; } ?>
            <?php } ?>
          </div> 
          <?php } ?>
        </div> 
        <?php } ?>

        <?php if ($featured_image) { ?>
          <div class="flexcol imageCol">
            <figure>
              <img src="<?php echo $featured_image['url'] ?>" alt="<?php echo $featured_image['title'] ?>" />
            </figure>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>
  <?php } ?>

<?php } ?>