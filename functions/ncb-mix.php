<?php
/**
 * Get the ID from the mix-manifest file.
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Helpers
 * @since      1.0.0
 */

if ( ! function_exists( 'ncb_mix' ) ) {

	/**
	 * Just a little helper to get filenames from the mix-manifest file.
	 *
	 * @param {string} $path to file.
	 *
	 * @return string
	 */
	function ncb_mix( $path ): string {
		static $manifest;
		if ( empty( $manifest ) ) {

			$manifest = NCB_ABSPATH . NCB_ASSETS_DIR . 'mix-manifest.json';

			if ( ! file_exists( $manifest ) ) {
				return NCB_ASSETS_URL . $path;
			}

			$manifest = get_object_vars(
				json_decode(
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
					file_get_contents( $manifest ),
				)
			);
		}

		if ( ! str_starts_with( $path, '/' ) ) {
			$path = '/' . $path;
		}

		if ( empty( $manifest[ $path ] ) ) {
			return NCB_ASSETS_URL . $path;
		}

		return untrailingslashit( NCB_ASSETS_URL ) . $manifest[ $path ];
	}
}
