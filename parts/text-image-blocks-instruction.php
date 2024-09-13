<?php
$pt = get_post_type();
/* TEXT AND IMAGE BLOCKS */
$textImageData = get_field("textImageCol"); 
// echo '<pre>';
// print_r($textImageData);
// echo '</pre>';
?>
<?php if ($textImageData) { 

?>
<section class="text-and-image-blocks nomtop">
	<?php if( get_post_type() != 'retreat') { ?>
		<div class="wrapper">
			<div class="shead-icon text-center">
				<h2 class="programs">Programs</h2>
			</div> 
		</div>
	<?php } ?>
	<div class="columns-2">
	<?php $i=1; while ( have_rows('textImageCol') ) : the_row();

		if( get_row_layout() == 'text_and_image' ):

		$e_title = get_sub_field('title');
		$e_text = get_sub_field('text');
		
		$details = get_sub_field('popup_details');
		$passport_product = get_sub_field('passport_product');
		$idArray = array('266','267','268','269','270','271','154','152','153','40','41','42','43','58','57','56','55','54','53','59','179','180','38','39','412');
		if( $passport_product == 'all' ) {
			$pp = 'data-accesso-launch';
		} elseif(in_array($passport_product, $idArray )) {
			$pp = 'data-accesso-package="'.$passport_product.'"';
		} else {
			$pp = 'data-accesso-keyword="'.$passport_product.'"';
		}
		$inquiry = get_sub_field('inquiry');
		// echo '<pre>';
		// print_r($pp);
		// echo '</pre>';
		$slides = get_sub_field('images');
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

							<div class="text-center">
							<?php if ($details) { ?>
								<div class="button inline-btn">
									<a href="#instr-details<?php echo $i; ?>" class="btn-sm xs instr" id="inline">
										<span>See Details</span>
									</a>
								</div>
							<?php } ?>
							<?php if ($passport_product !== 'none') { ?>
								<div class="button inline-btn">
									<a <?php echo $pp; ?> href="#" class="btn-sm xs instr">
										<span>Register</span>
									</a>
								</div>
							<?php } ?>
							<?php if ($inquiry) { ?>
								<div class="button inline-btn">
									<a href="<?php echo $inquiry['url'] ?>" class="btn-sm xs instr" target="<?php echo $inquiry['target'] ?>">
										<span><?php echo $inquiry['title'] ?></span>
									</a>
								</div>
							<?php } ?>
							</div>
								<div style="display: none;">
									<div id="instr-details<?php echo $i; ?>" class="instr-details">
										<?php echo $details; ?>
									</div>
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