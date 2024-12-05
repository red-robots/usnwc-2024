<?php if( get_row_layout() == 'gallery_featured_posts' ) { ?>

  <?php 
  $section_title = get_sub_field('section_title');
  $section_posts = get_sub_field('section_posts');
  $data_section = ($section_title) ? ' data-section="'.$section_title.'"':'';
  if($section_posts) { ?>
  <div id="section-gallery_featured_posts-<?php echo $ctr ?>"<?php echo $data_section ?> class="gallery_featured_posts fullwidth-float-left section-upcoming_events">
    <div class="wrapper">
      <?php if ($section_title) { ?>
      <h2 class="section-title"><?php echo $section_title ?></h2>
      <?php } ?>
      <div class="flexwrap">
        <?php foreach ($section_posts as $sp) { ?>
          <?php 
            $entry = $sp['post'];
            $button = $sp['button_text'];
            $buttonTitle = ($button) ? $button : 'Learn More';
            if($entry) { 
              $pid = $entry->ID;
              $post_title = $entry->post_title;
              $pagelink = get_permalink($pid);
              $thumbId = get_post_thumbnail_id($pid); 
              $featImg = wp_get_attachment_image_src($thumbId,'full');
              $imageUrl = '';
              $hero_image = get_field('flexslider_banner', $pid);
              if($featImg) {
                $imageUrl = $featImg[0];
              } else {
                if($hero_image) {
                  if( isset($hero_image[0]) ) {
                    $hero = $hero_image[0];
                    if( isset($hero['image']) && $hero['image'] ) {
                      $imageUrl = $hero['image']['url'];
                    }
                  } 
                }
              }
              ?>
              <div class="infobox">
                <div class="inside">
                  <figure class="event-image <?php echo ($imageUrl) ? 'has-image':'no-image'; ?>">
                    <?php if ($imageUrl) { ?>
                    <img src="<?php echo $imageUrl ?>" alt="" />
                    <?php } ?>
                  </figure>
                  <h3 class="event-title"><?php echo $post_title ?></h3>
                  <div class="button-block">
                    <a href="<?php echo $pagelink ?>" class="button-pill"><?php echo $buttonTitle ?></a>
                  </div>
                </div>
              </div>
            <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>

<?php } ?>
