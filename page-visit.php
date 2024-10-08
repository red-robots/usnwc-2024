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

			            foreach( $tile as $t ) {
				            $title = $t['title'];
				            $image = $t['image'];
				            $description = $t['description'];
				            $link = $t['link'];
				            $span = $t['span'];
						?>
							<div class="tile <?php echo $span ?>">
								<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
									<div class="img">
										<h2><?php echo $title; ?></h2>
										<div class="img-overlay"></div>
										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
									</div>
									<div class="desc">
										<?php echo $description; ?>
									</div>
									<div class="link"><?php echo $link['title']; ?></div>
								</a>
							</div>
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
