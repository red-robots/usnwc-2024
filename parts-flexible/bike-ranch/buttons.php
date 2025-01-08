 <?php if( get_row_layout() == 'buttons' ) {  

  $section_buttons = get_sub_field('buttons');
  $buttons_per_row = get_sub_field('buttons_per_row');
  
  if($section_buttons) { 
    $button_count = count($section_buttons);
    $section_class = ($button_count>1) ? ' multiple':' single';
    $section_class .= ' count-'.$button_count;
    if( $buttons_per_row==0 || empty($buttons_per_row) ) {
      $buttons_per_row = 1;
    }
    $per_row_class = ' buttons-per-row'.$buttons_per_row;
    ?>
    <section class="section section--<?php echo get_row_layout() ?>">
      <div class="content-buttons-row<?php echo $section_class ?>">
        <div class="section-buttons count-<?php echo $button_count ?><?php echo $per_row_class ?>">
          <?php $bCtr=1; foreach ($section_buttons as $b) { 
            $btn = $b['button'];
            $button_style = $b['button_style'];
            $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
            $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
            $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
            if($btnName && $btnLink) { ?>
            <div class="button-wrap">
              <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="button-traditional <?php echo $button_style ?>"><span><?php echo $btnName ?></span></a>
            </div>
            <?php $bCtr++; } ?>
          <?php } ?>
        </div> 
      </div>
    </section>
  <?php } ?>

<?php } ?>