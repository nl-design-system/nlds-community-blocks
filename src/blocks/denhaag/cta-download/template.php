<?php
/**
 * Template for the CTA_Download Block.
 * Build the HTML for the CTA_Download Block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/CTA_Download
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content The Block content.
 * @global array $attributes The Block attributes.
 */

if ( empty( $attributes ) || empty( $attributes['file'] ) || ! is_numeric( $attributes['file'] ) ) {
	return;
}

$ncb_attachment_url = wp_get_attachment_url( $attributes['file'] );
if ( ! wp_http_validate_url( $ncb_attachment_url ) ) {
	// URL isn't reachable!
	return;
}

$ncb_attachment_info      = pathinfo( wp_get_attachment_url( $attributes['file'] ) );
$ncb_attachment_mimetype  = get_post_mime_type( $attributes['file'] );
$ncb_attachment_file_size = esc_attr( size_format( filesize( get_attached_file( $attributes['file'] ) ), 2 ) );
$ncb_file_name            = sanitize_title( $ncb_attachment_info['filename'] );

preg_match( '/<h\d class="denhaag-cta-download__title">(.*?)<\/h\d>/s', $content, $matches );
$ncb_label = ! empty( $matches ) ? $matches[1] : $ncb_attachment_info['filename'];

$ncb_anchor_attributes = [
	'class'      => 'denhaag-cta-download',
	'href'       => esc_url( $ncb_attachment_url ),
	'extension'  => $ncb_attachment_info['extension'],
	'type'       => $ncb_attachment_mimetype,
	/* translators: %1$s name, %2$s file type, %3$s file size. */
	'aria-label' => sprintf( esc_attr_x( 'Download: %1$s (%2$s, %3$s)', 'Download', 'nlds-community-blocks' ), esc_attr( $ncb_label ), esc_attr( strtoupper( $ncb_attachment_info['extension'] ) ), $ncb_attachment_file_size ),
	/* preventing the download, to open file in same window. */
	'target'     => '_blank',
	'download'   => esc_attr( $ncb_file_name ),
];

$ncb_icon_file_path = __DIR__ . '/assets/icons/download.svg';
$ncb_icon           = file_exists( $ncb_icon_file_path ) ? ncb_file_get_contents( $ncb_icon_file_path ) : null;

printf(
	'<a %1$s><div class="denhaag-cta-link__dot">%2$s</div><div class="denhaag-cta-download__excerpt">%3$s(%4$s, %5$s)</div></a>',
	ncb_to_dom_attributes( $ncb_anchor_attributes ),
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $ncb_icon is an SVG.
	$ncb_icon,
	wp_kses_post( $content ),
	esc_attr( strtoupper( $ncb_attachment_info['extension'] ) ),
	// phpcs:inline WordPress.Security.EscapeOutput.OutputNotEscaped -- $ncb_attachment_file_size is escaped.
	$ncb_attachment_file_size
);
