import { ReactComponent as LinkIcon } from "../../icons/link.svg";
import { ReactComponent as VideoIcon } from "../../icons/video.svg";
import { useMemo } from "@wordpress/element";

/**
 * Returns the Button icon Toolbar Controller.
 *
 * @param {string} icon The Icon type.
 * @return {unknown}
 * @constructor
 */
const RenderIcon = ( { icon } ) => {
    // On update `value` the controller will be rendered.
    return useMemo( () => {
        switch ( icon ) {
            case 'video':
                return <VideoIcon />;
            case 'link':
            default:
                return <LinkIcon />;
        }
    }, [ icon ] );
};

export default RenderIcon;
