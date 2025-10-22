<?php if( get_row_layout() == 'sponsors_section' ) { ?>
  <?php  
  $html_anchor = get_sub_field('html_anchor');
  $section_title = get_sub_field('section_title');
  $sponsors = get_sub_field('sponsors');
  if($sponsors) { ?>
  <section id="<?php echo get_row_layout() ?>_<?php echo $ctr ?>" class="repeatable repeatable_<?php echo get_row_layout() ?>">
    <?php if ($html_anchor) { ?>
    <div id="<?php echo $html_anchor ?>" class="html-anchor"></div>
    <?php } ?>
    <div class="wrapper">
      <?php if ($section_title) { ?>
      <div class="page--title--wrapper">
        <h2 class="h2"><?php echo $section_title ?></h2>
      </div>
      <?php } ?>

      <?php if ($sponsors) { ?>
        <div class="sponsors">
          <?php foreach ($sponsors as $s) { 
            $logo = $s['logo'];
            $logoUrl = $s['url'];
            $logo_link = (isset($logoUrl['url']) && $logoUrl['url']) ? $logoUrl['url'] : '';
            $logo_name = (isset($logoUrl['title']) && $logoUrl['title']) ? $logoUrl['title'] : '';
            $logo_target = (isset($logoUrl['target']) && $logoUrl['target']) ? $logoUrl['target'] : '';
            if($logo) { ?>
              <figure class="sponsor-logo">
              <?php if ($logo_link) { ?>
                <a href="<?php echo $logo_link ?>" target="<?php echo $logo_target ?>">
                  <img src="<?php echo $logo['url'] ?>" alt="<?php echo $logo['title'] ?>">
                </a>
              <?php } else { ?>
                <img src="<?php echo $logo['url'] ?>" alt="<?php echo $logo['title'] ?>">
              <?php } ?>
              </figure>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  </section>
  <?php  } ?>
<?php  } ?>