<?php
/**
 * Template Name: Calendar
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bellaworks
 */
$filter_type = ( isset($_GET['type']) && $_GET['type'] ) ? $_GET['type'] : '';
$is_page = ( isset($_GET['pg']) && $_GET['pg'] ) ? $_GET['pg'] : '';
$current_tab = ( isset($_GET['tab']) && $_GET['tab'] ) ? $_GET['tab'] : '';

$has_filter = [$filter_type, $is_page];
$has_filter = array_filter($has_filter);
$placeholder = THEMEURI . 'images/rectangle.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
get_header(); ?>

<div id="primary" class="content-area-full content-default page-default-template <?php echo $has_banner ?>">
	<main id="main" class="site-main wrapper" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
      <header class="page-header">
        <h1 class="heading-center"><?php the_title(); ?></h1>
      </header>
    <?php endwhile; ?>

      <?php get_template_part('parts-calendar/todays-snapshot') ?>

			<section class="text-centered-section calendar-tab-section">
				<div class="text-center">
					<?php 

          if( get_the_content() ) { ?>
          <div class="page-intro-text"><?php the_content(); ?></div>
          <?php } ?>  

          <?php  
            $calendar_tabs = array(
              'calendar'=>'Calendar',
              'events'=>'Events'
            );
          ?>
					
          <?php if ( do_shortcode('[tribe_events]') ) { ?>
          <div class="calendar-grid-wrapper">
            <div class="tabs-calendar-wrapper">
              <?php $tb=1; foreach ($calendar_tabs as $key => $name) { 
                $is_tab_active = ($tb==1) ? ' active':'';
                $aria_selected = ($tb==1) ? 'true':'false';
                if($has_filter) {
                  $is_tab_active = ($key=='events') ? ' active' : '';
                }
                if($current_tab) {
                  $is_tab_active = ($current_tab==$key) ? ' active' : '';
                }
                ?>
                <div class="tabname--<?php echo $key ?>"><button role="tab" aria-selected="<?php echo $aria_selected ?>" aria-controls="<?php echo $key ?>Grid" class="tab-item<?php echo $is_tab_active?>"><?php echo $name ?></button></div>
              <?php $tb++; } ?>
            </div>

            <?php  
            $ctb=1; foreach ($calendar_tabs as $key => $name) { 
                $tabClass = '';
                $tabcontent_active = ($ctb==1) ? ' active':'';
                if($has_filter) {
                  $tabcontent_active = ($key=='events') ? ' active' : '';
                }
                if($current_tab) {
                  $tabcontent_active = ($current_tab==$key) ? ' active' : '';
                }
                if($key=='calendar') {
                  $tabClass = ' customCalendarGrid ';
                }
              ?>
              <div id="<?php echo $key ?>Grid" data-panel-name="<?php echo $name ?>" class="tab-calendar-panel<?php echo $tabClass?><?php echo $tabcontent_active?>">
                
                <?php //CALENDAR ?>
                <?php if($key=='calendar') { ?>
                  <?php if ( do_shortcode('[tribe_events]') ) { ?>
                  <div id="calendar-info">
                    <?php echo do_shortcode('[tribe_events]'); ?>
                  </div>
                  <?php } ?>

                <?php //EVENTS ?>
                <?php } else if($key=='events') { ?>
                  <?php get_template_part('parts-calendar/calendar-events-tab'); ?>
                <?php } ?>
              
              </div>
            <?php $ctb++; } ?>
            
            
          </div>
          <?php } ?>
				</div>
			</section>

		

	</main><!-- #main -->
</div><!-- #primary -->
<link rel='stylesheet' id='calendar-styles-css'  href='<?php bloginfo('url'); ?>/wp-content/plugins/events-override/calendar-styles.css?ver=1.4' type='text/css' media='all' />

<?php
include( locate_template('parts/popup-river-jam.php') );
get_footer();
