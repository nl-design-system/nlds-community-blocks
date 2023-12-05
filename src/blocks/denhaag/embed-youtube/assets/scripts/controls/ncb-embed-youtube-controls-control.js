import { useMemo } from '@wordpress/element';
import { ToolbarButton } from '@wordpress/components';
import { sprintf, __ } from '@wordpress/i18n';
import { ReactComponent as icon } from '../../icons/video-settings.svg';

/**
 * Returns Video Controls Button for within the blockControls.
 *
 * @param {boolean} value True or false.
 * @param {boolean} isDisabled True or false.
 * @param setAttributes setAttributes function of Gutenberg.
 * @returns {unknown}
 * @constructor
 */
const NCB_EmbedYouTubeControlsControl = ({
	value = false,
	isDisabled = false,
	setAttributes,
}) => {
	return useMemo(() => {
		const label = !!value
			? __('Hide', 'nlds-community-blocks')
			: __('Show', 'nlds-community-blocks');

		return (
			<ToolbarButton
				icon={icon}
				label={sprintf(
					__('%s video controls', 'nlds-community-blocks'),
					label
				)}
				onClick={() => setAttributes({ controls: !value })}
				isActive={!!value}
				disabled={!!isDisabled}
			/>
		);
	}, [value]);
};

export default NCB_EmbedYouTubeControlsControl;
