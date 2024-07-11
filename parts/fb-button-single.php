<?php /* Button */ ?>
<?php if ($linktype=='pdf' && $link_val ) { 
	$button_name = $link_val['buttonName'];
	$button_link = '';
	if( isset($link_val['pdflink']) && $link_val['pdflink'] ) {
		$pdf_info = $link_val['pdflink'];
		$button_link = $pdf_info['url'];
	}
	if($button_name && $button_link) { ?>
		<div class="buttondiv">
			<a href="<?php echo $button_link ?>" target="_blank" class="btn-sm xs pdflink"><span><?php echo $button_name ?></span></a>
		</div>
	<?php } ?>
<?php } else if($linktype=='link' && $link_val) { 
	$button_target = ( isset($link_val['target']) ) ? $link_val['target']:'_self'; ?>
	<div class="buttondiv">
		<a href="<?php echo $link_val['url'] ?>" target="<?php echo $button_target ?>" class="btn-sm pagelink"><span><?php echo $link_val['title'] ?></span></a>
	</div>
<?php } ?>