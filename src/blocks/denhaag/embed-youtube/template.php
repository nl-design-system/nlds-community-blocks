<?php
/**
 * Template for the Embed_YouTube Block.
 * Build the HTML for the Embed_YouTube Block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Src/Blocks/Denhaag/Embed_YouTube
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content    The Block content.
 * @global array  $attributes The Block attributes.
 */

$ncb_video_id = $attributes['id'];

if ( empty( $attributes['id'] ) ) {
	return;
}

$ncb_language = explode( '_', get_locale() )[0];

$ncb_url_attributes = [
	'autoplay'       => absint( $attributes['autoplay'] ),
	'controls'       => absint( $attributes['controls'] ),
	'loop'           => absint( $attributes['loop'] ),
	'mute'           => absint( $attributes['mute'] ),
	'portrait'       => absint( $attributes['portrait'] ),

	// Default parameters we don't want to be overwritten.
	'disablekb'      => 1, // Disabled to prevent keyboard navigation for accessibility.
	'modestbranding' => 1,
	'showinfo'       => 0,

	'hl'             => $ncb_language,
	'cc_lang_pref'   => $ncb_language,
];

$ncb_attributes = [
	'class'           => [ 'denhaag-embed-youtube' ],
	'loading'         => 'lazy',
	'src'             => "https://www.youtube-nocookie.com/embed/$ncb_video_id?" . http_build_query( $ncb_url_attributes ),
	'title'           => ! empty( $attributes['description'] )
		? sprintf( 'YouTube video: %s', esc_attr( $attributes['description'] ) )
		: esc_attr_x( 'YouTube Video', 'denhaag/embed-youtube title', 'nlds-community-blocks' ),
	'allowfullscreen' => null,
];

if ( ! empty( $attributes['portrait'] ) ) {
	$ncb_attributes['class'][] = 'denhaag-embed-youtube--portrait';
}

printf( '<iframe %s></iframe>', ncb_to_dom_attributes( $ncb_attributes ) );
