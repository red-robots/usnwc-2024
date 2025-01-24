<?php
/**
 * Template Name: Visit
 */
$placeholder = THEMEURI . 'images/rectangle.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
get_header(); ?>

<div id="primary" class="content-area-full stories-page <?php echo $has_banner ?>">
	<main id="main" class="site-main fw-left" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
			if( get_the_content() || get_the_title() ) { ?>
			<section class="text-centered-section">
				<div class="wrapper text-center">
					<div class="intro-text">
						<h1><?php the_title(); ?></h1>
						<?php the_content(); ?>
					</div>
				</div>
			</section>
			<?php } ?>
		<?php endwhile; ?>


		<section class="visit flexible-image-cards flexible-image-video-cards">
			<?php

			// Check value exists.
			if( have_rows('layouts') ):

			    // Loop through rows.
			    while ( have_rows('layouts') ) : the_row();

			        // Case: Paragraph layout.
			        if( get_row_layout() == 'tiles' ):
			          $tile = get_sub_field('tile'); ?>
                <?php if($tile) { ?>
                <div class="tileWrapper">
    	            <?php foreach( $tile as $t ) {
  		            $title = $t['title'];
                  $hero_type = $t['image_or_video'];
  		            $image = $t['image'];
                  $videoURL = $t['video_url'];
                  $videoThumb = $t['video_image'];
  		            $description = $t['description'];
  		            $link = $t['link'];
                  $buttons = $t['buttons'];
  		            $span = $t['span'];
                  $span .= ($description) ? ' has-description':' no-description';
                  if($image || $video_url) { ?>
      							<div class="tile <?php echo $span ?>">
                      <figure>
                        <div class="image-wrapper feature--<?php echo $hero_type ?>">

                          <?php if ($hero_type=='video') { ?>

                            <?php if($videoURL) {
                              $parts = parse_url($videoURL);
                              parse_str($parts['query'], $query);
                              ?>

                              <div class="video-container">
                              <?php /* YOUTUBE VIDEO */ ?>
                              <?php if (strpos( strtolower($videoURL), 'youtube.com') !== false) {
                                $youtubeId = '';

                                /* if iframe */
                                if (strpos( strtolower($videoURL), 'youtube.com/embed') !== false) {
                                  $parts = extractURLFromString($videoURL);
                                  $youtubeId = basename($parts);
                                } else {
                                  $youtubeId = (isset($query['v']) && $query['v']) ? $query['v']:''; 
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
                                <?php if ($videoThumb) { ?>
                                <span class="video-placeholder" style="background-image:url('<?php echo $videoThumb['url'] ?>')"></span>    
                                <?php } ?>  
                              </div>
                            <?php } ?>

                          <?php } else { ?>
                            
                            <?php if ($image) { ?>
                              
                              <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                              <?php if ($title) { ?>
                              <h2><?php echo $title; ?></h2>
                              <?php } ?>

                            <?php } ?>

                          <?php } ?>
                       
                        </div>

                        <?php if ($description || $buttons) { ?>
                        <figcaption>
                          <?php if ($description) { ?>
                          <div class="text"><?php echo $description; ?></div>
                          <?php } ?>

                          <?php if ($buttons) { $countButtons = count($buttons); ?>
                          <div class="buttons buttons-count-<?php echo $countButtons ?>">
                            <?php 
                            foreach ($buttons as $b) { 
                              $button_style = $b['button_style'];
                              $btn = $b['link'];
                              $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
                              $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
                              $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
                              if ($btnName && $btnLink) { ?>
                                <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="button--<?php echo $button_style ?>"><span><?php echo $btnName ?></span></a>
                              <?php } ?>
                            <?php } ?>
                          </div>
                          <?php } ?>
                        </figcaption>
                        <?php } ?>
                      </figure>
      							</div>
      			        <?php } ?>
                  <?php } ?>
                 </div> 
                <?php } ?>
			        <?php 

			        // Two-Column Section
			        elseif( get_row_layout() == 'two_column_layout' ): 
                $content_type = get_sub_field('content_type');
                $image = get_sub_field('image');
                $iframe = get_sub_field('iframe');
                $text_position = get_sub_field('text_position');
                $column2_content = get_sub_field('text_content');
                $column1_content = ''; 
                ?>
                <?php if($content_type=='image') {
                  ob_start();
                  if($image) { ?>
                  <div class="flexcol image">
                    <figure>
                      <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>" />
                    </figure>
                  </div>  
                  <?php  
                  $column1_content = ob_get_contents();
                  ob_end_clean();
                  }
                } else if( $content_type=='map') {
                  ob_start();
                  if($iframe) { ?>
                  <div class="flexcol iframe">
                    <div class="map"><?php echo $iframe ?></div>
                  </div>  
                  <?php  
                  $column1_content = ob_get_contents();
                  ob_end_clean();
                  }
                } 

                  $section_class = ($column1_content && $column2_content) ? 'twocol':'full';
                  $section_class .= ($text_position) ? ' text--' . $text_position : '';
                ?>

                <div class="two-column-layout-vist <?php echo $section_class ?>">
                  <div class="flex-wrapper">
                    <?php if ($column1_content) { ?>
                    <?php echo $column1_content ?>
                    <?php } ?>

                    <?php if ($column2_content) { ?>
                    <div class="flexcol textwrap">
                      <div class="inner">
                        <?php echo anti_email_spam($column2_content) ?>
                      </div>
                    </div>
                    <?php } ?>
                  </div>
                </div>

              <?php
              // Text Block
              elseif( get_row_layout() == 'text_block' ): 
                $title = get_sub_field('title');
                $description = get_sub_field('description');
                $text_alignment = get_sub_field('text_alignment');
                if( $title || $description ) { ?>
                <div class="text-block-section text-block-visit text--<?php echo $text_alignment ?>">
                  <div class="text-block-inner">
                    <?php if ($title) { ?>
                      <h2 class="stitle"><?php echo $title ?></h2>
                    <?php } ?>
                    <?php if ($description) { ?>
                      <div class="description"><?php echo anti_email_spam($description) ?></div>
                    <?php } ?>
                  </div>
                </div>
                <?php } ?>

              <?php
              // Cards Row (max 4 columns)
              elseif( get_row_layout() == 'cards_section' ): 
                $cards = get_sub_field('cards');
                if($cards) { ?>
                <div class="cards-row-section cards-count-<?php echo count($cards) ?>">
                  <div class="cards-inner">
                    <?php foreach ($cards as $c) { 
                      $card_image = $c['card_image'];
                      $card_title = $c['card_title'];
                      $card_subtitle = $c['card_subtitle'];
                      $card_button = $c['card_button'];
                      $btnName = (isset($card_button['title']) && $card_button['title']) ? $card_button['title'] : '';
                      $btnLink = (isset($card_button['url']) && $card_button['url']) ? $card_button['url'] : '';
                      $btnTarget = (isset($card_button['target']) && $card_button['target']) ? $card_button['target'] : '_self';
                      if($card_image) { ?>
                        <figure>
                          <div class="inside">
                            <div class="image">
                              <img src="<?php echo $card_image['url'] ?>" alt="<?php echo $card_image['title'] ?>" />

                              <?php if ($card_title) { ?>
                              <div class="cardInfo">
                                <div class="cardTitle"><?php echo $card_title ?></div>
                                <?php if ($card_subtitle) { ?>
                                <div class="cardText"><?php echo $card_subtitle ?></div>
                                <?php } ?>
                              </div>
                              <?php } ?>
                            </div>

                            <?php if ($btnName && $btnLink) { ?>
                            <div class="cardButton">
                              <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="button button-pill"><?php echo $btnName ?></a>
                            </div>
                            <?php } ?>
                          </div>
                        </figure>
                      <?php } ?>
                    <?php } ?>
                  </div>
                </div>
                <?php } ?>
			        <?php endif;

			    // End loop.
			    endwhile;

			// No value.
			else :
			    // Do something...
			endif;
			?>


		</section>
		


	</main><!-- #main -->
</div><!-- #primary -->

<script>
jQuery(document).ready(function($){
  if( $('.text-block-visit').length ) {
    $('.text-block-visit').each(function(){
      if( $(this).prev().hasClass('tileWrapper') ) {
        $(this).prev().addClass('nextText');
      }
    });
  }
});
</script>
<?php
get_footer();
