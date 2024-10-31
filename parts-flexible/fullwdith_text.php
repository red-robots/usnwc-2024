<?php if( get_row_layout() == 'fullwdith_text' ) { 
  $title = get_sub_field('title');
  $text = get_sub_field('text');
  if($title || $text) { ?>
  <section id="fullwdith--text-<?php echo $ctr?>" class="fullwdith_text_section">
    <div class="wrapper">
      <?php if ($title) { ?>
        <h2 class="stitle"><?php echo $title ?></h2>
      <?php } ?>
      
      <?php if ($text) { ?>
        <div class="text-wrap"><?php echo anti_email_spam($text) ?></div>
      <?php } ?>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>