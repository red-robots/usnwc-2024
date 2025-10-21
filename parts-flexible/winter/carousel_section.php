<?php if( get_row_layout() == 'carousel_section' ) { ?>
  <?php  
  $html_anchor = get_sub_field('html_anchor');
  $section_title = get_sub_field('section_title');
  $text_content = get_sub_field('text_content');
  $carousel_items = get_sub_field('carousel_items');
  $video_embed = get_sub_field('video_embed');
  if($text_content || $carousel_items || $video_embed) { ?>
  <section id="<?php echo get_row_layout() ?>_<?php echo $ctr ?>" class="repeatable repeatable_<?php echo get_row_layout() ?>">
    <?php if ($html_anchor) { ?>
    <div id="<?php echo $html_anchor ?>" class="html-anchor"></div>
    <?php } ?>
    <div class="section-inner">

      <?php if ($section_title) { ?>
      <div class="page--title--wrapper">
        <div class="wrapper">
          <h2 class="h2"><?php echo $section_title ?></h2>
        </div>
      </div>
      <?php } ?>


      <?php if ($carousel_items) { ?>
      <div class="repeater-carousel-section">
        <div class="custom-nav-buttons"></div>
        <div class="wrapper">
          <div class="carousel-slider owl-carousel">
            <?php foreach ($carousel_items as $c) { 
              $title = $c['title'];
              $category = $c['category'];
              $link = $c['link'];
              $image = $c['image'];
              $image_link = ( isset($link['url']) && $link['url'] ) ? $link['url'] : '';
              $image_name = ( isset($link['title']) && $link['title'] ) ? $link['title'] : '';
              $image_target = ( isset($link['target']) && $link['target'] ) ? $link['target'] : '';
              $has_link = ($image_link) ? 'has-link':'no-link';
              $link_open = '';
              $link_close = '';
              if($image_link) {
                $link_open = '<a href="'.$image_link.'" target="'.$image_target.'" class="imagelink">';
                $link_close = '</a>';
              }
              if($image) { ?>
              <div class="item">
                <figure class="<?php echo $has_link ?>">
                  <?php echo $link_open ?>
                  <?php if ($category) { ?><span class="category"><?php echo anti_email_spam($category); ?></span><?php } ?>
                  <?php if ($title) { ?><span class="title"><?php echo anti_email_spam($title); ?></span><?php } ?>
                  <div class="image"><img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>"></div>
                  <?php echo $link_close ?>
                </figure>
              </div>
              <?php } ?>
            <?php } ?>
          </div>
        </div>
      </div>
      <?php } ?>

      <?php if ($text_content) { ?>
      <div class="section-intro section-bottom-text">
        <div class="wrapper">
          <?php echo anti_email_spam($text_content); ?>
        </div>
      </div>
      <?php } ?>

      <?php if ($video_embed) { ?>
      <div class="video-section">
        <div class="wrapper">
          <div class="videoWrap">
            <?php echo $video_embed ?>
            <img src="<?php echo get_stylesheet_directory_uri() ?>/images/video-helper.png" class="video-resizer" alt="" aria-hidden="true" />
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>