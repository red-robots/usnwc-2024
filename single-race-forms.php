<?php
/**
 *
 */

$placeholder = THEMEURI . 'images/rectangle.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
get_header(); 

// if( is_page('waiver') ) {
// 	$pageClass = ' waiver';
// } else {
// 	$pageClass = '';
// }
?>

<div id="primary" class="raceform content-area-full content-default page-default-template <?php echo $has_banner ?>">
	<main id="main" class="site-main wrapper" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<section class="text-centered-section">
				<div class="wrapper text-center">
					<div class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</div>
					<?php if ( get_the_content() ) { ?>
          <div class="defaultTextContent<?php echo $pageClass; ?>">
            <?php the_content(); ?>
          </div>
          <?php } ?>
				</div>
			</section>

		<?php endwhile; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
