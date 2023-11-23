<?php
/**
 * Register the Columns Block.
 *
 * Registers the Columns Block for usage in the Gutenberg editor.
 *
 * @since      1.0.0
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Columns
 */

namespace Nlds_Community_Blocks\Src\Blocks\Denhaag;

use Nlds_Community_Blocks\Includes\Base_Block;

/**
 * The Columns class.
 *
 * This is used to register and render the block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Columns
 * @author     Paul van Impelen <paul@acato.nl>, Richard Korthuis <richard@acato.nl>
 */
class Columns extends Base_Block {
	/**
	 * Render the blocks HTML.
	 *
	 * @param array  $attributes An array of block attributes.
	 * @param string $content    The content for the block.
	 *
	 * @return string The HTML for the block.
	 */
	public function render_block( $attributes, $content ) {
		ob_start();
		include __DIR__ . '/template.php';
		return str_replace( [ "\n", "\t", "\r" ], '', trim( ob_get_clean() ) );
	}
}

new Columns();
