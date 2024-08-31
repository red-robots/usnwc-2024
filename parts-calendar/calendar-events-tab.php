<?php
global $wpdb;
$prefix = $wpdb->prefix;
$today_date = strtotime(date('Ymd'));
$where_post_types = '';
$paged = ( get_query_var( 'pg' ) ) ? absint( get_query_var( 'pg' ) ) : 1;
$filter_type = ( isset($_GET['type']) && $_GET['type'] ) ? $_GET['type'] : '';
$cpttypes = [
  'festival'    => 'Festivals',
  'race'        => 'Races',
  'film-series' => 'Film Series',
  'dining'      => 'Dining',
  'music'       => 'River Jam'
];
$selected_filter_name = ($filter_type && isset($cpttypes[$filter_type])) ? $cpttypes[$filter_type]:'';
$postTypes = $cpttypes;

if($filter_type && $filter_type!='all') {
  foreach($postTypes as $k=>$v) {
    if($k!=$filter_type) {
      unset($postTypes[$k]);
    }
  }
}


$k=0;
foreach($postTypes as $type=>$title) {
  $and = ($k>0) ? ' OR ' : '';
  $where_post_types .= $and . 'pp.post_type="'.$type.'"';
  $k++;
}

$condition1 = "SELECT pp.ID, mm.meta_value AS start_date 
          FROM " . $prefix ."posts pp, ".$prefix."postmeta mm 
          WHERE (".$where_post_types.") 
          AND pp.post_status='publish' 
          AND pp.ID=mm.post_id AND mm.meta_key='start_date'";

$condition2 = "SELECT pp.ID, mm.meta_value AS end_date  
          FROM " . $prefix ."posts pp, ".$prefix."postmeta mm 
          WHERE (".$where_post_types.") 
          AND pp.post_status='publish' 
          AND pp.ID=mm.post_id AND mm.meta_key='end_date' 
          AND UNIX_TIMESTAMP(mm.meta_value) >= ".strtotime(date('Ymd'))." ORDER BY end_date ASC";


$per_page = 15;
$offset = ($paged>1) ? ($per_page * $paged)-$per_page : 0;

$the_query = "SELECT p.*, st.start_date, en.end_date     
          FROM ".$prefix."posts p, ".$prefix."postmeta m, 
          (".$condition1.") st, (".$condition2.") en  
          WHERE p.ID=st.ID AND p.ID=en.ID AND p.ID=m.post_id 
          GROUP BY p.ID";

//$new_query = $the_query . " ORDER BY st.start_date ASC LIMIT ".$offset.", " . $per_page;
$new_query = $the_query . " ORDER BY st.start_date ASC LIMIT ".$per_page." OFFSET ".$offset;

// $the_count_query = "SELECT count(*) as total    
//           FROM ".$prefix."posts p, ".$prefix."postmeta m, 
//           (".$condition1.") st, (".$condition2.") en  
//           WHERE p.ID=st.ID AND p.ID=en.ID AND p.ID=m.post_id 
//           GROUP BY p.ID";
// $tp = $wpdb->get_row($the_count_query);
// $total_records = ($tp) ? $tp->total : 0;

$posts = $wpdb->get_results($new_query);
$total = $wpdb->get_results($the_query);
$total_records = ($total) ? count($total) : 0;
?>
<section class="calendar-tab-events-posts">
  <div data-selected="<?php echo ($filter_type) ? $filter_type :'all' ?>" data-baseUrl="<?php echo get_permalink() ?>" class="custom-dropdown dropdown-posttypes">
    <div class="selectwrap">
      <button class="select-event-type selector"><span><?php echo ($selected_filter_name) ? $selected_filter_name :'Type' ?></span></button>
    </div>
    <div class="dropdown-inner">
      <ul class="dropdownlist">
        <li class="option default<?php echo ($filter_type) ? '' :' hidden' ?>"><a href="javascript:void(0)" class="select-posttype" data-val="all">All</a></li>
      <?php foreach ($cpttypes as $type => $title) { 
        $is_selected = ($filter_type && $filter_type==$type) ? ' selected':'';
        ?>
        <li class="option<?php echo $is_selected ?>"><a href="javascript:void(0)" class="select-posttype" data-val="<?php echo $type ?>"><?php echo $title ?></a></li>
      <?php } ?>
      </ul>
    </div>
  </div>

  <?php if( $posts ) { ?>
  <div class="records-container">
    <div class="flexwrap">
      <?php 
        $CURRENT_DATE = date('Ymd');
        $i=1; foreach ($posts as $p) { 
        $post_id = $p->ID;
        $post_type = $p->post_type;
        $pagelink = get_permalink($post_id);
        $thumbnail = '';
        $short_description = get_field('event__short_description', $post_id);
        if( get_field('thumbnail_image', $post_id) ) {
          $thumbnail = get_field('thumbnail_image', $post_id);
        }
        if( $post_type=='dining' ) {
          $thumbnail = get_field('mobile-banner', $post_id);
        }

        $is_first = '';
        if($paged==1) {
          $is_first = ($i==1) ? ' first':'';
        }

        //$is_first = '';
        $page_title = $p->post_title;

        $start = get_field('start_date', $post_id);
        $end = get_field('end_date', $post_id);
        if(empty($end)) {
          $end = $start;
        }
        if(empty($start)) {
          $start = $end;
        }

        $start_str = ($start) ? strtotime($start) : '';
        $end_str = ($end) ? strtotime($end) : $start_str;

        $event_dates = '';
        if($start || $end) {
          if($start==$end) {
            $event_dates .= date('M j, Y', strtotime($start));
          } else {

            if($start && $end) {
              //Check if same month
              if( date('m', strtotime($start))==date('m', strtotime($end)) ) {
                $start_date_month = date('M j', strtotime($start));
                $end_date_month = date('j, Y', strtotime($end));
                $event_dates .= $start_date_month . '-' . $end_date_month;
              } else {
                //Different months
                $start_date_month = date('M j', strtotime($start));
                
                //Check if start month is past, 
                //then change month to current if end date is future
                if( strtotime($start) < strtotime(date('Ymd')) ) {
                  $start_date_month = date('M j');
                } 

                $end_date_month = date('M j, Y', strtotime($end));
                $event_dates .= $start_date_month . '-' . $end_date_month;
              }
            }
          }
        }

        include( get_template_directory() . '/parts-calendar/calendar-events-tab-item.php');
      ?>  
      <?php $i++; } ?>
    </div>

    <?php 
    if($total_records>$per_page) {
      $total_pages = round($total_records/$per_page); ?>
      <div id="pagination" class="pagination pagination-links">
        <?php
            $pagination = array(
                'base' => @add_query_arg('pg','%#%'),
                'format' => '?paged=%#%',
                'mid-size' => 1,
                'current' => $paged,
                'total' => $total_pages,
                'prev_next' => True,
                'prev_text' => __( '<span class="fas fa-chevron-left"></span>' ),
                'next_text' => __( '<span class="fas fa-chevron-right"></span>' )
            );
            echo paginate_links($pagination);
        ?>
      </div>
    <?php } ?>
  </div>
  <?php } else { ?>
    <?php if ( isset($_GET['type']) ) { ?>
    <div class="records-not-found">
      <h2>No event found.</h2>
    </div>
    <?php } ?>
  <?php } ?>
</section>