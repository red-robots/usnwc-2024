<?php
/**
 * Template Name: Instruction Pages
 */

get_header(); 
$currentPageLink = get_permalink();
$blank_image = THEMEURI . "images/rectangle.png";
$square = THEMEURI . "images/square.png";
$parent_page_id = get_the_ID();
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full boxedImagesPage instruction-page-v2">
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="intro-text-wrap">
			<div class="wrapper">
				<h1 class="page-title"><span><?php the_title(); ?></span></h1>
				<?php //if ( get_the_content() ) { ?>
				<div class="intro-text">
					<?php the_content(); ?>
						<?php 
						/*
							Temp measure while we convert this page from pulling instruction_type taxonomy to pages.

						*/
						$includer = get_field('instruction_picker');
						$excluder = get_field('instructions_excluder');

						// echo '<pre>';
						// print_r($includer);
						// echo '</pre>';

						?>
					</div>
				<?php //} ?>
			</div>
		</div>
	<?php endwhile; ?>


	<?php /* GET PARENT CATEGORIES */ ?>
	<?php  
	$taxonomy = 'instruction_type';
	$hide_empty = true;
	$parent_terms = get_terms( 
		array( 
			'taxonomy' => $taxonomy, 
			'parent' => 0, 
			'hide_empty'=>$hide_empty,
			'exclude' => $excluder, // In transistion... These are now chile pages of Outdoor School - Instruction
			 ) 
	);
	if($parent_terms) { ?>
	<section class="section-content entries-with-filter" style="padding-top:0;">
		<div class="post-type-entries boxes-element threecols instructions">
			<div id="data-container">
				<div class="posts-inner result">
					<div id="resultContainer" class="flex-inner align-middle">
						<?php 
						/*

							This is a temporary measure.
							We're going to combine both Child pages and the instruction_type taxonomy on this page until
							all programs are converted from the tax to the page hierarchy.

							So, we must pull the new pages first. Then we'll loop through the remaining instruction_type remaining.

						*/

						// First the pages
						foreach( $includer as $p ){
							$post = get_post($p); 

							if (get_post_status($post) == 'private' || get_post_status($post) == 'draft') {
						        continue; // Skip this iteration and move on to the next post
						    }
							setup_postdata( $post ); 
							// echo '<pre>';
							// print_r($p);
							// echo '</pre>';

							$term_name = get_the_title();
							$thumbImage = get_field("category_image");
							$thumbImageMobile = get_field("category_image_mobile");
							$pagelink = get_the_permalink();

							
						?>
							<?php include(locate_template('parts/instruction-guts.php')); ?>
						<?php 
							wp_reset_postdata(); 
						} 
						?>

						<?php 

						// Then the rest of the instruction_type taxonomy that hasn't been converted yet.

						$i=1; foreach ($parent_terms as $term) { 
							$term_id = $term->term_id;
							$term_name = $term->name;
							$thumbImage = get_field("category_image",$taxonomy.'_'.$term_id);
							$thumbImageMobile = get_field("category_image_mobile",$taxonomy.'_'.$term_id);
							$pagelink = get_term_link($term,$taxonomy);
							//$child_terms = get_term_children($term_id,$taxonomy);

							// quick hack for yoga - because there is only one
							if( $term_name == 'Yoga' ) {
								$pagelink = get_bloginfo('url').'/instructions/200-hour-yoga-teacher-training/';
								$term_name = 'Yoga Teacher Training';
							}

							?>
							<?php include(locate_template('parts/instruction-guts.php')); ?>
						<?php $i++; } ?>



					</div>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>

</div>
<?php
get_footer();
