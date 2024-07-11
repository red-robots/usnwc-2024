<?php /* Buttons */ 

$theButts = $s['buttons'];
// echo '<pre>';
// print_r($theButts);
// echo '</pre>';

if( $theButts ):
foreach( $theButts as $g ) {
	$linktype = $g['link_type'];
	// $link_field = ($linktype) ? 'entry_'.$linktype : '';
	// $link_val = ($link_field) ? $g[$link_field] : '';
?>
	<?php if ($linktype == 'pdf') { 
		$button_name = $g['pdf']['button_name'];
		$button_link = $g['pdf']['pdf']['url'];
		// if( isset($link_val['pdf']) && $link_val['pdf'] ) {
		// 	$pdf_info = $link_val['pdflink'];
		// 	$button_link = $g['pdf']['url'];
		// }
		if($button_name && $button_link) { ?>
			<div class="buttondiv">
				<a href="<?php echo $button_link ?>" target="_blank" class="btn-sm xs pdflink"><span><?php echo $button_name ?></span></a>
			</div>
		<?php } ?>
	<?php } else if($linktype == 'link') { 
		$button_target = $g['button_link']['target']; 
		$button_name = $g['button_link']['title'];
		$button_link = $g['button_link']['url'];
		?>
		<div class="buttondiv">
			<a href="<?php echo $button_link; ?>" target="<?php echo $button_target; ?>" class="btn-sm pagelink">
				<span><?php echo $button_name; ?></span>
			</a>
		</div>
	<?php } ?>
<?php } endif; ?>
<!-- 
Array
(
    [0] => Array
        (
            [link_type] => link
            [pdf] => Array
                (
                    [button_name] => 
                    [pdf] => 
                )

            [button_link] => Array
                (
                    [title] => Menu
                    [url] => http://localhost:8888/bellaworks/usnwc/usnwc-2021/things-to-do/eat-drink-shop/food-and-beverage/the-market-menu/
                    [target] => _blank
                )

        )

    [1] => Array
        (
            [link_type] => pdf
            [pdf] => Array
                (
                    [button_name] => pdf menu
                    [pdf] => Array
                        (
                            [ID] => 50159
                            [id] => 50159
                            [title] => 2021 Long Creek 4 hour results
                            [filename] => 2021-Long-Creek-4-hour-results.pdf
                            [filesize] => 0
                            [url] => http://localhost:8888/bellaworks/usnwc/usnwc-2021/wp-content/uploads/2021/02/2021-Long-Creek-4-hour-results.pdf
                            [link] => http://localhost:8888/bellaworks/usnwc/usnwc-2021/race/long-creek-adventure-race/2021-long-creek-4-hour-results/
                            [alt] => 
                            [author] => 14
                            [description] => 
                            [caption] => 
                            [name] => 2021-long-creek-4-hour-results
                            [status] => inherit
                            [uploaded_to] => 3957
                            [date] => 2021-11-15 15:20:20
                            [modified] => 2021-11-15 15:20:20
                            [menu_order] => 0
                            [mime_type] => application/pdf
                            [type] => application
                            [subtype] => pdf
                            [icon] => http://localhost:8888/bellaworks/usnwc/usnwc-2021/wp-includes/images/media/document.png
                        )

                )

            [button_link] => 
        )

) -->