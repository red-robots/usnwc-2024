<?php
global $wpdb;
$prefix = $wpdb->prefix;
$today_date = strtotime(date('Ymd'));
$where_post_types = '';
$paged = ( get_query_var( 'pg' ) ) ? absint( get_query_var( 'pg' ) ) : 1;
$filter_type = ( isset($_GET['type']) && $_GET['type'] ) ? $_GET['type'] : '';
$featured_event = get_field('featured_event','option'); /* will return a post id */

$cpttypes = [
  'dining'      => 'Dining',
  'festival'    => 'Festivals',
  'film-series' => 'Film Series',
  'music'       => 'River Jam',
  'race'        => 'Races',
  'wildwoods'   => 'Wildwoods',
  'special-events'   => 'Special Events'
];

$selected_filter_name = ($filter_type && isset($cpttypes[$filter_type])) ? $cpttypes[$filter_type]:'';
$postTypes = $cpttypes;
$the_post_types = array();

if($filter_type && $filter_type!='all') {
  foreach($postTypes as $k=>$v) {
    if($k!=$filter_type) {
      unset($postTypes[$k]);
    }
  }
}


if($postTypes) {
  $k=0;
  foreach($postTypes as $type=>$title) {
    $the_post_types[] = $type;
    $and = ($k>0) ? ' OR ' : '';
    $where_post_types .= $and . 'pp.post_type="'.$type.'"';
    $k++;
  }
}

$where_post_types_condition = ( count($postTypes)==1 ) ? $where_post_types : "(".$where_post_types.")";
$per_page = 15;
$total_records = 0;
$posts = '';
$offset = ($paged>1) ? ($per_page * $paged)-$per_page : 0;

$is_filtered = ( isset($_GET['type']) && $_GET['type']!='all' ) ? true : false;

if($the_post_types) {
  $query = "SELECT p.ID, p.post_title, p.post_type, pxt.post_id, pxt.start_date, pxt.end_date  
            FROM ".$prefix."postmeta_extension pxt, ".$prefix."posts p, ".$prefix."postmeta m  
            WHERE p.ID=pxt.post_id AND p.ID=m.post_id AND p.post_status='publish' AND p.post_type IN ('".implode("','",$the_post_types)."')  
            AND m.meta_key='start_date' AND ".strtotime(date('Ymd'))." BETWEEN UNIX_TIMESTAMP(pxt.start_date) AND UNIX_TIMESTAMP(pxt.end_date)";
  

  if($featured_event) {
    $query .= " AND p.ID!=" . $featured_event ." ";
  }
  $query_limit = $query . " ORDER BY pxt.start_date ASC, pxt.end_date DESC LIMIT ".$per_page." OFFSET ".$offset;

  $total = $wpdb->get_results($query);
  $total_records = ($total) ? count($total) : 0;
  $posts = $wpdb->get_results($query_limit);
}

?>
<section id="events-feed" class="calendar-tab-events-posts<?php echo ($filter_type && $filter_type!='all') ? ' is-filtered':'' ?>">
  <div data-selected="<?php echo ($filter_type) ? $filter_type :'all' ?>" data-baseUrl="<?php echo get_permalink() ?>" class="custom-dropdown dropdown-posttypes">
    <div class="selectwrap">
      <button class="select-event-type selector"><span><?php echo ($selected_filter_name) ? $selected_filter_name :'Type' ?></span></button>
    </div>
    <div class="dropdown-inner">
      <ul class="dropdownlist">
        <li class="option default<?php echo ($filter_type) ? '' :' hidden' ?>"><a href="javascript:void(0)" class="select-posttype" data-val="all">All</a></li>
      <?php foreach ($cpttypes as $type => $title) { 
        $is_selected = ($filter_type && $filter_type==$type) ? ' selected':'';
        if($type!='special-events') { ?>
        <li class="option<?php echo $is_selected ?>"><a href="javascript:void(0)" class="select-posttype" data-val="<?php echo $type ?>"><?php echo $title ?></a></li>
        <?php } ?>
      <?php } ?>
      </ul>
    </div>
  </div>

  <?php if( $posts ) { ?>
  <div class="records-container">
    <div class="flexwrap">
      <?php 

        if ($featured_event && $is_filtered==false) {
          include( get_template_directory() . '/parts-calendar/calendar-events-featured.php');
        }

        $CURRENT_DATE = date('Ymd');
        $i=1; foreach ($posts as $p) { 
        $post_id = $p->ID;
        $post_type = $p->post_type;
        $pagelink = get_permalink($post_id);
        //$is_featured = isFeaturedEvent($pagelink);

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

        //print_r($start);

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

                if( date('d', strtotime($start))==date('d', strtotime($end)) ) {
                  $event_dates .= date('M j, Y', strtotime($start));
                } else {
                  $event_dates .= $start_date_month . '-' . $end_date_month;
                }
                
              } else {
                //Different months
                $start_date_month = date('M j', strtotime($start));


                //Check if start month is past, 
                //then change month to current if end date is future
                // if( strtotime($start) < strtotime(date('Ymd')) ) {
                //   $start_date_month = date('M j');
                // } 

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
    if( $total_records>$per_page ) {
      $perpage_count = $total_records/$per_page;
      $total_pages = ceil($perpage_count); 
      ?>
      <div id="hiddenData" style="display:none;"></div>
      <div id="pagination" class="pagination-wrapper loadMoreWrappe">
        <a href="javascript:void(0)" data-baseurl="<?php echo get_permalink() ?>" id="loadMorePosts" data-perpage="<?php echo $per_page ?>" data-count="<?php echo $total_records ?>" data-next="2" data-total-pages="<?php echo $total_pages ?>" class="button button-pill">See More</a>
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

<script>
jQuery(document).ready(function($){

  $(document).on('click','#loadMorePosts', function(e){
    e.preventDefault();
    var loadMoreButton = $(this);
    var d = new Date();
    var next = $(this).attr('data-next');
    var nextPlus = parseInt(next) + 1;
    var totalPages = parseInt( $(this).attr('data-total-pages') );
    var baseUrl = $(this).attr('data-baseurl') + '?pg=' + next;
    loadMoreButton.attr('data-next', nextPlus);

    $('#hiddenData').load(baseUrl + ' .records-container .flexwrap', function(){
      if( $('#hiddenData .infoBox').length ) {
        if( $('#hiddenData .flexwrap .infoBox.first').length ) {
          $('#hiddenData .flexwrap .infoBox.first').remove();
        }
        var items = $('#hiddenData .flexwrap').html();
        $('.records-container .flexwrap').append(items);
        $('#hiddenData').html("");
      }

      //nextPlus = nextPlus + 1;
      if(nextPlus>totalPages) {
        loadMoreButton.hide();
      }
    });
  });

}); 
</script>
