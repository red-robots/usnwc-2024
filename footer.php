	</div><!-- #content -->
	
	<?php  
  $footLogo = get_field("footLogo","option");
  $footerLinks = get_field("group1","option");
	?>
	<footer id="colophon" class="site-footer" role="contentinfo">
			
			<div class="wrapper">
        <?php if ($footLogo) { ?>
        <div class="logo footLogo">
          <img src="<?php echo $footLogo['url'] ?>" alt="<?php echo $footLogo['title'] ?>" />
        </div>
        <?php } ?>
				      
				
      <?php if ($footerLinks) { 
        $links = (isset($footerLinks['links']) && $footerLinks['links']) ? $footerLinks['links'] : '';
        if($links) { ?>
        <div class="footcol footlinks">
          <ul class="flinks">
            <?php foreach ($links as $a) { 
              $link = $a['link'];
              $pageTitle = ( isset($link['title']) && $link['title'] ) ? $link['title'] : '';
              $pageLink = ( isset($link['url']) && $link['url'] ) ? $link['url'] : '';
              $target = ( isset($link['target']) && $link['target'] ) ? $link['target'] : '_self';  ?>
              <?php if ($pageTitle && $pageLink) { ?>
                <li><a href="<?php echo $pageLink ?>" target="<?php echo $target ?>"><?php echo $pageTitle ?></a></li>
              <?php } ?>
            <?php } ?>
          </ul>
        </div>
        <?php } ?>
      <?php } ?>
			</div>
		
	</footer><!-- #colophon -->
	
</div><!-- #page -->

<div id="customModalContainer">
  <div id="customModalInner">
    <div id="customModalContent"></div>
    <button id="customModalClose" class="customModalClose1" aria-label="Close Modal"></button>
  </div>
</div>

<div id="calendarPopHidden" style="display:none"></div>

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
<script src="https://player.vimeo.com/api/player.js"></script>
<script type="text/javascript" src="https://whitewater.secure-cdn.na3.accessoticketing.com/embed/accesso.js" data-accesso-integration-version="5"></script>
<!-- Center specific code ---------  Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-L51KQDPENF"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-L51KQDPENF');
</script>

<?php if ( is_user_logged_in() && current_user_can( 'administrator' ) ) { ?>
<script>
jQuery(document).ready(function($){
  $('body').addClass('is-administrator');
  if( $('[data-anchor-target]').length ) {
    //Copies Hashtag for Anchor Link purpose.
    //This autocopy hashtag only works on administrator user (logged-in).
    $(document).on('click','[data-anchor-target]', function(){
      var anchor = $(this).attr('data-anchor-target');
      navigator.clipboard.writeText(anchor);
      alert('Hashtag `'+anchor+'` has been copied!');
    });
  }
});
</script>
<?php } ?>
</body>
</html>
