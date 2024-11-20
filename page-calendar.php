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

      <?php get_template_part('parts-calendar/todays-snapshot') ?>

			<section class="text-centered-section calendar-tab-section">
				<div class="text-center">
					<?php 

          if( get_the_content() ) { ?>
          <div class="page-intro-text"><?php the_content(); ?></div>
          <?php } ?>  
					
          <?php if ( do_shortcode('[tribe_events]') ) { ?>
          <div class="calendar-grid-wrapper">
            <div class="tabs-calendar-wrapper">
              <div><button role="tab" aria-selected="true" aria-controls="calendarGrid" class="tab-item<?php echo ($has_filter) ? '':' active'?>">Calendar</button></div>
              <div><button role="tab" aria-selected="false" aria-controls="eventsGrid" class="tab-item<?php echo ($has_filter) ? ' active':''?>">Events</button></div>
            </div>
            <div id="calendarGrid" class="tab-calendar-panel customCalendarGrid<?php echo ($has_filter) ? '':' active'?>">
              <?php if ( do_shortcode('[tribe_events]') ) { ?>
              <div id="calendar-info">
                <?php echo do_shortcode('[tribe_events]'); ?>
              </div>
              <?php } ?>
            </div>
            <div id="eventsGrid" class="tab-calendar-panel<?php echo ($has_filter) ? ' active':''?>">
              <?php get_template_part('parts-calendar/calendar-events-tab'); ?>
            </div>
          </div>
          <?php } ?>
					
				</div>
			</section>

		<?php endwhile; ?>

	</main><!-- #main -->
</div><!-- #primary -->
<link rel='stylesheet' id='calendar-styles-css'  href='<?php bloginfo('url'); ?>/wp-content/plugins/events-override/calendar-styles.css?ver=1.4' type='text/css' media='all' />
<?php
get_footer();
