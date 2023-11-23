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
 * @param array  $array Array of meta data.
 * @param string $post_type The post type.
 * @param int    $post_id The post ID.
 *
 * @return array
 */
function ncb_filter_denhaag_meta( array $array = [], string $post_type = 'post', int $post_id = 0 ) {

	if ( in_array( $post_type, apply_filters( 'ncb_denhaag_meta_allow_post_types_post_type', [ 'post' ] ), true ) ) {
		$array['post_type'] = apply_filters( 'ncb_denhaag_meta_post_type_label', $post_type, 'singular_name' );
	}

	if ( in_array( $post_type, apply_filters( 'ncb_denhaag_meta_allow_post_types_date', [ 'post' ] ), true ) ) {
		$array['date'] = apply_filters( 'ncb_denhaag_meta_date', get_the_date( 'U', $post_id ) );
	}

	return ! empty( $array ) ? array_filter( $array ) : [];
}

add_filter( 'ncb_denhaag_meta', 'ncb_filter_denhaag_meta', 10, 3 );








