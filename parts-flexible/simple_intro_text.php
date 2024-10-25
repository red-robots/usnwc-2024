<?php if( get_row_layout() == 'simple_intro_text' ) { ?>

  <?php 
  $section_title = get_sub_field('title');
  $section_text = get_sub_field('intro');
  $data_section = ($section_title) ? ' data-section="'.$section_title.'"':'';
  if( $section_title || $section_text ) { ?>
  <div id="simple-intro-text-<?php echo $ctr ?>" class="fullwidth-float-left simple_intro_text_section"<?php echo $data_section ?>>
    <div class="midwrapper">
      <?php if ($section_title ) { ?>
        <div class="title-wrapper">
          <div class="wrapper narrow">
            <h2 class="section-title"><?php echo $section_title ?></h2>
          </div>
        </div>
      <?php } ?>
        <?php if ($section_text) { ?>
        <div class="section-text"><?php echo anti_email_spam($section_text) ?></div>
        <?php } ?>
    </div>
  </div>
  <?php } ?>

<?php } ?>