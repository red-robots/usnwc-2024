<?php
/**
 * Template Name: NEW Flexible Content
 */

get_header(); 
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full festival-page">
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="intro-text-wrap">
			<div class="wrapper">
				<h1 class="page-title"><span><?php the_title(); ?></span></h1>
				<?php if( get_the_content() ) { ?>
				<div class="intro-text"><?php the_content(); ?></div>
				<?php } ?>
			</div>
		</div>
	<?php endwhile;  ?>

  <?php /* FLEXIBLE CONTENT */ ?>
  <?php if( have_rows('flexible_content') ) { ?>
  <div class="new-flexible-content">
    <?php $i=1; while( have_rows('flexible_content') ): the_row(); ?>

      <?php include( locate_template('parts-flexible/image_and_text_row.php') ); ?>
      <?php include( locate_template('parts-flexible/fullwidth_red_bgcolor.php') ); ?>

    <?php $i++; endwhile; ?>
  </div>
  <?php } ?>

</div>
<?php 
get_footer();
