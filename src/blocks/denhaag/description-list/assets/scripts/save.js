import { RichText, InnerBlocks } from "@wordpress/block-editor";

export default function Save( { attributes } ) {
	return (
		<>
			{ !! attributes.caption && (
				<RichText.Content
					className="denhaag-description-list-caption"
					id={ attributes.id }
					value={ attributes.caption }
					tagName="h3"
				/>
			) }
			<dl className="denhaag-description-list" aria-labelledby={ !! attributes.caption ? attributes.id : null }>
				<InnerBlocks.Content />
			</dl>
		</>
	);
}
