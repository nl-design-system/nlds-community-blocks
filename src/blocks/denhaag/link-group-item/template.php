<?php
/**
 * Template for the Link_Group_Item Block.
 * Build the HTML for the Link_Group_Item Block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Link_Group_Item
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content    The Block content.
 * @global array  $attributes The Block attributes.
 */
if ( empty( $attributes ) || empty( $attributes['link'] ) ) {
	return;
}

$ncb_link        = ncb_get_url( $attributes['link'] );
$ncb_is_external = ncb_is_external_url( $ncb_link );
$ncb_title       = ! empty( $attributes['link']['title'] ) ? $attributes['link']['title'] : null;

$ncb_link_attributes = wp_parse_args(
	ncb_link_attributes( $attributes['link'] ),
	[
		'class' => [
			'denhaag-link',
			'denhaag-link--with-icon',
			'denhaag-link--with-icon-start',
		],
	]
);

if ( $ncb_is_external ) {
	$ncb_link_attributes['class'][] = 'denhaag-link--external';
}

?>
<li class="denhaag-link-group__list-item">
	<a <?php echo ncb_to_dom_attributes( $ncb_link_attributes ); ?>>
		<?php

		printf(
			'<span class="denhaag-link__icon">%s</span>',
			wp_kses_post( ncb_denhaag_icon( $ncb_is_external ? 'ncb-denhaag-external-icon' : 'ncb-denhaag-arrow-right-icon' ) )
		);

		printf( '<span>%s</span>', wp_kses_post( $ncb_title ) );

		if ( $ncb_is_external ) :
			printf(
				'<span class="denhaag-link__sr-only">%s</span>',
				esc_attr_x( '(External link)', 'Screenreader label', 'nlds-community-blocks' )
			);
		endif;

		?>
	</a>
</li>
