<?php
/**
 * Defines filter function for the denhaag/meta block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Filters
 */

/**
 * Return boolean based on post types we want to allow to show the ReadSpeaker.
 *
 * @param bool   $state     The default state.
 * @param string $post_type Post types we want to allow.
 *
 * @return bool
 */
function ncb_filter_denhaag_meta_show_readspeaker( bool $state, string $post_type = 'post' ) {
	// ReadSpeaker is currently not build.
	return false;

	// phpcs:disable
	return in_array(
		esc_attr( $post_type ),
		array_keys(
			array_merge(
				get_post_types(
					[

						'public'   => true,
						'_builtin' => false,
					],
					'names'
				),
				get_post_types(
					[
						'public'   => true,
						'_builtin' => true,
					],
					'names'
				)
			)
		),
		true
	);
	// phpcs:enable
}

add_filter( 'ncb_denhaag_meta_show_readspeaker', 'ncb_filter_denhaag_meta_show_readspeaker', 10, 2 );
