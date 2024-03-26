<?php
/**
 * Register the Embed_YouTube Block.
 *
 * Registers the Embed_YouTube Block for usage in the Gutenberg editor.
 *
 * @since      1.0.0
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Embed_YouTube
 */

namespace Nlds_Community_Blocks\Src\Blocks\Denhaag;

use Nlds_Community_Blocks\Includes\Base_Block;

/**
 * The Embed_YouTube class.
 *
 * This is used to register and render the block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Embed_YouTube
 * @author     Paul van Impelen <paul@acato.nl>, Richard Korthuis <richard@acato.nl>
 */
class Embed_YouTube extends Base_Block {
	/**
	 * Render the blocks HTML.
	 *
	 * @param array  $attributes An array of block attributes.
	 * @param string $content    The content for the block.
	 *
	 * @return string The HTML for the block.
	 */
	public function render_block( $attributes, $content ) {

		if ( ! empty( $attributes['id'] ) && wp_http_validate_url( $attributes['id'] ) ) {
			$attributes['id'] = self::get_youtube_id( $attributes['id'] );
		}

		ob_start();
		include __DIR__ . '/template.php';
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Returns video ID by provided input.
	 *
	 * @param string $input URL or video id.
	 *
	 * @return string
	 */
	private static function get_youtube_id( $input ): string {
		/**
		 * Get Video ID by URL input.
		 *
		 * Matches:
		 * youtube.com/v/[VIDEO_ID]
		 * youtube.com/vi/[VIDEO_ID]
		 * youtube.com/?v=[VIDEO_ID]
		 * youtube.com/?vi=[VIDEO_ID]
		 * youtube.com/watch?v=[VIDEO_ID]
		 * youtube.com/watch?vi=[VIDEO_ID]
		 * youtu.be/[VIDEO_ID]
		 * youtube.com/embed/[VIDEO_ID]
		 * http://youtube.com/v/[VIDEO_ID]
		 * http://www.youtube.com/v/[VIDEO_ID]
		 * https://www.youtube.com/v/[VIDEO_ID]
		 * youtube.com/watch?v=[VIDEO_ID]&wtv=wtv
		 * http://www.youtube.com/watch?dev=inprogress&v=[VIDEO_ID]&feature=related
		 * https://m.youtube.com/watch?v=[VIDEO_ID]
		 * youtube.com/shorts/[VIDEO_ID]
		 *
		 * Does not match:
		 * www.facebook.com?wtv=youtube.com/v/[VIDEO_ID]
		 */

		preg_match( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/", $input, $matches );
		return end( $matches );
	}
}

new Embed_YouTube();
