<?php
/**
 * Template Name: Food and Beverage
 */
$placeholder = THEMEURI . 'images/rectangle.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
$post_id = get_the_ID();
get_header(); ?>
<div id="primary" class="content-area-full <?php echo $has_banner ?>">
	<main id="main" class="site-main fw-left" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php
			$section_1 = get_field("section_1");
			if($section_1) { ?>
			<section class="text-centered-section">
				<div class="wrapper text-center">
					<?php echo anti_email_spam($section_1); ?>
				</div>
			</section>
			<?php } ?>

      <?php endwhile; ?>

			<?php get_template_part("parts/subpage-tabs"); ?>

			<?php 
			/* GALLERIES */
			$map_images = get_field("map_images"); ?>
			<?php if ($map_images) { 
				$imageList = array();
				foreach($map_images as $m) {
					if($m['image']) {
						$imageList[] = $m;
					}
				}

				if($imageList) { ?>
				<section class="map-images">
					<div id="foodSlides" class="flexslider">
						<ul class="slides">
						<?php foreach ($imageList as $m) { 
							$img = $m['image']; 
							if($img) { ?>
							<li><img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" /></li>
					    <?php } ?>
						<?php } ?>
						</ul>
					</div>
				</section>	
				<?php } ?>
			<?php } ?>


		  <?php if( have_rows('section_3') ) { ?>
        <?php $i=1; while( have_rows('section_3') ): the_row(); ?>
        <?php if( get_row_layout() == 'entry' ) { ?>
          <?php  
            $e_title = get_sub_field('entrytitle');
            $e_text = get_sub_field('entrytext');
            $e_time = get_sub_field('entrytime');
            $slides = get_sub_field('entryslides');
            $staticImages = get_sub_field('staticImages');
            $image_display_type = get_sub_field('image_display_type');
            $boxClass = ( ($e_title || $e_text) && $slides ) ? 'half':'full';
            $id = get_the_ID();
          ?>

          <section class="menu-sections menu-sections-repeatable">
            <div class="columns-2">

              <?php if( ($e_title || $e_text) || $slides) {  $colClass = ($i % 2) ? ' odd':' even'; ?>
              <div id="section-section_3_<?php echo $i?>" data-section="<?php echo $e_title ?>" class="mscol <?php echo $boxClass.$colClass ?>">
                <?php if ( $e_title || $e_text ) { ?>
                <div class="textcol">
                  <div class="inside">
                    <div class="info">
                      <?php if ($e_title) { ?>
                        <h3 class="mstitle"><?php echo $e_title ?></h3>
                      <?php } ?>

                      <?php if ($e_text || $e_time) { ?>
                      <div class="textwrap">
                        <?php if ($e_text) { ?>
                          <div class="mstext"><?php echo $e_text ?></div>
                        <?php } ?>

                        <?php if ($e_time) { ?>
                          <?php if (strpos($e_time, '[get_hours') !== false) { ?>
                            <?php if ( do_shortcode($e_time) ) { ?>
                              <div class="mstime shcode"><?php echo do_shortcode($e_time); ?></div>
                            <?php } ?>
                          <?php } else { ?>
                            <div class="mstime"><?php echo $e_time ?></div>
                          <?php } ?>
                        <?php } ?>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <?php } ?>

                <?php if ($image_display_type=='slider') { ?>
                  
                  <?php if ( $slides ) { ?>
                  <div class="gallerycol">
                    <div class="flexslider">
                      <ul class="slides">
                        <?php $helper = THEMEURI . 'images/rectangle-narrow.png'; ?>
                        <?php foreach ($slides as $s) { ?>
                          <li class="slide-item" style="background-image:url('<?php echo $s['url']?>')">
                            <img src="<?php echo $helper ?>" alt="" aria-hidden="true" class="placeholder">
                            <img src="<?php echo $s['url'] ?>" alt="<?php echo $s['title'] ?>" class="actual-image" />
                          </li>
                        <?php } ?>
                      </ul>
                    </div>
                  </div>  
                  <?php } ?>

                <?php } else { ?>
                  <?php 
                  $image1 = ( isset($staticImages['image1']) && $staticImages['image1'] ) ? $staticImages['image1']['url'] : ''; 
                  $image2 = ( isset($staticImages['image2']) && $staticImages['image2'] ) ? $staticImages['image2']['url'] : '';
                  $imagesArr = array($image1,$image2);
                  if( $images = array_filter($imagesArr) ) { ?>
                    <div class="gallerycol fb-static-images count-<?php echo count($images) ?>">
                      <div class="flexwrap">
                        <?php foreach ($images as $imgUrl) { ?>
                        <figure><img src="<?php echo $imgUrl ?>" alt=""></figure>  
                        <?php } ?>
                      </div>
                    </div>
                  <?php } ?>
                <?php } ?>
              </div>
              <?php } ?>  


            </div>
          </section>
        <?php } ?>
        
        <?php $i++; endwhile;  ?>
      <?php } ?>

			<?php 
			/* MAP */
			if( $fbmap = get_field("fbmap") ) { 
				$fbmap_title = get_field("fbmap_title");
				$custom_icon = get_field("custom_icon");
			?>
			<section id="fb-map-section" data-section="<?php echo $fbmap_title ?>" class="section-content fb-map-section">
				<div class="title-w-icon">
					<div class="wrapper">
						<div class="shead-icon text-center">
							<div class="icon"><span class="<?php echo $custom_icon ?>"></span></div>
							<h2 class="stitle"><?php echo $fbmap_title ?></h2>
						</div>
					</div>
					<div class="full-map-image">
						<img src="<?php echo $fbmap['url'] ?>" alt="<?php echo $fbmap['title'] ?>">
					</div>
				</div>
			</section>
			<?php } ?>

		
	</main><!-- #main -->
</div><!-- #primary -->

<?php include( locate_template('inc/pagetabs-script.php') ); ?>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('#foodSlides').flexslider({
    animation: "slide"
  });

  /* Sub-tabs */
  // if( $(".menu-sections .mstitle").length > 0 ) {
  // 	$("#pageTabs").show();

  // 	$(".menu-sections .mstitle").each(function(){
  // 		var parent = $(this).parents(".mscol");
  // 		var parentId = parent.attr("id");
  // 		var text = $(this).text().replace(/\s/g,' ').trim();
  // 		var tab = '<span class="mini-nav"><a href="#'+parentId+'">'+text+'</a></span>';
  // 		$("#tabcontent").append(tab);
  // 	});

  // 	$(document).on("click","#tabcontent a",function(e) {
		// 	$("#tabcontent a").removeClass("active");
		// 	$(this).addClass('active');
		// });
  // }
});
</script>
<?php
get_footer();
