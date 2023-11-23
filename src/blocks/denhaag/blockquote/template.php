<?php
/**
 * Template for the Blockquote Block.
 * Build the HTML for the Blockquote Block.
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Blockquote
 * @since      1.0.0
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

echo wp_kses_post( $content );
