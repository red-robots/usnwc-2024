<?php if( get_row_layout() == 'image_cards_columns' ) { ?>
  <?php  
  $html_anchor = get_sub_field('html_anchor');
  $section_title = get_sub_field('section_title');
  $section_text = get_sub_field('section_text');
  $text_position = get_sub_field('section_text_position');
  $cards = get_sub_field('cards');
  $buttons = get_sub_field('buttons');
  if($section_title || $section_text) { ?>
  <section id="image-cards-columns-<?php echo $ctr ?>" class="image_cards_columns text--<?php echo $text_position ?>">
    <?php if ($html_anchor) { ?>
    <div id="<?php echo $html_anchor ?>" class="html-anchor"></div>
    <?php } ?>
    <div class="wrapper">
      <?php if($section_title || $section_text) { ?>
      <div class="title-wrapper text-center">
        <?php if ($section_title) { ?>
          <h2 class="stitle"><?php echo $section_title ?></h2>
        <?php  } ?>

        <?php if ($text_position=='top') { ?>
          <?php if ($section_text) { ?>
            <div class="text-wrap text-center"><?php echo anti_email_spam($section_text) ?></div>
          <?php  } ?>
        <?php } ?>
        
      </div>
      <?php  } ?>

      <?php if ($cards) { ?>
      <div class="cards">
        <div class="flexwrap">
          <?php foreach ($cards as $c) { 
            $img = $c['image'];
            $category = $c['category_name'];
            $btn = $c['link'];
            $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
            $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
            $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
            if($img) { ?>
            <div class="imageBlock">
              <figure>
                <?php if($btnName && $btnLink) { ?>
                <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="imageLink">
                  <?php if($category || $btnName) { ?>
                  <span class="titleInfo">
                    <?php if($category) { ?>
                    <span class="cardCategory"><?php echo $category ?></span>
                    <?php } ?>

                    <?php if($btnName) { ?>
                    <span class="cardTitle"><?php echo $btnName ?></span>
                    <?php } ?>
                  </span>
                  <?php } ?>
                  <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
                </a>
                <?php } else { ?>
                  <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
                <?php } ?>
              </figure>
            </div>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
      <?php  } ?>

      <?php if ($text_position=='bottom') { ?>
        <?php if ($section_text) { ?>
        <div class="bottom-text">
          <div class="text-wrap text-center"><?php echo anti_email_spam($section_text) ?></div>
        </div>
        <?php  } ?>
      <?php } ?>

      <?php if ($buttons) { ?>
        <div class="cta-buttons">
        <?php foreach ($buttons as $b) { 
          $btn = $b['button'];
          $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
          $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
          $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
          if($btnName && $btnLink) { ?>
          <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="button button-pill-outline-white uppercase"><span><?php echo $btnName ?></span></a>
          <?php } ?>
        <?php } ?>
        </div>
      <?php } ?>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>