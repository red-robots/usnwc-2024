 <?php if( get_row_layout() == 'gallery' ) { ?>
  <?php  
  $section_gallery = get_sub_field('images');
  
  if($section_gallery) { 
     $count = count($section_gallery);
     $section_class = ($count>1) ? ' multiple':' single';
     $section_class .= ' count-'.$count;
    ?>
  <section class="section section--<?php echo get_row_layout() ?>">
    <div class="content-gallery<?php echo $section_class ?>">
      <?php $imgctr=1; foreach ($section_gallery as $s) { 
        $image = $s['image'];
        $logo_overlay = $s['logo_overlay'];
        $details = $s['details'];
        if($image) { ?>
        <figure class="img<?php echo $imgctr ?>">
          <span class="span-image" style="background-image:url('<?php echo $image['url'] ?>')"></span>
          <?php echo bellaworks_image( $image, 'large', array( 'alt' => $image['title'], 'class' => 'image-block' ) ); ?>
          <?php if ($logo_overlay) { ?>
           <?php echo bellaworks_image( $logo_overlay, 'large', array( 'alt' => $logo_overlay['title'], 'class' => 'logo-overlay' ) ); ?> 
          <?php } ?>
          <?php if ($details) { ?>
          <figcaption>
            <div class="image-text">
              <?php echo anti_email_spam($details); ?>
            </div>
          </figcaption>
          <?php } ?>
        </figure>
        <?php $imgctr++; } ?>
      <?php } ?>
    </div>
  </section>
  <?php } ?>

<?php } ?>