import { Paragraph } from '@utrecht/component-library-react';
import { RichText } from '@wordpress/block-editor';
import classNames from 'classnames';

export default function Save({ attributes }) {
	// Add custom class name for all non-standard variations
	const _CLASSES = classNames({
		[`utrecht-paragraph--${attributes.variation}`]:
			!['small', 'lead'].includes(attributes.variation) &&
			!!attributes.variation,
	});

	return (
		<Paragraph
			small={attributes.variation === 'small'}
			lead={attributes.variation === 'lead'}
			className={_CLASSES}
		>
			<RichText.Content value={attributes.content} />
		</Paragraph>
	);
}
