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
 * @param array $blocks    The allowed innerBlocks.
 *
 * @return array
 */
function ncb_filter_denhaag_meta_accordion_allowed_blocks( array $blocks = [] ):array {
	$ncb_blocks = [
		'ncb-denhaag/authentication',
		'ncb-denhaag/button-group',
		'ncb-denhaag/blockquote',
		'ncb-denhaag/cta-calendar-event',
		'ncb-denhaag/cta-download',
		'ncb-denhaag/cta-link',
		'ncb-denhaag/description-list',
		'ncb-denhaag/heading',
		'ncb-denhaag/image',
		'ncb-denhaag/lists',
		'ncb-denhaag/note',
		'ncb-denhaag/paragraph',
		'ncb-denhaag/table',

		// Core components we allow.
		'core/list',
	];

	return array_unique( array_merge( $blocks, $ncb_blocks ) );
}

add_filter( 'ncb_denhaag_meta_accordion_allowed_blocks', 'ncb_filter_denhaag_meta_accordion_allowed_blocks', 10, 1 );
