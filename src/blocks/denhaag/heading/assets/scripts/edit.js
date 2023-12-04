import { _x } from '@wordpress/i18n';
import { BlockControls, RichText } from '@wordpress/block-editor';
import NCB_HeadingScreenreaderControl from './controls/ncb-heading-screenreader-control';
import NCB_HeadingTagControl from '../../../../../editor/ncb-heading-tag-control';
import { useLayoutEffect, useMemo } from '@wordpress/element';
import classNames from 'classnames';

export default function Edit({ attributes, setAttributes, mergeBlocks }) {
	/**
	 * Return string of classNames which updates based on the variation.
	 *
	 * @type {string}
	 * @private
	 */
	const _CLASSES = useMemo(() => {
		return classNames({
			[`utrecht-heading-${attributes.appearance}`]:
				!!attributes.appearance,
			'sr-only': !!attributes.srOnly,
		});
	}, [attributes.appearance, !!attributes.srOnly]);

	const _PLACEHOLDER = _x(
		'Enter your heading…',
		'ncb-denhaag/heading: Placeholder',
		'nlds-community-blocks'
	);

	useLayoutEffect(() => {
		// Set back to default value.
		if (!!attributes.srOnly && 2 !== attributes.appearance) {
			setAttributes({ appearance: 2 });
		}

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
	}, [attributes.srOnly]);

	return (
		<>
			<BlockControls>
				<NCB_HeadingTagControl
					value={attributes.level}
					allowedLevels={attributes.allowedLevels}
					setAttributes={setAttributes}
				/>
				{!attributes.srOnly && (
					<NCB_HeadingTagControl
						attribute="appearance"
						value={attributes.appearance}
						allowedLevels={attributes.allowedLevels}
						setAttributes={setAttributes}
					/>
				)}
				<NCB_HeadingScreenreaderControl
					value={attributes.srOnly}
					setAttributes={setAttributes}
				/>
			</BlockControls>

			<RichText
				identifier="content"
				tagName={`h${attributes.level}`}
				value={attributes.content}
				onChange={(text) =>
					setAttributes({ content: text.replace(/<[^>]*>?/gm, '') })
				}
				onRemove={() => onReplace([])}
				onMerge={mergeBlocks}
				className={_CLASSES}
				allowedFormats={[]}
				placeholder={_PLACEHOLDER}
				unstableOnFocus={() => setAttributes({ placeholder: '' })}
				unstableOnBlur={() =>
					setAttributes({ placeholder: _PLACEHOLDER })
				}
				multiline={false}
				onReplace={() => {}}
			/>
		</>
	);
}
