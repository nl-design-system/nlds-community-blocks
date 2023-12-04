import { InnerBlocks } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { useLayoutEffect } from '@wordpress/element';

export default function edit({ clientId }) {
	/**
	 * Returns the amount of innerBlocks.
	 * @type {import("../../types").UseSelectReturn<function(*): *>}
	 * @return {int}
	 */
	const innerBlocksLength = useSelect(
		(select) => select('core/block-editor').getBlocks(clientId).length,
		[clientId]
	);

	useLayoutEffect(() => {
		if (0 === innerBlocksLength) {
			// Forcefully appends a new block when deleting the last innerBlock.
			wp.data
				.dispatch('core/block-editor')
				.insertBlocks(
					wp.blocks.createBlock('ncb-denhaag/social-link', {}),
					0,
					clientId
				);
		}
	}, [innerBlocksLength]);

	return (
		<nav className="denhaag-socials">
			<InnerBlocks
				allowedBlocks={['ncb-denhaag/social-link']}
				template={[['ncb-denhaag/social-link', {}]]}
				templateLock={false}
				templateInsertUpdatesSelection={true}
			/>
		</nav>
	);
}
