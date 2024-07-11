<?php
/**
 * Template Name: Salesforce
 */

$placeholder = THEMEURI . 'images/rectangle.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
get_header(); 

if( is_page('waiver') ) {
	$pageClass = 'waiver';
} else {
	$pageClass = '';
}
?>


<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8">
<script src="https://www.google.com/recaptcha/api.js"></script>
<style type="text/css">
	div#gform_wrapper_23 {
		display: none;
	}
	input[type="datetime-local"]::-webkit-calendar-picker-indicator {
	    background: transparent;
	    bottom: 0;
	    color: transparent;
	    cursor: pointer;
	    height: auto;
	    left: 0;
	    position: absolute;
	    right: 0;
	    top: 0;
	    width: auto;
	}
</style>

<div id="primary" class="content-area-full content-default page-default-template <?php echo $has_banner ?>">
	<main id="main" class="site-main wrapper" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<section class="text-centered-section">
				<div class="wrapper text-center">
					<div class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</div>
					<div class="<?php echo $pageClass; ?>">
						<?php the_content(); ?>
					

<?php echo do_shortcode('[gravityform id="23" title="false" description="false"]'); ?>


						<?php include( locate_template('parts/salesforce-event.php') ); ?>

					</div>
				</div>
			</section>

		<?php endwhile; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
