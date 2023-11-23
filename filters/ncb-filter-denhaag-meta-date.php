<?php
/**
 * Defines filter function for the denhaag/meta block.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Filters
 */

/**
 * Returns string or array of date time partial.
 *
 * @param int         $unixtimestamp The unix timestamp.
 * @param bool|string $icon the icon to show.
 *
 * @return string|array
 */
function ncb_filter_denhaag_meta_date( int $unixtimestamp = 0, $icon = 'calendar' ) {

	if ( ! $icon ) {
		return sprintf(
			'<time class="denhaag-meta__time" datetime="%s">%s</time>',
			esc_attr( gmdate( 'Y-m-d\TH:i:s\Z', $unixtimestamp ) ),
			esc_attr( sprintf( '%s %s', date_i18n( 'l', $unixtimestamp ), date_i18n( get_option( 'date_format' ), $unixtimestamp ) ) )
		);
	}

	return [
		ncb_denhaag_icon(
			$icon,
			[
				'width'  => '20',
				'height' => '20',
			]
		),
		sprintf(
			'<time class="denhaag-meta__time" datetime="%s">%s</time>',
			esc_attr( gmdate( 'Y-m-d\TH:i:s\Z', $unixtimestamp ) ),
			esc_attr( sprintf( '%s %s', date_i18n( 'l', $unixtimestamp ), date_i18n( get_option( 'date_format' ), $unixtimestamp ) ) )
		),
	];
}

add_filter( 'ncb_denhaag_meta_date', 'ncb_filter_denhaag_meta_date', 10, 2 );
