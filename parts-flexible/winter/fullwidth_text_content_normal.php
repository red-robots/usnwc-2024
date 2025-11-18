<?php if( get_row_layout() == 'fullwidth_text_content_normal' ) { ?>
  <?php  
  $html_anchor = get_sub_field('html_anchor');
  $content = get_sub_field('content');
  if($content) { ?>
  <section id="fullwidth_text_content_<?php echo $ctr ?>" class="fullwidth_text_content">
    <?php if ($html_anchor) { ?>
    <div id="<?php echo $html_anchor ?>" class="html-anchor"></div>
    <?php } ?>
    <div class="content-container">
      <?php echo anti_email_spam($content); ?>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>