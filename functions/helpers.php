<?php
/**
 * Defines helper functions.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Helpers
 */

if ( ! function_exists( 'ncb_get_url' ) ) {

	/**
	 * Get Url by input value, which can be directly a string, or an object/array.
	 * When it's an array it will check for the ID to fetch the WordPress permalink.
	 *
	 * @param string|array|object $value provided value.
	 *
	 * @return false|string|WP_Error
	 */
	function ncb_get_url( $value = '' ) {

		if ( empty( $value ) ) {
			return '#';
		}

		// Return valid url if is string.
		if ( is_string( $value ) ) {
			if ( wp_http_validate_url( $value ) ) {
				return esc_url( $value );
			}

			if ( str_starts_with( $value, 'mailto:' ) || str_starts_with( $value, 'tel:' ) ) {
				return $value;
			}
		}

		// Cast to array if is object.
		if ( is_object( $value ) ) {
			$value = (array) $value;
		}

		if ( is_array( $value ) ) {

			// Get The archive link of any post_type.
			if ( ! empty( $value['kind'] ) && 'taxonomy' === $value['kind'] ) {
				// phpcs:ignore WordPress.PHP.DisallowShortTernary.Found
				return get_term_link( $value['id'], esc_attr( $value['type'] ?: 'category' ) );
			}

			// Check within array if post/page ID or url.
			if ( ! empty( $value['id'] ) && is_numeric( $value['id'] ) ) {
				return get_permalink( $value['id'] );
			}

			// Return url, if not already returned permalink or archive link.
			if ( ! empty( $value['url'] ) ) {
				return ncb_get_url( $value['url'] );
			}

			// Return url, if not already returned permalink or archive link.
			if ( ! empty( $value['title'] ) && wp_http_validate_url( $value['title'] ) ) {
				return ncb_get_url( $value['title'] );
			}
		}

		// Return default hashtag.
		return '#';
	}
}

if ( ! function_exists( 'ncb_is_external_url' ) ) {

	/**
	 * Helper to check if url is external.
	 *
	 * @param array|object|string $input Array, object or string with the URL to check if is external or not.
	 *
	 * @return bool
	 */
	function ncb_is_external_url( $input ) {

		if ( empty( $input ) ) {
			return false;
		}

		$ncb_home_url = defined( 'WP_HOME' ) ? WP_HOME : home_url();

		// Check if domain is within the provided string.
		if ( is_string( $input ) ) {
			if ( wp_http_validate_url( $input ) ) {
				return false === strpos( untrailingslashit( $input ), untrailingslashit( $ncb_home_url ) );
			}

			if ( str_starts_with( $input, 'mailto:' ) || str_starts_with( $input, 'tel:' ) ) {
				return false;
			}
		}

		// Cast to array if is object.
		if ( is_object( $input ) ) {
			$input = (array) $input;
		}

		// Looks like a Gutenberg LinkControl output, check for specific string.
		if ( isset( $input['type'] ) && 'URL' === $input['type'] ) {
			if ( isset( $input['url'] ) ) {
				return ncb_is_external_url( $input['url'] );
			}
			// If Gutenberg LinkControl output fails to fetch the `url`, we check for the `title` since this is also the URL.
			if ( isset( $input['title'] ) ) {
				return ncb_is_external_url( $input['title'] );
			}
		} elseif ( ! isset( $input['type'] ) && isset( $input['url'] ) ) {
			// When `type` isn't set. but `url` is. Gutenberg LinkControl output is somewhat random.
			return ncb_is_external_url( $input['url'] );
		}

		return false;
	}
}

if ( ! function_exists( 'ncb_get_file_size' ) ) {

	/**
	 * Return filesize string from attachment id.
	 *
	 * @param int $attachment_id The ID of the attachment.
	 *
	 * @return string
	 */
	function ncb_get_file_size( $attachment_id ) {

		if ( ! $attachment_id || ! is_numeric( $attachment_id ) ) {
			// Nothing to see here.
			return '0 B';
		}

		$ncb_file = get_attached_file( $attachment_id );

		if ( empty( $ncb_file ) || ! file_exists( $ncb_file ) || is_wp_error( $ncb_file ) ) {
			// File can't be reached, probably the file isn't in the uploads directory.
			return '0 B';
		}

		$ncb_bytes = filesize( $ncb_file );

		if ( ! $ncb_bytes ) {
			// File can't be reached.
			return '0 B';
		}

		$ncb_sizes            = [ 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' ];
		$ncb_size_calculation = floor( log( $ncb_bytes ) / log( 1024 ) );

		return sprintf( '%s %s', number_format_i18n( $ncb_bytes / pow( 1024, floor( $ncb_size_calculation ) ), 1 ), $ncb_sizes[ $ncb_size_calculation ] );
	}
}

if ( ! function_exists( 'ncb_file_get_contents' ) ) {
	/**
	 * Return contents of a file.
	 * Will check for local files or URLs; while you should NEVER use file_get_contents on URLs, it is possible.
	 *
	 * @param string $path_or_url The path to the file, or a URL.
	 *
	 * @return string
	 */
	function ncb_file_get_contents( $path_or_url ) {
		if ( wp_http_validate_url( $path_or_url ) ) {
			$result = wp_safe_remote_get( $path_or_url );
			if ( $result && ! is_wp_error( $result ) ) {
				return wp_remote_retrieve_body( $result );
			}
		}
		if ( is_file( $path_or_url ) ) {
			// We know that the file is local and exists, possible misuse of file_get_contents is no longer an issue.
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			return file_get_contents( $path_or_url );
		}

		return false;
	}
}
