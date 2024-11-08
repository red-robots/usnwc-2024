<?php if( get_row_layout() == 'image_and_text_two_column' ) { ?>
  <?php  
  $section_title = get_sub_field('section_title');
  $image_position = get_sub_field('image_position');
  $image = get_sub_field('image');
  $text_content = get_sub_field('text_content');
  $buttons = get_sub_field('buttons');
  $section_class = ($text_content && $image) ? 'half' : 'full';
  if($section_title || $text_content) { ?>
  <section id="image-text-two-column-<?php echo $ctr ?>" class="image_and_text_two_column section-two_column_row">
    <div class="section-inner<?php echo ($image_position) ? ' image-'.$image_position:'' ?>">
      <div class="flexwrap <?php echo $section_class ?>">
        <?php if ($section_title || $section_text) { ?>
        <div class="textBlock">
          <div class="inside">
            <?php if ($section_title) { ?>
              <h2><?php echo $section_title ?></h2>
            <?php } ?>
            <?php echo anti_email_spam($text_content) ?>

            <?php if ($buttons) { ?>
              <div class="cta-buttons buttondiv">
              <?php foreach ($buttons as $b) { 
                $btn = $b['button'];
                $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
                $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
                $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
                if($btnName && $btnLink) { ?>
                <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="btn-sm xs"><span><?php echo $btnName ?></span></a>
                <?php } ?>
              <?php } ?>
              </div>
            <?php } ?>
          </div>
        </div>
        <?php } ?>

        <?php if ($image) { ?>
        <div class="imageBlock">
          <figure>
            <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>" />
          </figure>
        </div>
        <?php } ?>
      </div>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>