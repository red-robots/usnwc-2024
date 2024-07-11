<?php 
/* SCHEDULE */
$sched_section_icon = get_field("sched_section_icon"); 
$sched_section_title = get_field("sched_section_title"); 
$optional_text = get_field("optional_text"); 
$sched_section_icon = '';
$start = get_field("start_date");
$end = get_field("end_date");
$event_date = get_event_date_range($start,$end,true);
if($sched_section_title || $has_race_types) { ?>
<section id="section-schedule" data-section="Schedule" class="section-content">
	<?php if ($sched_section_title) { ?>
		<div class="title-w-icon">
			<div class="wrapper">
				<div class="shead-icon text-center">
					<div class="icon"><span class="ci-menu"></span></div>
					<h2 class="stitle"  style="color:#FFF;"><?php echo $sched_section_title ?></h2>
					<?php if ($event_date) { ?>
					<div class="event-date">
						<?php //echo $event_date ?>
							<?php //echo $start.' - '.$end; ?>
						</div>	
					<?php } ?>
				</div>
				<?php if( $optional_text ) { ?>
					<div class="optional-text">
						<?php echo $optional_text; ?>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>


	<?php 
	$stat_filter = get_field("filter_on_off");
	$show_filter = ($stat_filter=='off') ? false : true;
	if ( $has_race_types ) {
			$total_options = ($race_types) ? count($race_types) : 0; ?>
			<div class="race-types <?php echo $type_class; ?>">
				<div class="inner-wrapper">

					<?php // used to have filter here ?>

					<div class="race-typesz <?php //echo $type_class; ?>">
						<div class="inner-wrap">
							<div class="flexwrap">
								<?php $i=1; 
								$totalTypes = count($race_types);
								foreach ($race_types as $r) { 
									// echo '<pre style="background: #fff;">';
									// print_r($r);
									// echo '</pre>';
									$actualName = $r['name']; 
									$alias = $r['alias'];
									$name = ($alias) ? $alias : $actualName;
									$slug = sanitize_title($name);
									$sched = $r['schedule'];
									$startdate = ( isset($sched['date']) && $sched['date'] ) ? $sched['date'] : '';
									$enddate = ( isset($sched['enddate']) && $sched['enddate'] ) ? $sched['enddate'] : '';
									$singleDay = ($startdate) ? date('l, F j, Y',strtotime($startdate)) : '';
									if($totalTypes==1) {
										$singleDay = ($startdate) ? date('l',strtotime($startdate)) : '';
									}
									$activities = ( isset($sched['schedule']) && $sched['schedule'] ) ? $sched['schedule'] : '';
									$custom_date = $sched['custom_date'];
									// $is_active = ($i==1) ? ' active':'';
									$is_active = 'active';
									$dateRange = '';
									if($startdate && $enddate) {
										$dateRange = get_event_date_range($startdate,$enddate,true);
									}
									$day = ($dateRange) ? $dateRange : $singleDay;

									// build a repeating array
									$rDate = array();
									$useDate = '';
									if( $enddate && $enddate != $startdate ) {
										$rDate[] = $enddate;
										$useDate = $enddate;
									}
									$origStartDate = $startdate;
									$newStartDate = date("l, F j, Y", strtotime($origStartDate));
									$origEndDate = $enddate;
									$newEndDate = date("l, F j, Y", strtotime($origEndDate));
									// echo '<pre>';
									// print_r($enddate);
									// echo '</pre>';
									?>
									<div class="type">
										<div class="inside">
											<?php if ($name) { ?>
												<div class="type-name"><h3><?php echo $name ?></h3></div>
											<?php } ?>

											<?php if ($activities) { ?>
											<div class="sched-inside-new">
											<ul class="activities">
												<?php 
												$ii = 0;
												foreach ($activities as $a) { 
													$time = $a['time'];
													$event = $a['action'];
													if($time || $action) { ?>
													<?php if( $ii == 0 ){ ?>
														<li class="rdate">
															<?php 
															if($custom_date) {
																echo $custom_date;
															} else {
																echo $newStartDate; 
															}
															?>
														</li>
													<?php } ?>
													<li class="info">
														<div class="wrap">
															<span class="time"><span><?php echo $time ?></span></span>
															<span class="event"><span><?php echo $event ?></span></span>
														</div>
													</li>	
													<?php } $ii++; ?>
												<?php } ?>
												<!-- 

													Repeat if you have another date.

												-->
												<?php 
												//echo '<h1>'.$newStartDate. ' - '.$newEndDate.'</h1>';
												if( $newStartDate !== $newEndDate && $enddate !=''): ?>
													<?php 
													$iii = 0;
													foreach ($activities as $a) { 
														$time = $a['time'];
														$event = $a['action'];
														if($time || $action) { ?>
														<?php if( $iii == 0 ){ ?>
															<li class="rdate"><?php echo $newEndDate; ?></li>
														<?php } ?>
														<li class="info">
															<div class="wrap">
																<span class="time"><span><?php echo $time ?></span></span>
																<span class="event"><span><?php echo $event ?></span></span>
															</div>
														</li>	
														<?php } $iii++; ?>
													<?php } ?>
												<?php endif; ?>
												<!-- end repeat -->

											</ul>	
											</div>
											<?php } ?>
										</div>
									</div>
								<?php $i++; } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
	<?php } ?>


</section>
<?php } ?>