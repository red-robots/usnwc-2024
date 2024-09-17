<?php if( have_rows('flexible_blocks') ) { ?>
  <?php $i=1; while( have_rows('flexible_blocks') ): the_row(); ?>

    <?php if( get_row_layout() == 'image_cards' ) { 
      $cards = get_sub_field('cards');
      if($cards) { $count = count($cards); ?>
      <section id="section-image-cards-<?php echo $i ?>" class="repeatable-section section-image-cards image-cards-<?php echo $count ?>">
        <div class="inner-content">
          <div class="flexwrap">
            <?php foreach ($cards as $row) { 
              $title = $row['title'];
              $small_text = $row['small_text'];
              $image = $row['image'];
              $buttons = $row['buttons'];
              if($image) { ?>
              <div class="imagecard">
                <figure>
                  <div class="image-wrapper">
                    <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>">
                    <?php if ($title || $small_text) { ?>
                  </div>
                  <figcaption>
                    <?php if ($title) { ?>
                    <h3><?php echo $title ?></h3>
                    <?php } ?>
                    <?php if ($small_text) { ?>
                    <div class="info"><?php echo $small_text ?></div>
                    <?php } ?>
                  </figcaption>
                  <?php } ?>
                </figure>
                <?php if ($buttons) { ?>
                <div class="buttons-wrapper">
                  <?php foreach ($buttons as $b) { 
                    $btn = $b['button'];
                    $button_type = $b['button_type'];
                    $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
                    $btnTitle = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
                    $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
                    if($btnTitle && $btnLink) { ?>
                    <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="button button-type-<?php echo $button_type ?>"><?php echo $btnTitle ?></a>
                    <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
              <?php } ?>
            <?php } ?>
          </div>
        </div>
      </section>
      <?php } ?>
    <?php } ?>

    <?php if( get_row_layout() == 'intro' ) { 
      $intro_title = get_sub_field('title');
      $intro_text = get_sub_field('text');
      if($intro_title || $intro_text) { ?>
      <section id="section-intro-<?php echo $i ?>" class="repeatable-section section-intro">
        <div class="inner-content">
          <?php if($intro_title) { ?>
            <h2 class="section-title h1"><?php echo $intro_title ?></h2>
          <?php } ?>
          <?php if($intro_text) { ?>
            <div class="section-text"><?php echo $intro_text ?></div>
          <?php } ?>
        </div>
      </section>
      <?php } ?>
    <?php } ?>

  <?php $i++; endwhile; ?>
<?php } ?>