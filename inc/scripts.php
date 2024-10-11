<?php
/**
 * Enqueue scripts and styles.
 */
function bellaworks_scripts() {
	wp_enqueue_style( 
		'bellaworks-style',
		 get_stylesheet_uri(),
		 array(),
		 '2.01'
	);
  wp_enqueue_style( 'swiper-style', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css' );
  wp_enqueue_style( 'jquery-ui-style', 'https://code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css' );
	wp_deregister_script('jquery');
	wp_register_script('jquery', 'https://code.jquery.com/jquery-3.3.1.min.js', false, '3.5.1', false);
	wp_enqueue_script('jquery');

  wp_enqueue_script( 
  	'jquery-migrate','https://code.jquery.com/jquery-migrate-1.4.1.min.js', 
  	array(), '20200713', 
  	false 
  );

  wp_enqueue_script( 
    'jquery-ui','https://code.jquery.com/ui/1.13.3/jquery-ui.js', 
    array(), '1.13.3', 
    false 
  );


	wp_enqueue_script( 
			'gsap',
			get_template_directory_uri() . '/assets/js/vendors/gsap.min.js', 
			array(), '20200713', 
			false 
		);
	wp_enqueue_script( 
			'gsap-easepack',
			get_template_directory_uri() . '/assets/js/vendors/gsap-easepack.min.js', 
			array(), '20200713', 
			false 
		);
	// wp_enqueue_script( 
	// 		'scrolltrigger','https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/ScrollTrigger.min.js', 
	// 		array(), '20200713', 
	// 		false 
	// 	);

	// wp_enqueue_script( 
	// 		'lenis','https://unpkg.com/@studio-freight/lenis@1.0.33/dist/lenis.min.js', 
	// 		array(), '20200713', 
	// 		false 
	// 	);

	wp_enqueue_script( 
			'bellaworks-blocks', 
			get_template_directory_uri() . '/assets/js/vendors.min.js', 
			array(), '20200713', 
			true 
		);

	wp_enqueue_script( 
			'colorbox', 
			get_template_directory_uri() . '/assets/js/vendors/colorbox.js', 
			array(), '2.12.2', 
			true 
		);

	wp_enqueue_script( 
			'vimeo-player', 
			'https://player.vimeo.com/api/player.js', 
			array(), '2.12.2', 
			true 
		);

	wp_enqueue_script( 
			'bellaworks-carousel', 
			get_template_directory_uri() . '/assets/js/vendors/owl.carousel.min.js', 
			array(), '20200101', 
			true 
		);

  wp_enqueue_script( 
      'bellaworks-bootstrap', 
      get_template_directory_uri() . '/assets/js/vendors/bootstrap.min.js', 
      array(), '4.5.2', 
      true 
    );

	// wp_enqueue_script( 
	// 		'bellaworks-boostrap', 
	// 		'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', 
	// 		array(), '20200101', 
	// 		true 
	// 	);

	// wp_enqueue_script( 
	// 		'bellaworks-boostrap', 
	// 		get_template_directory_uri() . 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', 
	// 		array(), '20200713', 
	// 		true 
	// 	);

  wp_enqueue_script( 
    'bellaworks-swiper', 
    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', 
    array(), '05222024', true 
  );

  wp_enqueue_script( 
    'bellaworks-custom', 
    get_template_directory_uri() . '/assets/js/custom.js', 
    array(), '2.37', 
    true 
  );
  wp_enqueue_script( 
    'cusom-calendar', 
    get_template_directory_uri() . '/assets/js/calendar.js', 
    array(), '1.0.0', 
    true 
  );

	wp_localize_script( 'bellaworks-custom', 'frontajax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' )
	));

	
	wp_enqueue_script( 
		'font-awesome', 
		'https://use.fontawesome.com/8f931eabc1.js', 
		array(), '20180424', 
		true 
	);



	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bellaworks_scripts' );
