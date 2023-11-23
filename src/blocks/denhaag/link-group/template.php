<?php
/**
 * Template for the Link_Group Block.
 * Build the HTML for the Link_Group Block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Link_Group
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

$ncb_attributes = [
	'class' => [ 'denhaag-link-group' ],
];

if ( ! empty( $attributes['isDark'] ) ) {
	$ncb_attributes['class'][] = 'denhaag-link-group--dark';
}

?>
<div <?php echo ncb_to_dom_attributes( $ncb_attributes ); ?>>
	<?php
	if ( ! empty( $attributes['image'] ) && is_numeric( $attributes['image'] ) ) :
		echo wp_get_attachment_image(
			$attributes['image'],
			[
				140,
				140,
			],
			false,
			[ 'class' => 'denhaag-link-group__image' ]
		);
	endif;
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
		'<ul class="utrecht-link-list utrecht-link-list--html-ul denhaag-link-group__list">%s</ul>',
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		trim( $content )
	);
	?>
</div>
