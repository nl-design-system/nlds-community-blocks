import { RichText } from "@wordpress/block-editor";

export default function Save( { attributes } ) {
	return (
		<>
			<RichText.Content value={ attributes.title } tagName="dt" className="denhaag-description-list__title"/>
			<RichText.Content value={ attributes.detail } tagName="dd" className="denhaag-description-list__detail"/>
		</>
	);
}
