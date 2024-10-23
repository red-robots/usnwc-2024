<?php if( get_row_layout() == 'events' ) { 
  $section_title = get_sub_field('section_title');
  $has_section_text = get_sub_field('has_section_text');
  $section_text = get_sub_field('section_text');
  $events = get_sub_field('events_content');
  if($section_title || $events) { ?>
  <section id="events-cards-format-<?php echo $ctr ?>" data-section="<?php echo $section_title ?>" class="events-cards-format cards-format-line-items">
    <?php if ($section_title || $has_section_text) { ?>
    <div class="introWrap">
      <div class="wrapper">
        <div class="titlediv<?php echo ($has_section_text && $section_text) ? ' hasText':' noText'; ?>">
          <?php if ($section_title) { ?>
          <h2 class="stitle"><?php echo $section_title ?></h2> 
          <?php } ?>
          <?php if ($has_section_text && $section_text) { ?>
          <div class="stext"><?php echo anti_email_spam($section_text) ?></div> 
          <?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>

    <?php if ($events) { ?>
    <div class="eventsCards">
      <div class="wrapper">
        <div class="flexwrap">
          <?php foreach ($events as $e) { 
          $name = $e['title'];
          $img = $e['image'];
          $lineItems = $e['line_items'];
          if ($img) {  ?>
          <div class="infocard">
            <div class="inner">
              <figure>
                <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
              </figure>
              <?php if ($name) { ?>
              <h3 class="card-title"><?php echo $name ?></h3> 
              <?php } ?>
              <?php if ($lineItems) { ?>
              <div class="lineItems">
                <?php foreach ($lineItems as $m) { 
                  $mtitle = $m['title'];
                  $mtext = $m['description'];
                  $mbtn = $m['buttonLink'];
                  $mBtnName = (isset($mbtn['title']) && $mbtn['title']) ? $mbtn['title'] : '';
                  $mBtnUrl = (isset($mbtn['url']) && $mbtn['url']) ? $mbtn['url'] : '';
                  $mBtnTarget = (isset($mbtn['target']) && $mbtn['target']) ? $mbtn['target'] : '_self';
                  //$button_attribute = '';
                  // if( $mBtnUrl ) {
                  //   if ( (strpos($mBtnUrl, ':') !== false) && strpos($mBtnUrl, 'data-accesso-package') !== false ) {
                  //     $mBtnUrl = str_replace('#','',$mBtnUrl);
                  //     $parts = explode(':', $mBtnUrl);
                  //     $button_attribute = $parts[0] . '=' . $parts[1];
                  //     $button_attribute = trim( preg_replace('/\s+/', '', $button_attribute) );
                  //     $mBtnUrl = '#';
                  //   }
                  // }

                  $attribute = $m['link_attribute'];
                  $button_attribute = ($attribute) ? $attribute:'';

                  $right_text = $m['right_text'];

                  if ($mtitle) { ?>
                  <div class="line-item">
                    <div class="info">
                      <span class="name"><?php echo $mtitle ?></span>

                      <?php if ( $right_text && $mBtnName ) { ?>
                        <span class="right-info">
                          <?php if ( $right_text ) { ?>
                          <span class="right-text"><?php echo $right_text ?></span>
                          <?php } ?>
                          <?php if ( $mBtnName && $mBtnUrl ) { ?>
                          <a href="<?php echo $mBtnUrl ?>" target="<?php echo $mBtnTarget ?>" <?php echo $button_attribute ?> class="link"><?php echo $mBtnName ?></a>
                          <?php } ?>
                        </span>
                      <?php } else { ?>

                        <?php if ($right_text) { ?>
                          <span class="right-text-span"><?php echo $right_text ?></span>
                        <?php } else { ?>
                          <?php if ( $mBtnName && $mBtnUrl ) { ?>
                          <a href="<?php echo $mBtnUrl ?>" target="<?php echo $mBtnTarget ?>" <?php echo $button_attribute ?> class="link"><?php echo $mBtnName ?></a>
                          <?php } ?>
                        <?php } ?>

                      <?php } ?>
                    </div>
                    <?php if ($mtext) { ?>
                    <div class="desc"><?php echo anti_email_spam($mtext) ?></div>
                    <?php } ?>
                  </div>  
                  <?php } ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div>
          </div>
          <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>
  </section>
  <?php  } ?>
<?php  } ?>
