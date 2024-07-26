<?php
/**
 * Template Name: What's New
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


		<section class="stories-whats">
			<?php
			// Custom query to get sticky posts first
			$args = array(
			    'post_type' => array('post', 'whats-new'),
			    'meta_key' => 'sticky_post',
			    'meta_value' => '1',
			    'orderby' => 'date',
			    'order' => 'DESC',
			);

			$sticky_query = new WP_Query($args);

			if ($sticky_query->have_posts()) :
			    while ($sticky_query->have_posts()) : $sticky_query->the_post();
			        // Display sticky post content
			        get_template_part('parts/whats-new');
			    endwhile;
			    wp_reset_postdata();
			endif;

			// Regular query to get non-sticky posts
			$args = array(
			    'post_type' => array('post', 'whats-new'),
			    'meta_query' => array(
			        array(
			            'key' => 'sticky_post',
			            'compare' => 'NOT EXISTS',
			        ),
			    ),
			    'orderby' => 'date',
			    'order' => 'DESC',
			);

			$non_sticky_query = new WP_Query($args);

			if ($non_sticky_query->have_posts()) :
			    while ($non_sticky_query->have_posts()) : $non_sticky_query->the_post();
			        // Display non-sticky post content
			        get_template_part('parts/whats-new');
			    endwhile;
			    wp_reset_postdata();
			endif;
			?>
		</section>


	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
