<?php
/**
 * Template for the Social_Links_Item Block.
 *
 * Build the HTML for the Social_Links_Item Block.
 *
 * @since      1.0.0
 *
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Social_Links_Item
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

$ncb_url        = ncb_get_url( $attributes['link'] );
$ncb_parsed_url = wp_parse_url( $ncb_url );

if ( empty( $ncb_parsed_url['host'] ) ) {
	// Something is wrong with the url.
	return;
}

$ncb_exploded_host = explode( '.', $ncb_parsed_url['host'] );
$ncb_social        = $ncb_exploded_host[ count( $ncb_exploded_host ) - 2 ];

if ( 'wa' === $ncb_social ) {
	$ncb_social = 'whatsapp';
}

$ncb_link_attributes = wp_parse_args(
	ncb_link_attributes(
		wp_parse_args(
				// External URLs which must be opened in new tab. Therefor we provide attributes same as the `<LinkControl>`.
			[
				'opensInNewTab' => true,
				'noFollow'      => true,
			],
			$attributes['link']
		)
	),
	[
		'class'      => [ 'denhaag-socials__link' ],
		'aria-label' => sprintf(
			'%s %s',
			/* translators: %s The Socialmedia platform name from the we generated from the URL input. */
				sprintf( _x( 'Follow us on %s', 'denhaag/social-link: aria label', 'nlds-community-blocks' ), esc_attr( ucfirst( $ncb_social ) ) ),
			esc_attr_x( '(External link)', 'Screenreader label', 'nlds-community-blocks' )
		),
	]
);

?>
<a <?php echo ncb_to_dom_attributes( $ncb_link_attributes ); ?>>
	<?php
	echo wp_kses_post( ncb_denhaag_icon( 'ncb-denhaag-' . $ncb_social . '-icon' ) );
	?>
</a>
