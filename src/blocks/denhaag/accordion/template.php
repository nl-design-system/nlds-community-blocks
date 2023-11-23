<?php
/**
 * Template for the Accordion Block.
 * Build the HTML for the Accordion Block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Accordion
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

printf(
	'<div class="denhaag-accordion">%1$s</div>',
	wp_kses_post( $content )
);
