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
$heroImage = get_field("full_image");
$flexbanner = get_field("flexslider_banner");
$has_hero = 'no-banner';
if($heroImage) {
	$has_hero = ($heroImage) ? 'has-banner':'no-banner';
} else {
	if($flexbanner) {
		$has_hero = ($flexbanner) ? 'has-banner':'no-banner';
	}
}

get_template_part("parts/subpage-banner");

$rectangle_placeholder = get_bloginfo("template_url") . '/images/video-helper.png';
$post_id = get_the_ID(); ?>
	


	<div id="primary" class="byob content-area-full content-default <?php echo $has_hero;?> post-type-<?php echo $post_type;?>">

		<?php 
			$page_title = get_the_title();
			while ( have_posts() ) : the_post(); ?>
				
				<section class="main-description">
					<div class="wrapper text-center">
						<h1 class="pagetitle"><span><?php echo get_the_title(); ?></span></h1>
						<?php if ( get_the_content() ) { ?>
						<?php the_content(); ?>
						<?php } ?>
					</div>
				</section>
				

				<div id="pageTabs"></div>

				<?php //include(locate_template('parts/details.php')); ?>

				

				<?php 
				$eventInfoBoxes = get_field('use_event_info_boxes');

				if( $eventInfoBoxes == 'use' ) {
				/* REGISTRATION */
					include(locate_template('parts/post-type-race-top-boxes.php')); 

				} else {
					$register_section_icon = get_field("register_section_icon"); 
					$register_section_title = get_field("register_section_title"); 
					$race_types = get_field("race_types"); 
					$registration_note = get_field("registration_note");
				 


				
					$has_race_types = '';
					if ( isset($race_types[0]['schedule']) && $race_types[0]['schedule'] ) {
						//$rtypes =implode("",$race_types[0]['schedule']);
						//$rtypes = '';
						$has_race_types = ($race_types[0]['schedule']) ? true : false;
					}

					if( $has_race_types ){
						$count = count($race_types); 
						$type_class = 'one-col';
						if($count==2) {
							$type_class = 'two-col';
						} 
						elseif($count==3) {
							$type_class = 'three-col';
						} 
						elseif($count>3) {
							$type_class = 'multi-col';
						}
					}

					include(locate_template('parts/byob-schedule.php')); // schedule
					include(locate_template('parts/text-image-blocks.php')); //photo gallery
					include(locate_template('parts/byob-reg-race-types.php')); // float test
				
				}

				?>

				<?php include(locate_template('parts/additional-info-race.php')); // additional info ?>
				<?php include(locate_template('parts/byob-faqs.php')); // additional info ?>

				<?php //include(locate_template('parts/text-image-blocks.php')); //photo gallery ?>


				<?php // include(locate_template('parts/byob-schedule.php')); ?>

				<?php //include(locate_template('parts/details.php')); ?>

				<?php include(locate_template('parts/coursemap.php')); ?>
				<?php include(locate_template('parts/coursemap-two.php')); ?>

				<?php
				/* AWARDS */
				$awards = get_field("awards");
				$a_col_icon1 = ( isset($awards['COL1_icon']) && $awards['COL1_icon'] ) ? $awards['COL1_icon']:'';
				$a_col_title1 = ( isset($awards['COL1_title']) && $awards['COL1_title'] ) ? $awards['COL1_title']:'';
				$a_col_desc1 = ( isset($awards['COL1_description']) && $awards['COL1_description'] ) ? $awards['COL1_description']:'';
				$a_col_text1 = ( isset($awards['COL1_text1']) && $awards['COL1_text1'] ) ? $awards['COL1_text1']:'';
				$a_col_text2 = ( isset($awards['COL1_text2']) && $awards['COL1_text2'] ) ? $awards['COL1_text2']:'';
				
				$a_col_icon2 = ( isset($awards['COL2_icon']) && $awards['COL2_icon'] ) ? $awards['COL2_icon']:'';
				$a_col_title2 = ( isset($awards['COL2_title']) && $awards['COL2_title'] ) ? $awards['COL2_title']:'';
				$a_col_content2 = ( isset($awards['COL2_columns']) && $awards['COL2_columns'] ) ? $awards['COL2_columns']:'';
				
				$awards_content = array($a_col_title1,$a_col_desc1,$a_col_text1,$a_col_text2);
				if( ($awards_content && array_filter($awards_content )) || ($a_col_title2 && $a_col_content2) ) { ?>
				<section id="section-awards" data-section="Results & Awards" class="section-content">
					<div class="flexwrap">

						<?php if ($awards_content && array_filter($awards_content )) { ?>
							<div class="awards-columns awards">
								<div class="inside">
									<?php if ( $a_col_icon1 || $a_col_title1 ) { ?>
										<div class="col-title">
											<?php if ($a_col_icon1) { ?>
												<div class="icon-img"><span style="background-image:url('<?php echo  $a_col_icon1['url']?>')"></span></div>
											<?php } ?>
											<?php if ($a_col_title1) { ?>
												<h2 class="stitle"><?php echo $a_col_title1 ?></h2>
											<?php } ?>
										</div>
									<?php } ?>
									
									<?php if ( $a_col_desc1 || $a_col_text1 ||  $a_col_text2 ) { ?>
										<div class="col-content">
											<?php if ($a_col_desc1) { ?>
											<div class="text1<?php echo (empty($a_col_text1)) ? ' nombottom':'' ?>	"><?php echo $a_col_desc1 ?></div>	
											<?php } ?>

											<?php if ($a_col_text1) { ?>
											<div class="text2">
												<?php
												$a_col_text1 = str_replace('|',' <span class="red">|</span> ',$a_col_text1); 
												echo $a_col_text1 
												?>
												
											</div>	
											<?php } ?>

											<?php if ($a_col_text2) { ?>
											<div class="text3 ribbon">
												<div class="layer1">
													<div class="layer2"><?php echo $a_col_text2 ?></div>
												</div>
											</div>	
											<?php } ?>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>

						<?php if ($a_col_title2 && $a_col_content2) { ?>
							<?php $col_count = ($a_col_content2) ? count($a_col_content2) : 0;  ?>
							<div class="awards-columns result col-count-<?php echo $col_count?>">
								<div class="inside">
									<div class="col-title">
										<?php if ($a_col_icon2) { ?>
											<div class="icon-img"><span style="background-image:url('<?php echo  $a_col_icon2['url']?>')"></span></div>
										<?php } ?>

										<?php if ($col_count==1) { ?>

											<?php if ( isset($a_col_content2[0]['title']) && $a_col_content2[0]['title'] ) { ?>
											<h2 class="stitle"><?php echo $a_col_content2[0]['title']; ?></h2>
											<?php } else { ?>
												<?php if ($a_col_title2) { ?>
													<h2 class="stitle"><?php echo $a_col_title2 ?></h2>
												<?php } ?>
											<?php } ?>
											
										<?php } else { ?>
											<?php if ($a_col_title2) { ?>
												<h2 class="stitle"><?php echo $a_col_title2 ?></h2>
											<?php } ?>
										<?php } ?>
									</div>

									<?php if ($a_col_content2) { ?>
									<div class="col-content col-count-<?php echo $col_count?>">
										<div class="wrap">
											<?php foreach ($a_col_content2 as $col) { 
												$c_title = $col['title'];
												$c_text = $col['text'];
												if($c_title || $c_text) { ?>
												<div class="result-data">

													<?php if ($col_count>1) { ?>
														<?php if ($c_title) { ?>
														<h3 class="h3"><?php echo $c_title ?></h3>	
														<?php } ?>
													<?php } ?>

													<?php if ($c_text) { ?>
													<div class="rtext"><?php echo $c_text ?></div>	
													<?php } ?>
												</div>
												<?php } ?>
											<?php } ?>
										</div>
									</div>	
									<?php } ?>
								</div>
							</div>
						<?php } ?>

					</div>
				</section>
				<?php } ?>


				<?php 
				/* FAQ */ 
				//get_template_part("parts/content-faqs-race"); 
				//$faqVisible = get_field("faqs_visibility");
				//$showFAQs = ( isset($faqVisible[0]) && $faqVisible[0]=='hide' ) ? false : true;
				if( is_faqs_visible() ) {
					$useDefaultFAQIcon = true;
					$customFAQTitle = (get_field("faq_section_title")) ? get_field("faq_section_title") : 'FAQ';
					include( locate_template('parts/content-faqs.php') ); 
					include( locate_template('inc/faqs-script.php') );  /* FAQS JAVASCRIPT */
				} ?>
			<?php endwhile; ?>

			<?php  
			/* Similar Events */ 
			get_template_part("parts/similar-posts"); 
			?>


			<?php
			/* EVENT SPONSORS */
			$sponsor_section_title = get_field("sponsor_section_title");  
			$sponsors = get_field("race_sponsors_logo");  
			if($sponsors) { ?>
			<section id="section-sponsors" class="section-content">
				<div class="wrapper">
					<?php if ($sponsor_section_title) { ?>
					<div class="titlediv">
						<h2 class="sectionTitle text-center"><?php echo $sponsor_section_title ?></h2>
					</div>
					<?php } ?>
					
					<div class="sponsors-list">
						<div class="flexwrap">
							<?php foreach ($sponsors as $s) { 
							$link = get_field("image_website",$s['ID']);
							?>
							<span class="sponsor">
								<?php if ($link) { ?>
									<a href="<?php echo $link ?>" target="_blank"><img src="<?php echo $s['url'] ?>" alt="<?php echo $s['title'] ?>"></a>
								<?php } else { ?>
									<img src="<?php echo $s['url'] ?>" alt="<?php echo $s['title'] ?>">
								<?php } ?>
							</span>	
							<?php } ?>
						</div>
					</div>
				</div>
			</section>
			<?php } ?>

			<?php /* ADDITIONAL EVENT INFO MODAL */ ?>
			<?php if ($eventInfo) { ?>
			<div class="modal customModal fade" id="additionEventInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-lg" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			      	<!-- <div class="modaltitleDiv text-center">
			      		<h5 class="modal-title"><?php //echo $page_title ?></h5>
			      	</div> -->
			      	<div class="modalText">
			      		<div class="text"><?php echo $eventInfo ?></div>
			      	</div>
			      </div>
			    </div>
			  </div>
			</div>
			<?php } ?>


			<?php // Popups for Marketing 
			$popup_on = get_field('popup_on');
			$popup_creative = get_field('popup_creative');
			$popup_text = get_field('popup_text');
			$popup_link = get_field('popup_link');
			// echo '<pre>';
			// print_r($popup_creative);
			// echo '</pre>';
			?>
			<div style="display: none;">
				<div class="race-popup ajax" id="race-pop">
					<?php if($popup_link) {?><a href="<?php echo $popup_link['url']; ?>" target="<?php echo $popup_link['target']; ?>"><?php } ?>
						<?php if($popup_creative){ ?>
							<div class="race-pop-img">
								<img src="<?php echo $popup_creative['url']; ?>" width="<?php echo $popup_creative['width']; ?>" height="<?php echo $popup_creative['height']; ?>" >
							</div>
						<?php } ?>
						<?php if($popup_text){ ?>
							<div class="race-pop-txt">
								<?php echo $popup_text; ?>
							</div>
						<?php } ?>
					<?php if($popup_link) {?></a><?php } ?>
				</div>
			</div>
			<?php if( $popup_on == 'yes' ){ ?>
				<?php if (!isset($_COOKIE['racepup'])): ?>

				    <!-- replace this whatever you want to show -->
				    <script>
				    	$(document).ready(function(){
						    $.colorbox({
						    	inline:true, 
						    	href:".ajax",
						    	innerWidth: 300
						    });
						});
				    </script>

				    <?php
				    $Month = 2592000 + time();
				    setcookie('racepup',date("F jS - g:i a"), $Month); // 30 days
				    //echo $_COOKIE['racepup'];
				    ?>

				<?php endif; ?>
			<?php } ?>
			<script>

			jQuery(document).ready(function($){


				if( $(".customModal").length>0 ) {
					$(".customModal").insertAfter("#page");
				}

				$("select.customSelect").select2({
			    placeholder: "ALL",
			    allowClear: false,
			    dropdownParent: $('.custom-select-wrap')
				});

				// $("select.jsselect2").select2({
			 //    placeholder: "ALL",
			 //    allowClear: false,
			 //    dropdownParent: $('.custom-select-wrap')
				// });

				$("#race-type-option").on('change',function(){
					var opt = $(this).val();
					$(".schedule-info").removeClass("active");
					$(".schedule-info#" + opt).addClass('active');
				});

				/* FAQS */
				$(".faqsItems .collapsible").on("click",function(){
					if( $(this).hasClass('active') ) {
						$(this).removeClass("active fadeIn");
					} else {
						$(".faqsItems .collapsible").removeClass("active fadeIn");
						$(this).addClass("active fadeIn");
					}
				});

				/* page anchors */
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

			  $(".info-title").on("click",function(e){
			    var parent = $(this).parents('.info-panel');
			    var parent_id = parent.attr("id");
			    $("#tabs li").removeClass('active');
			    $('.info-panel').not(parent).find('.info-inner').hide();
			    $('.info-panel').not(parent).removeClass('active');
			    parent.find('.info-inner').toggleClass('fadeIn').slideToggle();
			    if( parent.hasClass('active') ) {
			      parent.removeClass('active');
			      $('#tabs a[data-rel="#'+parent_id+'"]').parents('li').removeClass('active');
			    } else {
			      parent.addClass('active');
			      $('#tabs a[data-rel="#'+parent_id+'"]').parents('li').addClass('active');
			    }

			  });

			});	
			</script>
	
	</div><!-- #primary -->





<?php
get_footer();
