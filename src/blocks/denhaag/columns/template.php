<?php
/**
 * Template for the Columns Block.
 * Build the HTML for the Columns Block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Columns
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content    The Block content.
 * @global array  $attributes The Block attributes.
 */
if ( empty( $content ) ) {
	return;
}

$ncb_layout = ! empty( $attributes['layout'] ) ? esc_attr( $attributes['layout'] ) : 'fifty-fifty';

$ncb_attributes = [
	'class' => [ 'denhaag-columns', "denhaag-columns--{$ncb_layout}" ],
];

printf(
	'<section %1$s>%2$s</section>',
	ncb_to_dom_attributes( $ncb_attributes ),
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	trim( $content )
);
