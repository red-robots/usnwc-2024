<?php 
get_header(); 
$rectangle = THEMEURI . "images/rectangle-narrow.png";
?>
<div id="primary" class="content-area full">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php

		// Check value exists.
		if( have_rows('homepage_layouts') ):

		    // Loop through rows.
        $ctr=1;
		    while ( have_rows('homepage_layouts') ) : the_row();

		        // Case: Paragraph layout.
		        if( get_row_layout() == 'full_width_promo' ):
		            $poster = get_sub_field('poster');
                $poster_mobile = get_sub_field('poster_portrait_mobile');
		            $title = get_sub_field('title');
                $title_width = get_sub_field('title_width');
		            $description = get_sub_field('description');
		            $full_cta_link = get_sub_field('full_cta_link');
		            $full_type = get_sub_field('full_type');
                if($poster) { ?>
                <?php if ($title_width) { ?>
                <style>
                  @media screen and (min-width: 961px) {
                    .full-bleed-promo--<?php echo $ctr ?> .info .words {
                      width: <?php echo $title_width ?>%!important;
                    }
                  }
                </style>
                <?php } ?>
		            <?php if ( $ctr === 1 ) { ?>
                <style>
                  /* <picture> hero (2026-09-23): one <img> serves both breakpoints, so it must stay visible
                     where the old two-image markup hid .poster-desktop, and the mobile aspect placeholder
                     that keyed off :has(img.poster-mobile) needs to key off the <picture> instead. */
                  .full-bleed-promo picture img.poster-desktop { display: block !important; }
                  @media screen and (max-width:768px) {
                    section.full-bleed-promo.rounded_with_padding div.img:has(picture) { padding-bottom: 120vw; }
                  }
                </style>
                <?php } ?>
		            <section class="full-bleed-promo full-bleed-promo--<?php echo $ctr ?> <?php echo $full_type; ?>">
		            	<div class="img">
		            		<?php
		            		// Hero images: srcset from the registered sizes, and <picture> so a phone downloads only the
		            		// portrait file (the CSS swaps them at 768px). The first promo is the LCP candidate, so it loads
		            		// eagerly with high priority; later promos are lazy. (2026-09-23)
		            		$hero_first  = ( $ctr === 1 );
		            		$hero_attrs  = array(
		            			'class'    => 'poster-desktop',
		            			'sizes'    => '100vw',
		            			'loading'  => $hero_first ? 'eager' : 'lazy',
		            			'decoding' => 'async',
		            		);
		            		if ( $hero_first ) { $hero_attrs['fetchpriority'] = 'high'; }
		            		if ( $poster_mobile ) {
		            			$mobile_src = wp_get_attachment_image_src( $poster_mobile['ID'], 'full' );
		            			$mobile_srcset = wp_get_attachment_image_srcset( $poster_mobile['ID'], 'full' );
		            			$desktop_srcset = wp_get_attachment_image_srcset( $poster['ID'], 'full' );
		            			?>
		            			<picture>
		            				<source media="(max-width: 768px)" srcset="<?php echo esc_attr( $mobile_srcset ? $mobile_srcset : $mobile_src[0] ); ?>" sizes="100vw">
		            				<source media="(min-width: 769px)" srcset="<?php echo esc_attr( $desktop_srcset ? $desktop_srcset : $poster['url'] ); ?>" sizes="100vw">
		            				<?php echo wp_get_attachment_image( $poster['ID'], 'full', false, $hero_attrs ); ?>
		            			</picture>
		            			<?php
		            		} else {
		            			echo wp_get_attachment_image( $poster['ID'], 'full', false, $hero_attrs );
		            		}
		            		?>

		            		<div class="info">
			            		<div class="words">
			            			<?php if( $title ) { ?>
				            			<h2><?php echo $title; ?></h2>
				            		<?php } ?>
				            		<?php if( $description ) { ?>
				            			<p><?php echo $description; ?></p>
				            		<?php } ?>
			            		</div>
			            		<?php if( $title && is_array( $full_cta_link ) && ! empty( $full_cta_link['url'] ) ) { ?>
			            			<div class="cta">
			            				<a href="<?php echo $full_cta_link['url']; ?>"><?php echo $full_cta_link['title']; ?></a>
			            			</div>
			            		<?php } ?>
			            	</div>
			            	<div class="grad"></div>
		            	</div>
		            </section>
                <?php } ?>

		        <?php
		        // Case: Download layout.
		        elseif( get_row_layout() == 'card_layout' ): 
		            $cards = get_sub_field('cards'); ?>
		            <?php if ($cards) { ?>
		            <div class="homerow activities--desktop">
		            	<div class="activities">
		            		<div class="wrappe-full full text-center">
  								    <div class="card-flex">
    			            <?php foreach( $cards as $card ) {
    			            	$card_poster = $card['card_poster'];
    			            	$card_title = $card['card_title'];
    			            	$card_link = $card['card_link'];
    			            	?>
                        <div class="imagebox">
                          <a href="<?php echo $card_link['url']; ?>" target="<?php echo $card_link['target']; ?>">
                            <div class="info-flex">
                              <div class="title"><?php echo $card_title; ?></div>
                              <div class="bg-overlay"></div>
                            </div>
                            <?php if ( isset($card_poster['ID']) ) {
                              echo wp_get_attachment_image( $card_poster['ID'], 'large', false, array( 'class' => 'poster', 'alt' => '', 'aria-hidden' => 'true', 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 768px) 90vw, 25vw' ) );
                            } elseif ( isset($card_poster['url']) ) { ?>
                            <img class="poster" src="<?php echo $card_poster['url']; ?>" alt="" aria-hidden="true" loading="lazy">
                            <?php } ?>
                          </a>
                        </div>
    			            <?php } ?>
  						       </div>
						        </div>
			           </div>
		           </div>

               <div class="carousel-activities-mobile">
                  <div id="activities--swiper-carousel">
                    <div id="activities--swiper" class="swiper-container">
                      <div class="swiper-wrapper">
                        <?php foreach( $cards as $card ) {
                          $card_poster = $card['card_poster'];
                          $card_title = $card['card_title'];
                          $card_link = $card['card_link'];
                          ?>
                          <figure class="swiper-slide">
                            <div class="inside">
                              <a href="<?php echo $card_link['url']; ?>" target="<?php echo $card_link['target']; ?>">
                                <div class="info-flex">
                                  <div class="title"><?php echo $card_title; ?></div>
                                  <div class="bg-overlay"></div>
                                </div>
                                <?php if ( isset($card_poster['ID']) ) {
                                  echo wp_get_attachment_image( $card_poster['ID'], 'large', false, array( 'class' => 'poster', 'alt' => '', 'aria-hidden' => 'true', 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '90vw' ) );
                                } else { ?>
                                <img class="poster" src="<?php echo $card_poster['url']; ?>" alt="" aria-hidden="true" loading="lazy">
                                <?php } ?>
                              </a>
                            </div>
                          </figure>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
               </div>

               
               <?php } ?>
		        <?php endif;

		    // End loop.
		    $ctr++;
        endwhile;

		// No value.
		else :
		    // Do something...
		endif;
		?>



	<?php endwhile; ?>
</div><!-- #primary -->
<?php
get_footer();