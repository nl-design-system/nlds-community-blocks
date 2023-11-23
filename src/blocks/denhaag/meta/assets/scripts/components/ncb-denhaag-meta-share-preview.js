import {useMemo} from "@wordpress/element";
import {sprintf, _x} from "@wordpress/i18n";
import ncb_to_dom_attributes from '../../../../../../editor/ncb-to-dom-attributes';
import ncb_denhaag_icon from  '../../../../../../editor/ncb-denhaag-icon';


/**
 * Return HTML b
 *
 * @param {boolean} showMeta Show or hide the HTML.
 * @return {HTMLElement}
 * @constructor
 */
const NCB_DenhaagMetaSharePreview = ({showMeta = false}) => {
  const {buttons: config} = window?.ncb_editor_variables?.denhaag?.meta;

  return useMemo( () => {
    return (
      <div className="denhaag-meta__buttons">
        {!!config.readspeaker && <div className="denhaag-meta__readspeaker">ReadSpeaker</div>}
        {!!config.share && (
          <div className="denhaag-meta__share"
               dangerouslySetInnerHTML={
                 {
                   __html: sprintf(
                     '<button %1$s><span class="denhaag-button__icon">%2$s</span>%3$s</button>',
                     ncb_to_dom_attributes( {
                       'id': 'denhaag-share-button',
                       'aria-expanded': 'false',
                       'aria-controls': 'denhaag-meta-share',
                       'aria-haspopup': 'true',
                       'class': [
                         'denhaag-button',
                         'denhaag-button--secondary-action',
                         'denhaag-button--start-icon',
                       ],
                     } ),
                     ncb_denhaag_icon( 'share' ),
                     _x( 'Share', 'ncb-denhaag/meta: Share button label', 'nlds-community-blocks' )
                   )
                 }
               }
          >
          </div>
        )}
      </div>
    );
  }, [config] );
};
export default NCB_DenhaagMetaSharePreview;
