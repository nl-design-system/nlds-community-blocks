<?php
/**
 * Template for the Accordion_Item Block.
 *
 * Build the HTML for the Accordion_Item Block.
 *
 * @since      1.0.0
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Accordion_Item
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content    The Block content.
 * @global array  $attributes The Block attributes.
 */
if ( empty( $content ) || empty( $attributes['title'] ) ) {
	return;
}

$ncb_id = sanitize_title( 'accordion-' . $attributes['title'] );

/* Translators: %1$s html element tag, %2$s Provided title, %3$s ID of the accordion based on the title, %4$s ID of the controllers based on the accordion-ID, %5$s Icon (HTML), %6$s Provided content. */
printf(
	'<div class="denhaag-accordion__container"><h%1$d class="denhaag-accordion__panel"><button aria-controls="%3$s" aria-expanded="false" class="denhaag-accordion__title" id="%4$s">%2$s</button>%5$s</h%1$d><div aria-labelledby="%3$s" class="denhaag-accordion__details" id="%4$s" role="region"><div class="denhaag-accordion__details-content">%6$s</div></div></div>',
	esc_attr( ! empty( $attributes['heading'] ) ? $attributes['heading'] : 3 ),
	esc_html( wp_strip_all_tags( $attributes['title'] ) ),
	esc_attr( $ncb_id ),
	esc_attr( $ncb_id . '-controls' ),
	wp_kses_post( ncb_denhaag_icon( 'ncb-denhaag-chevron-down-icon' ) ),
	wp_kses_post( trim( $content ) )
);
