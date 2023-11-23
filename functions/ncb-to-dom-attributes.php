<?php
/**
 * Defines helper functions to build HTML from an array.
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Helpers
 * @since      1.0.0
 */

if ( ! function_exists( 'ncb_to_dom_attributes' ) ) {
	/**
	 * Implements to_dom_attributes($attributes).
	 *
	 * @param array $attributes A named array of DOM attributes.
	 *
	 * @return string A string of all DOM attributes (without leading or trailing spaces).
	 */
	function ncb_to_dom_attributes( $attributes ) {
		$attr_str = '';
		foreach ( $attributes as $attr_key => $attr_value ) {
			if ( is_null( $attr_value ) ) {
				$attr_str .= " $attr_key";
				continue;
			}

			if ( is_object( $attr_value ) ) {
				$attr_value = (array) $attr_value;
			}

			if ( is_array( $attr_value ) ) {
				$attr_value = implode( ' ', array_unique( array_filter( $attr_value ) ) );
			}

			$attr_value = 'href' === $attr_key ? esc_url( $attr_value ) : esc_attr( $attr_value );
			$attr_str  .= " {$attr_key}=\"{$attr_value}\"";
		}

		return trim( $attr_str );
	}
}
