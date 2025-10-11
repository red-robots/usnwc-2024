<?php if( get_row_layout() == 'fullwidth_video_alt' ) { ?>
  <?php  
  $html_anchor = get_sub_field('html_anchor');
  $video_url = get_sub_field('video_url');
  $video_thumbnail = get_sub_field('video_thumbnail');
  $video_text = get_sub_field('video_text');
  $text_alignment = get_sub_field('text_alignment');
  if($video_url) { ?>
  <section id="fullwidth-video-alt-<?php echo $ctr ?>" class="fullwidth_video_block fullwidth_video_alt text--<?php echo $text_alignment ?>">
    <?php if ($html_anchor) { ?>
    <div id="<?php echo $html_anchor ?>" class="html-anchor"></div>
    <?php } ?>

    <?php if ($video_url) { ?>
      
      <?php if ($video_thumbnail) { ?>
      <div class="videoThumbnail">
        <figure>
          <a href="<?php echo $video_url ?>" data-fancybox data-fancybox-type="iframe">
            <span class="play"><i class="fa-regular fa-circle-play"></i></span>
            <span class="image" style="background-image:url('<?php echo $video_thumbnail['url'] ?>')"></span>
            <?php if ($video_text) { ?>
            <div class="videoText">
              <?php echo anti_email_spam($video_text) ?>
            </div>
            <?php } ?>
          </a>
        </figure>
      </div>
      <?php } ?>

    <?php } ?>
  </section>
  <?php  } ?>
<?php  } ?>