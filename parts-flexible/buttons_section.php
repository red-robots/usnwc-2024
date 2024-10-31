<?php if( get_row_layout() == 'buttons_section' ) { 
  $buttons = get_sub_field('buttons');
  if($buttons) { ?>
  <div class="buttons-section-wrapper">
    <div class="wrapper">
      <div class="flexwrap">
        <?php foreach ($buttons as $b) { 
          $btn = $b['button'];
          $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
          $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
          $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
          if($btnName && $btnLink) { ?>
          <a href="<?php echo $btnLink ?>" class="button button-pill" target="<?php echo $btnTarget ?>"><?php echo $btnName ?></a>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php  } ?>
<?php  } ?>