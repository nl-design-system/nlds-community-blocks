<?php
/**
 * Template for the Image Block.
 * Build the HTML for the Image Block.
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Image
 * @since      1.0.0
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content The Block content.
 * @global array $attributes The Block attributes.
 */
if ( empty( $attributes ) || empty( $attributes['image'] ) || ! is_numeric( $attributes['image'] ) ) {
	return;
}

$ncb_is_download  = $attributes['download'] ?? false;
$ncb_show_caption = $ncb_is_download || ! empty( $attributes['caption'] );

$ncb_anchor_attributes = [
	'class' => 'denhaag-image__figcaption-download',
];

$ncb_image_id = $attributes['image'];

$ncb_image_size = esc_attr( $attributes['size'] ?? 'large' );
$ncb_image      = wp_get_attachment_image( $ncb_image_id, $ncb_image_size, false, [ 'class' => 'denhaag-image__image denhaag-image__image--' . $ncb_image_size ] );

if ( $ncb_is_download && 'attachment' === get_post_type( $ncb_image_id ) ) {

	$ncb_anchor_attributes['href']   = esc_url( wp_get_attachment_image_url( $ncb_image_id, 'full' ) );
	$ncb_anchor_attributes['target'] = '_blank';

	// Get `post_mime_type`, set default to `image/jpeg`. Others are like `application/pdf`, `image/png`, etc.
	$ncb_attachment_mimetype  = get_post_mime_type( $ncb_image_id ) ?? 'image/jpeg';
	$ncb_attachment_extension = esc_attr( explode( '/', $ncb_attachment_mimetype )[1] );

	$ncb_image_title = get_the_title( $ncb_image_id ); // Image attribute from `wp_get_attachment_metadata()` is empty while filled in CMS.
	if ( ! empty( $ncb_image_title ) && false !== $ncb_attachment_mimetype ) {

		$ncb_anchor_attributes['download'] = esc_attr(
			implode(
				'.',
				[
					sanitize_title( $ncb_image_title ),
					$ncb_attachment_extension,
				]
			)
		);

		$ncb_anchor_attributes['type'] = $ncb_attachment_mimetype;

		$ncb_anchor_attributes['aria-label'] = ! empty( $ncb_image_title )
			/* translators: %1$s Image title, $2$s File extension, %3$s File size. */
				? sprintf( esc_attr_x( 'Download image: %1$s (%2$s, %3$s)', 'Image', 'nlds-community-blocks' ), esc_attr( $ncb_image_title ), esc_attr( strtoupper( $ncb_attachment_extension ) ), esc_attr( ncb_get_file_size( $ncb_image ) ) )
			/* translators: $1$s File extension, %2$s File size. */
				: sprintf( esc_attr_x( 'Download image: (%1$s, %2$s)', 'Image', 'nlds-community-blocks' ), esc_attr( strtoupper( $ncb_attachment_extension ) ), esc_attr( ncb_get_file_size( $ncb_image ) ) );

	}
}

?>
<figure class="denhaag-image">
	<?php
	if ( ! empty( $ncb_image ) ) :
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $ncb_image;
	endif;
	if ( $ncb_show_caption ) :
		?>
		<figcaption class="denhaag-image__figcaption">
			<?php
			if ( ! empty( $attributes['caption'] ) ) :
				printf( '<span class="denhaag-image__figcaption-text">%s</span>', wp_kses_post( $attributes['caption'] ) );
			endif;
			if ( $ncb_is_download ) :
				printf(
					'<a %1$s>%2$s<span class="denhaag-image__download-text">%3$ss</span></a>',
					ncb_to_dom_attributes( $ncb_anchor_attributes ),
					wp_kses_post(
						ncb_denhaag_icon(
							'ncb-denhaag-download-icon',
							[
								'class'  => 'denhaag-image__icon',
								'height' => 20,
								'width'  => 20,
							]
						)
					),
					esc_html_x( 'Download image', 'denhaag/image: Download label', 'nlds-community-blocks' )
				);
			endif;
			?>
		</figcaption>
		<hr class="denhaag-divider" role="presentation" />
	<?php endif; ?>
</figure>
