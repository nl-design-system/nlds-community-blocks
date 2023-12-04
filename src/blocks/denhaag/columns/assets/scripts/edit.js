import classnames from 'classnames';
import { useLayoutEffect, useMemo } from '@wordpress/element';
import { BlockControls, InnerBlocks } from '@wordpress/editor';
import { _x } from '@wordpress/i18n';
import NCB_ColumnsLayoutControl from './controls/ncb-columns-layout-control';
import { useSelect } from '@wordpress/data';

export default function edit({ attributes, setAttributes, clientId }) {
	const _ALLOWED_INNERBLOCKS = ['ncb-denhaag/column'];

	const _CLASSES = useMemo(() => {
		return {
			root: classnames('editor-denhaag-columns', {
				[`has-${attributes.columns}-columns`]:
					!!attributes.columns && !!attributes.layout,
				[`editor-denhaag-columns--${attributes.layout}`]:
					!!attributes.layout,
				['has-no-layout']: !attributes.layout,
			}),
			layoutSelection: 'editor-denhaag-columns__layout-selection',
		};
	}, [attributes.columns, attributes.layout]);

	// Return array with _ALLOWED_INNERBLOCKS for the length of the column-length.
	const _TEMPLATE = useMemo(
		() =>
			Array.from(
				{ length: attributes.columns },
				() => _ALLOWED_INNERBLOCKS
			),
		[attributes.columns]
	);

	/**
	 * Returns the innerBlocks.
	 * @type {import("../../types").UseSelectReturn<function(*): *>}
	 * @return {int}
	 */
	const innerBlocksLength = useSelect(
		(select) => select('core/block-editor').getBlocks(clientId).length,
		[clientId]
	);

	useLayoutEffect(() => {
		if (innerBlocksLength < attributes.columns) {
			for (
				let i = 0, j = Math.abs(attributes.columns - innerBlocksLength);
				i < j;
				i++
			) {
				wp.data
					.dispatch('core/block-editor')
					.insertBlocks(
						wp.blocks.createBlock('ncb-denhaag/column', {}),
						innerBlocksLength + i,
						clientId
					);
			}
		}
	}, [attributes.columns, innerBlocksLength]);

	return (
		<>
			<BlockControls>
				<NCB_ColumnsLayoutControl
					value={attributes.layout}
					setAttributes={setAttributes}
					inToolbar={true}
				/>
			</BlockControls>

			<section className={_CLASSES.root}>
				{attributes.layout ? (
					<InnerBlocks
						allowedBlocks={_ALLOWED_INNERBLOCKS}
						orientation="horizontal"
						template={_TEMPLATE}
					/>
				) : (
					<div className={_CLASSES.layoutSelection}>
						<h2 className="denhaag-heading">
							{_x(
								'Please select a layout',
								'ncb-denhaag/columns: Title',
								'nlds-community-blocks'
							)}
						</h2>
						<NCB_ColumnsLayoutControl
							value={attributes.layout}
							setAttributes={setAttributes}
						/>
					</div>
				)}
			</section>
		</>
	);
}
