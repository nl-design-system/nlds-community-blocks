import classNames from 'classnames';
import { InnerBlocks } from '@wordpress/block-editor';
import { useMemo, useLayoutEffect } from '@wordpress/element';
import { useSelect } from '@wordpress/data';

export default function edit({ clientId, attributes, setAttributes }) {
	/**
	 * Return object of classNames used in the block.
	 * @type {{root: string}}
	 * @private
	 */
	const _CLASSES = useMemo(() => {
		return {
			root: classNames('denhaag-button-group', {
				['denhaag-button-group--single']: 1 === attributes.amount,
				['denhaag-button-group--multiple']: 2 >= attributes.amount,
			}),
		};
	}, [attributes.amount]);

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
		setAttributes({ amount: innerBlocksLength });

		if (0 === innerBlocksLength) {
			// Forcefully appends a new block when deleting the last innerBlock.
			wp.data
				.dispatch('core/block-editor')
				.insertBlocks(
					wp.blocks.createBlock('ncb-denhaag/button', {}),
					0,
					clientId
				);
		}
	}, [innerBlocksLength]);

	const orientation = window.matchMedia(
		'(min-width: 480px) AND (orientation: landscape)'
	).matches
		? 'horizontal'
		: 'vertical';

	return (
		<div className={_CLASSES.root}>
			<InnerBlocks
				allowedBlocks={['ncb-denhaag/button']}
				orientation={orientation}
				template={[['ncb-denhaag/button', {}, []]]}
				templateInsertUpdatesSelection={true}
			/>
		</div>
	);
}
