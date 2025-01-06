<?php
global $wpdb;
$prefix = $wpdb->prefix;
$today_date = strtotime(date('Ymd'));
$where_post_types = '';
$paged = ( get_query_var( 'pg' ) ) ? absint( get_query_var( 'pg' ) ) : 1;
$filter_type = ( isset($_GET['type']) && $_GET['type'] ) ? $_GET['type'] : '';

$featured_event = get_field('featured_event','option'); /* will return a post id */

// $cpttypes = [
//   'festival'    => 'Festivals',
//   'race'        => 'Races',
//   'film-series' => 'Film Series',
//   'dining'      => 'Dining',
//   'music'       => 'River Jam',
//   'wildwoods'   => 'Wildwoods'
// ];

$cpttypes = [
  'dining'      => 'Dining',
  'festival'    => 'Festivals',
  'film-series' => 'Film Series',
  'music'       => 'River Jam',
  'race'        => 'Races',
  'wildwoods'   => 'Wildwoods'
];

$the_post_types = array();
foreach($cpttypes as $k=>$v) {
  $the_post_types[] = $k;
}

$selected_filter_name = ($filter_type && isset($cpttypes[$filter_type])) ? $cpttypes[$filter_type]:'';
$postTypes = $cpttypes;

// if($filter_type && $filter_type!='all') {
//   foreach($postTypes as $k=>$v) {
//     if($k!=$filter_type) {
//       unset($postTypes[$k]);
//     }
//   }
// }



$today = date('Y-m-d');
// $args = [
//     'post_type'   => $cpttypes,
//     'posts_per_page' => -1,
//     'post_status' => 'publish',
//     'order'=>'DESC',
//     'orderby'=>'meta_value_num',
//     'meta_key'=>'start_date',
//     'meta_query' => array(
//         'relation' => 'AND',
//         array(
//           'key'     => 'start_date',
//           'value'   =>  $today,
//           'compare' => '>'
//         ),
//         array(
//           'key' => 'start_date',
//           'compare' => 'EXISTS',
//         ),
//     ),
// ];

$perpage = 15;
$args = [
  'post_type'   => $the_post_types,
  'posts_per_page'   => $perpage,
  'post_status' => 'publish',
  'order'=>'ASC',
  'orderby'=>'meta_value',
  'meta_key'=>'start_date',
];

if($featured_event) {
  $args['post__not_in'] = array($featured_event);
}
//$post_items = get_posts( $args );
$post_items = new WP_Query($args);
$total_pages = $post_items->max_num_pages;
$count = $post_items->found_posts;

// echo "<pre>";
// print_r($args);
// echo "</pre>";
?>


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

      if(nextPlus>totalPages) {
        loadMoreButton.hide();
      }
    });
  });

}); 
</script>
