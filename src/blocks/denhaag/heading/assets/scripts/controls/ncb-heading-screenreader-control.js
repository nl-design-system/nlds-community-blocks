import { _x } from "@wordpress/i18n";
import { ToolbarButton } from "@wordpress/components";
import { useMemo } from "@wordpress/element";

/**
 * Returns the Variation Toolbar Controller.
 *
 * @param {boolean} value The Variation.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @return {unknown}
 * @constructor
 */
const NCB_HeadingScreenreaderControl = ( { value, setAttributes } ) => {
	// On update `value` the controller will be rendered.
	return useMemo( () => {
		return (
			<ToolbarButton
				onClick={ () => setAttributes( { srOnly: ! value } ) }
				icon="desktop"
				label={ _x(
					'Visible for screen readers only',
					'ncb-denhaag/heading: Control label',
					'nlds-community-blocks'
				) }
				isPressed={ value }
			/>
		);
	}, [ value ] );
};

export default NCB_HeadingScreenreaderControl;
