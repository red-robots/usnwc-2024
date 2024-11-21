<?php if( get_row_layout() == 'sponsors' ) { ?>
  <?php  
  $section_title = get_sub_field('section_title');
  $section_text = get_sub_field('section_text');
  $sponsor_images = get_sub_field('sponsor_images');
  ?>
  <?php if ($section_title) { ?>
    <div id="sponsor-section--<?php echo sanitize_title($section_title) ?>" data-section="<?php echo $section_title ?>" class="text_and_image_block_section sponsors-section column-style column-style-<?php echo $ctr ?>">
  <?php } else { ?>
    <div class="column-style sponsors-section column-style-<?php echo $ctr ?>">
  <?php } ?>
  
    <?php if ( $section_title || ($sponsor_images) ) { ?>
    <div class="wrapper title-wrapper <?php echo ($section_text) ? ' has-section-text':' no-section-text'; ?>">
      <?php if ($section_title ) { ?>
      <div class="titlediv">
        <div class="shead-icon text-center">
          <h2 class="stitle"><?php echo $section_title ?></h2>
        </div>
      </div>
      <?php } ?>

      <?php if ($section_text) { ?>
      <div class="text-wrap"><?php echo anti_email_spam($section_text) ?></div>
      <?php } ?>
    </div>
    <?php } ?>

    <?php if ( $sponsor_images ) { ?>
    <div class="sponsor-images">
      <div class="flexwrap">
      <?php foreach ($sponsor_images as $s) { 
        $link = get_field('image_website', $s['ID']);
        ?>
        <figure>
          <?php if ($link) { ?>
          <a href="<?php echo $link ?>" target="_blank"><img src="<?php echo $s['url'] ?>" alt="<?php echo $s['title'] ?>"></a>
          <?php } else { ?>
            <img src="<?php echo $s['url'] ?>" alt="<?php echo $s['title'] ?>">
          <?php } ?>
        </figure>  
      <?php } ?>
      </div>
    </div>
    <?php } ?>


  </div>  
<?php  } ?>