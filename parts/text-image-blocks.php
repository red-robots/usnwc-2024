<?php
/* TEXT AND IMAGE BLOCKS */
$textImageData = get_field("textImageCol"); ?>





<?php if ($textImageData) { ?>
<section class="text-and-image-blocks nomtop">
	<div class="columns-2">
	<?php 
		$i=1; while ( have_rows('textImageCol') ) : the_row();
	

		if( get_row_layout() == 'text_and_image' ):
		$e_title = get_sub_field('title'); //$e_title = $s['title'];
		$e_text = get_sub_field('text'); //$e_text = $s['text'];
		$btn = get_sub_field('button'); //$btn = $s['button'];
		$btnName = ( isset($btn['title']) && $btn['title'] ) ? $btn['title'] : '';
		$btnLink = ( isset($btn['url']) && $btn['url'] ) ? $btn['url'] : '';
		$btnTarget = ( isset($btn['target']) && $btn['target'] ) ? $btn['target'] : '_self';
		$slides = get_sub_field('images'); //$slides = $s['images'];
		$boxClass = ( ($e_title || $e_text) && $slides ) ? 'half':'full';
		if( ($e_title || $e_text) || $slides) {  $colClass = ($i % 2) ? ' odd':' even'; ?>
		<div id="section<?php echo $i?>" class="mscol <?php echo $boxClass.$colClass ?>">
				<?php if ( $e_title || $e_text ) { ?>
				<div class="textcol">
					<div class="inside">

						<div class="info">
							<?php if ($e_title) { ?>
								<h3 class="mstitle"><?php echo $e_title ?></h3>
							<?php } ?>

							<?php if ($e_text) { ?>
								<div class="textwrap">
									<div class="mstext"><?php echo $e_text ?></div>
								</div>
							<?php } ?>

							<?php if ($btnName && $btnLink) { ?>
							<div class="buttondiv">
								<a href="<?php echo $btnLink ?>" target="<?php echo $btnTarget ?>" class="btn-sm xs"><span><?php echo $btnName ?></span></a>
							</div>
							<?php } ?>
						</div><!-- .info -->

					</div><!-- .inside -->
				</div><!-- .textcol -->	
				<?php } ?>

				<?php if ( $slides ) { ?>
				<div class="gallerycol">
					<div class="flexslider">
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
		<?php } ?>

		</div>
		<?php $i++;  ?>
		<?php elseif( get_row_layout() == 'section_break' ):  
			$sHeading = get_sub_field('section_heading');
			$sDetails = get_sub_field('section_details');
			$s_icon = get_sub_field('section_icon');
			$ptID = sanitize_title_with_dashes($sHeading);
			?>
			<section class="section-break" data-section="<?php echo $sHeading ?>" id="<?php echo $ptID ?>">
				<?php if($s_icon){ ?>
					<div class="icon">
						<img src="<?php echo $s_icon['url']; ?>">
					</div>
				<?php } ?>
				<h3><?php echo $sHeading; ?></h3>
				<?php echo $sDetails; ?>
			</section>
		<?php endif; ?>
	<?php endwhile; ?>
	</div>
</section>	

<?php } ?>