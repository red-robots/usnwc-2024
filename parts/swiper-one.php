<section class="lights-info">
		<div class="container">

			<section>
			    <div class="image" data-type="background" data-speed="<?php echo $dataSpeed; ?>" style="background-image: url(<?php echo $hImg['url']; ?>);"></div>
			    <div class="stuff" data-type="content">
			    	<h1><?php echo $hTitle; ?></h1>
			    	<?php if( $hDesc ){ echo '<p>'.$hDesc.'</p>'; } ?>
			    </div>
			  </section>

		<?php if( have_rows('section') ): while( have_rows('section') ): the_row(); 
				$bg = get_sub_field('background_image');
				$title = get_sub_field('title');
				$desc = get_sub_field('description');
				$gallery = get_sub_field('gallery');
				$dataSpeed = get_sub_field('data_speed');

				// echo '<pre>';
				// echo print_r($gallery);
				// echo '</pre>';
			?>
			<section>
			    <div class="image" data-type="background" data-speed="<?php echo $dataSpeed; ?>" style="background-image: url(<?php echo $bg['url']; ?>);"></div>
			    <div class="stuff" data-type="content">
			    	<?php if( $desc ){ echo $desc; } ?>
			    		<?php if( $gallery ){ ?>
			    			<div class="image-gallery">
							  <div class="swiper-container">
							    <div class="swiper-wrapper">
							    	

							    	<?php foreach( $gallery as $g ) { ?>
									      <div class="swiper-slide">
									        <div class="image-gallery__picture">
									        	<img class="image-gallery__img" src="<?php echo $g['sizes']['medium']; ?>"/>
									        	<?php if( $g['description'] ){ ?>
									        		<div class="img-desc">
									        			<?php echo $g['description']; ?>
									        		</div>
									        	<?php } ?>
									        </div>
									      </div>
									  <?php } ?>

							     </div>

							    <div class="image-gallery__arrows">
								    <div class="swiper-button-prev">
								       <div class="image-gallery__button"><<<</div>
								    </div>
								    <div class="swiper-button-next">
								       <div class="image-gallery__button">>>></div>
								    </div>
								</div>

							  </div>
							</div>
			    		<?php } // if gallery ?>
			    		<?php $gallery == ''; ?>
			    	</div>
			  </section>

		<?php endwhile; endif; ?>