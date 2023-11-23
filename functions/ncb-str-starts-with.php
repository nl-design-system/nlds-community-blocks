<?php
/**
 * Defines plugin helper functions.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Helpers
 */

if ( ! function_exists( 'ncb_str_starts_with' ) ) {
	/**
	 * Fallback function for PHP versions lower than 8.0
	 *
	 * @param string $haystack Full string to check in.
	 * @param string $needle Needle to check if is equal to first characters in `$haystack`.
	 *
	 * @return bool
	 */
	function ncb_str_starts_with( $haystack, $needle ) {
		if ( function_exists( 'str_starts_with' ) ) {
			return str_starts_with( $haystack, $needle );
		}

		return substr( $haystack, 0, strlen( $needle ) ) === $needle;
	}
}
