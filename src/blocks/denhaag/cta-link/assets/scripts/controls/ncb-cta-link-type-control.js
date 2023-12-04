import { useMemo } from "@wordpress/element";
import { ToolbarButton, ToolbarGroup } from "@wordpress/components";
import { _x } from "@wordpress/i18n";
import { page, video } from '@wordpress/icons';

/**
 * Returns the CTA Link Type Toolbar Controller.
 *
 * @param {string} type The type of the CTA Link.
 * @param {function} setAttributes The WordPress function to set attributes.
 * @return {unknown}
 * @constructor
 */
const NCB_CtaLinkTypeControls = ( { type = 'link', setAttributes } ) => {
    return useMemo( () => {
        return (
            <ToolbarGroup>
                <ToolbarButton
                    icon={ page }
                    label={ _x(
                        'Link',
                        "ncb-denhaag/cta-link: type control option",
                        "nlds-community-blocks"
                    ) }
                    onClick={ () => setAttributes( { type: 'link' } ) }
                    isPressed={ 'link' === type }
                />
                <ToolbarButton
                    icon={ video }
                    label={ _x(
                        'Video',
                        "ncb-denhaag/cta-link: type control option",
                        "nlds-community-blocks"
                    ) }
                    onClick={ () => setAttributes( { type: 'video' } ) }
                    isPressed={ 'video' === type }
                />
            </ToolbarGroup>
        );
    }, [ type ] );
}
export default NCB_CtaLinkTypeControls;
