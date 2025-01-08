<?php
/**
 * Template Name: Bike Ranch Template
 */

get_header(); 
$post_id = get_the_ID(); 
$blank_image = THEMEURI . "images/square.png";
$square = THEMEURI . "images/square.png";
$rectangle = THEMEURI . "images/rectangle-lg.png";
$placeholder = THEMEURI . 'images/rectangle.png';
$show_subnav = get_field('show_page_subnav');
$show_faqs = get_field('show_faqs_items');
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full festival-page">
	<?php while ( have_posts() ) : the_post(); ?>
    <h1 class="sr-only"><?php echo get_the_title() ?></h1>
  <?php endwhile;  wp_reset_postdata(); ?>

		<?php /* FLEXIBLE CONTENT */ ?>
    <?php if( have_rows('flexible_content_bike_ranch') ) { ?>
    <div class="bike-ranch-sections">
      <?php 
      $section_fields = array(
        'two_column_layout_text_image',
        'gallery',
        'buttons'
      );
      ?>
      <?php $ctr=1; while( have_rows('flexible_content_bike_ranch') ): the_row(); ?>
        <?php foreach ($section_fields as $section) { 
          include( locate_template('parts-flexible/bike-ranch/'.$section.'.php') );
        } ?>
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
