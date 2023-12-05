import { RichText } from "@wordpress/block-editor";

export default function Save( { attributes } ) {

    let _CLASSES = {
        firstLabel: 'denhaag-cta-link__label',
        secondLabel: 'denhaag-cta-link__highlight'
    };

    if ( 'video' === attributes.type ) {
        _CLASSES = {
            firstLabel: 'denhaag-cta-link__highlight',
            secondLabel: 'denhaag-cta-link__label',
        };
    }

    return (
        <>
            { !! attributes.firstLabel && (
                <RichText.Content
                    value={ attributes.firstLabel }
                    tagName="span"
                    className={ _CLASSES.firstLabel }
                />
            ) }
            { !! attributes.secondLabel && (
                <RichText.Content
                    value={ attributes.secondLabel }
                    tagName="span"
                    className={ _CLASSES.secondLabel }
                />
            ) }
        </>
    );
}

