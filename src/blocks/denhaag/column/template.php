<?php
/**
 * Template for the Column Block.
 * Build the HTML for the Column Block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Column
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
	'<div class="denhaag-column">%1$s</div>',
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	trim( $content )
);
