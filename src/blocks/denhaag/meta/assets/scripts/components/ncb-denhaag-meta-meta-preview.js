import { useMemo } from '@wordpress/element';

/**
 * Return HTML b
 *
 * @param {boolean} showMeta Show or hide the HTML.
 * @return {HTMLElement}
 * @constructor
 */
const NCB_DenhaagMetaMetaPreview = ({ showMeta = false }) => {
	const { metadata } = window?.ncb_editor_variables?.denhaag?.meta;
	const newPost =
		wp.data.select('core/editor').isCleanNewPost() ||
		wp.data.select('core/editor').isEditedPostNew();

	return useMemo(() => {
		if (!showMeta || !metadata) {
			return null;
		}

		// Filter all falsy values ( "", 0, false, null, undefined ).
		const cleanedData = Object.entries(metadata).reduce(
			(a, [k, v]) => (v ? ((a[k] = v), a) : a),
			{}
		);

		if (0 === cleanedData.length) {
			return null;
		}

		const metaItems = Object.entries(cleanedData).map((object) => {
			const key = object[0];
			let value = object[1];

			// Sanitize the objects and join them so it's a string.
			if ('object' === typeof value) {
				if (!!newPost) {
					// Strip all `<SVG>` for this post, since we don't have the SVG sprite yet.
					const regex = new RegExp(
						'<svg[^>]*></?use[^>]*></svg>',
						'mi'
					);
					value.filter((v) => !regex.exec(v));
				}

				value = value.join('');
			}

			return `<div class="denhaag-meta__meta-item denhaag-meta__meta-item--${key}">${value}</div>`;
		});

		return (
			<div
				className="denhaag-meta__meta"
				dangerouslySetInnerHTML={{
					__html: metaItems.join(
						'<hr class="denhaag-divider denhaag-divider--vertical" role="presentation" />'
					),
				}}
			/>
		);
	}, [showMeta, metadata, newPost]);
};
export default NCB_DenhaagMetaMetaPreview;
