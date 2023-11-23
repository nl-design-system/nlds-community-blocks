<?php
/**
 * Defines helper functions to build HTML from an array.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Helpers
 */

if ( ! function_exists( 'ncb_denhaag_icon' ) ) {
	/**
	 * Helper for in Denhaag Blocks to reduce duplication.
	 *
	 * @param {string} $id The ID from the SVG sprite.
	 * @param {array}  $attributes Attributes to overwrite the default.
	 *
	 * @return string
	 */
	function ncb_denhaag_icon( $id = 'ncb-denhaag-external-icon', $attributes = [] ) {

		$id = sanitize_title( $id );

		// Remove the # if applied.
		if ( str_starts_with( $id, '#' ) ) {
			$id = substr( $id, 1 );
		}

		// Prepend with `ncb-denhaag-` for the correct format.
		if ( ! str_starts_with( $id, 'ncb-denhaag-' ) ) {
			$id = "ncb-denhaag-$id";
		}

		// Append with `-icon` for the correct format.
		if ( ! str_ends_with( $id, '-icon' ) ) {
			$id = "$id-icon";
		}

		return sprintf(
			'<svg %s><use href="#%s" /></svg>',
			ncb_to_dom_attributes(
				wp_parse_args(
					$attributes,
					[
						'aria-hidden'     => 'true',
						'class'           => 'denhaag-icon',
						'width'           => '1em',
						'height'          => '1em',
						'viewBox'         => '0 0 24 24',
						'fill'            => 'none',
						'xmlns'           => 'http://www.w3.org/2000/svg',
						'focusable'       => 'false',
						'shape-rendering' => 'auto',
						'role'            => 'img',
					]
				)
			),
			esc_attr( $id )
		);
	}
}
