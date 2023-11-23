import { Dropdown, ToolbarButton } from "@wordpress/components";
import { link } from "@wordpress/icons";
import { useMemo } from "@wordpress/element";
import { _x, __, sprintf } from "@wordpress/i18n";
import { __experimentalLinkControl as LinkControl } from "@wordpress/block-editor";

/**
 * Returns the controller to unset the link object.
 *
 * @param {string} attribute The attribute key of the link.
 * @param {object} value The link object.
 * @param {function} setAttributes The setAttributes function of WordPress.
 * @param {object} options Options for the LinkControl.
 * @return {JSX.Element}
 * @constructor
 */
const NCB_SetLinkControl = ( { attribute = 'link', value = {}, setAttributes, options = {} } ) => {

	return useMemo( () => {
	const config = {
			...{
				forceIsEditingLink: true,
				hasRichPreviews: true,
				hasTextControl: true,
				showSuggestions: true,
				createSuggestionButtonText: true,
				settings: true, // This can be an object, to make it empty just put `false`.
			}, ...options
		};

		const default_settings = [
			// Copied from `@wordpress/block-editor/build-module/components/constants.js`.
			{
				id: 'opensInNewTab',
				title: __( "Open in new tab" ),
			},
		]

		// Check if `wordpress-seo` plugin is active.
		if ( !! window.yoast ) {
			default_settings.push( {
				id: 'noFollow',
				title: sprintf(
					__( "Search engines should ignore this link (mark as %1$snofollow%2$s)%3$s", "wordpress-seo" ),
					"\"",
					"\"",
					"."
				)
			} )

			default_settings.push( {
				id: 'sponsored',
				title: sprintf(
					__( "This is a sponsored link or advert (mark as %1$ssponsored%2$s)%3$s", "wordpress-seo" ),
					"\"",
					"\"",
					"."
				),
			} );
		}

		return (
			<Dropdown
				renderToggle={ ( { onToggle } ) => (
					<ToolbarButton
						icon={ link }
						label={ _x( 'Add link', 'NCB_SetLinkControl label', 'nlds-community-blocks' ) }
						onClick={ onToggle }
						isActive={ 0 !== Object.keys( value ).length && ( value.hasOwnProperty( 'url' ) && value.url.length > 0 ) }
					/>
				) }
				renderContent={ () => (
					<>
						{ !! config.settings && 'boolean' === typeof config.settings && (
							<LinkControl
								forceIsEditingLink={ config.forceIsEditingLink }
								onChange={ ( link ) => setAttributes( { [ attribute ]: link } ) }
								value={ value && value }
								hasRichPreviews={ config.hasRichPreviews }
								hasTextControl={ config.hasTextControl }
								showSuggestions={ config.showSuggestions }
								createSuggestionButtonText={ config.createSuggestionButtonText }
								settings={ default_settings }
							/>
						) }
						{ !! config.settings && 'object' === typeof config.settings && (
							<LinkControl
								forceIsEditingLink={ config.forceIsEditingLink }
								onChange={ ( link ) => setAttributes( { [ attribute ]: link } ) }
								value={ value && value }
								hasRichPreviews={ config.hasRichPreviews }
								hasTextControl={ config.hasTextControl }
								showSuggestions={ config.showSuggestions }
								createSuggestionButtonText={ config.createSuggestionButtonText }
								settings={ config.settings }
							/>
						) }
						{ ! config.settings && (
							<LinkControl
								forceIsEditingLink={ config.forceIsEditingLink }
								onChange={ ( link ) => setAttributes( { [ attribute ]: link } ) }
								value={ value && value }
								hasRichPreviews={ config.hasRichPreviews }
								hasTextControl={ config.hasTextControl }
								showSuggestions={ config.showSuggestions }
								createSuggestionButtonText={ config.createSuggestionButtonText }
								settings={ [] }
							/>
						) }
					</>

				) }
			/>
		);
	}, [ value, options ] );
};
export default NCB_SetLinkControl;
