<?php
/**
 * Template Name: Rentals & Reservations
 */

get_header(); 
$blank_image = THEMEURI . "images/rectangle.png";
$square = THEMEURI . "images/square.png";
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full page-activity-passes <?php echo $has_banner ?>">

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="intro-text-wrap">
			<div class="wrapper">
				<h1 class="page-title"><span><?php the_title(); ?></span></h1>
				<div class="intro-text"><?php the_content(); ?></div>
			</div>
		</div>
	<?php endwhile;  ?>

	<?php //get_template_part("parts/subpage-tabs"); ?>

	<?php
	$all_access_title = get_field("all_access_title");
	$all_access_feat_image = get_field("all_access_feat_image");
	$all_access_text = get_field("all_access_text");

	$single_access_title = get_field("single_access_title");
	$single_access_feat_image = get_field("single_access_feat_image");
	$single_access_text = get_field("single_access_text");
	// $class1 = ( ($all_access_title || $all_access_text) && ($single_access_title || $single_access_text) ) ? 'half':'full';
	$class1 = 'half';
	?>

	<?php if(have_rows('cards')) :  ?>
	<section class="twoColSection <?php echo $class1 ?>">
		<div class="twoColInner">
				
			
			<?php while(have_rows('cards')): the_row();
					$title = get_sub_field('title');
					$fImage = get_sub_field('image');
					$desc = get_sub_field('description');
					$buy_btn = '';
			 ?>
			<?php /* SINGLE ACTIVITY PASSES */ ?>
			<?php //if ($single_access_title || $single_access_text) { ?>
			<div id="section2" data-section="<?php echo $single_access_title ?>" class="tcol <?php echo ($fImage) ? 'hasphoto':'nophoto' ?>">
				<div class="inner">
					<?php if ($fImage) { ?>
						<div class="photo">
							<div class="img" style="background-image:url('<?php echo $fImage['url'] ?>');"><img src="<?php echo $blank_image ?>" alt="" aria-hidden="true" class="helper"></div>
						</div>
					<?php } ?>

					<?php
					//$single_activities = get_single_activity_passes_list('default'); /* see inc/func-activity-passes.php */
					?>
					<div class="info text-center">
						
						<?php if ($title) { ?>
							<h2 class="stitle"><?php echo $title ?></h2>
						<?php } ?>
						<?php if ($desc) { ?>
							<div class="text"><?php echo $desc ?></div>
						<?php } ?>

						<?php if (have_rows('prices')) : ?>
						<div class="single-activities">
							<?php while(have_rows('prices')): the_row();
								$item = get_sub_field('item');
								$price = get_sub_field('price');
								?>
								<div id="<?php //echo $item_id?>" class="itemrow <?php echo $item_class?>">
									<span class="activity-name"><span><?php echo $item ?></span></span>
									<?php //if ($s_price || $buy_btn) { ?>
										<span class="button-group">
											<span class="wrap">
												<?php if ($price) { ?>
												<span class="price"><?php echo $price ?></span>	
												<?php } ?>
												<?php if ($buy_btn && $buy_link) { ?>
												<a href="<?php echo $buy_link ?>" target="<?php echo $buy_target ?>" class="btn-sm xs"><span><?php echo $buy_btn ?></span></a>
												<?php } ?>
											</span>
										</span>
									<?php //} ?>
								</div>
							<?php endwhile; ?>
						</div>
						<?php endif; ?>

					</div>
					
				</div>
			</div>
			<?php endwhile; ?>

		</div>
	</section>
	<?php  endif; ?>


	


	<?php
	$customFAQTitle = 'FAQ';
	$customFAQClass = 'custom-class-faq';
	include( locate_template('parts/content-faqs.php') );
	include( locate_template('inc/faqs.php') );
	?>


</div><!-- #primary -->

<?php
include( locate_template('inc/pagetabs-script.php') );
get_footer();
