import { useMemo } from '@wordpress/element';
import { ToolbarButton, ToolbarGroup } from '@wordpress/components';

/**
 * Returns the Variation Toolbar Controller.
 *
 * @param {string} value The Variation.
 * @param {array} options Options array to show.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @return {unknown}
 * @constructor
 */
const NCB_NoteVariantControl = ({
	value = 'info',
	options = [],
	setAttributes,
}) => {
	return useMemo(() => {
		if (0 === options.length) {
			return null;
		}

		const ToolbarButtons = () =>
			options.map((option) => {
				return (
					<ToolbarButton
						onClick={() =>
							setAttributes({ variant: option.variant })
						}
						key={option.variant}
						isActive={value === option.variant}
						icon={option.icon}
					/>
				);
			});

		return (
			<ToolbarGroup>
				<ToolbarButtons />
			</ToolbarGroup>
		);
	}, [value, options]);
};

export default NCB_NoteVariantControl;
