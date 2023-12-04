import { useMemo } from "@wordpress/element";
import { _x } from "@wordpress/i18n";
import { ToolbarButton, ToolbarGroup } from "@wordpress/components";
import { MediaUpload } from "@wordpress/block-editor";
import { upload as uploadIcon, close as removeIcon } from "@wordpress/icons";

/**
 * Returns the Button icon Toolbar Controller.
 *
 * @param {object} file The WordPress attachment ID.
 * @param {function} setAttributes setAttributes object of Gutenberg.
 * @return {unknown}
 * @constructor
 */
const NCB_File_Control = ( { file = {}, setAttributes } ) => {
    // On update `value` the controller will be rendered.
    return useMemo( () => {
        return (
            <ToolbarGroup>
                <MediaUpload
                    onSelect={ ( media ) => setAttributes( { file: media.id } ) }
                    multiple={ false }
                    render={ ( { open } ) => <ToolbarButton
                        onClick={ open }
                        icon={ uploadIcon }
                        isActive={ !! file }
                        label={ _x( 'Set file', 'ncb-denhaag/cta-download: remove file', 'nlds-community-blocks' ) } /> }
                />
                <ToolbarButton
                    onClick={ () => setAttributes( { file: null } ) }
                    icon={ removeIcon }
                    isDisabled={ ! file }
                    label={ _x( 'Remove file', 'ncb-denhaag/cta-download: remove file', 'nlds-community-blocks' ) } />
            </ToolbarGroup>
        );
    }, [ file ] );
};

export default NCB_File_Control;
