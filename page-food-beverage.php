<?php
/**
 * Template Name: Food and Beverage
 */
$placeholder = THEMEURI . 'images/rectangle.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
$post_id = get_the_ID();
$branch_status = 'open';
global $post;
if( isset($post->post_parent) && $post->post_parent ) {
  $parentId = $post->post_parent;
  $parentSlug = getPageSlugById($parentId);
  $branch_status = getBranchOperationStatus($parentSlug); /* open or closed */
}
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
            $auto_closed = get_sub_field('auto_closed_status');
            $e_title = get_sub_field('entrytitle');
            $e_text = get_sub_field('entrytext');
            $e_time = get_sub_field('entrytime');
            $slides = get_sub_field('entryslides');
            $staticImages = get_sub_field('staticImages');
            $hasImages = ($slides || $staticImages) ? true : false;
            $image_display_type = get_sub_field('image_display_type');
            $boxClass = ( ($e_title || $e_text) && $hasImages ) ? 'half':'full';
            $id = get_the_ID();
            $buttons = get_sub_field('buttons');
            $is_custom_time = get_sub_field('is_custom_time');
            $custom_times = get_sub_field('custom_times');

            if($auto_closed && $branch_status=='closed') {
              $e_time = '';
              $locationSectionClass = ' operation--status--closed';
            }
          ?>

          <section class="menu-sections menu-sections-repeatable<?php echo $locationSectionClass ?>">
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

                        <?php if ( $is_custom_time ) { ?>

                          <?php if ($custom_times) { ?>
                          <div class="custom-times">
                            <?php foreach ($custom_times as $c) { 
                              $c_name = $c['name'];
                              $start_time = $c['start_time'];
                              $end_time = $c['end_time'];
                              $time_info = array($start_time, $end_time);
                              $times = '';
                              if( array_filter($time_info) ) {
                                $time_info = array_filter($time_info);
                                $times = implode(' &ndash; ', $time_info);
                              }
                              if($c_name) { ?>
                              <div class="mstime custom-time">
                                <span class="name"><?php echo $c_name ?></span>
                                <?php if ($times) { ?>
                                <span class="time"><?php echo $times ?></span> 
                                <?php } ?>
                              </div>
                              <?php } ?>
                            
                            <?php } ?>
                          </div>
                          <?php } ?>
                          
                        <?php } else { ?>

                          <?php if ($e_time) { ?>
                            <?php if (strpos($e_time, '[get_hours') !== false) { ?>
                              <?php if ( do_shortcode($e_time) ) { 
                                $hours_info = do_shortcode($e_time);
                                $hours_info = strtolower($hours_info);
                                $has_hours = '';
                                if ( (strpos($hours_info, 'am') !== false) || strpos($hours_info, 'pm') !== false ) {
                                  $has_hours = ' has-hours';
                                } 
                                if($has_hours) { ?>
                                <div class="mstime shcode<?php echo $has_hours ?>"><?php echo do_shortcode($e_time); ?></div>
                                <?php } ?>
                              <?php } ?>
                            <?php } else { ?>
                              <div class="mstime"><?php echo $e_time ?></div>
                            <?php } ?>
                          <?php } else { ?>

                            <?php if ($branch_status=='closed') { ?>
                            <div class="mstime branch--status--closed">Closed</div>
                            <?php } ?>

                          <?php } ?>

                        <?php } ?>

                        <?php if ($buttons) { ?>
                        <div class="cta-buttons buttondiv">
                          <?php foreach ($buttons as $b) { 
                            $type = $b['link_type'];
                            $pdf = $b['pdf'];
                            $link = $b['button_link'];
                            if($type=='link') { ?>
                              
                              <?php if ($link) { 
                                $btnName = (isset($link['title']) && $link['title']) ? $link['title'] : '';
                                $btnLink = (isset($link['url']) && $link['url']) ? $link['url'] : '';
                                $btnTarget = (isset($link['target']) && $link['target']) ? $link['target'] : '_self';
                                if($btnName && $btnLink) { ?>
                                  <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="button"><span><?php echo $btnName ?></span></a>
                                <?php } ?>
                              <?php } ?>
                            
                            <?php } else if($type=='pdf') { ?>
                              <?php if ($pdf) { 
                                  $btnName = ( isset($pdf['button_name']) && $pdf['button_name'] ) ? $pdf['button_name'] : '';
                                  $btnLink = ( isset($pdf['pdf']['url']) ) ? $pdf['pdf']['url'] : '';
                                  if($btnName && $btnLink) { ?>
                                    <a href="<?php echo $btnLink ?>" target="_blank" class="button"><span><?php echo $btnName ?></span></a>
                                  <?php } ?>
                              <?php } ?>
                            <?php } ?>
                          <?php } ?>
                        </div>
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
