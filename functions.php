<?php
/**
 * bellaworks functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package bellaworks
 */

/* date_default_timezone_set('America/New_York'); */

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/theme-setup.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/scripts.php';

/**
 * Custom Post Types.
 */
require get_template_directory() . '/inc/post-types.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

require get_template_directory() . '/inc/anti-email-spam.php';

require get_template_directory() . '/inc/category-description.php';

/**
 * Post Pagination
 */
require get_template_directory() . '/inc/pagination.php';


/**
 * Theme Specific additions.
 */
require get_template_directory() . '/inc/theme.php';

/**
 * Block & Disable All New User Registrations & Comments Completely.
 * Description:  This simple plugin blocks all users from being able to register no matter what, 
 *				 this also blocks comments from being able to be inserted into the database.
 */
// require get_template_directory() . '/inc/block-all-registration-and-comments.php';

/**
 * Customizer additions.
 */
// require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

require get_template_directory() . '/inc/visual-biography-editor/visual-editor-biography.php';


require get_template_directory() . '/inc/func-activity-passes.php';

/**
 * Gravity Forms: hide Stripe card + billing fields at a $0 total (replaces Total-based conditional logic, which loops in GF 3.x).
 */
require get_template_directory() . '/inc/gf-zero-total.php';

/**
 * Race registration experience: full-screen, one-question-at-a-time skin for race Gravity Forms (per-form configs in inc/race-registration/).
 */
require get_template_directory() . '/inc/race-registration.php';

/**
 * Responsive + lazy-loaded image helper for templates (bellaworks_image()).
 */
require get_template_directory() . '/inc/images.php';

/**
 * Block oversized image uploads (PDFs and other documents are not limited).
 */
require get_template_directory() . '/inc/upload-limits.php';
