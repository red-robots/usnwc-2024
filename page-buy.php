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

	<?php
  $all_access_title = get_field("all_access_title");
  $all_access_feat_image = get_field("all_access_feat_image");
  $all_access_text = get_field("all_access_text");

  $single_access_title = get_field("single_access_title");
  $single_access_feat_image = get_field("single_access_feat_image");
  $single_access_text = get_field("single_access_text");
  $single_activities_options = get_field("single_activities_options");
  $class1 = ( ($all_access_title || $all_access_text) && ($single_access_title || $single_access_text) ) ? 'half':'full';

  $all_access_pass_options = get_field('all_access_pass_options');
  ?>

  <section class="access-pass-options">
    <div class="flexwrap">
      <?php if ($all_access_feat_image || $all_access_title) { ?>
      <div class="infobox all">
        <div class="inner">
          <figure>
            <?php if($all_access_feat_image) { ?>
            <img src="<?php echo $all_access_feat_image['url'] ?>" alt="<?php echo $all_access_feat_image['title'] ?>">
            <?php } ?>
          </figure>

          <?php if($all_access_text || $all_access_pass_options) { ?>
            <div class="textwrap">
              <div class="inside">
                <?php if($all_access_title) { ?>
                  <h2 class="stitle"><?php echo $all_access_title ?></h2>
                  <?php } ?>
                <?php if($all_access_text) { ?>
                  <div class="text"><?php echo $all_access_text ?></div>
                <?php } ?>

                <?php if($all_access_pass_options) { ?>
                  <div class="pass-options-wrap">
                    <?php foreach ($all_access_pass_options as $p) { 
                      $name = $p['title'];
                      $items = $p['items'];
                      $btn = $p['button'];
                      $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
                      $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
                      $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
                      if($name) { ?>
                      <div class="option">
                        <div class="wrap">
                          <div>
                            <h3><?php echo $name ?></h3>
                            <?php if ($items) { ?>
                            <div class="items">
                              <?php foreach ($items as $n) { ?>
                                <?php if ($n['name']) { ?>
                                  <div class="item">
                                    <span class="name"><span><?php echo $n['name'] ?></span></span>
                                    <?php if ($n['price']) { ?>
                                    <span class="price"> &ndash; <span><?php echo $n['price'] ?></span></span>
                                    <?php } ?>
                                  </div>
                                <?php } ?>
                              <?php } ?>
                            </div>  
                            <?php } ?>
                          </div>
                          <?php if ($btnLink && $btnTitle) { ?>
                          <div class="buttondiv">
                            <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="button btn-white-shadow"><?php echo $btnTitle ?></a>
                          </div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                    <?php } ?>
                  </div>
                <?php } ?>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>

      <?php if ($single_access_feat_image || $single_access_title) { ?>
      <div class="infobox single">
        <div class="inner">
          <figure>
            <?php if($single_access_feat_image) { ?>
            <img src="<?php echo $single_access_feat_image['url'] ?>" alt="<?php echo $single_access_feat_image['title'] ?>">
            <?php } ?>
          </figure>

          <div class="textwrap">
            <div class="inside">
              <?php if($single_access_title) { ?>
              <h2 class="stitle"><?php echo $single_access_title ?></h2>
              <?php } ?>
              <?php if($single_access_text) { ?>
                <div class="text"><?php echo $single_access_text ?></div>
              <?php } ?>

              <?php 
              $singleButtons = get_field('purchase_buttons');
              // $s_btnLink = (isset($singleBtn['url']) && $singleBtn['url']) ? $singleBtn['url'] : '';
              // $s_btnTitle = (isset($singleBtn['title']) && $singleBtn['title']) ? $singleBtn['title'] : '';
              // $s_btnTarget = (isset($singleBtn['target']) && $singleBtn['target']) ? $singleBtn['target'] : '_self';
              if($single_activities_options) { ?>
                <div class="single-activity-options">
                  <div class="firstCol"></div>
                  <div class="secondCol"></div>
                  <?php foreach ($single_activities_options as $a) { 
                    $a_name = $a['name'];
                    $a_price = $a['price'];
                    if($a_name) { ?>
                    <div class="option">
                      <h3 class="name"><span><?php echo $a_name ?></span></h3>
                      <?php if($a_price) { ?>
                      <span class="price"><span><?php echo $a_price ?></span></span>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  <?php } ?>
                </div>
              <?php } ?>


              <?php if ($singleButtons) { ?>
              <div class="buttons">
                <?php foreach ($singleButtons as $s) { 
                  $sbtn = $s['button'];
                  $s_btnLink = (isset($sbtn['url']) && $sbtn['url']) ? $sbtn['url'] : '';
                  $s_btnTitle = (isset($sbtn['title']) && $sbtn['title']) ? $sbtn['title'] : '';
                  $s_btnTarget = (isset($sbtn['target']) && $sbtn['target']) ? $sbtn['target'] : '_self';
                  if($single_activities_options) { ?>
                  <a href="<?php echo $s_btnLink ?>" target="<?php echo $s_btnTarget ?>" class="button btn-white-shadow"><?php echo $s_btnTitle ?></a>
                  <?php } ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div>
          </div>

        </div>
      </div>
      <?php } ?>
    </div>
  </section>


  <?php include( locate_template('parts/buy-repeater-blocks.php') );  ?>

  
  <?php endwhile;  ?>

</div><!-- #primary -->

<?php
include( locate_template('inc/pagetabs-script.php') );
get_footer();
