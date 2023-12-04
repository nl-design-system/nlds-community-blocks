import { useMemo } from '@wordpress/element';
import { ToolbarButton } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { ReactComponent as icon } from '../../icons/repeat.svg';

/**
 * Returns Video Loop Button for within the blockControls.
 *
 * @param {boolean} value True or false.
 * @param {boolean} isDisabled True or false.
 * @param setAttributes setAttributes function of Gutenberg.
 * @returns {unknown}
 * @constructor
 */
const NCB_EmbedYouTubeLoopControl = ({
	value = false,
	isDisabled = false,
	setAttributes,
}) => {
	return useMemo(() => {
		const label = !!value
			? __('Unloop', 'nlds-community-blocks')
			: __('Loop', 'nlds-community-blocks');

		return (
			<ToolbarButton
				icon={icon}
				label={sprintf(__('%s video', 'nlds-community-blocks'), label)}
				onClick={() => setAttributes({ loop: !value })}
				isActive={!!value}
				disabled={!!isDisabled}
			/>
		);
	}, [value]);
};

export default NCB_EmbedYouTubeLoopControl;
