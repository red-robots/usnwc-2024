<?php 

	$video_image = get_field('video_image');
	$hero_image = get_field('hero_image');
	$hero_image_mobile = get_field('hero_image_mobile');
	$hero_video = get_field('hero_video');
	$hero_video_mobile = get_field('hero_video_mobile');
	$hero_title = get_field('hero_title');
	$hero_description = get_field('hero_description');
	$hero_cta_links = get_field('hero_cta_links');
	$hero_branding = get_field('hero_branding');

	// echo '<pre>';
	// print_r($hero_cta_links);

 ?>
<section class="homepage-hero">
	<?php if( $video_image == 'Image' ) { ?>

		<div class="home-info">
			<div class="info">
				<?php if( $hero_title ) { ?>
					<h1><?php echo $hero_title; ?></h1>
				<?php } ?>
				<?php if( $hero_description ) { ?>
					<p><?php echo $hero_description; ?></p>
				<?php } ?>
				<?php if( $hero_cta_links ) { ?>
					<?php foreach( $hero_cta_links as $link ) { ?>
						<div class="cta">
							<a href="<?php echo $link['link']['url']; ?>"><?php echo $link['link']['title']; ?></a>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
      <?php if ( isset($hero_branding['url']) ) { ?>
			<div class="branding">
				<img src="<?php echo $hero_branding['url']; ?>"  alt="<?php echo $hero_branding['alt']; ?>">
			</div>
      <?php } ?>
		</div>

		<?php if( $hero_image ) { ?>
			<div class="img-holder desktop" style="background-image: url(<?php echo $hero_image['url']; ?>);"></div>
		<?php } ?>
		<?php if( $hero_image_mobile ) { ?>
			<div class="img-holder mobile" style="background-image: url(<?php echo $hero_image_mobile['url']; ?>);"></div>
		<?php } ?>
	<?php } elseif( $video_image == 'Video' ) { ?>
		<?php if( $hero_image ) { ?>
      <?php if (isset($hero_video) && $hero_video) { 
        $heroVideoUrl = ( is_array($hero_video) && ( isset($hero_video['url']) && $hero_video['url'] ) ) ? $hero_video['url'] : ''; 
        if($heroVideoUrl) { ?>
        <?php if ( isset($placeThumb['url']) && $placeThumb['url'] ) { ?>
        <video class="desktop" autoPlay loop muted playsinline  poster="<?php echo $placeThumb['url']; ?>">
        <?php } else { ?>
        <video class="desktop" autoPlay loop muted playsinline>
        <?php } ?>
  				<source src="<?php echo $heroVideoUrl; ?>" type="video/mp4">
  			</video>
        <?php } ?>
      <?php } ?>
		<?php } ?>
		<?php if( $hero_image_mobile ) { 

      $hero_video_mobile_url = ( is_array($hero_video_mobile) && ( isset($hero_video_mobile['url']) && $hero_video_mobile['url'] ) ) ? $hero_video_mobile['url'] : ''; 
      if( $hero_video_mobile_url ) { ?>
        <?php if ( isset($placeThumb['url']) && $placeThumb['url'] ) { ?>
        <video class="mobile" autoPlay loop muted playsinline  poster="<?php echo $placeThumb['url']; ?>">  
        <?php } else { ?>
        <video class="mobile" autoPlay loop muted playsinline>
        <?php } ?>
  				<source src="<?php echo $hero_video_mobile_url; ?>" type="video/mp4">
  			</video>
      <?php } ?>
		<?php } ?>
	<?php } else { echo 'No Media'; } ?>
</section>