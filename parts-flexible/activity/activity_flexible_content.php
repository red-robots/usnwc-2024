<?php //$activity_flexcontent = get_field('flexibleContent') ?>
<?php if( have_rows('activity_flexible_content') ) {  
$placeholder = THEMEURI . 'images/rectangle.png';
?>
<div class="flexibleContentWrap">
<section class="activity-flexible-content-wrapper">
  <?php $actr=1; while( have_rows('activity_flexible_content') ): the_row(); 
    if( get_row_layout() == 'text_and_image_block' ) {  
      $section_title = get_sub_field('section_title');
      $section_text = get_sub_field('section_text');
      $has_section_intro = get_sub_field('has_section_intro');
      $content_columns = get_sub_field('content_columns');
      $column_style = get_sub_field('column_style'); ?>
      <?php if ($section_title) { ?>
      <div id="column-style--<?php echo sanitize_title($section_title) ?>" data-section="<?php echo $section_title ?>" class="text_and_image_block_section column-style column-style-<?php echo $actr ?> <?php echo ($column_style) ? ' '.$column_style:''?>">
      <?php } else { ?>
      <div class="column-style column-style-<?php echo $actr ?> <?php echo ($column_style) ? ' '.$column_style:''?>">
      <?php } ?>

          <?php if ( $section_title || ($has_section_intro && $section_text) ) { ?>
          <div class="wrapper title-wrapper <?php echo ($has_section_intro && $section_text) ? ' has-section-text':' no-section-text'; ?>">
            <?php if ($section_title ) { ?>
            <div class="titlediv">
              <div class="shead-icon text-center">
                <h2 class="stitle"><?php echo $section_title ?></h2>
              </div>
            </div>
            <?php } ?>

            <?php if ($has_section_intro && $section_text) { ?>
            <div class="text-wrap"><?php echo anti_email_spam($section_text) ?></div>
            <?php } ?>
          </div>
          <?php } ?>


        <?php if( have_rows('content_columns') ) { ?>

          <section id="two-columns-block_<?php echo $actr ?>" data-columns="<?php echo $column_style ?>" class="three-columns-block three-columns-block-v2 section-content section-grid-images">
            <div class="entryList flexwrap">
            <?php $ni=1; while( have_rows('content_columns') ): the_row(); ?>
              <?php if( get_row_layout() == 'flex_content_block' ) { ?>
                <?php  
                $single_image = get_sub_field('single_image');
                $s_title = get_sub_field('title'); 
                $s_text = get_sub_field('description');  
                $boxClass = ( ($s_title || $s_text) && $single_image ) ? 'half':'full';
                $dataSection = ( $section_title ) ? '' : 'data-section="'.$s_title.'"';
                if($s_title || $s_text) { 
                  $colClass = ($ni % 2) ? ' odd':' even'; ?>
                  <div id="entryBlock<?php echo $ni?>_parent_<?php echo $actr?>" class="entryBlock fbox <?php echo ($single_image) ? 'hasImage':'noImage'; ?>">
                    <div class="inside text-center">
                      <div class="imagediv <?php echo ($single_image) ? 'hasImage':'noImage'?>">
                        <?php if ($single_image) { ?>
                        <span class="img" style="background-image:url('<?php echo $single_image['url']?>')"></span>
                        <?php } ?>
                        <img src="<?php echo $placeholder ?>" alt="" aria-hidden="true" class="placeholder">
                      </div>

                      <?php if ($s_title || $s_text) { ?>
                      <div class="details">
                        <?php if ($s_title) { ?>
                        <h3 class="h3"><?php echo $s_title ?></h3>
                        <?php } ?>

                        <?php if ($s_text) { ?>
                        <div class="text"><?php echo anti_email_spam($s_text) ?></div>
                        <?php } ?>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                <?php } ?>
              <?php } ?>
              <?php $ni++; endwhile;  ?>
            </div>
          </section>

        <?php } ?>


      </div>
    <?php } ?>
  <?php $actr++; endwhile; ?>
</section>
</div>
<?php } ?>