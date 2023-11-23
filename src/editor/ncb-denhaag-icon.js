import {sprintf} from "@wordpress/i18n";
import ncb_to_dom_attributes from './ncb-to-dom-attributes';

/**
 * Helper for in Denhaag Blocks to reduce duplication.
 *
 * @param {string} id The ID from the SVG sprite.
 * @param {array}  attributes Attributes to overwrite the default.
 *
 * @return string
 */
const ncb_denhaag_icon = (id = 'ncb-denhaag-external-icon', attributes = []) => {

  id = sanitize_title( id );

  // Remove the # if applied.
  if (!!id.startsWith( '#' )) {
    id = id.substring( 1 );
  }

  // Prepend with `ncb-denhaag-` for the correct format.
  if (!id.startsWith( 'ncb-denhaag-' )) {
    id = `ncb-denhaag-${id}`;
  }

  // Append with `-icon` for the correct format.
  if (!id.endsWith( '-icon' )) {
    id = `${id}-icon`;
  }

  const mergedAttr = {
    ...{
      'aria-hidden': 'true',
      'class': 'denhaag-icon',
      'width': '1em',
      'height': '1em',
      'viewBox': '0 0 24 24',
      'fill': 'none',
      'xmlns': 'http://www.w3.org/2000/svg',
      'focusable': 'false',
      'shape-rendering': 'auto',
      'role': 'img',
    }, ...attributes
  }

  return sprintf(
    '<svg %s><use href="#%s" /></svg>',
    ncb_to_dom_attributes( mergedAttr ),
    id
  );
};

/**
 * JS copy of sanitize_title() function of wordpress.
 * @param string
 * @return {string}
 */
const sanitize_title = (string) => {
  return string
    .toLowerCase()
    .replace( /\s+/g, '-' ) // Replace spaces with -
    .replace( /[^\w\-]+/g, '' ) // Remove all non-word chars
    .replace( /\-\-+/g, '-' ) // Replace multiple - with single -
    .replace( /^-+/, '' ) // Trim - from start of text
    .replace( /-+$/, '' ); // Trim - from end of text
};


export default ncb_denhaag_icon;
