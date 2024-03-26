<?php
/**
 * Defines filter function for the denhaag/meta block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Filters
 */

/**
 * Returns array of metadata we want to show in the denhaag/meta block.
 *
 * @param string $post_type The post type.
 * @param int    $post_id   The post ID.
 *
 * @return array
 */
function ncb_filter_denhaag_meta_buttons( string $post_type = 'post', int $post_id = 0 ) {
	return [
		'readspeaker' => apply_filters( 'ncb_denhaag_meta_show_readspeaker', true, $post_type ),
		'share'       => apply_filters( 'ncb_denhaag_meta_show_share', true, $post_type, $post_id ),
	];
}

add_filter( 'ncb_denhaag_meta_buttons', 'ncb_filter_denhaag_meta_buttons', 10, 2 );
