<?php if( get_row_layout() == 'cards_row' ) { 
  $card_title = get_sub_field('card_title');
  $card_text = get_sub_field('card_text');
  $cards_multiple = get_sub_field('cards_multiple');
  if($cards_multiple) { ?>
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
      <?php if ($cards_multiple) { ?>
      <div class="cardsWrapper">
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
    </div>
  </section>
  <?php  } ?>
<?php  } ?>