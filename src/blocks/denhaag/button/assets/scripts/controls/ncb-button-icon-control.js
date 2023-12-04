import { _x } from '@wordpress/i18n';
import { ToolbarButton } from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import { ReactComponent as HasIcon } from '../../icons/has-icon.svg';
import { ReactComponent as NoIcon } from '../../icons/no-icon.svg';

/**
 * Returns the Button icon Toolbar Controller.
 *
 * @param {boolean} value The value if the button icon variant.
 * @param {boolean} isDisabled If this controller is disabled or not.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @return {unknown}
 * @constructor
 */
const NCB_ButtonIconControl = ({
	value = false,
	isDisabled = false,
	setAttributes,
}) => {
	// On update `value` the controller will be rendered.
	return useMemo(() => {
		const label = !!value
			? _x(
					'Hide',
					'ncb-denhaag/button: Control label',
					'nlds-community-blocks'
			  )
			: _x(
					'Show',
					'ncb-denhaag/button: Control label',
					'nlds-community-blocks'
			  );

		return (
			<ToolbarButton
				onClick={() => setAttributes({ icon: !value })}
				icon={!!value ? HasIcon : NoIcon}
				label={sprintf(
					'%s %s',
					label,
					_x(
						'icon',
						'ncb-denhaag/button: Control label',
						'nlds-community-blocks'
					)
				)}
				isPressed={value}
				disabled={isDisabled}
			/>
		);
	}, [value, isDisabled]);
};

export default NCB_ButtonIconControl;
