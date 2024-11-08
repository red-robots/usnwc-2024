<?php if( get_row_layout() == 'text_block' ) { ?>
  <?php  
  $text_block = get_sub_field('text_block');
  if($text_block) { ?>
  <section class="text-content">
    <div class="contentWrap">
      <?php echo anti_email_spam($text_block) ?>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>