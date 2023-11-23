<?php
/**
 * Template for the Note Block.
 * Build the HTML for the Note Block.
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Note
 * @since      1.0.0
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content The Block content.
 * @global array $attributes The Block attributes.
 */
if ( empty( $attributes['text'] ) ) {
	return;
}

$ncb_variant = ! empty( $attributes['variant'] ) ? esc_attr( $attributes['variant'] ) : 'info';

$ncb_note_attributes = [
	'class' => [
		'denhaag-note',
		"denhaag-note--$ncb_variant",
	],
	'role'  => 'note',
];

$ncb_svg_attributes = [
	'aria-label' => 'warning' === $ncb_variant ? esc_attr_x( 'Pay attention!', 'denhaag/note', 'nlds-community-blocks' ) : esc_attr_x( 'Important information:', 'denhaag/note', 'nlds-community-blocks' ),
	'class'      => 'denhaag-note__icon',
];

printf(
	'<div %1$s>%2$s<div>%3$s</div></div>',
	ncb_to_dom_attributes( $ncb_note_attributes ),
	wp_kses_post( ncb_denhaag_icon( 'ncb-denhaag-' . esc_attr( $ncb_variant ) . '-icon', $ncb_svg_attributes ) ),
	wp_kses_post( $attributes['text'] )
);
