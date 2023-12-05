import { _x } from '@wordpress/i18n';
import {
	BlockControls,
	InspectorControls,
	RichText,
} from '@wordpress/block-editor';
import { useLayoutEffect, useMemo } from '@wordpress/element';
import classNames from 'classnames';
import { PanelBody } from '@wordpress/components';
import NCB_ParagraphVariationToolbarControl from './controls/ncb-paragraph-variation-toolbar-control';
import NCB_ParagraphVariationControl from './controls/ncb-paragraph-variation-control';

export default function Edit({ attributes, setAttributes, mergeBlocks }) {
	/**
	 * Return string of classNames which updates based on the variation.
	 *
	 * @type {string}
	 * @private
	 */
	const _CLASSES = useMemo(() => {
		return classNames('utrecht-paragraph', {
			[`utrecht-paragraph--${attributes.variation}`]:
				!!attributes.variation,
		});
	}, [attributes.variation]);

	const _PLACEHOLDER = attributes.placeholder
		? attributes.placeholder
		: _x(
				'Write paragraph…',
				'ncb-denhaag/paragraph: Placeholder',
				'nlds-community-blocks'
		  );

	const allowedFormats = [
		'core/bold',
		'core/italic',
		'core/subscript',
		'core/superscript',
	];

	// Push link if it's allowed.
	if (!!attributes.allowLinks) {
		allowedFormats.push('core/link');
	}

	useLayoutEffect(() => {
		// Remove `<meta charset="utf-8">` from copy-and-paste actions.
		if (
			!!attributes.content &&
			!!attributes.content.includes('<meta charset="utf-8">')
		) {
			setAttributes({
				content: attributes.content
					.replace(/<meta charSet="utf-8">/gim, '')
					.trim(),
			});
		}
	}, [attributes.content]);

	return (
		<>
			<BlockControls>
				<NCB_ParagraphVariationToolbarControl
					value={attributes.variation}
					setAttributes={setAttributes}
				/>
			</BlockControls>
			<InspectorControls>
				<PanelBody
					title={_x(
						'Variation',
						'ncb-denhaag/paragraph: Panel Title',
						'nlds-community-blocks'
					)}
				>
					<NCB_ParagraphVariationControl
						value={attributes.variation}
						setAttributes={setAttributes}
					/>
				</PanelBody>
			</InspectorControls>
			<RichText
				identifier="content"
				tagName="p"
				value={attributes.content}
				onChange={(content) => setAttributes({ content })}
				onRemove={() => onReplace([])}
				onMerge={mergeBlocks}
				className={_CLASSES}
				allowedFormats={allowedFormats}
				placeholder={_PLACEHOLDER}
				unstableOnFocus={() => setAttributes({ placeholder: '' })}
				unstableOnBlur={() =>
					setAttributes({ placeholder: _PLACEHOLDER })
				}
			/>
		</>
	);
}
