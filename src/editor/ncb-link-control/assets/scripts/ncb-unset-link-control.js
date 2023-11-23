import { ToolbarButton } from "@wordpress/components";
import { linkOff } from "@wordpress/icons";
import { useMemo } from "@wordpress/element";
import { _x } from "@wordpress/i18n";

/**
 * Returns the controller to unset the link object.
 *
 * @param {string} attribute The attribute key of the link.
 * @param {boolean} isDisabled isDisabled or not.
 * @param {function} setAttributes The setAttributes function of WordPress.
 * @return {JSX.Element}
 * @constructor
 */
const NCB_UnsetLinkControl = ( { attribute, isDisabled = false, setAttributes } ) => {
	return useMemo( () => {
		return (
			<ToolbarButton
				label={ _x( 'Remove link', 'NCB_UnsetLinkControl label', 'nlds-community-blocks' ) }
				disabled={ isDisabled }
				icon={ linkOff }
				onClick={ () => setAttributes( { [ attribute ]: {} } ) }
			/>
		);
	}, [ isDisabled ] );
};
export default NCB_UnsetLinkControl;
