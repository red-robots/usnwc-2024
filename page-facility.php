<?php
/**
 * Template Name: Facility Map
 */
$placeholder = THEMEURI . 'images/rectangle.png';
$square = THEMEURI . 'images/square.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
get_header(); ?>

<div id="primary" class="content-area-full map-page <?php echo $has_banner ?>">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<?php if( get_the_content() ) { ?>
			<div class="intro-text-wrap">
				<div class="wrapper">
					<h1 class="page-title"><span><?php the_title(); ?></span></h1>
					<div class="intro-text"><?php the_content(); ?></div>
				</div>
			</div>
			<?php } ?>
		<?php endwhile; ?>

    <?php include( locate_template('parts/tabs-locations.php') ); ?>
    
    <?php if( isset($wwlocations) && $wwlocations ) { ?>
    <div class="locationPanels facility-map-section">
      
      <?php foreach ($wwlocations as $k=>$w) { 
        $is_active = ($k==0) ? ' active':'';
        $loc = $w['locations_taxonomy'];
        $name = (isset($loc->name) && $loc->name) ? $loc->name : '';
        $slug = ($name) ? sanitize_title($name) : '';
        $maps = $w['maps'];
        $address = $w['location'];
        if($loc && $maps) { ?>
        <?php //get_template_part("parts/subpage-tabs"); ?>
        <div class="tabOuterWrapper">
          <div class="mobileTabHandleDiv">
            <button class="mobileTabHandle" aria-expanded="false" aria-controls="tabpanel-<?php echo $slug ?>">
              <div class="name"><?php echo $name ?></div>
              <?php if ($address) { ?>
              <div class="loc"><?php echo $address ?></div>
              <?php } ?>
            </button>
          </div>
          <div id="tabpanel-<?php echo $slug ?>" class="info-panel tab-<?php echo $slug.$is_active ?>">
            <div class="tabwrapper">
            <?php foreach ($maps as $m) { 
              $map_image = $m['map'];
              $map_name = $m['title'];
              $map_slug = ($map_name) ? sanitize_title($map_name) : '';
              if($map_image) { ?>
              <div id="map-info-<?php echo $map_slug ?>" class="mapcol" data-section="<?php echo $map_name ?>">
                <figure>
                  <a href="<?php echo $map_image['url'] ?>" data-fancybox>
                    <img src="<?php echo $map_image['url'] ?>" alt="<?php echo ($map_name) ? $map_name : $map_image['title'] ?>" />
                    <span class="zoom-icon"><i class="fas fa-search"></i></span>
                  </a>
                </figure>
              </div>
              <?php } ?>
            <?php } ?>
            </div>
          </div>
        </div>
        <?php } ?>
      <?php } ?>
      
    </div>
    <?php } ?>

	</main><!-- #main -->
</div><!-- #primary -->
<?php include( locate_template('inc/pagetabs-script.php') ); ?>

<script>
jQuery(document).ready(function($){
  if( $('.locationPanels .info-panel').length ) {
    $('.locationPanels .info-panel').each(function(){
      var infoPanel = $(this);
      if( $(this).find('.mapcol').length ) {
        var locationSubNav = '<div class="customSubNavWrapper"><div class="location_subnav">';
        $(this).find('.mapcol').each(function(){
          var sectionName = $(this).attr('data-section');
          var sectionId = $(this).attr('id');
          locationSubNav += '<span><a href="#'+sectionId+'">'+sectionName+'</a></span>';
        });
        locationSubNav +='</div></div>';
        infoPanel.prepend(locationSubNav);
      }
    });
  }
});
</script>
<?php
get_footer();
