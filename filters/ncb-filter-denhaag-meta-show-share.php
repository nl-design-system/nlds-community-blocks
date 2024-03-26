<?php
/**
 * Defines filter function for the denhaag/meta block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Filters
 */

/**
 * Return boolean based on post types we want to allow to show the Share buttons.
 *
 * @param bool   $state     The default state.
 * @param string $post_type Post types we want to allow.
 *
 * @return bool
 */
function ncb_filter_denhaag_meta_show_share( bool $state, string $post_type = 'post' ): bool {
	return in_array( esc_attr( $post_type ), [ 'post' ], true );
}

add_filter( 'ncb_denhaag_meta_show_share', 'ncb_filter_denhaag_meta_show_share', 10, 2 );
