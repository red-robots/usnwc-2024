<?php 
$placeholder = THEMEURI . 'images/rectangle.png';
// Check value exists.
 if ( have_rows('content_types') ) :

    // Loop through rows.
    while ( have_rows('content_types') ) : the_row();
    	$cards = get_sub_field('card'); 
    	$schedule_activities = get_sub_field('schedule_activities');


    	if( get_row_layout() == 'information_blocks'): 
                $blocks = get_sub_field('blocks');
                $countClass = count($blocks);
                if( $countClass == 1 ){
                    $c = 'oneCol';
                } elseif( $countClass == 2 ){
                    $c = 'twoCols';
                } elseif( $countClass == 3 ){
                    $c = 'threeCols';
                } elseif( $countClass == 4 ){
                    $c = 'fourCols';
                } 
                // echo '<pre>';
                // print_r($blocks);
                // echo '</pre>';
            ?>
                <div class="section-price-ages full  <?php echo $c; ?>">
                    <div class="flexwrap">
                        <?php foreach ( $blocks as $b ) {
                                $bTitle = $b['blocks_title'];
                                $bInfo = $b['blocks_info'];
                        ?>
                            <div class="info">
                                <div class="wrap">
                                    <?php if($bTitle) { ?>
                                        <div class="label">
                                            <?php echo $bTitle; ?>
                                        </div>
                                    <?php } ?>
                                    <?php if($bInfo) { ?>
                                        <div class="val">
                                            <?php echo $bInfo; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>



        <?php elseif( get_row_layout() == 'text_block'): 
        		$text_content = get_sub_field('text_content');
        		$text_title = get_sub_field('text_title'); ?>
        		<section class="text-centered-section full">
					<div class="wrapper text-center email-submission">
						<?php if($text_title) { ?>
							<div class="page-header">
								<h1 class="page-title"><?php echo $text_title; ?></h1>
							</div>
						<?php } ?>
						<div class="text" style="text-align: left;"><?php echo $text_content; ?></div>
					</div>
				</section>

        <?php elseif( get_row_layout() == 'disclaimer'): 
        		$the_disclaimer = get_sub_field('the_disclaimer'); ?>
        		<section class="flexible-row">
        			<div class="disclaimer">
        				<?php echo $the_disclaimer; ?>
        			</div>
        		</section>


        <?php elseif( get_row_layout() == 'title_and_icon' ):
		        $title = get_sub_field('title');
		        $icon = get_sub_field('icon');
         ?>
         		<div class="wrapper">
					<div class="shead-icon text-center">
						<div class="icon-via-img"><img src="<?php echo $icon['url']; ?>"></div>
						<h2 class="stitle"><?php echo $title; ?></h2>
					</div>
				</div>


        <?php elseif( get_row_layout() == 'gallery'): 
        		$photo_gallery = get_sub_field('photo_gallery');
        		$hide = get_sub_field('hide_on_mobile');
        		if( $hide[0] == 'Hide' ) {
        			$hideClass = 'hide-gal';
        		} else {
        			$hideClass ='lll';
        		}

        		 ?>

        		<section class="gallery-section full <?php echo $hideClass; ?>">
        			<?php 
        			// echo '<pre>';
        			// print_r($hide);
        			 ?>
						

					<div id="carousel-images" class="camp-caro swap">
						<div class="loop owl-carousel owl-theme">
						<?php foreach ($photo_gallery as $g) { ?>
							<div class="item">
								<div class="image" style="background-image:url('<?php echo $g['url']?>')">
									<img src="<?php echo $placeholder ?>" alt="" aria-hidden="true" />
								</div>
							</div>
						<?php } ?>
						</div>
					</div>
				</section>


		<?php elseif( get_row_layout() == 'card_carousel'): 
        		$card = get_sub_field('card'); 
        		// echo '<pre>';
        		// print_r($card);
        		// echo '</pre>';
		?>
        		<section class="gallery-section full">
						

					<div id="carousel-images" class="camp-caro swap">
						<div class="loop owl-carousel owl-theme">
						<?php foreach ($card as $g) { 
								$photo = $g['photo']['url'];
								$info = $g['info'];
						?>
							<div class="item">
								<div class="image" >
									<img src="<?php echo $photo; ?>" alt=""  />
								</div>
								<div class="carousel-item-text js-blocks">
									<?php echo $info; ?>
								</div>
							</div>
						<?php } ?>
						</div>
					</div>
				</section>


        <?php
    	elseif( get_row_layout() == 'alternating_cards' ): ?>
    		
    		<?php foreach( $cards as $c ) {

    			$media_type = $c['media_type'];
                $creative = $c['creative'];
    			$title = $c['title'];
    			$description = $c['description'];
    			$cta = $c['cta'];
                $gallery = $c['gallery'];
                $video = $c['video'];
    			?>
    			<section class="child-card" id="ho"  >
    				<div class="creative image-container">
                         <?php if( $media_type == 'gallery' ) { ?>
                            <div id="subpageSlides" class="subpageSlides rightcol <?php echo $slider_class ?>">
                                <ul class="slides">
                                    <?php foreach ($gallery as $g) { ?>
                                    <li class="sub-slide-item">
                                        <a href="<?php echo $g['url'] ?>" class="zoomPic zoom-image" data-fancybox="gallery">
                                            <div class="slide-image" style="background-image:url('<?php echo $g['url']?>')">
                                                <img src="<?php echo $g['url']?>" alt="">
                                            </div>
                                        </a>
                                    </li>   
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } elseif( $media_type == 'video' ) { ?>
                            <div class="embed-container">
                                <?php echo $video; ?>
                            </div>
                        <?php } else { ?>
                            <div class="full-bleed-img">
                               <img src="<?php echo $creative['url']; ?>">
                            </div>
                        <?php } ?>
                    </div>
    				<div class="info wow fadeIn email-submission" data-wow-duration="2s" data-wow-delay="0.5s">
    					<h2><?php echo $title; ?></h2>
            			<div class="desc"><?php echo $description; ?></div>
            			<div class="btn-wrapper">
		    				<?php if( $cta ){ ?>
		            			<div class="button">
		            				<a href="<?php echo $cta['url']; ?>" target="<?php echo $cta['target']; ?>" class="btn-sm">
		            					<span><?php echo $cta['title']; ?></span>
		            				</a>
		            			</div>
		            		<?php } ?>
		            	</div>
    				</div>

    			</section>
    			
    		<?php } ?>



        <?php elseif( get_row_layout() == 'faqs'): 
                $faq_icon = get_sub_field('faq_section_icon');
                $faq_title = get_sub_field('faq_section_title');
                $faq_items = get_sub_field('faq');
            ?>
            <section id="section-faqs" data-section="FAQ" class="section-content no-image faqs-race">
                <div class="wrapper">
                    <div class="col faqs">
                        
                        <?php if ($faq_title) { ?>
                        <div class="titlediv">
                            <?php if ($faq_icon) { ?>
                                <div class="icon-img text-center"><span style="background-image:url('<?php echo  $faq_icon['url']?>')"></span></div>
                            <?php } ?>
                            <h2 class="sectionTitle text-center"><?php echo $faq_title ?></h2>
                        </div>
                        <?php } ?>

                        <div class="faqsItems">
                            <?php 
                                if($faq_items) { ?>
                                        <?php $n=1; foreach ($faq_items as $f) { 
                                            $question = $f['question'];
                                            $answer = $f['answer'];
                                            $isFirst = ($n==1) ? ' first':'';
                                            if($question && $answer) { ?>
                                            <div class="faq-item collapsible <?php echo $isFirst ?>">
                                                <h3 style="text-align: left;" class="option-name"><?php echo $question ?><span class="arrow"></span></h3>
                                                <div style="text-align: left;" class="option-text"><?php echo $answer ?></div>
                                            </div>
                                            <?php } ?>

                                        <?php $n++; } ?>
                                   
                                <?php } ?>
                            <?php //} ?>
                        </div>  

                    </div>
                </div>

            </section>

            <?php elseif( get_row_layout() == 'schedule'):   
            		$schedule_dates = get_sub_field('schedule_dates');
            	?>
            	<section class="flexible-row">
            		<section id="section-schedule" data-section="SCHEDULE" class="section-content">
						<div class="wrapper">
							<div class="shead-icon text-center">
								<div class="icon"><span class="ci-menu"></span></div>
								<h2 class="stitle">SCHEDULE</h2>
								<?php if ($schedule_dates) { ?>
								<p class="eventDates"><?php echo $schedule_dates ?></p>	
								<?php } ?>
							</div>
            	<?php
            	
            		// echo '<pre>';
            		// print_r($schedule_activities);
            		// echo '</pre>';
					/*=== SCHEDULE ===*/ 
					// if ($is_filtered) { /* FILTERED SCHEDULE */ 
						$posts_selected = explode(",",$is_filtered);
						$filter_activites = array();
						$selected_activities = array();
						foreach ($schedule_activities as $a) { 
							$schedules = $a['schedule'];
							$day = $a['month'];
							$daySlug = ($day) ? sanitize_title($day) : '';
							if($schedules) {
								foreach ($schedules as $s) { 
									$time = $s['time'];
									$altText = ( isset($s['alt_text']) && $s['alt_text'] ) ? $s['alt_text']:'';
									$is_pop_up = ( isset($s['popup_info'][0]) && $s['popup_info'][0] ) ? true : false;
									$act = ( isset($s['activity']) && $s['activity'] ) ? $s['activity']:'';
									if($act) {
										$id = $act->ID;
										$act->schedule = $time;
										$act->popup_info = $is_pop_up;
										$act->alt_text = $altText;
										if( in_array($id,$posts_selected) ) {
											$filter_activites[$day][] = $act;
										}
									}
								}
							}
						}

						?>
						
						<?php //if ($filter_activites) { ?>
						<div id="filterResults" class="full filterResults">
							<div id="tabSchedules" class="schedules-list-wrap">
								<div id="tabOptions">
									<ul>
									<?php $n=1; foreach ($schedule_activities as $day) {
										// if($day) {
											$tabActive = ($n==1) ? ' active':''; ?>
											<li class="tablink<?php echo $tabActive?>"><a href="#" data-tab="#daygroup<?php echo $n?>"><?php echo ucwords($day['months'])?></a></li>
										<?php $n++; //} ?>
									<?php } ?>
									</ul>
								</div>
								<div class="scheduleContent">
								<?php 
								$ctr=1; 
								
								foreach ($schedule_activities as $day) {
								$isActive = ($ctr==1) ? ' active':''; 
								$items = $day['schedule'];
								// echo '<pre>';
								// print_r($day); 
								?>
								<div id="daygroup<?php echo $ctr?>" class="schedules-list<?php echo $isActive?>">
									<h3 class="day" style="display:none;"><?php echo ucwords($day['months']) ?></h3>
									<ul class="items">
										<?php foreach ($items as $m) {
											$time = $m['time'];
											$end_time = $m['end_time'];
											$oDate = $m['date'];
											$date = DateTime::createFromFormat('d/m/Y', $oDate);
											$formatted_date = $date->format('l, F j');
											$zoom = $m['zoom'][0];
											if( $zoom == 'Yes' ){ $zoom = 'via Zoom';}
											?>
											<li class="item">
												<div class="time"><?php echo $formatted_date; ?></div>
												<div class="event">
													
													<span class="actname">
														<?php if( $end_time ) {
															echo $time.' - '.$end_time;
														} else{
															echo $time;
														}

														?>
													</span>	

													<?php if ($zoom) { ?>
													<span class="alttext">(<?php echo $zoom ?>)</span>
													<?php } ?>
												</div>
											</li>
										<?php } ?>
									</ul>
								</div>
								<?php $ctr++; } ?>
								</div>
							</div>
						</div>
					</div>
					</section>
				</section>
						<!-- SCHEDULE -->
<?php

		endif;
    endwhile; 

else:

endif;
?>