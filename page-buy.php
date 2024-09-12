<?php
/**
 * Template Name: Buy
 */

get_header(); 
$blank_image = THEMEURI . "images/rectangle.png";
$square = THEMEURI . "images/square.png";
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full page-activity-passes buy-page buy-page-new <?php echo $has_banner ?>">

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="intro-text-wrap">
			<div class="wrapper">
				<h1 class="page-title"><span><?php the_title(); ?></span></h1>
				<div class="intro-text"><?php the_content(); ?></div>
			</div>
		</div>
	<?php endwhile;  ?>

	<?php
  $all_access_title = get_field("all_access_title");
  $all_access_feat_image = get_field("all_access_feat_image");
  $all_access_text = get_field("all_access_text");

  $single_access_title = get_field("single_access_title");
  $single_access_feat_image = get_field("single_access_feat_image");
  $single_access_text = get_field("single_access_text");
  $class1 = ( ($all_access_title || $all_access_text) && ($single_access_title || $single_access_text) ) ? 'half':'full';
  ?>

  <section class="access-pass-options">
    <div class="flexwrap">
      <?php if ($all_access_feat_image || $all_access_title) { ?>
      <div class="infobox all">
        <div class="inner">
          <figure>
            <?php if($all_access_title) { ?>
            <h2><?php echo $all_access_title ?></h2>
            <?php } ?>
            <?php if($all_access_feat_image) { ?>
            <img src="<?php echo $all_access_feat_image['url'] ?>" alt="<?php echo $all_access_feat_image['title'] ?>">
            <?php } ?>
          </figure>
          <?php if($single_access_text) { ?>
            <div class="text"><?php echo $single_access_text ?></div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>

      <?php if ($single_access_feat_image || $single_access_title) { ?>
      <div class="infobox single">
        <div class="inner">
          <figure>
            <?php if($single_access_title) { ?>
            <h2><?php echo $single_access_title ?></h2>
            <?php } ?>
            <?php if($single_access_feat_image) { ?>
            <img src="<?php echo $single_access_feat_image['url'] ?>" alt="<?php echo $single_access_feat_image['title'] ?>">
            <?php } ?>
          </figure>
        </div>
      </div>
      <?php } ?>
    </div>
  </section>

</div><!-- #primary -->

<?php
include( locate_template('inc/pagetabs-script.php') );
get_footer();
