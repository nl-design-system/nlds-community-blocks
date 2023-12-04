import { _x } from '@wordpress/i18n';
import { ToolbarButton } from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import { ReactComponent as IconEnd } from '../../icons/icon-end.svg';
import { ReactComponent as IconStart } from '../../icons/icon-start.svg';

/**
 * Returns the Button icon Toolbar Controller.
 *
 * @param {boolean} value The value if the button icon variant.
 * @param {boolean} isDisabled If this controller is disabled or not.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @return {unknown}
 * @constructor
 */
const NCB_ButtonIconBeforeControl = ({
	value = false,
	isDisabled = false,
	setAttributes,
}) => {
	// On update `value` the controller will be rendered.
	return useMemo(() => {
		const label = !!value
			? _x(
					'after',
					'ncb-denhaag/button: Control label',
					'nlds-community-blocks'
			  )
			: _x(
					'before',
					'ncb-denhaag/button: Control label',
					'nlds-community-blocks'
			  );

		return (
			<ToolbarButton
				onClick={() => setAttributes({ iconBefore: !value })}
				icon={!!value ? IconStart : IconEnd}
				label={sprintf(
					_x(
						'Set the icon %s the label',
						'ncb-denhaag/button: Control label',
						'nlds-community-blocks'
					),
					label
				)}
				isPressed={value}
				disabled={isDisabled}
			/>
		);
	}, [value, isDisabled]);
};

export default NCB_ButtonIconBeforeControl;
