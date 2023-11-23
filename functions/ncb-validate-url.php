<?php
/**
 * Defines helper functions to check if is valid url.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Helpers
 */

/**
 * Check if is valid url.
 *
 * @package              nlds-community-blocks
 * @subpackage           helpers
 * @since                1.0.0
 * @author               Paul van Impelen <paul@acato.nl>
 * @link                 https://acato.nl
 * @category             Helpers
 */
if ( ! function_exists( 'ncb_validate_url' ) ) {

	/**
	 * Helper to check if url is valid.
	 *
	 * @param string $input String with the URL to check if is valid or not.
	 *
	 * @return bool
	 */
	function ncb_validate_url( $input ) {

		if ( empty( $input ) || ! is_string( $input ) ) {
			return false;
		}

		// Default HTTP links.
		if ( wp_http_validate_url( $input ) ) {
			return true;
		}

		// Mailto links.
		if ( ncb_str_starts_with( $input, 'mailto:' ) ) {
			$email = substr( $input, 7 );

			if ( str_contains( $email, '?' ) ) {
				return is_email( substr( $email, 0, strpos( $email, '?' ) ) );
			}

			return is_email( $email );
		}

		// Phone numbers.
		if ( ncb_str_starts_with( $input, 'tel:' ) ) {
			$digits = preg_replace( '/\D/', '', $input );

			// Check if the number is not empty and contains only digits.
			if ( 0 === strlen( $digits ) || ! ctype_digit( $digits ) ) {
				return false;
			}

			// Check if the number starts with a valid country code (optional).
			if ( preg_match( '/^(\+|00)(\d{1,3})/', $digits, $matches ) ) {
				$country_code = $matches[0];
				$digits       = substr( $digits, strlen( $country_code ) );
			}

			// Check if the remaining digits match the expected length and format.
			return (bool) preg_match( '/^(\d{3})\d{3}\d{4}$/', $digits );
		}

		// Internal link.
		return ncb_str_starts_with( $input, '#' );
	}
}
