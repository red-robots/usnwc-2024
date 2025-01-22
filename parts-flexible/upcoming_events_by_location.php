<?php if( get_row_layout() == 'upcoming_events_by_location' ) { ?>

  <?php 
  $section_title = get_sub_field('section_title');
  $show_start_date = get_sub_field('show_start_date');
  $selected_post_types = get_sub_field('post_types');
  $selected_location = get_sub_field('location');
  $section_data = ($section_title) ? ' data-section="'.$section_title.'"':'';
  if($selected_post_types) { 
    $current_date = date('Y-m-d'); 
    $args = array(
      'posts_per_page'  => 3,
      'post_type'       => $selected_post_types,
      'post_status'     => 'publish',
      'meta_key' => 'start_date',
      'orderby' => 'meta_value_num',
      'order' => 'ASC',
      'meta_query'      => array(
        'relation' => 'OR',
        array(
          'key' => 'start_date',
          'value' => $current_date,
          'compare' => '>=',
          'type' => 'DATE'
        ),
        array(
          'key' => 'end_date',
          'value' => $current_date,
          'compare' => '>=',
          'type' => 'DATE'
        ),
      )
    );

    if($selected_location) {
      $location_slug = $selected_location->slug;
      $taxonomy = $selected_location->taxonomy;
      $taxonomies = array();
      $taxonomies[] = $taxonomy;
      if( in_array('race', $selected_post_types) ) {
        $taxonomies[] = 'activity_location';
      }


      if($taxonomies) {
        if( count($taxonomies)>1 ) {
          $args['tax_query']['relation']='OR';
        }

        foreach($taxonomies as $tax) {
          $args['tax_query'][] = array(
            'taxonomy' => $tax,
            'field' => 'slug',
            'terms' => $location_slug
          );
        }
      }

      // $args2['tax_query'] = array(
      //   'relation' => 'OR',
      //   array(
      //     'taxonomy' => 'test1',
      //     'field' => 'slug',
      //     'terms' => 'ptype1'
      //   ),
      //   array(
      //     'taxonomy' => 'test2',
      //     'field' => 'slug',
      //     'terms' => 'ptype2'
      //   )
      // );

      

    }

    // echo "<pre>";
    //   print_r($args);
    //   echo "</pre>";

    $posts = get_posts($args);
    //print_r($posts);

  ?>
  <section id="<?php echo get_row_layout() . '-' . $ctr ?>"<?php echo $section_data ?> class="repeatable-block section section-upcoming_events section_<?php echo get_row_layout() ?>">
    <div class="wrapper">
      <?php if ( $section_title ) { ?>
      <h2 class="section-title"><?php echo $section_title ?></h2>
      <?php } ?>

      <?php  
      
      $post_type_strs = ($selected_post_types) ? implode(',', $selected_post_types) : '';
      if($posts) { ?>
      <div class="flexwrap">
          <?php foreach($posts as $p) { 
            $pid = $p->ID;
            $title = $p->post_title;
            $image = get_field('full_image', $pid);
            // if(!$image) {
            //   $image = get_field('mobile-banner', $pid);
            // }
            $start_date = get_field('start_date', $pid);
            $pagelink = get_permalink($pid);
            $start_date_year = ($start_date ) ? date('Y', strtotime($start_date)) : '';
            if($start_date) {
              $start_date = date('l, F d', strtotime($start_date));
            }

            if( get_field('thumbnail_image', $pid) ) {
              $image = get_field('thumbnail_image', $pid);
            }
            ?>
            <div class="infobox">
              <div class="inside">
                
                <?php if ( get_post_type() == 'festival' ) { 
                    $images_repeater = get_field('flexslider_banner', $pid);
                    if($images_repeater) {
                      $img = $images_repeater[0];
                      $image = ( isset($img['image']) ) ? $img['image'] : ''; 
                    }
                  ?>

                  <a href="<?php echo $pagelink ?>" class="eventLink">
                    <?php if ($show_start_date && $start_date) { ?>
                    <div class="event-date start-date"><?php echo $start_date ?></div>
                    <?php } ?>
                    <?php if ($image && isset($image['url'])) { ?>
                    <figure class="event-image">
                      <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['url'] ?>" />
                    </figure>
                    <?php } else { ?>
                    <figure class="event-image no-image">
                      <img src="<?php echo get_stylesheet_directory_uri() ?>/images/image-not-available.jpg" alt="" />
                    </figure>
                    <?php } ?>
                    <?php if ($title) { ?>
                    <h3 class="event-title"><?php echo $title ?></h3>
                    <?php } ?>
                  </a>

                <?php } else { ?>

                  <?php if ($show_start_date && $start_date) { ?>
                  <div class="event-date start-date"><?php echo $start_date ?></div>
                  <?php } ?>
                  <?php if ( $image && isset($image['url']) ) { ?>
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

                <?php } ?>
              </div>
            </div>
          <?php } ?>
          </div>
      <?php } ?>

    </div>
  </section>
  <?php } ?>
<?php } ?>