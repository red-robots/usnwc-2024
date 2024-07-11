<?php
get_header(); 
?>
<div id="primary" class="content-area-full job-info-page">
	<main id="main" class="site-main full" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php
			$gallery = get_field("camp_gallery");
			?>
			<section class="text-centered-section">
				<div class="wrapper narrow text-center">
					<div class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</div>
				</div>
			</section>

			<section class="main-post-text job-description">
				<div class="wrapper narrow">
					<div class="flexwrap">
						<div class="textcol">
							<div class="inside">
								<?php the_content(); ?>
								<?php if( $applyLink = get_field("apply_link") ) { ?>
								<div class="buttondiv" style="margin-top:30px">
									<a href="<?php echo $applyLink ?>" target="_blank" class="btn-sm red xs"><span>Apply Now</span></a>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="wrapper photo-flex">
					<?php foreach( $gallery as $g ) { ?>
						<div class="photo">
							<img src="<?php echo $g['url']; ?>">
							<a href="<?php echo $g['url']; ?>" download>Download</a>
						</div>
					<?php } ?>
				</div>
			</section>

		<?php endwhile; ?>

	</main><!-- #main -->
</div><!-- #primary -->


<?php
get_footer();