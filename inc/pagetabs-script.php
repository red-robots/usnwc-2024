<?php
$hide_subnav_items = get_field('hide_specific_subnav');
$hideSubNavItemsJS = [];
if($hide_subnav_items) {
  $parts = explode(',', $hide_subnav_items);
  if($parts && array_filter($parts)) {
    foreach( array_filter($parts) as $item ) {
      if( $str = trim($item) ) {
        $sb_slug = sanitize_title($str);
        $hideSubNavItemsJS[] = $sb_slug;
      }
      
    }
  }
}
?>

<script type="text/javascript">
jQuery(document).ready(function ($) {
  var hideSubnavItems = <?php echo ($hideSubNavItemsJS) ? json_encode($hideSubNavItemsJS):'[]'; ?>;
	/* page anchors */
	if( $('[data-section]').length > 0 ) {
		var tabs = '';
    var tabsArr = [];
		$('[data-section]').each(function(){
			var name = $(this).attr('data-section');
			var id = $(this).attr("id");
      var slug = name.toLowerCase().trim().replace(/\s+/g, "-").replace(/[^\w-]+/g, "");
      var nameArr = name;
      if( hideSubnavItems.length ) {
        if ($.inArray(slug, hideSubnavItems) !== -1) {
          //Do nothing...
          nameArr = '';
        } else {
          tabs += '<span class="mini-nav"><a href="#'+id+'">'+name+'</a></span>';
        }
      } else {
        tabs += '<span class="mini-nav"><a href="#'+id+'">'+name+'</a></span>';
      }
      //tabsArr.push(nameArr);
		});

  

		if( $("#pageTabs").length>0 ) {
			$("#pageTabs").html('<div class="wrapper"><div id="tabcontent">'+tabs+'</div></div>');
			$("#pageTabs").show();
		}
	}
});
</script>