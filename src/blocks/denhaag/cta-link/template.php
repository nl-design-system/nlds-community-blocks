<?php
/**
 * Template for the CTA_Link Block.
 * Build the HTML for the CTA_Link Block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/CTA_Link
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content The Block content.
 * @global array $attributes The Block attributes.
 */

if ( empty( $attributes ) || empty( $attributes['link'] ) || empty( $content ) ) {
	return;
}

$ncb_link_attributes = wp_parse_args(
	[
		'class' => [
			'denhaag-cta-link',
			'denhaag-cta-link--' . $attributes['type'],
		],
	],
	ncb_link_attributes( $attributes['link'] )
);

$ncb_icon_file_path = __DIR__ . '/assets/icons/' . $attributes['type'] . '.svg';
$ncb_icon           = file_exists( $ncb_icon_file_path ) ? ncb_file_get_contents( $ncb_icon_file_path ) : null;
$ncb_external_link  = ncb_is_external_url( $attributes['link'] );

?>
<a <?php echo ncb_to_dom_attributes( $ncb_link_attributes ); ?> >
	<div class="denhaag-cta-link__dot">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $ncb_icon is an SVG.
		echo $ncb_icon;
		?>
	</div>
	<p class="denhaag-cta-link__excerpt">
		<?php

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $content is HTML.
		echo $content;

		if ( $ncb_external_link ) :
			printf( '<span class="sr-only">(%s)</span>', esc_attr_x( 'External link', 'A11y ScreenReader text', 'nlds-community-blocks' ) );
		endif;
		?>
	</p>
</a>
