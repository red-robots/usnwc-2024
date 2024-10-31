<?php //$res = update_post_status_if_expired(); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&family=Overpass:ital,wght@0,100..900;1,100..900&family=Sofia+Sans+Condensed:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?php bloginfo("template_url") ?>/css/jquery.fancybox.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php bloginfo("template_url") ?>/css/select2.min.css">
<meta name="facebook-domain-verification" content="vcbl42j06vfl4vocp07qka3fcdtyir" />
<?php if ( is_singular(array('post')) ) { 
global $post;
$post_id = $post->ID;
$thumbId = get_post_thumbnail_id($post_id); 
$featImg = wp_get_attachment_image_src($thumbId,'full'); ?>
<!-- SOCIAL MEDIA META TAGS -->


<meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
<meta property="og:url"		content="<?php echo get_permalink(); ?>" />
<meta property="og:type"	content="article" />
<meta property="og:title"	content="<?php echo get_the_title(); ?>" />
<meta property="og:description"	content="<?php echo (get_the_excerpt()) ? strip_tags(get_the_excerpt()):''; ?>" />
<?php if ($featImg) { ?>
<meta property="og:image"	content="<?php echo $featImg[0] ?>" />
<?php } ?>
<!-- end of SOCIAL MEDIA META TAGS -->
<?php } ?>

<!-- <script type='text/javascript' src='<?php bloginfo( 'template_url' ); ?>/assets/js/lottieplayer.js' id='sgr-js'></script>
<script type='text/javascript' src='<?php bloginfo( 'template_url' ); ?>/assets/js/lottiein.js' id='sgrm'></script> -->

<!-- -->
<script src="https://kit.fontawesome.com/df142d44cc.js" crossorigin="anonymous"></script>
<script defer src="<?php bloginfo( 'template_url' ); ?>/assets/svg-with-js/js/fontawesome-all.js"></script> 
<script>
var currentURL = '<?php echo get_permalink();?>';
var params={};location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){params[k]=v});
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-9PW6PHW0M8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-9PW6PHW0M8');
</script>
<script>!function(e,t,n,s,a,c,p,i,o,u){e[a]||((i=e[a]=function(){i.process?i.process.apply(i,arguments):i.queue.push(arguments)}).queue=[],i.pixelId="cbd42ac9-c947-41a0-a340-cc2163106c8c",i.t=1*new Date,(o=t.createElement(n)).async=1,o.src="https://found.ee/dmp/pixel.js?t="+864e5*Math.ceil(new Date/864e5),(u=t.getElementsByTagName(n)[0]).parentNode.insertBefore(o,u))}(window,document,"script",0,"foundee");foundee('', 'Y');</script>
<?php wp_head(); ?>
</head>
<?php
$heroImage = get_field("full_image");
$postHeroImage = get_field("post_image_full");
$flexbanner = get_field("flexslider_banner");
$xBodyClass = 'pageNoBanner';
if($heroImage) {
	$xBodyClass = ($heroImage) ? 'pageHasBanner':'pageNoBannerr';
} else {
	if($flexbanner) {
		$xBodyClass = ($flexbanner) ? 'pageHasBanner':'pageNoBanner';
	}
}
if($postHeroImage) {
	$xBodyClass = ($postHeroImage) ? 'pageHasBanner':'pageNoBanner';
}
?>
<body <?php body_class($xBodyClass); ?>>
	<?php if( is_page('employment') ) { get_template_part('inc/employment-tracking'); } ?>
<div id="page" class="site cf">
	<div id="overlay"></div>
	<a class="skip-link sr" href="#content"><?php esc_html_e( 'Skip to content', 'bellaworks' ); ?></a>

	<?php //get_template_part('parts/topbar'); ?>

	<header id="masthead" class="site-header start-position" role="banner">
		
		<div id="topSearchBar" class="top-search-bar">
			<div class="wrapper">
				<div class="form-wrapper">
					<?php echo get_search_form(); ?>
					<a href="#" id="topsearchBtn"><i class="fas fa-search"></i></a>
					<a href="#" id="closeTopSearch"><span>Close</span></a>
				</div>
			</div>
		</div>

		<div class="navbar">
			<div class="flexer">
				<div class="left">
		        <?php
						/* NAVIGATION */
						get_template_part("parts/navigation");
					?>
				</div>
				<div class="logo">
					<a href="<?php bloginfo('url'); ?>">
						<img src="<?php bloginfo('template_url'); ?>/images/logo-white.png">
					</a>
		   </div>
				<div class="right">
					<div class="cal"><a href="<?php bloginfo('url'); ?>/calendar">Calendar</a></div>
					<div class="today" data-id="1">Today</div>
					<?php get_template_part('parts/today'); ?>
				</div>
		  </div>
		</div>

		<nav class="mobile-nav-wrap">
  			<div class="logo">
        	<img src="<?php bloginfo('template_url'); ?>/images/logo-white.png" alt="" />
        </div>
		  	<span id="mobile-menu-toggle"><span class="bar"><span></span></span></span>
		  	<div class="mobile-navigation">
		  		<?php get_template_part('parts/navigation-mobile'); ?>
		  	</div>
		</nav>
		

	</header><!-- #masthead -->

	<?php 
	if( is_front_page() ){
		get_template_part('parts/homepage-hero');
	} else {
		// // for the merge of Instruction, we'll have to exclude some pages
		// $parent_page_id = 298;
		// // Get the current page's ancestors
		// $ancestors = get_post_ancestors( get_the_ID() );
		// if( !in_array( $parent_page_id, $ancestors ) ){ 
		// 	get_template_part("parts/slideshow"); 
		// }

    // get_template_part("parts/slideshow"); 
    // get_template_part('parts/single-hero');
    get_template_part("parts/hero");  
	}
	?>

	<div id="content" class="site-content">
