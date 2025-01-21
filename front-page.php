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
		    while ( have_rows('homepage_layouts') ) : the_row();

		        // Case: Paragraph layout.
		        if( get_row_layout() == 'full_width_promo' ):
		            $poster = get_sub_field('poster');
                $poster_mobile = get_sub_field('poster_portrait_mobile');
		            $title = get_sub_field('title');
		            $description = get_sub_field('description');
		            $full_cta_link = get_sub_field('full_cta_link');
		            $full_type = get_sub_field('full_type');
		            // echo '<pre>';
		            // print_r($full_type);
                if($poster) { ?>
		            <section class="full-bleed-promo <?php echo $full_type; ?>">
		            	<div class="img">
		            		<img src="<?php echo $poster['url']; ?>" alt="<?php echo $poster['alt']; ?>" class="poster-desktop">

                    <?php if ($poster_mobile) { ?>
                    <img src="<?php echo $poster_mobile['url']; ?>" alt="<?php echo $poster_mobile['alt']; ?>" class="poster-mobile">
                    <?php } ?>

		            		<div class="info">
			            		<div class="words">
			            			<?php if( $title ) { ?>
				            			<h2><?php echo $title; ?></h2>
				            		<?php } ?>
				            		<?php if( $description ) { ?>
				            			<p><?php echo $description; ?></p>
				            		<?php } ?>
			            		</div>
			            		<?php if( $title ) { ?>
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
                            <img class="poster" src="<?php echo $card_poster['url']; ?>" alt="" aria-hidden="true">
                          </a>
                        </div>
    			            <?php } ?>
  						       </div>
						        </div>
			           </div>
		           </div>

               <div class="carousel-activities-mobile">
                  <div id="activities--carousel" class="owl-carousel owl-theme">
                    <?php foreach( $cards as $card ) {
                      $card_poster = $card['card_poster'];
                      $card_title = $card['card_title'];
                      $card_link = $card['card_link'];
                      ?>
                      <figure class="item">
                        <a href="<?php echo $card_link['url']; ?>" target="<?php echo $card_link['target']; ?>">
                          <div class="info-flex">
                            <div class="title"><?php echo $card_title; ?></div>
                            <div class="bg-overlay"></div>
                          </div>
                          <img class="poster" src="<?php echo $card_poster['url']; ?>" alt="" aria-hidden="true">
                        </a>
                      </figure>
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



	<?php endwhile; ?>
</div><!-- #primary -->
<?php
get_footer();