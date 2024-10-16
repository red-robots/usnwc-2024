<?php
/*
* Template Name: Teaser layout
* Template Post Type: whats-new
*/

$placeholder = THEMEURI . 'images/rectangle.png';
$banner = get_field("flexslider_banner");
$has_banner = ($banner) ? 'hasbanner':'nobanner';
$anchor_nav = get_field('anchor_nav');
get_header(); 

if( is_page('waiver') ) {
	$pageClass = 'waiver';
} else {
	$pageClass = '';
}
?>

<div id="primary" class="content-area-full content-default page-default-template <?php echo $has_banner ?>">
	<main id="main" class="site-main wrapper" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<section class="text-centered-section">
				<div class="wrapper text-center">
					<div class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</div>
					<div class="<?php echo $pageClass; ?>">
						<?php the_content(); ?>
					</div>
					
				</div>
			</section>

			<?php if( $anchor_nav !== 'hide' ) { ?>
				<div id="pageTabs"></div>
			<?php } ?>

		<?php endwhile; ?>

	</main><!-- #main -->
	
	<section class="text-centered-section" >
		<div class=" text-center">
			<section class="teasers">
				<?php get_template_part('parts/teasers-child'); ?>
			</section>
		</div>
	</section>

</div><!-- #primary -->
<?php
	/* FAQS JAVASCRIPT */ 
	include( locate_template('inc/faqs-script.php') ); 
?>
<script type="text/javascript">


/* page anchors */

$(document).on("click","#tabOptions a",function(e){
	e.preventDefault();
	$("#tabOptions li").removeClass('active');
	$(this).parent().addClass('active');
	$(".schedules-list").removeClass('active');
	var tabContent = $(this).attr("data-tab");
	$(tabContent).addClass('active');
});



	if( $('[data-section]').length > 0 ) {
		var tabs = '';
		$('[data-section]').each(function(){
			var name = $(this).attr('data-section');
			var id = $(this).attr("id");
			tabs += '<span class="mini-nav"><a href="#'+id+'">'+name+'</a></span>';
		});
		$("#pageTabs").html('<div class="wrapper"><div id="tabcontent">'+tabs+'</div></div>');
	}

	 $("#tabs a").on("click",function(e){
    e.preventDefault();
    var panel = $(this).attr('data-rel');
    $("#tabs li").not(this).removeClass('active');
    $(this).parents("li").addClass('active');
    if( $(panel).length ) {
      $(".info-panel").not(panel).removeClass('active');
      $(".info-panel").not(panel).find('.info-inner').removeClass('fadeIn');
      $(panel).addClass('active');
      //$(panel).find('.info-inner').addClass('fadeIn').slideToggle();
      $(panel).find('.info-inner').toggleClass('fadeIn');
    }
  });

	</script>

<?php
get_footer();
