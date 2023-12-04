import { _x } from '@wordpress/i18n';
import { InnerBlocks, RichText } from '@wordpress/block-editor';
import { useLayoutEffect, useMemo } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { ReactComponent as Icon } from '../icons/chevron.svg';
import classNames from 'classnames';

const { allowed_blocks: ALLOWED_BLOCKS } =
	window.ncb_editor_variables?.denhaag['accordion-items'];

export default function Edit({
	clientId,
	attributes,
	setAttributes,
	context,
	isSelected,
}) {
	/**
	 * Check if current element is parent of selected innerBlock.
	 * @returns {boolean}
	 * @type {import("../../types").UseSelectReturn<function(*): *>}
	 */
	const isParentOfSelectedBlock = useSelect(
		(select) =>
			select('core/block-editor').hasSelectedInnerBlock(clientId, true),
		[clientId]
	);

	const _CLASSES = useMemo(() => {
		return {
			root: classNames('denhaag-accordion__container', {
				'denhaag-accordion__container--open':
					!!isSelected || !!isParentOfSelectedBlock,
			}),
			panel: 'denhaag-accordion__panel',
			title: 'denhaag-accordion__title',
			details: 'denhaag-accordion__details',
			content: 'denhaag-accordion__details-content',
			icon: 'denhaag-icon',
		};
	}, [isSelected, isParentOfSelectedBlock]);

	/**
	 * Returns heading tag.
	 * @return {"h3", "h4"}
	 * @constructor
	 */
	const Heading = `h${
		context['ncb-denhaag/accordion-heading'] ?? attributes.heading
	}`;

	// Update heading tag on update.
	useLayoutEffect(() => {
		setAttributes({ heading: context['ncb-denhaag/accordion-heading'] });
	}, [context]);

	return (
		<div className={_CLASSES.root}>
			<Heading className={_CLASSES.panel}>
				<button
					aria-controls={clientId + `-content`}
					aria-expanded="false"
					className={_CLASSES.title}
					id={clientId}
				>
					<RichText
						allowedFormats={[]}
						value={attributes.title}
						placeholder={_x(
							'Panel title…',
							'denhaag/accordion-item: Placeholder',
							'nlds-community-blocks'
						)}
						onChange={(title) =>
							setAttributes({
								title: title.replace(/<[^>]*>?/gm, '').trim(),
							})
						}
					/>
				</button>
				<Icon className={_CLASSES.icon} />
			</Heading>
			<div
				aria-labelledby={clientId}
				className={_CLASSES.details}
				id={clientId + `-content`}
				role="region"
			>
				<div className={_CLASSES.content}>
					<InnerBlocks allowedBlocks={ALLOWED_BLOCKS} />
				</div>
			</div>
		</div>
	);
}
