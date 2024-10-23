<?php if( get_row_layout() == 'fullwidth_red_bar' ) { 
  $red_bar_text = get_sub_field('red_bar_text');
  $btn = get_sub_field('button');
  $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
  $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
  $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
  ?>
  <section id="fullwidth-red-bar-<?php echo $ctr ?>" class="fullwidth-red-bar-section">
    <div class="wrapper">
      <?php if ($red_bar_text) { ?>
      <div class="introWrapper"><?php echo anti_email_spam($red_bar_text) ?></div>
      <?php } ?>
      <?php if ($btnName && $btnUrl) { ?>
      <div class="buttonBlock">
        <a href="<?php echo $btnUrl ?>" target="<?php echo $btnTarget ?>" class="button button-white"><?php echo $btnName ?></a>
      </div>
      <?php } ?>
    </div>
  </section>
<?php  } ?>