import NCB_SetLinkControl from "./assets/scripts/ncb-set-link-control";
import NCB_UnsetLinkControl from "./assets/scripts/ncb-unset-link-control";
import { ToolbarGroup } from '@wordpress/components';
import { useMemo } from "@wordpress/element";

/**
 *
 * @param {object} value The link object.
 * @param {string} attribute The attribute key to (un)set the value.
 * @param {function} setAttributes The setAttributes function of WordPress.
 * @param {object} Options to pass through <NCB_SetLinkControl>.
 * @return {JSX.Element}
 * @constructor
 */
const NCB_LinkControls = (
	{
		value = {},
		attribute = 'link',
		setAttributes,
		options = {}
	}
) => {
	return useMemo( () => {
		// Disable UnsetLinkControl if no value is set.
		const isDisabled = ! value || 0 === Object.keys( value ).length;
		return (
			<ToolbarGroup>
				<NCB_SetLinkControl attribute={ attribute } value={ value } setAttributes={ setAttributes } options={ options } />
				<NCB_UnsetLinkControl attribute={ attribute } isDisabled={ isDisabled } setAttributes={ setAttributes } />
			</ToolbarGroup>
		);
	}, [ value ] );
};

export default NCB_LinkControls;
