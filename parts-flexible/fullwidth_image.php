<?php if( get_row_layout() == 'fullwidth_image' ) { 
  $image = get_sub_field('image'); 
  if($image) { ?>
  <section id="fullwidth-image--<?php echo $ctr ?>" class="fullwidth-image-section">
    <figure>
      <img src="<?php echo $image['url'] ?>" alt="" />
    </figure>
  </section>
  <?php  } ?>
<?php  } ?>