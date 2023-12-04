import { ToolbarButton, ToolbarGroup } from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import { _x, sprintf } from '@wordpress/i18n';

import { ReactComponent as H2 } from './assets/icons/h2.svg';
import { ReactComponent as H3 } from './assets/icons/h3.svg';
import { ReactComponent as H4 } from './assets/icons/h4.svg';
import { ReactComponent as H5 } from './assets/icons/h5.svg';

const NCB_HeadingTagControl = ({
	value = 2,
	attribute = 'level',
	allowedTags = [2, 3, 4],
	setAttributes,
}) => {
	return useMemo(() => {
		if (0 === allowedTags.length) {
			return null;
		}

		const label =
			'tag' === attribute
				? _x(
						'Set as heading %s',
						'denhaag/paragraph: ToolbarButton label',
						'nlds-community-blocks'
				  )
				: _x(
						'Show as heading %s',
						'denhaag/paragraph: ToolbarButton label',
						'nlds-community-blocks'
				  );

		const controls = [
			{ value: 2, icon: H2 },
			{ value: 3, icon: H3 },
			{ value: 4, icon: H4 },
			{ value: 5, icon: H5 },
		]
			.filter((choice) => allowedTags.includes(choice.value))
			.map((tagOption) => (
				<ToolbarButton
					onClick={() =>
						setAttributes({ [attribute]: tagOption.value })
					}
					label={sprintf(label, tagOption.value)}
					key={tagOption[attribute]}
					isActive={value === tagOption.value}
					icon={tagOption.icon}
				/>
			));

		return <ToolbarGroup>{controls}</ToolbarGroup>;
	}, [value, allowedTags]);
};

export default NCB_HeadingTagControl;
