<?php if( get_row_layout() == 'columns_bottom_text' ) { 
  $has_intro = get_sub_field('add_intro');
  $intro_title = get_sub_field('section_title');
  $intro_text = get_sub_field('section_text');
  $columns_per_row = get_sub_field('columns_per_row');
  $numrow = ($columns_per_row) ? $columns_per_row : 3;
  $columns_content = get_sub_field('infocol');
  $data_section = '';
  if($has_intro) {
    $data_section = ($intro_title) ? ' data-section="'.$intro_title.'"':'';
  }
  if( $columns_content ) { ?>
  <section id="columns_bottom_text-<?php echo $ctr ?>" class="section_columns_bottom_text"<?php echo $data_section ?>>
    <div class="full-wrapper">
      <?php if ($has_intro) { ?>
        <?php if ($intro_title || $intro_text) { ?>
          <div class="wrapper introtext-wrapper<?php echo ($intro_text) ? ' has-section-text':''?>">
            <?php if ($intro_title) { ?>
            <div class="titleWrapper"><h2 class="stitle"><?php echo $intro_title ?></h2></div>
            <?php } ?>
            <?php if ($intro_text) { ?>
            <div class="textWrapper"><?php echo anti_email_spam($intro_text) ?></div>
            <?php } ?>
          </div>
        <?php } ?>
      <?php } ?>

      <?php if ($columns_content) { ?>
      <div class="columns-wrapper numrow-<?php echo $numrow ?>">
        <div class="flexwrap">
          <?php foreach ($columns_content as $c) { 
            $image = $c['image'];
            $has_border = $c['border_image'];
            $title = $c['title'];
            $description = $c['description'];
            $btn = $c['button'];
            $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
            $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
            $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
            $image_class = ($image) ? 'has-image':'no-image';
            $image_class .= ($has_border) ? ' has-border' : '';
            ?>
            <div class="infobox">
              <div class="inner">
                <figure class="<?php echo $image_class; ?>">
                  <?php if ($image) { ?>
                  <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>" />
                  <?php } ?>
                </figure>
                <?php if ($title || $description) { ?>
                <div class="details">
                  <?php if ($title) { ?>
                  <h2 class="info-title"><?php echo $title ?></h2>
                  <?php } ?>
                  <?php if ($description) { ?>
                  <div class="info-text"><?php echo anti_email_spam($description) ?></div>
                  <?php } ?>
                  <?php if ($btnName && $btnLink) { ?>
                  <div class="button-block">
                    <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="button"><span><?php echo $btnName ?></span></a>
                  </div>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>  
      <?php } ?>
    </div>
  </section>
  <?php } ?>
<?php  } ?>