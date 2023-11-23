import { _x } from "@wordpress/i18n";
import { ToolbarButton, ToolbarGroup } from "@wordpress/components";
import { useMemo } from "@wordpress/element";
import { symbolFilled, symbol } from '@wordpress/icons';

/**
 * Returns the Button size Toolbar Controller.
 *
 * @param {boolean} value The value if the button size variant.
 * @param {boolean} isDisabled If this controller is disabled or not.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @return {unknown}
 * @constructor
 */
const NCB_ButtonVariantControl = ( { value = false, isDisabled = false, setAttributes } ) => {
	// On update `value` the controller will be rendered.
	return useMemo( () => {
		const _VARIATIONS = [ {
			label: _x( 'Primary', 'ncb-denhaag/button: Control label', 'nlds-community-blocks' ),
			icon: symbolFilled,
			value: 'primary'
		}, {
			label: _x( 'Secondary', 'ncb-denhaag/button: Control label', 'nlds-community-blocks' ),
			icon: symbol,
			value: 'secondary'
		} ];

		return (
			<ToolbarGroup>
				{ _VARIATIONS.map( ( v ) => {
					return <ToolbarButton
						onClick={ () => setAttributes( { variant: v.value } ) }
						icon={ v.icon }
						label={ sprintf( _x( 'Set %s button style', 'ncb-denhaag/button: Control label', 'nlds-community-blocks' ), v.label ) }
						isPressed={ v.value === value }
						disabled={ isDisabled }
					/>
				} ) }
			</ToolbarGroup>
		);
	}, [ value, isDisabled ] );
};

export default NCB_ButtonVariantControl;
