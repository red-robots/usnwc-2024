<?php if( get_row_layout() == 'cards_row' ) { 
  $card_title = get_sub_field('card_title');
  $card_text = get_sub_field('card_text');
  $card_format = get_sub_field('card_format');
  $card_type = ($card_format=='card_style2') ? $card_format : 'card_style1';
  $cards_multiple = get_sub_field('cards_multiple');
  $cards_text_below = get_sub_field('cards_text_below');
  if( $cards_multiple || $cards_text_below ) { ?>
  <section id="cards-multiple-<?php echo $ctr ?>" class="cards-multiple-wrapper" data-section="<?php echo $card_title ?>">
    <div class="full-wrapper">
      <?php if ($card_title || $card_text) { ?>
        <div class="wrapper intro-wrapper">
          <?php if ($card_title) { ?>
          <div class="titleWrapper"><h2 class="stitle"><?php echo $card_title ?></h2></div>
          <?php } ?>
          <?php if ($card_text) { ?>
          <div class="textWrapper"><?php echo anti_email_spam($card_text) ?></div>
          <?php } ?>
        </div>
      <?php } ?>


      <?php if ($card_type=='card_style1' && $cards_multiple) { ?>
      <div class="cardsWrapper <?php echo $card_type ?>">
        <?php foreach ($cards_multiple as $c) { 
          $img = $c['image'];
          $btn = $c['cards_title'];
          $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
          $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
          $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
          if($img) { ?>
          <div class="imageWrap">
            <figure>
              <?php if ($btnName && $btnUrl) { ?>
              <a href="<?php echo $btnUrl ?>" target="<?php echo $btnTarget ?>" class="card-link">
                <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
                <div class="imageText">
                  <div class="title"><?php echo $btnName ?></div>
                </div>
              </a>
              <?php } else { ?>
              <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
              <?php } ?>
            </figure>
          </div>  
          <?php } ?>
        <?php } ?>
      </div>
      <?php } ?>


      <?php if ($card_type=='card_style2' && $cards_text_below) { ?>
      <div class="cardsWrapper count-<?php echo count($cards_text_below) ?> <?php echo $card_type ?>">
        <?php foreach ($cards_text_below as $c) { 
          $img = $c['card_image'];
          $card_item_title = $c['card_item_title'];
          $card_item_details = $c['card_item_details'];
          $btn = $c['card_item_button'];
          $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
          $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
          $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
          if($img) { ?>
          <div class="imageWrap">
            <figure>
              <div class="image">
                <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
              </div>
              <?php if ($card_item_title || $card_item_details) { ?>
              <figcaption>
                <?php if ($card_item_title) { ?>
                 <h3 class="card-title"><?php echo $card_item_title ?></h3> 
                <?php } ?>
                <?php if ($card_item_details) { ?>
                 <div class="card-details"><?php echo anti_email_spam($card_item_details) ?></div> 
                <?php } ?>
              </figcaption>
              <?php } ?>

              <?php if ($btnName && $btnUrl) { ?>
              <div class="buttonBlock">
                <a href="<?php echo $btnUrl ?>" target="<?php echo $btnTarget ?>" class="button"><?php echo $btnName ?></a>
              </div>
              <?php } ?>
            </figure>
          </div>  
          <?php } ?>
        <?php } ?>
      </div>
      <?php } ?>


    </div>
  </section>
  <?php  } ?>
<?php  } ?>