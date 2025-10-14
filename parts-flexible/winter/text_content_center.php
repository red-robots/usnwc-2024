<?php if( get_row_layout() == 'text_content_center' ) { ?>
  <?php  
  $html_anchor = get_sub_field('html_anchor');
  $section_title = get_sub_field('main_title');
  $section_text = get_sub_field('details');
  $buttons = get_sub_field('buttons');
  if($section_title || $section_text) { ?>
  <section id="text_content_center_<?php echo $ctr ?>" class="text_content_center_block">
    <?php if ($html_anchor) { ?>
    <div id="<?php echo $html_anchor ?>" class="html-anchor"></div>
    <?php } ?>
    <div class="wrapper textCenter">
      <?php if ($section_title) { ?>
      <div class="page--title--wrapper">
        <h2 class="h2"><?php echo $section_title ?></h2>
      </div>
      <?php } ?>

      <?php if ($section_text) { ?>
      <div class="textwrap"><?php echo anti_email_spam($section_text) ?></div>
      <?php } ?>

      <?php if ($buttons) { ?>
      <div class="buttonsBlock">
      <?php foreach ($buttons as $b) { 
        $btn = $b['button'];
        $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
        $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
        $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
        if($btnName && $btnLink) { ?>
        <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="button button-red"><span><?php echo $btnName ?></span></a>
        <?php } ?>
      <?php } ?>
      </div>
      <?php } ?>

    </div>
  </section>
  <?php  } ?>
<?php  } ?>