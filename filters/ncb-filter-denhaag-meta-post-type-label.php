<?php
/**
 * Defines filter function for the denhaag/meta block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Filters
 */

/**
 * Return a label from a post type.
 *
 * @param string $post_type The post type to receive the labels from.
 * @param string $label The label to show.
 *
 * @return null
 */
function ncb_filter_denhaag_meta_post_type_label( string $post_type = 'post', string $label = 'singular_name' ) {
	if ( ! post_type_exists( $post_type ) ) {
		return null;
	}

	$post_type_object = get_post_type_labels( get_post_type_object( $post_type ) );

	return $post_type_object->{$label};
}

add_filter( 'ncb_denhaag_meta_post_type_label', 'ncb_filter_denhaag_meta_post_type_label', 10, 2 );
