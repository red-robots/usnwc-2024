<!DOCTYPE html>
<html>
<head>
	<title><?php the_title(); ?></title>

<!-- <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.6/swiper-bundle.min.js' id='jquery-swiper'></script> -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"
/>
<style type="text/css">
	html,
      body {
        position: relative;
        height: 100%;
      }

      body {
        margin: 0;
        padding: 0;
      }
      .collapse-cont{
		height: 0;
		margin: 0;
		padding: 0 !important;
		opacity: 0 !important;
      }
      .swiper-slide {
      	-webkit-transform: translate3d(0, 0, 0);
      }
</style>




<?php 
/*
*
* Template Name: Lights Info
*
*
*/
wp_head();

$hImg = get_field('header_image');
$hTitle = get_field('header_title');
$hDesc = get_field('header_description');
$num = count(get_field('sections'));

?>
</head>
<body>
<!-- <section class="lights-info"> -->
	<div class="swiper mySwiper swiper-nocfl">
      <div class="swiper-wrapper">


        <div class="swiper-slide swiper-slide-nocfl " data-swiper-parallax="-300" data-swiper-parallax-duration="600">
	        <div class="slide-guts">
	        	<img src="<?php echo $hImg['url']; ?>">
	        	<div class="cont-wrap first">
		        	<div class="cont first">
			        	<h1><?php echo $hTitle; ?><?php //echo $num ?></h1>
					    <?php if( $hDesc ){ echo '<p>'.$hDesc.'</p>'; } ?>
				    </div>
				</div>
	        </div>   
	        <div class="more delay-2s flash animated slower">
	        	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 429.3l22.6-22.6 192-192L493.3 192 448 146.7l-22.6 22.6L256 338.7 86.6 169.4 64 146.7 18.7 192l22.6 22.6 192 192L256 429.3z"/></svg>
	        </div>
	    </div>
        
	    <?php $i=0; if( have_rows('sections') ):?>
	    	<?php while( have_rows('sections') ): the_row(); $i++;
				// $bg = get_sub_field('background_image');
				// $title = get_sub_field('title');
				// $desc = get_sub_field('description');
				$gallery = get_sub_field('section');
				// echo '<pre style="background-color: #fff;">';
				// print_r($gallery);
				// echo '</pre>';
			?>
		        <div class="swiper-slide swiper-slide-nocfl"data-swiper-parallax="-300" data-swiper-parallax-duration="600" >
		        	<?php if( $gallery ){ 
		        		$gNum = count( $gallery );
		        		?>
		        		<div class="swiper mySwiperTwo swiper-nocfl" data-swiper-parallax-scale="0.15">
		      				<div class="swiper-wrapper">
				        	<?php $j=0; foreach( $gallery as $g ) { $j++; ?>
				        		<div class="swiper-slide swiper-slide-nocfl" data-swiper-parallax-y="-23%">
				        			<div class="slide-guts" >
					        			<img src="<?php echo $g['card']['url']; ?>" >
					        			<?php if($g['title']) { ?>
					        				<div id="status" class="dis-title animated delay-.5s">
					        					<?php echo $g['title'] ?>
					        						<div class="caption"></div>
					        				</div>
					        			<?php } ?>
					        			<div class="cont-wrap ">
						        			<div class="cont toggle animated delay-1s">
										        <div class="text toggle animated delay-1s"><?php echo $g['description']; ?></div>   
										    </div>
									    	<div class="expand delay-2s animated flash slower">X</div>
									    </div>
								    </div>
								    <?php if( $gNum !== $j ){ ?>
							        	<div class="more right delay-2s flash animated slower">
								        	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M342.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L274.7 256 105.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
								        </div>
								    <?php } ?>
							    </div>
				        	<?php } ?>
					        </div>
					    </div>
			        <?php } ?>
			        <?php if( $num !== $i ){ ?>
			        	<div class="more delay-2s flash animated slower">
				        	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 429.3l22.6-22.6 192-192L493.3 192 448 146.7l-22.6 22.6L256 338.7 86.6 169.4 64 146.7 18.7 192l22.6 22.6 192 192L256 429.3z"/></svg>
				        </div>
				    <?php } ?>
		        </div>
		    <?php endwhile ?>
      </div>
  	<?php endif; ?>
      <!-- <div class="swiper-pagination"></div> -->
    </div>

	<?php //get_template_part('parts/swiper-one'); ?>

	
 <?php wp_footer(); ?>

 <!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

 <script type="text/javascript">

// jQuery(document).ready(function ($) {



	var swiper = new Swiper(".mySwiper", {
        direction: "vertical",
        // parallax: true,
        // pagination: {
        //   el: ".swiper-pagination",
        //   clickable: true,
        // },
     //    on: {
		   //  fromEdge: function () {
		   //    console.log('swiper edged');
		   //  },
	    // },
	   //  effect: 'coverflow',
		  // coverflowEffect: {
		  //   rotate: 30,
		  //   slideShadows: false,
		  // },
	    on: {
	    	// slideChangeTransitionStart: function(){
	    	slideChange: function(){
	    		// console.log('Event: ', this.activeIndexChange);
	    		
	    		// active slide
	    		// make title visible, resetting it from previous animation
	    		$('.swiper-slide-active').find('.dis-title').removeClass('fadeOutUp');
	    		// caption set to fade in after its delay
	    		$('.swiper-slide-active').find('.toggle').addClass('fadeInUp');
	    		
	    		setTimeout(
	    			function() {
    				// fade the title out
		    		$('.swiper-slide-active').find('.dis-title').addClass('fadeOutUp');
		    		// remove captions on next and prev slides
		    		$('.swiper-slide-next').find('.toggle').removeClass('fadeInUp');
		    		$('.swiper-slide-prev').find('.toggle').removeClass('fadeInUp');	     		
	    		}, 1000);
	    		
	    		// setTimeout(
	    			// function() {
	    				// $('.swiper-slide-active').find('.cont').css('opacity', '1');
	    				// $('.swiper-slide-active').find('.toggle').css('opacity', '1');
	    		// }, 4000);
	    		
	    	}
	    }
    });

    var swiper2 = new Swiper(".mySwiperTwo", {
        direction: "horizontal",
        // parallax: true,
        effect: 'coverflow',
		  coverflowEffect: {
		    rotate: 30,
		    slideShadows: false,
		  },
        on: {
	    	// slideChangeTransitionStart: function(){
    		slideChange: function(){
	    		// active slide
	    		// make title visible, resetting it from previous animation
	    		$('.swiper-slide-active').find('.dis-title').removeClass('fadeOutUp');
	    		// caption set to fade in after its delay
	    		$('.swiper-slide-active').find('.toggle').addClass('fadeInUp');
	    		
	    		setTimeout(
	    			function() {
    				// fade the title out
		    		$('.swiper-slide-active').find('.dis-title').addClass('fadeOutUp');
		    		// remove captions on next and prev slides
		    		$('.swiper-slide-next').find('.toggle').removeClass('fadeInUp');
		    		$('.swiper-slide-prev').find('.toggle').removeClass('fadeInUp');	    		
	    		}, 1000);


	    		// old stuff below
	    		/*
	    		$(".mySwiperTwo .dis-title").each(function() {
       				$(this).removeClass('fadeOutUp');
     			});
     			$(".mySwiperTwo .toggle").each(function() {
       				$(this).addClass('fadeInUp');
     			});
	    			// $('.mySwiperTwo .swiper-wrapper .swiper-slide-active').find('.dis-title').removeClass('fadeOutUp');
	    			// $('.mySwiperTwo .swiper-wrapper .swiper-slide-active').find('.toggle').addClass('fadeInUp');
	    		
	    		setTimeout(
	    			function() {
	    				$(".mySwiperTwo  .swiper-wrapper .swiper-slide-active .dis-title").each(function() {
		       				$(this).addClass('fadeOutUp');
		     			});
		    		$('.mySwiperTwo .swiper-wrapper .swiper-slide-active').find('.dis-title').addClass('fadeOutUp');
		    		$('.mySwiperTwo .swiper-wrapper .swiper-slide-next').find('.toggle').removeClass('fadeInUp');
		    		$('.mySwiperTwo .swiper-wrapper .swiper-slide-prev').find('.toggle').removeClass('fadeInUp');
	    		
	    		}, 1000);
	    		*/
	    		// setTimeout(
	    		// 	function() {
	    		// 		// $('.swiper-slide-active').find('.cont').css('opacity', '1');
	    		// 		// $('.swiper-slide-active').find('.toggle').css('opacity', '1');
	    		// }, 4000);

	    	
	    	}
	    }
    });






    jQuery(document).ready(function ($) {
    	// expand/collapse the info section
		$( ".expand" ).click(function(e) {
		  

		  // $(this).siblings('.cont').toggleClass("up", 1000, 'easeOutSine'); 
		  // $(this).siblings().find('.toggle').toggleClass("up", 1000, 'easeOutSine'); 


		  $(this).siblings('.cont').toggleClass("collapse-cont", 1000, 'easeOutSine'); 


		  $.fn.extend({
			    toggleText: function(a, b){
			        return this.text(this.text() == b ? a : b);
			    }
			});
		  $(this).toggleText('X', 'i');
	      e.preventDefault();
		});
	});// END


	

//});// END
 </script>
 </body>
</html>