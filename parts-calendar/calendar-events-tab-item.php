<div class="infoBox animated fadeIn post-type--<?php echo $post_type ?>">
  <div class="wrap">
    <figure>
      <?php if ($thumbnail && isset($thumbnail['url'])) { ?>
        <img src="<?php echo $thumbnail['url'] ?>" alt="" />
      <?php } else { ?>
        <img src="<?php echo get_template_directory_uri(); ?>/images/image-not-available.jpg" alt="" />
      <?php } ?>
    </figure>
    <div class="text">
      <h3><?php echo $page_title; ?></h3>
      <?php if ($event_dates) { ?>
      <div class="event-date"><?php echo $event_dates ?></div>
      <?php } ?>
      <?php if ($short_description) { ?>
      <div class="short-description"><?php echo $short_description ?></div>
      <?php } ?>
      <div class="buttonwrap">
        <?php if ($post_type=='music') { ?>
        <a href="javascript:void(0)" data-url="<?php echo get_permalink($post_id); ?>" data-action="ajaxGetPageData" data-id="<?php echo $post_id ?>" class="more-btn popupDetailsBtn">See Details</a>
        <?php } else { ?>
        <a href="<?php echo $pagelink; ?>" class="more-btn">See Details</a>
        <?php } ?>
      </div>
    </div>
  </div>
</div>