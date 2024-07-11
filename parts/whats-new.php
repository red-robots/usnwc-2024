<?php 
	$thumb = get_the_post_thumbnail_url();
	$excerpt = get_the_excerpt();
	$date = get_the_date();
	$rectangle = THEMEURI . "images/rectangle-narrow.png";
 ?>

 <div class="wn-box">
 	
 	<div class="imagediv" style="background-image: url(<?php echo $thumb; ?>);">
 		<div class="date-overlay"><?php echo $date; ?></div>
 		<img src="<?php echo $rectangle; ?>" class="blankImg">
 	</div>
 	<div class="wn-box-contents">
		<a href="<?php the_permalink(); ?>">
 			<div class="flex">
		 		<h2><?php the_title(); ?></h2>
		 		<div class="date"><?php echo $date; ?></div>
		 		<div class="excerpt"><?php echo $excerpt; ?></div>
		 		<div class="cta">Read More</div>
	 		</div>
 		</a>
 	</div>
</div>