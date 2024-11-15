<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bellaworks
 */

get_header(); 
$post_type = get_post_type();
?>
<div id="primary" class="content-area-full content-default new-layout post-type-<?php echo $post_type;?>">
  
    <?php 
    $postid = get_the_ID();
    while ( have_posts() ) : the_post(); 
      $top_notification = get_field("top_notification");
      $main_description = get_field("activity_descr");
      $taxonomy = 'pass_type';
      $categories = get_the_terms($postid,$taxonomy);
      $catSlugs = array();
      if($categories) {
        foreach($categories as $c) {
          $catSlugs[] = $c->slug;
        }
      }

      /* Show description if category slug is 'all-access-pass' */
      //$show_main_description = false;
      // if( $categories &&  in_array('all-access-pass',$catSlugs) ) {
      //  $show_main_description = true;
      // }

      $show_main_description = true;
      $pageTabs = array('intro','options','what to wear','check-in','faq');
      ?>
      
      <?php if ($show_main_description && $main_description) { ?>
      <section class="main-description">
        <div class="wrapper text-center">
          <h1 class="pagetitle"><span><?php echo get_the_title(); ?></span></h1>
          <?php echo $main_description ?>
        </div>
      </section>
      <?php } ?>

      <div id="pageTabs"></div>


      <?php 
      /* INTRO */
      $galleries = '';
      $activities = get_field("activities");
      $galleryData = get_field("gallery_content");
      if( isset($galleryData['g_images']) && $galleryData['g_images'] ) {
        $galleries = $galleryData['g_images'];
      }
      $left_icon = ( isset($galleryData['g_icon']) && $galleryData['g_icon'] ) ? $galleryData['g_icon']:'';
      $left_text = ( isset($galleryData['g_description']) && $galleryData['g_description'] ) ? $galleryData['g_description']:'';
      $introClass = ($left_text && $galleries) ? 'half':'full';
      $placeholder = THEMEURI . 'images/rectangle.png';
      $gallery_section_title = get_field("gallery_section_title");
      $section1 = ($gallery_section_title) ? ' data-section="'.$gallery_section_title.'"':'';
      ?>

      <?php if ($left_text || $galleries) { ?>
      <section id="section-intro" class="section-content intro-galleries <?php echo $introClass ?>"<?php echo $section1 ?>>
        <div class="flexwrap">
          <?php if ($left_text || $activities) { ?>
          <div class="leftcol textcol">
            <div class="wrap">
              <div class="inner">
                <?php if ($left_text) { ?>
                <div class="intro">
                  <?php echo $left_text ?>  
                </div>
                <?php } ?>
                
                <?php if ($activities) { ?>
                  <div class="options">
                    <div class="legend">
                      <div class="label">OPTIONS</div>
                      <div class="label">QUALIFIERS</div>
                    </div>
                    <?php $i=1; foreach ($activities as $a) {
                      $a_name = $a['name'];
                      $a_note = ( isset($a['spnote']) && $a['spnote'] ) ? $a['spnote'] : '';
                      $a_description = $a['description'];
                      $a_difficulty = $a['difficulty'];
                      $a_qualifiers = $a['qualifiers'];
                      $a_show = $a['show']; 
                      $show_this_option = ($a_show=='yes') ? true : false;
                      if($show_this_option) { ?>
                      <div id="item-<?php echo $i?>" class="item">
                        <?php /* OPTIONS */ ?>
                        <?php if ($a_name) { ?>
                        <div class="fxcol left">
                          <h2 class="type">
                            <?php echo $a_name ?>
                            <?php if ($a_note) { ?><em><?php echo $a_note; ?></em><?php } ?>
                          </h2>
                          <?php if ($a_description) { ?>
                          <div class="desc"><?php echo $a_description ?></div>  
                          <?php } ?>
                        </div>
                        <?php } ?>

                        <?php /* AGE */ ?>
                        <div class="fxcol right">
                          <?php if ($a_qualifiers) { ?>
                          <span class="age"><?php echo $a_qualifiers ?></span>
                          <?php } ?>
                        </div>
                      </div>  
                      <?php $i++; } ?>
                    <?php } ?>
                  </div>
                <?php } ?>

              </div>  
            </div>
          </div>  
          <?php } ?>

          <?php if ($galleries) { 
            $count = count($galleries); 
            $slider_class = ($count>1) ? 'subpage-sliders flexslider':'subpage-slider-static';
            ?>
          <div class="rightcol galleryCol">
            <div id="subpageSlides" class="rightcol <?php echo $slider_class ?>">
              <ul class="slides">
                <?php foreach ($galleries as $g) { ?>
                <li class="sub-slide-item">
                  <div class="slide-image" style="background-image:url('<?php echo $g['url']?>')">
                    <img src="<?php echo $placeholder ?>" alt="">
                  </div>
                </li> 
                <?php } ?>
              </ul>
            </div>  
          </div>
          <?php } ?>
        </div>
      </section>  
      <?php } ?>


      <?php
      $pass_price = get_field('price');
      $single_pass_price = get_field('single_access_price');
      $monthly_access_price = get_field('monthly_access_price');
      $reserve = get_field('reservation_data');
      $res_title = (isset($reserve['title']) && $reserve['title']) ? $reserve['title'] : '';
      $res_text = (isset($reserve['text']) && $reserve['text']) ? $reserve['text'] : '';
      $res_button = (isset($reserve['button']) && $reserve['button']) ? $reserve['button'] : '';
      $rbuttonLink = (isset($res_button['url']) && $res_button['url']) ? $res_button['url'] : '';
      $rbuttonText = (isset($res_button['title']) && $res_button['title']) ? $res_button['title'] : '';
      $rbuttonTarget = (isset($res_button['target']) && $res_button['target']) ? $res_button['target'] : '_blank';
      $has_price_options = array($pass_price, $single_pass_price, $monthly_access_price);
      // echo "<pre>";
      // print_r($reserve);
      // echo "</pre>";

      

      $pass_other_into = get_field('pass_other_into');
      $pass_required = get_field('is_pass_required');
      $is_pass_required = ($pass_required=='no') ? false : true;

      $a_column_class = ( ($has_price_options && array_filter($has_price_options) || $pass_other_into) && $res_text ) ? 'twocol' : 'full';

      if( ($has_price_options && array_filter($has_price_options)) || $res_text || $pass_other_into) { ?>
      <section id="pass-options-section" class="pass-price-options <?php echo $a_column_class ?>" data-section="Options">
        <div class="inner">
          <?php if ( $has_price_options && array_filter($has_price_options) || $pass_other_into ) { ?>
          <div class="col info-left">
            <h3>PASS OPTIONS</h3>

            <?php if ($is_pass_required) { ?>
              <?php if ($pass_price) { ?>
              <div class="pass-item">
                <span class="name"><span>ALL ACCESS PASS</span></span>
                <span class="price"><span><?php echo $pass_price ?></span></span>
              </div>  
              <?php } ?>

              <?php if ($monthly_access_price) { ?>
              <div class="pass-item">
                <span class="name"><span>MONTHLY ACTIVITY PASS</span></span>
                <span class="price"><span><?php echo $monthly_access_price ?></span></span>
              </div>  
              <?php } ?>

              <?php if ($single_pass_price) { ?>
              <div class="pass-item">
                <span class="name"><span>SINGLE ACTIVITY PASS</span></span>
                <span class="price"><span><?php echo $single_pass_price ?></span></span>
              </div>  
              <?php } ?>

            <?php } else { ?>

              <?php if ($pass_other_into) { ?>
              <div class="pass-item nopass">
                <span class="price"><span><?php echo $pass_other_into ?></span></span>
              </div>
              <?php } ?>
            
            <?php } ?>

          </div> 
          <?php } ?>

          <?php if ($res_text) { ?>
          <div class="col info-right">
            <?php /* RESERVATIONS */ ?>
            <?php if ($res_title) { ?>
            <h3 style="text-transform:uppercase;"><?php echo $res_title ?></h3>
            <?php } ?>
            <div class="text">
              <?php echo $res_text ?>
            </div>
            <?php if ($rbuttonLink && $rbuttonText) { ?>
            <a href="<?php echo $rbuttonLink ?>" target="<?php echo $rbuttonTarget ?>" class="button btn-red"><?php echo $rbuttonText ?></a>
            <?php } ?>
          </div> 
          <?php } ?>
        </div>
      </section>
      <?php } ?>

      <?php 
      /* FLEXIBLE CONTENT */
      $flex_section_title = get_field("flexcontent_section_title");
      $flex_blocks = get_field("flexcontent_text_image");
      $margin = ($flex_section_title) ? '':' nomtop';
      if($flex_blocks) { ?>
        <section id="flexible-content" data-section="<?php echo $flex_section_title ?>" class="section-content">
          <?php if ($flex_section_title) { ?>
          <div class="section-title-div">
            <div class="wrapper">
              <div class="shead-icon text-center">
                <!-- <div class="icon"><span class="ci-calendar"></span></div> -->
                <h2 class="stitle"><?php echo $flex_section_title ?></h2>
              </div>
            </div>
          </div>
          <?php } ?>

          <div class="text-and-image-blocks<?php echo $margin ?>">
            <div class="columns-2">
              <?php $x=1; foreach ($flex_blocks as $b) { 
                $f_title = $b['title'];
                $f_text = $b['description'];
                $f_image = $b['gallery_image'];
                $fbutton = $b['button'];
                if($f_title || $f_text || $f_image) { 
                  $colClass = ($x % 2==0) ? 'even':'odd';
                  if( ($f_title || $f_text) && $f_image ) {
                    $colClass .= ' half';
                  } else {
                    $colClass .= ' full';
                  }
                  $buttonTitle = (isset($fbutton['title']) && $fbutton['title']) ? $fbutton['title'] : '';
                  $buttonLink = (isset($fbutton['url']) && $fbutton['url']) ? $fbutton['url'] : '';
                  $buttonTarget = (isset($fbutton['target']) && $fbutton['target']) ? $fbutton['target'] : '_self';

                  $imageCount = ($f_image) ? count($f_image) : 0;
                ?>
                <div id="frow<?php echo $x?>" class="mscol <?php echo $colClass ?>">
                  <?php if ($f_title || $f_text) { ?>
                  <div class="textcol">
                    <div class="inside">
                      <div class="info">
                        <?php if($f_title) { ?>
                          <h3 class="mstitle"><?php echo $f_title ?></h3>
                        <?php } ?>
                        <?php if($f_text || ($buttonTitle && $buttonLink) ) { ?>
                          <div class="textwrap">
                            <div class="mstext"><?php echo $f_text ?></div>
                            <?php if ($buttonTitle && $buttonLink) { ?>
                            <div class="buttondiv">
                              <a href="<?php echo $buttonLink ?>" target="<?php echo $buttonTarget ?>" class="btn-sm xs"><span><?php echo $buttonTitle ?></span></a>
                            </div>
                            <?php } ?>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
                  
                  <?php if ($f_image) { ?>
                  <div class="gallerycol">

                    <?php if ($imageCount==1) { ?>
                    <div class="singlepic">
                      <img src="<?php echo $f_image[0]['url'] ?>" alt="<?php echo $f_image[0]['title'] ?>">
                    </div>
                    <?php } else { ?>
                      <div class="flexslider">
                        <ul class="slides">
                          <?php $helper = THEMEURI . 'images/rectangle-narrow.png'; ?>
                          <?php foreach ($f_image as $s) { ?>
                            <li class="slide-item" style="background-image:url('<?php echo $s['url']?>')">
                              <img src="<?php echo $helper ?>" alt="" aria-hidden="true" class="placeholder">
                              <img src="<?php echo $s['url'] ?>" alt="<?php echo $s['title'] ?>" class="actual-image" />
                            </li>
                          <?php } ?>
                        </ul>
                      </div>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <?php $x++; } ?>
              <?php } ?>
            </div>
          </div>
        </section>
      <?php } ?>

      


      <?php
      /* WHAT TO WEAR */ 
      $wtw_section_title = get_field("wtw_section_title");  
      $wtw_default_image = get_field("wtw_default_image");  
      $wtw_options = get_field("wtw_options");  
      $s3_title = get_field("whereto_section_title");
      //$section3 = ($s3_title) ? $s3_title : 'What to wear';
      $section3 = 'What to wear';
      $wtw_class = ($wtw_default_image && $wtw_options) ? 'half':'full';
      if ($wtw_options) { ?>
      <section id="section-whattowear" data-section="<?php echo $section3 ?>" class="section-content <?php echo $wtw_class ?>">
        <div class="wrapper">
          <div class="flexwrap">
            <?php if ($wtw_default_image) { ?>
            <div class="col model">
              <div id="defaultModel" class="default" data-default="<?php echo $wtw_default_image['url'] ?>" style="background-image:url('<?php echo $wtw_default_image['url'] ?>')">
                <?php if ($wtw_options) { ?>
                  <?php $i=1; foreach ($wtw_options as $m) { 
                    $wImg = $m['w_image']; 
                    if($wImg) { ?>
                    <div id="partImg<?php echo $i?>" class="part partImg animated" data-src="<?php echo $wImg['url'] ?>" style="background-image:url('<?php echo $wImg['url'] ?>')"></div>
                    <?php } ?>
                  <?php $i++; } ?>
                <?php } ?>
              </div>
            </div>  
            <?php } ?>

            <?php if ($wtw_options) { ?>
            <div class="col options">
              <?php if ($wtw_section_title) { ?>
              <div class="titlediv"><h2 class="sectionTitle"><?php echo $wtw_section_title ?></h2></div>  
              <?php } ?>

              <?php if ($wtw_options) { ?>
              <div class="wtw-options">
                <?php $n=1; foreach ($wtw_options as $m) { 
                  $title = $m['w_title'];
                  $description = $m['w_text'];
                  $image_part = $m['w_image']; 
                  $hasMapImage = ($image_part) ? 'has-map-image':'no-map-image';
                  if($title) { ?>
                  <div id="wtw<?php echo $n?>" data-part="#partImg<?php echo $n?>" class="wtw-row collapsiblewtw <?php echo $hasMapImage ?><?php echo ($n==1) ? ' first':''; ?>">
                    <?php if ($title) { ?>
                      <h3 class="option-name"><?php echo $title ?> <span class="arrow"></span></h3>
                    <?php } ?>

                    <?php if ($description) { ?>
                      <div class="option-text"><?php echo $description ?></div>
                    <?php } ?>
                  </div>
                  <?php } ?>
                <?php $n++; } ?>
              </div>  
              <?php } ?>
            </div>  
            <?php } ?>
          </div>
        </div>
      </section>
      <?php } ?>

      
    <?php endwhile; ?>


      
      <?php
      /* CHECK-IN */ 
      $rectangle = THEMEURI . "images/rectangle.png";
      $square = THEMEURI . "images/square.png";
      $checkin_images = array();
      $checkin_rows = array();
      $s4_title = get_field("whereto_section_title");
      //$section4 = ($s4_title) ? $s4_title : 'Check-In';
      $section4 = 'Check-In';
      if( have_rows('checkin_box') ) { 
        $ctr=0; while ( have_rows('checkin_box') ) : the_row(); 
          $has_text = get_sub_field('has_text'); 
          $has_text = ($has_text=='yes') ? true : false;
          $text = get_sub_field('flex_content'); 
          $c_img = get_sub_field('flex_image'); 
          if( ($has_text && $text) || $c_img ) {
            $checkin_rows[] = $ctr;
          }
        $ctr++; endwhile;

        $countImages = ($checkin_rows) ? count($checkin_rows) : 0;
        $checkin_classes = '';
        if($countImages==1) {
          $checkin_classes = ' has-one-image';
        }
        if($countImages==2) {
          $checkin_classes = ' has-two-images';
        }
      ?>
      <section id="section-checkin" data-section="<?php echo $section4 ?>" class="section-content<?php echo $checkin_classes;?>">
        <div class="wrapper-full">
          <?php if ($s4_title) { ?>
          <h2 class="section-title"><?php echo $s4_title ?></h2>
          <?php } ?>
          
          <?php if ( get_field('checkin_box') ) { ?>
          <div class="flexwrap">
            <?php  
              $count_section = count(get_field('checkin_box'));
              $i=1; while ( have_rows('checkin_box') ) : the_row(); 
              $has_text = get_sub_field('has_text'); 
              $has_text = ($has_text=='yes') ? true : false;
              $image = get_sub_field('flex_image'); 
              $text = get_sub_field('flex_content'); 
              $classList = '';
              $flex_class = '';
              $has_text_image = false;
              $verbiage = '';
              if($has_text && $text) {
                $verbiage = ($text) ? $text : '';
                $classList = ($text && $image) ? ' text-and-image':'';
                $classList .= ' has-text ';
                if($text && $image) {
                  $has_text_image = true;
                }
              }
              if($image) {
                $classList .= ' has-image';
              }
              ?>
              <?php /* LEFT COLUMN */ ?>
              <?php if($i==1) { ?>
              <div class="col-left">
              <?php } ?>

              <?php if($i==1) { ?>
              <div class="flex-content largebox <?php echo $flex_class.$classList ?>">
                <div class="inside">
                <?php if ($has_text_image) { ?>
                  <div class="imagediv" style="background-image:url('<?php echo $image['url'] ?>')">
                    <img src="<?php echo $rectangle ?>" alt="">
                  </div>
                  <div class="caption">
                    <div class="text"><?php echo $verbiage ?></div>
                  </div>
                <?php } else { ?>
                  
                  <?php if ($verbiage) { ?>
                    <div class="caption">
                      <div class="text"><?php echo $verbiage ?></div>
                    </div>
                  <?php } ?>

                  <?php if ($image) { ?>
                    <div class="image-only" style="background-image:url('<?php echo $image['url'] ?>')">
                      <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>">
                    </div>
                  <?php } ?>

                <?php } ?>
                </div>
              </div>
              <?php } ?>
              
              <?php if($i==1) { ?>
              </div>
              <?php } ?>

              
              <?php /* RIGHT COLUMN */ ?>
              <?php if($i==2) { ?>
              <div class="col-right">
              <?php } ?>

                <?php if($i>1) { ?>
                <div class="flex-content largebox <?php echo $flex_class.$classList ?>">
                  <div class="inside">
                  <?php if ($has_text_image) { ?>
                    <div class="imagediv" style="background-image:url('<?php echo $image['url'] ?>')">
                      <img src="<?php echo $rectangle ?>" alt="">
                    </div>
                  <?php } else { ?>
                    <?php if ($verbiage) { ?>
                      <div class="caption">
                        <div class="text"><?php echo $verbiage ?></div>
                      </div>
                    <?php } ?>

                    <?php if ($image) { ?>
                      <div class="image-only" style="background-image:url('<?php echo $image['url'] ?>')">
                        <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>" class="actual">
                      </div>
                    <?php } ?>
                  <?php } ?>
                  </div>
                </div>
                <?php } ?>

               <?php if($i==$count_section) { ?>
              </div>
              <?php } ?>

            <?php $i++; endwhile; ?>
          </div>
          <?php } ?>
        </div>
      </section>
      <?php } ?>

      <?php /* MAPS */ ?>
      <?php if( $maps = get_field("maps") ) { 
      $map_title = get_field("map_section_title");
      $map_icon = get_field("custom_icon");
      $mclass = count($maps); ?>
      <section id="section-trail-maps" data-section="<?php echo $map_title ?>" class="section-content section-flex-columns blocks<?php echo $mclass;?>">
        <?php if ($map_title) { ?>
        <div class="wrapper">
          <div class="shead-icon text-center">
            <div class="icon"><span class="<?php echo $map_icon ?>"></span></div>
            <h2 class="stitle"><?php echo $map_title ?></h2>
          </div>
        </div>
        <?php } ?>

        <div class="columns-wrapper">
          <div class="flexwrap">
          <?php foreach ($maps as $img) { 
            $image = $img['image'];
            $width = ($img['width']) ? $img['width'] : '100';
            $width = ($width) ? str_replace('%','',$width) : '';
            if($image) { ?>
            <div class="flexcol" style="width:<?php echo $width ?>%;background-image:url('<?php echo $image['url'] ?>')">
              <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['title'] ?>">
            </div>
            <?php } ?>
          <?php } ?>
          </div>
        </div>
      </section>
      <?php } ?>

      <?php /* FAQ */ ?>
      <?php 
        $customFAQTitle = get_field("faq_section_title");
        //get_template_part("parts/content-faqs"); 
        include( locate_template('parts/content-faqs.php') ); 
      ?>

      <?php /* EXPLORE OTHER ACTIVITIES */ ?>
      <?php get_template_part("parts/similar-posts"); ?>

      <?php
      /* FAQS JAVASCRIPT */ 
      include( locate_template('inc/faqs-script.php') ); 
      ?>
</div>
<?php
get_footer();
