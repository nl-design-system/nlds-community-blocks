import {_x} from '@wordpress/i18n';
import {
  BlockControls,
  RichText,
} from '@wordpress/block-editor';
import {useLayoutEffect, useMemo} from '@wordpress/element';
import {ReactComponent as ArrowRightIcon} from '../icons/arrow-right.svg';
import {ReactComponent as ExternalLinkIcon} from '../icons/external-icon.svg';
import NCB_LinkControls from "../../../../../editor/ncb-link-control";
import NCB_SetLinkControl from "../../../../../editor/ncb-link-control/assets/scripts/ncb-set-link-control";
import classNames from "classnames";

export default function Edit({attributes, setAttributes}) {

  /**
   * Check if is external url.
   *
   * @type {boolean}
   */
  const isExternal = useMemo( () => {
    if ( ! attributes.link?.url ) {
      return false;
    }

    return 'URL' === attributes.link?.type && attributes.link?.url && ! attributes.link.url.includes( window.location.origin );
  }, [ attributes.link ] );

  useLayoutEffect( () => {
    if (attributes.text && attributes.text !== attributes.link.title) {
      const newLink = attributes.link;
      newLink.title = attributes.text;

      setAttributes( {link: newLink} );
    }
  }, [attributes.text] );

  const _CLASSES = useMemo( () => {
    return {
      root: classNames('denhaag-link-group__list-item', {
        ['denhaag-link-group__list-item--no-link']: (!attributes.hasOwnProperty( 'link' ) || !attributes.link)
      }),
      instruction: 'denhaag-link-group__list-item-instruction',
      iconWrapper: 'denhaag-link__icon',
      icon: 'denhaag-icon',
      link: classNames( 'denhaag-link', 'denhaag-link--with-icon', 'denhaag-link--with-icon-start' ),
      linkLabel: 'denhaag-link__label'
    }
  }, [ attributes.link] );

  return (
    <>
      <BlockControls>
        <NCB_LinkControls value={attributes.link} setAttributes={setAttributes} />
      </BlockControls>
      <li className={_CLASSES.root}>
        {(!attributes.hasOwnProperty( 'link' ) || !attributes.link) ? (
          <>
            <NCB_SetLinkControl
              value={attributes.link}
              setAttributes={setAttributes}
              options={{
                hasRichPreviews: true,
                hasTextControl: true,
                showSuggestions: true,
                createSuggestionButtonText: true,
                settings: false
              }}
            />
            <span className={_CLASSES.instruction}>
						{_x( 'Click the icon to add a link.', 'denhaag/link-group-item: Instruction', 'nlds-community-blocks' )}
						</span>
          </>
        ) : (
          <a
            href={attributes?.link?.url}
            className={_CLASSES.link}
            onClick={(event) => event.preventDefault()}
          >
					<span className={_CLASSES.iconWrapper}>
						{isExternal ? <ExternalLinkIcon className={_CLASSES.icon} /> : <ArrowRightIcon className={_CLASSES.icon} />}
					</span>
            <RichText
              tagName="span"
              value={attributes?.link?.title}
              className={ _CLASSES.linkLabel }
              placeholder={
                _x(
                  'Start typing…',
                  'denhaag/link-group: Control label',
                  'nlds-community-blocks'
                )
              }
              onChange={(text) => setAttributes( {text} )}
              allowedFormats={[]}
              allowedBlocks={[]}
              multiline={false}
              onReplace={() => {
              }}
              onSplit={() => {
              }}
            />
          </a>
        )}
      </li>
    </>
  );
}
