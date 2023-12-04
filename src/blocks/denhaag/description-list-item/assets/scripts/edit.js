import { _x } from '@wordpress/i18n';
import { RichText } from '@wordpress/block-editor';

export default function edit({ attributes, setAttributes }) {
	const ALLOWED_FORMATS = ['core/link', 'core/subscript', 'core/superscript'];

	return (
		<>
			<RichText
				tagName="dt"
				className="denhaag-description-list__title"
				value={attributes.title}
				placeholder={_x(
					'Title here…',
					'denhaag/description-list-item: Placeholder',
					'nlds-community-blocks'
				)}
				onChange={(title) => setAttributes({ title })}
				allowedFormats={ALLOWED_FORMATS}
			/>
			<RichText
				tagName="dd"
				className="denhaag-description-list__detail"
				value={attributes.detail}
				placeholder={_x(
					'Details here…',
					'denhaag/description-list-item: Placeholder',
					'nlds-community-blocks'
				)}
				onChange={(detail) => setAttributes({ detail })}
				allowedFormats={ALLOWED_FORMATS}
			/>
		</>
	);
}
