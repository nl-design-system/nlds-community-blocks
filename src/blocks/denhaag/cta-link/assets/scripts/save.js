import { RichText } from "@wordpress/block-editor";
import { useMemo } from "@wordpress/element";

export default function Save( { attributes } ) {

    const _CLASSES = () => {
        switch( attributes.type ) {
            case 'video':
                return {
                    firstLabel: 'denhaag-cta-link__highlight',
                    secondLabel:'denhaag-cta-link__label',
                }
            case 'link':
            default:
                return {
                    firstLabel: 'denhaag-cta-link__label',
                    secondLabel: 'denhaag-cta-link__highlight',
                }
        }
    };

    return (
        <>
            <RichText.Content
                value={ attributes.firstLabel }
                tagName="span"
                className={_CLASSES.firstLabel}
            />
            <RichText.Content
                value={ attributes.secondLabel }
                tagName="span"
                className={_CLASSES.secondLabel}
            />
        </>
    );
}

