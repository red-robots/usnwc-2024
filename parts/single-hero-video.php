<?php if( isset($videoURL) && $videoURL ) { ?>
<section id="hero--single" class="hero--single-video">
    <?php
    $video_image = get_field('video_image');
    $parts = parse_url($videoURL);
    if( isset($parts['query']) ) {
      parse_str($parts['query'], $query);
    }

    //YOUTUBE
    if ( (strpos( strtolower($videoURL), 'youtube.com') !== false) || strpos( strtolower($videoURL), 'youtu.be') !== false ) { 
      $youtubeId = '';
      if(strpos( strtolower($videoURL), 'youtube.com') !== false) {
        $youtubeId = (isset($query['v']) && $query['v']) ? $query['v']:''; 
      }
      else if(strpos( strtolower($videoURL), 'youtu.be') !== false) {
        $youtubeId = trim($parts['path']);
        $youtubeId = ltrim($youtubeId, '/');
        $youtubeId = rtrim($youtubeId, '/');
      }

      if($youtubeId) {
        $embed_url = 'https://www.youtube.com/embed/'.$youtubeId.'?version=3&rel=0&loop=0&autoplay=1&mute=1'; 
        $mainImage = 'https://i.ytimg.com/vi/'.$youtubeId.'/maxresdefault.jpg';
        if($video_image) {
          $mainImage = $video_image['url'];
        }
        ?>
        <div class="outer-video-wrap">
          <div class="videoIframeDiv video-youtube video__youtube" style="background-image:url('<?php echo $mainImage?>');">
            <iframe class="videoIframe iframe-youtube" data-vid="youtube" src="<?php echo $embed_url; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
          </div>
        </div>
      <?php } ?>

  <?php } ?>

  <?php if( (strpos( strtolower($videoURL), 'vimeo.com') !== false) ) { 
    $video_json_url = 'https://vimeo.com/api/oembed.json?url=' . $videoURL;
    $vimeo_parts = explode("/",$videoURL);
    $parts = ($vimeo_parts && array_filter($vimeo_parts) ) ? array_filter($vimeo_parts) : '';
    $vimeoId = ($parts) ?  preg_replace('/\s+/', '', end($parts)) : '';
    $vimeoData = ($vimeoId) ? get_vimeo_data($vimeoId) : '';
    $data = json_decode( file_get_contents($video_json_url) );
    $vimeoImage = ($data) ? $data->thumbnail_url : '';
    if($video_image) {
      $vimeoImage = $video_image['url'];
    }
    ?>
    <div class="outer-video-wrap">
      <div class="videoIframeDiv video-vimeo video__vimeo" style="background-image:url('<?php echo $vimeoImage?>');">
        <iframe src="https://player.vimeo.com/video/<?php echo $vimeoId ?>?background=1&autoplay=1&loop=1" frameborder="0" allow="autoplay; fullscreen" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe>
      </div>
    </div>
  <?php } ?>

  <?php if( (strpos( strtolower($videoURL), 'mp4') !== false) ) { ?>
    <?php $videoImageUrl = ($video_image) ? $video_image['url'] : ''; ?>
    <div class="outer-video-wrap">
      <div class="videoIframeDiv">
        <?php if ($videoImageUrl) { ?>
          <video class="desktop" autoPlay loop muted playsinline poster="<?php echo $videoImageUrl; ?>">
        <?php } else { ?>
          <video class="desktop" autoPlay loop muted playsinline>
        <?php } ?>
          <source src="<?php echo $videoURL;?>" type="video/mp4">
        </video>
      </div>  
    </div>
  <?php } ?>

  <div class="banner-bottom" style="display:none;">
    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/banner-bottom.svg" alt="" aria-hidden="true" />
  </div>
</section>
<?php } ?>
