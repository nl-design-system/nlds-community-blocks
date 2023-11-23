<?php
/**
 * Defines filter function for extending the editor variables.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Filters
 */

/**
 * Returns editor variables foreach block that requires window variables extend by `municipality` and the block-name.
 *
 * @param array $blocks  Array of meta data per municipality and block-name.
 * @param int   $post_id The post ID.
 *
 * @return array
 */
function ncb_editor_variables( $blocks = [], $post_id = 0 ) {

	$ncb_post_type = get_post_type( $post_id );

	// Show specific editor variables for `denhaag/accordion-items` block.
	$blocks['denhaag']['accordion-items'] = [
		'allowed_blocks' => apply_filters( 'ncb_denhaag_meta_accordion_allowed_blocks', [], $ncb_post_type, $post_id ),
	];

	// Show specific editor variables for `denhaag/featured-image` block.
	$blocks['denhaag']['featured-image'] = [
		'supported' => post_type_supports( $ncb_post_type, 'thumbnail' ),
	];

	// Show specific editor variables for `denhaag/meta` block.
	$blocks['denhaag']['meta'] = [
		'buttons'   => apply_filters( 'ncb_denhaag_meta_buttons', $ncb_post_type, $post_id ),
		'metadata'  => apply_filters( 'ncb_denhaag_meta', [], $ncb_post_type, $post_id ),
		'supported' => [
			'title'   => post_type_supports( $ncb_post_type, 'title' ),
			'excerpt' => post_type_supports( $ncb_post_type, 'excerpt' ),
		],
	];

	$blocks['denhaag']['link-group'] = [
		'is_editor' => 'post' === get_current_screen()->base,
	];

	return $blocks;
}

add_filter( 'ncb_editor_variables', 'ncb_editor_variables', 15, 2 );
