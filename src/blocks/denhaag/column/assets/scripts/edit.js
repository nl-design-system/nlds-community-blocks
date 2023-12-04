import { InnerBlocks } from '@wordpress/editor';
import { useMemo } from '@wordpress/element';

export default function edit({ clientId, context }) {
	const _CLASSES = 'denhaag-column';

	const blocks = wp.blocks.getBlockTypes();

	/**
	 * Returns the names of the blocks.
	 *
	 * @param {object} blocks the blocks we filter through.
	 * @param {number} columns amount of columns.
	 *
	 * @return {Object[]} List of block names.
	 */
	const _ALLOWED_BLOCKS = useMemo(() => {
		return blocks
			.filter((block) => {
				if (1 === context['ncb-denhaag/columns']) {
					return (
						!block.hasOwnProperty('parent') &&
						!block.name.endsWith('/column') &&
						!block.name.endsWith('/columns') &&
						(block.name.startsWith('core') ||
							block.name.startsWith('ncb-')) &&
						!(
							block.name.startsWith('ncb-') &&
							block.name.endsWith('-layout')
						)
					);
				}

				return (
					!block.hasOwnProperty('parent') &&
					!block.name.endsWith('/column') &&
					!block.name.endsWith('/columns') &&
					block.name !== 'ncb-denhaag/authentication' && // only at 1 column;
					block.name !== 'ncb-denhaag/cta-image-content' && // only at 1 column;
					(block.name.startsWith('core') ||
						block.name.startsWith('ncb-')) &&
					!(
						block.name.startsWith('ncb-') &&
						block.name.endsWith('-layout')
					)
				);
			})
			.map((block) => block.name);
	}, [context['ncb-denhaag/columns'], blocks]);

	return (
		<div className={_CLASSES}>
			<InnerBlocks allowedBlocks={_ALLOWED_BLOCKS} templateLock={false} />
		</div>
	);
}
