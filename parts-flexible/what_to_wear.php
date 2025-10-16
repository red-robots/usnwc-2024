<?php if( get_row_layout() == 'what_to_wear' ) { 
  $section_title = get_sub_field('section_title');
  $section_text = get_sub_field('section_text');
  $default_image = get_sub_field('default_image');
  $what_to_wear_options = get_sub_field('what_to_wear_options');
  if($what_to_wear_options) { ?>
  <section id="section-whattowear_<?php echo $ctr ?>" data-section="What to wear" class="mscol section-whattowear-repeatable repeatable repeatable_<?php echo get_row_layout() ?>">
    <div class="wrapper">
      <div class="flexwrap">
        <?php if ($default_image) { ?>
        <div class="fxcol model">
          <figure class="model-image">
            <img src="<?php echo $default_image['url'] ?>" alt="" class="default-model" />
          </figure>
        </div>
        <?php } ?>

        <?php if ($what_to_wear_options) { ?>
        <div class="fxcol options">
          <div class="inside">
            <?php if ($section_title || $section_text) { ?>
            <div class="section-intro">
              <?php if ($section_title) { ?>
                <h2 class="sectionTitle"><?php echo $section_title ?></h2>
              <?php } ?>
              <?php if ($section_text) { ?>
                <div class="sectionText"><?php echo anti_email_spam($section_text); ?></div>
              <?php } ?>
            </div>
            <?php } ?>

            <ul class="whatToWearAccordion section-accordion">
            <?php $mx=1; foreach ($what_to_wear_options as $w) { 
              $w_image = $w['image'];
              $w_image_src = ($w_image) ? $w_image['url'] : '';
              $w_title = $w['title'];
              $w_description = $w['description'];
              if($w_title && $w_description) { ?>
              <li class="item">
                <div class="accordion-title" data-image="<?php echo $w_image_src ?>" tabindex="0" role="button" aria-expanded="false"><?php echo $w_title ?><span class="arrow"></span></div>
                <div class="accordion-details"><?php echo anti_email_spam($w_description); ?></div>
              </li>
              <?php } ?>
            <?php $mx++; } ?>
            </ul>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>