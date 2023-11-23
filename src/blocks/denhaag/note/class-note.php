<?php
/**
 * Register the Note Block.
 *
 * Registers the Note Block for usage in the Gutenberg editor.
 *
 * @since      1.0.0
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Note
 */

namespace Nlds_Community_Blocks\Src\Blocks\Denhaag;

use Nlds_Community_Blocks\Includes\Base_Block;

/**
 * The Note class.
 *
 * This is used to register and render the block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Note
 * @author     Paul van Impelen <paul@acato.nl>, Richard Korthuis <richard@acato.nl>
 */
class Note extends Base_Block {
	/**
	 * Render the blocks HTML.
	 *
	 * @param array  $attributes An array of block attributes.
	 * @param string $content    The content for the block.
	 *
	 * @return string The HTML for the block.
	 */
	public function render_block( $attributes, $content ) {

		if ( ! empty( $attributes['text'] ) ) {
			// phpcs:ignore Generic.Commenting.Todo.TaskFound
			// @todo: the `ncb_filter_denhaag_content_links()` must be applied to the REST endpoint.

			// Filter for links within the content.
			$attributes['text'] = ncb_filter_denhaag_content_links( $attributes['text'] );
		}

		ob_start();
		include __DIR__ . '/template.php';
		$output = ob_get_clean();

		return $output;
	}
}

new Note();
