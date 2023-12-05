import { _x, __ } from '@wordpress/i18n';
import { ToolbarButton, ToolbarGroup } from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import { resizeCornerNE, symbolFilled } from '@wordpress/icons';

/**
 * Returns the Button size Toolbar Controller.
 *
 * @param {boolean} value The value if the button size variant.
 * @param {boolean} isDisabled If this controller is disabled or not.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @return {unknown}
 * @constructor
 */
const NCB_ButtonSizeControl = ({
	value = false,
	isDisabled = false,
	setAttributes,
}) => {
	/*
	const onSizeChange = ( newKey ) => {
		setActiveSize( newKey );
		onChange( newKey );
	};

	return (

			<ButtonGroup aria-label={ __( 'Button Size' ) }>
				{ sizes.map( ( { key, label } ) => (
					<Button
						key={ key }
						isPrimary={ activeSize === key }
						aria-pressed={ activeSize === key }
						onClick={ () => onSizeChange( key ) }
					>
						{ label }
					</Button>
				) ) }
			</ButtonGroup>

	);
 */
	// On update `value` the controller will be rendered.
	return useMemo(() => {
		const _SIZES = [
			{
				label: _x(
					'Default',
					'ncb-denhaag/button: Control label',
					'nlds-community-blocks'
				),
				value: 'default',
			},
			{
				label: _x(
					'Large',
					'ncb-denhaag/button: Control label',
					'nlds-community-blocks'
				),
				value: 'large',
			},
		];

		return (
			<ToolbarGroup>
				{_SIZES.map((s) => {
					return (
						<ToolbarButton
							key={s.value}
							onClick={() => setAttributes({ size: s.value })}
							label={sprintf(
								_x(
									'Set %s button style',
									'ncb-denhaag/button: Control label',
									'nlds-community-blocks'
								),
								s.label
							)}
							isPressed={s.value === value}
							disabled={isDisabled}
						>
							{s.label}
						</ToolbarButton>
					);
				})}
			</ToolbarGroup>
		);
	}, [value, isDisabled]);
};

export default NCB_ButtonSizeControl;
