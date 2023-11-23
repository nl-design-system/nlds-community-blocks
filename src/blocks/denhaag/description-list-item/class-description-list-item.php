<?php
/**
 * Register the DescriptionListItem Block.
 *
 * Registers the DescriptionListItem Block for usage in the Gutenberg editor.
 *
 * @since      1.0.0
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/DescriptionListItem
 */

namespace Nlds_Community_Blocks\Src\Blocks\Denhaag;

use Nlds_Community_Blocks\Includes\Base_Block;

/**
 * The DescriptionListItem class.
 *
 * This is used to register and render the block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/DescriptionListItem
 * @author     Paul van Impelen <paul@acato.nl>, Richard Korthuis <richard@acato.nl>
 */
class Description_List_Item extends Base_Block {
	/**
	 * Render the blocks HTML.
	 *
	 * @param array  $attributes An array of block attributes.
	 * @param string $content    The content for the block.
	 *
	 * @return string The HTML for the block.
	 */
	public function render_block( $attributes, $content ) {

		if ( ! empty( $content ) ) {
			// Filter for links within the content.
			$content = ncb_filter_denhaag_content_links( $content );
		}

		ob_start();
		include __DIR__ . '/template.php';
		$output = ob_get_clean();

		return $output;
	}
}

new Description_List_Item();
