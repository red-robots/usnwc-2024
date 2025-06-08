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

$special_events_taxonomy = 'special-event-category';
$special_events_terms = get_terms([
  'taxonomy'    => $special_events_taxonomy,
  'hide_empty'  => true,
  'exclude'     => 1
]);

$special_types = []; 
$special_events_categories = []; 
if($special_events_terms) {
  foreach($special_events_terms as $se) {
    $se_name = trim($se->name);
    $se_slug = $se->slug;
    $special_events_categories[] = $se_slug;
    $uniq = '';
    if($cpttypes) {
      foreach($cpttypes as $k=>$v) {
        if( strtolower($v) == strtolower($se_name) ){
          $special_types[$se_slug] = $se_name;
        }
      }
      if( !array_key_exists($se_slug, $special_types) ) {
        $cpttypes[$se_slug] = $se_name;
      }
    }
  }
}


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
  $query_meta = "SELECT p.ID, p.post_title, p.post_name, p.post_type, p.post_parent, ext.start_date, ext.end_date 
              FROM ".$table_prefix."posts p, ".$table_prefix."postmeta_extension ext 
              WHERE p.ID=ext.post_id AND p.post_status='publish' ".$where." AND p.post_type='".$selected_post_type."' 
              AND ".strtotime(date('Ymd'))." <= UNIX_TIMESTAMP(ext.end_date)";
} else {
  $query_meta = "SELECT p.ID, p.post_title, p.post_name, p.post_type, p.post_parent, ext.start_date, ext.end_date 
                FROM ".$table_prefix."posts p, ".$table_prefix."postmeta_extension ext 
                WHERE p.ID=ext.post_id AND p.post_status='publish' ".$where." AND p.post_type IN ('".implode("','", $thePostTypesArr)."') 
                AND ".strtotime(date('Ymd'))." <= UNIX_TIMESTAMP(ext.end_date)";
}

$total_query = $query_meta . " GROUP BY p.ID ORDER BY UNIX_TIMESTAMP(ext.start_date) ASC";



if( isset($_GET['type']) && $_GET['type']!='all' ) {
  $posts = $wpdb->get_results($total_query);
} else {
  $new_query = $query_meta . " GROUP BY p.ID ORDER BY UNIX_TIMESTAMP(ext.start_date) ASC LIMIT ".$per_page." OFFSET ".$offset;
  $posts = $wpdb->get_results($new_query);
}

$total = $wpdb->get_results($total_query);
//$total_before = ($total) ? count($total) : 0;

$hasChildrenPosts = [];
if($total) {
  foreach($total as $ii=>$e) {
    $posttype = $e->post_type;
    if($posttype=='dining') {
      $post_parent = $e->post_parent;
      $has_children = postHasChildren($e->ID, $posttype);
      if($has_children) {
        $hasChildrenPosts[] = $e->ID;
        unset($total[$ii]);
      }
    }
  }
}

if($hasChildrenPosts) {
  $posts = array();
  $missing = count($hasChildrenPosts);
  $remains = $per_page-$missing;
  $new_per_page = $remains+$missing;
  $start = $offset;
  $end = $start + $per_page;

  $posts = array();
  $new_posts = array();
  if($total) {
    $new_total = array_values($total);
    for($i=$start; $i<$end; $i++) {
      if( isset($new_total[$i]) ) {
        $item = $new_total[$i];
        $posts[] = $item;
      }
    }
  }
}

$filteredSpecialEventTerms = array();
if($special_events_terms && $cpttypes) {
  if($filter_type && $filter_type!='all') {
    //print_r($special_types);
    foreach($special_events_terms as $se) {
      $se_name = trim($se->name);
      $se_slug = $se->slug;
      $uniq = '';
      if($cpttypes) {
        foreach($cpttypes as $k=>$v) {
          if($filter_type==$k) {
            if( strtolower($v) == strtolower($se_name) ){
              $filteredSpecialEventTerms[$k] = $se_slug;
            } else {
              $filteredSpecialEventTerms[$se_slug] = $se_slug;
            }
          }
        }
      }
    }
  } 
}

$total_records = ($total) ? count($total) : 0;

if($special_events_categories) {
  if($filter_type && $filter_type!='all') {
    $countExisting = ($posts) ? count($posts) : 0; 
    if( isset($filteredSpecialEventTerms[$filter_type]) && $filteredSpecialEventTerms[$filter_type] ) {
      $special_term = $filteredSpecialEventTerms[$filter_type];
      $se_args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'special-events',
        'post_status'      => 'publish',
        'tax_query'        => array(
          array(
            'taxonomy' => 'special-event-category',
            'terms' => $special_term,
            'field' => 'slug',
            'include_children' => true,
            'operator' => 'IN'
          )
        )
      );
      $se_post = get_posts($se_args);
      if($se_post) {
        $total_records += count($se_post);

        $j=$countExisting;
        foreach($se_post as $se) {
          $start_date = get_field('start_date', $se->ID);
          $end_date = get_field('end_date', $se->ID);
          $se->start_date = $start_date;
          $se->end_date = $end_date;
          $posts[$j] = $se;
          $j++;
        }
      }
    }
  } 

  if($posts) {
    foreach($posts as $p) {
      $start__date = (isset($p->start_date) && $p->start_date) ? $p->start_date : '';
      $start__date = ($start__date) ? str_replace('-','',$start__date) : '';
      $start__date = ($start__date) ? strtotime($start__date) : '';
      $p->start_date_unix = $start__date;
    }
  }

  usort($posts, function($a, $b) {
    return $a->start_date_unix - $b->start_date_unix;
  });
}

if( isset($_GET['type']) && $_GET['type']!='all' ) {
  if($posts) {
    $posts_paged = [];
    $ix = $offset;
    $max = $per_page * $paged;
    $j=1;
    for($i=$offset; $i<$max; $i++) {
      if( isset($posts[$i]) ) {
        $entry = $posts[$i];
        $posts_paged[$i] = $entry;
      }
    }
    $posts = [];
    $posts = $posts_paged;
  }
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
        $post_parent = ( isset($p->post_parent) && $p->post_parent ) ? $p->post_parent : '';

        //$post_parent = $p->post_parent;
        $pagelink = get_permalink($post_id);
        //$is_featured = isFeaturedEvent($pagelink);

        $thumbnail = '';
        $short_description = get_field('event__short_description', $post_id);
        if( get_field('thumbnail_image', $post_id) ) {
          $thumbnail = get_field('thumbnail_image', $post_id);
        }
        if( $post_type=='dining' ) {
          //$thumbnail = get_field('mobile-banner', $post_id);
          $thumbnail = get_field('post_image_thumb', $post_id);
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
