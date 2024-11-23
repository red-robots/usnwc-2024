 <?php if( get_row_layout() == 'image_and_text_row' ) { ?>
  <?php  
  $title_top = get_sub_field('title_top');
  $main_title = get_sub_field('main_title');
  $content = get_sub_field('content');
  $image = get_sub_field('image');
  $image_position = get_sub_field('image_position');
  $layout = ( ($main_title || $content) && $image ) ? 'half':'full';
  $layout .= ' image-position-' . $image_position;
  ?>
  <div class="flexibleContentDiv <?php echo $layout ?>">
    <div class="flexwrap">
      <?php if ($image) { ?>
      <div class="fxcol imageCol">
        <figure>
          <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>" />
        </figure>
      </div>
      <?php } ?>

      <?php if ($main_title || $content) { ?>
      <div class="fxcol textCol">
        <div class="inside">
          <?php if ($title_top || $main_title) { ?>
          <div class="titleDiv">
            <?php if ($title_top) { ?>
            <div class="title-sm"><?php echo $title_top ?></div>
            <?php } ?>
            <?php if ($main_title) { ?>
            <h2 class="title"><?php echo $main_title ?></h2>
            <?php } ?>
          </div>
          <?php } ?>

          <?php if ($content) { ?>
          <div class="contentDiv">
            <?php echo anti_email_spam($content) ?>
          </div>
          <?php } ?>
        </div>
      </div>  
      <?php } ?>

    </div>
  </div>
<?php } ?>