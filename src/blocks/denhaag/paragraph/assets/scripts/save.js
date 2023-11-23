import { RichText } from "@wordpress/block-editor";
import classNames from "classnames";

export default function Save( { attributes } ) {

	const _CLASSES = classNames( 'utrecht-paragraph', {
		[ `utrecht-paragraph--${ attributes.variation }` ]: !! attributes.variation,
	} );

	return (
		<p className={ _CLASSES }>
			<RichText.Content value={ attributes.content } />
		</p>
	);
}
