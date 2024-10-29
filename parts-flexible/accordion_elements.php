<?php if( get_row_layout() == 'accordion_elements' ) { ?>
  <?php  
  $accordions = get_sub_field('accordion_content');
  $section_title = get_sub_field('section_title');
  $section_text = get_sub_field('section_text');
  $has_section_text = get_sub_field('has_section_text');
  $data_section = ($section_title) ? ' data-section="'.$section_title.'"':'';
  if($accordions || $section_title) { ?>
  <div id="accordion-elements-<?php echo $ctr ?>" class="fullwidth-float-left accordion_elements_section"<?php echo $data_section ?>>
    <div class="title-wrapper no-border text-center <?php echo ($has_section_text && $section_text) ? ' has-section-text':' no-section-text'; ?>">
      <div class="midwrapper">
        <div class="inner">
          <?php if ($section_title ) { ?>
          <h2 class="section-title"><?php echo $section_title ?></h2>
          <?php } ?>
          <?php if ($has_section_text && $section_text) { ?>
          <div class="section-text"><?php echo anti_email_spam($section_text) ?></div>
          <?php } ?>
        </div>
      </div>
    </div>

    <?php if ($accordions) { ?>
    <div class="accordions-wrapper">
      <div class="midwrapper">
        <ul class="accordion">
        <?php foreach ($accordions as $a) { 
          $accordion_title = $a['title'];
          $accordion_details = $a['details'];
          if ($accordion_title && $accordion_details) { ?>
          <li class="accordion-item">
            <button class="accordion-handle" aria-expanded="false"><?php echo $accordion_title ?></button>
            <div class="accordion-details"><?php echo anti_email_spam($accordion_details) ?></div>
          </li>
          <?php } ?>    
        <?php } ?>
        </ul>
      </div>
    </div>
    <?php } ?>

  </div>
  <?php  } ?>

<?php  } ?>