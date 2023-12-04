import { _x } from '@wordpress/i18n';
import { Dropdown, ToolbarButton } from '@wordpress/components';
import { formatUppercase } from '@wordpress/icons';
import NCB_ParagraphVariationControl from './ncb-paragraph-variation-control';
import { useMemo } from '@wordpress/element';

/**
 * Returns the Variation Toolbar Controller.
 *
 * @param {string} value The Variation.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @return {unknown}
 * @constructor
 */
const NCB_ParagraphVariationToolbarControl = ({ value, setAttributes }) => {
	// On update `value` the controller will be rendered.
	return useMemo(() => {
		return (
			<Dropdown
				renderToggle={({ onToggle }) => (
					<ToolbarButton
						icon={formatUppercase}
						label={_x(
							'Select variation',
							'ncb-denhaag/paragraph: Toolbar Button label',
							'nlds-community-blocks'
						)}
						onClick={onToggle}
						isActive={!!value}
					/>
				)}
				renderContent={() => (
					<NCB_ParagraphVariationControl
						value={value}
						setAttributes={setAttributes}
					/>
				)}
			/>
		);
	}, [value]);
};

export default NCB_ParagraphVariationToolbarControl;
