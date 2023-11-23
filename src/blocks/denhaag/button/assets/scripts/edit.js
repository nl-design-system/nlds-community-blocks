import { _x } from '@wordpress/i18n';
import { RichText, BlockControls } from '@wordpress/block-editor';
import classNames from 'classnames';
import { ReactComponent as ArrowRightIcon } from '../icons/arrow-right.svg';
import { ReactComponent as ExternalLinkIcon } from '../icons/external-icon.svg';
import { useMemo, useLayoutEffect } from '@wordpress/element';
import NCB_LinkControls from "../../../../../editor/ncb-link-control";
import { ToolbarGroup } from "@wordpress/components";
import NCB_ButtonSizeControl from "./controls/ncb-button-size-control";
import NCB_ButtonIconControl from "./controls/ncb-button-icon-control";
import NCB_ButtonVariantControl from "./controls/ncb-button-variant-control";
import NCB_ButtonIconBeforeControl from "./controls/ncb-button-icon-before-control";

export default function Edit( { attributes, setAttributes } ) {

	/**
	 * Check if is external url.
	 *
	 * @type {boolean}
	 */
	const isExternal = useMemo( () => {

		if ( ! attributes.link?.url ) {
			return false;
		}

		return 'URL' === attributes.link?.type && attributes.link?.url && ! attributes.link.url.includes( window.location.origin );
	}, [ attributes.link ] );

	const _CLASSES = useMemo( () => {
		return {
			root: classNames( 'denhaag-button', {
				[ `denhaag-button--${ attributes.variant }-action` ]: !! attributes.variant,
				[ 'denhaag-button--end-icon' ]: ( !! attributes.icon && ! attributes.iconBefore ) || !! isExternal,
				[ 'denhaag-button--start-icon' ]: ( !! attributes.icon && !! attributes.iconBefore ) && ! isExternal,
				[ `denhaag-button--${ attributes.size }` ]: 'default' !== attributes.size,
				[ 'denhaag-button--external' ]: !! isExternal,
				[ 'denhaag-button--disabled' ]: ! attributes.link?.url,
				[ 'denhaag-button--icon-only' ]: !! attributes.icon && ! ( attributes.link && attributes.link.title )
			} ),
			icon: 'denhaag-button__icon'
		};
	}, [
		attributes.link,
		attributes.icon,
		attributes.iconBefore,
		attributes.size,
		attributes.variant,
		isExternal
	] );

	useLayoutEffect( () => {
		if ( ! attributes.icon && !! attributes.iconBefore ) {
			setAttributes( { iconBefore: false } );
		}
		if ( !! attributes.icon && ! isExternal ) {
			setAttributes( { icon: true } );
		}
	}, [] );

	/**
	 * Returns icon with wrapper based on if it's an external or internal icon.
	 *
	 * @return {unknown}
	 * @constructor
	 */
	const Icon = () => useMemo( () => {
		if ( !! isExternal ) {
			return (
				<span className={ _CLASSES.icon }>
					<ExternalLinkIcon />
				</span>
			);
		}

		return (
			<span className={ _CLASSES.icon }>
				<ArrowRightIcon />
			</span>
		);

	}, [ isExternal ] );

	return (
		<>
			<BlockControls>
				<NCB_LinkControls value={ attributes.link } setAttributes={ setAttributes } />
				<ToolbarGroup>
					<NCB_ButtonIconControl
						value={ attributes.icon }
						isDisabled={ !! isExternal || ! attributes?.link }
						setAttributes={ setAttributes }
					/>
					<NCB_ButtonIconBeforeControl
						value={ attributes.iconBefore }
						isDisabled={ ! attributes.icon || !! isExternal || ! attributes?.link }
						setAttributes={ setAttributes } />
				</ToolbarGroup>
				<NCB_ButtonSizeControl
					value={ attributes.size }
					setAttributes={ setAttributes }
					isDisabled={ ! attributes?.link }
				/>
				<NCB_ButtonVariantControl
					value={ attributes.variant }
					isDisabled={ ! attributes?.link }
					setAttributes={ setAttributes } />
			</BlockControls>

			{ !! attributes.link && (
				<div className={ _CLASSES.root }>
					{ !! attributes.icon && !! attributes.iconBefore && <Icon /> }
					<RichText
						withoutInteractiveFormatting
						placeholder={
							_x( 'Button label…', 'ncb-denhaag/button: Placeholder', 'nlds-community-blocks' )
						}
						value={ attributes.link.title }
						onChange={ ( text ) => {
							const link = !! attributes.link ? attributes.link : {}
							link.title = text;
							setAttributes( { link } );
						} }
						allowedFormats={ [] }
					/>
					{ ( ( !! attributes.icon && ! attributes.iconBefore ) || !! isExternal ) && <Icon /> }
				</div> ) }

			{ ! attributes.link && (
				<div className={ _CLASSES.root }>
					{ _x( 'Button label…', 'ncb-denhaag/button: Placeholder', 'nlds-community-blocks' ) }
				</div>
			) }
		</>
	);
}
