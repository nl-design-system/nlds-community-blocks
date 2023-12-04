import NCB_BlockquoteHasAuthorControl from './controls/ncb-blockquote-has-author-control';
import NCB_LinkControls from '../../../../../editor/ncb-link-control';
import { _x } from '@wordpress/i18n';
import { BlockControls, RichText } from '@wordpress/block-editor';
import { useLayoutEffect } from '@wordpress/element';

export default function edit({ attributes, setAttributes }) {
	useLayoutEffect(() => {
		// Remove `<meta charset="utf-8">` from copy-and-paste actions.
		if (
			!!attributes.quote &&
			!!attributes.quote.includes('<meta charset="utf-8">')
		) {
			setAttributes({
				quote: attributes.quote
					.replace(/<meta charSet="utf-8">/gim, '')
					.trim(),
			});
		}
	}, [attributes.quote]);

	useLayoutEffect(() => {
		// Remove `<meta charset="utf-8">` from copy-and-paste actions.
		if (
			!!attributes.author &&
			!!attributes.author.includes('<meta charset="utf-8">')
		) {
			setAttributes({
				author: attributes.author
					.replace(/<meta charSet="utf-8">/gim, '')
					.trim(),
			});
		}
	}, [attributes.author]);

	return (
		<>
			<BlockControls>
				<NCB_LinkControls
					value={attributes.link}
					setAttributes={setAttributes}
				/>
				<NCB_BlockquoteHasAuthorControl
					value={attributes.hasAuthor}
					setAttributes={setAttributes}
				/>
			</BlockControls>

			<figure className="denhaag-blockquote">
				<blockquote
					className="denhaag-blockquote__content"
					cite={
						attributes.link && attributes.link.url
							? attributes.link.url
							: null
					}
				>
					<RichText
						identifier="quote"
						tagName="p"
						withoutInteractiveFormatting
						placeholder={_x(
							'Add quote…',
							'ncb-denhaag/blockquote: Quote placeholder',
							'nlds-community-blocks'
						)}
						value={attributes.quote}
						onChange={(quote) => setAttributes({ quote })}
						allowedFormats={[]}
					/>
				</blockquote>
				{!!attributes.hasAuthor && (
					<RichText
						identifier="caption"
						tagName="figcaption"
						className="denhaag-blockquote__attribution"
						placeholder={_x(
							'Author name…',
							'ncb-denhaag/blockquote: Author placeholder',
							'nlds-community-blocks'
						)}
						value={attributes.author}
						onChange={(author) => setAttributes({ author })}
						allowedFormats={[]}
						withoutInteractiveFormatting
					/>
				)}
			</figure>
		</>
	);
}
