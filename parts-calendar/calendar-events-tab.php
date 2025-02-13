<?php
global $table_prefix, $wpdb;
$today_date = strtotime(date('Ymd'));
$thePostTypesArr = array();
$paged = ( get_query_var( 'pg' ) ) ? absint( get_query_var( 'pg' ) ) : 1;
$filter_type = ( isset($_GET['type']) && $_GET['type'] ) ? $_GET['type'] : '';
if($filter_type=='all') {
  $filter_type = '';
}
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
    $and = ($k>0) ? ' OR ' : '';
    $thePostTypesArr[] = $type;
    $k++;
  }
}

$per_page = 15;
$offset = ($paged>1) ? ($per_page * $paged)-$per_page : 0;

if( isset($_GET['type']) && $_GET['type']!='all' ) {
  $where = '';
} else {
  $where = ($featured_event) ? 'AND p.ID!=' . $featured_event : '';
}

if( count($thePostTypesArr) == 1 ) {
  $selected_post_type = end($thePostTypesArr);
   $query_meta = "SELECT p.ID, p.post_title, p.post_name, p.post_type, ext.start_date, ext.end_date FROM ".$table_prefix."posts p, ".$table_prefix."postmeta_extension ext 
              WHERE p.ID=ext.post_id AND p.post_status='publish' AND p.post_type='".$selected_post_type."'
              AND ".strtotime(date('Ymd'))." <= UNIX_TIMESTAMP(ext.end_date)";

} else {

  $query_meta = "SELECT p.ID, p.post_title, p.post_name, p.post_type, ext.start_date, ext.end_date FROM ".$table_prefix."posts p, ".$table_prefix."postmeta_extension ext 
              WHERE p.ID=ext.post_id AND p.post_status='publish' AND p.post_type IN ('".implode("','", $thePostTypesArr)."') 
              AND ".strtotime(date('Ymd'))." <= UNIX_TIMESTAMP(ext.end_date)";

}


$new_query = $query_meta . " GROUP BY p.ID ORDER BY UNIX_TIMESTAMP(ext.start_date) ASC LIMIT ".$per_page." OFFSET ".$offset;


$posts = $wpdb->get_results($new_query);
$total = $wpdb->get_results($query_meta);
$total_records = ($total) ? count($total) : 0;
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
        if( isset($_GET['type']) && $_GET['type']!='all' ) {
          //Don't show featured image
        } else {
          if ($featured_event) {
            include( get_template_directory() . '/parts-calendar/calendar-events-featured.php');
          }
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
    $filteredType = ( isset($_GET['type']) && $_GET['type'] ) ? $_GET['type'] : '';
    if( $total_records>$per_page ) {
      $perpage_count = $total_records/$per_page;
      //$total_pages = round($total_records/$per_page); 
      $total_pages = ceil($perpage_count); 
      ?>
      <div id="hiddenData" style="display:none;"></div>
      <div id="pagination" class="pagination-wrapper loadMoreWrappe">
        <a href="javascript:void(0)" data-filter="<?php echo $filteredType ?>" data-baseurl="<?php echo get_permalink() ?>" id="loadMorePosts" data-perpage="<?php echo $per_page ?>" data-count="<?php echo $total_records ?>" data-next="2" data-total-pages="<?php echo $total_pages ?>" class="button button-pill">See More</a>
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
    var baseUrl = $(this).attr('data-baseurl');
    var filter = $(this).attr('data-filter');
    if(filter) {
      baseUrl += '?type=' + filter + '&pg=' + next;
    } else {
      baseUrl += '?pg=' + next;
    }

    //var baseUrl = $(this).attr('data-baseurl') + '?pg=' + next;
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
