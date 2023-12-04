import { RichText } from "@wordpress/block-editor";

export default function Save( { attributes } ) {
    return <RichText.Content value={ attributes.label } tagName="h3" className="denhaag-cta-download__title" />;
}

