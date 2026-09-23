<?php
/**
 * Reject oversized image uploads.
 *
 * Editors were uploading straight-from-camera JPEGs (a 52 MB, 5433x8149 photo turned up in
 * the Media Library in Feb 2026). Those never belong on a web page. This blocks any *image*
 * larger than the limit below with a friendly message telling the editor to resize first.
 * PDFs, video, audio and every other file type are not affected, so large documents still upload.
 *
 * Limit can be changed with the 'bellaworks_max_image_upload_bytes' filter.
 *
 * @package bellaworks
 */

/**
 * Maximum accepted image upload size in bytes (default 8 MB).
 *
 * @return int
 */
function bellaworks_max_image_upload_bytes() {
	return (int) apply_filters( 'bellaworks_max_image_upload_bytes', 8 * MB_IN_BYTES );
}

/**
 * Runs before WordPress moves an upload into place; returning an 'error' key aborts it.
 *
 * @param array $file The $_FILES entry: name, type, tmp_name, error, size.
 * @return array
 */
function bellaworks_limit_image_upload_size( $file ) {
	if ( ! empty( $file['error'] ) || empty( $file['size'] ) ) {
		return $file; // Already failing, or nothing to check.
	}

	// Decide by the real file contents, not the browser-supplied type or the extension.
	$check = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'] );
	$type  = ! empty( $check['type'] ) ? $check['type'] : ( isset( $file['type'] ) ? $file['type'] : '' );
	if ( 0 !== strpos( (string) $type, 'image/' ) ) {
		return $file; // Not an image: PDFs, video, zip, etc. pass through untouched.
	}

	$limit = bellaworks_max_image_upload_bytes();
	if ( (int) $file['size'] > $limit ) {
		$file['error'] = sprintf(
			/* translators: 1: uploaded file size, 2: maximum allowed size */
			__( 'This image is %1$s. Images larger than %2$s cannot be uploaded to the site. Please resize it to no more than 2500 pixels on the long edge and export it for web (JPEG, quality around 80) before uploading. Large PDFs and other documents are not affected by this limit.', 'bellaworks' ),
			size_format( (int) $file['size'] ),
			size_format( $limit )
		);
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'bellaworks_limit_image_upload_size' );
add_filter( 'wp_handle_sideload_prefilter', 'bellaworks_limit_image_upload_size' );

/**
 * Tell editors the limit up front on the upload screens instead of only after a failed upload.
 */
function bellaworks_image_upload_limit_notice() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || ! in_array( $screen->id, array( 'upload', 'media' ), true ) ) {
		return;
	}
	printf(
		'<div class="notice notice-info"><p>%s</p></div>',
		esc_html( sprintf(
			/* translators: %s: maximum allowed image size */
			__( 'Images must be %s or smaller. Resize photos to about 2500 pixels on the long edge before uploading. This limit does not apply to PDFs or other documents.', 'bellaworks' ),
			size_format( bellaworks_max_image_upload_bytes() )
		) )
	);
}
add_action( 'admin_notices', 'bellaworks_image_upload_limit_notice' );
