<div id="postbox<?php echo $i?>" class="postbox animated fadeIn <?php echo ($thumbImage) ? 'has-image':'no-image';?>">
	<div class="inside">

		<div class="photo">
			<a href="<?php echo $pagelink ?>" class="link">
				<?php if ($thumbImage) { ?>
					<span class="imagediv mobile" style="background-image:url('<?php echo $thumbImageMobile['sizes']['medium_large'] ?>')"></span>
					<span class="imagediv desktop" style="background-image:url('<?php echo $thumbImage['sizes']['medium_large'] ?>')"></span>
					<img src="<?php echo $thumbImage['url']; ?>" alt="<?php echo $thumbImage['title'] ?>" class="feat-img" style="display:none;">
					<img src="<?php echo $blank_image ?>" alt="" class="feat-img placeholder">
				<?php } else { ?>
					<span class="imagediv"></span>
					<img src="<?php echo $blank_image ?>" alt="" class="feat-img placeholder">
				<?php } ?>
			</a>
		</div>

		<div class="details">
			<div class="info">
				<h3 class="event-name"><?php echo $term_name ?></h3>
			</div>

			<div class="button">
				<a href="<?php echo $pagelink ?>" class="btn-sm"><span>See Details</span></a>
			</div>
		</div>

	</div>
</div>