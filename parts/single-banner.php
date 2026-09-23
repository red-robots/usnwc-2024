<?php
$heroImage = get_field("full_image");
$thumbnail_image = get_field("thumbnail_image");
if($heroImage) { ?>
<div id="banner" class="subpageBanner">
	<div class="slides-wrapper static-banner">
		<ul class="slides">
			<li class="slideItem type-image">
				<div class="image-wrapper yes-mobile" style="background-image: url('<?php echo $heroImage['url']?>');">
					<?php echo bellaworks_image( $heroImage, 'full', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'class' => 'desktop ', 'alt' => $heroImage['title'] ) ); ?>
					<?php echo bellaworks_image( $thumbnail_image, 'full', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'class' => 'mobile ', 'alt' => $thumbnail_image['title'] ) ); ?>
				</div>
			</li>
		</ul>
	</div>
</div>
<?php } ?>