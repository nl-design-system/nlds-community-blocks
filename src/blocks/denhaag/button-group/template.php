<?php
/**
 * Template for the Button_Group Block.
 * Build the HTML for the Button_Group Block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Button_Group
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content The Block content.
 * @global array $attributes The Block attributes.
 */
if ( empty( $content ) ) {
	return;
}

$ncb_attributes = [
	'class' => [ 'denhaag-button-group' ],
];

if ( ! empty( $attributes['amount'] ) ) {
	$ncb_attributes['class'][] = 'denhaag-button-group--' . esc_attr( absint( $attributes['amount'] ) > 1 ? 'multiple' : 'single' );
}

printf(
	'<div %s>%s</div>',
	ncb_to_dom_attributes( $ncb_attributes ),
	wp_kses_post( $content )
);
