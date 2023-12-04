import { useMemo } from '@wordpress/element';
import { ToolbarButton } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Returns Video Portrait Button for within the blockControls.
 *
 * @param {boolean} value True or false.
 * @param {boolean} isDisabled True or false.
 * @param setAttributes setAttributes function of Gutenberg.
 * @returns {unknown}
 * @constructor
 */
const NCB_EmbedYouTubePortraitControls = ({
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
				icon="image-rotate-left"
				label={sprintf(
					__('%s autoplay', 'nlds-community-blocks'),
					label
				)}
				onClick={() => setAttributes({ portrait: !value })}
				isActive={!!value}
				disabled={!!isDisabled}
			/>
		);
	}, [value]);
};

export default NCB_EmbedYouTubePortraitControls;
