<?php 
	$trail_status_option = get_field("trail_status","option");
	$trail_status = ($trail_status_option=='open') ? 'active':'inactive';
	$trail_text = ($trail_status_option=='open') ? 'Trails Open':'Trails Closed';
 ?>
<div class="today-dropdown"  data-id="1">
	<div class="today-wrap">
		<div id="todayOptions">
			<ul>
				<li class="tablink active"><a href="#" data-tab="#daygroup1">WHITEWATER CENTER<span>Charlotte, NC</span></a></li>
				<li class="tablink "><a href="#" data-tab="#daygroup2">BIKE RANCH<span>Abingdon, VA</span></a></li>
			</ul>
		</div>
		<div class="todayContent">
			<div id="daygroup1" class="schedules-list active" >
				<ul class="items">
					<?php 

						$today_options = get_field("today","option"); 
						$snapshot_link = get_field("snapshot_link","option"); 

					?>
					<?php if ($today_options) { ?>
						
						<div id="businessHours" class="businessHours">
							<ul id="today-options" class="today-options">
								<li class="info">
									<div class="tName">
										TRAIL STATUS
									</div>
									<span class="trail-status el <?php echo $trail_status ?>">
										<span class="s"></span>
										<div class="hours-scode"><?php echo $trail_text ?></div>
									</span>
								</li>
								<?php foreach ($today_options as $t) { 
									$text1 = $t['text1'];
									$text2 = $t['text2'];
									$link = $t['link'];
									$icon_class = ($t['icon_class']) ? $t['icon_class']:'no-icon';
									$link_open = '';
									$link_close = '';
									if($link) {
										$target = (isset($link['target']) && $link['target']) ? $link['target']:'_self';
										$link_open = '<a href="'.$link['url'].'" target="'.$target.'" class="tdlink">';
										$link_close = '</a>';
									}
									$hours = ( isset($t['hours_shortcode']) && $t['hours_shortcode'] ) ? $t['hours_shortcode'] : '';
									if($hours) {
										$hours = trim( preg_replace('/\s+/', ' ', $hours) );
									}
								?>
								<li class="info <?php echo ($t['icon_class']) ? 'hasIcon':'noIcon'; ?>">
									
									<div class="text">
											<?php if ($text1) { ?>

												<?php if ($hours) { ?>
													<div class="n t1">
														<div class="tName"><?php echo $text1 ?></div>
														<!-- <div class="icon"><i class="<?php echo $icon_class ?>"></i></div> -->
														<?php if(do_shortcode($hours)) { ?>
														<div class="hours-scode"><?php echo do_shortcode($hours); ?></div>
														<?php } ?>
													</div>
												<?php } else { ?>
													<div class="n t1">
														<div class="tName"><?php echo $text1 ?></div>
													</div>
												<?php } ?>

											<?php } ?>

											<?php if ($text2) { ?>
											<div class="d t2"><?php echo $text2 ?></div>
											<?php } ?>
											
											<?php if($link) { ?>
												<div class="see-details">
													<a href="<?php echo $link['url']; ?>">See Details <i class="fa-thin fa-chevron-right"></i></a>
												</div>
											<?php } ?>
									</div>
								</li>
								<?php } ?>
							</ul>
							<?php if( $snapshot_link ) { ?>
								<li class="info">
									<div class="cta">
										<a href="<?php echo $snapshot_link['url']; ?>"><?php echo $snapshot_link['title']; ?></a>
									</div>
								</li>
							<?php } ?>
						</div>
					<?php } ?>
				</ul>
			</div>
			<div id="daygroup2" class="schedules-list " >
				<ul class="items">
					Bike Ranch
				</ul>
			</div>
		</div>
	</div>
</div>