<?php
$postTypes = ['dining'];
if ( is_single() && in_array(get_post_type(), $postTypes) ) {
  $thumbnail_id = get_post_thumbnail_id();
  $imageUrl = wp_get_attachment_image_url($thumbnail_id,'full'); 
  if($imageUrl) { ?>
  <section id="hero--single">
    <figure>
      <img src="<?php echo $imageUrl ?>" alt="<?php echo get_the_title() ?>" />
    </figure>
    <div class="banner-bottom">
      <img src="<?php echo get_stylesheet_directory_uri() ?>/images/banner-bottom.svg" alt="" aria-hidden="true" />
    </div>
  </section>
  <?php } ?>
<?php } ?>