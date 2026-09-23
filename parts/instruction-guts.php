<div id="postbox<?php echo $i?>" class="postbox animated fadeIn <?php echo ($thumbImage) ? 'has-image':'no-image';?>">
	<div class="inside">

		<div class="photo">
			<a href="<?php echo $pagelink ?>" class="link">
				<?php if ($thumbImage) { ?>
					<span class="imagediv mobile" style="background-image:url('<?php echo $thumbImageMobile['sizes']['medium_large'] ?>')"></span>
					<span class="imagediv desktop" style="background-image:url('<?php echo $thumbImage['sizes']['medium_large'] ?>')"></span>
					<?php echo bellaworks_image( $thumbImage, 'large', array( 'alt' => $thumbImage['title'], 'class' => 'feat-img', 'style' => 'display:none;' ) ); ?>
					<?php echo bellaworks_image( $blank_image, 'large', array( 'alt' => '', 'class' => 'feat-img placeholder' ) ); ?>
				<?php } else { ?>
					<span class="imagediv"></span>
					<?php echo bellaworks_image( $blank_image, 'large', array( 'alt' => '', 'class' => 'feat-img placeholder' ) ); ?>
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