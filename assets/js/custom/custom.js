/**
 *	Custom jQuery Scripts
 *
 *	Developed by: Lisa DeBona
 */

jQuery(document).ready(function ($) {

var params = {}; location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (s, k, v) { params[k] = v });

if( typeof params.popup!='undefined' && params.popup=='schedule') {
  if( window.location.hash ) {
    var hashTag = window.location.hash;
    if( hashTag.includes('#tab-') ) {
      var loc = hashTag.replace('#tab-','');
      setTimeout(function(){
        if( $('.todaySnapshotInfo .popupSchedule[data-schedule="'+loc+'"]').length ) {
          $('.todaySnapshotInfo .popupSchedule[data-schedule="'+loc+'"]').trigger('click');
        }
      },800); 
    }
  }
}

$('.site-header .new-nav-v2 li.has-children').hover(
  function() {
    $('.site-header').addClass('mouseover');
  }, function() {
    $('.site-header').removeClass('mouseover');
  }
);

if( $('.text_and_image_block_section.sponsors-section').length ) {
  $('.text_and_image_block_section.sponsors-section').each(function(){
    if( $(this).next().hasClass('fullwidth-image-section') ) {
      $(this).next().css('margin-top','0px');
    }
  });
}

$('.inline').colorbox({
  inline:true,
  width:"50%",
  // href:".instr",
  // innerWidth: 300
});


$('.loop').owlCarousel({
    center: true,
    items:2,
    nav: true,
    loop:true,
    margin:15,
    autoplay:true,
    smartSpeed: 1000,
    autoplayTimeout:10000,
    autoplayHoverPause:true,
    responsive:{
      600:{
        items:1
      },
      400:{
        items:1
      }
    }
});

if( $('#activities--carousel').length ) {
  $('#activities--carousel').owlCarousel({
    center: false,
    items:1,
    nav: true,
    loop:true,
    margin:0,
    autoplay:true,
    smartSpeed: 1000,
    autoplayTimeout:10000,
    autoplayHoverPause:true,
    onChanged:function(){
      // $('#activities--carousel .owl-item').each(function(){
      //   if( $(this).hasClass('active') ) {
      //     $(this).addClass('changed');
      //   } else {
      //     $(this).removeClass('changed');
      //   }
      // });
    }
  });
}


$('.carousel-center-loop').owlCarousel({
    center: true,
    items:2,
    nav: true,
    loop:true,
    margin:15,
    autoplay:true,
    smartSpeed: 1000,
    autoplayTimeout:10000,
    autoplayHoverPause:true,
    responsive:{
      400:{
        items:1
      },
      768: {
        items:2
      }
    }
});



/*
*
*     Subnaviagation Animation
*
*
*/
$('li.menu-item').hover(function() {
    $(this).toggleClass('active');
    $(this).find('.mega-menu .mega-menu-content .menu-col').toggleClass('is-hovered');

});


var subMenus = $(".mega-menu");

$.each($(".menu-item"), function(index, element) {
  let subMenu = $(element).children('.mega-menu'), tl;
  let subMenuItems = $(subMenu).children('li');

  if(subMenu.length != 0) {
    tl = new gsap.timeline({paused:true});

    tl.from(subMenu, .05, {height:0});
    // tl.staggerTo(subMenuItems, 0.6, {
    //   top: "0px",
    //   ease: Expo.easeInOut,
    // }, 0.1, "-=0.8");

    element.subMenuAnimation = tl;

    $(element).hover(menuItemOver, menuItemOut);
  }
});

function menuItemOver(e) {
  this.subMenuAnimation.play();
}

function menuItemOut() {
  this.subMenuAnimation.reverse();
}





/*
*
*     Controls the today dropdown
*
*
*/
// let menuOpen = false;

// const tl = gsap.timeline({
//   paused: true,
//   defaults: { duration: 0.3, ease: "power1.inOut" }
// });

// tl.fromTo(".today", {  }, {  }, 0)
//   .fromTo(
//     ".today-dropdown",
//     { visibility: "hidden", height: "0", padding: "0" },
//     { visibility: "visible", height: "auto", padding: "40" },
//     0
//   )
//   .fromTo(
//     "li.info",
//     { opacity: 0, y: "0.5em", stagger: 0.1 },
//     { opacity: 1, y: "0em", stagger: 0.1 }
//   )
//   .fromTo(
//     "li.tablink",
//     { opacity: 0, y: "0.5em", stagger: 0.1 },
//     { opacity: 1, y: "0em", stagger: 0.1 }
//   );

// document.querySelector(".today").addEventListener("mouseover", () => {
//   if (!menuOpen) {
//     tl.play();
//     menuOpen = true;
//   } else {
//     tl.reverse();
//     menuOpen = false;
//   }
// });



/*
*
*     Controls the today dropdown - MOBILE
*
*
*/
// let menuOpenMobile = false;

// const tlmobile = gsap.timeline({
//   paused: true,
//   defaults: { duration: 0.3, ease: "power1.inOut" }
// });

// tlmobile.fromTo(".today-mobile", {  }, {  }, 0)
//   .fromTo(
//     ".today-mobile-dropdown",
//     { visibility: "hidden", height: "0", padding: "0" },
//     { visibility: "visible", height: "auto", padding: "10" },
//     0
//   )
//   .fromTo(
//     "li.info-mobile",
//     { opacity: 0, y: "0.5em", stagger: 0.1 },
//     { opacity: 1, y: "0em", stagger: 0.1 }
//   )
//   .fromTo(
//     "li.tablink-mobile",
//     { opacity: 0, y: "0.5em", stagger: 0.1 },
//     { opacity: 1, y: "0em", stagger: 0.1 }
//   );

// document.querySelector(".today-mobile").addEventListener("click", () => {
//   if (!menuOpenMobile) {
//     tlmobile.play();
//     menuOpenMobile = true;
//   } else {
//     tlmobile.reverse();
//     menuOpenMobile = false;
//   }
// });

// $('.today').click(function() {
//   var id = $(this).data('id');
//   $('.today-dropdown[data-id="' + id + '"]').toggleClass('show');
// });

// $('.site-header .navbar .today').hover(
//   function(){
//     $(this).next('.today-dropdown').addClass('active');
//   }, function() {
//     //$(this).next('.today-dropdown').removeClass('show');
//   }
// );
// $('.navbar .today-dropdown').hover(
//   function(){
//     $(this).addClass('active');
//   }, function() {
//     $(this).addClass('slideUp');

//   }
// );

// $('.navbar .today-dropdown').on("mouseleave", function(){
//   setTimeout(function(){
//     $('.today-dropdown').removeClass('active slideUp');
//   },100);
// });


// $(document).on("click",".site-header .navbar .today",function(e){
//   e.preventDefault();
//   $(this).next('.today-dropdown').toggleClass('active');
// });

$(document).on("click","#todayOptions a",function(e){
    e.preventDefault();
    $("#todayOptions li").removeClass('active');
    $(this).parent().addClass('active');
    $(".schedules-list").removeClass('active');
    var tabContent = $(this).attr("data-tab");
    $(tabContent).addClass('active');
});

$(document).on("click","#todayOptions-mobile a",function(e){
    e.preventDefault();
    $("#todayOptions-mobile li").removeClass('active');
    $(this).parent().addClass('active');
    $(".schedules-list-mobile").removeClass('active');
    var tabContentMobile = $(this).attr("data-tab");
    //console.log(tabContentMobile);
    $(tabContentMobile).addClass('active');
});
/*
*
*     Fixed Navigation on Scroll up
*
*
*/
// Get the navigation bar element
const navbar = document.getElementById("masthead");

// Initialize the previous scroll position
let prevScrollPosition = window.pageYOffset;

window.addEventListener("scroll", () => {
  // Get the current scroll position
  const currentScrollPosition = window.pageYOffset;

  // Check if the user has scrolled up
  if (currentScrollPosition < prevScrollPosition) {
    // Set the navbar's position to fixed
    navbar.classList.add("fixed");
    navbar.classList.remove("scrolling");
  } else {
    // Optionally, you can unfix the navbar if the user scrolls down
    // Uncomment the following line if you want this behavior
    navbar.classList.remove("fixed");
    navbar.classList.add("scrolling");
    // navbar.classList.remove("start-position");
  }

   // Check if the user is at the top of the page
  if (currentScrollPosition === 0) {
    navbar.classList.add("start-position");
    navbar.classList.remove("fixed");
  } else {
    navbar.classList.remove("start-position");
  }

  // Update the previous scroll position
  prevScrollPosition = currentScrollPosition;
});

/*
*
*     Mobile Navigation
*
*
*/
$(document).on('click','#mobile-menu-toggle',function(){
  $('body').toggleClass('mobile-menu-open');
  $(this).toggleClass('active');
  $('.mobile-navigation').toggleClass('active');
});

$(document).on('click','#overlay',function(){
  $(this).removeClass('active');
  $('body').removeClass('mobile-menu-open');
  $('#mobile-menu-toggle').removeClass('active');
  $('.mobile-navigation').removeClass('active');
});


$(document).on("click", "a.mobile-parent-link", function(e) {
  e.preventDefault();
    $(this).parent().toggleClass('show-submenu');
    $(this).parent().find('.mega-menu-mobile').slideToggle();
    // if ($(this).hasClass('toggled')) {
    //     window.location.href = $(this).attr('href');
    // } else {
    //     e.preventDefault();
    //     $(this).next().slideToggle();
    //     $(this).addClass('toggled');
    // }
});

$(document).on("click", ".mobile-navigation .today-mobile", function(e) {
  e.preventDefault();
  $(this).toggleClass('active');
  $('.today-mobile-dropdown').toggleClass('active');
  $('.today-mobile-dropdown').slideToggle();
});









$("select#diff").change(function() {
	var diffResult = $(this).val();

    $('ul.items').find( 'li.show' ).removeClass('show');
    $('ul.items').find( 'li' ).addClass('hide');
    if( diffResult == '.advanced' ) {
    	$('ul.items').find( diffResult ).removeClass('hide');
    	$('ul.items').find( diffResult ).addClass('show');
    }
    if( diffResult == '.intermediate' ) {
    	$('ul.items').find( diffResult ).removeClass('hide');
    	$('ul.items').find( diffResult ).addClass('show');
    }
    if( diffResult == '.beginner' ) {
    	$('ul.items').find( diffResult ).removeClass('hide');
    	$('ul.items').find( diffResult ).addClass('show');
    }
    if( diffResult == '.all' ) {
    	$('ul.items').find( 'li.hide' ).removeClass('hide');
    	$('ul.items').find( 'li' ).addClass('show');
    }
	if ($(".hide-on-load").hasClass("hide")) {
		$('.hide-on-load').removeClass('hide');
		$('.hide-on-load').addClass('show');
	}

});
$("#selectByProgram").change(function() {
	if ($(".hide-on-load").hasClass("hide")) {
		$('.sch-prompt').addClass('hide');
		$('.hide-on-load').removeClass('hide');
		$('.hide-on-load').addClass('show');
		$(".diff-filter-wrap").removeClass('hide');
		$(".diff-filter-wrap").addClass('show');
		$("#fi").find('.select2-container--default').removeAttr("style");
		$("#fi").find('.select2-container--default').addClass("show");
	}
});




$(".fs-rest .fs-label-wrap").on("click", function (e) {
    e.preventDefault();
    $(this).toggleClass("fs-open"); // $(".corpnav").addClass("open");
    //$(".corpnav").addClass("open");
    // $("li.corplink").addClass('active');

    $(this).next('.fs-dropdown').toggleClass('fs-hidden');
  });


// init Isotope
var $container = $('#rest-isotope').isotope({
  itemSelector: '.item'
});

// var $output = $('#output');

// filter with selects and checkboxes
var $checkboxes = $('#form-ui input');

$checkboxes.change( function() {
  // map input values to an array
  var inclusives = [];
  // inclusive filters from checkboxes
  $checkboxes.each( function( i, elem ) {
    // if checkbox, use value if checked
    if ( elem.checked ) {
      inclusives.push( elem.value );
    }
  });

  // combine inclusive filters
  var filterValue = inclusives.length ? inclusives.join(', ') : '*';

  // $output.text( filterValue );
  $container.isotope({ filter: filterValue })
});





 $("a#inline").fancybox({
    'hideOnContentClick': true
  });

	$('[data-fancybox]').fancybox({
		touch : true,
		hash : false,
    youtube : {
        controls : 0,
        showinfo : 0,
        rel: 0
    },
    vimeo : {
        color : 'ffffff'
    }
	});

	$('.zoom-image').fancybox({
	  buttons : ['fullScreen','close'],
	  hash : false
	});




	var windowHeight = $(window).scrollTop();
	if(windowHeight  > 200) {
		$("body").addClass('scrolled');
	}

	$(window).scroll(function() {
		var wHeight = $(window).scrollTop();
		if(wHeight  > 200) {
			$("body").addClass('scrolled');
		} else{
			$("body").removeClass('scrolled');
			//$('body').removeClass('subnav-clicked');
		}
	});

	if( $("#banner").length > 0 && $("#pageTabs").length>0 ) {

		$(window).scroll(function() {
			var tabsHeight = $("#pageTabs").height();
			if( $(".main-description").length>0 ) {
				var main = $(".main-description").height();
				tabsHeight = main;
			}
			var bannerHeight = $("#banner").height();
			var screenOffset = bannerHeight + tabsHeight;
			var wHeight = $(window).scrollTop();
			if(wHeight  > screenOffset) {
				$("#pageTabs").addClass('fixed-top');
			} else {
				$("#pageTabs").removeClass('fixed-top');
			}
		});
	}


    $('.subpageSlides').flexslider({
      animation: "fade",
      smoothHeight: true
    });


// tiny helper function to add breakpoints
var getGridSize = function() {
  return (window.innerWidth < 600) ? 1 :
         (window.innerWidth < 900) ? 2 : 3;
}
// Instructions Schedule
  $('.flexslider-instr').flexslider({
    animation: "slide",
    animationLoop: false,
    slideshow: false,
    itemWidth: 210,
    itemMargin: 5,
    minItems: getGridSize(),
    maxItems: getGridSize(),
    startAt: 0,

  });


	if( $(".video__vimeo").length > 0 ) {
		$(".video__vimeo").each(function(){
			var target = $(this);
			var vimeoURL = $(this).attr("data-url");
			var apiURL = 'https://vimeo.com/api/oembed.json?url='+vimeoURL;
			$.get(apiURL,function(data){
				var thumbnail = data.thumbnail_url;
				target.css("background-image","url('"+thumbnail+"')");
			});
		});
	}


	var is_video_playing = false;

	var $slides = $('.flexslider .slides li');
    if ($slides.length > 0) {
        $slides.eq(1).add($slides.eq(-1)).find('img.lazy')
            .each(function () {
                var src = $(this).attr('data-src');
                $(this).removeClass('lazy');
                $(this).attr('src', src).removeAttr('data-src');
            });
    }

    var slideShow = $('.flexslider').flexslider({
		animation: "fade",
		smoothHeight: true,
		before: function (slider) {
			var $slides = $(slider.slides),
                index = slider.animatingTo,
                current = index,
                nxt_slide = current + 1,
                prev_slide = current - 1;
            if ($slides.length > 0) {
                $slides.eq(current).add($slides.eq(nxt_slide)).add($slides.eq(prev_slide))
                    .find('img.lazy').each(function () {
                        var src = $(this).attr('data-src');
                        $(this).removeClass('lazy');
                        $(this).attr('src', src).removeAttr('data-src');
                    });
                if($slides.eq(current).find('.videoIframe').length > 0){

	    			$(".videoIframeDiv").removeClass("playing");
	    			$(".videoIframe").hide();

                    $("body").addClass("current-slide-is-video");
                } else {
                	$("body").removeClass("current-slide-is-video");
                }
            }
		},
		start: function(slider){
			var $slides = $(slider.slides);
            if ($slides.length > 0) {
                play_flexslider_video(slider);
            }

		}
	});

    $(document).on("click",".flex-next, .flex-prev",function(e){
    	e.preventDefault();
    	if( $("iframe.videoIframe").length > 0 ) {
    		$("iframe.videoIframe").each(function(){
    			var type = $(this).attr("data-vid");
    			if(type=='youtube') {
    				var parent = $(this).parents(".videoIframeDiv");
    				var embedURL = parent.find(".playVidBtn").attr("data-embed");
    				if(e.target.outerText=='Next') {
    					$(this).attr("src",embedURL);
    				}
    			}
    			else if(type=='vimeo') {
 					var iframe = $(this)[0];
					var player = new Vimeo.Player(iframe);
					player.pause();
    			}
    		});
    	}
    });

    function play_flexslider_video(slider) {
		$(document).on("click",".playVidBtn",function(e){
			e.preventDefault();
			var type = $(this).attr("data-type");
			var parent = $(this).parents(".videoIframeDiv");
			if(type=='youtube') {
				var iframeSRC = $(this).attr("data-embed");
				parent.find("iframe.videoIframe")[0].src += "&autoplay=1";
				//parent.find("iframe.videoIframe").attr("src",iframeSRC+"&autoplay=1");
				parent.addClass("playing");
				parent.find("iframe.videoIframe").fadeIn();
				is_video_playing = true;
				slider.stop();
			}
			else if(type=='vimeo') {
				var iframe = parent.find("iframe.videoIframe")[0];
				var player = new Vimeo.Player(iframe);
				parent.addClass("playing");
				parent.find("iframe.videoIframe").fadeIn();
				player.play();
				slider.stop();
			}
		});


    }




 // When was this added?
 	// $(document).on('click', function (e) {
 	// 	var tag = $(this);
 	// 	var exceptions = ['todayToggle','todayLink','todayTxt','today-options', 'arrow'];
 	// 	var elementId = e.target.id;
  //   //console.log(e);
 	// 	var is_open = false;
 	// 	if( elementId=='today-options' ) {
 	// 		$(".topinfo .today").addClass("open");
 	// 	} else {
 	// 		if($.inArray(elementId, exceptions) != -1) {
	// 			if( $(".topinfo .today").hasClass("open") ) {
	// 				$(".topinfo .today").removeClass("open");
	// 			} else {
	// 				$(".topinfo .today").addClass("open");
	// 			}
	//  		} else {
	//  			$(".topinfo .today").removeClass("open");
	//  		}
 	// 	}
	// });


	// $('a[href*="#"]:not([href="#"])').click(function() {
  //   var headHeight = $("#masthead").height();
	// 	var offset = headHeight + 80;

  //   if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
  //       var target = $(this.hash);
  //       target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
  //       if (target.length) {
  //         $('html, body').animate({
  //             scrollTop: target.offset().top - offset
  //         }, 1000);
  //         return false;
  //       }
  //   }
	// });


	/* Load More */
	$(document).on("click","#loadMoreBtn",function(event){
		event.preventDefault();
		var loadButton = $(this);
		var pageURL = ( typeof $("a.page-numbers").eq(0)!=undefined ) ? $("a.page-numbers").attr("href") : '';
		var current_page = $(this).attr("data-current");
		var next_page = parseInt(current_page) + 1;
		var last_page = $(this).attr("data-end");
		if(pageURL) {
			var parts = pageURL.split("pg=");
			var num = parts[1];
			var url = pageURL.replace('pg='+num,'pg='+next_page);
			loadButton.attr("data-current",next_page);

			$(".next-posts").load(url + " .posts-inner .flex-inner",function(){
				var htmlContent = $(".next-posts .flex-inner").html();
				$("#data-container .flex-inner").append(htmlContent);
				$(".next-posts").html("");
				if(next_page==last_page) {
					$(".loadmorediv").html('').hide();
				}
			});
		}

	});

	$('#playYoutube').on('click', function(ev) {
		$(this).hide();
		$(".videoIframeDiv").addClass('play_video');
    $(".videoIframe")[0].src += "&autoplay=1";
    $("#banner").addClass("video-playing");
    ev.preventDefault();
  });

  $('.select-single').select2();

	/*
	*
	*	Wow Animation
	*
	------------------------------------*/
	new WOW().init();

	$('.js-blocks').matchHeight();
	$('.js-title').matchHeight();


	$(document).on("click","#toggleMenu",function(){
		$(this).toggleClass('open');
		$('body').toggleClass('open-mobile-menu');
	});

	/* Search Form (Header) */
	$(document).on("click","#topsearchBtn",function(e){
		e.preventDefault();
		$("#topSearchBar form").submit();
	});

	$(document).on("click","#searchHereBtn",function(e){
		e.preventDefault();
		$(this).toggleClass('search-open');
		$('#topSearchBar').toggleClass('show');
		$('body').toggleClass('search-form-open');
		$("input.search-field").focus();
	});

	$(document).on("click","#closeTopSearch",function(e){
		e.preventDefault();
		$('#searchHereBtn').removeClass('search-open');
		$('#topSearchBar').removeClass('show');
		$('body').removeClass('search-form-open');
		//$("input.search-field").val("");
	});

  if( $('.single #primary .intro-text-wrap.alt-color').length ) {
    if( $('.single #primary .intro-text-wrap.alt-color').next().hasClass('flexibleContentWrap') ) {
      if( $('.single #primary .intro-text-wrap.alt-color').next().find('section').length ) {
        var firstSection = $('.single #primary .intro-text-wrap.alt-color').next().find('section').first();
        if( firstSection.hasClass('column-data-section') ) {
          firstSection.addClass('padtop');
        }
      }
    }
  }

	/* Footer Subscribe Form */
	$('#footSubscribeForm input[type="email"]').on("focus",function(){
		$("#footSubscribeForm").addClass('input-focus');
	});
	$('#footSubscribeForm input[type="email"]').on("focusout blur",function(){
		$("#footSubscribeForm").removeClass('input-focus');
	});

	/* Ajax Load More */
	$(document).on("click","#loadMoreBtn2",function(e){
		e.preventDefault();
		var moreButton = $(this);
		var target = $(this);
		var perpage = target.attr("data-perpage");
		var posttype = target.attr("data-posttype");
		var paged = target.attr("data-page");
		var base_url = target.attr("data-baseurl");
		var next_page = parseInt(paged) + 1;
		var total_pages = target.attr("data-totalpages");
		var total = parseInt(total_pages);
		target.attr("data-page",next_page);
		var pageURL = currentURL + '?pg=' + paged;
		$("#tempContainer").load(pageURL+" #postLists",function(){

			if( $("#tempContainer #postLists").length>0 ) {
				var entries = $("#tempContainer #postLists").html();
				$(".wait").show();
				setTimeout(function(){
					$(entries).appendTo(".archive-posts-wrap #postLists");
					$(".wait").hide();
				},600);

				if(next_page>total) {
					moreButton.hide();
				}
			} else {
				moreButton.hide();
			}

		});



	});

	/* Ajax Load More */
	$(document).on("click","#loadMoreBtn3",function(e){
		e.preventDefault();
		var moreButton = $(this);
		var target = $(this);
		var perpage = target.attr("data-perpage");
		var posttype = target.attr("data-posttype");
		var paged = target.attr("data-page");
		var base_url = target.attr("data-baseurl");
		var next_page = parseInt(paged) + 1;
		var total_pages = target.attr("data-totalpages");
		var total = parseInt(total_pages);
		target.attr("data-page",next_page);
		var url = window.location.href;
		var countparams = url.split("=");
		var newURL = currentURL + '?pg=' + paged;
		var nextURL = currentURL + '?pg=' + next_page;
		if(countparams.length>1) {
			var str = url.replace(currentURL,'');
			newURL += str.replace('?','&');
			nextURL += str.replace('?','&');
		}
		$("#tempContainer").load(newURL+" #postLists",function(){

			if( $("#tempContainer #postLists").length>0 ) {
				var entries = $("#tempContainer #postLists").html();
				$(".wait").show();
				setTimeout(function(){
					$(entries).appendTo(".archive-posts-wrap #postLists");
					$(".wait").hide();
				},500);

				if(paged>=total) {
					moreButton.hide();
				}
			}

		});

		/* Hide More Button if end of records */
		$("#tempNext").load(nextURL+" #postLists",function(){
			if( $("#tempNext #postLists").length==0 ) {
				moreButton.hide();
			}
		});

	});

	$(document).on("change","select.facetwp-dropdown",function(){
		var opt = $(this).val();
		if( $(".morebuttondiv").length>0 ) {
			$(".morebuttondiv").load(currentURL+" .moreBtnSpan",function(){

			});
		}
	});

	if( $("#filter-form").length>0 ) {
		$(document).on("change",".select-filter",function(e){
			e.preventDefault();
			var opt = $(this).val();
			var name_sel_att = $(this).attr("name");
			var url = $("input#baseurl_input").val();
			var params = '';

			var n=1; $(".select-filter").each(function(){
				var nameAtt = $(this).attr("name");
				var delimiter = (n==1) ? '?':'&';
				var val = $(this).find("option:selected").val();
				params += delimiter + nameAtt +"="+val;
				n++;
			});

			var base_url = url + params;
			$("#loaderDiv").addClass("show");

			$("#load-post-div").load(base_url+" #load-data-div",function(){
				$('.select-single').select2();
				setTimeout(function(){
					$("#loaderDiv").removeClass("show");
				},600);
			});

		});
	}

	/* Align Bottom Page Vertically Center */
	if( $(".explore-other-stuff").length>0 ) {
		var totalEntries = $(".explore-other-stuff .entry").length;
		$(".explore-other-stuff .post-type-entries").addClass('column-list-'+totalEntries);
	}

	/* Ajax Load More */
	// $('a[href*="#"]:not([href="#"])').click(function() {
	//     var headHeight = $("#masthead").height();
	// 		var offset = headHeight + 80;
	//     if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	//         var target = $(this.hash);
	//         target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	//         if (target.length) {
	//           $('html, body').animate({
	//               scrollTop: target.offset().top - offset
	//           }, 1000);
	//           return false;
	//         }
	//     }
	// 	});

	if( typeof params.pid!='undefined' && params.pid!=null ) {
		if( $(".faqpid-"+params.pid).length>0 ) {
			view_faqs_info(params.pid);
		}
	}

	$(document).on("click",".faqGroup",function(e){
		e.preventDefault();
		$(".faqGroup").removeClass('active');
		var postid = $(this).attr("data-id");
		$(this).addClass('active');
		view_faqs_info(postid);
	});

	function view_faqs_info(postid) {
		var headHeight = $("#masthead").height();
		var offset = headHeight + 80
		var target = $("#faqItems");
		target.show();

		$('html, body').animate({
		scrollTop: target.offset().top - offset
		}, 1000);
		$.ajax({
			url : frontajax.ajaxurl,
			type : 'post',
			dataType : "json",
			data : {
				action 	: 'get_faq_group',
				post_id : postid
			},
			beforeSend:function(){
				$("#loaderDiv").appendTo(".main-faq-items");
				$("#loaderDiv").show();
			},
			success:function( obj ) {
				if(obj.result) {
					$("#faqsContainer").html(obj.result);
					setTimeout(function(){
						$("#loaderDiv").hide();
					},500);
					var newURL = currentURL + '?pid=' + postid;
					history.replaceState('',document.title,newURL);
				}
			},
			error:function() {
				$("#loaderDiv").hide();
			}
		});
	}


	/* More FAQs */
	$(document).on("click",".morefaqsBtn",function(e){
		e.preventDefault();
		var morefaqs = $(".morefaqs");
		morefaqs.hide();
		$(".faq-item").each(function(){
			if( $(this).hasClass('hide-faq') ) {
				$(this).removeClass('hide-faq').addClass("animated fadeIn");
			}
		});
	});

	/* Load More Entries:
		 - Race Series
	*/

	$(document).on("click","#loadMoreEntries",function(e){
		e.preventDefault();
		var d = new Date();
		var current = $(this).attr('data-current');
		var next = parseInt(current) + 1;
		var totalPages = $(this).attr('data-total-pages');
		$(this).attr('data-current',next);
		if( $("#pagination a.page-numbers").length>0 ) {
			var baseURL = $("#pagination a.page-numbers").eq(0).attr("href");
			var parts = baseURL.split("pg=");
			var newURL = parts[0] + 'pg=' + next;
			var nxt = next+1;
			$("#loaderDiv").show();
			$(".next-posts").load(newURL+" .result",function(){
				var content = $(".next-posts").html();
				$('.next-posts .postbox').addClass("animated fadeIn").appendTo("#data-container .result");
				setTimeout(function(){
					$("#loaderDiv").hide();
				},500);
			});

			if(next==totalPages) {
				$(".loadmorediv").hide();
			}
		}

	});


	$(".js-select2").select2({
		closeOnSelect : false,
		placeholder : "Select...",
		allowHtml: true,
		allowClear: true,
		tags: true
	});


	// if( $("select.js-select").length>0 ) {
	// 	$("select.js-select").each(function(){
	// 		var selectID = $(this).attr("id");
	// 		$("select#"+selectID).select2({
	// 			closeOnSelect : false,
	// 			placeholder : "Select...",
	// 			allowHtml: true,
	// 			allowClear: true,
	// 			tags: true,
	// 			width: 'resolve'
	// 		});
	// 	});
	// }

	$(document).on("click",".select2-selection--multiple",function (e) {
		var selectdiv = $(".customselectdiv").innerWidth();
		var w = selectdiv+2;
		$(".select2-container--default").css("width",w+"px");
	});

	/* Smooth Scrolling */
  if(window.location.hash){
    var hashUrl = window.location.hash;
    //SmoothScrolling(hashUrl);
    setTimeout(function(){
      SmoothScrolling(hashUrl);
    },500);
  }

	$('a[href*="#"]')
	  // Remove links that don't actually link to anything
	  .not('[href="#"]')
	  .not('[href="#0"]')
	  .click(function(event) {

	    // On-page links
	    if (
	      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
	      &&
	      location.hostname == this.hostname
	    ) {
	      // Figure out element to scroll to
	      var target = $(this.hash);
	      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
	      // Does a scroll target exist?
	      if (target.length) {
	        // Only prevent default if animation is actually gonna happen
	        event.preventDefault();
	        SmoothScrolling(this.hash);
	      } else {

          var anchor = $(this).attr('href');
          if( $('[data-anchor-target="'+anchor+'"]').length ) {
            event.preventDefault();
            SmoothScrolling(anchor);
          }
        }
	    }

  });


  function SmoothScrolling(anchor) {

    var offset = $('#masthead').outerHeight() + 150;

    if( $(anchor).length ) {
      var target = $(anchor);
      $('html, body').animate({
        scrollTop: target.offset().top - offset
      }, 500, function() {
        if ( target.is(":focus") ) {
          return false;
        } else {
          target.attr('tabindex','-1');
        };
      });
    }

    if( $('[data-anchor-target="'+anchor+'"]').length ) {
      var target = $('[data-anchor-target="'+anchor+'"]');
      $('html, body').animate({
        scrollTop: target.offset().top - offset
      }, 500, function() {
        if ( target.is(":focus") ) {
          return false;
        } else {
          target.attr('tabindex','-1');
        };
      });
    }
  }


  if( typeof params.filter!='undefined' && params.filter!=null ) {
    if( $('.filter-form').length ) {
      SmoothScrolling('#filter-form');
    }
  }



  if( $('.todaySnapshotInfo ul.location-tabs').length ) {

    if( $('.info-wrapper').length>1 ) {
      $('.info-wrapper').last().addClass('last');
    }

    // $('.todaySnapshotInfo ul.location-tabs li').each(function(){
    //   var button = $(this).find('button');
    //   var tabControl = button.attr('aria-controls');
    //   //Hide tab (Branch) if there's no activity schedule
    //   if( $('.info-wrapper#' + tabControl).length==0 ) {
    //     $(this).hide();
    //   }
    // });

    $(document).on('click', '.todaySnapshotInfo ul.location-tabs button', function(e){
      e.preventDefault();
      var target = $(this);
      var parent = target.parent();
      var control = $(this).attr('aria-controls');
      parent.addClass('active');
      $('.todaySnapshotInfo ul.location-tabs li').not(parent).removeClass('active');
      $('.todaySnapshotInfo ul.location-tabs button').not(target).attr('aria-selected','false');
      target.attr('aria-selected','true');
      var currentInfo = $('.info-wrapper#' + control);
      currentInfo.addClass('active');
      currentInfo.show();
      $('.info-wrapper').not('#'+control).removeClass('active');
      $('.info-wrapper').not('#'+control).hide();
    });

    $(document).on('click', '.todaySnapshotInfo .mobile-tab-heading', function(e){
      e.preventDefault();
      var target = $(this);
      var control = $(this).attr('aria-controls');
      var currentInfo = $('.info-wrapper#' + control);
      currentInfo.slideToggle();
      target.toggleClass('active');
      $('.info-wrapper').not('#'+control).removeClass('active');
      $('.info-wrapper').not('#'+control).slideUp();
      $('.todaySnapshotInfo .mobile-tab-heading').not(target).removeClass('active');
    });
  }

  //Homepage Calendar Tabs
  $(document).on("click",".tabs-calendar-wrapper .tab-item",function (e) {
    var tab = $(this);
    var tabPanel = $(this).attr('aria-controls');
    var currentPanel = '.tab-calendar-panel#' + tabPanel;
    $('.tabs-calendar-wrapper .tab-item').not(tab).removeClass('active');
    tab.toggleClass('active');
    if( $(this).attr('aria-selected')=='true' ) {
      $(this).attr('aria-selected','false');
    } else {
      $(this).attr('aria-selected','true');
    }
    if( $(currentPanel).length ) {
      $(currentPanel).addClass('active');
      $('.tab-calendar-panel').not(currentPanel).removeClass('active');
    }
    if( $('.custom-dropdown').length ) {
      var baseUrl = $('.custom-dropdown').attr('data-baseUrl');
      history.replaceState('','',baseUrl);
    }
  });


  //Created: 08.24.2024 [lisa]
  if( $('.custom-dropdown').length ) {
    $('.select-event-type').on('click', function(e){
      e.preventDefault();
      $(this).toggleClass('open');
      $(this).parents('.custom-dropdown').find('.dropdownlist').slideToggle();
      $(this).parents('.custom-dropdown').addClass('open');
    });
    $(document).on('click','.select-posttype', function(e){
      e.preventDefault();
      var d = new Date();
      var parent = $(this).parents('.custom-dropdown');
      var opt = $(this).text();
      var type = $(this).attr('data-val');
      var baseUrl = parent.attr('data-baseUrl');
      parent.find('.select-event-type span').text(opt);
      parent.attr('data-selected', type);
      parent.find('.dropdownlist li.default').removeClass('hidden');
      parent.find('.dropdownlist').slideUp();
      parent.removeClass('open');
      var newUrl = baseUrl + '?type=' + type;
      window.location.href = newUrl;

      //history.replaceState('','', newUrl);
      // $('#eventsGrid').load(newUrl+'&d='+d.getTime()+ ' .calendar-tab-events-posts',function(){
      // });
    });
  }

  if( typeof params.type!=undefined || params.type!=null ) {
    if( $('#eventsGrid').length ) {
      var target = $('#eventsGrid');
      setTimeout(function(){
        //scrollToSection(target,100);
        SmoothScrolling( '#eventsGrid' );
      },200);
    }
  }


  // function scrollToSection(target,offsetVal) {
  //   var adjustment = (offsetVal) ? offsetVal : 0;
  //   $('html, body').animate({
  //     scrollTop: target.offset().top - adjustment
  //   }, 1000, function() {
  //     target.focus();
  //     if (target.is(":focus")) {
  //       return false;
  //     } else {
  //       target.attr('tabindex','-1');
  //       target.focus();
  //     };
  //   });
  // }

  $(document).on('click', function(e){
    var target = $(e.target);

    if( target.hasClass('select-event-type') || target.parents('.select-event-type').length || target.parents('.dropdown-inner').length ) {
      //Do nothing...
    } else {
      if( $('.custom-dropdown.open').length ) {
        $('.select-event-type').trigger('click');
      }
    }
  });


  if( $('.teasers .child-card').length ) {
    var j=1;
    $('.teasers .child-card').each(function(){
      if( j % 2 == 0 ) {
        $(this).addClass('even');
      } else {
        $(this).addClass('odd');
      }
      j++;
    });
  }

  if( $('.single-activity-options .option').length ) {
    var activitiesCount = $('.single-activity-options .option').length;
    var countOptions = activitiesCount / 2;
        countOptions = Math.round(countOptions);
        $('.single-activity-options .option').each(function(k){
          if(k<countOptions) {
            $(this).appendTo('.single-activity-options .firstCol');
          } else {
            $(this).appendTo('.single-activity-options .secondCol');
          }
        });
  }



  //Popup Film Series
  $(document).on('click', '.button-popup-details', function(){
    var postid = $(this).attr('data-postid');
    $('body').addClass('modal-open');
    $.ajax({
      url : frontajax.ajaxurl,
      type : 'post',
      dataType : "json",
      data : {
        action  : 'film_series_details_popup',
        post_id : postid
      },
      beforeSend:function(){
        $("#loaderDiv").show();
      },
      success:function( obj ) {
        $("#loaderDiv").hide();
        if(obj.result) {
          $('#customModalContainer #customModalContent').html(obj.result);
          $('#customModalContainer').addClass('filmSeriesInfo show');
        }
      },
      error:function() {
        $("#loaderDiv").hide();
      }
    });
  });

  $(document).on('click', '.filmSeriesInfo #customModalClose', function(e){
    e.preventDefault();
    $('body').removeClass('modal-open');
    $('#customModalContainer').removeClass("filmSeriesInfo show");
    setTimeout(function(){
      $('#customModalContent').html("");
    },80);
  });


  if( $('.locationPanels').length ) {
    $(document).on('click', '.locationTabsWrapper .tab button', function(e){
      e.preventDefault();
      var tabId = $(this).attr('id');
      var tabSelected = $('.info-panel.' + tabId);
      tabSelected.addClass('active');
      $('.locationPanels .info-panel').not( tabSelected ).removeClass('active');
    });

    $(document).on('click', '.mobileTabHandle', function(e){
      e.preventDefault();
      var tabId = $(this).attr('aria-controls');
      var tabSelected = $('#' + tabId);
      //$(this).toggleClass('active');
      //$('.locationTabsWrapper .mobileTabHandle').not(this).removeClass('active');
      //tabSelected.slideToggle();
      if( $(this).attr('aria-expanded')=='false' ) {
        $(this).attr('aria-expanded','true');
      } else {
        $(this).attr('aria-expanded','false');
      }

      $('.locationPanels .info-panel').each(function(){
        if( $(this).attr('id')==tabId ) {
          $(this).slideToggle();
        } else {
          $(this).slideUp();
        }
      });
    });
  }


  $(document).on('click', '.accordion-handle', function(e){
    e.preventDefault();
    var target = $(this);
    var parent = $(this).parent();
    if( $(this).attr('aria-expanded')=='false' ) {
      $(this).attr('aria-expanded', 'true');
    } else {
      $(this).attr('aria-expanded', 'false');
    }
    $(this).toggleClass('active');
    $(this).next().slideToggle();
    //$('.accordion .accordion-item').not(parent).removeClass('active');
    $('.accordion .accordion-item').not(parent).each(function(){
      $(this).removeClass('active');
      $(this).find('.accordion-handle').removeClass('active');
      $(this).find('.accordion-details').slideUp();
    });
    parent.toggleClass('active');
  });

  if( $('.content-area-single blockquote').length ) {
    $('.content-area-single blockquote').each(function(){
      var prevBlock = $(this).prev();
      if( prevBlock.length==0 ) {
        $(this).addClass('mt-0');
      }
    });
  }

  //Move social media share icons to bottom page
  if( $('body.single-post').length && $('.simplesocialbuttons').length ) {
    $('.simplesocialbuttons').appendTo('#socialMediaShare');
  }

  if( $('#section-schedule.section-content ul.items').length ) {
    $('#section-schedule.section-content ul.items li').each(function(){
      if( $(this).find('.event').length && $(this).find('.event').text()=='' ) {
        $(this).addClass('no-event-info');
      }
      if( $(this).find('.time').length && $(this).find('.time').text()=='' ) {
        $(this).addClass('no-time-info');
      }
    });
  }


  $(document).on('click', '.filter-button', function(e){
    e.preventDefault();
    $(this).toggleClass('open');
    if( $(this).attr('aria-expanded')=='false' ) {
      $(this).attr('aria-expanded','true');
    } else {
      $(this).attr('aria-expanded','false');
    }
    $(this).next().find('ul').slideToggle();
  });

  $(document).on('click', 'ul.dropdown-options a', function(e){
    e.preventDefault();
    var optionText = $(this).text().trim();
    var option = $(this).attr('data-val');
    var actionUrl = $('form.filter').attr('action');
    var newUrl = actionUrl + '?filter=' + option;
    // history.replaceState('','', newUrl);
    // $('.filter-button').text(optionText);
    // $('.filter-button').trigger('click');
    window.location.href = newUrl;
  });




  if( $('a[href*="#data-accesso-keyword"]').length ) {
    $('a[href*="#data-accesso-keyword"]').each(function(){
      var parts = $(this).attr('href').split('#data-accesso-keyword-');
      var slug = parts[1];
      $(this).attr({
        'data-accesso-keyword': slug,
        'href':'javascript:void(0)'
      });
    });
  }

  /*===============================
    Today's Snapshot (per location)
    Pop-ups information
  ===============================*/

  //Today's Snapshot (Hours > Activity Schedule) pop-up
  $(document).on('click', '.popupSchedule', function(e){
    e.preventDefault();
    var schedule = $(this).attr('data-schedule');
    $('.activity-schedule-modal[data-schedule="'+schedule+'"]').show();
    $('#customModalContainer').addClass('open');
  });

  $(document).on('click', '#customModalClose.customModalClose1', function(e){
    e.preventDefault();
    $('#customModalContainer').removeClass('open');
    $('#customModalContent .activity-schedule-modal[data-schedule]').hide();
    $('#customModalContent .info-popup').hide();
    if( $(this).parent().find('.activity-schedule-modal').length ) {
      var params = {
        action  : 'activity_schedule_func',
        status : 'next',
        index : 0,
        reset : true
      };
      getActivityScheduleContent(params);
    }
  });

  $(document).on('keyup', function(e){
    //Close Modal when pressing Escape key
    if( e.which==27 ) {
      if( $('#customModalContainer').hasClass('open') ) {
       $('#customModalClose').trigger('click');
      }
    }
  });

  if( $('.activity-schedule-modal[data-schedule]').length && $('#customModalContainer').length ) {
    if( $('#customModalContent .activity-schedule-modal[data-schedule]').length==0 ) {
      $('.activity-schedule-modal[data-schedule]').appendTo('#customModalContent');
    }
  }

  if( $('.info-popup[data-todaypopinfo]').length && $('#customModalContainer').length ) {
    if( $('#customModalContent .info-popup[data-todaypopinfo]').length==0 ) {
      $('.info-popup[data-todaypopinfo]').appendTo('#customModalContent');
    }
  }

  if( $('.todaySnapshotInfo a[href*="#popup-"]').length ) {
    $('.todaySnapshotInfo a[href*="#popup-"]').each(function(){
      var hashTag = $(this).attr('href');
      $(this).attr({
        'data-todayinfo': hashTag,
        'href':'javascript:void(0)'
      });
    });
  }


  $(document).on('click', '#customModalClose.customModalClose2', function(e){
    e.preventDefault();
    $('#customModalContainer').removeClass('open');
    $('#customModalClose').removeClass('customModalClose2').addClass('customModalClose1');
    setTimeout(function(){
      $('.calendar-activity-schedule').remove();
    },300);
  });

  $(document).on('click','.calendar-grid-wrapper .activity-schedule-link a', function(e){
    e.preventDefault();
    var pageLink = $(this).attr('href');
    var d = new Date();
    $('#calendarPopHidden').load(pageLink + '&tmp='+d.getTime() + ' .schedule-activities-info ', function(content){
      var eventDate = $('#calendarPopHidden').find('h2.event-date').text();
      var eventContent = $('#calendarPopHidden').find('.todaySnapshotInfo').html();
      var content = '<div class="activity-schedule-modal calendar-activity-schedule" style="display:none">';
          content += '<div class="modal-title"><div class="modal-title-inner"><h2>Activity Schedule</h2><p class="hours-info">'+eventDate+'</p></div></div>';
          content += '<div class="modal-body"><div class="modal-body-inner"><div class="todaySnapshotInfo">'+eventContent+'</div></div></div>';
          content += '</div>';
      $('#customModalContent').append(content);
      $('#customModalClose').removeClass('customModalClose1').addClass('customModalClose2');
      $('#customModalContainer .calendar-activity-schedule').show();
      $('#customModalContainer .calendar-activity-schedule .location-tabs li.tab').each(function(){
        var tabContent = $(this).find('button').attr("aria-controls");
        if( $('.calendar-activity-schedule .info-wrapper#' + tabContent).length==0 ) {
          $(this).remove();
        }
      });
      setTimeout(function(){
        $('#customModalContainer').addClass('open');
      },80);
      
    });
  });

  $(document).on('click','a[data-todayinfo]', function(e){
    e.preventDefault();
    var hashTag = $(this).attr('data-todayinfo');
    if( $('#customModalContent .info-popup[data-todaypopinfo="'+hashTag+'"]').length ) {
      var currentInfo = $('#customModalContent .info-popup[data-todaypopinfo="'+hashTag+'"]');
      currentInfo.show();
      $('#customModalContainer').addClass('open');
    }
  });


  //TODAY (navigation)
  $(document).on('click', '.today--dropdown .branches a[data-location]', function(e){
    e.preventDefault();
    var branch = $(this).attr('data-location');
    $('.today--dropdown .branches a[data-location]').not(this).removeClass('active');
    $(this).toggleClass('active');
    if( $(this).attr('aria-expanded')=='false' ) {
      $(this).attr('aria-expanded','true');
    } else {
      $(this).attr('aria-expanded','false');
    }
    if( $('.today--dropdown .location-details li[data-location="'+branch+'"]').length ) {
      $('.today--dropdown .location-details li[data-location]').each(function(){
        var slug = $(this).attr('data-location');
        if(branch==slug) {
          $(this).addClass('active');
        } else {
          $(this).removeClass('active');
        }
      });
    }
  });

  if( $('.mobileNav li.has-children').length ) {
    $('.mobileNav li.has-children').each(function(){
      if( $(this).find('a.menu-link').length ) {
        var mHref = $(this).find('a.menu-link').attr('href');
        $(this).find('a.menu-link').attr({
          'href':'javascript:void(0)',
          'role':'button',
          'aria-expanded':'false'
        });
        $(this).find('a.menu-link').attr('data-link', mHref);
      }
    });

    $(document).on('click', '.mobileNav .has-children .menu-link', function(e){
      e.preventDefault();
      if( $(this).attr('aria-expanded')=='false' ) {
        $(this).attr('aria-expanded','true');
      } else {
        $(this).attr('aria-expanded','false');
      }
      $(this).next('.mega-menu').toggleClass('open').slideToggle();
    });

    $(document).on('click', '.mobileNav .mobile-info-toggle', function(e){
      e.preventDefault();
      $(this).toggleClass('active');
      $(this).next('.inner-info').toggleClass('open').slideToggle();
      if( $(this).attr('aria-expanded')=='false' ) {
        $(this).attr('aria-expanded', 'true');
      } else {
        $(this).attr('aria-expanded', 'false');
      }
    });
  }

  $(document).on('click', '.mobileNav .navlink-today', function(e){
    e.preventDefault();
    $(this).toggleClass('active');
    $(this).next('.today--dropdown').toggleClass('open').slideToggle();
  });

  $(document).on('click', '.mobileMenuToggle', function(e){
    e.preventDefault();
    $(this).toggleClass('active');
    $('body, .site-header-mobile').toggleClass('menu-open');
    $('.mobile-nav-wrapper, .mobile-nav-backdrop').toggleClass('open');
  });


  if( $('.flexibleContentWrap').length ) {
    let headingList = ['h1','h2','h3'];
    for(var h=0; h<headingList.length; h++) {
      var headTag = headingList[h];
      if( $(headTag).length ) {
        $(headTag).each(function(){
          var slug = $(this).text().trim();
          slug = slug.replace(/\W+(?!$)/g, '-').replace(/\W$/,'').toLowerCase();
          if(slug) {
            $(this).attr('data-anchor-target', '#'+slug);
          }
        });
      }
    }
  }

	if( $('.hero-wrapper .stats.teaser').length ) {
		if( $('#masthead div.navbar .stats.teaser').length==0 ) {
			$('.hero-wrapper .stats.teaser').clone().appendTo('#masthead div.navbar');
			$('#masthead div.navbar .stats.teaser').addClass('redtag');
		}
	}

  var mySwiper = new Swiper ('#activities--swiper', {
    speed: 400,
    spaceBetween: 10,
    initialSlide: 0,
    //truewrapper adoptsheight of active slide
    autoHeight: false,
    // Optional parameters
    direction: 'horizontal',
    loop: true,
    // delay between transitions in ms
    autoplay: 5000,
    autoplayStopOnLast: false, // loop false also
    effect: 'slide',
    // Distance between slides in px.
    spaceBetween: 20,
    //
    slidesPerView: 1,
    //
    centeredSlides: true,
    //
    slidesOffsetBefore: 0,
    //
    grabCursor: true,
  })


  if( $('body.page-template-page-food-beverage #main section.menu-sections.menu-sections-repeatable').length ) {
    $('body.page-template-page-food-beverage #main section.menu-sections.menu-sections-repeatable').last().addClass('last');
    if( $('body.page-template-page-food-beverage #main section.menu-sections.menu-sections-repeatable').last().find('.mscol').length ) {
      $('body.page-template-page-food-beverage #main section.menu-sections.menu-sections-repeatable').last().find('.mscol').last().addClass('last');
    }
  }

  if( $('body.page-template-page-about').length ) {
    $('body.page-template-page-about #main section').first().addClass('first');
    if( $('body.page-template-page-about #main section').first().hasClass('text_content_center_block') ) {
      $('body.page-template-page-about #main section').first().addClass('full-width-title');
      // $('body.page-template-page-about #main section').first().find('.textwrap').addClass('wow fadeInUp').attr({
      //   'data-wow-duration':'2s',
      //   'data-wow-delay':'1s',
      //   'data-wow-offset':'10'
      // });
      $(window).scroll(function() {
        var wHeight = $(window).scrollTop();
        if(wHeight  > 100) {
          $('#main .full-width-title .textwrap').addClass('reveal');
        } else {
          $('#main .full-width-title .textwrap').removeClass('reveal');
        }
      });
    }

    // var waypoint = new Waypoint({
    //   element: document.querySelector('.full-width-title .textwrap'),
    //   handler: function(direction) {
    //     console.log(direction);
    //     //console.log('Scrolled to waypoint!')
    //     if( direction=='down' ) {
    //       $('.full-width-title .textwrap').addClass('reveal');
    //     } else {
    //       $('.full-width-title .textwrap').removeClass('reveal');
    //     }
        
    //   }
    // });
  }



  if( $('.flexibleContentWrap').length ) {
    $('.flexibleContentWrap > *').last().addClass('last-element-section');
    if( $('.flexibleContentWrap .last-element-section section').find('[class*="columns-"]').length ) {
      $('.flexibleContentWrap .last-element-section section').find('[class*="columns-"]').find('.mscol').last().add('last-child-element');
    }
  }
  if( $('.flexible-content-wrapper').length ) {
    $('.flexible-content-wrapper > *').last().addClass('last-element-section');
  }

  if( $('#main .flexible-image-cards').length ) {
    var countDivs = $('#main .flexible-image-cards > *').length;
    if(countDivs>1) {
      $('#main .flexible-image-cards > *').last().addClass('last-element-section');
    } else {
      if(countDivs==1) {
        if( $('#main .flexible-image-cards > *').first().hasClass('tileWrapper') ) {
          $('#main .flexible-image-cards .tileWrapper > *').last().addClass('last-element-section');
        } else {
          $('#main .flexible-image-cards > *').addClass('last-element-section');
        }
      }
    }
  }

  if( $('.hero-wrapper .stats.teaser').length ) {
    if( $('.mobileRedTag .stats').length==0 ) {
      $('.hero-wrapper .stats.teaser').clone().appendTo('.mobileRedTag');
      setTimeout(function(){
        var redTagHeight = $('.mobileRedTag').outerHeight();
        if( $('.hero-wrapper').length ) {
          $('.hero-wrapper').css('margin-top',redTagHeight+'px');
        }
      },50);
    }
  }

  //Sitemap
  if( $('.navigation-sitemap .smap-menu-item').length ) {
    if( $('.navigation-sitemap.other-links ul').length ) {
      var siteMapLinks = '';
      // $('.sitemap-other-links .links-group').each(function(){
      //   siteMapLinks += '<li class="other-link">'+$(this).html()+'</li>';
      // });
      // if(siteMapLinks) {
      //   $('.navigation-sitemap ul.sitemap').append(siteMapLinks);
      // }
      $('.navigation-sitemap.other-links ul.sitemap').each(function(){
        if( $(this).find('li.other-link').length ) {
          $(this).find('li.other-link').each(function(){
            $(this).appendTo('.navigation-sitemap-main ul.sitemap');
          });
          
        }
      });
    }
  } else {
    $('.navigation-sitemap.other-links').show();
  }

  $('.masonry-grid').masonry({
    itemSelector: '.grid-item',
  });





  //MOBILE ONLY
  $(window).load('load resize', function(){
    moveTodayMenuLink();
  });
  function moveTodayMenuLink() {
    if( $(window).width() < 961  ) {
      if( $('.mobileTodayLink .nav-item-today').length==0 ) {
        if( $('.site-header-mobile div.right .nav-item.nav-item-today').length ) {
          $('.site-header-mobile div.right .nav-item.nav-item-today').appendTo('.mobileTodayLink');
        }
      }
    } else {
      if( $('.mobileTodayLink .nav-item-today').length ) {
        if( $('.mobileNav div.right .nav-item-today').length==0 ) {
          $('.mobileTodayLink .nav-item.nav-item-today').appendTo('.mobileNav div.right');
        }
      }
    }
  }

  //ACTIVITY SCHEDULE POPUP with Previous/Next
  $(document).on('click', '#customModalContent .scheduleNav', function(e){
    e.preventDefault();
    var modalContainer = $('#customModalContent');
    var previousButton = $('#customModalContent .scheduleNav.previous-schedule');
    var nextButton = $('#customModalContent .scheduleNav.next-schedule');
    var button = $(this);
    var dataNext = $(this).attr('data-index');
    var dataStatus = $(this).attr('data-action');
    var slug = $(this).attr('data-for');
    var max = 5;
    var newStat = dataNext;
    if(dataStatus=='next') {
      newStat = parseInt(dataNext) + 1;
      var prevStat = newStat-2;
      previousButton.attr('data-index', prevStat);
    } 
    else if(dataStatus=='previous') {
      newStat = parseInt(dataNext) - 1;
    } 
    $(this).attr('data-index', newStat);
    if(dataStatus=='next') {
      previousButton.removeClass('hide');
      if(newStat==max) {
        nextButton.addClass('hide');
      }
    }
    else if(dataStatus=='previous') {
      nextButton.removeClass('hide');
      var nextIndex = newStat+1;
      if(newStat<0) {
        previousButton.addClass('hide');
        nextButton.attr('data-index', 1);
      } else {
        nextButton.attr('data-index', nextIndex);
      }
    }
    var params = {
      action  : 'activity_schedule_func',
      status : dataStatus,
      index : dataNext
    };
    getActivityScheduleContent(params);
  });

  function getActivityScheduleContent(params) {
    var modalContainer = $('#customModalContent');
    var previousButton = $('#customModalContent .scheduleNav.previous-schedule');
    var nextButton = $('#customModalContent .scheduleNav.next-schedule');
    var isReset = (typeof params.reset!='undefined' ) ? params.reset : false;
    var max = 5;
    $.ajax({
      url : frontajax.ajaxurl,
      type : 'post',
      dataType : "json",
      data : params,
      beforeSend:function(obj){
      },
      success:function( obj ) {
        if(obj.result) {
          var postId = obj.ID;
          var content = $.parseHTML(obj.result);
          var modalTitle = $(content).find('.modal-title-inner').html();
          var modalBody = $(content).find('.modal-body').html();
          modalContainer.find('.modal-title-inner').html(modalTitle);
          modalContainer.find('.modal-title-inner').addClass('animated fadeIn');
          modalContainer.find('.modal-body').html(modalBody);
          modalContainer.find('.modal-body-inner').addClass('animated fadeInUp');
          $('.activity-schedule-modal').attr('data-pid', postId);

          if(isReset) {
            previousButton.attr('data-index',0);
            previousButton.addClass('hide');
            nextButton.attr('data-index',1);
            nextButton.removeClass('hide');
          }
        }
      }
    });
  }

});// END #####################################    END
