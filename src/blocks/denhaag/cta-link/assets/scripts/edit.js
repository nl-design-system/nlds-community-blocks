import { BlockControls, RichText } from "@wordpress/block-editor";
import { useState, useMemo } from "@wordpress/element";
import { _x, __ } from "@wordpress/i18n";
import NCB_Editor_Notices from "../../../../../editor/ncb-editor-notices";
import NCB_LinkControls from "../../../../../editor/ncb-link-control";
import RenderIcon from './renders/render-icon';
import NCB_CtaLinkTypeControls from "./controls/ncb-cta-link-type-control";

export default function Edit( { attributes, setAttributes, clientId, isSelected } ) {
    const {
        lockPostSaving,
        unlockPostSaving
    } = wp.data.dispatch( 'core/editor' );
    const { createNotice, removeNotice } = wp.data.dispatch( 'core/notices' );
    const [ locks, setLocks ] = useState( {} );

    /**
     * Lock/Unlock the save button and show notices.
     *
     * @param {boolean} lockIt Lock the editor.
     * @param {string} clientId The client ID of the block.
     * @param {string} type The type of the notices, eq: success, error, warning, info.
     * @param {string} handle The ID of the notice, used for the notices loop;
     * @param {string} message The message to show to the user.
     * @param {boolean} isDismissible if the notice is dismissible.
     * @param {array} noticeActions The actions to show in the Combined Notice.
     */
    const lock = ( lockIt = false, clientId, type = 'error', message = '', noticeActions = [], handle = type, isDismissible = true ) => {
        if ( '' === handle || '' === message || ! clientId ) {
            return;
        }

        if ( lockIt ) {
            if ( ! locks.hasOwnProperty( `${ clientId }--${ handle }` ) || ! locks[ `${ clientId }--${ handle }` ] ) {
                setLocks( {
                    ...locks,
                    [ `${ clientId }--${ handle }` ]: { type: type, message: message, actions: noticeActions }
                } );
                lockPostSaving( `${ clientId }--${ handle }` );
                createNotice( type, message, {
                    id: `${ clientId }--${ handle }`,
                    isDismissible: isDismissible,
                    actions: [
                        {
                            label: __( 'Scroll to block', 'nl-design-system-editor' ),
                            onClick: () => document.getElementById( `block-${ clientId }` ).scrollIntoView( {
                                behavior: "smooth",
                                block: "start",
                                inline: "nearest"
                            } ),
                        }
                    ]
                } );
            }
        } else if ( locks.hasOwnProperty( `${ clientId }--${ handle }` ) || locks[ `${ clientId }--${ handle }` ] ) {
            maybeUnlockEditor( `${ clientId }--${ handle }`, locks );
        } else if ( ! lockIt && 0 === Object.keys( locks ).length ) {
            unLockEditor( `${ clientId }--${ handle }` );
        }
    }

    /**
     * Check if it must unlock the editor. This is done by checking if one of the handles is still in the locks array.
     * uses the useState locks/setLocks to remove the current handle from the array.
     * @param {string} handle The handle to check.
     * @param {object} locks The locks object.
     */
    const maybeUnlockEditor = ( handle, locks = {} ) => {
        setLocks( Array.from( locks ).filter( item => item[ handle ] !== handle ) );
        unLockEditor( handle );
    }

    /**
     * Unlock the editor and remove the notice.
     * @param {string} handle The handle to unlock.
     */
    const unLockEditor = handle => {
        unlockPostSaving( handle );
        removeNotice( handle );
    }

    // Handle all the locks.
    lock(
        ! attributes.hasOwnProperty( 'link' ) && ! isSelected,
        clientId,
        'error',
        _x( "No link has been selected. Please enter a URL for the CTA Link block.", "ncb-denhaag/cta-link: No URL", "nlds-community-blocks" ),
        [],
        'file-not-found',
        false
    );

    const _CLASSES = useMemo( () => {
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
    }, [ attributes.type] );

    return (
        <>
            <BlockControls>
                <NCB_LinkControls
                    value={ attributes.link }
                    setAttributes={ setAttributes }
                    options={ { hasTextControl: false } }
                />
                <NCB_CtaLinkTypeControls type={ attributes.type } setAttributes={ setAttributes } />
            </BlockControls>

            <NCB_Editor_Notices locks={ locks } />

            <div className="denhaag-cta-link">
                <div className="denhaag-cta-link__dot">
                    <RenderIcon icon={ attributes.type } />
                </div>
                <p className="denhaag-cta-link__excerpt">
                    <RichText
                        tagName="span"
                        className={_CLASSES.firstLabel}
                        placeholder={ _x(
                            'Label',
                            'CTA link placeholder',
                            'nl-design-system-editor'
                        ) }
                        value={ attributes.firstLabel }
                        onChange={ ( firstLabel ) => setAttributes( { firstLabel } ) }
                        allowedFormats={ [] }
                    />
                    <RichText
                        tagName="span"
                        className={_CLASSES.secondLabel}
                        placeholder={ _x(
                            'Highlight here',
                            'CTA link placeholder',
                            'nl-design-system-editor'
                        ) }
                        value={ attributes.secondLabel }
                        onChange={ ( secondLabel ) => setAttributes( { secondLabel } ) }
                        allowedFormats={ [] }
                    />
                </p>
            </div>
        </>
    )
}
