import { _x, __ } from "@wordpress/i18n";
import { useLayoutEffect, useMemo, useState } from "@wordpress/element";
import { BlockControls, RichText } from "@wordpress/block-editor";
import NCB_File_Control from "./controls/ncb-file-control";
import NCB_Editor_Notices from "../../../../../editor/ncb-editor-notices";
import { ReactComponent as DownloadIcon } from "../icons/download.svg";

export default function Edit({ attributes, setAttributes, clientId, isSelected }) {
    const {
        lockPostSaving,
        unlockPostSaving
    } = wp.data.dispatch( 'core/editor' );
    const {createNotice, removeNotice} = wp.data.dispatch( 'core/notices' );
    const [locks, setLocks] = useState( {} );
    const [fileSize, setFileSize] = useState(0);
    const [fileType, setFileType] = useState('');

    /**
     * Returns the file data to display.
     * @type {string}
     */
    const readableEditorFileData = useMemo(() => {
        if (!fileType && !fileSize) {
            return null;
        }

        if (!fileType) {
            return `(${fileSize})`;
        }

        if (!fileSize) {
            return `(${fileType.toUpperCase()})`;
        }

        return `(${fileType.toUpperCase()}, ${fileSize})`;
    }, [fileType, fileSize]);

    /**
     * Fetch the file data.
     */
    useLayoutEffect(() => {
        wp.media.attachment(attributes.file).fetch().then((media) => {
            // Set the file type and size.
            setFileType(media.subtype);
            setFileSize(media.filesizeHumanReadable);

            // Set WordPress media attachment title if no label is set.
            if (!attributes.label) {
                setAttributes({ label: media.title });
            }
        }).catch((error) => {
            setFileType('');
            setFileSize(0);
        });
    }, [attributes.file]);

    useLayoutEffect(() => {
        if (!!attributes.label && !!attributes.label.includes('<meta charset="utf-8">')) {
            setAttributes({ label: attributes.label.replace(/<meta charSet="utf-8">/gmi, '').trim() });
        }
    }, [attributes.label]);

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
    const lock = (lockIt = false, clientId, type = 'error', message = '', noticeActions = [], handle = type, isDismissible = true) => {
        if ('' === handle || '' === message || !clientId) {
            return;
        }

        if (lockIt) {
            if ( ! locks.hasOwnProperty( `${clientId}--${handle}`) || !locks[`${clientId}--${handle}`] ) {
                setLocks( {...locks, [`${clientId}--${handle}`]: {type: type, message: message, actions: noticeActions}} );
                lockPostSaving( `${clientId}--${handle}` );
                createNotice( type, message, {
                    id: `${clientId}--${handle}`,
                    isDismissible: isDismissible,
                    actions: [
                        {
                            label: __( 'Scroll to block', 'nl-design-system-editor' ),
                            onClick: () => document.getElementById( `block-${clientId}` ).scrollIntoView( {
                                behavior: "smooth",
                                block: "start",
                                inline: "nearest"
                            } ),
                        }
                    ]
                } );
            }
        } else if ( locks.hasOwnProperty( `${ clientId }--${ handle }` ) || locks[ `${ clientId }--${ handle }` ] ) {
            maybeUnlockEditor( `${clientId}--${handle}`, locks );
        } else if (!lockIt && 0 === Object.keys( locks ).length) {
            unLockEditor( `${clientId}--${handle}` );
        }
    }

    /**
     * Check if it must unlock the editor. This is done by checking if one of the handles is still in the locks array.
     * uses the useState locks/setLocks to remove the current handle from the array.
     * @param {string} handle The handle to check.
     * @param {object} locks The locks object.
     */
    const maybeUnlockEditor = (handle, locks = {}) => {
        setLocks( Array.from( locks ).filter( item => item[handle] !== handle ) );
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
        ! attributes.file && ! isSelected,
        clientId,
        'error',
        _x( "No file has been selected. Please select a file for the CTA Download block.", "ncb-denhaag/cta-download: No file selected", "nlds-community-blocks" ),
        [],
        'file-not-found',
        false,
    );

    return (
        <>
            <BlockControls>
                <NCB_File_Control file={attributes.file} setAttributes={setAttributes} />
            </BlockControls>

            <NCB_Editor_Notices locks={locks} />

            <div className="denhaag-cta-download">
                <div className="denhaag-cta-link__dot">
                    <DownloadIcon />
                </div>
                <div className="denhaag-cta-download__excerpt">
                    <RichText
                        className="denhaag-cta-download__title"
                        withoutInteractiveFormatting
                        placeholder={_x('Your description here', 'ncb-denhaag/cta-download: Placeholder', 'nlds-community-blocks')}
                        value={attributes.label}
                        onChange={(label) => setAttributes({ label })}
                        allowedFormats={[]}
                    />
                    {readableEditorFileData}
                </div>
            </div>
        </>
    );
}
