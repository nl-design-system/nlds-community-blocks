import { _x, sprintf } from "@wordpress/i18n";
import { ToolbarButton } from "@wordpress/components";
import { useMemo } from "@wordpress/element";
import { postExcerpt as icon } from '@wordpress/icons';

/**
 * Returns the (allowed) excerpt Toolbar Controller.
 *
 * @param {boolean} value Enable or disable an excerpt.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @param {boolean} isDisabled True if the field is disabled, probably on a page which doesn't support excerpts.
 * @return {unknown}
 * @constructor
 */
const NCB_MetaExcerptControl = ( { value, setAttributes, isDisabled = false } ) => {
	// On update `value` the controller will be rendered.
	return useMemo( () => {
		return (
			<ToolbarButton
				onClick={ () => setAttributes( { excerpt: ! value } ) }
				icon={ icon }
				label={ sprintf( '%s %s',
					!! value
						? _x(
							'Disable',
							'ncb-denhaag/meta: Control label',
							'nlds-community-blocks'
						)
						: _x(
							'Enable',
							'ncb-denhaag/meta: Control label',
							'nlds-community-blocks'
						),
					_x(
						'Excerpt',
						'ncb-denhaag/meta: Control label',
						'nlds-community-blocks'
					)
				) }
				isPressed={ value }
				disabled={ isDisabled }
			/>
		);
	}, [ value, isDisabled ] );
};

export default NCB_MetaExcerptControl;
