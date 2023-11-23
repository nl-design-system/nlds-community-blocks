<?php
/**
 * Defines filter function for the denhaag/meta block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Filters
 */

/**
 * Return array of post types we want to allow to show the meta.
 *
 * @param array $post_types Post types we want to allow.
 *
 * @return array|string[]
 */
function ncb_filter_denhaag_meta_show_meta( array $post_types = [ 'post' ] ) {
	return $post_types;
}

add_filter( 'ncb_denhaag_meta_allow_post_types_date', 'ncb_filter_denhaag_meta_show_meta', 10, 1 );
add_filter( 'ncb_denhaag_meta_allow_post_types_post_type', 'ncb_filter_denhaag_meta_show_meta', 10, 1 );
