<?php if( get_row_layout() == 'gallery' ) { ?>
  <?php  
  $gallery = get_sub_field('gallery');
  if($gallery) { $count = count($gallery); ?>
  <section class="text-content-gallery">
    <div class="gallery-wrapper count-<?php echo $count ?>">
      <?php foreach ($gallery as $g) { ?>
      <div class="imagewrap">
        <figure><img src="<?php echo $g['url'] ?>" alt="<?php echo $g['title'] ?>"></figure>
      </div>
      <?php } ?>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>