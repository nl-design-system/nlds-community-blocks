import { _x } from "@wordpress/i18n";
import { ToolbarButton } from "@wordpress/components";
import { useMemo } from "@wordpress/element";
import { title } from '@wordpress/icons';

/**
 * Returns the Caption Toolbar Controller.
 *
 * @param {boolean} value The value if the image has caption.
 * @param {boolean} isDisabled If this controller is disabled or not.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @return {unknown}
 * @constructor
 */
const NCB_ImageCaptionControl = ( { value = false, isDisabled = false, setAttributes } ) => {
	// On update `value` the controller will be rendered.
	return useMemo( () => {
		return (
			<ToolbarButton
				onClick={ () => setAttributes( { hasCaption: ! value } ) }
				icon={ title }
				label={ _x(
					'Show image caption',
					'ncb-denhaag/image: Control label',
					'nlds-community-blocks'
				) }
				isPressed={ value }
				disabled={ isDisabled }
			/>
		);
	}, [ value, isDisabled ] );
};

export default NCB_ImageCaptionControl;
