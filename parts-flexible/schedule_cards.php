<?php if( get_row_layout() == 'schedule_cards' ) { ?>
  <?php  
  $schedule_cards_row = get_sub_field('schedule_cards_content');
  $section_title = get_sub_field('section_title');
  $section_text = get_sub_field('section_text');
  $has_section_text = get_sub_field('has_section_text');
  $data_section = ($section_title) ? ' data-section="'.$section_title.'"':'';
  if($schedule_cards_row || $section_title) { ?>
  <div id="schedule-cards-row-<?php echo $ctr ?>" class="fullwidth-float-left schedule_cards_section"<?php echo $data_section ?>>
    <div class="title-wrapper<?php echo ($has_section_text && $section_text) ? ' has-section-text':' no-section-text'; ?>">
      <div class="midwrapper">
        <div class="inner">
          <?php if ($section_title ) { ?>
          <h2 class="section-title"><?php echo $section_title ?></h2>
          <?php } ?>
          <?php if ($has_section_text && $section_text) { ?>
          <div class="section-text"><?php echo anti_email_spam($section_text) ?></div>
          <?php } ?>
        </div>
      </div>
    </div>

    <?php if ($schedule_cards_row) { $cards_count = count( $schedule_cards_row ); ?>
    <div class="cardsContainer">
      <div class="midwrapper">
        <div class="flexwrap cards-count-<?php echo $cards_count ?>">
          <?php $card_ctr=1; 
          while( have_rows('schedule_cards_content') ): the_row(); ?>
            <?php if( get_row_layout() == 'card_info' ) { 
              $title = get_sub_field('title');
              $date = get_sub_field('date');
              $image = get_sub_field('image');
              $schedule_items = get_sub_field('schedule_items');
              $buttons = get_sub_field('buttons');
            ?>
            <div class="infocard">
              <div class="inside">

                <?php if ($cards_count>1) { ?>
                  <?php if ($date ) { ?>
                  <div class="cardTopTitle"><?php echo $date ?></div>
                  <?php } ?>
                <?php } ?>
                
                <?php if ($image) { ?>
                <figure class="cardImage">
                  <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>" />
                </figure>
                <?php } ?>

                <?php if ($cards_count>1) { ?>
                  <?php if ($title) { ?>
                  <h3 class="cardMainTitle"><?php echo $title ?></h3>
                  <?php } ?>
                <?php } ?>

                <?php if ($schedule_items) { ?>
                <div class="schedule-items-wrap NoDescription">

                  <?php if ($cards_count==1) { ?>
                    <?php if ($title) { ?>
                    <h3 class="cardMainTitle"><?php echo $title ?></h3>
                    <?php } ?>
                    <?php if ($date ) { ?>
                    <div class="cardTopTitle"><?php echo $date ?></div>
                    <?php } ?>
                  <?php } ?>

                  <?php foreach ($schedule_items as $m) { 
                    $m_event = $m['title'];
                    $m_time = $m['time'];
                    
                    if ($m_event) {  ?>
                    <div class="dash-item">
                      <div class="line">
                        <span class="name"><?php echo $m_event ?></span>
                        <?php if ($m_time ) { ?>
                          <span class="right-info">
                            <?php if ($m_time) { ?>
                              <span class="text"><?php echo $m_time ?></span> 
                            <?php } ?>
                          </span>
                        <?php } ?>
                      </div>
                    </div>
                    <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>

                <?php if ($buttons) { ?>
                <div class="buttonsWrapper">
                  <?php foreach ($buttons as $b) { 
                    //$attribute = ($b['button_attribute']) ? ' ' . $b['button_attribute'] : '';
                    $btn = $b['button'];
                    $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
                    $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
                    $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
                    if($btnName && $btnLink) { ?>
                    <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="button button-red"><span><?php echo $btnName ?></span></a>
                    <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
          <?php $card_ctr++; endwhile;  ?>
        </div>
      </div>
    </div>
    <?php } ?>

  </div>
  <?php  } ?>

<?php  } ?>