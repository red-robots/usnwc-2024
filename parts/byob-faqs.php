<?php 
$faqs = get_field('faqs');
if($faqs):
 ?>
 <div class="main-faq-items" id="faqItems">
 	<div class="wrapper narrow">
 <div id="faqsContainer">
<div class="faqsItems">
	<div class="shead-icon text-center">
		<h2 class="stitle">FAQ's</h2>
	</div>
	<?php 
	// $faq_pageId = 157;
	// $max = 3;
	// $totalFaqs = count($faqLists);
	// $faq_ids = array();
	$n=1; foreach ($faqs as $f) { 
		// $faq_parent_id = $f['parent_id'];
		$question = $f['question'];
		$answer = $f['answer'];
		if($question && $answer) { 
			$isFirst = ($n==1) ? ' first':'';
			//$faqlimit = ($n>$max) ? ' hide-faq':'';
			//if($n<=$max) { $faq_ids[] = $faq_parent_id; ?>
			<div data-faq-parent="<?php echo $faq_parent_id; ?>" class="faq-item collapsible<?php echo $isFirst ?>">
				<h3 class="option-name"><?php echo $question ?><span class="arrow"></span></h3>
				<div class="option-text"><?php echo $answer ?></div>
			</div>
			<?php //} ?>
		<?php $n++; } ?>
	<?php } ?>
</div>
</div>
</div>
</div>
<?php endif; ?>