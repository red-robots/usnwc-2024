<?php if( get_row_layout() == 'fullwidth_image' ) { 
  $type = get_sub_field('image_or_video');  ?>

  <?php if($type=='video') {  
    
    $videoURL = get_sub_field('video'); 
    $video_status = get_sub_field('video_status'); 
    $videoThumb = get_sub_field('video_placeholder'); 
    if($videoURL) { 
      $parts = parse_url($videoURL);
      parse_str($parts['query'], $query); ?>
      <section id="fullwidth-video--<?php echo $ctr ?>" class="fullwidth-video-section">
        <?php if ($video_status=='auto') { ?>
          
          <div class="video-container">
            <?php /* YOUTUBE VIDEO */ ?>
            <?php 
            $is__youtube = array();
            $is__youtube[] = (strpos( strtolower($videoURL), 'youtu.be') !== false) ? true : false;
            $is__youtube[] = (strpos( strtolower($videoURL), 'youtube.com') !== false) ? true : false;
            $it_is_youtube = ( $is__youtube && array_filter($is__youtube) ) ? array_filter($is__youtube) : '';

            if ( $it_is_youtube ) {
              $youtubeId = '';

              /* if iframe */
              if (strpos( strtolower($videoURL), 'youtube.com/embed') !== false) {
                $parts = extractURLFromString($videoURL);
                $youtubeId = basename($parts);
              } else if(strpos( strtolower($videoURL), 'youtube.com')) {
                $youtubeId = (isset($query['v']) && $query['v']) ? $query['v']:''; 
              } else if(strpos( strtolower($videoURL), 'youtu.be')) {
                $v_parts = explode('youtu.be/',$videoURL);
                $vpart1 = explode('?',$v_parts[1]);
                $youtubeId = $vpart1[0];
              }

              if($youtubeId) {
                $embed_url = 'https://www.youtube.com/embed/'.$youtubeId.'?version=3&rel=0&loop=0'; 
                $mainImage = 'https://i.ytimg.com/vi/'.$youtubeId.'/maxresdefault.jpg'
                ?>
                <iframe
                  width="560"
                  height="315"
                  src="<?php echo $embed_url; ?>"
                  frameborder="0"
                  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
              ></iframe>

              <?php }  ?>
            <?php }  ?>

            <?php /* VIMEO VIDEO */ ?>
            <?php if( strpos( strtolower($videoURL), 'vimeo.com') !== false ) { 
              $vimeo_parts = explode("/",$videoURL);
              $parts = ($vimeo_parts && array_filter($vimeo_parts) ) ? array_filter($vimeo_parts) : '';
              $vimeoId = ($parts) ?  preg_replace('/\s+/', '', end($parts)) : '';
              $vimeoData = ($vimeoId) ? get_vimeo_data($vimeoId) : '';
              $data = json_decode( file_get_contents( 'https://vimeo.com/api/oembed.json?url=' . $videoURL ) );
              $vimeoImage = ($data) ? $data->thumbnail_url : '';
              ?>
              <?php if ($videoThumb) { ?>
              <span class="video-placeholder has-image" style="background-image:url('<?php echo $videoThumb['url'] ?>')"></span>    
              <?php } ?>  
              <iframe class="videoIframe iframe-vimeo" data-vid="vimeo" src="https://player.vimeo.com/video/<?php echo $vimeoId?>" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
            <?php } ?>
          </div>
          
        <?php } else { ?>
        <figure class="<?php echo ($videoThumb) ? 'video-has-placeholder':'video-no-placeholder' ?>">
          <a href="<?php echo $videoURL ?>" data-fancybox="featured-video" aria-label="Video Link" class="videopop">
            <span class="player"></span>
            <?php if ($videoThumb) { ?>
            <span class="video-placeholder has-image" style="background-image:url('<?php echo $videoThumb['url'] ?>')"></span>    
            <?php } else { ?>  
            <span class="video-placeholder no-image"></span>    
            <?php  } ?>
          </a>
        </figure>
        <?php } ?>
      </section>
    <?php  } ?>

  <?php  } else  { ?>

    <?php 
    $image = get_sub_field('image'); 
    if($image) { ?>
    <section id="fullwidth-image--<?php echo $ctr ?>" class="fullwidth-image-section">
      <figure>
        <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>" />
      </figure>
    </section>
    <?php  } ?>

  <?php  } ?>

<?php  } ?>