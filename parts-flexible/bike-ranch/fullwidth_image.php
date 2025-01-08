 <?php if( get_row_layout() == 'fullwidth_image' ) {  

  $featured_image = get_sub_field('image');
  $image_title = get_sub_field('title');
  $image_text = get_sub_field('small_text_below_title');

  if($featured_image) { ?>
    <section class="section section--<?php echo get_row_layout() ?>">
      <div class="content-inner-image">
        <figure>
          <img src="<?php echo $featured_image['url'] ?>" alt="<?php echo $featured_image['title'] ?>">
          <?php if ($image_title || $image_text) { ?>
          <figcaption>
            <div class="image-text">
              <?php if ($image_title) { ?>
                <h2 class="title-large"><?php echo $image_title ?></h2>
              <?php } ?>
              <?php if ($image_text) { ?>
                <p class="small-text"><?php echo $image_text ?></p>
              <?php } ?>
            </div>
          </figcaption>
          <?php } ?>
        </figure>
      </div>
    </section>
  <?php } ?>

<?php } ?>