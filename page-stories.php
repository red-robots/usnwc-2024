<?php
/**
 * Template Name: Stories and Inspiration
 */

get_header(); 
$filter = ( isset($_GET['filter']) && $_GET['filter'] ) ? $_GET['filter'] : '';
$filterVal = $filter;
if($filter && $filter!='all') {
  $filter = explode('_', $filter);
}
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full activities-parent">
	<?php while ( have_posts() ) : the_post(); ?>
	<div class="intro-text-wrap">
		<div class="wrapper">
			<h1 class="page-title"><span><?php the_title(); ?></span></h1>
			<div class="intro-text"><?php the_content(); ?></div>
		</div>
	</div>
	<?php endwhile;  ?>


  <?php  
  $featured_story_post_id = get_field('featured_story');
  $FocalPoint = get_field('featured_story_image_focal_point');
  $FocalPoint = ($FocalPoint) ? $FocalPoint:'center';
  $posttype = array('post','whats-new');
  $perpage = 15;
  $paged = ( get_query_var( 'pg' ) ) ? absint( get_query_var( 'pg' ) ) : 1;
  $args = array(
    'posts_per_page'  => $perpage,
    'post_type'       => $posttype,
    'paged'           => $paged,
    'post_status'     => 'publish'
  );

  if($filterVal && $filterVal!='all') {
    $args['tax_query'] = array(
      array(
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => $filter,
      )
    );
  }

  $posts = new WP_Query($args);
  $jsonBoxes = get_option('stories_boxes_width');
  $currentBoxes = ($jsonBoxes) ? json_decode($jsonBoxes) : '';
  // echo "<pre>";
  // print_r($currentBoxes);
  // echo "</pre>";

  if ( $posts->have_posts() ) { 
    $count = $posts->found_posts; 
    $stories_class = 'stories-count-'.$count;
    if($count>5) {
      $stories_class .= ' items-6-or-more';
    }

    $filter_options = array(
      'all'=>'All',
      'stories_films'=> 'Stories/Films'
    );

    $filter_text = ($filterVal && isset($filter_options[$filterVal])) ? $filter_options[$filterVal] : 'All';
  ?>

  <div id="filter-form" class="filter-form">
    <form class="filter" action="<?php echo get_permalink() ?>" method="get">
      <div class="input-field">
        <button type="button" class="filter-button" aria-expanded="false"><?php echo $filter_text ?></button>
        <div class="dropdown">
          <ul class="dropdown-options">
            <?php foreach ($filter_options as $k=>$v) { ?>
            <li>
              <a href="javascript:void(0)" data-val="<?php echo $k ?>"><?php echo $v ?></a>
            </li>  
            <?php } ?>
          </ul>
        </div>
      </div>
    </form>
  </div>

  <section class="stories-feeds-section ">
    <div class="wrapper stories-wrapper">
      <div id="featured__story" class="featured-story-container"></div>
      <div id="container" class="flexwrap stories-entries <?php echo $stories_class ?>">
        <?php $ctr=1; while ( $posts->have_posts() ) : $posts->the_post(); 
          $post_id = get_the_ID();
          $thumbnail_id = get_post_thumbnail_id($post_id);
          $photo = wp_get_attachment_image_url($thumbnail_id,'full');
          if(!$photo) {
            $photo = get_template_directory_uri() . '/assets/img/photo-coming-soon.jpg';
          }
          $excerpt = get_field('custom_excerpt');
          $btn = get_field('readmore');
          $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
          $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
          $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
          $options = ['25% | 4:5','25% | 1:1','50%','75%','100%'];
          $v_width = $options[0];
          if( isset($currentBoxes->$post_id) && $currentBoxes->$post_id ) {
            $obj = $currentBoxes->$post_id;
            $v_width = $obj->width;
          }
          $is_featured_story = ($post_id==$featured_story_post_id) ? 'true' : '';
          ?>
          <div class="storyBlock animated fadeIn resizable" data-index="<?php echo $ctr ?>" data-is-featured="<?php echo $is_featured_story ?>" data-pid="<?php echo $post_id ?>" tabindex="0">
            <div class="inner">
              <?php if ($is_featured_story) { ?>
              <figure data-image-focal="<?php echo $FocalPoint ?>">
                <img src="<?php echo $photo ?>" alt="<?php echo get_the_title() ?>" />
              </figure>
              <?php } else { ?>
              <figure>
                <img src="<?php echo $photo ?>" alt="<?php echo get_the_title() ?>" />
              </figure>
              <?php } ?>
              
              <div class="post-title"><?php the_title(); ?></div>
              <?php if ($excerpt || ($btnUrl && $btnTitle)) { ?>
              <div class="textwrap">
                <?php if ($excerpt) { ?>
                <div class="excerpt"><?php echo $excerpt ?></div>
                <?php } ?>
                <?php if ($btnUrl && $btnTitle) { ?>
                <div class="buttonBlock">
                  <a href="<?php echo $btnUrl ?>" class="button button-pill" target="<?php echo $btnTarget ?>"><?php echo $btnTitle ?></a>
                </div>
                <?php } ?>
              </div>
              <?php } ?>
            </div>
          </div>

        <?php $ctr++; endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
    <div id="hiddenData" style="display:none;"></div>

    <?php
    $total_pages = $posts->max_num_pages;
    $found_posts = $posts->found_posts;
    if ($found_posts > $perpage) { ?>
    <div id="pagination" class="pagination-wrapper loadMoreWrapper">
      <a href="javascript:void(0)" data-baseurl="<?php echo get_permalink() ?>" id="loadMorePosts" data-next="2" data-total-pages="<?php echo $total_pages ?>" class="button button-pill">See More</a>
    </div>
    <?php } ?>

  </section>

  <?php } ?>

</div><!-- #primary -->
<?php if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) { ?>
<!-- <button id="saveBoxesWidth">Save Boxes Width</button> -->
<?php } ?>

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

    $('#hiddenData').load(baseUrl + ' .stories-entries', function(){
      if( $('#hiddenData .storyBlock').length ) {
        var items = $('#hiddenData .stories-entries').html();
        $('.stories-wrapper .stories-entries').append(items);
        $('#hiddenData').html("");
      }
      if(nextPlus>totalPages) {
        loadMoreButton.hide();
      }
    });
  });

  $('#saveBoxesWidth').insertAfter('.site-footer');
  $('body').on('change','.resizeBlock select.blockSize',function(e){
    var num = $(this).val();
    $(this).parents('.storyBlock').attr('data-width', num);

    if(num=='25% | 1:1') {
      $(this).parents('.storyBlock').addClass('square');
      $(this).parents('.storyBlock').css('width', '25%');
    } else if(num=='25% | 4:5') {
      $(this).parents('.storyBlock').removeClass('square');
      $(this).parents('.storyBlock').addClass('long');
      $(this).parents('.storyBlock').css('width', '25%');
    } else {  
      $(this).parents('.storyBlock').css('width', num);
    }
  });

  $('body').on('click','#saveBoxesWidth',function(e){
    e.preventDefault();
    var pid = $(this).attr('data-pid');
    var boxWidth = $(this).parents('.resizeBlock').find('select').val();
    var postIds = [];
    var boxesWidth = [];
    $('.resizeBlock select').each(function(){
      var percent = $(this).val();
      var nameVal = $(this).attr('name').replace('entry_','');
      postIds.push(nameVal);
      boxesWidth.push(percent);
    });

    $.ajax({
      url : frontajax.ajaxurl,
      type : 'post',
      dataType : "json",
      data : {
        action  : 'assignBoxWidth',
        postid : postIds,
        width : boxesWidth
      },
      beforeSend:function(){
        $("#loaderDiv").show();
      },
      success:function( obj ) {
        $("#loaderDiv").hide();
        console.log(obj);
      },
      error:function(request, status, error) {
        console.log(error);
        console.log(request.responseText);
      }
    });
  });

  if( $('[data-is-featured]').length ) {
    $('[data-is-featured]').each(function(){
      var entry = $(this);
      var isFeatured = $(this).attr('data-is-featured');
      if(isFeatured=='true') {
        entry.addClass('hidden');
        entry.clone().addClass('is-featured-post').appendTo('#featured__story');
      }
    });
  }

}); 
</script>
<?php
get_footer();
