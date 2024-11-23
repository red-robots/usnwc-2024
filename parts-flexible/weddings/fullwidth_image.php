<?php if ( get_row_layout() == 'fullwidth_image' ) {  
  $image = get_sub_field('image'); 
  $text = get_sub_field('text'); 
  $add_text_overlay = get_sub_field('add_text_overlay'); 
  $hasText = ($add_text_overlay && $text) ? 'has-text-overlay':'only-image';
  if($image) { ?>
  <div id="section-fullwidth_image-<?php echo $ctr ?>" class="repeatable-block section  section-fullwidth_image <?php echo $hasText ?>">
    <div class="section-inner">
      <figure>
        <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>">
        <?php if ($add_text_overlay && $text) { ?>
        <div class="text-overlay">
          <div class="text-inner">
            <figcaption>
              <?php echo $text ?>
            </figcaption>
          </div>
        </div>  
        <?php } ?>
      </figure>
    </div>
  </div>
  <?php } ?>
<?php } 