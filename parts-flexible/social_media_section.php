<?php if( get_row_layout() == 'social_media_section' ) { ?>
  <?php  
  $section_title = get_sub_field('section_title');
  $section_text = get_sub_field('section_text');
  $has_section_intro = get_sub_field('has_section_intro');
  $display_social_media = get_sub_field('display_social_media');
  $data_section = ($section_title) ? ' data-section="'.$section_title.'"':'';


  

  if($display_social_media) { ?>
  <div id="social-media-section-<?php echo $ctr ?>" class="fullwidth-float-left social_media_section"<?php echo $data_section ?>>
    
    <?php if ($section_title ) { ?>
    <div class="title-wrapper no-border text-center <?php echo ($has_section_text && $section_text) ? ' has-section-text':' no-section-text'; ?>">
      <div class="midwrapper">
        <div class="inner">
          <?php if ($section_title ) { ?>
          <h2 class="section-title"><?php echo $section_title ?></h2>
          <?php } ?>
          <?php if ($has_section_text && $section_text) { ?>
          <div class="section-text"><?php echo anti_email_spam($section_text) ?></div>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>


    <?php 
    $social_media = [
      'facebook'  =>  '<i class="fa-brands fa-facebook"></i>',
      'instagram' =>  '<i class="fa-brands fa-instagram"></i>',
      'twitter'   =>  '<i class="fa-brands fa-square-x-twitter"></i>',
      'youtube'   =>  '<i class="fa-brands fa-youtube"></i>',
      'vimeo'     =>  '<i class="fa-brands fa-vimeo"></i>',  
      'linkedin'  =>  '<i class="fa-brands fa-linkedin"></i>'
    ];
    $social_order = get_field('social_links_order', 'option'); ?>

    <div class="social-media-links">
    <?php if ($social_order) { $socialParts = explode(",", $social_order); ?>
      <?php foreach ($socialParts as $a) { 
        $social = trim($a); 
        $link =  get_field($social,'option');
        $icon = ( isset($social_media[$social]) ) ? $social_media[$social] : '';
        if($link) { ?>
        <a href="<?php echo $link ?>" target="_blank"><?php echo $icon ?><span class="sr-only"><?php echo $social ?></span></a>
        <?php } ?>
      <?php } ?>
      
    <?php } else { ?>
      
      <?php foreach ($social_media as $social=>$icon) { 
        $link =  get_field($social,'option');
        if($link) { ?>
        <a href="<?php echo $link ?>" target="_blank"><?php echo $icon ?><span class="sr-only"><?php echo $social ?></span></a>
        <?php } ?>
      <?php } ?>

    <?php } ?>
    </div>

  </div>
  <?php  } ?>

<?php  } ?>