import { useMemo } from '@wordpress/element';
import { ToolbarButton } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { ReactComponent as icon } from '../../icons/autoplay.svg';

/**
 * Returns Video AutoPlay Button for within the blockControls.
 *
 * @param {boolean} value True or false.
 * @param {boolean} isDisabled True or false.
 * @param setAttributes setAttributes function of Gutenberg.
 * @returns {unknown}
 * @constructor
 */
const NCB_EmbedYouTubeAutoPlayControls = ({
	value = false,
	isDisabled = false,
	setAttributes,
}) => {
	return useMemo(() => {
		const label = !!value
			? __('Disable', 'nlds-community-blocks')
			: __('Enable', 'nlds-community-blocks');
		return (
			<ToolbarButton
				icon={icon}
				label={sprintf(
					__('%s autoplay', 'nlds-community-blocks'),
					label
				)}
				onClick={() => setAttributes({ autoplay: !value })}
				isActive={!!value}
				disabled={!!isDisabled}
			/>
		);
	}, [value]);
};

export default NCB_EmbedYouTubeAutoPlayControls;
