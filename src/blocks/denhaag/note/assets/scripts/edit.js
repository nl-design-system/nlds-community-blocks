import { _x } from '@wordpress/i18n';
import { useLayoutEffect, useMemo } from '@wordpress/element';
import { BlockControls, RichText } from '@wordpress/block-editor';
import { ReactComponent as InfoIcon } from '../icons/info.svg';
import { ReactComponent as WarningIcon } from '../icons/warning.svg';
import classNames from 'classnames';
import NCB_NoteVariantControl from "./controls/ncb-note-variant-control";

export default function Edit( { attributes, setAttributes } ) {
	/**
	 * Returns object of classNames based on attributes.
	 * @type {{root: string}}
	 * @private
	 */
	const _CLASSES = useMemo( () => {
		return {
			root: classNames( 'denhaag-note', {
				[ `denhaag-note--${ attributes.variant }` ]: attributes.variant,
			} ),
		};
	}, [ attributes.variant ] );

	/**
	 * Return icon for preview.
	 * @type {unknown}
	 */
	const icon = useMemo( () => {
		switch ( attributes.variant ) {
			default:
			case 'info':
				return <InfoIcon />;
			case 'warning':
				return <WarningIcon />;
		}
	}, [ attributes.variant ] );

	/**
	 * Returns placeholder string.
	 * @type {string}
	 * @private
	 */
	const _PLACEHOLDER = useMemo( () => {
		return 'warning' === attributes.variant
			? _x( 'Write warning…', 'ncb-denhaag/note: Placeholder', 'nlds-community-blocks' )
			: _x( 'Write notification…', 'ncb-denhaag/note: Placeholder', 'nlds-community-blocks' )
	}, [ attributes.variant ] );

	/**
	 * Return the variation options, used for the Toolbar Control.
	 * @type {[{variant: string, icon},{variant: string, icon}]}
	 */
	const variantOptions = [
		{ variant: 'info', icon: InfoIcon },
		{ variant: 'warning', icon: WarningIcon },
	];

	useLayoutEffect( () => {
		// Remove `<meta charset="utf-8">` from copy-and-paste actions.
		if ( !! attributes.text && !! attributes.text.includes( '<meta charset="utf-8">' ) ) {
			setAttributes( { text: attributes.text.replace( /<meta charSet="utf-8">/gmi, '' ).trim() } );
		}
	}, [ attributes.text ] );

	return (
		<>
			<BlockControls>
				<NCB_NoteVariantControl
					value={ attributes.variant }
					options={ variantOptions }
					setAttributes={ setAttributes }
				/>
			</BlockControls>
			<div className={ _CLASSES.root }>
				{ icon }
				<RichText
					tagName="div"
					placeholder={ _PLACEHOLDER }
					value={ attributes.content }
					onChange={ ( text ) => setAttributes( { text } ) }
					allowedFormats={ [ 'core/link' ] }
				/>
			</div>
		</>
	);
}
