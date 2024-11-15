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
			          $tile = get_sub_field('tile'); ?>
                <?php if($tile) { ?>
                <div class="tileWrapper">
    	            <?php foreach( $tile as $t ) {
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

<?php
get_footer();
