<?php if( get_row_layout() == 'cards_carousel_popup' ) {
  $section_title = get_sub_field('section_title');
  $has_section_intro = get_sub_field('has_section_intro');
  $section_text = get_sub_field('section_text');
  $bgcolor = get_sub_field('bgcolor');
  $bgcolor = ($bgcolor) ? $bgcolor : 'white';
  $cards = get_sub_field('cards');
  $cards = ($cards) ? array_values( array_filter( $cards, function($c){ return !empty($c['title']) || !empty($c['image']); } ) ) : array();
  $section_id = ($section_title) ? 'cards-carousel--' . sanitize_title($section_title) : 'cards-carousel--' . $ctr;
  $popups = array();
  if($cards) { ?>
  <section id="<?php echo esc_attr($section_id) ?>"<?php echo ($section_title) ? ' data-section="'.esc_attr($section_title).'"' : '' ?> class="cards_carousel_popup_section bg-<?php echo esc_attr($bgcolor) ?>">
    <div class="wrapper">

      <?php if ( $section_title || ($has_section_intro && $section_text) ) { ?>
      <div class="title-wrapper">
        <?php if ($section_title) { ?>
        <div class="shead-icon text-center">
          <h2 class="stitle"><?php echo esc_html($section_title) ?></h2>
        </div>
        <?php } ?>
        <?php if ($has_section_intro && $section_text) { ?>
        <div class="text-wrap"><?php echo anti_email_spam($section_text) ?></div>
        <?php } ?>
      </div>
      <?php } ?>

      <div class="cards-carousel">
        <div class="swiper cards-carousel-swiper">
          <div class="swiper-wrapper">
            <?php foreach ($cards as $i => $card) {
              $c_image = $card['image'];
              $c_title = $card['title'];
              $c_text = $card['short_description'];
              $c_popup = trim( (string) $card['popup_content'] );
              $c_button = ($card['button_text']) ? $card['button_text'] : 'Learn More';
              $modal_id = 'cards-carousel-' . $ctr . '-' . ($i + 1) . '-modal';
              if ($c_popup) {
                $popups[] = array( 'id' => $modal_id, 'title' => $c_title, 'text' => $c_popup );
              } ?>
            <div class="swiper-slide">
              <article class="ccard">
                <figure class="ccard-image">
                  <?php if ($c_image) {
                    echo bellaworks_image( $c_image, 'large', array( 'alt' => ($c_image['alt']) ? $c_image['alt'] : $c_title, 'loading' => 'lazy', 'sizes' => '(min-width: 1200px) 380px, (min-width: 600px) 45vw, 85vw' ) );
                  } ?>
                </figure>
                <div class="ccard-body">
                  <?php if ($c_title) { ?>
                  <h3 class="ccard-title"><?php echo esc_html($c_title) ?></h3>
                  <?php } ?>
                  <?php if ($c_text) { ?>
                  <p class="ccard-text"><?php echo esc_html($c_text) ?></p>
                  <?php } ?>
                  <?php if ($c_popup) { ?>
                  <div class="ccard-button">
                    <a href="#<?php echo esc_attr($modal_id) ?>" data-toggle="modal" data-target="#<?php echo esc_attr($modal_id) ?>" class="btn-sm xs" role="button"><span><?php echo esc_html($c_button) ?></span></a>
                  </div>
                  <?php } ?>
                </div>
              </article>
            </div>
            <?php } ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
        <button type="button" class="cards-carousel-nav cards-carousel-prev" aria-label="Previous"><span></span></button>
        <button type="button" class="cards-carousel-nav cards-carousel-next" aria-label="Next"><span></span></button>
      </div>

    </div>
  </section>

  <?php
  // Popups use the theme's shared modal markup; page-flexible-content.php moves .customModal after #page.
  foreach ($popups as $p) {
    $modal_id = $p['id'];
    $modal_title = $p['title'];
    $modal_text = $p['text'];
    $buttons = array();
    include( locate_template('parts/flexible-content-popup.php') );
  }
  unset($modal_id, $modal_title, $modal_text, $buttons);
  } ?>
<?php } ?>
