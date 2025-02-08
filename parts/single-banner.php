<?php
$heroImage = get_field("full_image");
$thumbnail_image = get_field("thumbnail_image");
if($heroImage) { ?>
<div id="banner" class="subpageBanner">
	<div class="slides-wrapper static-banner">
		<ul class="slides">
			<li class="slideItem type-image">
				<div class="image-wrapper yes-mobile" style="background-image: url('<?php echo $heroImage['url']?>');">
					<img class="desktop " src="<?php echo $heroImage['url']?>" alt="<?php echo $heroImage['title']?>">
					<img class="mobile " src="<?php echo $thumbnail_image['url']?>" alt="<?php echo $thumbnail_image['title']?>">
				</div>
			</li>
		</ul>
	</div>
</div>
<?php } ?>