<?php
/**
 * Template for the Social_Links Block.
 *
 * Build the HTML for the Social_Links Block.
 *
 * @since      1.0.0
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Social_Links
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content The Block content.
 */

if ( empty( $content ) ) {
	return;
}

printf(
	'<nav class="denhaag-socials">%s</nav>',
	wp_kses_post( $content )
);
