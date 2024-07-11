	</div><!-- #content -->
	
	<?php  
	$address = get_field("address","option");
	$phone = get_field("phone","option");
	$email = get_field("email","option");
	$social_media = get_social_links();
	$links[] = get_field("group1","option");
	$links[] = get_field("group2","option");
	$footLinks = array();
	if($links) {
		foreach($links as $n) {
			if($n['title'] && $n['links']) {
				$footLinks[] = $n;
			}
		}
	}
	$subscribe = get_field("group3","option");
	?>
	<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="wrapper">

				<div class="logo">
	            	<img src="<?php bloginfo('template_url'); ?>/images/logo-white.png">
	            </div>
					

					<?php if ($footLinks) { ?>
						<?php foreach ($footLinks as $e) { 
							$c_title = $e['title'];
							$c_links = $e['links'];
							$link = (isset($e['foot_parent_link']['url']) && $e['foot_parent_link']['url']) ? $e['foot_parent_link']['url'] : '';
							$linkTarget = (isset($e['foot_parent_link']['target']) && $e['foot_parent_link']['target']) ? $e['foot_parent_link']['target'] : '_self';
							$link_open = '';
							$link_close = '';
							if($link) {
								$link_open = '<a class="pagelink" href="'.$link.'" target="'.$linkTarget.'">';
								$link_close = '</a>';
							}
						?>
						<div class="footcol footlinks">
							
							<?php if ($c_links) { ?>
								<ul class="flinks">
									<?php foreach ($c_links as $a) { 
										$link = $a['link'];
						                $pageTitle = ( isset($link['title']) && $link['title'] ) ? $link['title'] : '';
						                $pageLink = ( isset($link['url']) && $link['url'] ) ? $link['url'] : '';
						                $target = ( isset($link['target']) && $link['target'] ) ? $link['target'] : '_self';  ?>
						                <?php if ($pageTitle && $pageLink) { ?>
						                	<li><a href="<?php echo $pageLink ?>" target="<?php echo $target ?>"><?php echo $pageTitle ?></a></li>
						            	<?php } ?>
									<?php } ?>
								</ul>
							<?php } ?>
						</div>
						<?php } ?>
					<?php } ?>

					
				</div>
			
		
		
		
	</footer><!-- #colophon -->
	
</div><!-- #page -->
<div id="loaderDiv"> <div class="loaderInline"> <div class="sk-chase"> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> </div> </div> </div>

<?php wp_footer(); ?>
<script>
jQuery(document).ready(function($){
	$(window).on('load', function() {
    	if( $('input.datepicker_with_icon').length ) {
			$('input.datepicker_with_icon').each(function(){
			  var id = $(this).attr('id');
			  var iconURL = $('input#gforms_calendar_icon_'+id).val();
			  $(this).parent().find('img').attr('src',iconURL);
			});
		  }
	});
});
</script>
<script type="text/javascript" src="https://whitewater.secure-cdn.na3.accessoticketing.com/embed/accesso.js" data-accesso-integration-version="5"></script>
<!-- Center specific code ---------  Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-L51KQDPENF"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-L51KQDPENF');
</script>
</body>
</html>
