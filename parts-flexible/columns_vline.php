<?php if( get_row_layout() == 'columns_vline' ) { 
  $column_data = get_sub_field('column_data'); 
  if($column_data) { ?>
  <section id="column-data-<?php echo $ctr ?>" class="column-data-section column-count-<?php echo count($column_data) ?>">
    <div class="wrapper">
      <div class="flexwrap">
        <?php foreach ($column_data as $d) { 
          $title = $d['title'];
          $text = $d['text'];
          $btn = $d['link'];
          $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
          $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
          $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
          ?>
        <div class="fxcol">
          <div class="inside">
            <?php if ($title) { ?>
            <p class="title-small"><?php echo $title ?></p>
            <?php } ?>
            <?php if ($text) { ?>
            <p class="text-large"><?php echo $text ?></p>
            <?php } ?>

            <?php if ($btnName && $btnLink) { ?>
            <div class="button-simple">
              <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="simple-link"><?php echo $btnName ?> <i class="fa-light fa-angle-right"></i></a>
            </div>
            <?php } ?>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>