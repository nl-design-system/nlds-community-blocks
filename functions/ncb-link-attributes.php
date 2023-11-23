<?php
/**
 * Defines helper functions to make sure all the link attributes are set.
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
if ( ! function_exists( 'ncb_link_attributes' ) ) {

	/**
	 * Helper to return all link attributes,
	 *
	 * @param array  $input Array of link attributes.
	 * @param string $title The title of the link.
	 *
	 * @return array|false
	 */
	function ncb_link_attributes( $input = [], $title = '' ) {
		if ( empty( $input ) || ! is_array( $input ) ) {
			return false;
		}

		$attributes = [
			'href' => ncb_get_url( $input ),
		];

		$attributes['title'] = ! empty( $title )
			? $title
			: (
				! empty( $input['title'] )
					? $input['title']
					: null
			);

		if ( ! empty( $input['opensInNewTab'] ) ) {
			$attributes['target'] = '_blank';
			$attributes['rel'][]  = 'noreferrer';
			$attributes['rel'][]  = 'noopener';
		}

		if ( ! empty( $input['noFollow'] ) ) {
			$attributes['rel'][] = 'nofollow';
		}

		if ( ! empty( $input['sponsored'] ) ) {
			$attributes['rel'][] = 'sponsored';
		}

		return array_filter( $attributes );
	}
}
