<?php if ( get_row_layout() == 'cards_bottom_text' ) {  
  $card_text_intro = get_sub_field('card_text_intro'); 
  $card_columns = get_sub_field('card_columns'); 
  $card_content = get_sub_field('card_content'); 
  ?>
  <div id="section-cards_bottom_text-<?php echo $ctr ?>" class="repeatable-block section  section-cards_bottom_text">
    <div class="wrapper">
      <?php if ($card_text_intro) { ?>
      <div class="content-part text-intro">
        <?php echo anti_email_spam($card_text_intro); ?>
      </div> 
      <?php } ?>

      <?php if ($card_content) { ?>
      <div class="content-part cards-content show-<?php echo $card_columns ?>">
        <div class="flexwrap">
          <?php foreach ($card_content as $c) { 
            $image = $c['image'];
            $text = $c['text'];
            if($image || $text) { ?>
            <div class="fxcol">
              <div class="inner">
              <?php if ($image) { ?>
              <figure><img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>"></figure> 
              <?php } ?>

              <?php if ($text) { ?>
              <div class="textbox"><?php echo $text ?></div> 
              <?php } ?>
              </div>
            </div>
            <?php } ?>
          <?php } ?>
        </div>
      </div> 
      <?php } ?>
    </div>
  </div>
<?php } 