import { _x } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';
import { useMemo } from '@wordpress/element';

/**
 * Returns the Variation SelectControl.
 *
 * @param {string} value The variation.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @return {unknown}
 * @constructor
 */
const NCB_ParagraphVariationControl = ({ value, setAttributes }) => {
	// On update `value` the controller will be rendered.
	return useMemo(() => {
		const VARIATIONS = [
			{
				label: _x(
					'Default',
					'Variation denhaag/paragraph',
					'nlds-community-blocks'
				),
				value: '',
			},
			{
				label: _x(
					'Lead',
					'Variation denhaag/paragraph',
					'nlds-community-blocks'
				),
				value: 'lead',
			},
			{
				label: _x(
					'Small',
					'Variation denhaag/paragraph',
					'nlds-community-blocks'
				),
				value: 'small',
			},
			{
				label: _x(
					'Distanced',
					'Variation denhaag/paragraph',
					'nlds-community-blocks'
				),
				value: 'distanced',
			},
			{
				label: _x(
					'Den Haag Detail',
					'Variation denhaag/paragraph',
					'nlds-community-blocks'
				),
				value: 'denhaag-detail',
			},
		];

		return (
			<SelectControl
				className="variation-select"
				label={_x(
					'Select variation',
					'denhaag/paragraph: Toolbar Button label',
					'nlds-community-blocks'
				)}
				value={value}
				options={VARIATIONS}
				onChange={(variation) => setAttributes({ variation })}
			/>
		);
	}, [value]);
};

export default NCB_ParagraphVariationControl;
