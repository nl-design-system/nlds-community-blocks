import { useMemo } from "@wordpress/element";
import { Notice } from "@wordpress/components";

/**
 * Returns the notices to show in the editor.
 *
 * @param locks
 * @return {*[]}
 * @constructor
 */
const NCB_Editor_Notices = ( { locks } ) => {
    /**
     * Combine all notices and show in the block.
     * @type {*[]}
     */
    return useMemo( () => {
        if ( ! locks || Object.keys( locks ).length === 0 ) {
            return;
        }

        return Object.values( locks ).map( ( { type, message, actions = [] } ) => <Notice status={ type }
                                                                                          actions={ actions }
                                                                                          isDismissible={ false }>{ message }</Notice> );
    }, [ locks ] );
};


export default NCB_Editor_Notices;
