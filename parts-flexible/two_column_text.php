<?php if( get_row_layout() == 'two_column_text' ) { 
  $column1 = get_sub_field('column1');
  $column2 = get_sub_field('column2');
  
  $bgcolor = get_sub_field('bgcolor');
  $divider = get_sub_field('show_vertical_divider_line');
  $section_class = ($divider) ? ' has-vertical-line':'';
  $has_intro = get_sub_field('include_intro_title_and_text_twocol');
  $intro_title = get_sub_field('title');
  $intro_text = get_sub_field('text');
  $section_data_title = ($has_intro && $intro_title) ? ' data-section="'.$intro_title.'"':'';
  ?>
  <section id="section-fullwidth-black-<?php echo $ctr?>" class="mscol fullwidth two_column_text_section section-fullwidth-<?php echo $bgcolor?><?php echo $section_class?>"<?php echo $section_data_title?>>
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
    <div class="wrapper">
      <div class="flexwrap">
        <?php if ($column1) { ?>
        <div class="fxcol column1">
          <div class="inside">
            <?php echo ($column1) ? anti_email_spam($column1) : '' ?>
          </div>
        </div> 
        <?php } ?>

        <?php if ($column2) { ?>
        <div class="fxcol column2">
          <div class="inside">
            <?php echo ($column2) ? anti_email_spam($column2) : '' ?>
          </div>
        </div> 
        <?php } ?>
      </div>
    </div>
  </section>
<?php  } ?>