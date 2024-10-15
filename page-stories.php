<?php
/**
 * Template Name: Stories and Inspiration
 */

get_header(); 
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
  $posttype = array('post','whats-new');
  $perpage = 12;
  $paged = ( get_query_var( 'pg' ) ) ? absint( get_query_var( 'pg' ) ) : 1;
  $args = array(
    'posts_per_page'  => $perpage,
    'post_type'       => $posttype,
    'paged'           => $paged,
    'post_status'     => 'publish'
  );
  $posts = new WP_Query($args);
  $jsonBoxes = get_option('stories_boxes_width');
  $currentBoxes = ($jsonBoxes) ? json_decode($jsonBoxes) : '';
  // echo "<pre>";
  // print_r($currentBoxes);
  // echo "</pre>";

  if ( $posts->have_posts() ) { ?>
  <section class="stories-feeds-section">
    <div class="wrapper stories-wrapper">
      <div id="container" class="flexwrap stories-entries">
        <?php while ( $posts->have_posts() ) : $posts->the_post(); 
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


          ?>
          <div class="storyBlock animated fadeIn resizable" tabindex="0" data-width="<?php echo $v_width ?>">
            <div class="inner">
              <?php if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) { ?>
              <div class="resizeBlock">
                <a href="<?php echo get_edit_post_link($post_id); ?>" target="_blank" title="Edit Post" class="editPostLink"><i class="fa-sharp fa-light fa-pencil"></i></a>

                <select name="entry_<?php echo $post_id?>" class="blockSize">
                  <?php foreach ($options as $k=>$e) { 
                    $is_selected = ($k==0) ? ' selected':'';
                    if($v_width) {
                      if($e==$v_width) {
                        $is_selected = ' selected';
                      }
                    } else {
                      $is_selected = '';
                    }
                  ?>
                  <option value="<?php echo $e ?>"<?php echo $is_selected ?>><?php echo $e ?></option>
                  <?php } ?>
                </select>
                <button class="saveButton" data-pid="<?php echo $post_id?>">Save</button>
              </div>
              <?php } ?>
              <figure>
                <img src="<?php echo $photo ?>" alt="<?php echo get_the_title() ?>" />
              </figure>
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

        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
    <div id="hiddenData" style="display:none;"></div>

    <?php
    $total_pages = $posts->max_num_pages;
    if ($total_pages > $perpage) { ?>
    <div id="pagination" class="pagination-wrapper loadMoreWrapper">
      <a href="javascript:void(0)" data-baseurl="<?php echo get_permalink() ?>" id="loadMorePosts" data-next="2" data-total-pages="<?php echo $total_pages ?>" class="button button-red">See More</a>
    </div>
    <?php } ?>

  </section>

  <?php } ?>

</div><!-- #primary -->
<?php if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) { ?>
<button id="saveBoxesWidth">Save Boxes Width</button>
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
      },
      error:function(request, status, error) {
        console.log(error);
        console.log(request.responseText);
      }
    });
  });

}); 
</script>
<?php
get_footer();
