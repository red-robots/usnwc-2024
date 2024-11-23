<?php if( get_row_layout() == 'text_and_image_block' ) { ?>
  <?php  
  $section_title = get_sub_field('section_title');
  $section_text = get_sub_field('section_text');
  $has_section_intro = get_sub_field('has_section_intro');
  $content_columns = get_sub_field('content_columns');
  $column_style = get_sub_field('column_style');
  $has_dash_items = get_sub_field('add_dash_items');
  $line_items_dash = get_sub_field('line_items_dash');
  ?>
  <?php if ($section_title) { ?>
    <div id="column-style--<?php echo sanitize_title($section_title) ?>" data-section="<?php echo $section_title ?>" class="text_and_image_block_section column-style column-style-<?php echo $ctr ?> <?php echo ($column_style) ? ' '.$column_style:''?>">
  <?php } else { ?>
    <div class="column-style column-style-<?php echo $ctr ?> <?php echo ($column_style) ? ' '.$column_style:''?>">
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



        <?php if ($column_style=='column2') { ?>
        <section id="two-columns-block_<?php echo $ctr ?>" class="two-columns-block text-and-image-blocks">
          <div class="columns-2">
            <?php $i=1; foreach ($content_columns as $col) { 

              // $map_image_type = get_sub_field('map_image_type');
              // $map_embed_code = get_sub_field('map_embed_code');
              $map_image_type = ( isset($col['map_image_type']) ) ? $col['map_image_type'] : '';
              $map_embed_code = ( isset($col['map_embed_code']) ) ? $col['map_embed_code'] : '';
              $is_image_col = ($map_image_type=='map') ? false : true;

              $e_title_top = $col['title_top'];
              $e_title = $col['title'];
              $e_text = $col['description']; 
              $fullText = $col['description']; 
              $shorttext = $col['shorttext']; 
              $has_dash_items = $col['add_dash_items']; 
              $line_items = $col['line_items_dash']; 
              $image_position = $col['force_image_position']; 
              $colClass = ($i % 2) ? ' odd':' even';
              $image_type = $col['image_type'];
              $single_image = $col['single_image'];
              $has_image = false;
              $images = ( $image_type && isset($col[$image_type.'_image']) ) ? $col[$image_type.'_image'] : '';
              $slides = array();
              if($image_type=='single') {
                if( $single_image ) {
                  $slides[0]['url'] = $single_image['url'];
                  $slides[0]['title'] = $single_image['title'];
                }
              } else {
                $slides = $col['gallery_image'];
              }
              $buttons = $col['buttons'];
              $boxClass = ( ($e_title || $e_text) && $slides ) ? 'half':'full';
              $textType = ($shorttext) ? 'shorttext':'fulltext';
              if($shorttext) {
                $e_text = $shorttext;
              }
              $popupContent = ( isset($col['content_display_type']) && $col['content_display_type'] ) ? true : false;
              $imagePos = ($image_position) ? ' image-position-'.$image_position : '';
              $dataSection = ( $section_title ) ? '' : 'data-section="'.$e_title.'"';

              $is_repeater_dashed_items = ( isset($col['is_repeater_dashed_items']) ) ? $col['is_repeater_dashed_items'] : '';
              $repeater_dashed_items = ( isset($col['repeater_dashed_items']) ) ? $col['repeater_dashed_items'] : '';

              if( ($e_title || $e_text) || $slides || ($has_dash_items && $line_items)) { $colClass = ($i % 2) ? ' odd':' even'; ?>

                <div id="section<?php echo $i?>_parent<?php echo $ctr?>" <?php echo $dataSection?> class="mscol <?php echo $boxClass.$colClass.$imagePos ?>">

                  <?php if ( $e_title || $e_text || ($has_dash_items && $line_items) ) { ?>
                  <div class="textcol">
                    <div class="inside">
                      <div class="info">
                        <?php if ($e_title_top || $e_title) { ?>
                        <div class="titlediv">
                          <?php if ($e_title_top) { ?>  
                            <div class="titletop"><?php echo $e_title_top ?></div>
                          <?php } ?>
                          <?php if ($e_title) { ?>
                            <h3 class="mstitle"><?php echo $e_title ?></h3>
                          <?php } ?>
                        </div>
                        <?php } ?>

                        <?php if ( ($e_text || $buttons) || ($has_dash_items && $line_items) || ($is_repeater_dashed_items && $repeater_dashed_items) ) { ?>
                          <div class="textwrap text-center">
                            <?php if ($e_text) { ?>
                            <div class="mstext <?php echo $textType ?>"><?php echo $e_text ?></div>
                            <?php } ?>

                            <!-- DASHED ITEMS -->
                            <?php include( locate_template('parts-flexible/schedule_dashed_items.php') ); ?>
                            <!-- end of DASHED ITEMS -->


                            <?php if ($popupContent) { ?>
                              <?php  
                              $modal_id = "textcol2".$i."_parent_".$ctr."_modal";
                              $modal_title = $e_title;
                              $modal_text = $fullText;
                              ?>
                              <div class="cta-buttons buttondiv">
                                <a data-toggle="modal" data-target="#<?php echo $modal_id ?>" class="button"><span>See Details</span></a>
                              </div>
                              <?php include( locate_template('parts/flexible-content-popup.php') ); ?>
                            <?php } else { ?>
                              <?php if ($buttons) { ?>
                                <div class="cta-buttons buttondiv">
                                <?php foreach ($buttons as $b) { 
                                  $btn = $b['button'];
                                  $attribute = ($b['button_attribute']) ? ' ' . $b['button_attribute'] : '';
                                  $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
                                  $btnLink = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
                                  $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
                                  if($btnName && $btnLink) { ?>
                                  <a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>"<?php echo $attribute ?> class="button"><span><?php echo $btnName ?></span></a>
                                  <?php } ?>
                                <?php } ?>
                                </div>
                              <?php } ?>
                            <?php } ?>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <?php } ?>

                  <?php if ( $is_image_col ) { ?>
                    <?php if ( $slides ) { 
                    $slideCount = count($slides);
                    $flexClass = ($slideCount==1) ? 'noFlex':'flexslider';
                    $slide_id = ($slideCount==1) ? 'staticImage':'slideShow';
                    ?>
                    <div class="gallerycol">
                      <div id="<?php echo $slide_id ?>" class="flexslider">
                        <ul class="slides">
                          <?php $helper = THEMEURI . 'images/rectangle-narrow.png'; ?>
                          <?php foreach ($slides as $s) { ?>
                            <li class="slide-item" style="background-image:url('<?php echo $s['url']?>')">
                              <img src="<?php echo $helper ?>" alt="" aria-hidden="true" class="placeholder">
                              <img src="<?php echo $s['url'] ?>" alt="<?php echo $s['title'] ?>" class="actual-image" />
                            </li>
                          <?php } ?>
                        </ul>
                      </div>
                    </div>  
                    <?php } ?>
                  
                  <?php } else { ?>

                    <?php if( $map_image_type=='map' ) { ?>
                    <div class="gallerycol mapCol">
                      <div class="mapframe">
                        <?php echo $map_embed_code ?>
                      </div>
                    </div>
                    <?php } ?>

                  <?php } ?>

                </div>
              <?php } ?>

            <?php $i++; } ?>
          </div>
        </section>
        <?php } ?>

        <?php if ($column_style=='column3') { ?>
        <section id="three-columns-block_<?php echo $ctr ?>" data-section="<?php echo $section_title ?>" class="three-columns-block section-content section-grid-images">
          <div class="entryList flexwrap">
            <?php $i=1; foreach ($content_columns as $col) {
            $e_title = $col['title'];
            $shorttext = $col['shorttext']; 
            $e_text = $col['description']; 
            $fullText = $col['description']; 
            $has_dash_items = $col['add_dash_items']; 
            $line_items = $col['line_items_dash']; 
            $is_repeater_dashed_items = $col['is_repeater_dashed_items'];
            $repeater_dashed_items = $col['repeater_dashed_items'];

            $colClass = ($i % 2) ? ' odd':' even';
            $image_type = $col['image_type'];
            $has_image = false;
            $images = ($image_type) ? $col[$image_type.'_image'] : '';
            $slides = array();
            $single_image = $col['single_image'];
            if($image_type=='single') {
              if($single_image) {
                $slides[0]['url'] = $images['url'];
                $slides[0]['title'] = $images['title'];
                $slides[0]['sizes'] = $images['sizes'];
              }
            } else {
              $slides = $col['gallery_image'];
            }

            $thumbnail = array();
            if($slides) {
              $thumbnail = $slides[0];
            }
            $buttons = $col['buttons'];
            $boxClass = ( ($e_title || $e_text) && $slides ) ? 'half':'full';

            $textType = ($shorttext) ? 'shorttext':'fulltext';
            if($shorttext) {
              $e_text = $shorttext;
            }

            $popupContent = ( isset($col['content_display_type']) && $col['content_display_type'] ) ? true : false;
            if( ($e_title || $e_text) || $slides) { ?>
            <div id="entryBlock<?php echo $i?>_parent_<?php echo $ctr?>" class="fbox <?php echo ($thumbnail) ? 'hasImage':'noImage'; ?>">
              <div class="inside text-center">

                <div class="imagediv <?php echo ($thumbnail) ? 'hasImage':'noImage'?>">
                  <?php if ($thumbnail) { ?>
                    <span class="img" style="background-image:url('<?php echo $thumbnail['url']?>')"></span>
                  <?php } ?>
                  <img src="<?php echo $placeholder ?>" alt="" aria-hidden="true" class="placeholder">
                </div>

                <?php if ($e_title || $e_text) { ?>
                <div class="details">
                  <?php if ($e_title) { ?>
                  <h3 class="h3"><?php echo $e_title ?></h3>
                  <?php } ?>

                  <?php if ($e_text) { ?>
                  <div class="text <?php echo $textType ?>"><?php echo $e_text ?></div>
                  <?php } ?>

                  <!-- DASHED ITEMS -->
                  <?php include( locate_template('parts-flexible/schedule_dashed_items.php') ); ?>
                  <!-- end of DASHED ITEMS -->



                  <?php if ($popupContent) { ?>
                      <?php  
                      $modal_id = "textcol3_".$i."_parent_".$ctr."_modal";
                      $modal_title = $e_title;
                      $modal_text = $fullText;
                      ?>
                      <div class="cta-buttons buttondiv">
                        <a data-toggle="modal" data-target="#<?php echo $modal_id ?>" class="btn-sm xs"><span>See Details</span></a>
                      </div>
                      <?php include( locate_template('parts/flexible-content-popup.php') ); ?>

                  <?php } else { ?>
                  
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

                  <?php } ?>

                </div>
                <?php } ?>

              </div>
            </div>
            <?php $i++; } ?>
          <?php } ?>
          </div>
        </section>
        <?php } ?>



    <?php } ?>
  </div>  
<?php  } ?>