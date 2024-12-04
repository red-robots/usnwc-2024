<?php if( get_row_layout() == 'gallery_featured_posts' ) { ?>

  <?php 
  $section_title = get_sub_field('section_title');
  $section_posts = get_sub_field('section_posts');
  if($section_posts) { ?>
  <div id="section-gallery_featured_posts-<?php echo $ctr ?>" class="gallery_featured_posts section-upcoming_events">
    <div class="wrapper">
      <?php if ($section_title) { ?>
      <h2 class="section-title"><?php echo $section_title ?></h2>
      <?php } ?>
      <div class="flexwrap">
        <?php foreach ($section_posts as $sp) { 
          $post = $sp['post'];
          $button = $sp['button_text'];
          $buttonTitle = ($button) ? $button : 'Learn More';
          if($post) {
            $pid = $post->ID;
            $post_title = $post->post_title;
            $pagelink = get_permalink($pid);
            $thumbId = get_post_thumbnail_id($pid); 
            $featImg = wp_get_attachment_image_src($thumbId,'full');
            $imageUrl = '';
            if($featImg) {
              $imageUrl = $featImg[0];
            } else {
              $custom_image = get_field('full_image', $pid);
              if($custom_image) {
                $imageUrl = (isset($custom_image['url'])) ? $custom_image['url'] : '';
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