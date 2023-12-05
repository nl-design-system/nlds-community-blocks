import { _x, sprintf } from '@wordpress/i18n';
import {
	Button,
	ButtonGroup,
	ToolbarButton,
	ToolbarGroup,
} from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import { _VARIATIONS } from '../constants.js';

/**
 * Returns the Variation Toolbar Controller.
 *
 * @param {boolean} value The Layout.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @param {boolean} inToolbar if this control for toolbar or block.
 * @return {unknown}
 * @constructor
 */
const NCB_ColumnsLayoutControl = ({
	value,
	setAttributes,
	inToolbar = false,
}) => {
	// On update `value` the controller will be rendered.
	return useMemo(() => {
		const labelPrefix = _x(
			'Select %s',
			'ncb-denhaag/columns: Control prefix',
			'nlds-community-blocks'
		);

		if (inToolbar) {
			return (
				<ToolbarGroup>
					{_VARIATIONS.map((item) => {
						return (
							<ToolbarButton
								key={item.name}
								onClick={() =>
									setAttributes({
										layout: item.name,
										columns: item.columns,
									})
								}
								icon={item.icon}
								label={sprintf(labelPrefix, item.label)}
								isPressed={item.name === value}
							/>
						);
					})}{' '}
				</ToolbarGroup>
			);
		}

		return (
			<ButtonGroup>
				{_VARIATIONS.map((item) => {
					return (
						<Button
							key={item.name}
							onClick={() =>
								setAttributes({
									layout: item.name,
									columns: item.columns,
								})
							}
							icon={item.icon}
							label={sprintf(labelPrefix, item.label)}
							isPressed={item.name === value}
						/>
					);
				})}
			</ButtonGroup>
		);
	}, [value]);
};

export default NCB_ColumnsLayoutControl;
