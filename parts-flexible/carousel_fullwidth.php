<?php if( get_row_layout() == 'carousel_fullwidth' ) {  
  $has_intro = get_sub_field('title_and_description');
  $intro_title = get_sub_field('title');
  $intro_text = get_sub_field('description');
  $carousel_images = get_sub_field('carousel_images');
  $section_data_title = ($has_intro && $intro_title) ? ' data-section="'.$intro_title.'"':'';
  if($carousel_images) { ?>
    <section id="carousel_fullwidth-<?php echo $ctr ?>" class="carousel_fullwidth_section fullwidth-float-left"<?php echo $section_data_title ?>>
      <div class="full-wrapper">
        <?php if ($has_intro) { ?>
          <?php if ($intro_title || $intro_text) { ?>
            <div class="wrapper introtext-wrapper<?php echo ($intro_text) ? ' has-section-text':''?>">
              <?php if ($intro_title) { ?>
              <div class="titleWrapper"><h2 class="stitle"><?php echo $intro_title ?></h2></div>
              <?php } ?>
              <?php if ($intro_text) { ?>
              <div class="textWrapper"><?php echo anti_email_spam($intro_text) ?></div>
              <?php } ?>
            </div>
          <?php } ?>
        <?php } ?>

        <div class="carousel-images">
          <div class="carousel-center-loop owl-carousel owl-theme">
          <?php foreach ($carousel_images as $img) {  ?>
            <figure>
              <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['title']; ?>"  />
            </figure>
          <?php } ?>
          </div>
        </div>
      </div>
    </section>
  <?php  } ?>
<?php  } ?>