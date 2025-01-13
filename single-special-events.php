<?php
get_header(); 
$post_id = get_the_ID(); 
$blank_image = THEMEURI . "images/square.png";
$square = THEMEURI . "images/square.png";
$rectangle = THEMEURI . "images/rectangle-lg.png";
$placeholder = THEMEURI . 'images/rectangle.png';
//$show_subnav = get_field('show_page_subnav');
$hide_page_subnav = get_field('hide_page_subnav');
$show_subnav = ($hide_page_subnav) ?  false : true;
$show_faqs = get_field('show_faqs_items');
$intro_bgcolor = (get_field('bgcolor')) ? get_field('bgcolor') : '#FFFFFF';
$intro_textcolor = (get_field('textcolor')) ? get_field('textcolor') : '#555555';
?>
<style>
.intro-text-wrap.alt-color {
  background-color: <?php echo $intro_bgcolor ?>;
  color: <?php echo $intro_textcolor ?>;
}
.intro-text-wrap.alt-color h1,
.intro-text-wrap.alt-color h1 span,
.intro-text-wrap.alt-color p {
  color: <?php echo $intro_textcolor ?>;
}
body.single-festival .column-data-section .text-large {
  color: <?php echo $intro_bgcolor ?>;
}
body.single-festival .bannerBottomSvg2 {
  fill: <?php echo $intro_bgcolor ?>!important;
}
body.single-festival .schedule-listing-dashes a {
  color: <?php echo $intro_bgcolor ?>!important;
}
body.single-festival .schedule-listing-dashes a:hover {
  opacity: 1!important;
  text-decoration: underline!important;
}
body.single-festival .info2 {
  color: <?php echo $intro_bgcolor ?>!important;
}
</style>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full festival-page">
	<?php while ( have_posts() ) : the_post(); ?>
		
		<div class="intro-text-wrap alt-color">
			<div class="wrapper">
				<h1 class="page-title"><span><?php the_title(); ?></span></h1>
				<?php if( get_the_content() ) { ?>
				<div class="intro-text"><?php the_content(); ?></div>
				<?php } ?>
			</div>
		</div>

  <?php endwhile;  ?>


    <?php if ($show_subnav) { ?>
    <?php get_template_part("parts/subpage-tabs"); ?> 
    <?php } ?>


		<?php /* FLEXIBLE CONTENT */ ?>
    <?php if( have_rows('flexible_content_page') ) { ?>
    <div class="flexibleContentWrap">
      <?php $ctr=1; while( have_rows('flexible_content_page') ): the_row(); ?>
        
        <?php include( locate_template('parts-flexible/text_and_image_block.php') ); ?>
        <?php include( locate_template('parts-flexible/two_column_text.php') ); ?>
        <?php include( locate_template('parts-flexible/middle_card.php') ); ?>
        <?php include( locate_template('parts-flexible/cards_row.php') ); ?>
        <?php include( locate_template('parts-flexible/fullwidth_red_bar.php') ); ?>
        <?php include( locate_template('parts-flexible/events.php') ); ?>
        <?php include( locate_template('parts-flexible/columns_vline.php') ); ?>
        <?php include( locate_template('parts-flexible/fullwidth_image.php') ); ?>
        <?php include( locate_template('parts-flexible/carousel_fullwidth.php') ); ?>
        <?php include( locate_template('parts-flexible/upcoming_events.php') ); ?>
        <?php include( locate_template('parts-flexible/schedule_cards.php') ); ?>
        <?php include( locate_template('parts-flexible/simple_intro_text.php') ); ?>
        <?php include( locate_template('parts-flexible/accordion_elements.php') ); ?>
        <?php include( locate_template('parts-flexible/buttons_section.php') ); ?>
        <?php include( locate_template('parts-flexible/schedule_dash_listings.php') ); ?>
        <?php include( locate_template('parts-flexible/fullwdith_text.php') ); ?>

      <?php $ctr++; endwhile;  ?>
    </div>
    <?php  } ?>

		
    <?php /* FAQ */
    if($show_faqs) {
      $faq_title = get_field("faq_title");
      if( $faqs = get_faq_listings($post_id) ) { 
        $customFAQTitle = $faq_title;
        include( locate_template('parts/content-faqs.php') ); 
        include( locate_template('inc/faqs.php') ); 
      } 
    } ?>

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
if ($show_subnav) {
  include( locate_template('inc/pagetabs-script.php') );  
}
get_footer();
