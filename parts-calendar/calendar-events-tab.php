<?php
$posttypes = array(
  'festival',
  'race',
  'film-series',
  'dining',
  'music'
);

//$paged = ( get_query_var( 'pg' ) ) ? absint( get_query_var( 'pg' ) ) : 1;
$perpage = -1;
$current_date = date('Y-m-d');
$args = array(
  'post_type'       => $posttypes,
  'posts_per_page'  => $perpage,
  'post_status'     => 'publish',
  'meta_key'        => 'start_date',
  'orderby'         => 'meta_value_num',
  'order'           => 'ASC',
  'meta_query'      => array (
    array(
      'key'       => 'start_date',
      'value'     => $current_date,
      'compare'   => '>=',
      'type'      => 'DATE'
    ),
  )
);
//$posts = new WP_Query( $args );
$posts = get_posts( $args );
if( $posts ) { ?>
<section class="calendar-tab-events-posts">
  <div class="flexwrap">
    <?php $i=1; foreach ($posts as $p) { 
      $post_id = $p->ID;
      $thumbnail = '';
      if( get_field('thumbnail_image', $post_id) ) {
        $thumbnail = get_field('thumbnail_image', $post_id);
      }
      if( get_post_type($post_id)=='dining' ) {
        $thumbnail = get_field('mobile-banner', $post_id);
      }
      $is_first = ($i==1) ? ' first':'';
      $page_title = $p->post_title;

      $start = get_field('start_date', $post_id);
      $end = get_field('end_date', $post_id);
      $event_dates = '';
      if($start || $end) {
        if($start==$end) {
          $event_dates .= date('M j, Y', strtotime($start));
        } else {
          if($end) {
            $start_date_month = date('M j', strtotime($start));
            $end_date_month = date('j, Y', strtotime($end));
            $event_dates .= $start_date_month . '-' . $end_date_month;
          } else {
            $event_dates .= date('M j, Y', strtotime($start));
          }
        }
      }
    ?>  
    <div class="infoBox<?php echo $is_first ?>">
      <div class="wrap">
        <figure>
          <?php if ($thumbnail) { ?>
            <img src="<?php echo $thumbnail['url'] ?>" alt="" />
          <?php } else { ?>
            <img src="<?php echo get_template_directory_uri(); ?>/images/image-not-available.jpg" alt="" />
          <?php } ?>
        </figure>
        <h3><?php echo $page_title; ?></h3>
        <?php if ($event_dates) { ?>
        <div class="event-date"><?php echo $event_dates ?></div>
        <?php } ?>
      </div>
    </div>
    <?php $i++; } ?>
  </div>
</section>
<?php } ?>