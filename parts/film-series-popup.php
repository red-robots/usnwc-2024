<?php if( isset($post) && $post ) { 
  $post_id = $post->ID;
  $full_image = get_field('full_image', $post_id);
  $content = $post->post_content;
  if($content) {
    $content = apply_filters('the_content', $content);
  }

  $buttons = get_field('call-to-actions', $post_id);
  ?>
<div class="popup-content-wrapper details-film-series">
  <h2 class="eventName"><?php echo $post->post_title; ?></h2>
  <?php if ($full_image) { ?>
  <figure class="featured-image">
    <img src="<?php echo $full_image['url'] ?>" alt="<?php echo $full_image['title'] ?>" class="post-image" />
    <div class="banner-bottom">
      <img src="<?php echo get_stylesheet_directory_uri() ?>/images/banner-bottom.svg" alt="">
    </div>
  </figure>
  <?php } ?>

  <?php if ( $post->post_content ) { ?>
  <div class="event-intro">
    <?php echo $content ?>
    <?php if ($buttons) { ?>
    <div class="buttonBlock">
      <?php foreach ($buttons as $b) {
        $btn = $b['ctabutton']; 
        $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
        $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
        $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
        if($btnUrl && $btnTitle) { ?>
        <a href="<?php echo $btnUrl ?>" target="<?php echo $btnTarget ?>" class="button button-red"><?php echo $btnTitle ?></a>
        <?php } ?>
      <?php } ?>
    </div>
    <?php } ?>
  </div>
  <?php } ?>


  <?php  
  $start_date = get_field('start_date', $post_id);
  $event_time = get_field('event_time', $post_id);
  $cost_free = get_field('cost_free', $post_id);
  $cost = get_field('cost', $post_id);
  $weather = get_field('weather', $post_id);
  $film_disclaimer = get_field('film_disclaimer', $post_id);
  ?>

  <div class="details-wrapper">
    <h2 class="section-title">Event Details</h2>
    <div class="infoboxes">

      <div class="infobox">
        <div class="infoTitle">Date</div>
        <?php if($start_date) { ?>
        <div class="infoText infoDate">
          <?php if($start_date) { ?>
            <div class="date-day"><?php echo date('l', strtotime($start_date)); ?></div>
            <div class="date-full"><?php echo date('m/d/Y', strtotime($start_date)); ?></div>
          <?php } ?>
        </div>
        <?php } ?>
      </div>

      <div class="infobox">
        <div class="infoTitle">Time</div>
        <?php if($event_time) { ?>
        <div class="infoText infoDate">
          <div class="info"><?php echo $event_time ?></div>
        </div>
        <?php } ?>
      </div>

      <div class="infobox">
        <div class="infoTitle">Price</div>
        <?php if($cost_free || $cost) { ?>
        <div class="infoText infoDate">
          <div class="info">FREE</div>

          <?php if($cost) { ?>
          <p class="cost-info"><?php echo $cost ?></p>
          <?php } ?>
        </div>
        <?php } ?>
      </div>

    </div>

    <?php if($weather) { ?>
    <div class="otherInfo">
      <h3>Weather</h3>
      <div class="info"><?php echo $weather ?></div>
    </div>
    <?php } ?>

    <?php if($film_disclaimer) { ?>
    <div class="otherInfo disclaimer">
      <div class="info"><?php echo $film_disclaimer ?></div>
    </div>
    <?php } ?>
  </div>
  


</div>
<?php } ?>