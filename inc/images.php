<?php
/**
 * Responsive, lazy-loaded image output for theme templates.
 *
 * The templates used to print raw <img src="<?php echo $img['url'] ?>"> tags, which bypass
 * everything WordPress does for images: no srcset/sizes, no loading="lazy", no decoding="async",
 * no fetchpriority on the LCP image. bellaworks_image() routes every template image through
 * wp_get_attachment_image() whenever an attachment ID can be found, and falls back to a plain
 * lazy-loaded tag when it can't (external URLs, static theme assets).
 *
 * Usage:
 *   echo bellaworks_image( $acf_image_array );                          // ACF image field, array return format
 *   echo bellaworks_image( $acf_image_array, 'full', array( 'class' => 'hero', 'loading' => 'eager' ) );
 *   echo bellaworks_image( $url_string, 'large', array( 'alt' => 'Kayaker' ) );   // URL: ID is looked up
 *   echo bellaworks_image( 123, 'medium' );                              // attachment ID
 *
 * @package bellaworks
 */

/**
 * Resolve whatever a template has (ACF array, attachment ID, URL) to an attachment ID, or 0.
 *
 * @param mixed $image
 * @return int
 */
function bellaworks_image_id( $image ) {
	if ( is_numeric( $image ) ) {
		return (int) $image;
	}
	if ( is_array( $image ) ) {
		if ( ! empty( $image['ID'] ) ) {
			return (int) $image['ID'];
		}
		if ( ! empty( $image['id'] ) ) {
			return (int) $image['id'];
		}
		if ( ! empty( $image['url'] ) ) {
			return bellaworks_image_id( $image['url'] );
		}
		return 0;
	}
	if ( is_string( $image ) && '' !== $image ) {
		static $cache = array();
		if ( isset( $cache[ $image ] ) ) {
			return $cache[ $image ];
		}
		$url = $image;
		// Only uploads can be attachments; skip external and theme asset URLs without a query.
		$uploads = wp_get_upload_dir();
		if ( empty( $uploads['baseurl'] ) || false === strpos( $url, $uploads['baseurl'] ) ) {
			return $cache[ $image ] = 0;
		}
		$id = attachment_url_to_postid( $url );
		if ( ! $id ) {
			// Sized URL (…-300x200.jpg) → try the original.
			$original = preg_replace( '/-\d+x\d+(\.[a-zA-Z0-9]+)$/', '$1', $url );
			if ( $original !== $url ) {
				$id = attachment_url_to_postid( $original );
			}
		}
		return $cache[ $image ] = (int) $id;
	}
	return 0;
}

/**
 * Output an image tag with srcset/sizes and lazy loading.
 *
 * @param mixed  $image ACF image array, attachment ID, or URL string.
 * @param string $size  Registered image size for the fallback src. Use 'full' for hero / full-width images.
 * @param array  $attrs Extra attributes (class, alt, loading, fetchpriority, sizes, id, style, data-*...).
 *                      Pass 'loading' => 'eager' for above-the-fold images; otherwise WordPress decides
 *                      (its default marks the first few images on the page eager, the rest lazy).
 * @return string HTML, or '' when there is no image.
 */
function bellaworks_image( $image, $size = 'large', $attrs = array() ) {
	if ( empty( $image ) ) {
		return '';
	}
	$attrs = (array) $attrs;
	foreach ( $attrs as $k => $v ) {
		if ( null === $v || false === $v ) {
			unset( $attrs[ $k ] );
		}
	}

	/*
	 * Lazy-load policy for images the template didn't hint:
	 * WordPress leaves the first few in-loop images eager and lazy-loads the rest, but many of
	 * these templates print images outside the main loop, where WordPress treats everything as
	 * above the fold. So after the first three unhinted images on a page, force lazy. Heroes
	 * pass 'loading' => 'eager' explicitly and are never affected.
	 */
	static $unhinted = 0;
	if ( ! isset( $attrs['loading'] ) ) {
		$unhinted++;
		if ( $unhinted > 3 ) {
			$attrs['loading'] = 'lazy';
		}
	}

	$id = bellaworks_image_id( $image );

	if ( $id && wp_attachment_is_image( $id ) ) {
		if ( ! isset( $attrs['decoding'] ) ) {
			$attrs['decoding'] = 'async';
		}
		// ACF arrays carry the alt the editor set; keep it when the template didn't pass one.
		if ( ! isset( $attrs['alt'] ) && is_array( $image ) && isset( $image['alt'] ) ) {
			$attrs['alt'] = $image['alt'];
		}
		$html = wp_get_attachment_image( $id, $size, false, $attrs );
		if ( $html ) {
			return $html;
		}
	}

	// Fallback: plain tag. External URL, theme asset, or an upload we couldn't match.
	if ( is_array( $image ) ) {
		$src = isset( $image['url'] ) ? $image['url'] : '';
		if ( ! isset( $attrs['alt'] ) && isset( $image['alt'] ) ) {
			$attrs['alt'] = $image['alt'];
		}
		if ( ! isset( $attrs['width'] ) && ! empty( $image['width'] ) && ! empty( $image['height'] ) ) {
			$attrs['width']  = (int) $image['width'];
			$attrs['height'] = (int) $image['height'];
		}
	} elseif ( is_numeric( $image ) ) {
		$src = wp_get_attachment_url( (int) $image );
	} else {
		$src = (string) $image;
	}
	if ( '' === $src ) {
		return '';
	}
	if ( ! isset( $attrs['loading'] ) ) {
		$attrs['loading'] = 'lazy';
	}
	if ( ! isset( $attrs['decoding'] ) ) {
		$attrs['decoding'] = 'async';
	}
	if ( ! isset( $attrs['alt'] ) ) {
		$attrs['alt'] = '';
	}
	// Drop srcset/sizes hints that only make sense with a resolved attachment.
	unset( $attrs['sizes'], $attrs['srcset'] );

	$html = '<img src="' . esc_url( $src ) . '"';
	foreach ( $attrs as $name => $value ) {
		$name = preg_replace( '/[^a-zA-Z0-9_:-]/', '', (string) $name );
		if ( '' === $name ) {
			continue;
		}
		$html .= ' ' . $name . '="' . esc_attr( $value ) . '"';
	}
	return $html . '>';
}
