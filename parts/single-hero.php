<?php
$thumbnail_id = get_post_thumbnail_id();
$imageUrl = wp_get_attachment_image_url($thumbnail_id,'full'); 
$mobile_image = get_field('mobile_featured_image');
if($imageUrl) { ?>
<section id="hero--single">
  <figure>
    <img src="<?php echo $imageUrl ?>" alt="<?php echo get_the_title() ?>" class="desktop-feat-image" />
    <?php if ($mobile_image) { ?>
    <img src="<?php echo $mobile_image['url'] ?>" alt="" class="mobile-feat-image" />
    <?php } else { ?>
    <img src="<?php echo $imageUrl ?>" alt="<?php echo get_the_title() ?>" class="mobile-feat-image from-desktop-image" />
    <?php } ?>
  </figure>
  <div class="banner-bottom">
    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/banner-bottom.svg" alt="" aria-hidden="true" />
  </div>
</section>
<?php } ?>