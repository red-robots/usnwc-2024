<?php if( get_row_layout() == 'fullwidth_video' ) { ?>
  <?php  
  //$video_type = get_sub_field('video_type');
  $video_url = get_sub_field('video_url');
  $video_text = get_sub_field('video_text');
  $text_alignment = get_sub_field('text_alignment');
  if($video_url) { ?>
  <section id="fullwidth_video_<?php echo $ctr ?>" class="fullwidth_video_block text--<?php echo $text_alignment ?>">
    <?php if ($video_text) { ?>
    <div class="textOverlay">
      <div class="wrapper"><?php echo anti_email_spam($video_text) ?></div>
    </div>
    <?php } ?>

    <?php if ($video_url) { ?>
      
      <?php 
      $youtubeId = extractYoutubeId($video_url);
      if($youtubeId) { 
        $embed_url = 'https://www.youtube.com/embed/'.$youtubeId.'?version=3&rel=0&loop=0&controls=0&autoplay=1&mute=1'; 
        ?>
        <div class="videoFrame youtube">
          <iframe class="iframe-youtube" data-vid="youtube" src="<?php echo $embed_url; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
      <?php } else { 

        $vimeoId = extractVimdeoId($video_url);
        if($vimeoId) { 
          $embed_vimeo_url = 'https://player.vimeo.com/video/'.$vimeoId.'?autoplay=1&loop=1&autopause=0&background=1';
          ?>
        <div class="videoFrame vimeo">
          <iframe src="<?php echo $embed_vimeo_url; ?>" class="iframe-vimeo" width="640" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
        </div>
        <?php } ?>
      
      <?php } ?>

    <?php } ?>
  </section>
  <?php  } ?>
<?php  } ?>