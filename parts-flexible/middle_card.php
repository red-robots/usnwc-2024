<?php if( get_row_layout() == 'middle_card' ) { 
  $content_top = get_sub_field('content_top');
  $content_bottom = get_sub_field('content_bottom');
  $image = get_sub_field('image');
  if($content_top || $content_bottom) { ?>
  <section id="section-middle_card-<?php echo $ctr?>" class="fullwidth section-middle-card"> 
    <div class="wrapper">
      <div class="flexwrap">

        <div class="fxcol left">
          <div class="inside">
            <?php if ($content_top) { ?>
            <div class="top"><?php echo $content_top ?></div>
            <?php } ?>

            <?php if ($content_bottom) { ?>
            <div class="bottom">
              <div class="wrap">
                <?php echo $content_bottom ?>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>

        <?php if ($image) { ?>
          <div class="fxcol right">
            <figure>
              <img src="<?php echo $image['url'] ?>" alt="" />
            </figure>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>