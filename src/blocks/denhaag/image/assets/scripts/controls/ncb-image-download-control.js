import { _x } from '@wordpress/i18n';
import { ToolbarButton } from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import { download } from '@wordpress/icons';

/**
 * Returns the Download Toolbar Controller.
 *
 * @param {boolean} value The value if image may be downloaded.
 * @param {boolean} isDisabled If this controller is disabled or not.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @return {unknown}
 * @constructor
 */
const NCB_ImageDownloadControl = ({
	value = false,
	isDisabled = false,
	setAttributes,
}) => {
	// On update `value` the controller will be rendered.
	return useMemo(() => {
		return (
			<ToolbarButton
				onClick={() => setAttributes({ download: !value })}
				icon={download}
				label={_x(
					'Show download option',
					'ncb-denhaag/image: Control label',
					'nlds-community-blocks'
				)}
				isPressed={value}
				disabled={isDisabled}
			/>
		);
	}, [value, isDisabled]);
};

export default NCB_ImageDownloadControl;
