import { useMemo } from '@wordpress/element';
import { ToolbarButton } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { ReactComponent as volumeOnIcon } from '../../icons/volume-on.svg';
import { ReactComponent as volumeOffIcon } from '../../icons/volume-off.svg';

/**
 * Returns Video Mute/Unmute Button for within the blockControls.
 *
 * @param {boolean} value True or false.
 * @param {boolean} isDisabled True or false.
 * @param setAttributes setAttributes function of Gutenberg.
 * @returns {unknown}
 * @constructor
 */
const NCB_EmbedYouTubeMuteControl = ({
	value = false,
	isDisabled = false,
	setAttributes,
}) => {
	return useMemo(() => {
		const label = !!value
			? __('Muted', 'nlds-community-blocks')
			: __('Unmuted', 'nlds-community-blocks');
		const icon = !!value ? volumeOffIcon : volumeOnIcon;
		return (
			<ToolbarButton
				icon={icon}
				label={sprintf(__('Set %s', 'nlds-community-blocks'), label)}
				onClick={() => setAttributes({ mute: !value })}
				isActive={!!value}
				disabled={!!isDisabled}
			/>
		);
	}, [value]);
};

export default NCB_EmbedYouTubeMuteControl;
