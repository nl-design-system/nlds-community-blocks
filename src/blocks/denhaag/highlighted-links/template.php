<?php
/**
 * Template for the Highlighted_Links Block.
 * Build the HTML for the Highlighted_Links Block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Highlighted_Links
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

?>
<div class="denhaag-link-group denhaag-highlighted-links">
	<?php
	if ( ! empty( $attributes['caption'] ) ) :
		$ncb_tag_show_as = ! empty( $attributes['tagShownAs'] ) ? $attributes['tagShownAs'] : 4;

		printf(
			'  <h%1$d class="%3$s">%2$s</h%1$d>',
			esc_attr( ! empty( $attributes['tag'] ) ? $attributes['tag'] : 4 ),
			wp_kses_post( $attributes['caption'] ),
			esc_attr( "utrecht-heading-$ncb_tag_show_as denhaag-link-group__caption" )
		);
	endif;

	printf(
		'<ul class="utrecht-link-list utrecht-link-list--html-ul denhaag-link-group__list denhaag-highlighted-links__list">%s</ul>',
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		trim( $content )
	);
	?>
</div>
