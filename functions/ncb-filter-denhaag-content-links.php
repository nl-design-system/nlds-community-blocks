<?php
/**
 * Defines helper functions to check if is valid url.
 *
 * @since      1.0.0
 * @package    Nlds_Community_Blocks
 * @subpackage Nlds_Community_Blocks/Helpers
 */

/**
 * Modify all links from `<a>` to NLDS(DH) allowed links HTML format.
 *
 * @package              nlds-community-blocks
 * @subpackage           helpers
 * @since                1.0.0
 * @author               Paul van Impelen <paul@acato.nl>
 * @link                 https://acato.nl
 * @category             Helpers
 */
if ( ! function_exists( 'ncb_filter_denhaag_content_links' ) ) {
	/**
	 * Modify all links from `<a>` to NLDS(DH) allowed links HTML format.
	 *
	 * @source https://nl-design-system.github.io/denhaag/?path=/docs/css-navigation-link--link
	 * @param string  $input HTML input to filter in.
	 * @param boolean $with_meta Default true, will add/remove the <meta> tag.
	 *
	 * @return false|mixed|string
	 */
	function ncb_filter_denhaag_content_links( $input = '', $with_meta = true ) {

		if ( empty( $input ) ) {
			return $input;
		}

		// Instantiate the DOMDocument class.
		$ncb_html_dom = new \DOMDocument();

		// Disable libxml errors.
		libxml_use_internal_errors( true );

		if ( $with_meta ) {
			// Parse the HTML of the page using `DOMDocument::loadHTML`.
			$ncb_html_dom->loadHTML( "<html><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /><body>$input</body></html>", LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED );
		} else {
			// Parse the HTML of the page using `DOMDocument::loadHTML` without the `<meta>` tag.
			// MUST PROVIDE VALID HTML.
			$ncb_html_dom->loadHTML( "<html><body>$input</body></html>", LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED );
		}

		// Re-enable libxml errors.
		libxml_use_internal_errors( false );

		// Extract the links from the HTML.
		$ncb_links = $ncb_html_dom->getElementsByTagName( 'a' );

		// Return the input.
		if ( 0 === count( $ncb_links ) ) {
			return $input;
		}

		// Loop through the DOMNodeList.
		// We can do this because the DOMNodeList object is traversable.
		foreach ( $ncb_links as $ncb_link ) {
			$ncb_attributes = [
				'class' => [ 'denhaag-link' ],
			];

			// Get the link in the href attribute.
			$ncb_href = $ncb_link->getAttribute( 'href' );

			// Get the textContent and set in value.
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$ncb_content = $ncb_link->textContent;

			// Remove textContent.
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$ncb_link->textContent = '';

			// Wrap textContent.
			$ncb_link_label = $ncb_html_dom->createElement( 'span' );
			$ncb_link_label->setAttribute( 'class', 'denhaag-link__label' );
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$ncb_link_label->textContent = $ncb_content;

			$ncb_link_external_label = null;
			$ncb_link_external_icon  = null;

			if ( ncb_validate_url( $ncb_href ) ) {
				if ( ncb_is_external_url( $ncb_href ) ) {
					$ncb_attributes['class'][] = 'denhaag-link--with-icon';
					$ncb_attributes['class'][] = 'denhaag-link--with-icon-end';

					$ncb_link_external_label = $ncb_html_dom->createElement( 'span' );
					$ncb_link_external_label->setAttribute( 'class', 'denhaag-link__sr-only' );
					// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					$ncb_link_external_label->textContent = _x( '(External link)', 'Screenreader label', 'nlds-community-blocks' );

					$ncb_link_external_icon = $ncb_html_dom->createElement( 'span' );
					$ncb_link_external_icon->setAttribute( 'class', 'denhaag-link__icon' );
					$ncb_link_external_svg = $ncb_html_dom->createElementNS( 'http://www.w3.org/2000/svg', 'svg' );
					$ncb_link_external_use = $ncb_html_dom->createElement( 'use' );

					$ncb_svg_attributes = [
						'width'           => '1em',
						'height'          => '1em',
						'viewBox'         => '0 0 24 24',
						'fill'            => 'none',
						'xmlns'           => 'http://www.w3.org/2000/svg',
						'class'           => 'denhaag-icon',
						'focusable'       => 'false',
						'aria-hidden'     => 'true',
						'shape-rendering' => 'auto',
					];

					$ncb_svg_path_attributes = [
						'xlink:href' => '#ncb-denhaag-external-icon',
					];

					foreach ( $ncb_svg_attributes as $ncb_svg_attribute => $ncb_svg_attribute_value ) {
						$ncb_link_external_svg->setAttribute( $ncb_svg_attribute, $ncb_svg_attribute_value );
					}

					foreach ( $ncb_svg_path_attributes as $ncb_svg_path_attribute => $ncb_svg_path_attribute_value ) {
						$ncb_link_external_use->setAttribute( $ncb_svg_path_attribute, $ncb_svg_path_attribute_value );
					}

					$ncb_link_external_svg->appendChild( $ncb_link_external_use );
					$ncb_link_external_icon->appendChild( $ncb_link_external_svg );
				}
			} else {
				// Invalid URL's will show up here.
				$ncb_attributes['class'][]  = 'denhaag-link--disabled';
				$ncb_attributes['tabindex'] = - 1;
			}

			// Loop through the attributes and append them to.
			foreach ( $ncb_attributes as $ncb_attribute => $ncb_attribute_value ) {
				if ( is_array( $ncb_attribute_value ) ) {
					$ncb_link->setAttribute( esc_attr( $ncb_attribute ), esc_attr( implode( ' ', array_unique( $ncb_attribute_value ) ) ) );
					continue;
				}

				$ncb_link->setAttribute( esc_attr( $ncb_attribute ), esc_attr( $ncb_attribute_value ) );
			}

			/*
			 * Order of innerContent:
			 * 1. Icon (if exists);
			 * 2. Label;
			 * 3. External link label;
			 */
			if ( $ncb_link_external_icon ) {
				$ncb_link->appendChild( $ncb_link_external_icon );
			}
			$ncb_link->appendChild( $ncb_link_label );
			if ( $ncb_link_external_label ) {
				$ncb_link->appendChild( $ncb_link_external_label );
			}
		}

		return trim($ncb_html_dom->saveHTML());
	}
}
