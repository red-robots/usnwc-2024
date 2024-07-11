<div class="topbar">
	<div class="wrapper">
		<?php
		$trail_status_option = get_field("trail_status","option");
		$trail_status = ($trail_status_option=='open') ? 'active':'inactive';
		$toplink = get_field("toplink","option");
		$trail_text = ($trail_status_option=='open') ? 'Trails Open':'Trails Closed';
		?>
		<div class="topinfo">
			<span class="trail-status el <?php echo $trail_status ?>">
				<span class="t"><?php echo $trail_text ?></span>
				<span class="s"></span>
			</span>
			<span id="todayLink" class="today el">
				<?php $today_options = get_field("today","option"); ?>
				<?php if ($today_options) { ?>
					<a href="#" id="todayToggle" class="spanlink"><i id="todayTxt" class="txt">TODAY</i><i id="arrow" class="arrow"></i></a>
					<div id="businessHours" class="businessHours">
						<ul id="today-options" class="today-options">
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
								<div class="icon"><i class="<?php echo $icon_class ?>"></i></div>
								<div class="text">
									<?php echo $link_open; ?>
										<?php if ($text1) { ?>

											<?php if ($hours) { ?>
												<div class="n t1">
													<div class="tName"><?php echo $text1 ?></div>
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
										
									<?php echo $link_close; ?>
								</div>
							</li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
			</span>
			<span class="srchLink el">
				<a id="searchHereBtn" class="search"><i class="fas fa-search"></i></a>
			</span>
		</div>
	</div>
</div>