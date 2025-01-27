<?php
/**
 * Template Name: Sitemap
 */

$placeholder = THEMEURI . 'images/rectangle.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
get_header(); ?>

<div id="primary" class="content-area-full content-default page-default-template sitemappage <?php echo $has_banner ?>">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<section class="text-centered-section">
				<div class="wrapper text-center">
					<div class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</div>
					<?php the_content(); ?>
				</div>
			</section>

		<?php endwhile; ?>

		<section class="site-map">
				<div class="wrapper">
					
					<?php
					/* Main Navigation */
					global $post;
					$current_post_id = ( isset($post->ID) && $post->ID ) ? $post->ID : '';
					$current_url = ($current_post_id) ? get_permalink($current_post_id) : '';
					$current_url = ($current_url) ? rtrim($current_url,"/") : '';

					$parents = get_field("parent_menu","option");
					$childenMenuItems = array();
					$secondary_menu = get_field("secondary_menu","option");

					if($parents) { ?>
					<div id="sitemapLinks">
						<?php get_template_part("parts/navigation-sitemap"); ?>
					</div>
					<?php } ?>

					</div>
				</section>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
