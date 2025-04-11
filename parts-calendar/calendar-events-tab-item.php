<?php 
$parentId = ( isset($post_parent) && $post_parent ) ? $post_parent : ''; 
if ($parentId && $post_type=='dining') {
  $pagelink = get_permalink($parentId);
}
?>
<div class="infoBox animated fadeIn post-type--<?php echo $post_type ?>" data-postid="<?php echo $post_id ?>" data-parent-id="<?php echo $parentId ?>">
  <div class="wrap">
    <?php if ($post_type=='music') { ?>
      <a href="javascript:void(0)" class="image-post-link popUp post---<?php echo $post_type ?>">
    <?php } else if($post_type=='film-series') { ?>
      <a href="javascript:void(0)" class="image-post-link is-calendar-page button-popup-details" data-postid="<?php echo $post_id ?>">
    <?php } else { ?>
      <a href="<?php echo $pagelink; ?>" class="image-post-link">
    <?php } ?>
    
      <figure>
        <?php if ($thumbnail && isset($thumbnail['url'])) { ?>
          <img src="<?php echo $thumbnail['url'] ?>" alt="" />
        <?php } else { ?>
          <img src="<?php echo get_template_directory_uri(); ?>/images/image-not-available.jpg" alt="" />
        <?php } ?>
      </figure>
    </a>
    <div class="text">
      <?php if ($parentId && $post_type=='dining') { ?>
        <h3 data-parent-id="<?php echo $parentId ?>" class="child-title"><?php echo get_the_title($parentId); ?></h3>
      <?php } else { ?>
        <h3><?php echo $page_title; ?></h3>
      <?php } ?>

      <?php if ($event_dates) { ?>
      <div class="event-date"><?php echo $event_dates ?></div>
      <?php } ?>
      <?php if ($short_description) { ?>
      <div class="short-description"><?php echo $short_description ?></div>
      <?php } ?>
      <div class="buttonwrap">
        <?php if ($post_type=='music') { ?>
        <a href="javascript:void(0)" data-url="<?php echo get_permalink($post_id); ?>" data-action="ajaxGetPageData" data-id="<?php echo $post_id ?>" class="more-btn popupDetailsBtn">See Details</a>
        <?php } else if($post_type=='film-series') { ?>
          <a href="javascript:void(0)" class="more-btn is-calendar-page button-popup-details" data-postid="<?php echo $post_id ?>">See Details</a>
        <?php } else { ?>
        <a href="<?php echo $pagelink; ?>" class="more-btn">See Details</a>
        <?php } ?>
      </div>
    </div>
  </div>
</div>