<?php if ( get_row_layout() == 'two_column_row' ) { 
  $text = get_sub_field('text_column'); 
  $images = get_sub_field('images'); 
  $gallery_type = get_sub_field('gallery_type'); 
  $image_position = get_sub_field('image_position'); 
  $columnClass = ( $text && $images ) ? 'half':'full';
  if($images) {
    if($gallery_type=='normal' && count($images)>3 ) {
      foreach($images as $k=>$img) {
        if($k>3) {
          unset($images[$k]);
        }
      }
    }
  }
  $alt_class = 'odd';
  if($text || $images) { 
    if($twoColumnItems && ( end($twoColumnItems) % 2==0 ) ) {
      $alt_class = 'even';
    }
    $twoColumnItems[] = $ctr; 
    if($image_position) {
      $position = ($image_position==1) ? 'left':'right';
      $alt_class .= ' image-position-'.$position;
    }
    ?>
    <div id="section-two_column_row-<?php echo $ctr ?>" class="repeatable-block section  section-two_column_row <?php echo $alt_class ?>">
      <div class="section-inner">
        <div class="flexwrap <?php echo $columnClass ?>">
          <?php if ($text) { ?>
          <div class="textBlock">
            <div class="inside"><?php echo $text ?></div>
          </div>
          <?php } ?>

          <?php if ($images) { ?>
          <div class="imageBlock <?php echo $gallery_type ?> count<?php echo count($images) ?>">
            <div class="gallery-content">
              <?php if ($gallery_type=='slideshow') { ?>
              <div id="gallerySlider<?php echo $ctr ?>" class="swiper gallerySlider">
                <div class="swiper-wrapper">
                  <?php foreach ($images as $img) { ?>
                  <div class="swiper-slide">
                    <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
                  </div>
                  <?php } ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
              </div>

              <?php } else { ?>
                <?php foreach ($images as $img) { ?>
                <figure>
                  <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
                </figure>
                <?php } ?>
              <?php } ?>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  <?php } ?>
<?php } 