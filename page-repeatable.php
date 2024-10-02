<?php
/**
 * Template Name: Flexible Content
 */
get_header(); ?>
<div id="primary" class="content-area-full content-default repeatable-layout">
	<main id="main" class="site-main" role="main">

    <?php while ( have_posts() ) : the_post(); ?>
    <section class="main-description">
      <div class="wrapper text-center">
        <h1 class="pagetitle"><span><?php the_title() ?></span></h1>
        <?php if ( get_the_content() ) { ?>
        <?php the_content(); ?> 
        <?php } ?>
      </div>
    </section>
    <?php endwhile; ?>


		<?php if( have_rows('flexibleContent') ) {  ?>
    <section class="flexible-content-wrapper">
      <?php $i=1; while( have_rows('flexibleContent') ): the_row(); ?>
        
        <?php if( get_row_layout() == 'upcoming_events' ) { ?>
          <?php 
            $section_title = get_sub_field('section_title');
            $post_type = get_sub_field('post_type');
            $btn = get_sub_field('button');
            $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
            $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
            $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
            if($post_type) { 
              $args = array(
                'posts_per_page'  => 3,
                'post_type'       => $post_type,
                'post_status'     => 'publish',
                'meta_query'      => array(
                  array(
                    'key' => 'start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE'
                  )
                )
              );
              $posts = get_posts($args);
              if($posts) { ?>
              <div id="section-upcoming_events-<?php echo $i ?>" class="repeatable-block section-upcoming_events">
                <div class="wrapper">
                  
                  <?php if($section_title) { ?>
                    <h2 class="section-title"><?php echo $section_title ?></h2>
                  <?php } ?>

                  <div class="flexwrap">
                  <?php foreach($posts as $p) { 
                    $pid = $p->ID;
                    $title = $p->post_title;
                    $image = get_field('full_image', $pid);
                    $start_date = get_field('start_date', $pid);
                    $pagelink = get_permalink($pid);
                    if($start_date) {
                      $start_date = date('l, F d', strtotime($start_date));
                    }
                    ?>
                    <div class="infobox">
                      <div class="inside">
                        <?php if ($start_date) { ?>
                        <div class="event-date"><?php echo $start_date ?></div>
                        <?php } ?>
                        <?php if ($image) { ?>
                        <figure class="event-image">
                          <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['url'] ?>" />
                        </figure>
                        <?php } ?>
                        <?php if ($title) { ?>
                        <h3 class="event-title"><?php echo $title ?></h3>
                        <?php } ?>
                        <div class="button-block">
                          <a href="<?php echo $pagelink ?>" class="button-pill">See Details</a>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                  </div>

                </div>
              </div>
              <?php } ?>
            <?php } ?>
        <?php } ?>

      <?php $i++; endwhile; ?>
    </section>
    <?php } ?>

	</main><!-- #main -->
</div><!-- #primary -->
<?php
get_footer();