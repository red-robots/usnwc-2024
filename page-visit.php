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


		<section class="visit">
			<?php

			// Check value exists.
			if( have_rows('layouts') ):

			    // Loop through rows.
			    while ( have_rows('layouts') ) : the_row();

			        // Case: Paragraph layout.
			        if( get_row_layout() == 'tiles' ):
			          $tile = get_sub_field('tile');
                if($tile) {
    	            foreach( $tile as $t ) {
  		            $title = $t['title'];
  		            $image = $t['image'];
  		            $description = $t['description'];
  		            $link = $t['link'];
  		            $span = $t['span'];
                  $span .= ($description) ? ' has-description':' no-description';
                  if($image) { ?>
      							<div class="tile <?php echo $span ?>">
                      <?php if( isset($link['url']) ) { ?>
        								<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="tile-link tile-inner">
        									<div class="img">
                            <?php if ($title) { ?>
                            <h2><?php echo $title; ?></h2>
                            <?php } ?>
        										<div class="img-overlay"></div>
        										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
        									</div>
                          <?php if ($description) { ?>
                          <div class="desc">
                            <?php echo $description; ?>
                          </div>
                          <?php } ?>
        									<div class="link"><?php echo $link['title']; ?></div>
        								</a>
                      <?php } else { ?>
                      <div class="nolink tile-inner">
                        <div class="img">
                          <?php if ($title) { ?>
                          <h2><?php echo $title; ?></h2>
                          <?php } ?>
                          <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                        </div>
                      </div>
                      <?php } ?>
      							</div>
      			        <?php } ?>
                  <?php } ?>

                <?php } ?>

			        <?php 
			        // Case: Download layout.
			        elseif( get_row_layout() == 'download' ): 
			            $file = get_sub_field('file');
			            // Do something...

			        endif;

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

<?php
get_footer();
