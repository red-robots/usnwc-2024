<?php
/**
 * Template Name: Page with Flexible Content
 */

get_header(); 
$blank_image = THEMEURI . "images/square.png";
$square = THEMEURI . "images/square.png";
$rectangle = THEMEURI . "images/rectangle-lg.png";
$placeholder = THEMEURI . 'images/rectangle.png';
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full festival-page">
	<?php while ( have_posts() ) : the_post(); ?>
		
		<div class="intro-text-wrap">
			<div class="wrapper">
				<h1 class="page-title"><span><?php the_title(); ?></span></h1>
				<?php if( get_the_content() ) { ?>
				<div class="intro-text"><?php the_content(); ?></div>
				<?php } ?>
			</div>
		</div>

  <?php endwhile;  ?>

		<?php //get_template_part("parts/subpage-tabs"); ?>


		<?php /* FLEXIBLE CONTENT */ ?>
    <?php if( have_rows('flexible_content_page') ) { ?>
    <div class="flexibleContentWrap">
      <?php $ctr=1; while( have_rows('flexible_content_page') ): the_row(); ?>
        
        <?php if( get_row_layout() == 'text_and_image_block' ) { ?>
          <?php  
          $section_title = get_sub_field('section_title');
          $section_text = get_sub_field('section_text');
          $content_columns = get_sub_field('content_columns');
          $column_style = get_sub_field('column_style');
          ?>
          <div class="column-style column-style-<?php echo $ctr ?> <?php echo ($column_style) ? ' '.$column_style:''?>">
            <?php if ($section_title || $section_text) { ?>
            <div class="wrapper title-wrapper <?php echo ($section_text) ? ' has-section-text':''; ?>">
              <?php if ($section_title ) { ?>
              <div class="titlediv">
                <div class="shead-icon text-center">
                  <h2 class="stitle"><?php echo $section_title ?></h2>
                </div>
              </div>
              <?php } ?>

              <?php if ($section_text ) { ?>
              <div class="text-wrap"><?php echo anti_email_spam($section_text) ?></div>
              <?php } ?>
            </div>
            <?php } ?>

            <?php if ($content_columns) { ?>

              <?php if ($column_style=='column2') { ?>
              <section id="two-columns-block_<?php echo $ctr ?>" class="two-columns-block text-and-image-blocks">
                <div class="columns-2">
                  <?php $i=1; foreach ($content_columns as $col) { 
                    $e_title_top = $col['title_top'];
                    $e_title = $col['title'];
                    $e_text = $col['description']; 
                    $fullText = $col['description']; 
                    $shorttext = $col['shorttext']; 
                    $colClass = ($i % 2) ? ' odd':' even';
                    $image_type = $col['image_type'];
                    $has_image = false;
                    $images = ($image_type) ? $col[$image_type.'_image'] : '';
                    $slides = array();
                    if($image_type=='single') {
                      $slides[0]['url'] = $images['url'];
                      $slides[0]['title'] = $images['title'];
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
                    
                    if( ($e_title || $e_text) || $slides) { $colClass = ($i % 2) ? ' odd':' even'; ?>
                      <div id="section<?php echo $i?>_parent<?php echo $ctr?>" data-section="<?php echo $e_title?>" class="mscol <?php echo $boxClass.$colClass ?>">
                        <?php if ( $e_title || $e_text ) { ?>
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

                              <?php if ($e_text || $buttons) { ?>
                                <div class="textwrap text-center">
                                  <?php if ($e_text) { ?>
                                  <div class="mstext <?php echo $textType ?>"><?php echo $e_text ?></div>
                                  <?php } ?>

                                  <?php if ($popupContent) { ?>
                                    <?php  
                                    $modal_id = "textcol2".$i."_parent_".$ctr."_modal";
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
                        </div>
                        <?php } ?>

                        <?php if ( $slides ) { 
                          $slideCount = count($slides);
                          $flexClass = ($slideCount==1) ? 'noFlex':'flexslider';
                          $slide_id = ($slideCount==1) ? 'staticImage':'slideShow';
                          ?>
                          <div class="gallerycol">
                            <div id="<?php echo $slide_id ?>" class="flexslider>">
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
                  $colClass = ($i % 2) ? ' odd':' even';
                  $image_type = $col['image_type'];
                  $has_image = false;
                  $images = ($image_type) ? $col[$image_type.'_image'] : '';
                  $slides = array();
                  if($image_type=='single') {
                    $slides[0]['url'] = $images['url'];
                    $slides[0]['title'] = $images['title'];
                    $slides[0]['sizes'] = $images['sizes'];
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

        <?php if( get_row_layout() == 'two_column_text' ) { 
          $column1 = get_sub_field('column1');
          $column2 = get_sub_field('column2');
        ?>
        <section id="section-fullwidth-black-<?php echo $ctr?>" data-section="<?php echo $e_title?>" class="mscol fullwidth section-fullwidth-black">
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

        <?php if( get_row_layout() == 'cards_row' ) { 
        $card_title = get_sub_field('card_title');
        $card_text = get_sub_field('card_text');
        $cards_multiple = get_sub_field('cards_multiple');
        if($cards_multiple) { ?>
        <section id="cards-multiple-<?php echo $ctr ?>" data-section="">
          <div class="full-wrapper">
            <?php if ($card_title || $card_text) { ?>
              <div class="intro-wrapper">
                <?php if ($card_title) { ?>
                <div class="titleWrapper"><h2 class="stitle"><?php echo $card_title ?></h2></div>
                <?php } ?>
                <?php if ($card_text) { ?>
                <div class="textWrapper"><?php echo anti_email_spam($card_text) ?></div>
                <?php } ?>
              </div>
            <?php } ?>
            <?php if ($cards_multiple) { ?>
            <div class="cardsWrapper">
              <?php foreach ($cards_multiple as $c) { 
                $img = $c['image'];
                $btn = $c['cards_title'];
                $btnName = (isset($btn['title']) && $btn['title']) ? $btn['title'] : '';
                $btnUrl = (isset($btn['url']) && $btn['url']) ? $btn['url'] : '';
                $btnTarget = (isset($btn['target']) && $btn['target']) ? $btn['target'] : '_self';
                if($img) { ?>
                <div class="imageWrap">
                  <figure>
                    <?php if ($btnName && $btnUrl) { ?>
                    <a href="<?php echo $btnUrl ?>" target="<?php echo $btnTarget ?>" class="card-link">
                      <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
                      <div class="imageText">
                        <div class="title"><?php echo $btnName ?></div>
                      </div>
                    </a>
                    <?php } else { ?>
                    <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['title'] ?>" />
                    <?php } ?>
                  </figure>
                </div>  
                <?php } ?>
              <?php } ?>
            </div>
            <?php } ?>
          </div>
        </section>
        <?php  } ?>
        <?php  } ?>

      <?php $ctr++; endwhile;  ?>
    </div>
    <?php  } ?>

		
	

</div><!-- #primary -->
<script type="text/javascript">
jQuery(document).ready(function($){
	$('#slideShow').flexslider({
    animation: "slide"
  });

  if( $(".customModal").length>0 ) {
  	$(".customModal.modal").each(function(){
  		$(this).insertAfter('#page');
  	});
  }
});
</script>
<?php 
//include( locate_template('inc/pagetabs-script.php') );  
get_footer();
