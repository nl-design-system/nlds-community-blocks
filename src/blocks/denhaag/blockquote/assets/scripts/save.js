import { RichText } from "@wordpress/block-editor";
import classNames from "classnames";
import { _x } from "@wordpress/i18n";

export default function Save( { attributes } ) {
	return (
		<figure className="denhaag-blockquote">
			<blockquote
				className="denhaag-blockquote__content"
				cite={
					attributes.link && attributes.link.url
						? attributes.link.url
						: null
				}
			>
				<RichText.Content tagName="p" value={ attributes.quote } />
			</blockquote>
			{ !! attributes.hasAuthor && (
				<RichText.Content
					tagName="figcaption"
					className="denhaag-blockquote__attribution"
					value={ attributes.author }
				/>
			) }
		</figure>
	);
}
