<!DOCTYPE html>
<html>
<head>

<link rel='stylesheet' id='bellaworks-style-css'  href='<?php bloginfo('template_url'); ?>/style.css' type='text/css' media='all' />
<script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.min.js?ver=3.5.1' id='jquery-js'></script>
<style type="text/css">
	#primary.activity-schedule-onsite {
		margin-top: 0;
		border: 0;
	}
	#primary.activity-schedule-onsite .wrapper {
		max-width: 100%;
		font-size: 36px;
		font-weight: bold;
	}
	#primary.activity-schedule-onsite .wrapper h3 {
		font-weight: bold;
	}
	#primary.activity-schedule-onsite .intro-text-wrap {
		padding-top: 0;
		padding-bottom: 0;
	}
	#primary.activity-schedule-onsite .status-legend {
		padding-top: 10px;
	}
	#primary.activity-schedule-onsite .date-hours {
		margin-top: 0;
	}
	#primary.activity-schedule-onsite .activities {
		/*display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		justify-content: space-around;*/
	}
	#primary.activity-schedule-onsite {

	}
	#primary.activity-schedule-onsite .activity-info {
		/*flex-basis: 45%;*/
		margin-bottom: 50px;
		max-width: 96%;
	}
	#primary.activity-schedule-onsite span.redclosed {
		width: auto;
		padding: 2px 10px;
		background-color: #BA0D30;
		color: #fff;
	}
	.daily-content, .daily-container {
		width: 100%;
		float: left;
		background-color: #fff;
	}
	.daily-container {
		/*padding: 5px;*/
		height: 4000px;
	}
	.schedule-activities-info.new-layout .activities .activity-info ul.list .cell.cell-open:before, .schedule-activities-info.new-layout .activities .activity-info ul.list .cell.cell-closed:before {
		width: 20px;
		height: 20px;
		left: -10px;
		top: 15px;
	}
	.schedule-activities-info.new-layout .activities .activity-info h3.type {
		font-size: 42px;
	}


.drink-wrapper .clear { clear: both; }

.drink-wrapper .fnb { width: 100%; padding: 0; margin-bottom: 60px; }

.drink-wrapper .fnb.hide { display: none; }

.drink-wrapper .introtext { width: 100%; font-size: 14px; margin: 0 0 20px 0; }

.drink-wrapper h2.m-item { border-bottom: dotted 1px #333; text-transform: uppercase;text-align: center; font-size: 50px; padding: 10px 0 20px 0; width: 100%; margin-bottom: 20px; margin-top: 0; }

.drink-wrapper .bottom-section { width: 100%; float: left; margin: 40px auto; }

.drink-wrapper .bottom-section .fnb-ww-logo { width: 237px; margin: 30px auto; text-indent: -90000px; background-image: url(assets/img/logo-full.png); background-size: cover; background-repeat: no-repeat; background-position: center center; }



.drink-wrapper .bottom-section .fnb-ww-logo img { max-width: 100%; }

.drink-wrapper .bottom-section .term-desc { width: 50%; text-align: center; font-size: 12px; margin: 0 auto; }

.drink-wrapper .bottom-section .notes { width: 100%; text-align: center; }

.drink-wrapper .bottom-section .notes .noter { width: auto; display: inline-block; text-align: center; margin: 0 10px; line-height: 1; font-size: 14px; }

.drink-wrapper .bottom-section .notes .noter .flexer { display: -webkit-box; display: -ms-flexbox; display: flex; -webkit-box-orient: horizontal; -webkit-box-direction: normal; -ms-flex-direction: row; flex-direction: row; -webkit-box-align: center; -ms-flex-align: center; align-items: center; }

.drink-wrapper .bottom-section .notes .noter .vegetarian, .drink-wrapper .bottom-section .notes .noter .gluten { width: 20px; height: 20px; display: inline-block; margin: 0 4px 0 0; background-size: contain; }

.drink-wrapper .bottom-section .notes .noter .thenote { width: auto; display: inline-block; }

.drink-wrapper .bottom-section .notes .noter .vegetarian { background-image: url("assets/img/vegetarian.png"); }

.drink-wrapper .bottom-section .notes .noter .gluten { background-image: url("assets/img/gluten.png"); }

.drink-wrapper .fnb-item { width: 100%; }

.drink-wrapper .fnb-item .item-row { width: 30%; line-height: 1; position: relative; margin: 0 0 20px 0; }

.drink-wrapper .fnb-item .ficon { text-indent: -9000px; width: 20px; height: 20px; display: inline-block; margin: 0; background-size: cover; }

.drink-wrapper .fnb-item .vegetarian { background-image: url("assets/img/vegetarian.png"); }

.drink-wrapper .fnb-item .gluten { background-image: url("assets/img/gluten.png"); }

.drink-wrapper .fnb-item .both { width: 40px; position: absolute; left: -45px; font-size: 0; }

.drink-wrapper .fnb-item .single { width: 20px; position: absolute; left: -25px; }

.drink-wrapper .fnb-item h3 { font-size: 28px; display: inline-block; width: auto; text-transform: capitalize; }

.drink-wrapper .fnb-item .price { font-size: 28px; font-weight: bold; display: inline-block; width: auto;opacity: .5; }

.drink-wrapper .fnb-item .fdesc { font-size: 20px; line-height: 1.3;}
.drink-wrapper {
	padding: 50px;
}
.drink-col {
	padding-left: 120px;
}
	.fnb-item {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		justify-content: space-between;
		align-items: stretch;
	}
</style>
</head>
<body>


<?php
/**
 * Template Name: RE Drinks On Site
 */

//get_header(); 
$blank_image = THEMEURI . "images/square.png";
$square = THEMEURI . "images/square.png";
// $flexslider = get_field( "flexslider_banner" );
// $slidesCount = ($flexslider) ? count($flexslider) : 0;
// $slideImages = array();
// if($flexslider) {
// 	foreach($flexslider as $r) {
// 		if( isset($r['image']['url']) ) {
// 			$slideImages[] = $r['image']['url'];
// 		}
// 	}
// }
// $has_banner = ($slideImages) ? 'has-banner':'no-banner';
?>

<div id="primary" data-post="<?php echo get_the_ID()?>" class="content-area-full activity-schedule-onsite <?php echo $has_banner ?>">
	<div class="daily-container">
		<div class="daily-content">
		<?php
		$i = 0;
		$pbTitle = '';
		$location = $a['location'];
		// get the terms
		$ourTerm = get_term_by( 'slug', $location, 'fnb_location' );
		$termID = $ourTerm->term_id;
		// echo '<pre>';
		// print_r($ourTerm);
		// echo '</pre>';
		
		$logo = get_field( 'logo', 'fnb_location_'.$termID );
		$termDesc = term_description( $termID );

			$wp_query = new WP_Query();
			$wp_query->query(array(
				'post_type'=>'food_menus',
				'posts_per_page' => -1,
				'post__in' => array(47937),
				'tax_query' => array(
					array(
						'taxonomy' => 'fnb_location',
						'field' => 'slug',
						'terms' => array( 'rivers-edge' )
					)
				)
			));
			if ($wp_query->have_posts()) : ?>

				<div class="drink-wrapper">
					<?php if($logo) { ?>
						<div class="fnb-logo">
							<img src="<?php echo $logo['url']; ?>">
						</div>
						<div class="clear"></div>
					<?php } ?>
				    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); $i++;  ?>
				    	
				    		<?php // Do they want this live check
							$published = get_field('publish');
							if( $published != 'No' ):
								$title = get_the_title();
								// $pbTitle = get_field('page_break_title');
								$pageBreak = get_field('page_break');
								if( $pageBreak == 'yes' ) {
									$pClass = 'hide';
								} else {
									$pClass = 'show';
								}
								//echo $title;
								if( $pageBreak == 'yes' ) { ?>
									<div class="pagebreak"><h2><?php the_title(); ?></h2></div>
								<?php }

								?>
								<div class="fnb <?php echo $pClass; ?>">
									<?php 
								// echo 'works';
								$introText = get_field('intro_text');
								?>
								<h2 class="m-item"><?php the_title(); ?></h2>
								<?php
								if( $introText ) { ?>
									<div class="introtext"><?php echo $introText; ?></div>
								<?php } 
								//if( function_exists( get_field )) :
								 
									if( have_rows('menu_item') ) : ?>
										<div class="drink-col">
										<div class="fnb-item">
											<?php while( have_rows('menu_item') ) : the_row();
												$name = get_sub_field('name');
												$price = get_sub_field('price');
												$description = get_sub_field('description');
												$note = get_sub_field('note');
												$hide = get_sub_field('hide');

										if( $hide != 'Yes' ) {
										?>
											<div class="item-row">
												<?php if( $note == 'vegetarian' ) { ?>
													<div class="single"><div class="vegetarian ficon">Vegetarian</div></div>
												<?php } ?>
												<?php if( $note == 'gluten' ) { ?>
													<div class="single"><div class="gluten ficon">Gluten-Free</div></div>
												<?php } ?>
												<?php if( $note == 'both' ) { ?>
													<div class="both">
														<div class="gluten ficon">Gluten-Free</div>
														<div class="vegetarian ficon">Vegetarian</div>
													</div>
												<?php } ?>
												<h3><?php echo $name; ?></h3>&nbsp;&nbsp;
												<?php if( $price ) { ?><div class="price"><?php echo $price; ?></div><?php } ?>
												<?php if( $description ) { ?><div class="fdesc"><?php echo $description; ?></div><?php } ?>
											</div>
										<?php } ?>
											<?php endwhile; ?>
										</div>
									<?php 
									endif; // end repeater loop ?>
									<?php if( $pageBreak == 'yes' ) { ?>
									<div class="extradiv"></div>
									<?php } ?>
									</div>
								<?php endif; ?>
				    	</div>
				    <?php endwhile; ?>	
				    <div class="bottom-section">
						<div class="fnb-ww-logo">
							Whitewater Center
						</div>
						<div class="notes">
							<div class="noter">
								<div class="flexer">
									<div class="vegetarian"></div><div class="thenote">Vegetarian</div>
								</div>
							</div>
							<div class="noter">
								<div class="flexer">
									<div class="gluten"></div><div class="thenote">Gluten Free</div>
								</div>
							</div>
						</div>
						<div class="term-desc"><?php echo $termDesc; ?></div>
					</div>
			    </div>
		<?php endif; ?>
		</div>
	</div>
</div><!-- #primary -->


<script type="text/javascript">
	jQuery(document).ready(function ($) {

// function scroll_to_bottom_looped(duration,page_height){
// 	$('html, body').animate({ 
// 	   scrollTop: page_height},
// 	   duration,
// 	   "swing"
// 	).promise().then(function(){
// 	  scroll_to_top_looped(duration,page_height);
// 	});
// }
// function scroll_to_top_looped(duration,page_height){
// 	$('html, body').animate({ 
// 	   scrollTop: 0},
// 	   duration,
// 	   "swing"
// 	).promise().then(function(){
// 	  scroll_to_bottom_looped(duration,page_height);
// 	});
// }
// function repeat_scroller(duration,page_height,repeats,i){
// 	if( i < repeats ){
// 		$('html, body').animate({ 
// 		   scrollTop: page_height},
// 		   duration,
// 		   "swing"
// 		).promise().then(function(){
// 			$('html, body').animate({ 
// 			   scrollTop: 0},
// 			   duration,
// 			   "swing"
// 			).promise().then(function(){
// 			  i++;			 
// 			  repeat_scroller(duration,page_height,repeats,i);
// 			});
// 		});
// 	}else{
// 		return false;
// 	}
// }

// jQuery(document).ready(function ($) {	
// 	// force window to top of page
// 	$(this).scrollTop(0);
// 	// define vars
// 	let page_height = $(document).height()-$(window).height();
// 	let duration = 60000;

// 	// begin the neverending scrollage festival
// 	scroll_to_bottom_looped(duration,page_height);

// 	// or, use a set number of repeats
// 	let repeats = 3;
// 	let i = 0;
// 	// repeat_scroller(duration,page_height,repeats,i);
// });

  });// END

</script>
</body>
</html>
