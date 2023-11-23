import {_x} from '@wordpress/i18n';
import {
  BlockControls,
  InnerBlocks,
  RichText,
} from '@wordpress/block-editor';
import {useMemo} from '@wordpress/element';
import classNames from 'classnames';
import NCB_HeadingTagControl from '../../../../../editor/ncb-heading-tag-control';

export default function edit({attributes, setAttributes}) {
  const _CLASSES = useMemo( () => {
    return {
      root: classNames( 'denhaag-link-group', 'denhaag-highlighted-links' ),
      caption: classNames( 'denhaag-link-group__caption', {
        [`utrecht-heading-${attributes.tagShownAs}`]: !!attributes.tagShownAs,
      } ),
      list: classNames( 'utrecht-link-list', 'utrecht-link-list--html-ul', 'denhaag-link-group__list', 'denhaag-highlighted-links__list' )
    };
  }, [
    attributes.tagShownAs,
  ] );

  return (
    <>
      <BlockControls>
        <NCB_HeadingTagControl
          value={attributes.tag}
          allowedTags={attributes.allowedTags}
          setAttributes={setAttributes}
        />
        <NCB_HeadingTagControl
          value={attributes.tagShownAs}
          attribute="tagShownAs"
          allowedTags={attributes.allowedTags}
          setAttributes={setAttributes}
        />
      </BlockControls>

      <div className={_CLASSES.root}>
        <RichText
          tagName={`h${attributes.tag}`}
          className={_CLASSES.caption}
          value={attributes.caption}
          placeholder={_x(
            'Place your caption here',
            'ncb-denhaag/highlighted-links: Caption placeholder',
            'nlds-community-blocks'
          )}
          onChange={(caption) => setAttributes( {caption} )}
          allowedFormats={[]}
        />
        <ul className={_CLASSES.list}>
          <InnerBlocks
            allowedBlocks={['ncb-denhaag/link-group-item']}
            template={[['ncb-denhaag/link-group-item', {}]]}
            templateLock={false}
          />
        </ul>
      </div>
    </>
  );
}
