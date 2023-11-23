<?php
/**
 * Template for the Button Block.
 * Build the HTML for the Button Block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Button
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content The Block content.
 * @global array $attributes The Block attributes.
 */
if ( empty( $attributes ) || empty( $attributes['link'] ) ) {
	return;
}

$ncb_link        = ncb_get_url( $attributes['link'] );
$ncb_is_external = ncb_is_external_url( $ncb_link );
$ncb_has_icon    = ! empty( $attributes['icon'] ) ? $attributes['icon'] : false;
$ncb_label       = $attributes['link']['title'];

$ncb_attributes = wp_parse_args(
	ncb_link_attributes( $attributes['link'] ),
	[
		'class' => [ 'denhaag-button' ],
	]
);

$ncb_icon_id = 'ncb-denhaag-arrow-right-icon';

if ( ! ncb_validate_url( $ncb_link ) ) {
	$ncb_attributes['class'][] = 'denhaag-button--disabled';
}

if ( $ncb_is_external ) {
	$ncb_attributes['class'][] = 'denhaag-button--external';

	$ncb_icon_id = 'ncb-denhaag-external-icon';
	$ncb_label  .= sprintf(
		'<span class="denhaag-button__sr-only">%s</span>',
		esc_attr_x( '(External link)', 'Screenreader label', 'nlds-community-blocks' )
	);
}

if ( $ncb_has_icon && ! $attributes['iconBefore'] && ! empty( $ncb_label ) ) {
	$ncb_attributes['class'][] = 'denhaag-button--end-icon';
} elseif ( $ncb_has_icon && $attributes['iconBefore'] && ! empty( $ncb_label ) ) {
	$ncb_attributes['class'][] = 'denhaag-button--start-icon';
}

if ( ! empty( $attributes['size'] ) && 'default' !== $attributes['size'] ) {
	$ncb_attributes['class'][] = 'denhaag-button--' . esc_attr( $attributes['size'] );
}

if ( ! empty( $attributes['variant'] ) && 'primary' !== $attributes['variant'] ) {
	$ncb_attributes['class'][] = 'denhaag-button--' . esc_attr( $attributes['variant'] ) . '-action';
}

if ( empty( $ncb_label ) ) {
	$ncb_attributes['class'][] = 'denhaag-button--icon-only';
}

$ncb_icon = $ncb_has_icon
	? sprintf(
		'<span class="denhaag-button__icon">%s</span>',
		wp_kses_post( ncb_denhaag_icon( $ncb_icon_id ) )
	) : null;

if ( ! empty( $ncb_label ) && ! $ncb_has_icon ) {
	printf(
		'<a %1$s>%2$s</a>',
		ncb_to_dom_attributes( $ncb_attributes ),
		wp_kses_post( $ncb_label )
	);
} elseif ( ! empty( $ncb_label ) && $ncb_has_icon && ! $attributes['iconBefore'] ) {
	printf(
		'<a %1$s>%2$s</a>',
		ncb_to_dom_attributes( $ncb_attributes ),
		wp_kses_post( $ncb_label . $ncb_icon )
	);
} elseif ( ! empty( $ncb_label ) && $ncb_has_icon && $attributes['iconBefore'] ) {
	printf(
		'<a %1$s>%2$s</a>',
		ncb_to_dom_attributes( $ncb_attributes ),
		wp_kses_post( $ncb_icon . $ncb_label )
	);
} else {
	printf(
		'<a %1$s>%2$s</a>',
		ncb_to_dom_attributes( $ncb_attributes ),
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$ncb_icon
	);
}
