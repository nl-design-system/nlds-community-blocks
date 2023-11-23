import { _x } from '@wordpress/i18n';
import { InnerBlocks, RichText } from '@wordpress/block-editor';
import { useLayoutEffect } from "@wordpress/element";
import { useSelect } from "@wordpress/data";

export default function edit( { attributes, setAttributes, clientId } ) {

	useLayoutEffect( () => {
		// Store clientId in `id` for the aria-labelledby when there`s a caption.
		setAttributes( { id: !! attributes.caption ? clientId : null } );
	}, [ attributes.caption, clientId ] );

	/**
	 * Returns the amount of innerBlocks.
	 * @type {import("../../types").UseSelectReturn<function(*): *>}
	 * @return {int}
	 */
	const innerBlocksLength = useSelect( ( select ) => select( 'core/block-editor' ).getBlocks( clientId ).length, [ clientId ] );

	useLayoutEffect( () => {
		if( 0 === innerBlocksLength ) {
			// Forcefully appends a new block when deleting the last innerBlock.
			wp.data.dispatch( 'core/block-editor' ).insertBlocks( wp.blocks.createBlock( 'ncb-denhaag/description-list-item', {} ), 0, clientId );
		}
	}, [ innerBlocksLength ] );

	return (
		<>
			<RichText
				tagName="h3"
				className="denhaag-description-list-caption"
				withoutInteractiveFormatting
				placeholder={ _x( 'Caption…', 'denhaag/description-list: Placeholder', 'nlds-community-blocks' ) }
				value={ attributes.caption }
				onChange={ ( caption ) => setAttributes( { caption } ) }
				allowedFormats={ [] }
				id={ clientId }
			/>

			<dl className="denhaag-description-list" aria-labelledby={ !! attributes.caption ? clientId : null }>
				<InnerBlocks
					allowedBlocks={ [ 'ncb-denhaag/description-list-item' ] }
					template={ [ [ 'ncb-denhaag/description-list-item', {} ] ] }
				/>
			</dl>
		</>
	);
}
